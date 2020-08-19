<?php

if (!class_exists('Lebe_Shortcode_demo')) {
    class Lebe_Shortcode_demo extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'demo';


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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_demo', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('lebe-demo');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['comming'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['demo_custom_id'];
	        $css_class[] = $atts['animate_on_scroll'];

            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = ' ' . apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
            }
            $demo_link = vc_build_link($atts['link']);
            if ($demo_link['url']) {
                $link_url = $demo_link['url'];
            } else {
                $link_url = '#';
            }
            if ($demo_link['target']) {
                $link_target = $demo_link['target'];
            } else {
                $link_target = '_self';
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="demo-inner">
                    <?php if ($atts['style'] == 'style-02'): ?>
                        <div class="demo-left">
                            <?php if ($atts['title']): ?>
                                <h3 class="demo-title">
                                    <?php if ($atts['image']) : ?>
                                        <?php echo wp_get_attachment_image($atts['image'], 'full'); ?>
                                    <?php endif; ?>
                                    <span><?php echo esc_html($atts['title']) ?></span>
                                </h3>
                            <?php endif; ?>
                            <?php if ($atts['des']): ?>
                                <div class="demo-des">
                                    <?php echo esc_html($atts['des']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($demo_link['title']) : ?>
                            <div class="demo-right">
                                <a class="demo-button" target="<?php echo esc_attr($link_target); ?>"
                                   href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($demo_link['title']); ?></a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($atts['image']) : ?>
                            <a target="<?php echo esc_attr($link_target); ?>"
                               href="<?php echo esc_url($link_url); ?>"><?php echo wp_get_attachment_image($atts['image'], 'full'); ?></a>
                        <?php endif; ?>
                        <?php if ($demo_link['title']) : ?>
                            <a class="demo-button" target="<?php echo esc_attr($link_target); ?>"
                               href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($demo_link['title']); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_demo', $html, $atts, $content);
        }
    }
}