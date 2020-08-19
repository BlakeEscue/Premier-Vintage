<?php
$lebe_blog_used_sidebar = lebe_get_option( 'blog_sidebar', 'sidebar-1' );
if ( is_single() ) {
	$lebe_blog_used_sidebar = lebe_get_option( 'single_post_sidebar', 'sidebar-1' );
}

?>
<?php if ( is_active_sidebar( $lebe_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area sidebar-blog">
		<?php dynamic_sidebar( $lebe_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>