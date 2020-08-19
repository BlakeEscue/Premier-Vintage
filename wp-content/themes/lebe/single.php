<?php
/**
 * The template for displaying all single posts
 *
 * @package    Lebe
 * @subpackage Lebe
 * @since      1.0.0
 * @version    1.0.0
 */

$lebe_post_used_sidebar = lebe_get_option( 'single_post_sidebar', 'sidebar-1' );
$lebe_blog_style = lebe_get_option( 'blog-style', 'standard' );
/*Single post layout*/
$lebe_blog_layout = lebe_get_option( 'sidebar_single_post_position', 'right' );
$lebe_blog_single_layout = lebe_get_option( 'lebe_blog_single_layout', 'layout1' );
$blog_frame_width = intval( lebe_get_option( 'blog_frame_width', '1400px' ) );
if ( ! is_active_sidebar( $lebe_post_used_sidebar ) ) {
	$lebe_blog_layout = 'full';
}

$lebe_container_class = array( 'single-container' );
if ( $lebe_blog_layout == 'full' ) {
	$lebe_container_class[] = 'no-sidebar';
} else {
	$lebe_container_class[] = $lebe_blog_layout . '-sidebar has-sidebar';
}

$lebe_content_class   = array();
$lebe_content_class[] = 'main-single-content';
if ( $lebe_blog_layout == 'full' ) {
	$lebe_content_class[] = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
} else {
	$lebe_content_class[] = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
}
if ( $lebe_blog_style == 'standard' ) {
    $lebe_content_class[] = 'single-standard';
}
$lebe_sidebar_class   = array();
$lebe_sidebar_class[] = 'sidebar';
if ( $lebe_blog_layout != 'full' ) {
	$lebe_sidebar_class[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12 ';
}

$show_cats = lebe_get_option( 'post-meta-cats', 'yes' );
$show_tags = lebe_get_option( 'post-meta-tags', 'yes' );

$featured_img_size   = lebe_get_option( 'featured_img_size', '1400x817' );
$featured_img_width  = 1400;
$featured_img_height = 817;

if ( trim( $featured_img_size ) != '' ) {
	$featured_img_size_arg = explode( 'x', $featured_img_size );
	$featured_img_width    = isset( $featured_img_size_arg[0] ) ? $featured_img_size_arg[0] : $featured_img_width;
	$featured_img_height   = isset( $featured_img_size_arg[1] ) ? $featured_img_size_arg[1] : $featured_img_height;
}
get_header(); ?>
    <div class="<?php echo esc_attr( implode( ' ', $lebe_container_class ) ); ?>" style="max-width: <?php echo esc_attr( $blog_frame_width ); ?>px;">
        <div class="container">
            <div class="row">
				<?php while ( have_posts() ) {
					the_post();
					$featured_img = array();
					if ( has_post_thumbnail() ) {
                        if ($lebe_blog_single_layout == 'layout1') {
                            $featured_img = lebe_resize_image(get_post_thumbnail_id(), 'null', 1040, 700, true, true, false);
                        } else {
                            $featured_img = lebe_resize_image(get_post_thumbnail_id(), 'null', $featured_img_width, $featured_img_height, true, true, false);
                        }
                    }
					?>
                    <?php if( $lebe_blog_single_layout == 'layout1'){?>
                        <div class="lebe-breadcrumb">
                            <?php get_template_part( 'template-parts/part', 'breadcrumb' );?>
                        </div>
                    <?php }; ?>
                    <?php if( $lebe_blog_single_layout == 'layout2'){?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-post">
                                <div class="post-info">
                                    <h2 class="post-title"><?php the_title(); ?></h2>
                                    <span class="post-date"> <?php the_date(); ?></span>
                                </div>
                                <?php if ( has_post_format( 'video' ) || has_post_format( 'audio' ) ) { ?>
                                    <?php
                                    // Get Link from Metabox
                                    $url = get_post_meta( get_the_ID(), '_custom_post_woo_options', true );
                                    if ( ! empty( $url['audio-video-url'] ) ) { ?>
                                        <?php $video = $url['audio-video-url']; ?>
                                        <div class="post-thumb">
                                            <?php if ( wp_oembed_get( $video ) ) {
                                                echo wp_oembed_get( $video );
                                            } else {
                                                echo wp_kses_post( $video );
                                            }; ?>
                                        </div>
                                    <?php } else { ?>
                                        <?php if ( has_post_thumbnail() ) {
                                            echo lebe_img_output( $featured_img, 'attachment-post-thumbnail wp-post-image' );
                                        }; ?>
                                    <?php }; ?>
                                <?php } elseif ( has_post_format( 'gallery' ) ) { ?>
                                    <?php
                                    // Get Link from Metabox
                                    $list_imgs = get_post_meta( get_the_ID(), '_custom_post_woo_options', true );
                                    ?>
                                    <?php if ( ! empty( $list_imgs['post-gallery'] ) ) { ?>
                                        <div class="post-thumb">
                                            <div class="post-gallery owl-carousel nav-circle control-light"
                                                 data-autoplay="false"
                                                 data-nav="true" data-dots="true" data-loop="false" data-slidespeed="200"
                                                 data-margin="0" data-items="1">
                                                <?php $ids = explode( ',', $list_imgs['post-gallery'] ); ?>
                                                <?php foreach ( $ids as $id ): ?>
                                                    <?php $img_src = lebe_resize_image( $id, null, $featured_img_width, $featured_img_height, true, true, false ) ?>
                                                    <div class="post-img-item">
                                                        <?php echo lebe_img_output( $img_src ); ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <?php if ( has_post_thumbnail() ) { ?>
                                            <?php
                                            echo lebe_img_output( $featured_img, 'attachment-post-thumbnail wp-post-image' );
                                            ?>
                                        <?php }; ?>
                                    <?php }; ?>
                                <?php } else { ?>
                                    <?php if ( has_post_thumbnail() ) { ?>
                                        <?php
                                        echo lebe_img_output( $featured_img, 'attachment-post-thumbnail wp-post-image' );
                                        ?>
                                    <?php }; ?>
                                <?php }; ?>

                            </div>
                        </div>
                    <?php } ?>
                    <div class="<?php echo esc_attr( implode( ' ', $lebe_content_class ) ); ?>">
                    <?php if( $lebe_blog_single_layout == 'layout1'){?>
                        <div class="post-info-layout1">
                            <?php if ( has_post_format( 'video' ) || has_post_format( 'audio' ) ) { ?>
                                <?php
                                // Get Link from Metabox
                                $url = get_post_meta( get_the_ID(), '_custom_post_woo_options', true );
                                if ( ! empty( $url['audio-video-url'] ) ) { ?>
                                    <?php $video = $url['audio-video-url']; ?>
                                    <div class="post-thumb">
                                        <?php if ( wp_oembed_get( $video ) ) {
                                            echo wp_oembed_get( $video );
                                        } else {
                                            echo wp_kses_post( $video );
                                        }; ?>
                                    </div>
                                <?php } else { ?>
                                    <?php if ( has_post_thumbnail() ) {
                                        echo lebe_img_output( $featured_img, 'attachment-post-thumbnail wp-post-image' );
                                    }; ?>
                                <?php }; ?>
                            <?php } elseif ( has_post_format( 'gallery' ) ) { ?>
                                <?php
                                // Get Link from Metabox
                                $list_imgs = get_post_meta( get_the_ID(), '_custom_post_woo_options', true );
                                ?>
                                <?php if ( ! empty( $list_imgs['post-gallery'] ) ) { ?>
                                    <div class="post-thumb">
                                        <div class="post-gallery owl-carousel nav-circle control-light"
                                             data-autoplay="false"
                                             data-nav="true" data-dots="true" data-loop="false" data-slidespeed="200"
                                             data-margin="0" data-items="1">
                                            <?php $ids = explode( ',', $list_imgs['post-gallery'] ); ?>
                                            <?php foreach ( $ids as $id ): ?>
                                                <?php $img_src = lebe_resize_image( $id, null, $featured_img_width, $featured_img_height, true, true, false ) ?>
                                                <div class="post-img-item">
                                                    <?php echo lebe_img_output( $img_src ); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <?php if ( has_post_thumbnail() ) { ?>
                                        <?php
                                        echo lebe_img_output( $featured_img, 'attachment-post-thumbnail wp-post-image' );
                                        ?>
                                    <?php }; ?>
                                <?php }; ?>
                            <?php } else { ?>
                                <?php if ( has_post_thumbnail() ) { ?>
                                    <?php
                                    echo lebe_img_output( $featured_img, 'attachment-post-thumbnail wp-post-image' );
                                    ?>
                                <?php }; ?>
                            <?php }; ?>
                            <span class="post-date"> <?php the_date(); ?></span>
                            <h2 class="post-title"><?php the_title(); ?></h2>
                        </div>
                        <?php } ?>
						<?php get_template_part( 'templates/blog/blog', 'single' ); ?>
						<?php // If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif; ?>
                    </div>
				<?php }; // Endwhile ?>
				
				<?php if ( $lebe_blog_layout != "full" ) { ?>
                    <div class="<?php echo esc_attr( implode( ' ', $lebe_sidebar_class ) ); ?>">
						<?php get_sidebar(); ?>
                    </div>
				<?php }; ?>
            </div> <!--End .row-->
        </div><!-- .container -->
    </div><!-- .wrap -->

<?php get_footer();
