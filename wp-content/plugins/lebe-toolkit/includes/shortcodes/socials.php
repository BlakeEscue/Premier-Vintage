<?php

if ( ! class_exists( 'Lebe_Shortcode_Socials' ) ) {
	class Lebe_Shortcode_Socials extends Lebe_Shortcode {
		/**
		 * Shortcode name.
		 *
		 * @var  string
		 */
		public $shortcode = 'socials';
		
		
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
			$atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'lebe_socials', $atts ) : $atts;
			
			// Extract shortcode parameters.
			extract( $atts );
			
			$css_class   = array( 'lebe-socials' );
			$css_class[] = $atts['align'];
			$css_class[] = $atts['el_class'];
			$css_class[] = $atts['socials_custom_id'];
			$css_class[] = $atts['animate_on_scroll'];
			$css_class[] = $atts['style'];
			
			if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
				$css_class[] = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $atts['css'], ' ' ), '', $atts );
			}
			
			ob_start();
			?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <div class="socials-inner">
					<?php if ( ! empty( $atts['use_socials'] ) ): ?>
						<?php
						$socials         = explode( ',', $atts['use_socials'] );
						$all_socials     = lebe_get_option( 'user_all_social' );
						$all_socials_tmp = array();
						if ( $all_socials ) {
							foreach ( $all_socials as $social_tmp ) {
								$all_socials_tmp[] = $social_tmp;
							}
						}
						
						?>
                        <div class="socials">
							<?php foreach ( $socials as $social ) :
								$i = $social - 1; ?>
								<?php $array_social = $all_socials_tmp[ $i ]; ?>
                                <a class="social-item" href="<?php echo esc_url( $array_social['link_social'] ) ?>"
                                   target="_blank">
                                    <i class="<?php echo esc_attr( $array_social['icon_social'] ); ?>"></i>
                                </a>
							<?php endforeach; ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
			<?php
			$html = ob_get_clean();
			
			return apply_filters( 'Lebe_Shortcode_socials', $html, $atts, $content );
		}
	}
}