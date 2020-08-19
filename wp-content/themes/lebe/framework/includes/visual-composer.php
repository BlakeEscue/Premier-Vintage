<?php
if ( ! class_exists( 'Lebe_Visual_Composer' ) ) {
	class Lebe_Visual_Composer {
		public function __construct() {
			$this->define_constants();
			add_filter( 'vc_google_fonts_get_fonts_filter', array( $this, 'vc_fonts' ) );
//			add_action( 'init', array( &$this, 'params' ) );
//			add_action( 'init', array( &$this, 'autocomplete' ) );
			$this->params();
			$this->autocomplete();
			/* Custom font Icon*/
			add_filter( 'vc_iconpicker-type-lebecustomfonts', array( &$this, 'iconpicker_type_lebe_customfonts' ) );
			$this->map_shortcode();
		}
		
		/**
		 * Define  Constants.
		 */
		private function define_constants() {
			$this->define( 'LEBE_SHORTCODE_PREVIEW', get_theme_file_uri( '/framework/assets/images/shortcode-previews/' ) );
			$this->define( 'LEBE_SHORTCODES_ICONS_URI', get_theme_file_uri( '/framework/assets/images/vc-shortcodes-icons/' ) );
			$this->define( 'LEBE_PRODUCT_STYLE_PREVIEW', get_theme_file_uri( '/woocommerce/product-styles/' ) );
			$this->define( 'LEBE_PRODUCT_DEAL_PREVIEW', get_theme_file_uri( '/woocommerce/product-deal/' ) );
			
		}
		
		/**
		 * Define constant if not already set.
		 *
		 * @param  string      $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}
		
		function params() {
			if ( function_exists( 'lebe_toolkit_vc_param' ) ) {
				lebe_toolkit_vc_param( 'taxonomy', array( $this, 'taxonomy_field' ) );
				lebe_toolkit_vc_param( 'uniqid', array( $this, 'uniqid_field' ) );
				lebe_toolkit_vc_param( 'select_preview', array( $this, 'select_preview_field' ) );
				lebe_toolkit_vc_param( 'number', array( $this, 'number_field' ) );
			}
		}
		
		/**
		 * load param autocomplete render
		 * */
		public function autocomplete() {
			add_filter( 'vc_autocomplete_lebe_products_ids_callback', array(
				$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_lebe_products_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_lebe_dealproduct_ids_callback', array(
				$this,
				'productIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_lebe_dealproduct_ids_render', array(
				$this,
				'productIdAutocompleteRender'
			), 10, 1 );
			add_filter( 'vc_autocomplete_lebe_pinmap_ids_callback', array(
				$this,
				'pinmapIdAutocompleteSuggester'
			), 10, 1 );
			add_filter( 'vc_autocomplete_lebe_pinmap_ids_render', array(
				$this,
				'pinmapIdAutocompleteRender'
			), 10, 1 );
			
		}
		
		/*
         * taxonomy_field
         * */
		public function taxonomy_field( $settings, $value ) {
			$dependency = '';
			$value_arr  = $value;
			if ( ! is_array( $value_arr ) ) {
				$value_arr = array_map( 'trim', explode( ',', $value_arr ) );
			}
			$output = '';
			if ( isset( $settings['hide_empty'] ) && $settings['hide_empty'] ) {
				$settings['hide_empty'] = 1;
			} else {
				$settings['hide_empty'] = 0;
			}
			if ( ! empty( $settings['taxonomy'] ) ) {
				$terms_fields = array();
				if ( isset( $settings['placeholder'] ) && $settings['placeholder'] ) {
					$terms_fields[] = "<option value=''>" . $settings['placeholder'] . "</option>";
				}
				$terms = get_terms( $settings['taxonomy'], array(
					'parent'     => $settings['parent'],
					'hide_empty' => $settings['hide_empty']
				) );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$selected       = ( in_array( $term->slug, $value_arr ) ) ? ' selected="selected"' : '';
						$terms_fields[] = "<option value='{$term->slug}' {$selected}>{$term->name}</option>";
					}
				}
				$size     = ( ! empty( $settings['size'] ) ) ? 'size="' . $settings['size'] . '"' : '';
				$multiple = ( ! empty( $settings['multiple'] ) ) ? 'multiple="multiple"' : '';
				$uniqeID  = uniqid();
				$output   = '<select style="width:100%;" id="vc_taxonomy-' . $uniqeID . '" ' . $multiple . ' ' . $size . ' name="' . $settings['param_name'] . '" class="lebe_vc_taxonomy wpb_vc_param_value wpb-input wpb-select ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" ' . $dependency . '>'
				            . implode( $terms_fields )
				            . '</select>';
			}
			
			return $output;
		}
		
		public function uniqid_field( $settings, $value ) {
			if ( ! $value ) {
				$value = uniqid( hash( 'crc32', $settings['param_name'] ) . '-' );
			}
			$output = '<input type="text" class="wpb_vc_param_value textfield" name="' . $settings['param_name'] . '" value="' . esc_attr( $value ) . '" />';
			
			return $output;
		}
		
		public function number_field( $settings, $value ) {
			$dependency = '';
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type '] ) ? $settings['type'] : '';
			$min        = isset( $settings['min'] ) ? $settings['min'] : '';
			$max        = isset( $settings['max'] ) ? $settings['max'] : '';
			$suffix     = isset( $settings['suffix'] ) ? $settings['suffix'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';
			if ( ! $value && isset( $settings['std'] ) ) {
				$value = $settings['std'];
			}
			$output = '<input type="number" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="wpb_vc_param_value textfield ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . esc_attr( $value ) . '" ' . $dependency . ' style="max-width:100px; margin-right: 10px;" />' . $suffix;
			
			return $output;
		}
		
		public function select_preview_field( $settings, $value ) {
			ob_start();
			// Get menus list
			$options = $settings['value'];
			$default = $settings['default'];
			if ( is_array( $options ) && count( $options ) > 0 ) {
				$uniqeID = uniqid();
				$i       = 0;
				?>
                <div class="container-select_preview">
                    <select id="lebe_select_preview-<?php echo esc_attr( $uniqeID ); ?>"
                            name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                            class="lebe_select_preview vc_select_image wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field">
						<?php foreach ( $options as $k => $option ): ?>
							<?php
							if ( $i == 0 ) {
								$first_value = $k;
							}
							$i ++;
							?>
							<?php $selected = ( $k == $value ) ? ' selected="selected"' : ''; ?>
                            <option data-img="<?php echo esc_url( $option['img'] ); ?>"
                                    value='<?php echo esc_attr( $k ) ?>' <?php echo esc_attr( $selected ) ?>><?php echo esc_attr( $option['alt'] ) ?></option>
						<?php endforeach; ?>
                    </select>
                    <div class="image-preview">
						<?php if ( isset( $options[ $value ] ) && $options[ $value ] && ( isset( $options[ $value ]['img'] ) ) ): ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $value ]['img'] ); ?>">
						<?php else: ?>
                            <img style="margin-top: 10px; max-width: 100%;height: auto;"
                                 src="<?php echo esc_url( $options[ $default ]['img'] ); ?>">
						<?php endif; ?>
                    </div>
                </div>
				<?php
			}
			
			return ob_get_clean();
		}
		
		/**
		 * Suggester for autocomplete by id/name/title/sku
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 * @return array - id's from products with title/sku.
		 */
		public function productIdAutocompleteSuggester( $query ) {
			global $wpdb;
			$product_id      = (int) $query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title, b.meta_value AS sku
    					FROM {$wpdb->posts} AS a
    					LEFT JOIN ( SELECT meta_value, post_id  FROM {$wpdb->postmeta} WHERE `meta_key` = '_sku' ) AS b ON b.post_id = a.ID
    					WHERE a.post_type = 'product' AND ( a.ID = '%d' OR b.meta_value LIKE '%%%s%%' OR a.post_title LIKE '%%%s%%' )", $product_id > 0 ? $product_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'lebe' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'lebe' ) . ': ' . $value['title'] : '' ) . ( ( strlen( $value['sku'] ) > 0 ) ? ' - ' . esc_html__( 'Sku', 'lebe' ) . ': ' . $value['sku'] : '' );
					$results[]     = $data;
				}
			}
			
			return $results;
		}
		
		/**
		 * Find product by id
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function productIdAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get product
				$product_object = wc_get_product( (int) $query );
				if ( is_object( $product_object ) ) {
					$product_sku         = $product_object->get_sku();
					$product_title       = $product_object->get_title();
					$product_id          = $product_object->get_id();
					$product_sku_display = '';
					if ( ! empty( $product_sku ) ) {
						$product_sku_display = ' - ' . esc_html__( 'Sku', 'lebe' ) . ': ' . $product_sku;
					}
					$product_title_display = '';
					if ( ! empty( $product_title ) ) {
						$product_title_display = ' - ' . esc_html__( 'Title', 'lebe' ) . ': ' . $product_title;
					}
					$product_id_display = esc_html__( 'Id', 'lebe' ) . ': ' . $product_id;
					$data               = array();
					$data['value']      = $product_id;
					$data['label']      = $product_id_display . $product_title_display . $product_sku_display;
					
					return ! empty( $data ) ? $data : false;
				}
				
				return false;
			}
			
			return false;
		}
		
		/**
		 * Suggester for autocomplete by id/name/title
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 * @return array - id's from post_types with title/.
		 */
		public function pinmapIdAutocompleteSuggester( $query ) {
			global $wpdb;
			$post_type_id    = (int) $query;
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.ID AS id, a.post_title AS title 
    					FROM {$wpdb->posts} AS a 
    					WHERE a.post_type = 'lebe_mapper' AND ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )", $post_type_id > 0 ? $post_type_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
			);
			$results         = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['id'];
					$data['label'] = esc_html__( 'Id', 'lebe' ) . ': ' . $value['id'] . ( ( strlen( $value['title'] ) > 0 ) ? ' - ' . esc_html__( 'Title', 'lebe' ) . ': ' . $value['title'] : '' );
					$results[]     = $data;
				}
			}
			
			return $results;
		}
		
		/**
		 * Find product by id
		 *
		 * @since  1.0
		 *
		 * @param $query
		 *
		 * @author Reapple
		 *
		 * @return bool|array
		 */
		public function pinmapIdAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get post_type
				$post_type_object = wc_get_post_type( (int) $query );
				if ( is_object( $post_type_object ) ) {
					$post_type_title = $post_type_object->get_title();
					$post_type_id    = $post_type_object->get_id();
					
					$post_type_title_display = '';
					if ( ! empty( $post_type_title ) ) {
						$post_type_title_display = ' - ' . esc_html__( 'Title', 'lebe' ) . ': ' . $post_type_title;
					}
					$post_type_id_display = esc_html__( 'Id', 'lebe' ) . ': ' . $post_type_id;
					$data                 = array();
					$data['value']        = $post_type_id;
					$data['label']        = $post_type_id_display . $post_type_title_display;
					
					return ! empty( $data ) ? $data : false;
				}
				
				return false;
			}
			
			return false;
		}
		
		public function vc_fonts( $fonts_list ) {
			/* Gotham */
			$Gotham              = new stdClass();
			$Gotham->font_family = "Gotham";
			$Gotham->font_styles = "100,300,400,600,700";
			$Gotham->font_types  = "300 Light:300:light,400 Normal:400:normal";
			
			$fonts = array( $Gotham );
			
			return array_merge( $fonts_list, $fonts );
		}
		
		/* Custom Font icon*/
		function iconpicker_type_lebe_customfonts( $icons ) {
			$icons['Flaticon'] = array(
				array( 'flaticon-profile' => 'Flaticon profile' ),
				array( 'flaticon-bag' => 'Flaticon bag' ),
				array( 'flaticon-heart' => 'Flaticon heart' ),
				array( 'flaticon-valentines-heart' => 'Flaticon valentines heart' ),
				array( 'flaticon-magnifying-glass-browser' => 'Flaticon magnifying glass browser' ),
				array( 'flaticon-instagram' => 'Flaticon instagram' ),
				array( 'flaticon-package' => 'Flaticon package' ),
				array( 'flaticon-box' => 'Flaticon box' ),
				array( 'flaticon-shield' => 'Flaticon shield' ),
				array( 'flaticon-filter' => 'Flaticon filter' ),
				array( 'flaticon-button-symbol-of-nine-dots' => 'Flaticon button symbol of nine dots' ),
				array( 'flaticon-loading' => 'Flaticon loading' ),
				array( 'flaticon-comment' => 'Flaticon comment' ),
				array( 'flaticon-contact' => 'Flaticon contact' ),
				array( 'flaticon-contact-1' => 'Flaticon contact 1' ),
				array( 'flaticon-wave' => 'Flaticon wave' ),
				array( 'flaticon-anchor' => 'Flaticon anchor' ),
				array( 'flaticon-fish' => 'Flaticon fish' ),
				array( 'flaticon-credit-card' => 'Flaticon credit card' ),
				array( 'flaticon-safety' => 'Flaticon safety' ),
				array( 'flaticon-delivery-truck' => 'Flaticon delivery truck' ),
				array( 'flaticon-credit-card-1' => 'Flaticon credit card 1' ),
				array( 'flaticon-support-1' => 'Flaticon support 1' ),
				array( 'flaticon-shuffle-arrows' => 'Flaticon shuffle arrows' ),
				array( 'flaticon-tick' => 'Flaticon tick' ),
				array( 'flaticon-close' => 'Flaticon close' ),
				array( 'flaticon-share' => 'Flaticon share' ),
				array( 'flaticon-right-arrow' => 'Flaticon right arrow' ),
				array( 'flaticon-left-arrow' => 'Flaticon left arrow' ),
				array( 'flaticon-tailor' => 'Flaticon tailor' ),
				array( 'flaticon-protection-shield-with-a-check-mark' => 'Flaticon protection shield with a check mark' ),
				array( 'flaticon-safe' => 'Flaticon safe' ),
				array( 'flaticon-chat' => 'Flaticon chat' ),
				array( 'flaticon-round-done-button' => 'Flaticon round done button' ),
				array( 'flaticon-check' => 'Flaticon check' ),
				array( 'flaticon-telephone' => 'Flaticon telephone' ),
				array( 'flaticon-old-handphone' => 'Flaticon old handphone' ),
				array( 'flaticon-placeholder' => 'Flaticon placeholder' ),
				array( 'flaticon-login-square-arrow-button-outline' => 'Flaticon login square arrow button outline' ),
				array( 'flaticon-dove' => 'Flaticon dove' ),
				array( 'flaticon-login' => 'Flaticon login' ),
				array( 'flaticon-arrow' => 'Flaticon arrow' ),
				array( 'flaticon-mail' => 'Flaticon mail' ),
				array( 'flaticon-truck' => 'Flaticon truck' ),
				array( 'flaticon-support' => 'Flaticon support' ),
			);
			
			return $icons;
		}
		
		public function animation_on_scroll() {
		    return array(
			    esc_html__( 'None', 'lebe' )      => '',
			    esc_html__( 'Smooth Up', 'lebe' ) => 'lebe-wow fadeInUp',
			    esc_html__( 'Smooth Down', 'lebe' ) => 'lebe-wow fadeInDown',
			    esc_html__( 'Smooth Left', 'lebe' ) => 'lebe-wow fadeInLeft',
			    esc_html__( 'Smooth Right', 'lebe' ) => 'lebe-wow fadeInRight',
		    );
        }
		
		public function map_shortcode() {
			
			vc_map(
				array(
					'base'        => 'lebe_adv_text',
					'name'        => esc_html__( 'Lebe: Advance Text', 'lebe' ),
					'icon'        => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Creates 2 different text versions on mobile and desktop', 'lebe' ),
					'params'      => array(
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Text', 'lebe' ),
							'param_name'  => 'none_mobile_text',
							'admin_label' => true
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Mobile Text', 'lebe' ),
							'param_name'  => 'mobile_text',
							'admin_label' => true
						)
					),
				)
            );
		    
			/* Map New Banner */
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Banner', 'lebe' ),
					'base'        => 'lebe_banner', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a Banner list.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'banner.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-04.jpg',
								),
								'style-05' => array(
									'alt' => 'Style 05', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-05.jpg',
								),
								'style-06' => array(
									'alt' => 'Style 06', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-06.jpg',
								),
								'style-07' => array(
									'alt' => 'Style 07', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-07.jpg',
								),
								'style-08' => array(
									'alt' => 'Style 08', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-08.jpg',
								),
								'style-09' => array(
									'alt' => 'Style 09', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-09.jpg',
								),
								'style-10' => array(
									'alt' => 'Style 10', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-10.jpg',
								),
								'style-11' => array(
									'alt' => 'Style 11', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-11.jpg',
								),
								'style-12' => array(
									'alt' => 'Style 12', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-12.jpg',
								),
								'style-13' => array(
									'alt' => 'Style 13', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-13.jpg',
								),
								'style-14' => array(
									'alt' => 'Style 14', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-14.jpg',
								),
								'style-15' => array(
									'alt' => 'Style 15', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'banner/style-15.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'position',
							'heading'    => esc_html__( 'Select Position Image', 'lebe' ),
							'value'      => array(
								esc_html__( 'Image Left', 'lebe' )  => '',
								esc_html__( 'Image Right', 'lebe' ) => 'right',
							),
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-07' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'border',
							'heading'    => esc_html__( 'Select Border', 'lebe' ),
							'value'      => array(
								esc_html__( 'No border', 'lebe' )  => '',
								esc_html__( 'Has border', 'lebe' ) => 'border',
							),
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-02' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'lebe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
									'style-07',
									'style-09',
									'style-10',
									'style-11',
									'style-12',
									'style-13'
								),
							),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Big Title', 'lebe' ),
							'param_name'  => 'bigtitle',
							'description' => esc_html__( 'The title of shortcode', 'lebe' ),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Description', 'lebe' ),
							'param_name' => 'desc',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-03', 'style-07', 'style-09', 'style-14', 'style-15' ),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Banner Link', 'lebe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add banner link.', 'lebe' ),
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image", "lebe" ),
							"param_name"  => "image",
							"admin_label" => false,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'overlay',
							'heading'    => esc_html__( 'Select Overlay', 'lebe' ),
							'value'      => array(
								esc_html__( 'Big', 'lebe' )   => '',
								esc_html__( 'Small', 'lebe' ) => 'small',
							),
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'banner_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section Buttonvideo*/
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Button', 'lebe' ),
					'base'        => 'lebe_button', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display Button.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'button/style-01.jpg'
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'button/style-02.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Button Link', 'lebe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add button link.', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'align',
							'heading'    => esc_html__( 'Text align', 'lebe' ),
							'value'      => array(
								esc_html__( 'Left', 'lebe' )   => '',
								esc_html__( 'Center', 'lebe' ) => 'text-center',
							),
							'std'        => '',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'button_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/*Section Buttonvideo*/
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Button Video', 'lebe' ),
					'base'        => 'lebe_buttonvideo', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display Button Video.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'buttonvideo/style-01.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__( 'Image', 'lebe' ),
							'param_name' => 'image',
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Link Video", "lebe" ),
							"param_name" => "link_video",
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'buttonvideo_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/* Map New blog */
			$categories_array = array(
				esc_html__( 'All', 'lebe' ) => '',
			);
			$args             = array();
			$categories       = get_categories( $args );
			foreach ( $categories as $category ) {
				$categories_array[ $category->name ] = $category->slug;
			}
			
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Blog', 'lebe' ),
					'base'        => 'lebe_blog', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a blog list.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'blog.png',
					'params'      => array(
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Section title", "lebe" ),
							"param_name" => "title",
						),
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'blog/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'blog/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'blog/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'blog/style-04.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Number Post', 'lebe' ),
							'param_name'  => 'per_page',
							'std'         => 10,
							'admin_label' => true,
							'description' => esc_html__( 'Number post in a slide', 'lebe' ),
						),
						array(
							'param_name'  => 'category_slug',
							'type'        => 'dropdown',
							'value'       => $categories_array,
							'heading'     => esc_html__( 'Category filter:', 'lebe' ),
							"admin_label" => true,
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'lebe' ),
							"param_name"  => "orderby",
							"value"       => array(
								esc_html__( 'None', 'lebe' )     => 'none',
								esc_html__( 'ID', 'lebe' )       => 'ID',
								esc_html__( 'Author', 'lebe' )   => 'author',
								esc_html__( 'Name', 'lebe' )     => 'name',
								esc_html__( 'Date', 'lebe' )     => 'date',
								esc_html__( 'Modified', 'lebe' ) => 'modified',
								esc_html__( 'Rand', 'lebe' )     => 'rand',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort retrieved posts.", 'lebe' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'lebe' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'lebe' )  => 'ASC',
								esc_html__( 'DESC', 'lebe' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'lebe' ),
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'lebe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'lebe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'lebe' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Arrow', 'lebe' )       => '',
								esc_html__( 'Cirle Arrow', 'lebe' ) => 'nav-circle',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation Type', 'lebe' ),
							'param_name'  => 'nav_type',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'lebe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'dots-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'lebe' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'lebe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'lebe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'lebe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'lebe' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							'value'      => array(
								esc_html__( 'No', 'lebe' )  => '',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'        => '',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'lebe' ),
							"param_name"  => "ls_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'lebe' ),
							"param_name"  => "lg_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'lebe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'lebe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'lebe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'lebe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'blog_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Map New Category */
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Category', 'lebe' ),
					'base'        => 'lebe_categories', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display Category.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'cat.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'categories/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'categories/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'categories/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'categories/style-04.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'lebe' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => false,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'lebe' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'lebe' ),
							'std'         => '',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'categories_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Custom menu*/
			$all_menu = array();
			$menus    = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			if ( $menus && count( $menus ) > 0 ) {
				foreach ( $menus as $m ) {
					$all_menu[ $m->name ] = $m->slug;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Custom Menu', 'lebe' ),
					'base'        => 'lebe_custommenu', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a custom menu.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'custom-menu.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'custommenu/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'custommenu/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'custommenu/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'custommenu/style-04.jpg',
								),
								'style-05' => array(
									'alt' => 'Style 05',
									'img' => LEBE_SHORTCODE_PREVIEW . 'custommenu/style-05.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Text Color', 'lebe' ),
							'param_name'  => 'text_color',
							'description' => esc_html__( 'Text Color', 'lebe' ),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Text Left', 'lebe' )   => '',
								esc_html__( 'Text Right', 'lebe' )  => 'right',
								esc_html__( 'Text Center', 'lebe' ) => 'center',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Text align', 'lebe' ),
							'param_name'  => 'align',
							'description' => esc_html__( 'Text align', 'lebe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02', 'style-03' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'lebe' ),
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01' ),
							),
							'std'         => '',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Menu', 'lebe' ),
							'param_name'  => 'menu',
							'value'       => $all_menu,
							'description' => esc_html__( 'Select menu to display.', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'custommenu_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Demo', 'lebe' ),
					'base'        => 'lebe_demo', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a Banner list.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'banner.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'demo/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'demo/style-02.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Image", "lebe" ),
							"param_name"  => "image",
							"admin_label" => false,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Description', 'lebe' ),
							'param_name'  => 'des',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02' ),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'Demo Link', 'lebe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add demo link.', 'lebe' ),
							'admin_label' => true,
						),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => esc_html__( 'Comming soon mode', 'lebe' ),
                            'value'      => array(
                                esc_html__( 'Off', 'lebe' )  => '',
                                esc_html__( 'On', 'lebe' ) => 'comming-mode',
                            ),
                            'param_name' => 'comming',
                            'std'        => '',
                            'dependency'  => array(
                                'element' => 'style',
                                'value'   => array( 'style-01' ),
                            ),
                        ),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'demo_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section IconBox*/
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Icon Box', 'lebe' ),
					'base'        => 'lebe_iconbox', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display Iconbox.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'iconbox.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Layout', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-01.jpg'
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-02.jpg'
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-03.jpg'
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-04.jpg'
								),
								'style-05' => array(
									'alt' => 'Style 05',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-05.jpg'
								),
								'style-06' => array(
									'alt' => 'Style 06',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-06.jpg'
								),
								'style-07' => array(
									'alt' => 'Style 07',
									'img' => LEBE_SHORTCODE_PREVIEW . 'iconbox/style-07.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose icon or image', 'lebe' ),
							'value'      => array(
								esc_html__( 'Icon', 'lebe' )  => '',
								esc_html__( 'Image', 'lebe' ) => 'imagetype',
							),
							'param_name' => 'iconimage',
							'std'        => '',
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "Image custom", "lebe" ),
							"param_name" => "image",
							'dependency' => array(
								'element' => 'iconimage',
								'value'   => 'imagetype',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'lebe' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'lebe' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'lebe' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'lebe' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => array( '' ),
							),
						),
						array(
							'param_name'  => 'icon_lebecustomfonts',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'lebecustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
						),
						array(
							'type'       => 'colorpicker',
							'heading'    => esc_html__( 'Background Icon', 'lebe' ),
							'param_name' => 'bg_icon',
							'value'      => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The Title of IconBox.', 'lebe' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Description', 'lebe' ),
							'param_name'  => 'des',
							'description' => esc_html__( 'The Description of IconBox.', 'lebe' ),
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03', 'style-05', 'style-07' ),
							),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'URL (Link)', 'lebe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link.', 'lebe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-06', 'style-07' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'lebe' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'iconbox_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					
					)
				)
			);
			/* Map New Section tabs */
			vc_map(
				array(
					'name'                      => esc_html__( 'Section', 'lebe' ),
					'base'                      => 'vc_tta_section',
					'icon'                      => 'icon-wpb-ui-tta-section',
					'allowed_container_element' => 'vc_row',
					'is_container'              => true,
					'show_settings_on_create'   => false,
					'as_child'                  => array(
						'only' => 'vc_tta_tour,vc_tta_tabs,vc_tta_accordion',
					),
					'category'                  => esc_html__( 'Content', 'lebe' ),
					'description'               => esc_html__( 'Section for Tabs, Tours, Accordions.', 'lebe' ),
					'params'                    => array(
						array(
							'type'        => 'textfield',
							'param_name'  => 'title',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'description' => esc_html__( 'Enter section title (Note: you can leave it empty).', 'lebe' ),
						),
						array(
							'type'        => 'el_id',
							'param_name'  => 'tab_id',
							'settings'    => array(
								'auto_generate' => true,
							),
							'heading'     => esc_html__( 'Section ID', 'lebe' ),
							'description' => esc_html__( 'Enter section ID (Note: make sure it is unique and valid according to w3c specification.', 'lebe' )
						),
						array(
							'type'        => 'checkbox',
							'param_name'  => 'add_icon',
							'heading'     => esc_html__( 'Add icon?', 'lebe' ),
							'description' => esc_html__( 'Add icon next to section title.', 'lebe' ),
						),
						array(
							'type'        => 'dropdown',
							'param_name'  => 'i_position',
							'value'       => array(
								esc_html__( 'Before title', 'lebe' ) => 'left',
								esc_html__( 'After title', 'lebe' )  => 'right',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'heading'     => esc_html__( 'Icon position', 'lebe' ),
							'description' => esc_html__( 'Select icon position.', 'lebe' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'lebe' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'lebe' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'lebe' ) => 'fontflaticon',
							),
							'dependency'  => array(
								'element' => 'add_icon',
								'value'   => 'true',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'std'         => 'fontawesome',
							'description' => esc_html__( 'Select icon library.', 'lebe' ),
						),
						array(
							'param_name'  => 'icon_lebecustomfonts',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'lebecustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							// default value to backend editor admin_label
							'settings'    => array(
								'emptyIcon'    => false,
								// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,
								// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'lebe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'lebe' ),
						),
					),
					'js_view'                   => 'VcBackendTtaSectionView',
					'custom_markup'             => '
                    <div class="vc_tta-panel-heading">
                        <h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
                    </div>
                    <div class="vc_tta-panel-body">
                        {{ editor_controls }}
                        <div class="{{ container-class }}">
                        {{ content }}
                        </div>
                    </div>',
					'default_content'           => '',
				)
			);
			
			/*Map New section title */
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Section Title', 'lebe' ),
					'base'        => 'lebe_title',
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a custom title.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'section-title.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style1' => array(
									'alt' => 'Style 01', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-01.jpg',
								),
								'style2' => array(
									'alt' => 'Style 02', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-02.jpg',
								),
								'style3' => array(
									'alt' => 'Style 03', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-03.jpg',
								),
								'style4' => array(
									'alt' => 'Style 04', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-04.jpg',
								),
								'style5' => array(
									'alt' => 'Style 05', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-05.jpg',
								),
								'style6' => array(
									'alt' => 'Style 06', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-06.jpg',
								),
								'style7' => array(
									'alt' => 'Style 07', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-07.jpg',
								),
								'style8' => array(
									'alt' => 'Style 08', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'title/style-08.jpg',
								),
							),
							'default'     => 'style1',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'lebe' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'param_name' => 'text_color',
							'heading'    => esc_html__( 'Text Color', 'lebe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'light',
							),
							'std'        => '',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'title_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			// Map new Tabs element.
			vc_map(
				array(
					'name'                    => esc_html__( 'Lebe: Tabs', 'lebe' ),
					'base'                    => 'lebe_tabs',
					'icon'                    => LEBE_SHORTCODES_ICONS_URI . 'tabs.png',
					'is_container'            => true,
					'show_settings_on_create' => false,
					'as_parent'               => array(
						'only' => 'vc_tta_section',
					),
					'category'                => esc_html__( 'Lebe Elements', 'lebe' ),
					'description'             => esc_html__( 'Tabs content', 'lebe' ),
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01', //LEBE_SHORTCODE_PREVIEW
									'img' => LEBE_SHORTCODE_PREVIEW . 'tabs/style-01.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						vc_map_add_css_animation(),
						array(
							'param_name' => 'ajax_check',
							'heading'    => esc_html__( 'Using Ajax Tabs', 'lebe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Yes', 'lebe' ) => '1',
								esc_html__( 'No', 'lebe' )  => '0',
							),
							'std'        => '0',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Active Section', 'lebe' ),
							'param_name' => 'active_section',
							'std'        => '1',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Padding Tabs', 'lebe' ),
							'param_name'  => 'padding_tabs',
							'std'         => '0',
							'description' => esc_html__( 'Ex: 60px', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'lebe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'lebe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'tabs_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'             => 'checkbox',
							'param_name'       => 'collapsible_all',
							'heading'          => esc_html__( 'Allow collapse all?', 'lebe' ),
							'description'      => esc_html__( 'Allow collapse all accordion sections.', 'lebe' ),
							'edit_field_class' => 'hidden',
						),
					),
					'js_view'                 => 'VcBackendTtaTabsView',
					'custom_markup'           => '
                    <div class="vc_tta-container" data-vc-action="collapse">
                        <div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
                            <div class="vc_tta-tabs-container">'
					                             . '<ul class="vc_tta-tabs-list">'
					                             . '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="vc_tta_section"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>'
					                             . '</ul>
                            </div>
                            <div class="vc_tta-panels vc_clearfix {{container-class}}">
                              {{ content }}
                            </div>
                        </div>
                    </div>',
					'default_content'         => '
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'lebe' ), 1 ) . '"][/vc_tta_section]
                        [vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Tab', 'lebe' ), 2 ) . '"][/vc_tta_section]
                    ',
					'admin_enqueue_js'        => array(
						vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ),
					),
				)
			);
			
			// Map new Products
			// CUSTOM PRODUCT SIZE
			$product_size_width_list = array();
			$width                   = 300;
			$height                  = 300;
			$crop                    = 1;
			if ( function_exists( 'wc_get_image_size' ) ) {
				$size      = array(
					"width"  => 300,
					"height" => 300,
					"crop"   => 1,
				);
				$width  = isset( $size['width'] ) ? $size['width'] : $width;
				$height = isset( $size['height'] ) ? $size['height'] : $height;
				$crop   = isset( $size['crop'] ) ? $size['crop'] : $crop;
			}
			for ( $i = 100; $i < $width; $i = $i + 10 ) {
				array_push( $product_size_width_list, $i );
			}
			$product_size_list                           = array();
			$product_size_list[ $width . 'x' . $height ] = $width . 'x' . $height;
			foreach ( $product_size_width_list as $k => $w ) {
				$w = intval( $w );
				if ( isset( $width ) && $width > 0 ) {
					$h = round( $height * $w / $width );
				} else {
					$h = $w;
				}
				$product_size_list[ $w . 'x' . $h ] = $w . 'x' . $h;
			}
			$product_size_list['Custom'] = 'custom';
			$attributes_tax              = array();
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes_tax = wc_get_attribute_taxonomies();
			}
			
			$attributes = array();
			if ( is_array( $attributes_tax ) && count( $attributes_tax ) > 0 ) {
				foreach ( $attributes_tax as $attribute ) {
					$attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Products', 'lebe' ),
					'base'        => 'lebe_products', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a product list or grid.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'product.png',
					'params'      => array(
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Product List style', 'lebe' ),
							'param_name'  => 'productsliststyle',
							'value'       => array(
								esc_html__( 'Grid Bootstrap', 'lebe' ) => 'grid',
								esc_html__( 'Owl Carousel', 'lebe' )   => 'owl',
							),
							'description' => esc_html__( 'Select a style for list', 'lebe' ),
							'admin_label' => true,
							'std'         => 'grid',
						),
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Product style', 'lebe' ),
							'value'       => array(
								'1' => array(
									'alt' => esc_html__( 'Style 01', 'lebe' ),
									'img' => LEBE_PRODUCT_STYLE_PREVIEW . 'content-product-style-1.jpg',
								),
								'2' => array(
									'alt' => esc_html__( 'Style 02', 'lebe' ),
									'img' => LEBE_PRODUCT_STYLE_PREVIEW . 'content-product-style-2.jpg',
								),
								'3' => array(
									'alt' => esc_html__( 'Style 03', 'lebe' ),
									'img' => LEBE_PRODUCT_STYLE_PREVIEW . 'content-product-style-3.jpg',
								),
								'4' => array(
									'alt' => esc_html__( 'Style 04', 'lebe' ),
									'img' => LEBE_PRODUCT_STYLE_PREVIEW . 'content-product-style-4.jpg',
								),
								'5' => array(
									'alt' => esc_html__( 'Style 05', 'lebe' ),
									'img' => LEBE_PRODUCT_STYLE_PREVIEW . 'content-product-style-5.jpg',
								),
							),
							'default'     => '1',
							'admin_label' => true,
							'param_name'  => 'product_style',
							'description' => esc_html__( 'Select a style for product item', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Show label sale, new', 'lebe' ),
							'param_name' => 'show_label',
							'value'      => array(
								esc_html__( 'Yes', 'lebe' ) => '',
								esc_html__( 'No', 'lebe' )  => 'no-label',
							),
							'std'        => '',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Select type color', 'lebe' ),
							'param_name' => 'type_color',
							'value'      => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'light',
							),
							'std'        => '',
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Total items', 'lebe' ),
							'param_name' => 'per_page',
							'value'      => 10,
							"dependency" => array(
								"element" => "target",
								"value"   => array(
									'best-selling',
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'product_attribute',
									'on_sale',
									'on_new'
								)
							),
						),
						
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image size', 'lebe' ),
							'param_name'  => 'product_image_size',
							'value'       => $product_size_list,
							'description' => esc_html__( 'Select a size for product', 'lebe' ),
							'std'         => '320x387',
							'admin_label' => true,
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Width", 'lebe' ),
							"param_name" => "product_custom_thumb_width",
							"value"      => $width,
							"suffix"     => esc_html__( "px", 'lebe' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Height", 'lebe' ),
							"param_name" => "product_custom_thumb_height",
							"value"      => $height,
							"suffix"     => esc_html__( "px", 'lebe' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						array(
							'type'        => 'vc_link',
							'heading'     => esc_html__( 'View All Button', 'lebe' ),
							'param_name'  => 'link',
							'description' => esc_html__( 'Add link.', 'lebe' ),
						),
						/*Products */
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'lebe' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => true,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'lebe' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'lebe' ),
							'std'         => '',
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Target', 'lebe' ),
							'param_name'  => 'target',
							'value'       => array(
								esc_html__( 'Best Selling Products', 'lebe' ) => 'best-selling',
								esc_html__( 'Top Rated Products', 'lebe' )    => 'top-rated',
								esc_html__( 'Recent Products', 'lebe' )       => 'recent-product',
								esc_html__( 'Product Category', 'lebe' )      => 'product-category',
								esc_html__( 'Products', 'lebe' )              => 'products',
								esc_html__( 'Featured Products', 'lebe' )     => 'featured_products',
								esc_html__( 'On Sale', 'lebe' )               => 'on_sale',
								esc_html__( 'On New', 'lebe' )                => 'on_new',
							),
							'description' => esc_html__( 'Choose the target to filter products', 'lebe' ),
							'std'         => 'recent-product',
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'lebe' ),
							"param_name"  => "orderby",
							"value"       => array(
								'',
								esc_html__( 'Date', 'lebe' )          => 'date',
								esc_html__( 'ID', 'lebe' )            => 'ID',
								esc_html__( 'Author', 'lebe' )        => 'author',
								esc_html__( 'Title', 'lebe' )         => 'title',
								esc_html__( 'Modified', 'lebe' )      => 'modified',
								esc_html__( 'Random', 'lebe' )        => 'rand',
								esc_html__( 'Comment count', 'lebe' ) => 'comment_count',
								esc_html__( 'Menu order', 'lebe' )    => 'menu_order',
								esc_html__( 'Sale price', 'lebe' )    => '_sale_price',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort.", 'lebe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array(
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'on_sale',
									'on_new',
									'product_attribute'
								)
							),
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'lebe' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'lebe' )  => 'ASC',
								esc_html__( 'DESC', 'lebe' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'lebe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array(
									'top-rated',
									'recent-product',
									'product-category',
									'featured_products',
									'on_sale',
									'on_new',
									'product_attribute'
								)
							),
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Products', 'lebe' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => true,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
							'description' => esc_html__( 'Enter List of Products', 'lebe' ),
							"dependency"  => array( "element" => "target", "value" => array( 'products' ) ),
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						/* OWL Settings */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'lebe' )  => '1',
								esc_html__( '2 Rows', 'lebe' ) => '2',
								esc_html__( '3 Rows', 'lebe' ) => '3',
								esc_html__( '4 Rows', 'lebe' ) => '4',
								esc_html__( '5 Rows', 'lebe' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'lebe' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'lebe' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'lebe' ) => 'rows-space-0',
								esc_html__( '10px', 'lebe' )    => 'rows-space-10',
								esc_html__( '20px', 'lebe' )    => 'rows-space-20',
								esc_html__( '30px', 'lebe' )    => 'rows-space-30',
								esc_html__( '40px', 'lebe' )    => 'rows-space-40',
								esc_html__( '50px', 'lebe' )    => 'rows-space-50',
								esc_html__( '60px', 'lebe' )    => 'rows-space-60',
								esc_html__( '70px', 'lebe' )    => 'rows-space-70',
								esc_html__( '80px', 'lebe' )    => 'rows-space-80',
								esc_html__( '90px', 'lebe' )    => 'rows-space-90',
								esc_html__( '100px', 'lebe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							"dependency" => array(
								"element" => "owl_number_row",
								"value"   => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'lebe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Navigation', 'lebe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Center', 'lebe' ) => 'nav-center',
								esc_html__( 'Left', 'lebe' )   => 'nav-left',
							),
							'std'         => 'nav-center',
							'heading'     => esc_html__( 'Nav Position', 'lebe' ),
							'param_name'  => 'nav_position',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'lebe' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Arrow', 'lebe' )        => '',
								esc_html__( 'Circle Arrow', 'lebe' ) => 'nav-circle',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Nav Type', 'lebe' ),
							'param_name'  => 'nav_type',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Enable Dots', 'lebe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'dots-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'lebe' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Loop', 'lebe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'lebe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"suffix"      => esc_html__( "milliseconds", 'lebe' ),
							"description" => esc_html__( 'Slide speed in milliseconds', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'lebe' ),
							"param_name"  => "margin",
							"value"       => "0",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'lebe' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							'value'      => array(
								esc_html__( 'No', 'lebe' )  => '',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'        => '',
							"dependency" => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'lebe' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px and < 1500px )", 'lebe' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'lebe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'lebe' ),
							"param_name"  => "sm_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'lebe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'lebe' ),
							"param_name"  => "ts_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'owl' ),
							),
						),
						/* Bostrap setting */
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'lebe' ),
							'param_name' => 'boostrap_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'lebe' ) => 'rows-space-0',
								esc_html__( '10px', 'lebe' )    => 'rows-space-10',
								esc_html__( '20px', 'lebe' )    => 'rows-space-20',
								esc_html__( '30px', 'lebe' )    => 'rows-space-30',
								esc_html__( '40px', 'lebe' )    => 'rows-space-40',
								esc_html__( '50px', 'lebe' )    => 'rows-space-50',
								esc_html__( '60px', 'lebe' )    => 'rows-space-60',
								esc_html__( '70px', 'lebe' )    => 'rows-space-70',
								esc_html__( '80px', 'lebe' )    => 'rows-space-80',
								esc_html__( '90px', 'lebe' )    => 'rows-space-90',
								esc_html__( '100px', 'lebe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Boostrap settings', 'lebe' ),
							"dependency" => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'lebe' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '15',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'lebe' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on landscape tablet', 'lebe' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '3',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on portrait tablet', 'lebe' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '4',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'lebe' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '6',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'lebe' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							"dependency"  => array(
								"element" => "productsliststyle",
								"value"   => array( 'grid' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'products_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Deal Product', 'lebe' ),
					'base'        => 'lebe_dealproduct', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display deal product.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'product.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Style', 'lebe' ),
							'value'       => array(
								'01' => array(
									'alt' => esc_html__( 'Style 01', 'lebe' ),
									'img' => LEBE_PRODUCT_DEAL_PREVIEW . 'content-product-style-01.jpg',
								),
								'02' => array(
									'alt' => esc_html__( 'Style 02', 'lebe' ),
									'img' => LEBE_PRODUCT_DEAL_PREVIEW . 'content-product-style-02.jpg',
								),
							),
							'default'     => '01',
							'admin_label' => true,
							'param_name'  => 'style',
							'description' => esc_html__( 'Select a style for product item', 'lebe' ),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Image size', 'lebe' ),
							'param_name'  => 'product_image_size',
							'value'       => $product_size_list,
							'description' => esc_html__( 'Select a size for product', 'lebe' ),
							'std'         => '320x387',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Width", 'lebe' ),
							"param_name" => "product_custom_thumb_width",
							"value"      => $width,
							"suffix"     => esc_html__( "px", 'lebe' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						array(
							"type"       => "textfield",
							"heading"    => esc_html__( "Height", 'lebe' ),
							"param_name" => "product_custom_thumb_height",
							"value"      => $height,
							"suffix"     => esc_html__( "px", 'lebe' ),
							"dependency" => array( "element" => "product_image_size", "value" => array( 'custom' ) ),
						),
						/*Products */
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Target', 'lebe' ),
							'param_name'  => 'target',
							'value'       => array(
								esc_html__( 'On Sale', 'lebe' )  => 'on_sale',
								esc_html__( 'Products', 'lebe' ) => 'products',
							),
							'description' => esc_html__( 'Choose the target to filter products', 'lebe' ),
							'std'         => 'on_sale',
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						array(
							'type'        => 'autocomplete',
							'heading'     => esc_html__( 'Products', 'lebe' ),
							'param_name'  => 'ids',
							'settings'    => array(
								'multiple'      => true,
								'sortable'      => true,
								'unique_values' => true,
							),
							'save_always' => true,
							'description' => esc_html__( 'Enter List of Products', 'lebe' ),
							"dependency"  => array( "element" => "target", "value" => array( 'products' ) ),
							'group'       => esc_html__( 'Products options', 'lebe' ),
						),
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Total items', 'lebe' ),
							'param_name' => 'per_page',
							'value'      => 5,
						),
						array(
							"type"        => "taxonomy",
							"taxonomy"    => "product_cat",
							"class"       => "",
							"heading"     => esc_html__( "Product Category", 'lebe' ),
							"param_name"  => "taxonomy",
							"value"       => '',
							'parent'      => '',
							'multiple'    => true,
							'hide_empty'  => false,
							'placeholder' => esc_html__( 'Choose category', 'lebe' ),
							"description" => esc_html__( "Note: If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'lebe' ),
							'std'         => '',
							'group'       => esc_html__( 'Products options', 'lebe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array( 'on_sale' )
							),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order by", 'lebe' ),
							"param_name"  => "orderby",
							"value"       => array(
								'',
								esc_html__( 'Date', 'lebe' )          => 'date',
								esc_html__( 'ID', 'lebe' )            => 'ID',
								esc_html__( 'Author', 'lebe' )        => 'author',
								esc_html__( 'Title', 'lebe' )         => 'title',
								esc_html__( 'Modified', 'lebe' )      => 'modified',
								esc_html__( 'Random', 'lebe' )        => 'rand',
								esc_html__( 'Comment count', 'lebe' ) => 'comment_count',
								esc_html__( 'Menu order', 'lebe' )    => 'menu_order',
								esc_html__( 'Sale price', 'lebe' )    => '_sale_price',
							),
							'std'         => 'date',
							"description" => esc_html__( "Select how to sort.", 'lebe' ),
							'group'       => esc_html__( 'Products options', 'lebe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array( 'on_sale' )
							),
						),
						array(
							"type"        => "dropdown",
							"heading"     => esc_html__( "Order", 'lebe' ),
							"param_name"  => "order",
							"value"       => array(
								esc_html__( 'ASC', 'lebe' )  => 'ASC',
								esc_html__( 'DESC', 'lebe' ) => 'DESC',
							),
							'std'         => 'DESC',
							"description" => esc_html__( "Designates the ascending or descending order.", 'lebe' ),
							'group'       => esc_html__( 'Products options', 'lebe' ),
							"dependency"  => array(
								"element" => "target",
								"value"   => array( 'on_sale' )
							),
						),
						/* OWL Settings */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'lebe' )  => '1',
								esc_html__( '2 Rows', 'lebe' ) => '2',
								esc_html__( '3 Rows', 'lebe' ) => '3',
								esc_html__( '4 Rows', 'lebe' ) => '4',
								esc_html__( '5 Rows', 'lebe' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'lebe' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'lebe' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'lebe' ) => 'rows-space-0',
								esc_html__( '10px', 'lebe' )    => 'rows-space-10',
								esc_html__( '20px', 'lebe' )    => 'rows-space-20',
								esc_html__( '30px', 'lebe' )    => 'rows-space-30',
								esc_html__( '40px', 'lebe' )    => 'rows-space-40',
								esc_html__( '50px', 'lebe' )    => 'rows-space-50',
								esc_html__( '60px', 'lebe' )    => 'rows-space-60',
								esc_html__( '70px', 'lebe' )    => 'rows-space-70',
								esc_html__( '80px', 'lebe' )    => 'rows-space-80',
								esc_html__( '90px', 'lebe' )    => 'rows-space-90',
								esc_html__( '100px', 'lebe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							"dependency" => array(
								"element" => "owl_number_row",
								"value"   => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'lebe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Navigation', 'lebe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Center', 'lebe' ) => 'nav-center',
								esc_html__( 'Right', 'lebe' )  => 'nav-right',
							),
							'std'         => 'nav-center',
							'heading'     => esc_html__( 'Nav Position', 'lebe' ),
							'param_name'  => 'nav_position',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'lebe' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Arrow', 'lebe' )        => '',
								esc_html__( 'Circle Arrow', 'lebe' ) => 'nav-circle',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Nav Type', 'lebe' ),
							'param_name'  => 'nav_type',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => false,
							'heading'     => esc_html__( 'Enable Dots', 'lebe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'dots-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'lebe' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'lebe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"suffix"      => esc_html__( "milliseconds", 'lebe' ),
							"description" => esc_html__( 'Slide speed in milliseconds', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'lebe' ),
							"param_name"  => "margin",
							"value"       => "0",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'lebe' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							'value'      => array(
								esc_html__( 'No', 'lebe' )  => '',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'lebe' ),
							"param_name"  => "ls_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'lebe' ),
							"param_name"  => "lg_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'lebe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'lebe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'lebe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'lebe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( '02' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'dealproduct_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/* Instagram */
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Instagram', 'lebe' ),
					'base'        => 'lebe_instagram', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a instagram photo list.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'instagram.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagram/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagram/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagram/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagram/style-04.jpg',
								),
                                'style-05' => array(
									'alt' => 'Style 05',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagram/style-05.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose icon or image', 'lebe' ),
							'value'      => array(
								esc_html__( 'No use', 'lebe' ) => '',
								esc_html__( 'Icon', 'lebe' )   => 'icontype',
								esc_html__( 'Image', 'lebe' )  => 'imagetype',
							),
							'param_name' => 'iconimage',
							'std'        => '',
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "Image custom", "lebe" ),
							"param_name" => "image",
							'dependency' => array(
								'element' => 'iconimage',
								'value'   => 'imagetype',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'lebe' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'lebe' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'lebe' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'lebe' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => 'icontype',
							),
						),
						array(
							'param_name'  => 'icon_lebecustomfonts',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'lebecustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'lebe' ),
							'admin_label' => true,
							'std'         => '',
						),
						array(
							'type'       => 'textarea_html',
							'heading'    => esc_html__( 'Description', 'lebe' ),
							'param_name' => 'content',
							'std'        => '',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Images limit', 'lebe' ),
							'param_name'  => 'limit',
							'std'         => '6',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Instagram user ID', 'lebe' ),
							'param_name'  => 'id',
							'admin_label' => true,
							'description' => esc_html__( 'Your Instagram ID. Ex: 2267639447. ', 'lebe' ) . '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?', 'lebe' ) . '</a>',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Access token', 'lebe' ),
							'param_name'  => 'token',
							'description' => esc_html__( 'Your Instagram token. Ex: 2267639447.1677ed0.eade9f2bbe8245ea8bdedab984f3b4c3. ', 'lebe' ) . '<a href="http://instagram.pixelunion.net/" target="_blank">' . esc_html__( 'How to find?', 'lebe' ) . '</a>',
							'admin_label' => true,
						),
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( '1 Row', 'lebe' )  => '1',
								esc_html__( '2 Rows', 'lebe' ) => '2',
								esc_html__( '3 Rows', 'lebe' ) => '3',
								esc_html__( '4 Rows', 'lebe' ) => '4',
								esc_html__( '5 Rows', 'lebe' ) => '5',
							),
							'std'         => '1',
							'heading'     => esc_html__( 'The number of rows which are shown on block', 'lebe' ),
							'param_name'  => 'owl_number_row',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'lebe' ),
							'param_name' => 'owl_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'lebe' ) => 'rows-space-0',
								esc_html__( '10px', 'lebe' )    => 'rows-space-10',
								esc_html__( '20px', 'lebe' )    => 'rows-space-20',
								esc_html__( '30px', 'lebe' )    => 'rows-space-30',
								esc_html__( '40px', 'lebe' )    => 'rows-space-40',
								esc_html__( '50px', 'lebe' )    => 'rows-space-50',
								esc_html__( '60px', 'lebe' )    => 'rows-space-60',
								esc_html__( '70px', 'lebe' )    => 'rows-space-70',
								esc_html__( '80px', 'lebe' )    => 'rows-space-80',
								esc_html__( '90px', 'lebe' )    => 'rows-space-90',
								esc_html__( '100px', 'lebe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							"dependency" => array(
								"element" => "owl_number_row",
								"value"   => array( '2', '3', '4', '5' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'lebe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'lebe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'lebe' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'lebe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'dots-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'lebe' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false'
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'lebe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'lebe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'lebe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'lebe' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							'value'      => array(
								esc_html__( 'No', 'lebe' )  => '',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'lebe' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px )", 'lebe' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'lebe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'lebe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'lebe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'lebe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-04' ),
							),
						),
						/* Bostrap setting */
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Rows space', 'lebe' ),
							'param_name' => 'boostrap_rows_space',
							'value'      => array(
								esc_html__( 'Default', 'lebe' ) => 'rows-space-0',
								esc_html__( '10px', 'lebe' )    => 'rows-space-10',
								esc_html__( '20px', 'lebe' )    => 'rows-space-20',
								esc_html__( '30px', 'lebe' )    => 'rows-space-30',
								esc_html__( '40px', 'lebe' )    => 'rows-space-40',
								esc_html__( '50px', 'lebe' )    => 'rows-space-50',
								esc_html__( '60px', 'lebe' )    => 'rows-space-60',
								esc_html__( '70px', 'lebe' )    => 'rows-space-70',
								esc_html__( '80px', 'lebe' )    => 'rows-space-80',
								esc_html__( '90px', 'lebe' )    => 'rows-space-90',
								esc_html__( '100px', 'lebe' )   => 'rows-space-100',
							),
							'std'        => 'rows-space-0',
							'group'      => esc_html__( 'Boostrap settings', 'lebe' ),
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'lebe' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1500px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '15',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Desktop', 'lebe' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >= 1200px and < 1500px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '3',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on landscape tablet', 'lebe' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=992px and < 1200px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '3',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on portrait tablet', 'lebe' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=768px and < 992px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '4',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'lebe' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device >=480  add < 768px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '6',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Items per row on Mobile', 'lebe' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '1 item', 'lebe' )  => '12',
								esc_html__( '2 items', 'lebe' ) => '6',
								esc_html__( '3 items', 'lebe' ) => '4',
								esc_html__( '4 items', 'lebe' ) => '3',
								esc_html__( '5 items', 'lebe' ) => '15',
								esc_html__( '6 items', 'lebe' ) => '2',
							),
							'description' => esc_html__( '(Item per row on screen resolution of device < 480px)', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-05' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'instagram_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			
			
			/*Map new Container */
			vc_map(
				array(
					'name'                    => esc_html__( 'Lebe: Container', 'lebe' ),
					'base'                    => 'lebe_container',
					'category'                => esc_html__( 'Lebe Elements', 'lebe' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'is_container'            => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => LEBE_SHORTCODES_ICONS_URI . 'container.png',
					'params'                  => array(
						array(
							'param_name'  => 'content_width',
							'heading'     => esc_html__( 'Content width', 'lebe' ),
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Default', 'lebe' )         => 'container',
								esc_html__( 'Custom Boostrap', 'lebe' ) => 'custom_col',
								esc_html__( 'Custom Width', 'lebe' )    => 'custom_width',
							),
							'admin_label' => true,
							'std'         => 'container',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Desktop', 'lebe' ),
							'param_name'  => 'boostrap_bg_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'lebe' ) => '12',
								esc_html__( '11 column - 11/12', 'lebe' ) => '11',
								esc_html__( '10 column - 10/12', 'lebe' ) => '10',
								esc_html__( '9 column - 9/12', 'lebe' )   => '9',
								esc_html__( '8 column - 8/12', 'lebe' )   => '8',
								esc_html__( '7 column - 7/12', 'lebe' )   => '7',
								esc_html__( '6 column - 6/12', 'lebe' )   => '6',
								esc_html__( '5 column - 5/12', 'lebe' )   => '5',
								esc_html__( '4 column - 4/12', 'lebe' )   => '4',
								esc_html__( '3 column - 3/12', 'lebe' )   => '3',
								esc_html__( '2 column - 2/12', 'lebe' )   => '2',
								esc_html__( '1 column - 1/12', 'lebe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'lebe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'lebe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >= 1500px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '15',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Desktop', 'lebe' ),
							'param_name'  => 'boostrap_lg_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'lebe' ) => '12',
								esc_html__( '11 column - 11/12', 'lebe' ) => '11',
								esc_html__( '10 column - 10/12', 'lebe' ) => '10',
								esc_html__( '9 column - 9/12', 'lebe' )   => '9',
								esc_html__( '8 column - 8/12', 'lebe' )   => '8',
								esc_html__( '7 column - 7/12', 'lebe' )   => '7',
								esc_html__( '6 column - 6/12', 'lebe' )   => '6',
								esc_html__( '5 column - 5/12', 'lebe' )   => '5',
								esc_html__( '4 column - 4/12', 'lebe' )   => '4',
								esc_html__( '3 column - 3/12', 'lebe' )   => '3',
								esc_html__( '2 column - 2/12', 'lebe' )   => '2',
								esc_html__( '1 column - 1/12', 'lebe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'lebe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'lebe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >= 1200px and < 1500px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on landscape tablet', 'lebe' ),
							'param_name'  => 'boostrap_md_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'lebe' ) => '12',
								esc_html__( '11 column - 11/12', 'lebe' ) => '11',
								esc_html__( '10 column - 10/12', 'lebe' ) => '10',
								esc_html__( '9 column - 9/12', 'lebe' )   => '9',
								esc_html__( '8 column - 8/12', 'lebe' )   => '8',
								esc_html__( '7 column - 7/12', 'lebe' )   => '7',
								esc_html__( '6 column - 6/12', 'lebe' )   => '6',
								esc_html__( '5 column - 5/12', 'lebe' )   => '5',
								esc_html__( '4 column - 4/12', 'lebe' )   => '4',
								esc_html__( '3 column - 3/12', 'lebe' )   => '3',
								esc_html__( '2 column - 2/12', 'lebe' )   => '2',
								esc_html__( '1 column - 1/12', 'lebe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'lebe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'lebe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=992px and < 1200px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on portrait tablet', 'lebe' ),
							'param_name'  => 'boostrap_sm_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'lebe' ) => '12',
								esc_html__( '11 column - 11/12', 'lebe' ) => '11',
								esc_html__( '10 column - 10/12', 'lebe' ) => '10',
								esc_html__( '9 column - 9/12', 'lebe' )   => '9',
								esc_html__( '8 column - 8/12', 'lebe' )   => '8',
								esc_html__( '7 column - 7/12', 'lebe' )   => '7',
								esc_html__( '6 column - 6/12', 'lebe' )   => '6',
								esc_html__( '5 column - 5/12', 'lebe' )   => '5',
								esc_html__( '4 column - 4/12', 'lebe' )   => '4',
								esc_html__( '3 column - 3/12', 'lebe' )   => '3',
								esc_html__( '2 column - 2/12', 'lebe' )   => '2',
								esc_html__( '1 column - 1/12', 'lebe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'lebe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'lebe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=768px and < 992px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Mobile', 'lebe' ),
							'param_name'  => 'boostrap_xs_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'lebe' ) => '12',
								esc_html__( '11 column - 11/12', 'lebe' ) => '11',
								esc_html__( '10 column - 10/12', 'lebe' ) => '10',
								esc_html__( '9 column - 9/12', 'lebe' )   => '9',
								esc_html__( '8 column - 8/12', 'lebe' )   => '8',
								esc_html__( '7 column - 7/12', 'lebe' )   => '7',
								esc_html__( '6 column - 6/12', 'lebe' )   => '6',
								esc_html__( '5 column - 5/12', 'lebe' )   => '5',
								esc_html__( '4 column - 4/12', 'lebe' )   => '4',
								esc_html__( '3 column - 3/12', 'lebe' )   => '3',
								esc_html__( '2 column - 2/12', 'lebe' )   => '2',
								esc_html__( '1 column - 1/12', 'lebe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'lebe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'lebe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device >=480  add < 768px )', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Percent width row on Mobile', 'lebe' ),
							'param_name'  => 'boostrap_ts_items',
							'value'       => array(
								esc_html__( '12 column - 12/12', 'lebe' ) => '12',
								esc_html__( '11 column - 11/12', 'lebe' ) => '11',
								esc_html__( '10 column - 10/12', 'lebe' ) => '10',
								esc_html__( '9 column - 9/12', 'lebe' )   => '9',
								esc_html__( '8 column - 8/12', 'lebe' )   => '8',
								esc_html__( '7 column - 7/12', 'lebe' )   => '7',
								esc_html__( '6 column - 6/12', 'lebe' )   => '6',
								esc_html__( '5 column - 5/12', 'lebe' )   => '5',
								esc_html__( '4 column - 4/12', 'lebe' )   => '4',
								esc_html__( '3 column - 3/12', 'lebe' )   => '3',
								esc_html__( '2 column - 2/12', 'lebe' )   => '2',
								esc_html__( '1 column - 1/12', 'lebe' )   => '1',
								esc_html__( '1 column 5 - 1/5', 'lebe' )  => '15',
								esc_html__( '4 column 5 - 4/5', 'lebe' )  => '45',
							),
							'description' => esc_html__( '(Percent width row on screen resolution of device < 480px)', 'lebe' ),
							'group'       => esc_html__( 'Boostrap settings', 'lebe' ),
							'std'         => '12',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_col' ),
							),
						),
						array(
							'param_name'  => 'number_width',
							'heading'     => esc_html__( 'width', 'lebe' ),
							"description" => esc_html__( "you can width by px or %, ex: 100%", "lebe" ),
							'std'         => '50%',
							'admin_label' => true,
							'type'        => 'textfield',
							'dependency'  => array(
								'element' => 'content_width',
								'value'   => array( 'custom_width' ),
							),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'param_name'       => 'container_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/*Map New Newsletter*/
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Newsletter', 'lebe' ),
					'base'        => 'lebe_newsletter', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a newsletter box.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'newllter.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-04.jpg',
								),
								'style-05' => array(
									'alt' => 'Style 05',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-05.jpg',
								),
								'style-06' => array(
									'alt' => 'Style 06',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-06.jpg',
								),
								'style-07' => array(
									'alt' => 'Style 07',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-07.jpg',
								),
								'style-08' => array(
									'alt' => 'Style 08',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-08.jpg',
								),
								'style-09' => array(
									'alt' => 'Style 09',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-09.jpg',
								),
								'style-10' => array(
									'alt' => 'Style 10',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-10.jpg',
								),
								'style-11' => array(
									'alt' => 'Style 11',
									'img' => LEBE_SHORTCODE_PREVIEW . 'newsletter/style-11.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'param_name' => 'newsletter_type',
							'heading'    => esc_html__( 'Newsletter Type', 'lebe' ),
							'type'       => 'dropdown',
							'value'      => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'light',
							),
							'sdt'        => '',
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'lebe' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'lebe' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'lebe' ) => 'fontflaticon',
							),
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-02', 'style-03', 'style-08' ),
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'std'         => 'fontawesome',
							'description' => esc_html__( 'Select icon library.', 'lebe' ),
						),
						array(
							'param_name'  => 'icon_lebecustomfonts',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'lebecustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							// default value to backend editor admin_label
							'settings'    => array(
								'emptyIcon'    => false,
								// default true, display an "EMPTY" icon?
								'iconsPerPage' => 4000,
								// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'description' => esc_html__( 'The title of shortcode', 'lebe' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
									'style-01',
									'style-02',
									'style-03',
									'style-04',
									'style-05',
									'style-06',
									'style-07',
									'style-10',
									'style-11'
								),
							),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Description', 'lebe' ),
							'param_name' => 'description',
							'std'        => '',
							'dependency' => array(
								'element' => 'style',
								'value'   => array(
									'style-04',
									'style-05',
									'style-07',
									'style-09',
									'style-10',
									'style-11'
								),
							),
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Placeholder text", 'lebe' ),
							"param_name"  => "placeholder_text",
							"admin_label" => false,
							'std'         => 'Email address here',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Button text", 'lebe' ),
							"param_name"  => "submit_text",
							"admin_label" => false,
							'std'         => 'Subscribe',
							'dependency'  => array(
								'element' => 'style',
								'value'   => array(
									'style-02',
									'style-03',
									'style-04',
									'style-05',
									'style-06',
									'style-07',
									'style-08',
									'style-09',
									'style-10',
									'style-11'
								),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'newsletter_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Slider*/
			vc_map(
				array(
					'name'                    => esc_html__( 'Lebe: Slider', 'lebe' ),
					'base'                    => 'lebe_slider',
					'category'                => esc_html__( 'Lebe Elements', 'lebe' ),
					'description'             => esc_html__( 'Display a custom slide.', 'lebe' ),
					'as_parent'               => array( 'only' => 'vc_single_image,lebe_categories,lebe_banner,lebe_iconbox,lebe_testimonials' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => LEBE_SHORTCODES_ICONS_URI . 'slide.png',
					'params'                  => array(
						/* Owl */
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'AutoPlay', 'lebe' ),
							'param_name'  => 'autoplay',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Navigation', 'lebe' ),
							'param_name'  => 'navigation',
							'description' => esc_html__( "Show buton 'next' and 'prev' buttons.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'nav-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Navigation color', 'lebe' ),
							'param_name'  => 'nav_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'navigation',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Arrow', 'lebe' )        => '',
								esc_html__( 'Circle Arrow', 'lebe' ) => 'nav-circle',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Nav Type', 'lebe' ),
							'param_name'  => 'nav_type',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							"dependency"  => array(
								"element" => "navigation",
								"value"   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'No', 'lebe' )  => 'false',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Enable Dots', 'lebe' ),
							'param_name'  => 'dots',
							'description' => esc_html__( "Show buton dots.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Dark', 'lebe' )  => '',
								esc_html__( 'Light', 'lebe' ) => 'dots-light',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Dots color', 'lebe' ),
							'param_name'  => 'dots_color',
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
							'dependency'  => array(
								'element' => 'dots',
								'value'   => array( 'true' ),
							),
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Yes', 'lebe' ) => 'true',
								esc_html__( 'No', 'lebe' )  => 'false',
							),
							'std'         => 'false',
							'heading'     => esc_html__( 'Loop', 'lebe' ),
							'param_name'  => 'loop',
							'description' => esc_html__( "Inifnity loop. Duplicate last and first items to get loop illusion.", 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Slide Speed", 'lebe' ),
							"param_name"  => "slidespeed",
							"value"       => "200",
							"description" => esc_html__( 'Slide speed in milliseconds', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Margin", 'lebe' ),
							"param_name"  => "margin",
							"value"       => "30",
							"description" => esc_html__( 'Distance( or space) between 2 item', 'lebe' ),
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Auto Responsive Margin', 'lebe' ),
							'param_name' => 'autoresponsive',
							'group'      => esc_html__( 'Carousel settings', 'lebe' ),
							'value'      => array(
								esc_html__( 'No', 'lebe' )  => '',
								esc_html__( 'Yes', 'lebe' ) => 'true',
							),
							'std'        => '',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1500px )", 'lebe' ),
							"param_name"  => "ls_items",
							"value"       => "5",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 1200px < 1500px )", 'lebe' ),
							"param_name"  => "lg_items",
							"value"       => "4",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on desktop (Screen resolution of device >= 992px < 1200px )", 'lebe' ),
							"param_name"  => "md_items",
							"value"       => "3",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on tablet (Screen resolution of device >=768px and < 992px )", 'lebe' ),
							"param_name"  => "sm_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile landscape(Screen resolution of device >=480px and < 768px)", 'lebe' ),
							"param_name"  => "xs_items",
							"value"       => "2",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "The items on mobile (Screen resolution of device < 480px)", 'lebe' ),
							"param_name"  => "ts_items",
							"value"       => "1",
							'group'       => esc_html__( 'Carousel settings', 'lebe' ),
							'admin_label' => false,
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'heading'     => esc_html__( 'Extra Class Name', 'lebe' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'lebe' ),
							'type'        => 'textfield',
							'param_name'  => 'el_class',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'slider_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Map New Instagram Shop Wrap*/
			vc_map(
				array(
					'name'                    => esc_html__( 'Lebe: Instagram Shop Wrap', 'lebe' ),
					'base'                    => 'lebe_instagramshopwrap',
					'category'                => esc_html__( 'Lebe Elements', 'lebe' ),
					'description'             => esc_html__( 'Display a custom instagram shop wrap.', 'lebe' ),
					'as_parent'               => array( 'only' => 'ziss' ),
					'content_element'         => true,
					'show_settings_on_create' => true,
					'js_view'                 => 'VcColumnView',
					'icon'                    => LEBE_SHORTCODES_ICONS_URI . 'container.png',
					'params'                  => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagramshopwrap/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagramshopwrap/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagramshopwrap/style-03.jpg',
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'instagramshopwrap/style-04.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose icon or image', 'lebe' ),
							'value'      => array(
								esc_html__( 'Icon', 'lebe' )  => 'icontype',
								esc_html__( 'Image', 'lebe' ) => 'imagetype',
							),
							'param_name' => 'iconimage',
							'std'        => 'icontype',
							'dependency' => array(
								'element' => 'style',
								'value'   => 'style-02',
							),
						),
						array(
							"type"       => "attach_image",
							"heading"    => esc_html__( "Image custom", "lebe" ),
							"param_name" => "image",
							'dependency' => array(
								'element' => 'iconimage',
								'value'   => 'imagetype',
							),
						),
						array(
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'Icon library', 'lebe' ),
							'value'       => array(
								esc_html__( 'Font Awesome', 'lebe' )  => 'fontawesome',
								esc_html__( 'Font Flaticon', 'lebe' ) => 'fontflaticon',
							),
							'admin_label' => true,
							'param_name'  => 'i_type',
							'description' => esc_html__( 'Select icon library.', 'lebe' ),
							'std'         => 'fontawesome',
							'dependency'  => array(
								'element' => 'iconimage',
								'value'   => 'icontype',
							),
						),
						array(
							'param_name'  => 'icon_lebecustomfonts',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
							'type'        => 'iconpicker',
							'settings'    => array(
								'emptyIcon' => true,
								'type'      => 'lebecustomfonts',
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontflaticon',
							),
						),
						array(
							'type'        => 'iconpicker',
							'heading'     => esc_html__( 'Icon', 'lebe' ),
							'param_name'  => 'icon_fontawesome',
							'value'       => 'fa fa-adjust',
							'settings'    => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
							'dependency'  => array(
								'element' => 'i_type',
								'value'   => 'fontawesome',
							),
							'description' => esc_html__( 'Select icon from library.', 'lebe' ),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Title', 'lebe' ),
							'param_name'  => 'title',
							'admin_label' => true,
							'dependency'  => array(
								'element' => 'style',
								'value'   => array( 'style-02' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'heading'     => esc_html__( 'Extra Class Name', 'lebe' ),
							'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'lebe' ),
							'type'        => 'textfield',
							'param_name'  => 'el_class',
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'instagramshopwrap_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			/*Section Testimonial*/
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Testimonial', 'lebe' ),
					'base'        => 'lebe_testimonials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display testimonial info.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'testimonial/style-01.jpg'
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'testimonial/style-02.jpg'
								),
								'style-03' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'testimonial/style-03.jpg'
								),
								'style-04' => array(
									'alt' => 'Style 04',
									'img' => LEBE_SHORTCODE_PREVIEW . 'testimonial/style-04.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__( 'Image', 'lebe' ),
							'param_name' => 'image',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-03', 'style-04' ),
							),
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Star Rating', 'lebe' ),
							'param_name' => 'rating',
							'value'      => array(
								esc_html__( '1 Star', 'lebe' )  => 'rating-1',
								esc_html__( '2 Stars', 'lebe' ) => 'rating-2',
								esc_html__( '3 Stars', 'lebe' ) => 'rating-3',
								esc_html__( '4 Stars', 'lebe' ) => 'rating-4',
								esc_html__( '5 Stars', 'lebe' ) => 'rating-5',
							),
							'std'        => 'rating-5',
							'dependency' => array(
								'element' => 'style',
								'value'   => array( 'style-01', 'style-03' ),
							),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Content', 'lebe' ),
							'param_name' => 'desc',
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Name', 'lebe' ),
							'param_name'  => 'name',
							'description' => esc_html__( 'Name', 'lebe' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Position', 'lebe' ),
							'param_name'  => 'position',
							'description' => esc_html__( 'Position', 'lebe' ),
							'admin_label' => true,
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Link', 'lebe' ),
							'param_name' => 'link',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'testimonials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/*Section Team*/
			require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-icon-element.php' );
			$icon_params = array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Link Social', 'lebe' ),
					'param_name'  => 'link_social',
					'admin_label' => true,
					'description' => esc_html__( 'shortcode title.', 'lebe' ),
				),
			);
			$icon_params = array_merge( $icon_params, (array) vc_map_integrate_shortcode(
				vc_icon_element_params(), 'i_', '',
				array(
					// we need only type, icon_fontawesome, icon_.., NOT color and etc
					'include_only_regex' => '/^(type|icon_\w*)/',
				)
			)
			);
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Team', 'lebe' ),
					'base'        => 'lebe_team', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display team info.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'testimonial.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select Style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'team/style-01.jpg'
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'attach_image',
							'heading'    => esc_html__( 'Image', 'lebe' ),
							'param_name' => 'image',
						),
						array(
							'type'       => 'param_group',
							'heading'    => esc_html__( 'Social', 'lebe' ),
							'param_name' => 'social_team',
							'params'     => $icon_params,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Name', 'lebe' ),
							'param_name'  => 'name',
							'description' => esc_html__( 'Name', 'lebe' ),
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Position', 'lebe' ),
							'param_name'  => 'position',
							'description' => esc_html__( 'Position', 'lebe' ),
							'admin_label' => true,
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Link', 'lebe' ),
							'param_name' => 'link',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" )
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'team_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						)
					)
				)
			);
			/* Map Google Map */
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Google Map', 'lebe' ),
					'base'        => 'lebe_googlemap', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a google map.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'gmap.png',
					'params'      => array(
						array(
							"type"        => "attach_image",
							"heading"     => esc_html__( "Pin", "lebe" ),
							"param_name"  => "pin_icon",
							"admin_label" => false,
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Title", 'lebe' ),
							"param_name"  => "title",
							'admin_label' => true,
							"description" => esc_html__( "title.", 'lebe' ),
							'std'         => 'Tic themes',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Phone", 'lebe' ),
							"param_name"  => "phone",
							'admin_label' => true,
							"description" => esc_html__( "phone.", 'lebe' ),
							'std'         => '088-465 9965 02',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Email", 'lebe' ),
							"param_name"  => "email",
							'admin_label' => true,
							"description" => esc_html__( "email.", 'lebe' ),
							'std'         => 'famithemes@gmail.com',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Map Height", 'lebe' ),
							"param_name"  => "map_height",
							'admin_label' => true,
							'std'         => '400',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Maps type', 'lebe' ),
							'param_name' => 'map_type',
							'value'      => array(
								esc_html__( 'ROADMAP', 'lebe' )   => 'ROADMAP',
								esc_html__( 'SATELLITE', 'lebe' ) => 'SATELLITE',
								esc_html__( 'HYBRID', 'lebe' )    => 'HYBRID',
								esc_html__( 'TERRAIN', 'lebe' )   => 'TERRAIN',
							),
							'std'        => 'ROADMAP',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Show info content?', 'lebe' ),
							'param_name' => 'info_content',
							'value'      => array(
								esc_html__( 'Yes', 'lebe' ) => '1',
								esc_html__( 'No', 'lebe' )  => '2',
							),
							'std'        => '1',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Address", 'lebe' ),
							"param_name"  => "address",
							'admin_label' => true,
							"description" => esc_html__( "address.", 'lebe' ),
							'std'         => 'Hoang Van Thu, TP. Thai Nguyen',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Longitude", 'lebe' ),
							"param_name"  => "longitude",
							'admin_label' => true,
							"description" => esc_html__( "longitude.", 'lebe' ),
							'std'         => '105.800286',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Latitude", 'lebe' ),
							"param_name"  => "latitude",
							'admin_label' => true,
							"description" => esc_html__( "latitude.", 'lebe' ),
							'std'         => '21.587001',
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Zoom", 'lebe' ),
							"param_name"  => "zoom",
							'admin_label' => true,
							"description" => esc_html__( "zoom.", 'lebe' ),
							'std'         => '14',
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", 'lebe' ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'googlemap_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Map New Social */
			$socials     = array();
			$all_socials = lebe_get_option( 'user_all_social' );
			$i           = 1;
			if ( $all_socials ) {
				foreach ( $all_socials as $social ) {
					$socials[ $social['title_social'] ] = $i ++;
				}
			}
			vc_map(
				array(
					'name'        => esc_html__( 'Lebe: Socials', 'lebe' ),
					'base'        => 'lebe_socials', // shortcode
					'class'       => '',
					'category'    => esc_html__( 'Lebe Elements', 'lebe' ),
					'description' => esc_html__( 'Display a social list.', 'lebe' ),
					'icon'        => LEBE_SHORTCODES_ICONS_URI . 'socials.png',
					'params'      => array(
						array(
							'type'        => 'select_preview',
							'heading'     => esc_html__( 'Select style', 'lebe' ),
							'value'       => array(
								'style-01' => array(
									'alt' => 'Style 01',
									'img' => LEBE_SHORTCODE_PREVIEW . 'socials/style-01.jpg',
								),
								'style-02' => array(
									'alt' => 'Style 02',
									'img' => LEBE_SHORTCODE_PREVIEW . 'socials/style-02.jpg',
								),
								'style-03' => array(
									'alt' => 'Style 03',
									'img' => LEBE_SHORTCODE_PREVIEW . 'socials/style-03.jpg',
								),
							),
							'default'     => 'style-01',
							'admin_label' => true,
							'param_name'  => 'style',
						),
						array(
							'type'       => 'checkbox',
							'heading'    => esc_html__( 'Display on', 'lebe' ),
							'param_name' => 'use_socials',
							'class'      => 'checkbox-display-block',
							'value'      => $socials,
						),
						array(
							'type'        => 'dropdown',
							'value'       => array(
								esc_html__( 'Text Left', 'lebe' )   => '',
								esc_html__( 'Text Right', 'lebe' )  => 'right',
								esc_html__( 'Text Center', 'lebe' ) => 'center',
							),
							'std'         => '',
							'heading'     => esc_html__( 'Text align', 'lebe' ),
							'param_name'  => 'align',
							'description' => esc_html__( 'Text align', 'lebe' ),
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							"type"        => "textfield",
							"heading"     => esc_html__( "Extra class name", "lebe" ),
							"param_name"  => "el_class",
							"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "lebe" ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'Css', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
						array(
							'param_name'       => 'socials_custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
					),
				)
			);
			
			/* Pin Mapper */
			$all_pin_mappers      = get_posts(
				array(
					'post_type'      => 'lebe_mapper',
					'posts_per_page' => '-1'
				)
			);
			$all_pin_mappers_args = array(
				esc_html__( ' ---- Choose a pin mapper ---- ', 'lebe' ) => '0',
			);
			if ( ! empty( $all_pin_mappers ) ) {
				foreach ( $all_pin_mappers as $pin_mapper ) {
					$all_pin_mappers_args[ $pin_mapper->post_title ] = $pin_mapper->ID;
				}
			} else {
				$all_pin_mappers_args = array(
					esc_html__( ' ---- No pin mapper to choose ---- ', 'lebe' ) => '0',
				);
			}
			vc_map(
				array(
					'name'     => esc_html__( 'Lebe: Pin Mapper', 'lebe' ),
					'base'     => 'lebe_pinmap',
					'category' => esc_html__( 'Lebe Elements', 'lebe' ),
					'icon'     => LEBE_SHORTCODES_ICONS_URI . 'pinmapper.png',
					'params'   => array(
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Choose Pin Mapper', 'lebe' ),
							'param_name' => 'ids',
							'value'      => $all_pin_mappers_args
						),
						array(
							'type'       => 'dropdown',
							'param_name' => 'animate_on_scroll',
							'heading'    => esc_html__( 'Animation On Scroll', 'lebe' ),
							'value'      => $this->animation_on_scroll(),
							'std'        => ''
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Extra class name', 'lebe' ),
							'param_name'  => 'el_class',
							'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'lebe' ),
						),
						array(
							'param_name'       => 'custom_id',
							'heading'          => esc_html__( 'Hidden ID', 'lebe' ),
							'type'             => 'uniqid',
							'edit_field_class' => 'hidden',
						),
						array(
							'type'       => 'dropdown',
							'heading'    => esc_html__( 'Show info content?', 'lebe' ),
							'param_name' => 'show_info_content',
							'value'      => array(
								esc_html__( 'Yes', 'lebe' ) => 'yes',
								esc_html__( 'No', 'lebe' )  => 'no',
							),
							'std'        => 'no',
							'group'      => esc_html__( 'Info Text', 'lebe' ),
						),
						array(
							'type'       => 'textarea',
							'heading'    => esc_html__( 'Title', 'lebe' ),
							'param_name' => 'title',
							'dependency' => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'      => esc_html__( 'Info Text', 'lebe' ),
						),
						array(
							'type'        => 'textarea',
							'heading'     => esc_html__( 'Short Description', 'lebe' ),
							'param_name'  => 'short_desc',
							'description' => esc_html__( 'Short description display under the title', 'lebe' ),
							'admin_label' => true,
							'std'         => '',
							'dependency'  => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'       => esc_html__( 'Info Text', 'lebe' ),
						),
						array(
							'type'       => 'vc_link',
							'heading'    => esc_html__( 'Button Link', 'lebe' ),
							'param_name' => 'btn_link',
							'dependency' => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'      => esc_html__( 'Info Text', 'lebe' ),
						),
						array(
							'type'        => 'textfield',
							'holder'      => 'div',
							'class'       => '',
							'heading'     => esc_html__( 'Position', 'lebe' ),
							'param_name'  => 'pos',
							'std'         => '200:800',
							'description' => esc_html__( '{top}:{left}. Example: 200:800, etc...', 'lebe' ),
							'dependency'  => array(
								'element' => 'show_info_content',
								'value'   => array( 'yes' ),
							),
							'group'       => esc_html__( 'Info Text', 'lebe' ),
						),
						array(
							'type'       => 'css_editor',
							'heading'    => esc_html__( 'CSS box', 'lebe' ),
							'param_name' => 'css',
							'group'      => esc_html__( 'Design Options', 'lebe' ),
						),
					
					),
				)
			);
			
		}
	}
	
	new Lebe_Visual_Composer();
}

