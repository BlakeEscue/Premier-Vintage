<?php

if (!class_exists('Lebe_Shortcode_banner')) {
    class Lebe_Shortcode_banner extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'banner';


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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_banner', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);
            $css_class = array('lebe-banner');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['border'];
            $css_class[] = $atts['overlay'];
            $css_class[] = $atts['position'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['banner_custom_id'];
            $css_class[] = $atts['animate_on_scroll'];

            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = ' ' . apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '), '', $atts);
            }
            $banner_link = vc_build_link($atts['link']);
            if ($banner_link['url']) {
                $link_url = $banner_link['url'];
            } else {
                $link_url = '#';
            }
            if ($banner_link['target']) {
                $link_target = $banner_link['target'];
            } else {
                $link_target = '_self';
            }
            $equal_container = '';
            $equal_elem = '';
            $container  = '';
            if ($atts['style'] == 'style-07') {
                $equal_container = 'equal-container better-height';
                $equal_elem = 'equal-elem';
            }
            if ($atts['style'] == 'style-15') {
                $container = 'container';
            }
            ob_start();
            ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="banner-inner <?php echo esc_attr($equal_container) ?>">
                    <?php if ($atts['image']) : ?>
                        <figure class="banner-thumb <?php echo esc_attr($equal_elem) ?>">
                            <a target="<?php echo esc_attr($link_target); ?>" href="<?php echo esc_url($link_url); ?>">
                                <?php echo wp_get_attachment_image($atts['image'], 'full'); ?>
                            </a>
                        </figure>
                    <?php endif; ?>
                    <div class="banner-info <?php echo esc_attr($equal_elem) ?>">
                        <div class="banner-info-inner <?php echo esc_attr($container) ?>">
                            <?php if ($atts['title'] && ($atts['style'] == 'style-07' || $atts['style'] == 'style-09' || $atts['style'] == 'style-10' || $atts['style'] == 'style-11' || $atts['style'] == 'style-12' || $atts['style'] == 'style-13')) : ?>
                                <h6 class="title">
                                    <?php echo esc_html($atts['title']); ?>
                                </h6>
                            <?php endif; ?>
                            <?php if ($atts['bigtitle']) : ?>
                                <h3 class="bigtitle">
                                    <?php if ($atts['style'] == 'style-04' || $atts['style'] == 'style-06'): ?>
                                        <a target="<?php echo esc_attr($link_target); ?>"
                                           href="<?php echo esc_url($link_url); ?>">
                                            <?php echo wp_specialchars_decode($atts['bigtitle']); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo wp_specialchars_decode($atts['bigtitle']); ?>
                                    <?php endif; ?>
                                </h3>
                            <?php endif; ?>
                            <?php if ($atts['desc'] && ($atts['style'] == 'style-03' || $atts['style'] == 'style-07' || $atts['style'] == 'style-09' || $atts['style'] == 'style-14' || $atts['style'] == 'style-15')): ?>
                                <div class="desc">
                                    <?php echo wp_specialchars_decode($atts['desc']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($banner_link['title'] && ($atts['style'] == 'style-01' || $atts['style'] == 'style-02' || $atts['style'] == 'style-03' || $atts['style'] == 'style-05' || $atts['style'] == 'style-07' || $atts['style'] == 'style-08' || $atts['style'] == 'style-09' || $atts['style'] == 'style-10' || $atts['style'] == 'style-11' || $atts['style'] == 'style-12' || $atts['style'] == 'style-13' || $atts['style'] == 'style-14' || $atts['style'] == 'style-15')) : ?>
                                <a class="button" target="<?php echo esc_attr($link_target); ?>"
                                   href="<?php echo esc_url($link_url); ?>"><span><?php echo esc_html($banner_link['title']); ?></span></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_banner', $html, $atts, $content);
        }
    }
}