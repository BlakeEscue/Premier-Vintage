<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 *
 * @var $atts
 * Shortcode class
 * @var $this "Lebe_Shortcode_Adv_Text"
 */

if ( ! class_exists( 'Lebe_Shortcode_Adv_Text' ) ) {
	class Lebe_Shortcode_Adv_Text extends Lebe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'adv_text';
		
		public function output_html( $atts, $content = null ) {
			
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'lebe_adv_text', $atts ) : $atts;
			extract( $atts );
			$css_class    = array( 'lebe-adv-text' );
			$css_class[]  = isset( $atts['style'] ) ? $atts['style'] : '';
			$css_class[]  = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
			$css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'lebe_adv_text', $atts );
			
			$html = '';
			
			$mobile_text      = str_replace( '`}`', ']', str_replace( '`{`', '[', str_replace( '``', '"', $mobile_text ) ) );
			$mobile_text      = str_replace( '"{`', '[', $mobile_text );
			$mobile_text      = str_replace( '`}', ']', $mobile_text );
			$none_mobile_text = str_replace( '`}`', ']', str_replace( '`{`', '[', str_replace( '``', '"', $none_mobile_text ) ) );
			$none_mobile_text = str_replace( '"{`', '[', $none_mobile_text );
			$none_mobile_text = str_replace( '`}', ']', $none_mobile_text );
			
			if ( lebe_toolkit_is_mobile() ) {
				if ( $mobile_text != '' ) {
					$html .= do_shortcode( $mobile_text );
				}
			} else {
				if ( $none_mobile_text ) {
					$html .= do_shortcode( $none_mobile_text );
				}
			}
			
			return apply_filters( 'Lebe_Shortcode_Adv_Text', $html, $atts, $content );
		}
	}
	
	// new Lebe_Shortcode_Adv_Text();
}