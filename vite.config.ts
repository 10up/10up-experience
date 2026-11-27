/**
 * Vite+ config for the 10up Experience plugin.
 *
 * One `vp build` writes the admin scripts and stylesheets
 * (`build.rollupOptions.input`) and the images under assets/img
 * (`copyAssetsDir`) into dist/.
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
