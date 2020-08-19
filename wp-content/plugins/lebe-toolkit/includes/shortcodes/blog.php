<?php

if (!class_exists('Lebe_Shortcode_blog')) {
    class Lebe_Shortcode_blog extends Lebe_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'blog';


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
            $atts = function_exists('vc_map_get_attributes') ? vc_map_get_attributes('lebe_blog', $atts) : $atts;

            // Extract shortcode parameters.
            extract($atts);

            $css_class = array('lebe-blog');
            $css_class[] = $atts['style'];
            $css_class[] = $atts['el_class'];
            $css_class[] = $atts['blog_custom_id'];
	        $css_class[] = $atts['animate_on_scroll'];
            if (function_exists('vc_shortcode_custom_css_class')) {
                $css_class[] = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($atts['css'], ' '), '', $atts);
            }

            $owl_class[] = 'owl-carousel nav-center ' . $atts['nav_color'] . ' ' . $atts['nav_type'] . ' ' . $atts['dots_color'];
            $owl_settings = $this->generate_carousel_data_attributes('', $atts);

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $atts['per_page'],
                'suppress_filter' => true,
                'orderby' => $atts['orderby'],
                'order' => $atts['order'],
            );
            if (!empty($ids_post)) {
                $args['p'] = $ids_post;
            }

            if ($atts['category_slug']) {
                $idObj = get_category_by_slug($atts['category_slug']);
                if (is_object($idObj)) {
                    $args['cat'] = $idObj->term_id;
                }
            }
            $loop_posts = new WP_Query(apply_filters('lebe_shortcode_posts_query', $args, $atts));

            ob_start();
            ?>
            <?php if ($loop_posts->have_posts()) : ?>
                <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                    <?php if ($atts['title']): ?>
                        <h3 class="blog-heading"><?php echo esc_html($atts['title']) ?></h3>
                    <?php endif; ?>
                    <div class="owl-carousel equal-container better-height <?php echo esc_attr( implode( ' ', $owl_class ) ); ?>" <?php echo force_balance_tags($owl_settings); ?>>
                        <?php while ($loop_posts->have_posts()) : $loop_posts->the_post() ?>
                            <?php get_template_part('templates/blog/blog-styles/content-blog', $atts['style']); ?>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <?php get_template_part('content', 'none'); ?>
            <?php endif; ?>
                <?php
            $html = ob_get_clean();

            return apply_filters('Lebe_Shortcode_blog', $html, $atts, $content);
        }
    }
}