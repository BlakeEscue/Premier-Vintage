<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$animation_on_scroll = lebe_get_option( 'animation_on_scroll', false );

?>

<?php if ( have_posts() ) : $i = 0; ?>
    <div class="blog-content list simplpe">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			$post_classes[] = 'post-item';
			if ( $animation_on_scroll ) {
				$post_classes[] = 'lebe-wow fadeInUp';
			}
			$width  = 1040;
			$height = 610;
			?>
            <article <?php post_class( $post_classes ); ?>>
				<?php lebe_post_thumbnail( $width, $height ); ?>
                <div class="post-info">
                    <div class="header-info">
                        <a class="post-date" href="<?php the_permalink(); ?>"><span><?php echo get_the_date(); ?></span></a>
                        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                    </div>
                    <div class="content-info">
                        <div class="post-excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 200, esc_html__( '...', 'lebe' ) ); ?></div>
                    </div>
                    <div class="footer-info clearfix">
                        <div class="post-author-blog">
                            <span class="title-author"><?php echo esc_html__( 'By:', 'lebe' ); ?></span>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
								<?php the_author() ?>
                            </a>
                        </div>
                        <div class="post-expand">
							<?php lebe_post_product_meta(); ?>
							<?php do_action( 'lebe_social_share' ); ?>
                            <div class="comment-count">
								<?php comments_number(
									esc_html__( '0 comments', 'lebe' ),
									esc_html__( '1 comment', 'lebe' ),
									esc_html__( '% comments', 'lebe' )
								);
								?>
                            </div>
                        </div>
                    </div>
                    <a class="read-more-blog"
                       href="<?php the_permalink(); ?>"><?php echo esc_html__( 'Continue reading', 'lebe' ); ?></a>
                </div>
            </article>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
    </div>
	<?php lebe_paging_nav(); ?>
<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>