if ( class_exists( 'Vc_Manager' ) ) {
	function change_vc_row() {
		$args = array(
			array(
				"type"        => "checkbox",
				"group"       => "Additions",
				"holder"      => "div",
				"class"       => "custom-checkbox",
				"heading"     => esc_html__( 'Parallax effect: ', 'lebe' ),
				"description" => esc_html__( 'Chosen for using Paralax scroll', 'lebe' ),
				"param_name"  => "paralax_class",
				'admin_label' => true,
				"value"       => array(
					esc_html__( 'paralax-slide', 'lebe' ) => "type_paralax",
				),
			),
			array(
				"type"        => "checkbox",
				"group"       => "Additions",
				"heading"     => esc_html__( 'Slide Class: ', 'lebe' ),
				"description" => esc_html__( 'Chosen for using slide scroll', 'lebe' ),
				"param_name"  => "section_class",
				'admin_label' => true,
				"value"       => array(
					esc_html__( 'section-slide', 'lebe' ) => "section-slide",
				),
			),
		);
		foreach ( $args as $value ) {
			// vc_add_param( "vc_row", $value );
			vc_add_param( "vc_section", $value );
		}
	}
	
	change_vc_row();
	get_template_part( 'vc_templates/vc_row.php' );
	get_template_part( 'vc_templates/vc_section.php' );
}

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_Lebe_Tabs extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Lebe_Accordions extends WPBakeryShortCode_VC_Tta_Accordion {
}

class WPBakeryShortCode_Lebe_Container extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_Lebe_Slider extends WPBakeryShortCodesContainer {
}

class WPBakeryShortCode_lebe_Instagramshopwrap extends WPBakeryShortCodesContainer {
}
