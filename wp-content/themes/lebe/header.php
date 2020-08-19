<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link       https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package    WordPress
 * @subpackage Lebe
 * @since      1.0
 * @version    1.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
$enable_header_mobile = lebe_get_option( 'enable_header_mobile', false );
$wrapper_class        = '';
$menu_sticky          = lebe_get_option( 'enable_sticky_menu', 'none' );
$single_id            = lebe_get_single_page_id();
if ( $single_id > 0 ) {
	$enable_custom_header = false;
	$meta_data            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
	if ( $enable_custom_header ) {
		$menu_sticky = $meta_data['enable_sticky_menu'];
	}
}
if ( $menu_sticky == 'normal' ) {
	$wrapper_class = 'wrapper_menu-sticky-nomal';
} elseif ( $menu_sticky == 'smart' ) {
	$wrapper_class = ' wrapper_menu-sticky';
}
$sticky_info_w              = '';
$enable_info_product_single = lebe_get_option( 'enable_info_product_single', false );
if ( $enable_info_product_single ) {
	$sticky_info_w = 'sticky-info_single_wrap';
}
$lebe_blog_single_layout = lebe_get_option( 'lebe_blog_single_layout', 'layout1' );
$lebe_single_layout ='';
if ( is_single() ){
    if ( $lebe_blog_single_layout == 'layout1'){
        $lebe_single_layout = 'single-layout1';
    }else{
        $lebe_single_layout = 'single-layout2';
    }
}
$lebe_blog_style = lebe_get_option( 'blog-style', 'standard' );
$lebe_blog_style_layout = '';
if ( is_home() ) {
    if ($lebe_blog_style == 'modern') {
        $lebe_blog_style_layout = 'blog_style_modern';
    }
}
?>
<div id="page-wrapper"
     class="page-wrapper <?php echo esc_attr($wrapper_class); ?><?php echo esc_attr($sticky_info_w); ?> <?php echo esc_attr($lebe_single_layout); ?> <?php echo esc_attr($lebe_blog_style_layout); ?>">
    <div class="body-overlay"></div>
    <div class="sidebar-canvas-overlay"></div>
	<?php if ( ! $enable_header_mobile || ( $enable_header_mobile && ! lebe_is_mobile() ) ) { ?>
        <div id="box-mobile-menu" class="box-mobile-menu full-height">
            <a href="javascript:void(0);" id="back-menu" class="back-menu"><i class="pe-7s-angle-left"></i></a>
            <span class="box-title"><?php echo esc_html__( 'Menu', 'lebe' ); ?></span>
            <a href="javascript:void(0);" class="close-menu"><i class="pe-7s-close"></i></a>
            <div class="box-inner"></div>
        </div>
	<?php } ?>
	<?php lebe_get_header(); ?>
	<?php if ( is_search() && class_exists( 'WooCommerce' ) ) {
		get_template_part( 'template_parts/search', 'heading' );
	} ?>
	<?php
	if ( is_singular( 'product' ) ):
		do_action( 'lebe_product_toolbar' );
	endif;
	?>
