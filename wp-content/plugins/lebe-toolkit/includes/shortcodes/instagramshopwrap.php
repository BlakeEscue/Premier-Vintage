<?php

if (!class_exists('Lebe_Shortcode_Instagramshopwrap')) {
    class Lebe_Shortcode_Instagramshopwrap extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'instagramshopwrap';

        /**
         * Default $atts .
         *
         * @var  array
         */
        public $default_atts = array();


        public static function generate_css($atts)
        {
            extract($atts);
            $css = '';

            return $css;
        }

        public function output_html($atts, $content = null)
        {
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_instagramshopwrap', $atts) : $atts;

            extract($atts);
            $css_class = array('lebe-instagramshopwrap');
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['iconimage'];
            $css_class[] = $atts['instagramshopwrap_custom_id'];
            $css_class[] = $atts['style'];
            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), '', $atts);
            }
            $type_icon = isset($atts['i_type']) ? $atts['i_type'] : '';
            if ($type_icon == 'fontflaticon') {
                $class_icon = isset($atts['icon_lebecustomfonts']) ? $atts['icon_lebecustomfonts'] : '';
            } else {
                $class_icon = isset($atts['icon_fontawesome']) ? $atts['icon_fontawesome'] : '';
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($atts['style'] == 'style-02'): ?>
                    <div class="title-insshop">
                        <?php if ($atts['iconimage'] == 'imagetype' && $atts['image']): ?>
                            <?php echo wp_get_attachment_image($atts['image'], 'full'); ?>
                        <?php else: ?>
                            <span class="<?php echo esc_attr($class_icon); ?>"></span>
                        <?php endif; ?>
                        <?php if ($atts['title']): ?>
                            <h3><?php echo esc_html($atts['title']); ?></h3>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php echo wpb_js_remove_wpautop($content); ?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_instagramshopwrap', $html, $atts, $content);
        }
    }
}