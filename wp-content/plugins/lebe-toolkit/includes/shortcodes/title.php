<?php

if (!class_exists('Lebe_Shortcode_Title')) {
    class Lebe_Shortcode_Title extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'title';

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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_title', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('lebe-title');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['text_color'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['title_custom_id'];
	        $css_class[] = $atts['animate_on_scroll'];
            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), '', $atts);
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="title-inner">
                    <?php if ($atts['title']): ?>
                        <h3 class="block-title">
                            <?php echo esc_html($atts['title']); ?>
                        </h3>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_title', $html, $atts, $content);
        }
    }
}