<?php

if ( !class_exists( 'Lebe_Shortcode_Container' ) ) {

	class Lebe_Shortcode_Container extends Lebe_Shortcode
	{
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'container';


		/**
		 * Default $atts .
		 *
		 * @var  array
		 */
		public $default_atts = array();


		public static function generate_css( $atts )
		{
			// Extract shortcode parameters.
			extract( $atts );
			$css = '';
			if ( $content_width == 'custom_width' ) {
				$css = '.'.$container_custom_id.' { width: ' . $number_width . ';}';
			}
			return $css;
		}


		public function output_html( $atts, $content = null )
		{
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'lebe_container', $atts ) : $atts;
			// Extract shortcode parameters.
			extract( $atts );

			$css_class   = array( 'lebe-container' );
			$css_class[] = $atts[ 'el_class' ];
			$css_class[] = $atts[ 'content_width' ];
			$css_class[] = $atts['animate_on_scroll'];
			if ( $content_width == 'custom_col' ) {
				$css_class[] = 'col-bg-' . $boostrap_bg_items;
				$css_class[] = 'col-lg-' . $boostrap_lg_items;
				$css_class[] = 'col-md-' . $boostrap_md_items;
				$css_class[] = 'col-sm-' . $boostrap_sm_items;
				$css_class[] = 'col-xs-' . $boostrap_xs_items;
				$css_class[] = 'col-ts-' . $boostrap_ts_items;
			} elseif ( $content_width == 'custom_width' ) {
				$css_class[] = $content_width;
			}
			$css_class[] = $atts[ 'container_custom_id' ];
            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), '', $atts);
            }
			ob_start();
			?>
			<div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
				<div class="lebe-container-inner">
					<?php echo wpb_js_remove_wpautop( $content ); ?>
				</div>
			</div>
			<?php
			$html = ob_get_clean();

			return apply_filters( 'Lebe_Shortcode_container', force_balance_tags( $html ), $atts, $content );
		}
	}
}