<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div <?php post_class('blog-item equal-elem'); ?>>
    <div class="blog-thumb">
        <?php if (has_post_thumbnail()) {
            $image_thumb = lebe_resize_image(get_post_thumbnail_id(), null, 440, 350, true, false, false); ?>
            <a href="<?php the_permalink(); ?>">
                <?php echo lebe_img_output($image_thumb, 'attachment-post-thumbnail wp-post-image', get_the_title()); ?>
                <span class="blog-date">
                    <span class="blog-day"><?php echo get_the_date('d')?></span>
                    <span class="blog-month"><?php echo get_the_date('M')?></span>
                </span>
            </a>
        <?php } ?>
    </div>
    <div class="blog-info">
        <h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <ul class="blog-meta">
            <li class="blog-author">
                <span><?php echo esc_html__( 'By ', 'lebe' ) ?></span>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
                    <?php the_author() ?>
                </a>
            </li>
            <li class="blog-comment">
                <?php
                comments_number(
                    esc_html__( '0 comments', 'lebe' ),
                    esc_html__( '1 comment', 'lebe' ),
                    esc_html__( '% comments', 'lebe' )
                );
                ?>
            </li>
        </ul>
    </div>
</div>