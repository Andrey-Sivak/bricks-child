/**
 * ESLint Configuration for Bricks Child Theme
 * Extends @wordpress/scripts default configuration
 */

module.exports = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],

	env: {
		browser: true,
		es2020: true,
		node: true,
		jquery: true,
	},

	globals: {
		wp: 'readonly',
		wpApiSettings: 'readonly',
		bricksChildData: 'readonly', // Custom global if you use wp_localize_script
	},

	parserOptions: {
		ecmaVersion: 2020,
		sourceType: 'module',
		ecmaFeatures: {
			jsx: true,
		},
	},

	rules: {
		// Customize rules if needed
		'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
		'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',

		// jQuery best practices
		'no-unused-vars': [ 'error', { args: 'none' } ],

		// Allow named exports
		'import/no-anonymous-default-export': 'off',

		// Prettier compatibility
		'prettier/prettier': [
			'error',
			{},
			{
				usePrettierrc: true,
			},
		],
	},

	settings: {
		'import/resolver': {
			node: {
				extensions: [ '.js', '.jsx' ],
			},
		},
		'import/core-modules': [ '@wordpress/i18n' ],
	},

	overrides: [
		{
			// Configuration for admin scripts
			files: [ '**/admin.js', '**/admin/**/*.js' ],
			env: {
				browser: true,
				node: false,
			},
		},
	],
};
