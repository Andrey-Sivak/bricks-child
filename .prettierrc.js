/**
 * Prettier Configuration for Bricks Child Theme
 * Uses WordPress coding standards
 */

module.exports = {
	// Formatting options
	useTabs: true,
	tabWidth: 4,
	printWidth: 80,
	semi: true,
	singleQuote: true,
	trailingComma: 'es5',
	bracketSpacing: true,
	bracketSameLine: false,
	arrowParens: 'always',
	endOfLine: 'lf',

	// WordPress specific
	parenSpacing: true,

	// Override for specific file types
	overrides: [
		{
			files: '*.json',
			options: {
				useTabs: false,
				tabWidth: 2,
				printWidth: 100,
			},
		},
		{
			files: [ '*.yml', '*.yaml' ],
			options: {
				useTabs: false,
				tabWidth: 2,
			},
		},
		{
			files: '*.md',
			options: {
				useTabs: false,
				tabWidth: 2,
				printWidth: 100,
				proseWrap: 'always',
			},
		},
		{
			files: [ '*.css', '*.scss' ],
			options: {
				singleQuote: false,
			},
		},
	],
};
