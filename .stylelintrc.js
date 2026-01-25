/**
 * Stylelint Configuration for Bricks Child Theme
 * Extends @wordpress/scripts default configuration
 */

module.exports = {
    extends: [
        '@wordpress/stylelint-config/scss',
    ],

    rules: {
        // Customize rules if needed
        'no-descending-specificity': null,
        'no-duplicate-selectors': null,

        // SCSS specific
        'scss/at-import-partial-extension': null,
        'scss/operator-no-newline-after': null,

        // Allow vendor prefixes (autoprefixer handles this)
        'property-no-vendor-prefix': null,
        'value-no-vendor-prefix': null,

        // Color format
        'color-hex-length': 'short',
        'color-named': 'never',

        // Font family
        'font-family-name-quotes': 'always-where-recommended',

        // String quotes
        'string-quotes': 'single',

        // Selector specificity
        'selector-max-id': 1,
        'selector-max-compound-selectors': 4,
        'selector-max-specificity': '0,4,0',

        // Declaration order (optional, can be strict)
        'order/properties-alphabetical-order': null,

        // Allow empty sources (for entry files that only import)
        'no-empty-source': null,

        // WordPress specific
        'max-line-length': null,

        // Prettier compatibility
        'prettier/prettier': true,
    },

    ignoreFiles: [
        'node_modules/**/*',
        'vendor/**/*',
        'assets/dist/**/*',
        'build/**/*',
        '**/*.js',
        '**/*.json',
    ],
};