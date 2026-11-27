/**
 * Vite+ config for the Fueled Experience plugin.
 *
 * One `vp build` writes everything into dist/:
 * - the classic admin scripts and stylesheets (`build.rollupOptions.input`),
 * - the images under assets/img (`copyAssetsDir`),
 * - the `@wordpress/boot` admin pages: every directory under
 *   assets/js/admin-pages with a `content` entry is a page, built as Script
 *   Modules into dist/modules/<page>/, plus the PHP that registers them
 *   (dist/build.php).
 */
import tenup, { i18nRules } from '@10up/oxlint-config';
import { wp } from '@10up/wp-vite-plugins';
import { defineConfig } from 'vite-plus';

export default defineConfig({
	plugins: [
		wp({
			copyAssetsDir: './assets',
			// Lightning CSS, which keeps relative url()s as written: `../img/…`
			// from dist/css/ points at the copies in dist/img/.
			css: {},
			adminPagesDir: './assets/js/admin-pages',
			adminPages: {
				php: {
					// The package name starts with a digit, which isn't a valid PHP identifier.
					prefix: 'tenup-experience',
					textdomain: 'tenup',
				},
			},
		}),
	],
	build: {
		outDir: 'dist',
		rollupOptions: {
			input: {
				'js/notices': './assets/js/notices.js',
				'js/passwords': './assets/js/passwords.js',
				'css/passwords-styles': './assets/css/passwords.css',
				'css/admin': './assets/css/admin.css',
				'css/tenup-pages': './assets/css/tenup-pages.css',
			},
			output: {
				entryFileNames: '[name].js',
				// Code shared between admin pages (DataViews, the page layout).
				chunkFileNames: 'modules/chunks/[name]-[hash].js',
				assetFileNames: '[name][extname]',
			},
		},
	},
	lint: {
		extends: [tenup],
		rules: {
			...i18nRules('tenup'),
		},
		ignorePatterns: ['dist/**', 'vendor/**'],
	},
	staged: {
		'*.{js,jsx,ts,tsx}': 'vp lint',
		'*.css': 'stylelint --allow-empty-input',
		'*.php': './vendor/bin/phpcs --extensions=php --warning-severity=8 -s',
	},
});
