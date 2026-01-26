/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

/**
 * Custom Webpack Configuration
 * Extends @wordpress/scripts default configuration
 */
module.exports = {
	...defaultConfig,

	entry: {
		main: path.resolve( process.cwd(), 'src/js', 'main.js' ),
		style: path.resolve( process.cwd(), 'src/scss', 'main.scss' ),
		admin: path.resolve( process.cwd(), 'src/js', 'admin.js' ),
		'admin-style': path.resolve( process.cwd(), 'src/scss', 'admin.scss' ),
		header: path.resolve( process.cwd(), 'src/js', 'header.js' ),
	},

	// Output configuration
	output: {
		...defaultConfig.output,
		path: path.resolve( process.cwd(), 'assets/dist' ),
		filename: '[name].js',
		// Clean dist folder before each build
		clean: true,
	},

	externals: {
		'@wordpress/i18n': [ 'wp', 'i18n' ],
	},

	// Source maps for development
	devtool: process.env.NODE_ENV === 'production' ? false : 'source-map',

	// Development server configuration
	devServer: {
		...defaultConfig.devServer,
		allowedHosts: 'all',
		host: 'localhost',
		port: 8887,
		headers: {
			'Access-Control-Allow-Origin': '*',
		},
	},

	// Module rules
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,

			// Image assets
			{
				test: /\.(png|jpg|jpeg|gif|svg)$/i,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name][ext]',
				},
			},

			// Font assets
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/i,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]',
				},
			},
		],
	},

	// Performance hints
	performance: {
		hints: process.env.NODE_ENV === 'production' ? 'warning' : false,
		maxEntrypointSize: 512000,
		maxAssetSize: 512000,
	},
};
