<?php get_header(); ?>
<?php

$enable_single_product_mobile = lebe_get_option( 'enable_single_product_mobile', true );

/*Shop layout*/
$lebe_woo_shop_layout  = lebe_get_option( 'sidebar_shop_page_position', 'left' );
$lebe_woo_shop_sidebar = lebe_get_option( 'shop_page_sidebar', 'shop-widget-area' );
$shop_modern_bg_image  = lebe_get_option( 'shop_modern_bg_image' );
if ( is_product() ) {
    $lebe_woo_shop_layout  = lebe_get_option( 'sidebar_product_position', 'left' );
    $lebe_woo_shop_sidebar = lebe_get_option( 'single_product_sidebar', 'product-widget-area' );
}

// Always full width on real mobile
if ( $enable_single_product_mobile && lebe_is_mobile() ) {
    $lebe_woo_shop_layout = 'full';
}

if ( ! is_active_sidebar( $lebe_woo_shop_sidebar ) ) {
    $lebe_woo_shop_layout = 'full';
}

/*Main container class*/
$main_container_class   = array();
$main_container_modren  = '';
$main_container_class[] = 'main-container shop-page';
if ( $lebe_woo_shop_layout == 'full' ) {
    $main_container_class[] = 'no-sidebar';
}elseif( $lebe_woo_shop_layout == 'modern' ){
    $main_container_class[] = 'shop-modern';
    $main_container_modren = 'main-modern';
}else{
    $main_container_class[] = $lebe_woo_shop_layout . '-sidebar';
}

/*Setting single product*/

$main_content_class   = array();
$main_content_class[] = 'main-content';
if (( $lebe_woo_shop_layout == 'full' ) || ( $lebe_woo_shop_layout == 'modern' ) ||  is_tax( 'dc_vendor_shop' ) ) {
    $main_content_class[] = 'col-sm-12';
} else {
    $main_content_class[] = 'col-md-9 col-sm-8 has-sidebar';
}

$slidebar_class   = array();
$slidebar_class[] = 'sidebar';
if (( $lebe_woo_shop_layout == 'left' ) || ( $lebe_woo_shop_layout == 'right' )){
    $slidebar_class[] = 'col-md-3 col-sm-4 sidebar-' . $lebe_woo_shop_layout;
}
if( $lebe_woo_shop_layout == 'modern' ){
    $shop_modern_bg_image_url = wp_get_attachment_image_url( $shop_modern_bg_image, 'full' );
    if($shop_modern_bg_image_url != ''){
        $css = 'background-image:  url("' . esc_url($shop_modern_bg_image_url) . '");';
    }
}

?>
    <div class="<?php echo esc_attr( implode( ' ', $main_container_class ) ); ?>"
        <?php if(( $lebe_woo_shop_layout == 'modern' ) && ($shop_modern_bg_image_url != '')){?> style="<?php echo esc_attr( $css ); ?>" <?php }?>>
        <?php if ( ! is_single() ) { ?>

        <?php }else{ ?>
        <div class="lebe-single-container">
        <?php }; ?>
            <?php if ( $lebe_woo_shop_layout != 'modern' ) {?>
            <div class="container">
                <?php }; ?>
                <div class="row <?php echo esc_attr($main_container_modren); ?>">
                    <div class="<?php echo esc_attr( implode( ' ', $main_content_class ) ); ?>">
                        <?php
                        /**
                         * lebe_woocommerce_before_main_content hook
                         */
                        do_action( 'lebe_woocommerce_before_main_content' );
                        ?>
                        <?php
                        /**
                         * lebe_before_shop_loop hook.
                         *
                         * @hooked lebe_shop_top_control - 10
                         */
                        if ( ! is_search() ):
                            do_action( 'lebe_before_shop_loop' );
                        endif;
                        ?>
                        <div class="main-product">
                            <?php
                            /**
                             * lebe_woocommerce_before_loop_start hook
                             */
                            do_action( 'lebe_woocommerce_before_loop_start' );

                            woocommerce_content();

                            /**
                             * lebe_woocommerce_before_loop_start hook
                             */
                            do_action( 'lebe_woocommerce_fater_loop_start' );
                            ?>
                        </div> <!-- End .main-product-->
                    </div>

                    <?php if (( $lebe_woo_shop_layout == 'left' ) || ( $lebe_woo_shop_layout == 'right' )): 
                        if( ! is_tax( 'dc_vendor_shop' )): ?>
                        <div class="<?php echo esc_attr( implode( ' ', $slidebar_class ) ); ?>">
                            <?php if ( is_active_sidebar( $lebe_woo_shop_sidebar ) ) : ?>
                                <div id="widget-area" class="widget-area shop-sidebar">
                                    <?php dynamic_sidebar( $lebe_woo_shop_sidebar ); ?>
                                </div><!-- .widget-area -->
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if ( $lebe_woo_shop_layout != 'modern' ) {?>
            </div>
        <?php }; ?>
        <?php if ( ! is_single() ) { ?>

            <?php }else{ ?>
        </div>
        <?php }; ?>
    </div>
<?php get_footer(); ?>