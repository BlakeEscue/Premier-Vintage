<?php

if ( ! class_exists( 'Lebe_PluginLoad' ) ) {
	class Lebe_PluginLoad {
		public $plugins = array();
		public $config  = array();
		
		public function __construct() {
			$this->plugins();
			$this->config();
			if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
				return false;
			}
			
			if ( function_exists( 'tgmpa' ) ) {
				tgmpa( $this->plugins, $this->config );
			}
			
		}
		
		public function plugins() {
			$theme_plugins_uri = get_template_directory_uri() . '/framework/plugins/';
			$this->plugins     = array(
				array(
					'name'               => 'Lebe Toolkit',
					'slug'               => 'lebe-toolkit',
					'source'             => $theme_plugins_uri . 'lebe-toolkit.zip',
					'required'           => true,
					'version'            => '1.2.1',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'WPBakery Page Builder',
					'slug'               => 'js_composer',
					'source'             => $theme_plugins_uri . 'js_composer.zip',
					'required'           => true,
					'version'            => '6.0.5',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => esc_url( '' ),
				),
				array(
					'name'               => 'Fami Responsive Visual Composer',
					'slug'               => 'fami-responsive-js-composer',
					'source'             => $theme_plugins_uri . 'fami-responsive-js-composer.zip',
					'required'           => true,
					'version'            => '',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => esc_url( '' ),
				),
				array(
					'name'     => 'WooCommerce',
					'slug'     => 'woocommerce',
					'required' => false,
					'image'    => esc_url( '' ),
				),
				array(
					'name'     => 'Fami Sales Popup', // The plugin name
					'slug'     => 'fami-sales-popup', // The plugin slug (typically the folder name)
					'required' => false, // If false, the plugin is only 'recommended' instead of required
					'image'    => esc_url( '' ),
				),
				array(
					'name'               => 'Fami Buy Together',
					'slug'               => 'fami-buy-together',
					'source'             => $theme_plugins_uri . 'fami-buy-together.zip',
					'required'           => false,
					'version'            => '1.0.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => esc_url( '' ),
				),
				array(
					'name'               => 'Ziss - WooCommerce Product Pinner',
					'slug'               => 'ziss',
					'source'             => $theme_plugins_uri . 'ziss.zip',
					'required'           => false,
					'version'            => '2.9',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'WooCommerce Product Filter',
					'slug'               => 'prdctfltr',
					'source'             => $theme_plugins_uri . 'prdctfltr.zip',
					'required'           => false,
					'version'            => '6.6.5',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => esc_url( '' ),
				),
				array(
					'name'               => 'Revolution Slider',
					'slug'               => 'revslider',
					'source'             => $theme_plugins_uri . 'revslider.zip',
					'required'           => false,
					'version'            => '6.1.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => esc_url( '' ),
				),
				array(
					'name'     => 'YITH WooCommerce Wishlist', // The plugin name
					'slug'     => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
					'required' => false, // If false, the plugin is only 'recommended' instead of required
					'image'    => esc_url( '' ),
				),
				array(
					'name'     => 'YITH WooCommerce Quick View', // The plugin name
					'slug'     => 'yith-woocommerce-quick-view', // The plugin slug (typically the folder name)
					'required' => false, // If false, the plugin is only 'recommended' instead of required
					'image'    => esc_url( '' ),
				),
				array(
					'name'     => 'Contact Form 7',
					'slug'     => 'contact-form-7',
					'required' => false,
					'image'    => esc_url( '' ),
				),
			);
		}
		
		public function config() {
			$this->config = array(
				'id'           => 'lebe',
				// Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',
				// Default absolute path to bundled plugins.
				'menu'         => 'lebe-install-plugins',
				// Menu slug.
				'parent_slug'  => 'themes.php',
				// Parent menu slug.
				'capability'   => 'edit_theme_options',
				// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,
				// Show admin notices or not.
				'dismissable'  => true,
				// If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',
				// If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,
				// Automatically activate plugins after installation or not.
				'message'      => '',
				// Message to output right before the plugins table.
			);
		}
	}
	
	
}
if ( ! function_exists( 'Lebe_PluginLoad' ) ) {
	function Lebe_PluginLoad() {
		new  Lebe_PluginLoad();
	}
}
add_action( 'tgmpa_register', 'Lebe_PluginLoad' );