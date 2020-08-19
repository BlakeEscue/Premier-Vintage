<?php

if (!class_exists('Lebe_Shortcode_button')) {
    class Lebe_Shortcode_button extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'button';


        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();


        public static function generate_css($atts)
        {
            // Extract shortcode parameters.
            extract($atts);
            $css = '';

            return $css;
        }


        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_button', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('lebe-button');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['align'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['button_custom_id'];
	        $css_class[] = $atts['animate_on_scroll'];

            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = ' ' . apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
            }
            $button_link = vc_build_link($atts['link']);
            if ($button_link['url']) {
                $link_url = $button_link['url'];
            } else {
                $link_url = '#';
            }
            if ($button_link['target']) {
                $link_target = $button_link['target'];
            } else {
                $link_target = '_self';
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="button-inner">
                    <?php if ($button_link['title']) : ?>
                        <a class="button" target="<?php echo esc_attr($link_target); ?>"
                           href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($button_link['title']); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_button', $html, $atts, $content);
        }
    }
}