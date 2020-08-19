<?php

if ( ! function_exists( 'lebe_custom_css' ) ) {
	function lebe_custom_css() {
		$css = '';
		$css .= lebe_theme_color();
		$css .= lebe_vc_custom_css_footer();
		wp_enqueue_style( 'lebe_custom_css', get_theme_file_uri( '/assets/css/customs.css' ), array(), '1.0' );
		wp_add_inline_style( 'lebe_custom_css', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'lebe_custom_css', 999 );

if ( ! function_exists( 'lebe_theme_color' ) ) {
	function lebe_theme_color() {
		$css = '';
		
		// Typography
		$enable_google_font = lebe_get_option( 'enable_google_font', false );
		if ( $enable_google_font ) {
			$body_font = lebe_get_option( 'typography_themes' );
			if ( ! empty( $body_font ) ) {
				$typography_themes['family']  = 'Open Sans';
				$typography_themes['variant'] = '400';
				$body_fontsize        = lebe_get_option( 'fontsize-body', '15' );
				
				$css .= 'body{';
				$css .= 'font-family: "' . $body_font['family'] . '";';
				if ( '100italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 100;
					font-style: italic;
				';
				} elseif ( '300italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 300;
					font-style: italic;
				';
				} elseif ( '400italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 400;
					font-style: italic;
				';
				} elseif ( '700italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 700;
					font-style: italic;
				';
				} elseif ( '800italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 700;
					font-style: italic;
				';
				} elseif ( '900italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 900;
					font-style: italic;
				';
				} elseif ( 'regular' == $body_font['variant'] ) {
					$css .= 'font-weight: 400;';
				} elseif ( 'italic' == $body_font['variant'] ) {
					$css .= 'font-style: italic;';
				} else {
					$css .= 'font-weight:' . $body_font['variant'] . ';';
				}
				// Body font size
				if ( $body_fontsize ) {
					$css .= 'font-size:' . esc_attr( $body_fontsize ) . 'px;';
				}
				$css .= '}';
				$css .= 'body
				{
					font-family: "' . $body_font['family'] . '";
				}';
			}
		}
		
		/* Main color */
		$main_color      = lebe_get_option( 'lebe_main_color', '#e5b72d' );
		$body_text_color = trim( lebe_get_option( 'lebe_body_text_color', '' ) );
		$body_text_color = str_replace( "#", '', $body_text_color );
		$main_color      = str_replace( "#", '', $main_color );
		$main_color      = "#" . $main_color;
        $body_text_color      = "#" . $body_text_color;

		$css .= '
            a:hover, a:focus, a:active {
                color: ' . esc_attr( $main_color ) . ';
            }
            q {
                border-left: 3px solid ' . esc_attr( $main_color ) . ';
            }
            blockquote p::before {
                border-left: 3px solid ' . esc_attr( $main_color ) . ';
            }
            .widget_rss .rss-date {
                color: ' . esc_attr( $main_color ) . ';
            }
            .style-07 .lebe-demo.style-01 .demo-button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .menu-social .social-list li a:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .style-07 .horizon-menu .main-navigation .main-menu .menu-item:hover > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .horizon-menu .main-navigation .main-menu > .menu-item .submenu li.active > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .horizon-menu .main-navigation .main-menu > .menu-item .submenu li > a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .horizon-menu .main-navigation .main-menu .menu-item:hover .toggle-submenu::before {
                color: ' . esc_attr( $main_color ) . ' !important;
            }
            .box-mobile-menu .back-menu:hover,
            .box-mobile-menu .close-menu:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .box-mobile-menu .main-menu .menu-item.active > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .box-mobile-menu .main-menu .menu-item:hover > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .box-mobile-menu .main-menu .menu-item:hover > .toggle-submenu::before {
                color: ' . esc_attr( $main_color ) . ';
            }
            .mobile-navigation:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .menu-btn-icon:hover span {
                background-color: ' . esc_attr( $main_color ) . ' !important;
            }
            .single-product-mobile .product-grid .product-info .price {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-content-single-product-mobile .product-mobile-layout .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs li img.flex-active {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            div.lebe-content-single-product-mobile .summary .yith-wcwl-add-to-wishlist {
                color: ' . esc_attr( $main_color ) . ';
            }
            .woocommerce-cart-form-mobile .actions .actions-btn .shopping:hover {
                background-color: ' . esc_attr( $main_color ) . ';
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .close-vertical-menu:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .vertical-menu .main-navigation .main-menu > .menu-item:hover > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header-search-box > .icons:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .instant-search-close:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .instant-search-modal .product-cats label span::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .instant-search-modal .product-cats label span:hover,
            .instant-search-modal .product-cats label.selected span {
                color: ' . esc_attr( $main_color ) . ';
            }
            .search-view:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::after {
                border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
            }
            .currency-language .dropdown > a:hover::after {
                border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
            }
            .currency-language .dropdown > a:hover::before {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .currency-language .dropdown .active a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header .lebe-minicart:hover .mini-cart-icon {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header .lebe-minicart .mini-cart-icon .minicart-number {
                background: ' . esc_attr( $main_color ) . ';
            }
            .header .minicart-content-inner .close-minicart:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header .minicart-items .product-cart .product-remove .remove:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header .minicart-content-inner .actions .button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header .to-cart:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .header-type-transparent-white .horizon-menu .main-navigation .main-menu .menu-item:hover > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header-type-transparent-white .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before,
            .header-type-transparent-white .currency-language .dropdown > a:hover::before {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .header-type-transparent-dark .currency-language .wcml-dropdown-click a:hover,
            .header-type-transparent-dark .currency-language .dropdown a:hover,
            .header-type-transparent-dark .block-account a:hover,
            .header-type-transparent-dark .header-search-box > .icons:hover,
            .header-type-transparent-dark .lebe-minicart .mini-cart-icon:hover,
            .header-type-transparent-dark .horizon-menu .main-navigation .main-menu .menu-item:hover > a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .header-type-transparent-dark .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::after,
            .header-type-transparent-dark .currency-language .dropdown > a:hover::after {
                border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
            }
            .header-type-transparent-dark .currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before,
            .header-type-transparent-dark .currency-language .dropdown > a:hover::before {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            h5.vc_custom_heading span {
                color: ' . esc_attr( $main_color ) . ';
            }
            .banner-page .content-banner .breadcrumb-trail .trail-items .trail-item a:hover span {
                color: ' . esc_attr( $main_color ) . ';
            }
            .banner-page .content-banner .breadcrumb-trail .trail-items .trail-item a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .post-info .cat-post a:hover, .main-single-content:not(.single-standard) .post-info .cat-post a:hover, .main-single-content:not(.single-standard) .post-info .tag-post a:hover {
                color: ' . esc_attr( $main_color ) . ';
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .sticky .post-title a, .sticky .post-name a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .post-title a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .post-author-blog a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .post-author a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-breadcrumb .breadcrumb > li:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .single-container .header-post .cat-post a:hover,
            .single-container .header-post .tag-post a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .main-single-content .lebe-social a:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .user-socials-wrap .user-social:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            h3.related-posts-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .post-single .owl-dots .owl-dot.active span {
                border-color: ' . esc_attr( $main_color ) . ';
                background: ' . esc_attr( $main_color ) . ';
            }
            .comment_container .flex a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .comment-form .form-submit .submit:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .post-product-carousel:hover .icons,
            .social-share:hover .icons {
                color: ' . esc_attr( $main_color ) . ';
            }
            .blog-content.list .post-info .post-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .blog-content.grid .post-info .post-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .blog-modern .post-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .blog-content-standard .post-expand a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .blog-content-standard .post-date a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-ajax-load a:hover, .more-items .woo-product-loadmore:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .more-items .woo-product-loadmore.loading {
                border-color: ' . esc_attr( $main_color ) . ';
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .sidebar .widget ul li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .widget_categories ul li.cat-item a:hover,
            .widget_categories ul li.cat-item.current-cat,
            .widget_categories ul li.cat-item.current-cat a {
                color: ' . esc_attr( $main_color ) . ';
            }
            a.social:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .tagcloud a:hover {
                border-color: ' . esc_attr( $main_color ) . ';
                background: ' . esc_attr( $main_color ) . ';
            }
            .widget_shopping_cart .woocommerce-mini-cart__buttons .button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .WOOF_Widget .woof_container li .icheckbox_flat-purple.hover,
            .WOOF_Widget .woof_container li .iradio_flat-purple.hover,
            .icheckbox_flat-purple.checked,
            .iradio_flat-purple.checked {
                background: ' . esc_attr( $main_color ) . ' 0 0 !important;
                border: 1px solid ' . esc_attr( $main_color ) . ' !important;
            }
            .WOOF_Widget .woof_container .icheckbox_flat-purple.checked ~ label,
            .WOOF_Widget .woof_container .iradio_flat-purple.checked ~ label,
            .WOOF_Widget .woof_container li label.hover,
            .WOOF_Widget .woof_container li label.hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            nav.woocommerce-breadcrumb a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .toolbar-products .category-filter li a::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .toolbar-products .category-filter li.active a,
            .toolbar-products .category-filter li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            span.prdctfltr_title_selected {
                background: ' . esc_attr( $main_color ) . ';
            }
            div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label.prdctfltr_active > span::before, div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label:hover > span::before {
                background: ' . esc_attr( $main_color ) . ';
                border: 1px double ' . esc_attr( $main_color ) . ';
                color: ' . esc_attr( $main_color ) . ';
            }
            .prdctfltr_filter .prdctfltr_regular_title::before {
                border-top: 1px solid ' . esc_attr( $main_color ) . ';
            }
            div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .prdctfltr_woocommerce_filter_submit:hover, .prdctfltr_wc .prdctfltr_buttons .prdctfltr_reset span:hover, .prdctfltr_sale:hover,
            .prdctfltr_instock:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .prdctfltr-pagination-load-more:not(.prdctfltr-ignite) .button:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            div.pf_rngstyle_flat .irs-from::after, div.pf_rngstyle_flat .irs-to::after, div.pf_rngstyle_flat .irs-single::after {
                border-top-color: ' . esc_attr( $main_color ) . ';
            }
            div.pf_rngstyle_flat .irs-from, div.pf_rngstyle_flat .irs-to, div.pf_rngstyle_flat .irs-single {
                background: ' . esc_attr( $main_color ) . ';
            }
            div.pf_rngstyle_flat .irs-bar {
                background: ' . esc_attr( $main_color ) . ';
            }
            div.pf_rngstyle_flat .irs-bar::after {
                background: ' . esc_attr( $main_color ) . ';
            }
            div.pf_rngstyle_flat .irs-bar::before {
                background: ' . esc_attr( $main_color ) . ';
            }
            .toolbar-products-mobile .cat-item.active, .toolbar-products-mobile .cat-item.active a,
            .real-mobile-toolbar.toolbar-products-shortcode .cat-item.active, .real-mobile-toolbar.toolbar-products-shortcode .cat-item.active a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .enable-shop-page-mobile .shop-page a.products-size.products-list.active {
                color: ' . esc_attr( $main_color ) . ';
            }
            .enable-shop-page-mobile span.prdctfltr_title_selected {
                background: ' . esc_attr( $main_color ) . ';
            }
            .enable-shop-page-mobile .shop-page .product-inner .price {
                color: ' . esc_attr( $main_color ) . ';
            }
            .enable-shop-page-mobile .woocommerce-page-header ul .line-hover a:hover,
            .enable-shop-page-mobile .woocommerce-page-header ul .line-hover.active a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .scrollbar-macosx > .scroll-element.scroll-y .scroll-bar {
                background: ' . esc_attr( $main_color ) . ';
            }
            .woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs .slick-arrow {
                color: ' . esc_attr( $main_color ) . ';
            }
            .summary .woocommerce-product-rating .woocommerce-review-link:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .summary .price {
                color: ' . esc_attr( $main_color ) . ';
            }
            .reset_variations:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            div button.close {
                color: ' . esc_attr( $main_color ) . ';
            }
            .summary .cart .single_add_to_cart_button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .product_meta a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .social-share-product {
                background: ' . esc_attr( $main_color ) . ';
            }
            .sticky_info_single_product button.lebe-single-add-to-cart-btn.btn.button {
                background: ' . esc_attr( $main_color ) . ';
            }
            .gallery_detail .slick-dots li.slick-active button {
                background: ' . esc_attr( $main_color ) . ';
            }
            .gallery_detail .slick-dots li button:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .big_images .slick-dots li button::before {
                border: 2px solid ' . esc_attr( $main_color ) . ';
            }
            .wc-tabs li a::before {
                border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
            }
            p.stars:hover a:before,
            p.stars.selected:not(:hover) a:before {
                color: ' . esc_attr( $main_color ) . ';
            }
            .product-grid-title::before {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .total-price-html {
                color: ' . esc_attr( $main_color ) . ';
            }
            .total-price-html {
                color: ' . esc_attr( $main_color ) . ';
            }
            div.famibt-wrap .famibt-item .famibt-price {
                color: ' . esc_attr( $main_color ) . ';
            }
            .famibt-wrap ins {
                color: ' . esc_attr( $main_color ) . ';
            }
            .famibt-messages-wrap a.button.wc-forward:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .return-to-shop .button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            body .woocommerce table.shop_table tr td.product-remove a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            body .woocommerce table.shop_table .product-add-to-cart .add_to_cart:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .actions-btn .shopping:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .wc-proceed-to-checkout .checkout-button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .owl-carousel .owl-dots .owl-dot.active span {
                background-color: ' . esc_attr( $main_color ) . ';
                
            }
            .owl-carousel.nav-center .owl-nav > div:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .track_order .form-tracking .button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-01 .newsletter-form-wrap.processing button::before {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-01 .newsletter-form-wrap button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-02 .newsletter-form-wrap button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-03 .newsletter-form-wrap button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-testimonial.style-03 .name a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-01 .blog-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-01 .blog-readmore:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-01 .blog-readmore::before,
            .lebe-blog.style-03 .blog-readmore::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-01 .blog-heading::before {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-02 .blog-info .blog-title::after {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-02 .blog-readmore:hover {
                border: 2px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-02 .blog-readmore::before {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-03 .blog-title::after {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-blog.style-03 .blog-readmore:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-team.style-01 .thumb:before {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-categories.style-02 .category-name a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-instagram-sc.style-02 .desc {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-instagram-sc.style-02 .desc::before {
                border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
            }
            .product-item.style-01 .product-thumb .vertical_thumnail .slick-current img {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-dealproduct .product-info .price ins {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-instagramshopwrap .slick-dots li.slick-active {
                background-color: ' . esc_attr( $main_color ) . ';
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-products .view-products:hover {
                background-color: ' . esc_attr( $main_color ) . ';
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .contact-form-container .wpcf7-submit:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .product-grid .yith-wcqv-button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .yith-wcqv-button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            #yith-quick-view-close:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            #yith-quick-view-content .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs > li img.flex-active {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .product-list .yith-wcwl-add-to-wishlist:hover {
                border-color: ' . esc_attr( $main_color ) . ';
                background: ' . esc_attr( $main_color ) . ';
            }
            .product-list .product-inner .add_to_cart_button:hover,
            .product-list .product-inner .product_type_variable:hover,
            .product-list .product-inner .product_type_grouped:hover,
            .product-list .product-inner .product_type_simple:hover,
            .product-list .product-inner .product_type_external:hover {
                border-color: ' . esc_attr( $main_color ) . ';
                background: ' . esc_attr( $main_color ) . ';
            }
            .product-list .yith-wcqv-button:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .product-list .product-inner .added_to_cart:hover {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .product-grid .product-item.style-4 .yith-wcwl-add-to-wishlist:hover,
            .product-grid .product-item.style-4 .add-to-cart:hover,
            .product-grid .product-item.style-4 a.yith-wcqv-button:hover,
            .product-grid .style-4 .product-inner .add_to_cart_button:hover,
            .product-grid .style-4 .product-inner .added_to_cart:hover,
            .product-grid .style-4 .product-inner .product_type_variable:hover,
            .product-grid .style-4 .product-inner .product_type_simple:hover,
            .product-grid .style-4 .product-inner .product_type_external:hover,
            .product-grid .style-4 .product-inner .product_type_grouped:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            body.wpb-js-composer .vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a {
                color: ' . esc_attr( $main_color ) . ' !important;
            }
            body .vc_toggle_default.vc_toggle_active .vc_toggle_title > h4 {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-01 .banner-info .button {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-01 .banner-info .button::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-08 .button:hover,
            .lebe-banner.style-06 .bigtitle:hover a,
            .lebe-iconbox.style-07 .button:hover {
                color: ' . esc_attr( $main_color ) . ';
                
            }
            .lebe-banner.style-08 .button:hover::before,
            .lebe-iconbox.style-07 .button:hover::before {
                border-color: ' . esc_attr( $main_color ) . ';
                
            }
            .lebe-banner.style-03 .button {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-03 .button::before {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-04 .bigtitle::before {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-04 .bigtitle:hover::before {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-13 .button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-banner.style-15 .button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-tabs .tab-head .tab-link li a::before {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .product-info.equal-elem .product-title a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-iconbox.style-03 .icon {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-iconbox.style-06 .button:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-iconbox.style-06 .button:hover::before {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-title.style1 .block-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-title.style1.light.line-red .block-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-title.style4 .block-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-title.style5 .block-title::before {
                border: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-title.style8 .block-title::before {
                border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-06 .submit-newsletter:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-05.light .submit-newsletter {
                background: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-05.light .submit-newsletter:hover,
            .lebe-newsletter.style-04 .submit-newsletter:hover,
            .lebe-newsletter.style-09 .submit-newsletter:hover,
            .lebe-newsletter.style-11 .submit-newsletter:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-04 .submit-newsletter::before,
            .lebe-newsletter.style-09 .submit-newsletter::before,
            .lebe-newsletter.style-11 .submit-newsletter::before {
                background: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-09 .submit-newsletter:hover::before {
                border: 2px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-07 .submit-newsletter:hover,
            .lebe-newsletter.style-10 .submit-newsletter:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-08.light .submit-newsletter:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-09 .submit-newsletter:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-11 .submit-newsletter:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-newsletter.style-11 .newsletter-title::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-button.style-01 .button:hover {
                background: ' . esc_attr( $main_color ) . ';
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-button.style-02 .button:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .lebe-custommenu.style-01 .widgettitle::before {
                border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-custommenu.style-01.light .menu li a:hover,
            .lebe-custommenu.style-01 .menu li a:hover,
            .footer.style-10 .lebe-custommenu.style-01.light ul li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-custommenu.style-02 .menu li:hover a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-custommenu.style-03 ul li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-custommenu ul li:hover a,
            .lebe-custommenu.style-05.light ul li:hover a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-socials .social-item::before {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-socials.style-02 .social-item:hover {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .lebe-buttonvideo.style-01 .lebe-bt-video a {
                background: ' . esc_attr( $main_color ) . ';
            }
            .lebe-buttonvideo.style-01 .lebe-bt-video a::after {
                border: 3px solid ' . esc_attr( $main_color ) . ';
            }
            .lebe-buttonvideo.style-01 .lebe-bt-video a span::after {
                border-color: transparent transparent transparent ' . esc_attr( $main_color ) . ';
            }
            .lebe-instagramshopwrap.icontype.style-02 .title-insshop {
                color: ' . esc_attr( $main_color ) . ';
            }
            .breadcrumb > li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .woocommerce-MyAccount-content input.button:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .woocommerce-MyAccount-navigation > ul li.is-active a {
                color: ' . esc_attr( $main_color ) . ';
            }
            .shop-sidebar .widget ul li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .shop-sidebar .widget ul li a:hover::before {
                background: ' . esc_attr( $main_color ) . ' none repeat scroll 0 0;
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .products-size.active svg, .products-size:hover svg {
                stroke: ' . esc_attr( $main_color ) . ';
                fill: ' . esc_attr( $main_color ) . ';
            }
            a.filter-toggle > i {
                background: ' . esc_attr( $main_color ) . ';
            }
            .price_slider_amount .button:hover, .price_slider_amount .button:focus {
                background-color: ' . esc_attr( $main_color ) . ';
                border: 2px solid ' . esc_attr( $main_color ) . ';
            }
            .validate-required label::after {
                color: ' . esc_attr( $main_color ) . ';
            }
            header .wcml_currency_switcher li li a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .error404 .lebe-searchform button:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .page-404 a.button {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            .wpb-js-composer div.vc_tta.vc_tta-accordion .vc_active .vc_tta-controls-icon-position-right .vc_tta-controls-icon::before {
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .modal-content > button.close:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            a#cn-accept-cookie:hover {
                background-color: ' . esc_attr( $main_color ) . ';
            }
            a.button.btn.lebe-button.owl-btn-link:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .product-inner .product-title a:hover {
                color: ' . esc_attr( $main_color ) . ';
            }
            .product-inner .add_to_cart_button:hover,
            .product-inner .added_to_cart:hover,
            .product-inner .product_type_variable:hover,
            .product-inner .product_type_simple:hover,
            .product-inner .product_type_external:hover,
            .product-inner .product_type_grouped:hover {
                background: ' . esc_attr( $main_color ) . ';
            }
            .yith-wcwl-add-to-wishlist:hover {
                background: ' . esc_attr( $main_color ) . ';
                border-color: ' . esc_attr( $main_color ) . ';
            }
            .widget.widget_product_categories ul li a:hover{
                color:' . esc_attr( $main_color ) . ';
            }
            @media (min-width: 992px) {
                .ziss-popup-wrap .ziss-popup-inner .ziss-popup-body.ziss-right-no-content ~ .ziss-popup-nav:hover,
                .ziss-popup-wrap .ziss-popup-inner .ziss-popup-body:not(.ziss-right-no-content) ~ .ziss-popup-nav:hover {
                    color: ' . esc_attr( $main_color ) . ';
                }
            }';
		
		if ( $body_text_color != '' && $body_text_color != '#999' && $body_text_color != '#999999' ) {
			$css .= 'body {color: ' . esc_attr( $body_text_color ) . '}';
		}
		
		/* Main Menu Break Point */
		$main_menu_res_break_point = intval( lebe_get_option( 'main_menu_res_break_point', 1199 ) );
		// 991 is default style sheet css
		if ( $main_menu_res_break_point > 0 && $main_menu_res_break_point != 991 ) {
			$css .= '@media (min-width: ' . esc_attr( $main_menu_res_break_point + 1 ) . 'px) {
						
					}';
			$css .= '@media (max-width: ' . esc_attr( $main_menu_res_break_point ) . 'px) {
						
					}';
		}
		
		return $css;
	}
}

if ( ! function_exists( 'lebe_vc_custom_css_footer' ) ) {
	function lebe_vc_custom_css_footer() {
		
		$lebe_footer_options = lebe_get_option( 'lebe_footer_options', '' );
		$page_id              = lebe_get_single_page_id();
		
		$data_option_meta = get_post_meta( $page_id, '_custom_metabox_theme_options', true );
		if ( $page_id > 0 ) {
			$enable_custom_footer = false;
			if ( isset( $data_option_meta['enable_custom_footer'] ) ) {
				$enable_custom_footer = $data_option_meta['enable_custom_footer'];
			}
			if ( $enable_custom_footer ) {
				$lebe_footer_options = $data_option_meta['lebe_metabox_footer_options'];
			}
		}
		
		$shortcodes_custom_css = get_post_meta( $lebe_footer_options, '_wpb_post_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $lebe_footer_options, '_wpb_shortcodes_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $lebe_footer_options, '_lebe_shortcode_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $lebe_footer_options, '_responsive_js_composer_shortcode_custom_css', true );
		
		return $shortcodes_custom_css;
	}
}

if ( ! function_exists( 'lebe_write_custom_js ' ) ) {
	function lebe_write_custom_js() {
		$lebe_custom_js = lebe_get_option( 'lebe_custom_js', '' );
		wp_enqueue_script( 'lebe-script', get_theme_file_uri( '/assets/js/functions.js' ), array(), '1.0' );
		wp_add_inline_script( 'lebe-script', $lebe_custom_js );
	}
}
add_action( 'wp_enqueue_scripts', 'lebe_write_custom_js' );