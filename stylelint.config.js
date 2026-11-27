export default {
	extends: ['@10up/stylelint-config'],
	ignoreFiles: ['dist/**', 'vendor/**', 'node_modules/**'],
	rules: {
		'scale-unlimited/declaration-strict-value': null,
	},
};
