<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

if ( !$related_products ) {
	return;
}

$classes                      = array();
$lebe_woo_product_style      = lebe_get_option( 'lebe_shop_product_style', 1 );
$lebe_enable_relate_products = lebe_get_option( 'enable_relate_products', 'yes' );
if ( $lebe_enable_relate_products == 'no' ) {
	return;
}

$classes[]      = 'product-item style-' . $lebe_woo_product_style;
$template_style = 'style-' . $lebe_woo_product_style;

$woo_related_ls_items = lebe_get_option( 'lebe_woo_related_ls_items', 5 );
$woo_related_lg_items = lebe_get_option( 'lebe_woo_related_lg_items', 4 );
$woo_related_md_items = lebe_get_option( 'lebe_woo_related_md_items', 3 );
$woo_related_sm_items = lebe_get_option( 'lebe_woo_related_sm_items', 2 );
$woo_related_xs_items = lebe_get_option( 'lebe_woo_related_xs_items', 2 );
$woo_related_ts_items = lebe_get_option( 'lebe_woo_related_ts_items', 1 );

$data_reponsive = array(
	'0'    => array(
		'items' => $woo_related_ts_items,
        'margin' => 20,
	),
	'360'  => array(
		'items' => $woo_related_xs_items,
        'margin' => 20,
	),
	'768'  => array(
		'items' => $woo_related_sm_items,
        'margin' => 30,
	),
	'992'  => array(
		'items' => $woo_related_md_items,
        'margin' => 30,
	),
	'1200' => array(
		'items' => $woo_related_lg_items,
        'margin' => 40,
	),
	'1500' => array(
		'items' => $woo_related_ls_items,
        'margin' => 40,
	),
);

$data_reponsive    = json_encode( $data_reponsive );
$loop              = 'false';
$dots              = 'true';
$data_margin       = '40';
$woo_related_title = lebe_get_option( 'lebe_related_products_title', 'Related Products' );

if ( $related_products ) : ?>
	<?php
	if ( count( $related_products ) > $woo_related_lg_items ) {
		$loop = 'true';
	}
	?>
    <section class="related products product-grid">

        <h2 class="product-grid-title"><?php echo esc_html( $woo_related_title ) ?></h2>
        <div class="owl-carousel owl-products equal-container better-height nav-center nav-circle"
             data-margin="<?php echo esc_attr( $data_margin ); ?>" data-nav="true"
             data-dots="<?php echo esc_attr( $dots ); ?>" data-loop="<?php echo esc_attr( $loop ); ?>"
             data-responsive='<?php echo esc_attr( $data_reponsive ); ?>'>
			<?php foreach ( $related_products as $related_product ) : ?>
                <div <?php post_class( $classes ) ?>>
					<?php
					$post_object = get_post( $related_product->get_id() );
					
					setup_postdata( $GLOBALS['post'] =& $post_object );
					
					wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
                </div>
			<?php endforeach; ?>
        </div>

    </section>

<?php endif;

wp_reset_postdata();
