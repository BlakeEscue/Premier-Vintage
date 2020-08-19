<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


get_header();

/* Data MetaBox */
$data_meta             = get_post_meta( get_the_ID(), '_custom_page_side_options', true );
$lebe_page_extra_class = '';
$lebe_page_layout      = 'left';
$lebe_page_sidebar     = 'sidebar-1';

if ( ! empty( $data_meta ) ) {
	$lebe_page_extra_class = $data_meta['page_extra_class'];
	$lebe_page_layout      = $data_meta['sidebar_page_layout'];
	$lebe_page_sidebar     = $data_meta['page_sidebar'];
}

if ( ! is_active_sidebar( $lebe_page_sidebar ) ) {
	$lebe_page_layout = 'full';
}

/*Main container class*/
$lebe_main_container_class   = array();
$lebe_main_container_class[] = $lebe_page_extra_class;
$lebe_main_container_class[] = 'main-container';
if ( $lebe_page_layout == 'full' ) {
	$lebe_main_container_class[] = 'no-sidebar';
} else {
	$lebe_main_container_class[] = $lebe_page_layout . '-slidebar';
}
$lebe_main_content_class   = array();
$lebe_main_content_class[] = 'main-content';
if ( $lebe_page_layout == 'full' ) {
	$lebe_main_content_class[] = 'col-sm-12';
} else {
	$lebe_main_content_class[] = 'col-lg-9 col-md-9 col-sm-8 col-xs-12';
}
$lebe_slidebar_class   = array();
$lebe_slidebar_class[] = 'sidebar';
if ( $lebe_page_layout != 'full' ) {
	$lebe_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4 col-xs-12';
}

?>
    <main class="site-main <?php echo esc_attr( implode( ' ', $lebe_main_container_class ) ); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $lebe_main_content_class ) ); ?>">
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
                            <div class="page-main-content">
								<?php
								the_content();
								wp_link_pages( array(
									               'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'lebe' ) . '</span>',
									               'after'       => '</div>',
									               'link_before' => '<span>',
									               'link_after'  => '</span>',
									               'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'lebe' ) . ' </span>%',
									               'separator'   => '<span class="screen-reader-text">, </span>',
								               )
								);
								?>
                            </div>
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
							<?php
						}
					}
					?>
                </div>
				<?php if ( $lebe_page_layout != "full" ): ?>
					<?php if ( is_active_sidebar( $lebe_page_sidebar ) ) : ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr( implode( ' ', $lebe_slidebar_class ) ); ?>">
							<?php dynamic_sidebar( $lebe_page_sidebar ); ?>
                        </div><!-- .widget-area -->
					<?php endif; ?>
				<?php endif; ?>
            </div>
        </div>
    </main>
<?php get_footer(); 