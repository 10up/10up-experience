/**
 * esbuild config for @wordpress/boot route modules.
 *
 * Builds ES modules that use CJS shims for window.wp.* globals while keeping
 * @wordpress/boot and @wordpress/route as bare ESM imports (resolved by WP's
 * import map at runtime).
 *
 * Usage:
 *   node esbuild.boot.mjs          # one-off build
 *   node esbuild.boot.mjs --watch  # watch mode
 */

import { build, context } from 'esbuild';
import { createHash } from 'crypto';
import { writeFileSync, readFileSync, mkdirSync } from 'fs';
import { join } from 'path';

const isWatch = process.argv.includes('--watch');

// ── Pages config — add new boot pages here ──────────────────────────────
const PAGES = [{ name: 'settings', src: 'assets/js/admin-settings' }];

// Packages that remain as bare ESM imports (resolved by WP import map).
const ESM_EXTERNALS = new Set(['@wordpress/boot', '@wordpress/route']);

// Packages bundled from node_modules (not available as WP globals).
const BUNDLE_PACKAGES = new Set(['@wordpress/dataviews', '@wordpress/icons', '@wordpress/ui']);

// Vendor packages mapped to browser globals.
const VENDOR_GLOBALS = {
	react: { global: 'window.React', handle: 'react' },
	'react-dom': { global: 'window.ReactDOM', handle: 'react-dom' },
	'react/jsx-runtime': { global: 'window.ReactJSXRuntime', handle: 'react-jsx-runtime' },
};

/**
 * Derive the window global and WP script handle for a @wordpress/* package.
 *
 * @param {string} pkg Package name.
 */
function wpGlobal(pkg) {
	const name = pkg.replace('@wordpress/', '');
	const camel = name.replace(/-([a-z])/g, (_, c) => c.toUpperCase());
	return { global: `window.wp.${camel}`, handle: `wp-${name}` };
}

/**
 * esbuild plugin that redirects @wordpress/* and React imports to CJS shims
 * pointing at their browser globals, while keeping ESM_EXTERNALS as bare
 * import specifiers.
 */
const wpExternalsPlugin = {
	name: 'wp-externals',
	setup(pluginBuild) {
		// Keep ESM externals as bare imports.
		pluginBuild.onResolve({ filter: /^@wordpress\/(boot|route)$/ }, (args) => ({
			path: args.path,
			external: true,
		}));

		// Redirect @wordpress/* to CJS shim namespace (skip bundled packages).
		pluginBuild.onResolve({ filter: /^@wordpress\// }, (args) => {
			if (ESM_EXTERNALS.has(args.path)) return undefined;
			if ([...BUNDLE_PACKAGES].some((p) => args.path === p || args.path.startsWith(`${p}/`))) {
				return undefined;
			}
			return { path: args.path, namespace: 'wp-global' };
		});

		// Redirect React packages to CJS shim namespace.
		pluginBuild.onResolve({ filter: /^react(-dom)?(\/jsx-runtime)?$/ }, (args) => {
			if (VENDOR_GLOBALS[args.path]) {
				return { path: args.path, namespace: 'wp-global' };
			}
			return undefined;
		});

		// Load CJS shim: module.exports = <global>.
		pluginBuild.onLoad({ filter: /.*/, namespace: 'wp-global' }, (args) => {
			const info = VENDOR_GLOBALS[args.path] || wpGlobal(args.path);
			return { contents: `module.exports = ${info.global};`, loader: 'js' };
		});
	},
};

/**
 * Generate .asset.php manifests and an empty loader.js anchor module.
 *
 * @param {object} metafile esbuild metafile.
 * @param {string} outdir   Output directory.
 */
function generateAssets(metafile, outdir) {
	for (const [outFile, info] of Object.entries(metafile.outputs)) {
		if (!outFile.endsWith('.js')) continue;

		const deps = new Set();
		for (const inputPath of Object.keys(info.inputs)) {
			if (!inputPath.startsWith('wp-global:')) continue;
			const pkg = inputPath.replace('wp-global:', '');
			const { handle } = VENDOR_GLOBALS[pkg] || wpGlobal(pkg);
			deps.add(handle);
		}

		const content = readFileSync(outFile, 'utf8');
		const hash = createHash('md5').update(content).digest('hex').slice(0, 20);

		const depsStr = [...deps]
			.sort()
			.map((d) => `'${d}'`)
			.join(', ');
		const php = `<?php return array('dependencies' => array(${depsStr}), 'version' => '${hash}');`;
		writeFileSync(outFile.replace('.js', '.asset.php'), php);
	}

	mkdirSync(outdir, { recursive: true });
	writeFileSync(join(outdir, 'loader.js'), '// Empty module loader for page dependencies\n');
}

/**
 * Build (or watch) a single page.
 *
 * @param {{name: string, src: string}} page Page config.
 */
async function buildPage(page) {
	const outdir = `dist/modules/${page.name}`;
	const buildOptions = {
		entryPoints: [`${page.src}/content.js`, `${page.src}/route.js`],
		outdir,
		format: 'esm',
		bundle: true,
		metafile: true,
		jsx: 'automatic',
		loader: { '.js': 'jsx', '.css': 'css' },
		plugins: [wpExternalsPlugin],
		logLevel: 'info',
	};

	if (isWatch) {
		const ctx = await context({
			...buildOptions,
			plugins: [
				...buildOptions.plugins,
				{
					name: 'watch-assets',
					setup(pluginBuild) {
						pluginBuild.onEnd((result) => {
							if (result.metafile) generateAssets(result.metafile, outdir);
						});
					},
				},
			],
		});
		await ctx.watch();
		// eslint-disable-next-line no-console
		console.log(`Watching ${page.name} boot modules for changes…`);
	} else {
		const result = await build(buildOptions);
		generateAssets(result.metafile, outdir);
	}
}

await Promise.all(PAGES.map(buildPage));
