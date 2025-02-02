<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>
<?php if ( lebe_is_mobile() ) { ?>
    <form class="woocommerce-cart-form woocommerce-cart-form-mobile"
          action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

        <table class="shop_table_mobile cart woocommerce-cart-form__contents" cellspacing="0">
            <tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>
			
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                        <td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							
							if ( ! $product_permalink ) {
								printf( '%s', $thumbnail );
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
							?>
                        </td>

                        <td class="product-info" data-title="<?php esc_attr_e( 'Product Info', 'lebe' ); ?>">
							<?php
							if ( ! $product_permalink ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
							}
							
							// Meta data
							echo wc_get_formatted_cart_item_data( $cart_item );
							
							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'lebe' ) . '</p>';
							}
							?>
                            <span class="product-price" data-title="<?php esc_attr_e( 'Price', 'lebe' ); ?>">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                ?>
                            </span>
							
							<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									                                                'input_name'  => "cart[{$cart_item_key}][qty]",
									                                                'input_value' => $cart_item['quantity'],
									                                                'max_value'   => $_product->get_max_purchase_quantity(),
									                                                'min_value'   => '0',
								                                                ), $_product, false );
							}
							
							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							?>
                            <span class="product-remove">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
	                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times; <span class="remove-text">' . esc_html__( 'Remove', 'lebe' ) . '</span></a>',
	                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
	                                esc_html__( 'Remove this item', 'lebe' ),
	                                esc_attr( $product_id ),
	                                esc_attr( $_product->get_sku() )
                                ), $cart_item_key );
                                ?>
                            </span>
                        </td>


                    </tr>
					<?php
				}
			}
			?>
			
			<?php do_action( 'woocommerce_cart_contents' ); ?>
            </tbody>
        </table>
        <div class="actions">
            <div class="actions-btn">
                <input type="submit" class="button" name="update_cart"
                       value="<?php esc_attr_e( 'Update cart', 'lebe' ); ?>"/>
				<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
                    <a class="shopping"
                       href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php echo esc_html__( 'Continue Shopping', 'lebe' ) ?></a>
				<?php endif; ?>
            </div>
			<?php if ( wc_coupons_enabled() ) { ?>
                <div class="coupon">
                    <label for="coupon_code"><?php esc_html_e( 'Coupon', 'lebe' ); ?></label> <input type="text"
                                                                                                      name="coupon_code"
                                                                                                      class="input-text"
                                                                                                      id="coupon_code"
                                                                                                      value=""
                                                                                                      placeholder="<?php esc_attr_e( 'Coupon code', 'lebe' ); ?>"/>
                    <input type="submit" class="button" name="apply_coupon"
                           value="<?php esc_attr_e( 'Apply coupon', 'lebe' ); ?>"/>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
                </div>
			<?php } ?>
			
			<?php do_action( 'woocommerce_cart_actions' ); ?>
			
			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
        </div>
		
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>
<?php } else { ?>
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
            <thead>
            <tr>
                <th class="product-thumbnail"><?php esc_html_e( 'Product', 'lebe' ); ?></th>
                <th class="product-name"></th>
                <th class="product-price"><?php esc_html_e( 'Price', 'lebe' ); ?></th>
                <th class="product-quantity"><?php esc_html_e( 'Quantity', 'lebe' ); ?></th>
                <th class="product-subtotal"><?php esc_html_e( 'Total', 'lebe' ); ?></th>
                <th class="product-remove"></th>
            </tr>
            </thead>
            <tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>
			
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                        <td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							
							if ( ! $product_permalink ) {
								printf( '%s', $thumbnail );
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
							?>
                        </td>

                        <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'lebe' ); ?>">
							<?php
							if ( ! $product_permalink ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
							}
							
							// Meta data
							echo wc_get_formatted_cart_item_data( $cart_item );
							
							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'lebe' ) . '</p>';
							}
							?>
                        </td>

                        <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'lebe' ); ?>">
                            <label class="lebe-cart-mobile-lb price-lb hidden-sm hidden-md hidden-lg"><?php esc_attr_e( 'Price', 'lebe' ); ?></label>
							<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
                        </td>

                        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'lebe' ); ?>">
                            <label class="lebe-cart-mobile-lb qty-lb hidden-sm hidden-md hidden-lg"><?php esc_attr_e( 'Quantity', 'lebe' ); ?></label>
							<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									                                                'input_name'  => "cart[{$cart_item_key}][qty]",
									                                                'input_value' => $cart_item['quantity'],
									                                                'max_value'   => $_product->get_max_purchase_quantity(),
									                                                'min_value'   => '0',
								                                                ), $_product, false );
							}
							
							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
							?>
                        </td>

                        <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'lebe' ); ?>">
                            <label class="lebe-cart-mobile-lb subtotal-lb hidden-sm hidden-md hidden-lg"><?php esc_attr_e( 'Total', 'lebe' ); ?></label>
							<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
                        </td>
                        <td class="product-remove">
							<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times; <span class="remove-text">' . esc_html__( 'Remove', 'lebe' ) . '</span></a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'lebe' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
							?>
                        </td>
                    </tr>
					<?php
				}
			}
			?>
			
			<?php do_action( 'woocommerce_cart_contents' ); ?>
            </tbody>
        </table>
        <div class="actions">
            <div class="actions-btn">
                <input type="submit" class="button" name="update_cart"
                       value="<?php esc_attr_e( 'Update cart', 'lebe' ); ?>"/>
				<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
                    <a class="shopping"
                       href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php echo esc_html__( 'Continue Shopping', 'lebe' ) ?></a>
				<?php endif; ?>
            </div>
			<?php if ( wc_coupons_enabled() ) { ?>
                <div class="coupon">
                    <label for="coupon_code"><?php esc_html_e( 'Coupon', 'lebe' ); ?></label> <input type="text"
                                                                                                      name="coupon_code"
                                                                                                      class="input-text"
                                                                                                      id="coupon_code"
                                                                                                      value=""
                                                                                                      placeholder="<?php esc_attr_e( 'Coupon code', 'lebe' ); ?>"/>
                    <input type="submit" class="button" name="apply_coupon"
                           value="<?php esc_attr_e( 'Apply coupon', 'lebe' ); ?>"/>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
                </div>
			<?php } ?>
			
			<?php do_action( 'woocommerce_cart_actions' ); ?>
			
			<?php wp_nonce_field( 'woocommerce-cart' ); ?>
        </div>
		
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>
<?php } ?>
<div class="cart-collaterals">
	<?php
	/**
	 * woocommerce_cart_collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
