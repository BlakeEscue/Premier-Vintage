<?php
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}
if ( ! class_exists( 'Lebe_Functions' ) ) {
	class Lebe_Functions {
		/**
		 * Instance of the class.
		 *
		 * @since   1.0.0
		 *
		 * @var   object
		 */
		protected static $instance = null;
		
		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 * and styles.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'lebe_settup' ) );
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
			add_action( 'upload_mimes', array( $this, 'lebe_add_svg_type_upload' ), 1 );
			add_filter( 'get_default_comment_status', array( $this, 'open_default_comments_for_page' ), 10, 3 );
			add_filter( 'comment_form_fields', array( &$this, 'lebe_move_comment_field_to_bottom' ), 10, 3 );
			
			$this->includes();
		}
		
		public function lebe_settup() {
			/*
			* Make theme available for translation.
			* Translations can be filed in the /languages/ directory.
			* If you're building a theme based on boutique, use a find and replace
			* to change 'lebe' to the name of your theme in all the template files
			*/
			load_theme_textdomain( 'lebe', get_template_directory() . '/languages' );
			add_theme_support( 'automatic-feed-links' );
			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );
			/*
			 * Enable support for Post Formats.
			 *
			 * See: https://codex.wordpress.org/Post_Formats
			 */
			add_theme_support( 'post-formats', array(
				'video',
				'gallery',
				'audio',
			) );
			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'custom-header' );
			
			set_post_thumbnail_size( 825, 510, true );
			/*This theme uses wp_nav_menu() in two locations.*/
			register_nav_menus( array(
				                    'primary'     => esc_html__( 'Primary Menu', 'lebe' ),
				                    'double-menu' => esc_html__( 'Double Menu', 'lebe' ),
			                    )
			);
			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array(
				                          'search-form',
				                          'comment-form',
				                          'comment-list',
				                          'gallery',
				                          'caption',
			                          )
			);
			
			if ( class_exists( 'WooCommerce' ) ) {
				/*Support woocommerce*/
				add_theme_support( 'woocommerce');
				add_theme_support( 'wc-product-gallery-lightbox' );
				add_theme_support( 'wc-product-gallery-slider' );
				add_theme_support( 'wc-product-gallery-zoom' );
			}
			
			self::support_gutenberg();
		}
		
		public function support_gutenberg() {
			// Add support for Block Styles.
			add_theme_support( 'wp-block-styles' );
			
			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );
			
			// Add support for editor styles.
			add_theme_support( 'editor-styles' );
			
			// Enqueue editor styles.
			add_editor_style( 'style-editor.css' );
			
			// Add custom editor font sizes.
			add_theme_support(
				'editor-font-sizes',
				array(
					array(
						'name'      => __( 'Small', 'lebe' ),
						'shortName' => __( 'S', 'lebe' ),
						'size'      => 13,
						'slug'      => 'small',
					),
					array(
						'name'      => __( 'Normal', 'lebe' ),
						'shortName' => __( 'M', 'lebe' ),
						'size'      => 14,
						'slug'      => 'normal',
					),
					array(
						'name'      => __( 'Large', 'lebe' ),
						'shortName' => __( 'L', 'lebe' ),
						'size'      => 36,
						'slug'      => 'large',
					),
					array(
						'name'      => __( 'Huge', 'lebe' ),
						'shortName' => __( 'XL', 'lebe' ),
						'size'      => 48,
						'slug'      => 'huge',
					),
				)
			);
			
			// Add support for responsive embedded content.
			add_theme_support( 'responsive-embeds' );
		}
		
		public function lebe_move_comment_field_to_bottom( $fields ) {
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;
			
			return $fields;
		}
		
		/**
		 * Register widget area.
		 *
		 * @since lebe 1.0
		 *
		 * @link  https://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		function widgets_init() {
			register_sidebar( array(
				                  'name'          => esc_html__( 'Primary Sidebar', 'lebe' ),
				                  'id'            => 'sidebar-1',
				                  'description'   => esc_html__( 'Add widgets here to display on blog page, posts, pages...', 'lebe' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Shop Sidebar', 'lebe' ),
				                  'id'            => 'shop-widget-area',
				                  'description'   => esc_html__( 'Add widgets are here to display on the shop page', 'lebe' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Product Description Sidebar', 'lebe' ),
				                  'id'            => 'product-desc-sidebar',
				                  'description'   => esc_html__( 'This sidebar is only displayed on default product page or product with vertical thumbnails', 'lebe' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arow"></span></h2>',
			                  )
			);
			register_sidebar( array(
				                  'name'          => esc_html__( 'Product Sidebar', 'lebe' ),
				                  'id'            => 'product-widget-area',
				                  'description'   => esc_html__( 'This is the default sidebar that displays the single product page. Note, the product layout must have left sidebar or right sidebar.', 'lebe' ),
				                  'before_widget' => '<div id="%1$s" class="widget %2$s">',
				                  'after_widget'  => '</div>',
				                  'before_title'  => '<h2 class="widgettitle">',
				                  'after_title'   => '<span class="arow"></span></h2>',
			                  )
			);
		}
		
		/*Load Google fonts*/
		
		/**
		 * Register Google fonts for Twenty Fifteen.
		 *
		 * @since Lucky Shop 1.0
		 *
		 * @return string Google fonts URL for the theme.
		 */
		function google_fonts_url() {
			$fonts_url = '';
			$fonts     = array();
			$subsets   = 'latin,latin-ext';
			$body_font = lebe_get_option( 'typography_themes' );
			
			/*
			 * Translators: If there are characters in your language that are not supported
			 * by Poppins, translate this to 'off'. Do not translate into your own language.
			 */
			if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'lebe' ) ) {
				$fonts[] = 'Open Sans:300,400,600,700,800';
			}
			
			if ( isset( $body_font['family'] ) ) {
				if ( trim( $body_font['family'] ) != '' ) {
					$fonts[] = '' . $body_font['family'] . ':' . $body_font['variant'] . '';
				}
			}
			
			/*
			 * Translators: To add an additional character subset specific to your language,
			 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
			 */
			$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'lebe' );
			
			if ( 'cyrillic' == $subset ) {
				$subsets .= ',cyrillic,cyrillic-ext';
			} elseif ( 'greek' == $subset ) {
				$subsets .= ',greek,greek-ext';
			} elseif ( 'devanagari' == $subset ) {
				$subsets .= ',devanagari';
			} elseif ( 'vietnamese' == $subset ) {
				$subsets .= ',vietnamese';
			}
			
			if ( $fonts ) {
				$fonts_url = add_query_arg(
					array(
						'family' => urlencode( implode( '|', $fonts ) ),
						'subset' => urlencode( $subsets ),
					), 'https://fonts.googleapis.com/css'
				);
			}
			
			return $fonts_url;
			
			
		}
		
		/**
		 * Convert HSL to HEX colors
		 */
		public static function hsl_hex( $h, $s, $l, $to_hex = true ) {
			
			$h /= 360;
			$s /= 100;
			$l /= 100;
			
			$r = $l;
			$g = $l;
			$b = $l;
			$v = ( $l <= 0.5 ) ? ( $l * ( 1.0 + $s ) ) : ( $l + $s - $l * $s );
			if ( $v > 0 ) {
				$m;
				$sv;
				$sextant;
				$fract;
				$vsf;
				$mid1;
				$mid2;
				
				$m       = $l + $l - $v;
				$sv      = ( $v - $m ) / $v;
				$h       *= 6.0;
				$sextant = floor( $h );
				$fract   = $h - $sextant;
				$vsf     = $v * $sv * $fract;
				$mid1    = $m + $vsf;
				$mid2    = $v - $vsf;
				
				switch ( $sextant ) {
					case 0:
						$r = $v;
						$g = $mid1;
						$b = $m;
						break;
					case 1:
						$r = $mid2;
						$g = $v;
						$b = $m;
						break;
					case 2:
						$r = $m;
						$g = $v;
						$b = $mid1;
						break;
					case 3:
						$r = $m;
						$g = $mid2;
						$b = $v;
						break;
					case 4:
						$r = $mid1;
						$g = $m;
						$b = $v;
						break;
					case 5:
						$r = $v;
						$g = $m;
						$b = $mid2;
						break;
				}
			}
			$r = round( $r * 255, 0 );
			$g = round( $g * 255, 0 );
			$b = round( $b * 255, 0 );
			
			if ( $to_hex ) {
				
				$r = ( $r < 15 ) ? '0' . dechex( $r ) : dechex( $r );
				$g = ( $g < 15 ) ? '0' . dechex( $g ) : dechex( $g );
				$b = ( $b < 15 ) ? '0' . dechex( $b ) : dechex( $b );
				
				return "#$r$g$b";
				
			} else {
				
				return "rgb($r, $g, $b)";
			}
		}
		
		/**
		 * Enqueue scripts and styles.
		 *
		 * @since lebe 1.0
		 */
		function scripts() {
			if ( class_exists( 'WooCommerce' ) ) {
				if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
					wp_dequeue_script( 'wc_price_slider' );
					wp_dequeue_script( 'wc-checkout' );
					wp_dequeue_script( 'wc-cart' );
					wp_dequeue_script( 'wc-chosen' );
					wp_dequeue_script( 'prettyPhoto' );
					wp_dequeue_script( 'prettyPhoto-init' );
					wp_dequeue_script( 'jquery-blockui' );
					wp_dequeue_script( 'jquery-placeholder' );
					wp_dequeue_script( 'fancybox' );
					wp_dequeue_script( 'jqueryui' );
				}
			}
			
			$animation_on_scroll = lebe_get_option( 'animation_on_scroll', '' );
			
			// Load fonts
			wp_enqueue_style( 'lebe-googlefonts', $this->google_fonts_url(), array(), null );
			/*Load our main stylesheet.*/
			wp_enqueue_style( 'boostrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), array(), false );
			wp_enqueue_style( 'owl-carousel', get_theme_file_uri( '/assets/css/owl.carousel.min.css' ), array(), false );
			wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), array(), false );
			wp_enqueue_style( 'flat-icons', get_theme_file_uri( '/assets/fonts/flaticon.css' ), array(), false );
			wp_enqueue_style( 'scrollbar', get_theme_file_uri( '/assets/css/jquery.scrollbar.css' ), array(), false );
			if ( $animation_on_scroll ) {
				wp_enqueue_style( 'animation-on-scroll', get_theme_file_uri( '/assets/css/animation-on-scroll.css' ), array(), false );
			}
			
			wp_enqueue_style( 'lebe-custom', get_theme_file_uri( '/assets/css/customs.css' ), array(), false );
			wp_enqueue_style( 'lebe-main-style', get_template_directory_uri() . '/style.css', array(), false );
			
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			
			/*Load lib js*/
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'wc-add-to-cart-variation' );
			
			wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'owl-carousel', get_theme_file_uri( '/assets/js/owl.carousel.min.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/jquery.magnific-popup.min.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'scrollbar', get_theme_file_uri( '/assets/js/jquery.scrollbar.min.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'sticky', get_theme_file_uri( '/assets/js/jquery.sticky.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'jquery-countdown', get_theme_file_uri( '/assets/js/jquery.countdown.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'theia-sticky-sidebar', get_theme_file_uri( '/assets/js/theia-sticky-sidebar.min.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'threesixty', get_theme_file_uri( '/assets/js/threesixty.min.js' ), array( 'jquery' ), false );
			wp_enqueue_script( 'slick', get_theme_file_uri( '/assets/js/slick.js' ), array( 'jquery' ), false );
			if ( $animation_on_scroll ) {
				wp_enqueue_script( 'wow', get_theme_file_uri( '/assets/js/wow.min.js' ), array(), false, true );
			}
			
			$gmap_api_key = lebe_get_option( 'gmap_api_key', '' );
			$gmap_api_key = trim( $gmap_api_key );
			if ( $gmap_api_key != '' ) {
				$load_gmap_js        = false;
				$load_gmap_js_target = lebe_get_option( 'load_gmap_js_target', 'all_pages' );
				if ( $load_gmap_js_target == 'selected_pages' ) {
					$load_gmap_js_on = lebe_get_option( 'load_gmap_js_on', array() );
					if ( ! is_array( $load_gmap_js_on ) ) {
						$load_gmap_js_on = array();
					}
					if ( is_singular( 'page' ) ) {
						if ( in_array( get_the_ID(), $load_gmap_js_on ) ) {
							$load_gmap_js = true;
						}
					}
				}
				if ( $load_gmap_js_target == 'all_pages' ) {
					$load_gmap_js = true;
				}
				if ( $load_gmap_js ) {
					wp_enqueue_script( 'maps', esc_url( 'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $gmap_api_key ) ), array( 'jquery' ), false, true );
				}
			}
			
			$enable_lazy = lebe_get_option( 'lebe_enable_lazy', false );
			if ( $enable_lazy ) {
				/* http://jquery.eisbehr.de/lazy */
				wp_enqueue_script( 'lazy', get_theme_file_uri( '/assets/js/jquery.lazy.min.js' ), array( 'jquery' ), false );
			}
			
			$enable_smooth_scroll = lebe_get_option( 'enable_smooth_scroll', false );
			if ( $enable_smooth_scroll ) {
				wp_enqueue_script( 'smooth-scroll', get_theme_file_uri( '/assets/js/SmoothScroll.min.js' ), array( 'jquery' ), false );
			}
			
			wp_enqueue_script( 'lebe-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), false, true );
			
			$main_menu_res_break_point = intval( lebe_get_option( 'main_menu_res_break_point', 1199 ) );
			wp_localize_script( 'lebe-script', 'lebe_theme_frontend',
			                    array(
				                    'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				                    'security'              => wp_create_nonce( 'lebe_ajax_frontend' ),
				                    'main_menu_break_point' => $main_menu_res_break_point,
				                    'text'                  => array(
					                    'load_more'         => esc_html__( 'Load More', 'lebe' ),
					                    'no_more_product'   => esc_html__( 'No More Product', 'lebe' ),
					                    'more_detail'       => esc_html__( 'More Details', 'lebe' ),
					                    'less_detail'       => esc_html__( 'Less Details', 'lebe' ),
					                    'back_to_menu_text' => esc_html__( 'Back to "{{menu_name}}"', 'lebe' ),
				                    ),
				                    'animation_on_scroll'   => $animation_on_scroll ? 'yes' : 'no'
			                    )
			);
			
		}
		
		function admin_enqueue_scripts() {
			wp_enqueue_style( 'lebe-fonts', self::google_fonts_url(), array(), null );
		}
		
		/**
		 * Filter whether comments are open for a given post type.
		 *
		 * @param string $status       Default status for the given post type,
		 *                             either 'open' or 'closed'.
		 * @param string $post_type    Post type. Default is `post`.
		 * @param string $comment_type Type of comment. Default is `comment`.
		 *
		 * @return string (Maybe) filtered default status for the given post type.
		 */
		function open_default_comments_for_page( $status, $post_type, $comment_type ) {
			if ( 'page' == $post_type ) {
				return 'open';
			}
			
			return $status;
			/*You could be more specific here for different comment types if desired*/
		}
		
		
		public function includes() {
			include_once( get_template_directory() . '/framework/framework.php' );
			define( 'CS_ACTIVE_FRAMEWORK', true ); // default true
			define( 'CS_ACTIVE_METABOX', true ); // default true
			define( 'CS_ACTIVE_TAXONOMY', true ); // default true
			define( 'CS_ACTIVE_SHORTCODE', false ); // default true
			define( 'CS_ACTIVE_CUSTOMIZE', false ); // default true
		}
		
		public function lebe_custom_data_js() {
			$data = array();
			// Get option for permalink
			$data['permalink'] = ( get_option( 'permalink_structure' ) == '' ) ? 'plain' : '';
			
			return $data;
		}
		
		public function lebe_add_svg_type_upload( $file_types ) {
			$new_filetypes        = array();
			$new_filetypes['svg'] = 'image/svg+xml';
			$file_types           = array_merge( $file_types, $new_filetypes );
			
			return $file_types;
		}
		
	}
	
	new  Lebe_Functions();
}

if ( ! function_exists( 'lebe_preload_tmp' ) ) {
	function lebe_preload_tmp( $page_selector = '' ) {
		
		$html_header_tmp = '<div data-h="84" class="header-preload-wrap preload-group">
								<div class="row">
									<div class="col-md-2 col-sm-2">
										<div class="preload-div"></div>
									</div>
									<div class="col-sm-7 col-md-8">
										<div class="preload-div"></div>
									</div>
									<div class="col-sm-3 col-md-2">
										<div class="preload-div"></div>
									</div>
								</div>
							</div>';
		$html_body_tmp   = '<div class="header-preload-wrap preload-group">
								<div class="text-center">
									<div data-h="50" data-w="110" class="preload-div clear-both"></div>
									<div data-h="32" data-w="120" class="preload-div clear-both"></div>
								</div>
							</div>';
		$html_footer_tmp = '';
		$html_tmp        = '';
		
		switch ( $page_selector ) {
			case 'button-viewcart':
				$html_body_tmp = '<div class="header-preload-wrap preload-group">
										<div class="text-center">
											<div data-h="50" data-w="110" class="preload-div clear-both"></div>
											<div data-h="32" data-w="120" class="preload-div clear-both"></div>
										</div>
									</div>';
				break;
			default:
				break;
		}
		
		$html_tmp = '<div class="preload-for-' . esc_attr( $page_selector ) . ' preload-total-wrap">' . $html_header_tmp . $html_body_tmp . $html_footer_tmp . '</div>';
		
		return $html_tmp;
	}
}

if ( ! function_exists( 'lebe_html_output' ) ) {
	function lebe_html_output( $html ) {
		if ( function_exists( 'fami_wp_html_compression_output' ) ) {
			return fami_wp_html_compression_output( $html );
		}
		
		return apply_filters( 'lebe_html_output', $html );
	}
}

add_action( 'admin_head', 'vc_css_admin' );
if ( ! function_exists( 'vc_css_admin' ) ) {
    function vc_css_admin() {
        echo '<style>
	    .vc_license-activation-notice,
	    .customize-control-woocommerce_catalog_columns {
	      display:none !important;;
	    } 
	  </style>';
    }
}