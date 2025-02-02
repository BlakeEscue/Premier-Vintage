<?php

if ( !class_exists( 'Lebe_Shortcode_Slider' ) ) {
	class Lebe_Shortcode_Slider extends Lebe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'slider';

		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			$css = '';
			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'lebe_slider', $atts ) : $atts;

			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'lebe-slider' );
			$owl_class   = array();
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'slider_custom_id' ];
			$css_class[] = $atts['animate_on_scroll'];

			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = ' ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
			}
            $owl_class[] = 'owl-carousel nav-center '.$atts['nav_color'].' '.$atts['nav_type'].' '.$atts['dots_color'].'';
            $owl_settings = $this->generate_carousel_data_attributes( '', $atts );
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="<?php echo esc_attr( implode( ' ', $owl_class ) ); ?>"  <?php echo force_balance_tags($owl_settings); ?>>
					<?php echo wpb_js_remove_wpautop( $content ); ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Lebe_Shortcode_slider', $html, $atts, $content );
		}
	}
}