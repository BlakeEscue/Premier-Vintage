<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package lebe
 */
?>
<?php get_header(); ?>
<?php

/* Blog Layout */
$lebe_blog_layout = lebe_get_option( 'lebe_blog_layout', 'right' );

/* Blog Style */
$lebe_container_class   = array();
$lebe_container_class[] = 'search-page';
if ( $lebe_blog_layout == 'full' ) {
	$lebe_container_class[] = 'no-sidebar';
} else {
	$lebe_container_class[] = $lebe_blog_layout . '-sidebar has-sidebar blog-page';
}

$lebe_content_class   = array();
$lebe_content_class[] = 'main-content';
if ( $lebe_blog_layout == 'full' ) {
	$lebe_content_class[] = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
} else {
	$lebe_content_class[] = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
}
$lebe_slidebar_class   = array();
$lebe_slidebar_class[] = 'sidebar';
if ( $lebe_blog_layout != 'full' ) {
	$lebe_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
}

?>
    <div class="<?php echo esc_attr( implode( ' ', $lebe_container_class ) ); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $lebe_content_class ) ); ?>">
					<?php get_template_part( 'templates/blog/blog', 'search' ); ?>
                </div>
				<?php if ( $lebe_blog_layout != "full" ): ?>
                    <div class="<?php echo esc_attr( implode( ' ', $lebe_slidebar_class ) ); ?>">
						<?php get_sidebar(); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>