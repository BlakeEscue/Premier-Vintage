<?php

if (!class_exists('Lebe_Shortcode_buttonvideo')) {
    class Lebe_Shortcode_buttonvideo extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'buttonvideo';


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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_buttonvideo', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('lebe-buttonvideo');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['buttonvideo_custom_id'];
	        $css_class[] = $atts['animate_on_scroll'];

            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = ' ' . apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="buttonvideo-inner">
                    <?php if ($atts['image']) : ?>
                        <?php echo wp_get_attachment_image($atts['image'], 'full'); ?>
                    <?php endif; ?>
                    <?php if ($atts['link_video']) : ?>
                        <div class="lebe-bt-video">
                            <a href="<?php echo esc_url($atts['link_video']); ?>"><span></span></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_buttonvideo', $html, $atts, $content);
        }
    }
}