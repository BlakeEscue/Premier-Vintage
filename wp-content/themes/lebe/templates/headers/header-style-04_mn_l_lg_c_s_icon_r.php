<?php
/*
 Name:  Header Style4 Menu Left - Logo Center - Search, Icons Right
 */
$menu_sticky          = lebe_get_option( 'enable_sticky_menu', 'none' );
$header_pos           = lebe_get_option( 'header_position', 'relative' );
$header_class         = '';
$header_color         = lebe_get_option( 'header_text_color', '#000' );
$header_bg_color      = lebe_get_option( 'header_bg_color', '#fff' );
$single_id            = lebe_get_single_page_id();
$enable_custom_header = false;
if ( $single_id > 0 ) {
	$meta_data = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
	// Override custom header (if request from url)
	if ( isset( $_GET['enable_custom_header'] ) ) {
		$meta_data['enable_custom_header'] = $_GET['enable_custom_header'] == 'yes';
	}
	if ( isset( $meta_data['enable_custom_header'] ) ) {
		$enable_custom_header = $meta_data['enable_custom_header'];
	}
	if ( $enable_custom_header ) {
		$header_color    = $meta_data['header_text_color'];
		$header_bg_color = $meta_data['header_bg_color'];
		$header_pos      = isset( $meta_data['header_position'] ) ? $meta_data['header_position'] : $header_pos;
		$menu_sticky     = $meta_data['enable_sticky_menu'];
	}
}

if ( $menu_sticky == 'normal' ) {
	$header_class = ' menu-sticky-nomal';
} elseif ( $menu_sticky == 'smart' ) {
	$header_class = ' menu-sticky-smart';
}
if ( lebe_get_option( 'enable_topbar' ) ) {
	$header_class .= ' topbar-enabled';
}

$header_class .= ' header-pos-' . esc_attr( $header_pos );
$enable_info_product_single = lebe_get_option( 'enable_info_product_single', false );
if ( $enable_info_product_single ) {
	$header_class .= ' sticky-info_single';
}
$enable_header_wishlist = lebe_get_option( 'enable_header_wishlist', false );
?>
<header id="header"
        class="site-header header style-04 header-mn_l-lg_c-ic_r mm_l lg_c ic_r <?php echo esc_attr( $header_class ); ?>">
    <div class="header-main-inner">
        <div class="header-wrap"
         style="background-color: <?php echo esc_attr( $header_bg_color ); ?>; color: <?php echo esc_attr( $header_color ); ?>;">
		<?php get_template_part( 'template-parts/header', 'topbar' ); ?>
        <div class="header-wrap-stick">
            <div class="header-position" style="background-color: <?php echo esc_attr( $header_bg_color ); ?>;">
                <div class="header-container container">
                    <div class="main-menu-wrapper"></div>
                    <div class="row row-table">
                        <div class="header-search col-md-5 col-sm-5">
                            <a class="menu-bar mobile-navigation" href="javascript:void(0)">
                                <span class="menu-btn-icon">
                                    <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                                    <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                                    <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                                </span>
                            </a>
                            <div class="header-menu horizon-menu">
                                <nav class="main-navigation">
                                    <?php
                                    wp_nav_menu( array(
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
                                    ?>
                                </nav>
                            </div>
                        </div>
                        <div class="header-logo col-md-2 col-sm-2">
                            <div class="logo">
								<?php lebe_get_logo(); ?>
                            </div>
                        </div>
                        <div class="header-control-right col-md-5 col-sm-5">
                            <div class="header-search-box">
                                <?php lebe_search_form(); ?>
                            </div>
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
                            <?php if ( class_exists( 'WooCommerce' ) ) { ?>
                                <div class="block-account">
                                    <?php if ( is_user_logged_in() ) { ?>
                                        <?php $currentUser = wp_get_current_user(); ?>
                                        <a class="header-userlink"
                                           href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                                           title="<?php esc_attr_e( 'My Account', 'lebe' ); ?>">
                                            <span class="screen-reader-text"><?php echo sprintf( esc_html__( 'Hi, %s', 'lebe' ), $currentUser->display_name ); ?></span>
                                            <span class="flaticon-profile"></span>
                                        </a>
                                    <?php } else { ?>
                                        <a href="#login-popup" data-effect="mfp-zoom-in" class="acc-popup">
                                            <span>
                                                <span class="flaticon-profile"></span>
                                            </span>
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php
                                        if ( $enable_header_wishlist ) {
                                            $wish_list_url = lebe_wisth_list_url();
                                            if ( trim( $wish_list_url ) != '' ) {
                                                echo '<div class="wish-list-wrap"><a href="' . esc_url( $wish_list_url ) . '" class="header-wish-list"><span class="flaticon-heart"></span></a></div>';
                                            }
                                        }
                                    ?>
                                <?php get_template_part( 'template-parts/header', 'minicart' ); ?>
                            <?php }; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="header-action-res" style="background-color: <?php echo esc_attr( $header_bg_color ); ?>; color: <?php echo esc_attr( $header_color ); ?>;">
        <div class="meta-woo">
            <a class="menu-bar mobile-navigation" href="javascript:void(0)">
                <span class="menu-btn-icon">
                    <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                    <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                    <span style="color: <?php echo esc_attr( $header_color ); ?>;"></span>
                </span>
            </a>
            <?php lebe_search_form(); ?>
        </div>
        <div class="logo">
            <?php lebe_get_logo(); ?>
        </div>
        <?php if ( class_exists( 'WooCommerce' ) ) { ?>
            <div class="acction-right">
                <div class="block-account">
                    <?php if ( is_user_logged_in() ) { ?>
                        <?php $currentUser = wp_get_current_user(); ?>
                        <a class="header-userlink"
                           href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                           title="<?php esc_attr_e( 'My Account', 'lebe' ); ?>">
                            <span class="screen-reader-text"><?php echo sprintf( esc_html__( 'Hi, %s', 'lebe' ), $currentUser->display_name ); ?></span>
                            <span class="flaticon-profile"></span>
                        </a>
                    <?php } else { ?>
                        <a href="#login-popup" data-effect="mfp-zoom-in" class="acc-popup">
                                            <span>
                                                <span class="flaticon-profile"></span>
                                            </span>
                        </a>
                    <?php } ?>
                </div>
                <?php get_template_part( 'template-parts/header', 'minicart' ); ?>
            </div>
        <?php }; ?>
    </div>
	<?php
	get_template_part( 'template-parts/hero', 'section' );
	?>

</header>