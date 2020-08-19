<?php
/**
 * Plugin Name: Lebe Toolkit
 * Plugin URI:  http://famithemes.com
 * Description: lebe toolkit for lebe theme. Currently supports the following theme functionality: shortcodes, CPT.
 * Version:     1.2.1
 * Author:      Famithemes
 * Author URI:  http://www.famithemes.com
 * License:     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lebe-toolkit
 */

// Define path to this plugin file.
define( 'LEBE_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );

// Define url to this plugin file.
define( 'LEBE_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );

// Include function plugins if not include.
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function lebe_toolkit_load_textdomain() {
	load_plugin_textdomain( 'lebe-toolkit', false, LEBE_TOOLKIT_PATH . '/languages' );
}

add_action( 'init', 'lebe_toolkit_load_textdomain' );

// Add css and js for admin
function lebe_admin_register_scripts() {
	wp_enqueue_style( 'flexslider', plugins_url( '/assets/vendors/flexslider/flexslider.min.css', __FILE__ ) );
	wp_enqueue_style( 'lebe-toolkit-style', plugins_url( '/assets/css/admin.css', __FILE__ ) );
	wp_enqueue_script( 'lebe-toolkit-script', plugins_url( '/assets/js/admin.js', __FILE__ ), array( 'jquery' ), false, true );
}

add_action( 'admin_enqueue_scripts', 'lebe_admin_register_scripts' );

function lebe_toolkit_frontend_script() {
	wp_enqueue_script( 'flexslider', plugins_url( '/assets/vendors/flexslider/jquery.flexslider-min.js', __FILE__ ), array( 'jquery' ), false, true );
	wp_localize_script( 'lebe-toolkit-frontend-script', 'lebe_ajax_frontend', array(
		                                                  'ajaxurl'  => admin_url( 'admin-ajax.php' ),
		                                                  'security' => wp_create_nonce( 'lebe_ajax_frontend' ),
	                                                  )
	);
}

add_action( 'wp_enqueue_scripts', 'lebe_toolkit_frontend_script' );

if ( ! function_exists( 'lebe_toolkit_remove_wp_ver_css_js' ) ) {
	// remove wp version param from any enqueued scripts
	function lebe_toolkit_remove_wp_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		
		return $src;
	}
	
	add_filter( 'style_loader_src', 'lebe_toolkit_remove_wp_ver_css_js', 9999 );
	add_filter( 'script_loader_src', 'lebe_toolkit_remove_wp_ver_css_js', 9999 );
}

// Run shortcode in widget text
add_filter( 'widget_text', 'do_shortcode' );

// For WooCommerce
function lebe_toolkit_load_attributes_swatches() {
	if ( class_exists( 'WooCommerce' ) ) {
		$enable_attributes_swatches = lebe_toolkit_get_option( 'enable_attributes_swatches', true );
		if ( $enable_attributes_swatches ) {
			include_once( LEBE_TOOLKIT_PATH . '/includes/classes/woo-attributes-swatches/woo-term.php' );
			include_once( LEBE_TOOLKIT_PATH . '/includes/classes/woo-attributes-swatches/woo-product-attribute-meta.php' );
		}
		include_once( LEBE_TOOLKIT_PATH . '/includes/shortcode.php' );
	}
}

add_action( 'plugins_loaded', 'lebe_toolkit_load_attributes_swatches' );

// Load the core class.
include_once( LEBE_TOOLKIT_PATH . '/includes/classes/lebe-mapper/includes/core.php' );

// Instantiate an object of the core class.
Lebe_Mapper::initialize();

// Load functions
include_once( LEBE_TOOLKIT_PATH . '/includes/functions.php' );

// Register custom shortcodes
include_once( LEBE_TOOLKIT_PATH . '/includes/shortcode.php' );

// Register custom post types
include_once( LEBE_TOOLKIT_PATH . '/includes/post-types.php' );

// Register init

if ( ! function_exists( 'lebe_toolkit_init' ) ) {
	function lebe_toolkit_init() {
		include_once( LEBE_TOOLKIT_PATH . '/includes/init.php' );
	}
}

add_action( 'lebe_toolkit_init', 'lebe_toolkit_init' );

if ( ! function_exists( 'lebe_toolkit_install' ) ) {
	function lebe_toolkit_install() {
		do_action( 'lebe_toolkit_init' );
		
		if ( class_exists( 'PrdctfltrInit' ) && class_exists( 'WPBakeryShortCode' ) ) {
			include_once( LEBE_TOOLKIT_PATH . '/includes/fami-pf-composer.php' );
			include_once( LEBE_TOOLKIT_PATH . '/includes/shortcodes/fami-pf-shortcode.php' );
		}
	}
}
add_action( 'plugins_loaded', 'lebe_toolkit_install', 11 );

function lebe_toolkit_remove_type_attr( $tag, $handle ) {
	return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
}

add_filter( 'style_loader_tag', 'lebe_toolkit_remove_type_attr', 99, 2 );
add_filter( 'script_loader_tag', 'lebe_toolkit_remove_type_attr', 99, 2 );
