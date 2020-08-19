<?php

if ( ! class_exists( 'Lebe_Shortcode_Products' ) ) {
	class Lebe_Shortcode_Products extends Lebe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'products';
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		public $product_thumb_width  = 590;
		public $product_thumb_height = 590;
		
		
		public static function generate_css( $atts ) {
			extract( $atts );
			$css = '';
			
			return $css;
		}
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'lebe_products', $atts ) : $atts;
			
			extract( $atts );
			$css_class   = array( 'lebe-products' );
			$css_class[] = $atts['show_label'];
			$css_class[] = $atts['type_color'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['products_custom_id'];
			$css_class[] = $atts['animate_on_scroll'];
			$css_class[] = 'style-' . $atts['product_style'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
			}
			$product_size_args = array(
				'width'  => 320,
				'height' => 320
			);
			
			/* Product Size */
			if ( $atts['product_image_size'] ) {
				if ( $atts['product_image_size'] == 'custom' ) {
					$this->product_thumb_width  = $atts['product_custom_thumb_width'];
					$this->product_thumb_height = $atts['product_custom_thumb_height'];
				} else {
					$product_image_size         = explode( "x", $atts['product_image_size'] );
					$this->product_thumb_width  = $product_image_size[0];
					$this->product_thumb_height = $product_image_size[1];
				}
			}
			
			$product_size_args['width']  = $this->product_thumb_width;
			$product_size_args['height'] = $this->product_thumb_height;
			
			$products      = $this->getProducts( $atts );
			$total_product = $products->post_count;
			
			$product_item_class   = array( 'product-item', $atts['target'] );
			$product_item_class[] = 'style-' . $atts['product_style'];
			
			$product_list_class = array();
			$owl_settings       = '';
			if ( $productsliststyle == 'grid' ) {
				$animate_class        = 'famiau-wow-continuous lebe-wow fadeInUp';
				$product_list_class[] = 'product-grid row auto-clear equal-container better-height nav-circle';
				$product_item_class[] = $boostrap_rows_space;
				$product_item_class[] = 'col-bg-' . $boostrap_bg_items;
				$product_item_class[] = 'col-lg-' . $boostrap_lg_items;
				$product_item_class[] = 'col-md-' . $boostrap_md_items;
				$product_item_class[] = 'col-sm-' . $boostrap_sm_items;
				$product_item_class[] = 'col-xs-' . $boostrap_xs_items;
				$product_item_class[] = 'col-ts-' . $boostrap_ts_items;
				$product_item_class[] = $animate_class;
			}
			if ( $productsliststyle == 'owl' ) {
				if ( $total_product < $lg_items ) {
					$atts['owl_loop'] = 'false';
				}
				$product_list_class[] = 'product-grid product-list-owl owl-carousel equal-container better-height nav-circle ' . $atts['nav_position'] . ' ' . $atts['nav_color'] . ' ' . $atts['nav_type'] . ' ' . $atts['dots_color'];
				$product_item_class[] = $owl_rows_space;
				$owl_settings         = $this->generate_carousel_data_attributes( '', $atts );
			}
			$style_css     = '';
			$products_link = vc_build_link( $atts['link'] );
			if ( $products_link['url'] ) {
				$link_url = $products_link['url'];
			} else {
				$link_url = '#';
			}
			if ( $products_link['target'] ) {
				$link_target = $products_link['target'];
			} else {
				$link_target = '_self';
			}
			
			$product_style = intval( str_replace( 'style 0', '', strtolower( $product_style ) ) );
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>" <?php echo esc_attr( $style_css ); ?>>
				<?php if ( $products->have_posts() ): ?>
					<?php if ( $productsliststyle == 'grid' ): ?>
                        <ul id="<?php echo esc_attr( $atts['products_custom_id'] ); ?>"
                            class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>">
							<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                <li id="post-<?php echo get_the_ID(); ?>" <?php post_class( $product_item_class ); ?>>
									<?php wc_get_template( 'product-styles/content-product-style-' . $product_style . '.php', $product_size_args ); ?>
                                </li>
							<?php endwhile; ?>
                        </ul>
						<?php if ( $products_link['title'] ) : ?>
                            <a class="view-products" target="<?php echo esc_attr( $link_target ); ?>"
                               href="<?php echo esc_url( $link_url ); ?>"><span><?php echo esc_html( $products_link['title'] ); ?></span></a>
						<?php endif; ?>
					<?php elseif ( $productsliststyle == 'owl' ) : ?>
                        <!-- OWL Products -->
						<?php $i = 1; ?>
                        <div class="<?php echo esc_attr( implode( ' ', $product_list_class ) ); ?>" <?php echo force_balance_tags( $owl_settings ); ?>>
                            <div class="owl-one-row">
								<?php while ( $products->have_posts() ) : $products->the_post(); ?>
                                    <div <?php post_class( $product_item_class ); ?>>
										<?php wc_get_template( 'product-styles/content-product-style-' . $product_style . '.php', $product_size_args ); ?>
                                    </div>
									<?php
									if ( $i % $owl_number_row == 0 && $i < $total_product ) {
										echo '</div><div class="owl-one-row">';
									}
									$i ++;
									?>
								<?php endwhile; ?>
                            </div>
                        </div>
					<?php endif; ?>
				<?php else: ?>
                    <p>
                        <strong><?php esc_html_e( 'No Product', 'lebe-toolkit' ); ?></strong>
                    </p>
				<?php endif; ?>
            </div>
			<?php
			wp_reset_postdata();
			$html = ob_get_clean();
			
			return apply_filters( 'Lebe_Shortcode_products', force_balance_tags( $html ), $atts, $content );
		}
	}
}