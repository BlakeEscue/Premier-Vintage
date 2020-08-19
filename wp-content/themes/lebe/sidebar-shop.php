<?php
/**
 * The sidebar containing the main widget area
 *
 */
?>
<?php

$lebe_woo_shop_used_sidebar = lebe_get_option( 'shop_page_sidebar', 'shop-widget-area' );
if ( is_product() ) {
	$lebe_woo_shop_used_sidebar = lebe_get_option( 'single_product_sidebar', 'product-widget-area' );
	if ( $lebe_woo_shop_used_sidebar == '' ) {
		$lebe_woo_shop_used_sidebar = 'product-widget-area';
	}
}
?>

<?php if ( is_active_sidebar( $lebe_woo_shop_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area shop-sidebar">
		<?php dynamic_sidebar( $lebe_woo_shop_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>
