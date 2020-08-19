<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$enable_header_mini_cart_mobile      = lebe_get_option( 'enable_header_mini_cart_mobile', false );
$enable_header_product_search_mobile = lebe_get_option( 'enable_header_product_search_mobile', false );
$enable_wishlist_mobile              = lebe_get_option( 'enable_wishlist_mobile', false );
$enable_lang_mobile                  = lebe_get_option( 'enable_lang_mobile', false );
$enable_header_mobile_sticky         = lebe_get_option( 'enable_header_mobile_sticky', false );
$header_sticky_class = '';
if ( $enable_header_mobile_sticky) {
    $header_sticky_class = 'menu-sticky-smart';
}
?>

<header class="site-header header site-header-primary site-header-mobile site-header-mobile-default <?php echo esc_attr( $header_sticky_class ); ?>">
    <div class="header-content">
        <div class="main-header header-mobile">
            <nav class="main-navigation" id="site-navigation">
                <div class="main-menu">
                    <div id="box-mobile-menu" class="box-mobile-menu full-height box-mobile-menu-tabs box-tabs">
                        <div class="box-mibile-overlay"></div>
                        <div class="box-mobile-menu-inner">
                            <a href="#" class="close-menu"><span
                                        class=""><?php esc_html_e( 'Close', 'lebe' ); ?></span></a>

                            <div id="mobile-menu-content-tab"
                                 class="box-inner mn-mobile-content-tab box-tab-content active">

                                <div class="mobile-back-nav-wrap">
                                    <a href="#" id="back-menu" class="back-menu"><i
                                                class="pe-7s-angle-left"></i></a>
                                    <span class="box-title"><?php echo esc_html__( 'Menu', 'lebe' ); ?></span>
                                </div>
    							
    							<?php
    							wp_nav_menu( array(
    								             'menu'            => 'primary-menu',
    								             'theme_location'  => 'primary',
    								             'depth'           => 3,
    								             'container'       => '',
    								             'container_class' => '',
    								             'container_id'    => '',
    								             'menu_class'      => 'clone-main-menu lebe-nav main-menu',
    								             'fallback_cb'     => 'Lebe_navwalker::fallback',
    								             'walker'          => new Lebe_navwalker(),
    							             )
    							);
    							
    							if ( $enable_wishlist_mobile ) {
    								$wish_list_url = lebe_wisth_list_url();
    								if ( trim( $wish_list_url ) != '' ) {
    									echo '<div class="wish-list-mobile-menu-link-wrap"><a href="' . esc_url( $wish_list_url ) . '" class="wish-list-mobile-menu-link">' . esc_html__( 'My Wishlist', 'lebe' ) . '</a> <span class="flaticon-heart"></span></div>';
    								}
    							}

    							?>
                                <?php if ( $enable_lang_mobile ) { ?>
                                <?php if ( class_exists( 'SitePress' ) ) { ?>
                                    <ul class="header-lang-mobile">
                                        <li class="menu-item item-lang-currency">
                                            <?php if ( class_exists( 'SitePress' ) ) { ?>
                                                <div class="currency-language-wrap">
                                                    <ul class="currency-language">
                                                        <?php
                                                        get_template_part( 'template-parts/header', 'language' );
                                                        ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                            <?php get_template_part( 'template-parts/header', 'currency' ); ?>
                                        </li>
                                    </ul>
                                <?php } ?>
                                <?php } ?>
                            </div>

                            <div id="mobile-login-content-tab" class="box-inner mn-mobile-content-tab box-tab-content">
                                <div class="my-account-wrap">
    								<?php
    								if ( shortcode_exists( 'woocommerce_my_account' ) ) {
    									echo do_shortcode( '[woocommerce_my_account]' );
    								}
    								?>
                                </div>
                            </div>

                            <div class="box-tabs-nav-wrap">
                                <div class="box-tabs-nav">
                                    <a href="#mobile-menu-content-tab" class="box-tab-nav active">
                                        <div class="hamburger hamburger--collapse js-hamburger">
                                            <div class="hamburger-box">
                                                <div class="hamburger-inner"></div>
                                            </div>
                                        </div>
                                        <span class="nav-text"><?php esc_html_e( 'Menu', 'lebe' ); ?></span>
                                    </a>
                                    <a href="#mobile-login-content-tab"
                                       class="box-tab-nav">
                                        <i class="flaticon-profile"></i>
                                        <div class="nav-text account-text">
    										<?php
    										if ( is_user_logged_in() ) {
    											esc_html_e( 'My Account', 'lebe' );
    										} else {
    											esc_html_e( 'Login', 'lebe' );
    										}
    										?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="menu-mobile-hamburger-wrap col-xs-4">
                <a href="#" class="mobile-hamburger-navigation">
                    <div class="hamburger hamburger--collapse js-hamburger">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="logo col-xs-4"><?php lebe_get_logo(); ?></div>
            <div class="header-right col-xs-4">
				<?php
				if ( $enable_header_mini_cart_mobile && class_exists( 'WooCommerce' ) ) {
					get_template_part( 'template-parts/header', 'minicart' );
				}
				?>
            </div><!--End .header-right-->
			<?php // lebe_search_from_mobile(); ?>
        </div> <!-- End .main-header -->
    </div>
    <?php
    if ( $enable_header_product_search_mobile ) {
        ?>
        <div class="header-search-box">
            <?php lebe_search_form(); ?>
        </div>
        <?php
    }?>
	<?php get_template_part( 'template-parts/hero', 'section' ); ?>
</header>