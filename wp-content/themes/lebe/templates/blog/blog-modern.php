<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$animation_on_scroll = lebe_get_option( 'animation_on_scroll', false );

?>

<?php if ( have_posts() ) : $i = 0; ?>
    <div class="blog-content blog-modern auto-clear row">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			$post_classes[] = 'post-item col-md-6';
			if ( $animation_on_scroll ) {
				$post_classes[] = 'lebe-wow fadeInUp';
			}
			$width  = 644;
			$height = 780;
			?>
            <article <?php post_class( $post_classes ); ?>>
                <div class="post-item-inner">
					<?php lebe_post_thumbnail( $width, $height ); ?>
                    <div class="post-info">
                        <div class="header-info">
                            <a class="post-date"
                               href="<?php the_permalink(); ?>"><span><?php echo get_the_date(); ?></span></a>
                            <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                        </div>
                        <div class="content-info">
                            <div class="post-excerpt"><?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 15, esc_html__( '...', 'lebe' ) ); ?></div>
                        </div>
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