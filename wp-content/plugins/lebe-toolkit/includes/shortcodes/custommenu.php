<?php
if ( ! class_exists( 'Lebe_Shortcode_Custommenu' ) ) {
	class Lebe_Shortcode_Custommenu extends Lebe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'custommenu';
		
		
		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();
		
		
		public static function generate_css( $atts ) {
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			
			return $css;
		}
		
		
		public function output_html( $atts, $content = null ) {
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'lebe_custommenu', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'lebe-custommenu' );
			$css_class[] = $atts['style'];
			$css_class[] = $atts['text_color'];
			$css_class[] = $atts['align'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['custommenu_custom_id'];
			$css_class[] = $atts['animate_on_scroll'];
            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), '', $atts);
            }
			$nav_menu = get_term_by( 'slug', $atts['menu'], 'nav_menu' );
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<?php if ( is_object( $nav_menu ) ): ?>
					<?php if ( $atts['title'] && $atts['style'] == 'style-01'): ?>
                        <h2 class="widgettitle"><?php echo esc_html($atts['title'] ); ?></h2>
					<?php endif ?>
					<?php
					wp_nav_menu( array(
                             'menu'            => $nav_menu->slug,
                             'theme_location'  => $nav_menu->slug,
                             'container'       => '',
                             'container_class' => '',
                             'container_id'    => '',
                             'menu_class'      => 'menu',
                             'fallback_cb'     => 'lebe_navwalker::fallback',
                             'walker'          => new lebe_navwalker(),
                         )
					);
					?>
				<?php endif; ?>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Lebe_Shortcode_custommenu', force_balance_tags( $html ), $atts, $content );
		}
	}
}