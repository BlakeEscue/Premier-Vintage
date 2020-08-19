<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$animation_on_scroll = lebe_get_option( 'animation_on_scroll', false );
$lebe_blog_bg_items  = lebe_get_option( 'lebe_blog_bg_items', 15 );
$lebe_blog_lg_items  = lebe_get_option( 'lebe_blog_lg_items', 4 );
$lebe_blog_md_items  = lebe_get_option( 'lebe_blog_md_items', 4 );
$lebe_blog_sm_items  = lebe_get_option( 'lebe_blog_sm_items', 6 );
$lebe_blog_xs_items  = lebe_get_option( 'lebe_blog_xs_items', 6 );
$lebe_blog_ts_items  = lebe_get_option( 'lebe_blog_ts_items', 12 );

$post_classes[] = 'post-item';
$post_classes[] = 'col-bg-' . $lebe_blog_bg_items;
$post_classes[] = 'col-lg-' . $lebe_blog_lg_items;
$post_classes[] = 'col-md-' . $lebe_blog_md_items;
$post_classes[] = 'col-sm-' . $lebe_blog_sm_items;
$post_classes[] = 'col-xs-' . $lebe_blog_xs_items;
$post_classes[] = 'col-ts-' . $lebe_blog_ts_items;
if ( $animation_on_scroll ) {
	$post_classes[] = 'lebe-wow fadeInUp';
}

$width  = 440;
$height = 503;

?>
<?php if ( have_posts() ) : ?>
    <div class="blog-content grid auto-clear row">
		<?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( $post_classes ); ?>>
                <div class="post-thumb-grid">
                    <div class="post-date-wrap">
                        <a class="post-date"
                           href="<?php the_permalink(); ?>"><span><?php echo get_the_date(); ?></span></a>
                    </div>
					<?php lebe_post_thumbnail( $width, $height ); ?>
                </div>

                <div class="post-info">
                    <div class="header-info">
                        <div class="cat-post">
							<?php the_category( ', ', '' ); ?>
                        </div>
                        <div class="post-expand">
                            <!-- <?php //lebe_post_product_meta(); ?> -->
							<?php do_action( 'lebe_social_share' ); ?>
                            <div class="comment-count">
								<?php comments_number(
									esc_html__( '0', 'lebe' ),
									esc_html__( '1', 'lebe' ),
									esc_html__( '%', 'lebe' )
								);
								?>
                            </div>
                        </div>
                    </div>
                    <div class="content-info">
                        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    </div>
                    <div class="content-info">
                        <div class="post-excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 15, esc_html__( '...', 'lebe' ) ); ?></div>
                    </div>
                    <div class="footer-info clearfix">
                        <a class="read-more-blog"
                           href="<?php the_permalink(); ?>"><?php echo esc_html__( 'Continue reading', 'lebe' ); ?></a>

                    </div>
                </div>
            </article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
    </div>
	<?php lebe_paging_nav(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>