<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$lebe_woo_product_style = lebe_get_option( 'lebe_shop_product_style', 1 );
$enable_products_sizes  = lebe_get_option( 'enable_products_sizes', false );
/*
 * 5 items: col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6
 * 4 items: col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-12
 * 3 items: col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-12
 */

$lebe_woo_bg_items = 3;     // 15
$lebe_woo_lg_items = 3;     // 15
if ( class_exists( 'WeDevs_Dokan' ) ) {
	$lebe_woo_bg_items = 4; 
	$lebe_woo_lg_items = 4; // 15
}

$lebe_woo_md_items = 4;     // 15
$lebe_woo_sm_items = 3;     // 3
$lebe_woo_xs_items = 6;     // 4
$lebe_woo_ts_items = 12;    // 6

$enable_single_product_mobile = lebe_get_option( 'enable_single_product_mobile', true );
if ( $enable_single_product_mobile && lebe_is_mobile() ) {
	$lebe_woo_bg_items      = 15;     // 15
	$lebe_woo_lg_items      = 15;     // 15
	$lebe_woo_md_items      = 15;     // 15
	$lebe_woo_sm_items      = 3;      // 3
	$lebe_woo_xs_items      = 4;      // 4
	$lebe_woo_ts_items      = 6;      // 6
	$lebe_woo_product_style = 1;      // Always use product style 1 on real mobile
}

// Custom columns
if ( ! $enable_products_sizes ) {
	$lebe_woo_bg_items = lebe_get_option( 'lebe_woo_bg_items', 3 );
	$lebe_woo_lg_items = lebe_get_option( 'lebe_woo_lg_items', 3 );
	$lebe_woo_md_items = lebe_get_option( 'lebe_woo_md_items', 4 );
	$lebe_woo_sm_items = lebe_get_option( 'lebe_woo_sm_items', 4 );
	$lebe_woo_xs_items = lebe_get_option( 'lebe_woo_xs_items', 6 );
	$lebe_woo_ts_items = lebe_get_option( 'lebe_woo_ts_items', 6 );
}

$animate_class = 'famiau-wow-continuous lebe-wow fadeInUp';
$classes[]     = 'product-item';
$classes[]     = 'col-bg-' . $lebe_woo_bg_items;
$classes[]     = 'col-lg-' . $lebe_woo_lg_items;
$classes[]     = 'col-md-' . $lebe_woo_md_items;
$classes[]     = 'col-sm-' . $lebe_woo_sm_items;
$classes[]     = 'col-xs-' . $lebe_woo_xs_items;
$classes[]     = 'col-ts-' . $lebe_woo_ts_items;
$classes[]     = $animate_class;

$template_style    = 'style-' . $lebe_woo_product_style;
$classes[]         = 'style-' . $lebe_woo_product_style;
$shop_display_mode = lebe_get_option( 'shop_display_mode', 'grid' );
if ( $shop_display_mode == "list" ) {
	$classes[] = 'style-1';
}
?>

<li <?php post_class( $classes ); ?>>
	<?php if ( $shop_display_mode == "list" ) {
		wc_get_template_part( 'product-styles/content-product', 'style-1' );
	} else {
		wc_get_template_part( 'product-styles/content-product', $template_style );
	} ?>
</li>
