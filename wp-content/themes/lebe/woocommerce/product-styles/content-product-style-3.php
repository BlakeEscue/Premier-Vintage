<?php
/*
     Name: Product style 3
     Slug: content-product-style-3
*/

$args = isset($args) ? $args : null;

?>
<div class="product-inner">
    <div class="product-thumb">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action( 'woocommerce_before_shop_loop_item_title', $args );
        ?>

    </div>
    <div class="product-info equal-elem">
        <div class="info-top">
            <div class="varible-loop">
                <?php do_action( 'woocommerce_before_shop_loop_item_title', $args );?>
            </div>
            <div class="button-loop-action">
                <?php do_action('lebe_function_shop_loop_item_wishlist');?>
                <div class="add-to-cart">
                    <?php do_action( 'woocommerce_after_shop_loop_item' );?>
                </div>
                <?php do_action('lebe_function_shop_loop_item_quickview'); ?>
            </div>
        </div>
        <div class="info-product-content">
            <?php
            /**
             * woocommerce_after_shop_loop_item_title hook.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            do_action( 'lebe_shop_loop_rating' );

            // do_action( 'lebe_product_short_description' );
            ?>
        </div>
    </div>
</div>