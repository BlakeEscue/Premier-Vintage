<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
if ( ! class_exists( 'Lebe_ThemeOption' ) ) {
	class Lebe_ThemeOption {
		public $sidebars        = array();
		public $header_options  = array();
		public $product_options = array();
		
		public function __construct() {
			$this->get_sidebars();
			$this->get_footer_options();
			$this->get_header_options();
			$this->lebe_rev_slide_options_for_redux();
			$this->get_product_options();
			$this->init_settings();
			add_action( 'admin_bar_menu', array( $this, 'lebe_custom_menu' ), 1000 );
		}
		
		public function get_header_options() {
			$layoutDir      = get_template_directory() . '/templates/headers/';
			$header_options = array();
			
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'header-', '', $fileInfo['filename'] );
								$header_options[ $file_name ] = array(
									'title'   => $file_data['Name'],
									'preview' => get_template_directory_uri() . '/templates/headers/header-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->header_options = $header_options;
		}
		
		public function get_social_options() {
			$socials     = array();
			$all_socials = cs_get_option( 'user_all_social' );
			if ( $all_socials ) {
				foreach ( $all_socials as $key => $social ) {
					$socials[ $key ] = $social['title_social'];
				}
			}
			
			return $socials;
		}
		
		public function get_footer_options() {
			$footer_options = array(
				'default' => esc_html__( 'Default', 'lebe-toolkit' ),
			);
			$layoutDir      = get_template_directory() . '/templates/footers/';
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                    = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                    = str_replace( 'footer-', '', $fileInfo['filename'] );
								$footer_options[ $file_name ] = $file_data['Name'];
							}
						}
					}
				}
			}
			$this->footer_options = $footer_options;
		}
		
		/* GET REVOLOTION */
		public function lebe_rev_slide_options_for_redux() {
			$lebe_herosection_revolutions = array( '' => esc_html__( '--- Choose Revolution Slider ---', 'lebe-toolkit' ) );
			if ( class_exists( 'RevSlider' ) ) {
				global $wpdb;
				if ( shortcode_exists( 'rev_slider' ) ) {
					$rev_sql  = $wpdb->prepare(
						"SELECT *
                    FROM {$wpdb->prefix}revslider_sliders
                    WHERE %d", 1
					);
					$rev_rows = $wpdb->get_results( $rev_sql );
					if ( count( $rev_rows ) > 0 ) {
						foreach ( $rev_rows as $rev_row ):
							$lebe_herosection_revolutions[ $rev_row->alias ] = $rev_row->title;
						endforeach;
					}
				}
			}
			
			$this->herosection_options = $lebe_herosection_revolutions;
		}
		
		public function get_product_options() {
			$layoutDir       = get_template_directory() . '/woocommerce/product-styles/';
			$product_options = array();
			
			if ( is_dir( $layoutDir ) ) {
				$files = scandir( $layoutDir );
				if ( $files && is_array( $files ) ) {
					$option = '';
					foreach ( $files as $file ) {
						if ( $file != '.' && $file != '..' ) {
							$fileInfo = pathinfo( $file );
							if ( $fileInfo['extension'] == 'php' && $fileInfo['basename'] != 'index.php' ) {
								$file_data                     = get_file_data( $layoutDir . $file, array( 'Name' => 'Name' ) );
								$file_name                     = str_replace( 'content-product-style-', '', $fileInfo['filename'] );
								$product_options[ $file_name ] = array(
									'title'   => $file_data['Name'],
									'preview' => get_template_directory_uri() . '/woocommerce/product-styles/content-product-style-' . $file_name . '.jpg',
								);
							}
						}
					}
				}
			}
			$this->product_options = $product_options;
		}
		
		public function lebe_attributes_options() {
			$attributes     = array();
			$attributes_tax = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attribute_name                = 'pa_' . $attribute->attribute_name;
					$attributes[ $attribute_name ] = $attribute->attribute_label;
				}
			}
			
			return $attributes;
		}
		
		public function get_sidebars() {
			global $wp_registered_sidebars;
			$sidebars = array(
				'' => esc_html__( 'Default (Product Sidebar)', 'lebe-toolkit' )
			);
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}
			$this->sidebars = $sidebars;
		}
		
		public function lebe_custom_menu() {
			global $wp_admin_bar;
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'theme_option',
				'title' => esc_html__( 'Theme Options', 'lebe-toolkit' ),
				'href'  => admin_url( 'admin.php?page=lebe-toolkit' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}
		
		public function init_settings() {
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK SETTINGS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$settings = array(
				'menu_title'      => 'Theme Options',
				'menu_type'       => 'submenu', // menu, submenu, options, theme, etc.
				'menu_slug'       => 'lebe-toolkit',
				'ajax_save'       => true,
				'menu_parent'     => 'lebe_menu',
				'show_reset_all'  => true,
				'menu_position'   => 2,
				'framework_title' => '<a href="http://lebe.famithemes.com/" target="_blank"><img src="' . esc_url( LEBE_TOOLKIT_URL . 'assets/images/logo-backend.png' ) . '" alt=""></a> <small>by <a href="https://famithemes.com" target="_blank">FamiThemes</a></small>',
			);
			
			// ===============================================================================================
			// -----------------------------------------------------------------------------------------------
			// FRAMEWORK OPTIONS
			// -----------------------------------------------------------------------------------------------
			// ===============================================================================================
			$options = array();
			
			// ----------------------------------------
			// a option section for options overview  -
			// ----------------------------------------
			$options[] = array(
				'name'     => 'general',
				'title'    => esc_html__( 'General', 'lebe-toolkit' ),
				'icon'     => 'fa fa-wordpress',
				'sections' => array(
					array(
						'name'   => 'main_settings',
						'title'  => esc_html__( 'Main Settings', 'lebe-toolkit' ),
						'fields' => array(
							array(
								'id'        => 'lebe_logo',
								'type'      => 'image',
								'title'     => esc_html__( 'Logo', 'lebe-toolkit' ),
								'add_title' => esc_html__( 'Add Logo', 'lebe-toolkit' ),
								'desc'      => esc_html__( 'Add custom logo for your website.', 'lebe-toolkit' ),
							),
							array(
								'id'      => 'lebe_width_logo',
								'type'    => 'number',
								'default' => '103',
								'title'   => esc_html__( 'Width Logo', 'lebe-toolkit' ),
								'desc'    => esc_html__( 'Unit PX', 'lebe-toolkit' )
							),
							array(
								'id'      => 'lebe_main_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Main Color', 'lebe-toolkit' ),
								'default' => '#ffa749',
								'rgba'    => true,
							),
							array(
								'id'      => 'lebe_body_text_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Body Text Color', 'lebe-toolkit' ),
								'default' => '#999',
								'rgba'    => true,
							),
							array(
								'id'    => 'gmap_api_key',
								'type'  => 'text',
								'title' => esc_html__( 'Google Map API Key', 'lebe-toolkit' ),
								'desc'  => wp_kses( sprintf( __( 'Enter your Google Map API key. <a href="%s" target="_blank">How to get?</a>', 'lebe-toolkit' ), 'https://developers.google.com/maps/documentation/javascript/get-api-key' ), array(
									'a' => array(
										'href'   => array(),
										'target' => array()
									)
								) ),
							),
							array(
								'id'         => 'load_gmap_js_target',
								'type'       => 'select',
								'title'      => esc_html__( 'Load GMap JS On', 'lebe-toolkit' ),
								'options'    => array(
									'all_pages'      => esc_html__( 'All Pages', 'lebe-toolkit' ),
									'selected_pages' => esc_html__( 'Selected Pages', 'lebe-toolkit' ),
									'disabled'       => esc_html__( 'Don\'t Load Gmap JS', 'lebe-toolkit' ),
								),
								'default'    => 'all_pages',
								'dependency' => array( 'gmap_api_key', '!=', '' ),
							),
							array(
								'id'         => 'load_gmap_js_on',
								'type'       => 'select',
								'title'      => esc_html__( 'Select Pages To Load GMap JS', 'lebe-toolkit' ),
								'options'    => 'pages',
								'query_args' => array(
									'post_type'      => 'page',
									'orderby'        => 'post_date',
									'order'          => 'ASC',
									'posts_per_page' => - 1
								),
								'attributes' => array(
									'multiple' => 'multiple',
									'style'    => 'width: 500px; height: 125px;',
								),
								'class'      => 'chosen',
								'desc'       => esc_html__( 'Load Google Map JS on selected pages', 'lebe-toolkit' ),
								'dependency' => array(
									'gmap_api_key|load_gmap_js_target',
									'!=|==',
									'|selected_pages'
								),
							),
							array(
								'id'      => 'lebe_enable_lazy',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Lazy Load Images', 'lebe-toolkit' ),
								'default' => true,
								'desc'    => esc_html__( 'Enables lazy load to reduce page requests.', 'lebe-toolkit' ),
							),
							array(
								'id'      => 'animation_on_scroll',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Animation On Scroll', 'lebe-toolkit' ),
								'default' => false,
								'desc'    => esc_html__( 'If enabled, will active the animation of elements when scrolling. You also need to select the animation when scrolling the mouse for each element when editing the article', 'lebe-toolkit' ),
							),
							array(
								'id'      => 'enable_smooth_scroll',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Smooth Scroll', 'lebe-toolkit' ),
								'default' => false,
								'desc'    => esc_html__( 'Turn on if you want to smooth out when scrolling', 'lebe-toolkit' ),
							),
							array(
								'id'      => 'main_menu_res_break_point',
								'type'    => 'text',
								'title'   => esc_html__( 'Main Menu Responsive Break Point', 'lebe-toolkit' ),
								'default' => 1199,
								'desc'    => esc_html__( 'Break point of the main menu when resizing the browser. Ex: 991, 1199 ...', 'lebe-toolkit' )
							),
						),
					),
					array(
						'name'   => 'theme_js_css',
						'title'  => 'Customs JS',
						'fields' => array(
							array(
								'id'         => 'lebe_custom_js',
								'type'       => 'ace_editor',
								'title'      => esc_html__( 'Custom Js', 'lebe-toolkit' ),
								'attributes' => array(
									'data-theme' => 'twilight',  // the theme for ACE Editor
									'data-mode'  => 'javascript',     // the language for ACE Editor
								),
							),
						),
					),
				),
			);
			$options[] = array(
				'name'   => 'newsletter',
				'title'  => esc_html__( 'Newsletter Popup', 'lebe-toolkit' ),
				'icon'   => 'fa fa-envelope-o',
				'fields' => array(
					array(
						'id'      => 'enable_newsletter',
						'type'    => 'switcher',
						'title'   => esc_html__( 'Enable Newsletter', 'lebe-toolkit' ),
						'default' => true,
					),
					array(
						'id'         => 'lebe_newsletter_popup',
						'type'       => 'select',
						'title'      => esc_html__( 'Select Newsletter Popup', 'lebe-toolkit' ),
						'options'    => 'posts',
						'value'      => 'The title1 bar',
						'dependency' => array( 'enable_newsletter', '==', true ),
						'query_args' => array(
							'post_type'      => 'newsletter',
							'orderby'        => 'post_date',
							'order'          => 'ASC',
							'posts_per_page' => - 1
						),
					
					),
					array(
						'id'         => 'disable_on_mobile',
						'type'       => 'switcher',
						'title'      => esc_html__( 'On Mobile', 'lebe-toolkit' ),
						'default'    => false,
						'dependency' => array( 'enable_newsletter', '==', true ),
					),
				
				),
			);
			$options[] = array(
				'name'     => 'header',
				'title'    => esc_html__( 'Header Settings', 'lebe-toolkit' ),
				'icon'     => 'fa fa-folder-open-o',
				'sections' => array(
					array(
						'name'   => 'header_general_settings',
						'title'  => esc_html__( 'General Header Settings', 'lebe-toolkit' ),
						'fields' => array(
							array(
								'id'      => 'enable_sticky_menu',
								'type'    => 'select',
								'title'   => esc_html__( 'Sticky Header', 'lebe-toolkit' ),
								'options' => array(
									'none'  => esc_html__( 'Disable', 'lebe-toolkit' ),
									'smart' => esc_html__( 'Sticky Header', 'lebe-toolkit' ),
								),
								'default' => 'none',
							),
							array(
								'id'      => 'enable_topbar',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Topbar', 'lebe-toolkit' ),
								'default' => false,
							),
							array(
								'id'         => 'topbar-text',
								'type'       => 'text',
								'title'      => esc_html__( 'Text Topbar', 'lebe-toolkit' ),
								'dependency' => array( 'enable_topbar', '==', true ),
							),
							array(
								'id'         => 'background_color_topbar_text',
								'type'       => 'color_picker',
								'title'      => esc_html__( 'Background Color Topbar Text ', 'lebe-toolkit' ),
								'default'    => '#000',
								'rgba'       => true,
								'dependency' => array( 'enable_topbar', '==', true ),
							),
							array(
								'id'      => 'enable_header_search',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Header Search', 'lebe-toolkit' ),
								'default' => false,
							),
							array(
								'id'         => 'enable_header_wishlist',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Enable Header Wishlist Icon', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide wish list icon on menu', 'lebe-toolkit' ),
								'default'    => false,
								'on'         => esc_html__( 'Show', 'lebe-toolkit' ),
								'off'        => esc_html__( 'Hide', 'lebe-toolkit' ),
							),
							
							array(
								'id'      => 'lebe_used_header',
								'type'    => 'select_preview',
								'title'   => esc_html__( 'Header Layout', 'lebe-toolkit' ),
								'desc'    => esc_html__( 'Select a header layout', 'lebe-toolkit' ),
								'options' => $this->header_options,
								'default' => 'style-01_s_l_c_mn_c_icon_r',
							),
							array(
								'id'      => 'header_text_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Header Text Color', 'lebe-toolkit' ),
								'default' => '#000',
								'rgba'    => true,
							),
							array(
								'id'      => 'header_bg_color',
								'type'    => 'color_picker',
								'title'   => esc_html__( 'Header Background Color', 'lebe-toolkit' ),
								'default' => 'rgba(255,255,255,1)',
								'rgba'    => true,
							),
							array(
								'id'      => 'header_position',
								'type'    => 'select',
								'title'   => esc_html__( 'Header Type', 'lebe-toolkit' ),
								'options' => array(
									'relative' => esc_html__( 'Header No Transparent', 'lebe-toolkit' ),
									'absolute' => esc_html__( 'Header Transparent', 'lebe-toolkit' ),
								),
								'default' => 'relative',
							),
							array(
								'id'         => 'lebe_header_social',
								'title'      => esc_html__( 'Header Social', 'lebe' ),
								'type'       => 'select',
								'options'    => $this->get_social_options(),
								'attributes' => array(
									'multiple' => 'multiple',
								),
								'class'      => 'chosen',
								'dependency' => array(
									'lebe_used_header',
									'==',
									'style-07_mn_l_icon_l_lg_c_s_r_icon_r'
								),
							),
						),
					),
					array(
						'name'   => 'page_banner_settings',
						'title'  => esc_html__( 'Page Banner Settings', 'lebe-toolkit' ),
						'fields' => array(
							array(
								'id'      => 'page_banner_type',
								'type'    => 'select',
								'title'   => esc_html__( 'Banner Type', 'lebe-toolkit' ),
								'options' => array(
									'has_background' => esc_html__( 'Has Background', 'lebe-toolkit' ),
									'no_background'  => esc_html__( 'No Background ', 'lebe-toolkit' ),
								),
								'default' => 'has_background'
							),
							array(
								'id'         => 'page_banner_image',
								'type'       => 'background',
								'title'      => esc_html__( 'Banner Image', 'lebe-toolkit' ),
								'add_title'  => esc_html__( 'Upload', 'lebe-toolkit' ),
								'dependency' => array( 'page_banner_type', '==', 'has_background' ),
							),
							array(
								'id'         => 'colortext_banner_page',
								'type'       => 'color_picker',
								'title'      => esc_html__( 'Banner Text Color', 'lebe-toolkit' ),
								'default'    => '#ffffff',
								'rgba'       => true,
								'dependency' => array( 'page_banner_type', '==', 'has_background' ),
							),
							array(
								'id'         => 'page_banner_full_width',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Banner Full Width', 'lebe-toolkit' ),
								'default'    => true,
								'dependency' => array( 'page_banner_type', '==', 'has_background' ),
							),
							array(
								'id'      => 'page_height_banner',
								'type'    => 'number',
								'title'   => esc_html__( 'Banner Height', 'lebe-toolkit' ),
								'default' => '420'
							),
							array(
								'id'      => 'page_margin_top',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Top', 'lebe-toolkit' ),
								'default' => 0
							),
							array(
								'id'      => 'page_margin_bottom',
								'type'    => 'number',
								'title'   => esc_html__( 'Margin Bottom', 'lebe-toolkit' ),
								'default' => 0,
							),
						
						)
					),
					array(
						'name'   => 'header_mobile',
						'title'  => esc_html__( 'Header Mobile', 'lebe-toolkit' ),
						'fields' => array(
							array(
								'id'      => 'enable_header_mobile',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Header Mobile', 'lebe-toolkit' ),
								'default' => false,
							),
							array(
								'id'      => 'enable_header_mobile_sticky',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Header Mobile Sticky', 'lebe-toolkit' ),
								'default' => false,
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'lebe_mobile_logo',
								'type'       => 'image',
								'title'      => esc_html__( 'Mobile Logo', 'lebe-toolkit' ),
								'add_title'  => esc_html__( 'Add Mobile Logo', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Add custom logo for mobile. If no mobile logo is selected, the default logo will be used or custom logo if placed in the page', 'lebe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'lebe_width_mobile_logo',
								'type'       => 'number',
								'default'    => '75',
								'title'      => esc_html__( 'Width Logo', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Unit PX', 'lebe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_header_mini_cart_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Mini Cart Icon', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide header mini cart icon on mobile', 'lebe-toolkit' ),
								'default'    => true,
								'on'         => esc_html__( 'On', 'lebe-toolkit' ),
								'off'        => esc_html__( 'Off', 'lebe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_header_product_search_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Products Search Icon', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide header product search icon on mobile', 'lebe-toolkit' ),
								'default'    => true,
								'on'         => esc_html__( 'On', 'lebe-toolkit' ),
								'off'        => esc_html__( 'Off', 'lebe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_wishlist_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Wish List Icon', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide wish list icon on siding menu mobile', 'lebe-toolkit' ),
								'default'    => false,
								'on'         => esc_html__( 'Show', 'lebe-toolkit' ),
								'off'        => esc_html__( 'Hide', 'lebe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
							array(
								'id'         => 'enable_lang_mobile',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Languges and Currency', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Show/Hide Languges and Currency on siding menu mobile', 'lebe-toolkit' ),
								'default'    => false,
								'on'         => esc_html__( 'Show', 'lebe-toolkit' ),
								'off'        => esc_html__( 'Hide', 'lebe-toolkit' ),
								'dependency' => array( 'enable_header_mobile', '==', true )
							),
						),
					),
				)
			);
			$options[] = array(
				'name'   => 'footer',
				'title'  => esc_html__( 'Footer Settings', 'lebe-toolkit' ),
				'icon'   => 'fa fa-folder-open-o',
				'fields' => array(
					array(
						'id'         => 'lebe_footer_options',
						'type'       => 'select',
						'title'      => esc_html__( 'Select Footer Builder', 'lebe-toolkit' ),
						'options'    => 'posts',
						'query_args' => array(
							'post_type'      => 'footer',
							'orderby'        => 'post_date',
							'order'          => 'ASC',
							'posts_per_page' => - 1
						),
					),
				),
			);
			
			$options[] = array(
				'name'     => 'blog',
				'title'    => esc_html__( 'Blog Settings', 'lebe-toolkit' ),
				'icon'     => 'fa fa-rss',
				'sections' => array(
					array(
						'name'   => 'shop_page',
						'title'  => esc_html__( 'Blog Page', 'lebe-toolkit' ),
						'fields' => array(
							array(
								'type'    => 'subheading',
								'content' => esc_html__( 'General Settings', 'lebe-toolkit' ),
							),
							
							array(
								'id'         => 'blog-style',
								'type'       => 'image_select',
								'title'      => esc_html__( 'Style', 'lebe-toolkit' ),
								'radio'      => true,
								'options'    => array(
									'standard' => CS_URI . '/assets/images/layout/standard.png',
									'classic'  => CS_URI . '/assets/images/layout/classic.png',
									'grid'     => CS_URI . '/assets/images/layout/grid.png',
									'modern'   => CS_URI . '/assets/images/layout/modern.png',
								),
								'default'    => 'classic',
								'attributes' => array(
									'data-depend-id' => 'blog-style',
								),
							),
							array(
								'id'         => 'lebe_blog_layout',
								'type'       => 'image_select',
								'title'      => esc_html__( 'Blog Sidebar Position', 'lebe-toolkit' ),
								'desc'       => esc_html__( 'Select sidebar position on Blog.', 'lebe-toolkit' ),
								'options'    => array(
									'left'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
									'right' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
									'full'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
								),
								'default'    => 'full',
								'dependency' => array( 'blog-style', 'any', 'classic,standard,grid' ),
							),
							array(
								'type'       => 'subheading',
								'content'    => esc_html__( 'Grid Column Settings', 'lebe-toolkit' ),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'lebe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'lebe-toolkit' ),
								'id'         => 'lebe_blog_bg_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							
							),
							array(
								'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'lebe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'lebe-toolkit' ),
								'id'         => 'lebe_blog_lg_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'lebe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'lebe-toolkit' ),
								'id'         => 'lebe_blog_md_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'lebe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'lebe-toolkit' ),
								'id'         => 'lebe_blog_sm_items',
								'type'       => 'select',
								'default'    => '4',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'lebe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'lebe-toolkit' ),
								'id'         => 'lebe_blog_xs_items',
								'type'       => 'select',
								'default'    => '6',
								'options'    => array(
									'6' => '2 items',
									'4' => '3 items',
									'3' => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							array(
								'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'lebe-toolkit' ),
								'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'lebe-toolkit' ),
								'id'         => 'lebe_blog_ts_items',
								'type'       => 'select',
								'default'    => '12',
								'options'    => array(
									'12' => '1 items',
									'6'  => '2 items',
									'4'  => '3 items',
									'3'  => '4 items',
								),
								'dependency' => array( 'blog-style', '==', 'grid' ),
							),
							
							array(
								'id'      => 'blog_frame_width',
								'type'    => 'text',
								'title'   => esc_html__( 'Frame Width', 'lebe-toolkit' ),
								'default' => 1400,
								'desc'    => esc_html__( 'Set width for Your Blog. Ex: 1400px, 1199px ...', 'lebe-toolkit' )
							),
							array(
								'id'         => 'blog_sidebar',
								'type'       => 'select',
								'title'      => esc_html__( 'Blog Sidebar', 'lebe-toolkit' ),
								'options'    => $this->sidebars,
								'default'    => 'sidebar-1',
								'dependency' => array( 'sidebar_shop_layout_full', '==', false ),
							),
							array(
								'id'      => 'enable_breadcrumb',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Breadcrumb', 'lebe-toolkit' ),
								'default' => false,
							),
							array(
								'id'      => 'enable-sharing',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Enable Sharing', 'lebe-toolkit' ),
								'default' => false,
							),
							array(
								'id'         => 'social-sharing',
								'type'       => 'select',
								'title'      => esc_html__( 'Social Sharing', 'lebe-toolkit' ),
								'options'    => array(
									'facebook'   => esc_html__( 'Facebook', 'lebe-toolkit' ),
									'twitter'    => esc_html__( 'Twitter', 'lebe-toolkit' ),
									'googleplus' => esc_html__( 'Google Plus', 'lebe-toolkit' ),
									'pinterest'  => esc_html__( 'Pinterest', 'lebe-toolkit' ),
									'tumblr'     => esc_html__( 'Tumblr', 'lebe-toolkit' ),
								),
								'attributes' => array(
									'multiple' => 'multiple',
									'style'    => 'width: 500px; height: 125px;',
								),
								'class'      => 'chosen',
								'default'    => array( 'facebook', 'twitter', 'googleplus', 'pinterest', 'tumblr' ),
								'dependency' => array( 'enable-sharing', '==', true ),
							),
						),
					),
					array(
						'name'   => 'single_post',
						'title'  => 'Single Post',
						'fields' => array(
							array(
								'title'   => esc_html__( 'Featured Image Size', 'lebe-toolkit' ),
								'id'      => 'featured_img_size',
								'type'    => 'text',
								'default' => '1400x817',
								'desc'    => esc_html__( 'Featured image size option. Format {width}x{height}, width and height in the form of positive integer. Default: 1400x817', 'lebe-toolkit' ),
							),
							array(
								'id'      => 'lebe_blog_single_layout',
								'type'    => 'image_select',
								'title'   => esc_html__( 'Blog Single Layout', 'lebe-toolkit' ),
								'desc'    => esc_html__( 'Select Blog Single Layout.', 'lebe-toolkit' ),
								'options' => array(
									'layout1' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/layout1.jpg',
									'layout2' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/layout2.jpg',
								),
								'default' => 'layout1',
							),
							array(
								'id'      => 'post-meta-cats',
								'type'    => 'select',
								'options' => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'title'   => esc_html__( 'Show Categories In Single', 'lebe-toolkit' ),
								'default' => 'yes',
							),
							array(
								'id'      => 'post-meta-tags',
								'type'    => 'select',
								'options' => array(
									'yes' => 'Yes',
									'no'  => 'No',
								),
								'title'   => esc_html__( 'Show Tags In Single', 'lebe-toolkit' ),
								'default' => 'no',
							),
							array(
								'id'      => 'show_post_author',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Show Post Author Bio', 'lebe-toolkit' ),
								'default' => true,
							),
							array(
								'id'         => 'show_post_author_socials',
								'type'       => 'switcher',
								'title'      => esc_html__( 'Show Author Socials Networks', 'lebe-toolkit' ),
								'default'    => false,
								'dependency' => array( 'show_post_author', '==', true ),
							),
							array(
								'id'      => 'sidebar_single_post_position',
								'type'    => 'image_select',
								'title'   => 'Single Post Sidebar Position',
								'desc'    => 'Select sidebar position on Single Post.',
								'options' => array(
									'left'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
									'right' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
									'full'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
								),
								'default' => 'left',
							),
							array(
								'id'      => 'show_single_related_posts',
								'type'    => 'switcher',
								'title'   => esc_html__( 'Show Related Posts', 'lebe-toolkit' ),
								'default' => true,
							),
							array(
								'id'         => 'single_post_sidebar',
								'type'       => 'select',
								'title'      => 'Single Post Sidebar',
								'options'    => $this->sidebars,
								'default'    => 'blue',
								'dependency' => array( 'sidebar_single_post_position_full', '==', false ),
							),
						),
					),
				),
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$options[] = array(
					'name'     => 'wooCommerce',
					'title'    => esc_html__( 'WooCommerce', 'lebe-toolkit' ),
					'icon'     => 'fa fa-shopping-cart',
					'sections' => array(
						array(
							'name'   => 'shop_product',
							'title'  => esc_html__( 'General Settings', 'lebe-toolkit' ),
							'fields' => array(
								array(
									'type'    => 'subheading',
									'content' => esc_html__( 'Shop Settings', 'lebe-toolkit' ),
								),
								array(
									'id'      => 'shop_banner_type',
									'type'    => 'select',
									'title'   => esc_html__( 'Shop Banner Type', 'lebe-toolkit' ),
									'options' => array(
										'has_background' => esc_html__( 'Has Background', 'lebe-toolkit' ),
										'no_background'  => esc_html__( 'No Background ', 'lebe-toolkit' ),
									),
									'default' => 'no_background',
									'desc'    => esc_html__( 'Banner for Shop page, archive, search results page...', 'lebe-toolkit' ),
								),
								array(
									'id'         => 'shop_banner_image',
									'type'       => 'background',
									'title'      => esc_html__( 'Banner Image', 'lebe-toolkit' ),
									'add_title'  => esc_html__( 'Upload', 'lebe-toolkit' ),
									'dependency' => array( 'shop_banner_type', '==', 'has_background' ),
								),
								array(
									'id'         => 'colortext_shop_page',
									'type'       => 'color_picker',
									'title'      => esc_html__( 'Banner Text Color', 'lebe-toolkit' ),
									'default'    => '#ffffff',
									'rgba'       => true,
									'dependency' => array( 'shop_banner_type', '==', 'has_background' ),
								),
								array(
									'id'      => 'shop_banner_height',
									'type'    => 'number',
									'title'   => esc_html__( 'Banner Height', 'lebe-toolkit' ),
									'default' => 420,
								),
								array(
									'id'      => 'shop_margin_top',
									'type'    => 'number',
									'title'   => esc_html__( 'Margin Top', 'lebe-toolkit' ),
									'default' => 0,
								),
								array(
									'id'      => 'shop_margin_bottom',
									'type'    => 'number',
									'title'   => esc_html__( 'Margin Bottom', 'lebe-toolkit' ),
									'default' => 0,
								),
								array(
									'id'      => 'shop_panel',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Top Panel', 'lebe-toolkit' ),
									'default' => false,
								),
								array(
									'id'             => 'panel-categories',
									'type'           => 'select',
									'title'          => esc_html__( 'Select Categories', 'lebe-toolkit' ),
									'options'        => 'categories',
									'query_args'     => array(
										'type'           => 'product',
										'taxonomy'       => 'product_cat',
										'orderby'        => 'post_date',
										'order'          => 'DESC',
										'posts_per_page' => - 1
									),
									'attributes'     => array(
										'multiple' => 'multiple',
										'style'    => 'width: 500px; height: 125px;',
									),
									'class'          => 'chosen',
									'default_option' => esc_html__( 'Select Categories', 'lebe-toolkit' ),
									'desc'           => esc_html__( 'Product categories displayed on the shop page', 'lebe-toolkit' ),
									'dependency'     => array( 'shop_panel', '==', true ),
								),
								array(
									'id'      => 'enable_shop_mobile',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Shop Mobile Layout', 'lebe-toolkit' ),
									'default' => true,
									'desc'    => esc_html__( 'Use the dedicated mobile interface on a real device instead of responsive. Note, this option is not available for desktop browsing and uses resize the screen.', 'lebe-toolkit' ),
								),
								array(
									'id'      => 'enable_instant_product_search',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Instant Products Search', 'lebe-toolkit' ),
									'default' => false,
									'desc'    => esc_html__( 'Enabling "Instant Products Search" will display search results instantly as soon as you type', 'lebe-toolkit' ),
								),
								array(
									'id'      => 'sidebar_shop_page_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Shop Page Layout', 'lebe-toolkit' ),
									'desc'    => esc_html__( 'Select layout for Shop Page.', 'lebe-toolkit' ),
									'options' => array(
										'left'   => LEBE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
										'right'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
										'full'   => LEBE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
										'modern' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/modern.png',
									),
									'default' => 'full',
								),
								array(
									'id'         => 'shop_page_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Shop Sidebar', 'lebe-toolkit' ),
									'options'    => $this->sidebars,
									'dependency' => array(
										'sidebar_shop_page_position_full|sidebar_shop_page_position_modern',
										'==|==',
										false | false
									),
								),
								array(
									'id'         => 'shop_modern_bg_image',
									'type'       => 'image',
									'title'      => esc_html__( 'Shop Modern Background Image', 'lebe-toolkit' ),
									'add_title'  => esc_html__( 'Upload', 'lebe-toolkit' ),
									'dependency' => array( 'sidebar_shop_page_position_modern', '==', true ),
								),
								array(
									'id'       => 'shop_display_mode',
									'type'     => 'image_select',
									'compiler' => true,
									'title'    => esc_html__( 'Shop Layout', 'lebe' ),
									'subtitle' => esc_html__( 'Select default layout for shop, product category archive.', 'lebe' ),
									'options'  => array(
										'grid' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/grid-display.png',
										'list' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/list-display.png',
									),
									'default'  => 'grid',
								),
								array(
									'id'      => 'product_per_page',
									'type'    => 'number',
									'title'   => esc_html__( 'Products perpage', 'lebe-toolkit' ),
									'desc'    => 'Number of products on shop page.',
									'default' => '12',
								),
								array(
									'id'      => 'lebe_enable_loadmore',
									'type'    => 'select',
									'options' => array(
										'default'  => esc_html__( 'Default', 'lebe-toolkit' ),
										'loadmore' => esc_html__( 'Load More', 'lebe-toolkit' ),
										'infinity' => esc_html__( 'Infinity', 'lebe-toolkit' ),
									
									),
									'title'   => esc_html__( 'Choose Pagination', 'lebe-toolkit' ),
									'desc'    => esc_html__( 'Choose pagination type for shop page.', 'lebe-toolkit' ),
									'default' => 'default',
								),
								array(
									'id'         => 'lebe_shop_product_style',
									'type'       => 'select_preview',
									'title'      => esc_html__( 'Product Shop Layout', 'lebe-toolkit' ),
									'desc'       => esc_html__( 'Select a Product layout in shop page', 'lebe-toolkit' ),
									'options'    => $this->product_options,
									'default'    => '1',
									'dependency' => array( 'shop_display_mode_grid', '==', true ),
								),
								array(
									'id'      => 'enable_attributes_swatches',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Attributes Swatches', 'lebe-toolkit' ),
									'default' => true,
								),
								array(
									'id'      => 'ajax_variation_threshold',
									'type'    => 'number',
									'title'   => esc_html__( 'Ajax Variation Threshold', 'lebe-toolkit' ),
									'default' => '1000',
								),
								array(
									'id'         => 'products_loop_attributes_display',
									'type'       => 'select',
									'title'      => esc_html__( 'Products Attribute Display On Loop', 'lebe-toolkit' ),
									'options'    => $this->lebe_attributes_options(),
									'attributes' => array(
										'multiple' => 'multiple',
										'style'    => 'width: 500px; height: 125px;',
									),
									'class'      => 'chosen',
									'default'    => array( 'pa_color' )
								),
								array(
									'type'       => 'subheading',
									'content'    => 'Grid Column Settings',
									'dependency' => array( 'shop_display_mode_grid', '==', true ),
								),
								array(
									'id'         => 'enable_products_sizes',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Show Products Size', 'lebe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'shop_display_mode_grid', '==', true ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_bg_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_md_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_sm_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_xs_items',
									'type'       => 'select',
									'default'    => '6',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_ts_items',
									'type'       => 'select',
									'default'    => '12',
									'options'    => array(
										'12' => '1 item',
										'6'  => '2 items',
										'4'  => '3 items',
										'3'  => '4 items',
										'15' => '5 items',
										'2'  => '6 items',
									),
									'dependency' => array( 'enable_products_sizes', '==', false ),
								),
							),
						),
						array(
							'name'   => 'categories',
							'title'  => esc_html__( 'Categories', 'lebe-toolkit' ),
							'fields' => array(
								array(
									'id'    => 'lebe_woo_cat_enable',
									'type'  => 'switcher',
									'title' => esc_html__( 'Enable Category Products', 'lebe-toolkit' ),
								),
								array(
									'id'         => 'category_banner',
									'type'       => 'image',
									'title'      => esc_html__( 'Categories banner', 'lebe-toolkit' ),
									'desc'       => esc_html__( 'Banner in category page WooCommerce.', 'lebe-toolkit' ),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								array(
									'id'         => 'category_banner_url',
									'type'       => 'text',
									'default'    => '#',
									'title'      => esc_html__( 'Banner Url', 'lebe-toolkit' ),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								
								array(
									'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_cate_ls_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Desktop( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_cate_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								array(
									'title'      => esc_html__( 'Items per row on landscape tablet( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_cate_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								array(
									'title'      => esc_html__( 'Items per row on portrait tablet( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_cate_sm_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_cate_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
								array(
									'title'      => esc_html__( 'Items per row on Mobile( For grid mode )', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_cate_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'lebe_woo_cat_enable', '==', true ),
								),
							),
						),
						array(
							'name'   => 'single_product',
							'title'  => esc_html__( 'Single Product', 'lebe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'sidebar_product_position',
									'type'    => 'image_select',
									'title'   => esc_html__( 'Single Product Sidebar Position', 'lebe-toolkit' ),
									'desc'    => esc_html__( 'Select sidebar position on single product page.', 'lebe-toolkit' ),
									'options' => array(
										'left'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
										'right' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
										'full'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
									),
									'default' => 'left',
								),
								array(
									'id'      => 'enable_single_product_mobile',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Product Mobile Layout', 'lebe-toolkit' ),
									'default' => true,
									'desc'    => esc_html__( 'Use the dedicated mobile interface on a real device instead of responsive. Note, this option is not available for desktop browsing and uses resize the screen.', 'lebe-toolkit' ),
								),
								array(
									'id'      => 'lebe_product_variation_layout',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Mobile Variation Layout', 'lebe-toolkit' ),
									'desc'    => esc_html__( 'Choose Single Product Mobile Variation Layout', 'lebe-toolkit' ),
									'options' => array(
										'default'           => esc_html__( 'Default', 'lebe-toolkit' ),
										'variation_popup'   => esc_html__( 'Variation Popup', 'lebe-toolkit' ),
									),
									'default' => 'variation_popup',
								),
								array(
									'id'      => 'enable_info_product_single',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Sticky Info Product Single', 'lebe-toolkit' ),
									'default' => true,
									'desc'    => esc_html__( 'On or Off Sticky Info Product Single.', 'lebe-toolkit' ),
								),
								array(
									'id'         => 'single_product_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Single Product Sidebar', 'lebe-toolkit' ),
									'options'    => $this->sidebars,
									'default'    => 'blue',
									'dependency' => array( 'sidebar_product_position_full', '==', false ),
								),
								array(
									'id'      => 'lebe_woo_single_product_layout',
									'type'    => 'select',
									'title'   => esc_html__( 'Choose Single Style', 'lebe-toolkit' ),
									'desc'    => esc_html__( 'Choose Single Product Style', 'lebe-toolkit' ),
									'options' => array(
										'default'           => esc_html__( 'Default', 'lebe-toolkit' ),
										'vertical_thumnail' => esc_html__( 'Thumbnail Vertical', 'lebe-toolkit' ),
										'sticky_detail'     => esc_html__( 'Sticky Detail', 'lebe-toolkit' ),
										'gallery_detail'    => esc_html__( 'Gallery Detail', 'lebe-toolkit' ),
										'big_images'        => esc_html__( 'Big Images', 'lebe-toolkit' ),
									),
									'default' => 'vertical_thumnail',
								),
								array(
									'id'         => 'single_product_img_bg_color',
									'type'       => 'color_picker',
									'title'      => esc_html__( 'Image Background Color', 'lebe-toolkit' ),
									'default'    => 'rgba(0,0,0,0)',
									'rgba'       => true,
									'dependency' => array(
										'lebe_woo_single_product_layout',
										'==',
										'big_images'
									),
									'desc'       => esc_html__( 'For "Big Images" style only. Default: transparent', 'lebe-toolkit' ),
								),
								array(
									'id'         => 'single_product_sum_border',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Summary Border', 'lebe-toolkit' ),
									'default'    => false,
									'dependency' => array(
										'lebe_woo_single_product_layout',
										'any',
										'default,vertical_thumnail,sticky_detail'
									),
								),
								array(
									'id'         => 'single_product_title_price_stars_outside_sum',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Title, Price And Stars Outside Sumary', 'lebe-toolkit' ),
									'default'    => false,
									'dependency' => array(
										'lebe_woo_single_product_layout',
										'any',
										'default,vertical_thumnail,sticky_detail'
									),
								),
								array(
									'id'      => 'enable_single_product_sharing',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Product Sharing', 'lebe-toolkit' ),
									'default' => false,
								),
								array(
									'id'         => 'enable_single_product_sharing_fb',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Facebook Sharing', 'lebe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
								array(
									'id'         => 'enable_single_product_sharing_tw',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Twitter Sharing', 'lebe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
								array(
									'id'         => 'enable_single_product_sharing_pinterest',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Pinterest Sharing', 'lebe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
								array(
									'id'         => 'enable_single_product_sharing_gplus',
									'type'       => 'switcher',
									'title'      => esc_html__( 'Google Plus Sharing', 'lebe-toolkit' ),
									'default'    => true,
									'dependency' => array( 'enable_single_product_sharing', '==', true ),
								),
							),
						),
						array(
							'name'   => 'extend_single_product',
							'title'  => esc_html__( 'Extend Single Products', 'lebe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'enable_extend_single_product',
									'type'    => 'switcher',
									'title'   => esc_html__( 'Enable Extend Single Products', 'lebe-toolkit' ),
									'default' => false,
								),
								array(
									'id'         => 'lebe_extend_single_product_summary_sidebar',
									'type'       => 'select',
									'title'      => esc_html__( 'Extend Sidebar Used For summary Single Product', 'lebe' ),
									'options'    => $this->sidebars,
									'dependency' => array( 'enable_extend_single_product', '==', true ),
								),
								array(
									'id'         => 'lebe_extend_single_product_summary_sidebar_style',
									'type'       => 'select',
									'title'      => esc_html__( 'Type Of Extend Sidebar Used For summary Single Product', 'lebe' ),
									'desc'       => esc_html__( 'Extend Sidebar for summary vertical or horizontal', 'lebe' ),
									'options'    => array(
										'vertical'   => esc_html__( 'Vertical', 'lebe' ),
										'horizontal' => esc_html__( 'Horizontal', 'lebe' ),
									),
									'default'    => 'vertical',
									'dependency' => array( 'enable_extend_single_product', '==', true ),
								),
								array(
									'id'         => 'lebe_image_extend',
									'type'       => 'image',
									'title'      => esc_html__( 'Image Extend', 'lebe-toolkit' ),
									'add_title'  => esc_html__( 'Add image', 'lebe-toolkit' ),
									'desc'       => esc_html__( 'Add image in single product Extend Horizontal.', 'lebe-toolkit' ),
									'dependency' => array(
										'lebe_extend_single_product_summary_sidebar_style',
										'==',
										'horizontal'
									),
								),
								array(
									'id'         => 'lebe_image_extend_url',
									'type'       => 'text',
									'default'    => '#',
									'title'      => esc_html__( 'Image Extend Url', 'lebe-toolkit' ),
									'dependency' => array( 'lebe_image_extend', '!=', '' ),
								),
							),
						),
						array(
							'name'   => 'cross_sell',
							'title'  => esc_html__( 'Cross Sell', 'lebe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'enable_cross_sell',
									'type'    => 'select',
									'options' => array(
										'yes' => esc_html__( 'Yes', 'lebe-toolkit' ),
										'no'  => esc_html__( 'No', 'lebe-toolkit' ),
									),
									'title'   => esc_html__( 'Enable Cross Sell', 'lebe-toolkit' ),
									'default' => 'yes',
								),
								array(
									'title'      => esc_html__( 'Cross sell title', 'lebe-toolkit' ),
									'id'         => 'lebe_cross_sells_products_title',
									'type'       => 'text',
									'default'    => esc_html__( 'You may be interested in...', 'lebe-toolkit' ),
									'desc'       => esc_html__( 'Cross sell title', 'lebe-toolkit' ),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								
								array(
									'title'      => esc_html__( 'Cross sell items per row on Desktop', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_crosssell_ls_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on Desktop', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_crosssell_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on landscape tablet', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_crosssell_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on portrait tablet', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_crosssell_sm_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on Mobile', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_crosssell_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Cross sell items per row on Mobile', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_crosssell_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_cross_sell', '==', 'yes' ),
								),
							),
						),
						array(
							'name'   => 'related_product',
							'title'  => 'Related Products',
							'fields' => array(
								array(
									'id'      => 'enable_relate_products',
									'type'    => 'select',
									'options' => array(
										'yes' => esc_html__( 'Yes', 'lebe-toolkit' ),
										'no'  => esc_html__( 'No', 'lebe-toolkit' ),
									),
									'title'   => esc_html__( 'Enable Related Products', 'lebe-toolkit' ),
									'default' => 'yes',
								),
								array(
									'title'      => esc_html__( 'Related products title', 'lebe-toolkit' ),
									'id'         => 'lebe_related_products_title',
									'type'       => 'text',
									'default'    => 'Related Products',
									'desc'       => esc_html__( 'Related products title', 'lebe-toolkit' ),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'    => esc_html__( 'Limit Number Of Products', 'lebe' ),
									'id'       => 'lebe_related_products_perpage',
									'type'     => 'text',
									'default'  => '8',
									'validate' => 'numeric',
									'subtitle' => esc_html__( 'Number of products on shop page', 'lebe' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Desktop', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_related_ls_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Desktop', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_related_lg_items',
									'type'       => 'select',
									'default'    => '4',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on landscape tablet', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_related_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related product items per row on portrait tablet', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_related_sm_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Mobile', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_related_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Related products items per row on Mobile', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_related_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_relate_products', '==', 'yes' ),
								),
							),
						),
						array(
							'name'   => 'upsells_product',
							'title'  => esc_html__( 'Up sells Products', 'lebe-toolkit' ),
							'fields' => array(
								array(
									'id'      => 'enable_up_sell',
									'type'    => 'select',
									'options' => array(
										'yes' => esc_html__( 'Yes', 'lebe-toolkit' ),
										'no'  => esc_html__( 'No', 'lebe-toolkit' ),
									),
									'title'   => esc_html__( 'Enable Up Sell', 'lebe-toolkit' ),
									'default' => 'yes',
								),
								array(
									'title'      => esc_html__( 'Up sells title', 'lebe-toolkit' ),
									'id'         => 'lebe_upsell_products_title',
									'type'       => 'text',
									'default'    => esc_html__( 'You may also like...', 'lebe-toolkit' ),
									'desc'       => esc_html__( 'Up sells products title', 'lebe-toolkit' ),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								
								array(
									'title'      => esc_html__( 'Up sells items per row on Desktop', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_upsell_ls_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on Desktop', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >= 1200px < 1500px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_upsell_lg_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on landscape tablet', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=992px and < 1200px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_upsell_md_items',
									'type'       => 'select',
									'default'    => '3',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on portrait tablet', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=768px and < 992px )', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_upsell_sm_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on Mobile', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device >=480  add < 768px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_upsell_xs_items',
									'type'       => 'select',
									'default'    => '2',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
								array(
									'title'      => esc_html__( 'Up sells items per row on Mobile', 'lebe-toolkit' ),
									'desc'       => esc_html__( '(Screen resolution of device < 480px)', 'lebe-toolkit' ),
									'id'         => 'lebe_woo_upsell_ts_items',
									'type'       => 'select',
									'default'    => '1',
									'options'    => array(
										'1' => '1 item',
										'2' => '2 items',
										'3' => '3 items',
										'4' => '4 items',
										'5' => '5 items',
										'6' => '6 items',
									),
									'dependency' => array( 'enable_up_sell', '==', 'yes' ),
								),
							),
						),
					),
				);
			}
			
			$options[] = array(
				'name'   => 'social_settings',
				'title'  => esc_html__( 'Social Settings', 'lebe-toolkit' ),
				'icon'   => 'fa fa-users',
				'fields' => array(
					array(
						'type'    => 'subheading',
						'content' => esc_html__( 'Socials Networks', 'lebe-toolkit' ),
					),
					array(
						'id'              => 'user_all_social',
						'type'            => 'group',
						'title'           => esc_html__( 'Socials', 'lebe-toolkit' ),
						'button_title'    => esc_html__( 'Add New Social', 'lebe-toolkit' ),
						'accordion_title' => esc_html__( 'Social Settings', 'lebe-toolkit' ),
						'fields'          => array(
							array(
								'id'      => 'title_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Social Title', 'lebe-toolkit' ),
								'default' => esc_html__( 'Facebook', 'lebe-toolkit' ),
							),
							array(
								'id'      => 'link_social',
								'type'    => 'text',
								'title'   => esc_html__( 'Social Link', 'lebe-toolkit' ),
								'default' => 'https://facebook.com',
							),
							array(
								'id'      => 'icon_social',
								'type'    => 'icon',
								'title'   => esc_html__( 'Social Icon', 'lebe-toolkit' ),
								'default' => 'fa fa-facebook',
							),
						),
					),
				),
			);
			
			$options[] = array(
				'name'   => 'typography',
				'title'  => esc_html__( 'Typography Options', 'lebe-toolkit' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					array(
						'id'      => 'enable_google_font',
						'type'    => 'switcher',
						'title'   => esc_html__( 'Enable Google Font', 'lebe-toolkit' ),
						'default' => false,
						'on'      => esc_html__( 'Enable', 'lebe-toolkit' ),
						'off'     => esc_html__( 'Disable', 'lebe-toolkit' )
					),
					array(
						'id'         => 'typography_themes',
						'type'       => 'typography',
						'title'      => esc_html__( 'Body Typography', 'lebe-toolkit' ),
						'default'    => array(
							'family'  => 'Open Sans',
							'variant' => '400',
							'font'    => 'google',
						),
						'dependency' => array( 'enable_google_font', '==', true )
					),
					array(
						'id'         => 'fontsize-body',
						'type'       => 'number',
						'title'      => esc_html__( 'Body Font Size', 'lebe-toolkit' ),
						'default'    => '15',
						'after'      => ' <i class="cs-text-muted">px</i>',
						'dependency' => array( 'enable_google_font', '==', true )
					)
				),
			);
			
			$options[] = array(
				'name'   => 'backup_option',
				'title'  => esc_html__( 'Backup Options', 'lebe-toolkit' ),
				'icon'   => 'fa fa-font',
				'fields' => array(
					array(
						'type'  => 'backup',
						'title' => esc_html__( 'Backup Field', 'lebe-toolkit' ),
					),
				),
			);
			
			
			CSFramework::instance( $settings, $options );
		}
	}
	
	new Lebe_ThemeOption();
}
