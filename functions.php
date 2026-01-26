<?php
/**
 * Bricks Child Theme Functions
 *
 * @package Bricks_Child
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define theme constants
 */
define( 'BRICKS_CHILD_VERSION', '1.0.0' );
define( 'BRICKS_CHILD_DIR', get_stylesheet_directory() );
define( 'BRICKS_CHILD_URI', get_stylesheet_directory_uri() );
define( 'BRICKS_CHILD_PATH', get_stylesheet_directory() . '/' );

/**
 * Load Composer autoloader if available
 */
if ( file_exists( BRICKS_CHILD_DIR . '/vendor/autoload.php' ) ) {
	require_once BRICKS_CHILD_DIR . '/vendor/autoload.php';
}

/**
 * Load theme includes
 */
require_once BRICKS_CHILD_DIR . '/inc/enqueue.php';
require_once BRICKS_CHILD_DIR . '/inc/i18n.php';
require_once BRICKS_CHILD_DIR . '/inc/templates.php';

/**
 * Theme setup
 *
 * @return void
 */
function bricks_child_setup() {
	/**
	 * Translations in the /languages/ directory.
	 */
	load_child_theme_textdomain( 'bricks-child', BRICKS_CHILD_DIR . '/languages' );

	/**
	 * Add theme support for various features if needed
	 * Note: Bricks already adds most supports, only add if you need additional ones
	 */
	// add_theme_support( 'custom-logo' );
	// add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'bricks_child_setup' );

/**
 * Register custom image sizes if needed
 *
 * @return void
 */
function bricks_child_image_sizes() {
	// Example: add_image_size( 'custom-thumb', 300, 200, true );
}
add_action( 'after_setup_theme', 'bricks_child_image_sizes' );

/**
 * Enqueue admin styles and scripts
 *
 * @param string $hook The current admin page hook.
 * @return void
 */
function bricks_child_admin_enqueue_scripts( $hook ) {
	$admin_asset_file = BRICKS_CHILD_DIR . '/assets/dist/admin.asset.php';

	if ( ! file_exists( $admin_asset_file ) ) {
		return;
	}

	$admin_asset = require $admin_asset_file;

	wp_enqueue_style(
		'bricks-child-admin',
		BRICKS_CHILD_URI . '/assets/dist/admin.css',
		array(),
		$admin_asset['version']
	);

	wp_enqueue_script(
		'bricks-child-admin',
		BRICKS_CHILD_URI . '/assets/dist/admin.js',
		$admin_asset['dependencies'],
		$admin_asset['version'],
		true
	);

	// Localize admin script if needed.
	wp_localize_script(
		'bricks-child-admin',
		'bricksChildAdmin',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'bricks_child_admin_nonce' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'bricks_child_admin_enqueue_scripts' );

/**
 * Add custom body classes
 *
 * @param array $classes Existing body classes.
 * @return array Modified body classes.
 */
function bricks_child_body_classes( $classes ) {
	// Add language class for styling purposes.
	$current_lang = function_exists( 'pll_current_language' ) ? pll_current_language() : get_locale();
	$classes[]    = 'lang-' . sanitize_html_class( $current_lang );

	// Add logged-in class if user is logged in.
	if ( is_user_logged_in() ) {
		$classes[] = 'user-logged-in';
	}

	return $classes;
}
add_filter( 'body_class', 'bricks_child_body_classes' );

/**
 * Disable Bricks parent theme CSS if completely overriding
 * WARNING: Only uncomment if you're replacing ALL parent styles
 *
 * @return void
 */
// function bricks_child_disable_parent_css() {
//  wp_dequeue_style( 'bricks-frontend' );
//  wp_deregister_style( 'bricks-frontend' );
// }
// add_action( 'wp_enqueue_scripts', 'bricks_child_disable_parent_css', 20 );

/**
 * Add custom query vars for custom templates
 *
 * @param array $vars Existing query vars.
 * @return array Modified query vars.
 */
function bricks_child_query_vars( $vars ) {
	// Example: $vars[] = 'custom_var';
	return $vars;
}
add_filter( 'query_vars', 'bricks_child_query_vars' );

/**
 * Modify excerpt length
 *
 * @param int $length Current excerpt length.
 * @return int Modified excerpt length.
 */
function bricks_child_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'bricks_child_excerpt_length' );

/**
 * Register custom REST API endpoints if needed
 *
 * @return void
 */
function bricks_child_register_rest_routes() {
	// Example REST endpoint.
	register_rest_route(
		'bricks-child/v1',
		'/example',
		array(
			'methods'             => 'GET',
			'callback'            => 'bricks_child_rest_example',
			'permission_callback' => '__return_true',
		)
	);
}
// add_action( 'rest_api_init', 'bricks_child_register_rest_routes' );

/**
 * Example REST API callback
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object or error.
 */
function bricks_child_rest_example( $request ) {
	return new WP_REST_Response(
		array(
			'success' => true,
			'data'    => array(
				'message' => __( 'Example endpoint', 'bricks-child' ),
			),
		),
		200
	);
}

/**
 * Security: Remove WordPress version from head
 *
 * @return string
 */
function bricks_child_remove_version() {
	return '';
}
add_filter( 'the_generator', 'bricks_child_remove_version' );

/**
 * Security: Disable XML-RPC
 *
 * @param bool $enabled Current XML-RPC status.
 * @return bool Modified XML-RPC status.
 */
function bricks_child_disable_xmlrpc( $enabled ) {
	return false;
}
// add_filter( 'xmlrpc_enabled', 'bricks_child_disable_xmlrpc' );

/**
 * Performance: Defer parsing of JavaScript
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @param string $src    The script source URL.
 * @return string Modified script tag.
 */
function bricks_child_defer_scripts( $tag, $handle, $src ) {
	// List of scripts to defer.
	$defer_scripts = array(
		'bricks-child',
	);

	if ( in_array( $handle, $defer_scripts, true ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'bricks_child_defer_scripts', 10, 3 );

/**
 * Allow SVG uploads
 * WARNING: Only enable if you trust all users who can upload files
 *
 * @param array $mimes Existing mime types.
 * @return array Modified mime types.
 */
function bricks_child_mime_types( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'bricks_child_mime_types' );

/**
 * Fix SVG thumbnails in media library
 *
 * @param array  $response   Array of prepared attachment data.
 * @param object $attachment Attachment object.
 * @param array  $meta       Array of attachment meta data.
 * @return array Modified attachment data.
 */
function bricks_child_fix_svg_thumb( $response, $attachment, $meta ) {
	if ( 'image/svg+xml' === $response['mime'] ) {
		$response['image'] = array(
			'src'    => $response['url'],
			'width'  => 150,
			'height' => 150,
		);
	}
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'bricks_child_fix_svg_thumb', 10, 3 );

/**
 * Custom login page styles
 *
 * @return void
 */
function bricks_child_login_styles() {
	?>
	<style type="text/css">
		body.login {
			/* Add custom login page styles */
		}
	</style>
	<?php
}
// add_action( 'login_enqueue_scripts', 'bricks_child_login_styles' );

/**
 * Change login logo URL
 *
 * @return string Site home URL.
 */
function bricks_child_login_logo_url() {
	return home_url();
}
// add_filter( 'login_headerurl', 'bricks_child_login_logo_url' );

/**
 * Change login logo title
 *
 * @return string Site name.
 */
function bricks_child_login_logo_url_title() {
	return get_bloginfo( 'name' );
}
// add_filter( 'login_headertext', 'bricks_child_login_logo_url_title' );

/**
 * Cleanup WordPress head
 *
 * @return void
 */
function bricks_child_cleanup_head() {
	// Remove RSD link.
	remove_action( 'wp_head', 'rsd_link' );
	// Remove Windows Live Writer manifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// Remove shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	// Remove feed links.
	// remove_action( 'wp_head', 'feed_links', 2 );
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
}
add_action( 'init', 'bricks_child_cleanup_head' );

/**
 * Limit post revisions
 *
 * @param int $num Number of revisions.
 * @return int Limited number of revisions.
 */
function bricks_child_limit_revisions( $num ) {
	return 5;
}
// add_filter( 'wp_revisions_to_keep', 'bricks_child_limit_revisions' );

/**
 * Add custom admin notice
 *
 * @return void
 */
function bricks_child_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( 'themes' !== $screen->id ) {
		return;
	}

	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<?php
			printf(
			/* translators: %s: Theme version */
				esc_html__( 'Bricks Child Theme v%s is active. Thank you for using our theme!', 'bricks-child' ),
				esc_html( BRICKS_CHILD_VERSION )
			);
			?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'bricks_child_admin_notice' );

/**
 * Debug helper: Log to debug.log
 *
 * @param mixed $data Data to log.
 * @return void
 */
function bricks_child_log( $data ) {
	if ( ! WP_DEBUG ) {
		return;
	}

	if ( is_array( $data ) || is_object( $data ) ) {
        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( print_r( $data, true ) );
	} else {
        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		error_log( $data );
	}
}

/**
 * Add theme info to site health debug info
 *
 * @param array $info Existing debug info.
 * @return array Modified debug info.
 */
function bricks_child_site_health_info( $info ) {
	$info['bricks-child'] = array(
		'label'  => __( 'Bricks Child Theme', 'bricks-child' ),
		'fields' => array(
			'version'        => array(
				'label' => __( 'Theme Version', 'bricks-child' ),
				'value' => BRICKS_CHILD_VERSION,
			),
			'parent_version' => array(
				'label' => __( 'Parent Theme Version', 'bricks-child' ),
				'value' => wp_get_theme()->parent()->get( 'Version' ),
			),
		),
	);

	return $info;
}
add_filter( 'debug_information', 'bricks_child_site_health_info' );