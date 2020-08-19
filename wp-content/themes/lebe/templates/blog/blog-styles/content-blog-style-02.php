<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div <?php post_class('blog-item equal-elem'); ?>>
    <div class="blog-thumb">
        <?php if (has_post_thumbnail()) {
            $image_thumb = lebe_resize_image(get_post_thumbnail_id(), null, 320, 390, true, false, false); ?>
            <a href="<?php the_permalink(); ?>">
                <?php echo lebe_img_output($image_thumb, 'attachment-post-thumbnail wp-post-image', get_the_title()); ?>
            </a>
        <?php } ?>
    </div>
    <div class="blog-info">
        <a class="blog-date" href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
        <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <div class="blog-content">
            <?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 15, esc_html__('...', 'lebe')); ?>
        </div>
        <a class="blog-readmore" href="<?php the_permalink(); ?>"><span><?php echo esc_html__('Read more', 'lebe'); ?></span></a>
    </div>
</div>