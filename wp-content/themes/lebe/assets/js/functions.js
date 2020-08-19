(function ($) {
    "use strict"; // Start of use strict
    $(document).on('click', function (event) {
        var _target = $(event.target).closest('.lebe-dropdown'), _parent = $('.lebe-dropdown');
        
        if (_target.length > 0) {
            _parent.not(_target).removeClass('open');
            if ($(event.target).is('[data-lebe="lebe-dropdown"]') || $(event.target).closest('[data-lebe="lebe-dropdown"]').length > 0) {
                _target.toggleClass('open');
                event.preventDefault();
            }
        } else {
            $('.lebe-dropdown').removeClass('open');
        }
    });
    
    function lebe_init_lazy_load() {
        if ($('.lazy').length > 0) {
            var _config = [];
            
            _config.beforeLoad = function (element) {
                element.parent().addClass('loading-lazy');
            };
            _config.afterLoad = function (element) {
                element.addClass('lazy-loaded').parent().removeClass('loading-lazy');
            };
            _config.effect = 'fadeIn';
            _config.enableThrottle = true;
            _config.throttle = 250;
            _config.effectTime = 1000;
            _config.threshold = 0;
            
            $('.lazy').lazy(_config);
            if ($(this).closest('.megamenu').length > 0) {
                _config.delay = 0;
            }
            
        }
    }
    
    // Animation on scroll
    function lebe_wow() {
        if (lebe_theme_frontend['animation_on_scroll'] != 'yes') {
            return;
        }
        var wow = new WOW({
            boxClass: 'lebe-wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 50,          // distance to the element when triggering the animation (default is 0)
            mobile: true,       // trigger animations on mobile devices (default is true)
            live: true,       // act on asynchronously loaded content (default is true)
            callback: function (box) {
                // the callback is fired every time an animation is started
                // the argument that is passed in is the DOM node being animated
            }, scrollContainer: null // optional scroll container selector, otherwise use window
        });
        wow.init();
    }
    
    lebe_wow();
    
    function lebe_init_popup() {
        var popup_news = $('#popup-newsletter');
        if (popup_news.length > 0) {
            if ($('body').hasClass('home')) {
                setTimeout(function () {
                    $('#popup-newsletter').modal({
                        keyboard: false
                    })
                }, 8000);
            }
        }
    }
    
    function sticky_detail_content() {
        $('.detail-content').theiaStickySidebar({
            additionalMarginTop: 125
        });
    }
    
    function sticky_scrollup() {
        if ($('.sticky_info_single_product').length) {
            
            var previousScroll = 0,
                headerOrgOffset = $('.site-header').outerHeight() + $('.site-header').outerHeight() + $('.product-top-inner').offset().top;
            
            $(window).scroll(function () {
                var currentScroll = $(this).scrollTop();
                if (currentScroll > headerOrgOffset) {
                    $('body').addClass('show-sticky_info_single');
                } else {
                    $('body').removeClass('show-sticky_info_single');
                }
                previousScroll = currentScroll;
            });
        }
        if ($('.header-pos-absolute').length) {
            $('.header-pos-absolute').each(function () {
                var headerOffset = $('.header-position').outerHeight() + $('.header-topbar').outerHeight();
                var contentBannerOffset = headerOffset - $('.header-position').outerHeight() / 2;
                if ($('.page-wrapper').hasClass('single-layout1')) {
                    $(this).parent().find('.single-container').css({'padding-top': headerOffset + 'px'});
                } else {
                    $(this).parent().find('.single-container').css({'padding-top': 0 + 'px'});
                }
                if ($('.product-toolbar').length) {
                    $(this).parent().find('.product-toolbar').css({'padding-top': headerOffset + 'px'});
                } else {
                    $(this).parent().find('.product-toolbar').css({'padding-top': 0 + 'px'});
                }
                if ($('.content-banner').length) {
                    $('.content-banner').css({'padding-top': headerOffset + 'px'});
                }
            });
        }
    }
    
    //My Account
    function toggle_form() {
        $('.toggle-form').on('click', function (e) {
            $(this).toggleClass('active');
            $('.block-form').toggleClass('nav-show form-show');
            e.preventDefault();
            e.stopPropagation();
        });
    }
    
    // sidebar canvas
    function lebe_sidebar_offcanvas() {
        $(document).on('click', '.btn-canvas, .sidebar-canvas-overlay', function (e) {
            $('body,html').toggleClass('sidebar-canvas-open');
            e.preventDefault();
        });
    }
    
    // minicart canvas
    function lebe_minicart_offcanvas() {
        $(document).on('click', '.mini-cart-icon,.minicart-canvas-overlay,.close-minicart', function (e) {
            $('body,html').toggleClass('minicart-canvas-open');
            e.preventDefault();
        })
    }
    
    // Close instant search
    function lebe_instant_search_close() {
        $(document).on('click', '.header-search-box .icons,.instant-search-close', function (e) {
            $('body').toggleClass('instant-search-open');
            e.preventDefault();
        });
    }
    
    //Close Vertical Menu
    function lebe_vertical_menu() {
        $(document).on('click', '.vertical-menu-btn,.close-vertical-menu,.vertical-menu-overlay', function (e) {
            $('body,html').toggleClass('vertical-menu-open');
            e.preventDefault();
        })
    }
    
    function getCookie(c_name) {
        var c_value = document.cookie;
        var c_start = c_value.indexOf(" " + c_name + "=");
        if (c_start == -1) {
            c_start = c_value.indexOf(c_name + "=");
        }
        if (c_start == -1) {
            c_value = null;
        } else {
            c_start = c_value.indexOf("=", c_start) + 1;
            var c_end = c_value.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = c_value.length;
            }
            c_value = decodeURI(c_value.substring(c_start, c_end));
        }
        return c_value;
    }
    
    function setCookie(c_name, value, exdays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = encodeURI(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = c_name + "=" + c_value;
    }
    
    function lebe_singleProduct_popup() {
        $('.lebe-bt-video a').magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            disableOn: false,
            fixedContentPos: false
        });
        $('.product-360-button a').magnificPopup({
            type: 'inline',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            disableOn: false,
            preloader: false,
            fixedContentPos: false,
            callbacks: {
                open: function () {
                    $(window).resize()
                },
            },
        });
        $('.open-popup-link').magnificPopup({
            mainClass: 'mfp-fade', removalDelay: 100, type: 'inline', callbacks: {
                beforeOpen: function () {
                    this.st.mainClass = this.st.el.attr('data-effect');
                }
            }, midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
        });
        $('.block-account .acc-popup').magnificPopup({
            mainClass: 'mfp-fade', removalDelay: 100, type: 'inline', callbacks: {
                beforeOpen: function () {
                    this.st.mainClass = this.st.el.attr('data-effect');
                }
            }, midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
        });
        
        var expireDate = getCookie("showpopup");
        var today = new Date().toUTCString();
        if (expireDate != null && expireDate > today) {
            //Do nothing!
        } else {
            setTimeout(function () {
                if ($('#popup-discount').length) {
                    $.magnificPopup.open({
                        items: {
                            src: '#popup-discount'
                        }, type: 'inline', mainClass: 'mfp-zoom-in'
                    });
                }
            }, 3000);
            //Create cookie
            setCookie("showpopup", "anything", 1);
        }
        
    }
    
    /* Main Menu */
    
    /* ---------------------------------------------
     Resize mega menu
     --------------------------------------------- */
    function lebe_resizeMegamenu() {
        var window_size = jQuery('body').innerWidth();
        window_size += lebe_get_scrollbar_width();
        if (window_size > 767) {
            if ($('#header .main-menu-wrapper').length > 0) {
                var container = $('#header .main-menu-wrapper');
                if (container != 'undefined') {
                    var container_width = 0;
                    container_width = container.innerWidth();
                    var container_offset = container.offset();
                    setTimeout(function () {
                        $('.main-menu .item-megamenu').each(function (index, element) {
                            $(element).children('.megamenu').css({'max-width': container_width + 'px'});
                            var sub_menu_width = $(element).children('.megamenu').outerWidth();
                            var item_width = $(element).outerWidth();
                            $(element).children('.megamenu').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                            var container_left = container_offset.left;
                            var container_right = (container_left + container_width);
                            var item_left = $(element).offset().left;
                            var overflow_left = (sub_menu_width / 2 > (item_left - container_left));
                            var overflow_right = ((sub_menu_width / 2 + item_left) > container_right);
                            if (overflow_left) {
                                var left = (item_left - container_left);
                                $(element).children('.megamenu').css({'left': -left + 'px'});
                            }
                            if (overflow_right && !overflow_left) {
                                var left = (item_left - container_left);
                                left = left - ( container_width - sub_menu_width );
                                $(element).children('.megamenu').css({'left': -left + 'px'});
                            }
                        })
                    }, 100);
                }
            }
        } else {
            $('.owl-carousel').each(function (index, el) {
                var config = $(this).data();
                if ($(this).is('.instagram,.product-list-owl')) {
                    config.responsive = {
                        0: {items: 2, margin: 15}, 481: {items: 2, margin: 15}
                    }
                }
            });
        }
    }
    
    function lebe_instagramshopslide() {
        var window_size = jQuery('body').innerWidth();
        window_size += lebe_get_scrollbar_width();
        if (window_size < 1200) {
            $('.lebe-instagramshopwrap.style-02 .ziss-row').not('.slick-initialized').slick({
                slidesToShow: 4, slidesToScroll: 1, arrows: false, dots: false, infinite: false, responsive: [{
                    breakpoint: 991, settings: {
                        arrows: false, slidesToShow: 3
                    }
                }, {
                    breakpoint: 480, settings: {
                        arrows: false, slidesToShow: 2
                    }
                }]
            });
        }
    }
    
    function lebe_get_scrollbar_width() {
        var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
            $outer = jQuery('<div style="width:200px; height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner = $inner[0],
            outer = $outer[0];
        jQuery('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    }
    
    // Toggle submenu mobile / responsive
    $(document).on('click', '.menu-item .toggle-submenu', function (e) {
        var $this = $(this);
        var $thisParent = $this.closest('.menu-item-has-children');
        if ($thisParent.length) {
            $thisParent.toggleClass('show-submenu').find('> .submenu').stop().slideToggle();
        }
        e.preventDefault();
        return false;
    });
    
    /*Clone Main Menu*/
    function lebe_clone_main_menu() {
        if ($('#header .clone-main-menu').length > 0) {
            var _winw = $(window).innerWidth();
            var _clone_menu = $('#header .clone-main-menu');
            var _target = $('#box-mobile-menu.clone-main-menu');
            var main_menu_break_point = lebe_theme_frontend['main_menu_break_point'];
            if (_winw <= main_menu_break_point) {
                if (_clone_menu.length > 0 && _target.length == 0) {
                    if ($("#box-mobile-menu .box-inner").hasClass('menu-cloned')) {
                        //nope
                    } else {
                        _clone_menu.clone().appendTo("#box-mobile-menu .box-inner");
                        //Replace Id ul, menu item
                        $('.box-mobile-menu .menu-double-menu').each(function () {
                            if ($(this).attr('id') != '') {
                                var cur_id = $(this).attr('id');
                                $(this).attr('id', cur_id + '-clone');
                            }
                        });
                        $('.box-mobile-menu li.menu-item').each(function () {
                            if ($(this).attr('id') != '') {
                                var cur_id = $(this).attr('id');
                                $(this).attr('id', cur_id + '-clone');
                            }
                        });
                    }
                    $("#box-mobile-menu .box-inner").addClass('menu-cloned');
                }
            }
        }
    }
    
    function lebe_clone_append_category() {
        if ($('.product-category').length > 0) {
            $('.main-product').prepend('<ul class="list-cate"></ul>')
            $('.product-category').detach().prependTo('.list-cate');
            if($('.main-product .prdctfltr_wc').length > 0){
        		$('.list-cate').insertAfter('.main-product .prdctfltr_wc');
        	}
        }
    }
    
    /* Lebe Carousel  */
    function lebe_init_carousel() {
        $('.owl-carousel').each(function (index, el) {
            var config = $(this).data();
            if ($(this).is('.category-filter-mobile')) {
                config['autoWidth'] = true;
            }
            if ($('body').is('.rtl')) {
                config['rtl'] = true;
            }
            config.navText = ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'];
            var animateOut = $(this).data('animateout');
            var animateIn = $(this).data('animatein');
            var slidespeed = $(this).data('slidespeed');
            
            if (typeof animateOut != 'undefined') {
                config.animateOut = animateOut;
            }
            if (typeof animateIn != 'undefined') {
                config.animateIn = animateIn;
            }
            if (typeof (slidespeed) != 'undefined') {
                config.smartSpeed = slidespeed;
            }
            
            var owl = $(this);
            owl.on('initialized.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                    
                }, 100);
            })
            owl.on('refreshed.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                    
                }, 100);
            })
            owl.on('change.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });
                    
                }, 100);
                
                
            });
            owl.on('translated.owl.carousel', function (event) {
                // Fami lazy load for owl
                if ($('.owl-item .lazy').length) {
                    $('.owl-item .lazy').lazy({
                        bind: "event"
                    });
                }
            });
            owl.owlCarousel(config);
            
        });
    }
    
    function thumbnail_product() {
        $('.default:not(.product-mobile-layout) .flex-control-thumbs').not('.slick-initialized').slick({
            slidesToShow: 5,
            infinite: false,
            slidesToScroll: 1,
            prevArrow: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            nextArrow: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
            responsive: [{
                breakpoint: 1024, settings: {
                    slidesToShow: 5, slidesToScroll: 5, infinite: true, dots: true
                }
            }, {
                breakpoint: 600, settings: {
                    slidesToShow: 4, slidesToScroll: 4
                }
            }, {
                breakpoint: 480, settings: {
                    slidesToShow: 3, slidesToScroll: 3
                }
            }]
        });
        $('.vertical_thumnail:not(.product-mobile-layout) .flex-control-thumbs').each(function () {
            if ($(this).not('.slick-initialized').children().length == 0) {
                return;
            }
            $(this).slick({
                vertical: true,
                verticalSwiping: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: false,
                prevArrow: '<i class="fa fa-angle-up" aria-hidden="true"></i>',
                nextArrow: '<i class="fa fa-angle-down" aria-hidden="true"></i>',
                responsive: [{
                    breakpoint: 1024, settings: {
                        vertical: false,
                        verticalSwiping: false,
                        slidesToShow: 4,
                        prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                        nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                    }
                }, {
                    breakpoint: 600, settings: {
                        vertical: false,
                        verticalSwiping: false,
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                        nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                    }
                }, {
                    breakpoint: 320, settings: {
                        vertical: false,
                        verticalSwiping: false,
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                        nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                    }
                }]
            });
        });
        $('.gallery_detail:not(.product-mobile-layout) .single-left .lebe-center-mode').not('.slick-initialized').slick({
            centerMode: true, centerPadding: '27.8947368%', slidesToShow: 1, arrows: false, dots: true, responsive: [{
                breakpoint: 767, settings: {
                    centerMode: false,
                }
            }]
        });
        
    }
    
    function product_gallery() {
        $('.product-gallery-slick').not('.slick-initialized').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            infinite: false,
            asNavFor: '.vertical_thumnail'
        });
        $('.vertical_thumnail').not('.slick-initialized').slick({
            focusOnSelect: true,
            vertical: true,
            verticalSwiping: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            infinite: false,
            asNavFor: '.product-gallery-slick',
            prevArrow: '<i class="fa fa-angle-up" aria-hidden="true"></i>',
            nextArrow: '<i class="fa fa-angle-down" aria-hidden="true"></i>',
            responsive: [{
                breakpoint: 1024, settings: {
                    slidesToShow: 3,
                    vertical: false,
                    verticalSwiping: false,
                    prevArrow: '<i class="fa fa-caret-left" aria-hidden="true"></i>',
                    nextArrow: '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                }
            }]
        });
    }
    
    function instagram_shop_slick() {
        $('.lebe-instagramshopwrap.style-04 .ziss-row').not('.slick-initialized').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            dots: true,
            infinite: false,
            prevArrow: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            nextArrow: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
            responsive: [{
                breakpoint: 1199, settings: {
                    slidesToShow: 3
                }
            }, {
                breakpoint: 991, settings: {
                    slidesToShow: 2
                }
            }]
        });
    }
    
    function number_dots_rev() {
        $('.dione').each(function () {
            var n = $(this).find('.tp-bullet').length;
            var $dots = $(this).find('.tp-bullet');
            
            for (var i = 0; i <= n; i++) {
                var _number;
                if (i < 9) {
                    _number = '0' + (i + 1);
                } else {
                    _number = i + 1;
                }
                $dots.eq(i).html('<span class="number">' + _number + '</span>');
            }
        });
    }
    
    /* ---------------------------------------------
     COUNTDOWN
     --------------------------------------------- */
    function lebe_countdown() {
        $('.lebe-countdown').each(function () {
            var $el = $(this), $timers = $el.find('.timers'), output = '';
            $timers.countdown($timers.data('date'), function (event) {
                output = '';
                var day = event.strftime('%D');
                for (var i = 0; i < day.length; i++) {
                    output += '<span>' + day[i] + '</span>';
                }
                $timers.find('.day').html(output);
                
                output = '';
                var hour = event.strftime('%H');
                for (i = 0; i < hour.length; i++) {
                    output += '<span>' + hour[i] + '</span>';
                }
                $timers.find('.hour').html(output);
                
                output = '';
                var minu = event.strftime('%M');
                for (i = 0; i < minu.length; i++) {
                    output += '<span>' + minu[i] + '</span>';
                }
                $(this).find('.min').html(output);
                
                output = '';
                var secs = event.strftime('%S');
                for (i = 0; i < secs.length; i++) {
                    output += '<span>' + secs[i] + '</span>';
                }
                $timers.find('.secs').html(output);
            });
        });
    };
    
    /* ---------------------------------------------
     Woocommerce Quantily
     --------------------------------------------- */
    function lebe_woo_quantily() {
        $('body').on('click', '.quantity .quantity-plus', function () {
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.data('min')),
                max_qty = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty = val_qty + step_qty;
            if (max_qty && val_qty > max_qty) {
                val_qty = max_qty;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });
        
        $('body').on('click', '.quantity .quantity-minus', function () {
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val()),
                min_qty = parseInt(obj_qty.data('min')),
                max_qty = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty = val_qty - step_qty;
            if (min_qty && val_qty < min_qty) {
                val_qty = min_qty;
            }
            if (!min_qty && val_qty < 0) {
                val_qty = 0;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });
    }
    
    /* ---------------------------------------------
     TAB EFFECT
     --------------------------------------------- */
    function lebe_tab_fade_effect() {
        // effect click
        $(document).on('click', '.lebe-tabs .tab-link a', function () {
            var tab_id = $(this).attr('href');
            var tab_animated = $(this).data('animate');
            
            tab_animated = ( tab_animated == undefined || tab_animated == "" ) ? '' : tab_animated;
            if (tab_animated == "") {
                return false;
            }
            
            $(tab_id).find('.product-list-owl .owl-item.active, .product-item').each(function (i) {
                
                var t = $(this);
                var style = $(this).attr("style");
                style = ( style == undefined ) ? '' : style;
                var delay = i * 400;
                t.attr("style", style + ";-webkit-animation-delay:" + delay + "ms;" + "-moz-animation-delay:" + delay + "ms;" + "-o-animation-delay:" + delay + "ms;" + "animation-delay:" + delay + "ms;").addClass(tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    t.removeClass(tab_animated + ' animated');
                    t.attr("style", style);
                });
            })
        })
    }
    
    /* ---------------------------------------------
     Ajax Tab
     --------------------------------------------- */
    $(document).on('click', '[data-ajax="1"]', function () {
        better_equal_elems();
        if (!$(this).hasClass('loaded')) {
            var id = $(this).data('id');
            var tab_id = $(this).attr('href');
            var section_id = tab_id.replace('#', '');
            var t = $(this);
            
            $(tab_id).closest('.tab-container').append('<div class="cssload-wapper" style="min-height: 300px;position: static"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
            $(tab_id).closest('.panel-collapse').append('<div class="cssload-wapper" style="min-height: 300px;position: static"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
            $.ajax({
                type: 'POST', data: {
                    action: 'lebe_ajax_tabs', security: lebe_theme_frontend.security, id: id, section_id: section_id,
                }, url: lebe_theme_frontend.ajaxurl, success: function (response) {
                    $(tab_id).closest('.tab-container').find('.cssload-wapper').remove();
                    $(tab_id).closest('.panel-collapse').find('.cssload-wapper').remove();
                    $(tab_id).html($(response['html']).find('.vc_tta-panel-body').html());
                    t.addClass('loaded');
                }, complete: function () {
                    lebe_product_loadmore();
                    better_equal_elems();
                    lebe_tab_fade_effect();
                }
            });
        }
    });
    
    function lebe_google_maps() {
        if ($('.lebe-google-maps').length <= 0) {
            return;
        }
        $('.lebe-google-maps').each(function () {
            var $this = $(this),
                $id = $this.attr('id'),
                $title_maps = $this.attr('data-title_maps'),
                $phone = $this.attr('data-phone'),
                $email = $this.attr('data-email'),
                $zoom = parseInt($this.attr('data-zoom')),
                $latitude = $this.data('latitude'),
                $longitude = $this.data('longitude'),
                $address = $this.attr('data-address'),
                $map_type = $this.attr('data-map-type'),
                $pin_icon = $this.attr('data-pin-icon'),
                $modify_coloring = true,
                $saturation = $this.data('saturation'),
                $hue = $this.data('hue'),
                $map_style = $this.data('map-style'),
                $styles;
            
            if ($modify_coloring == true) {
                var $styles = [{
                    stylers: [{hue: $hue}, {invert_lightness: false}, {saturation: $saturation}, {lightness: 1}, {
                        featureType: "landscape.man_made", stylers: [{
                            visibility: "on"
                        }]
                    }]
                }, {
                    featureType: 'water', elementType: 'geometry', stylers: [{color: '#46bcec'}]
                }];
            }
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: $zoom,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                draggable: true,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId[$map_type],
                styles: $styles
            };
            
            map = new google.maps.Map(document.getElementById($id), mapOptions);
            map.setTilt(45);
            
            // Multiple Markers
            var markers = [];
            var infoWindowContent = [];
            
            if ($latitude != '' && $longitude != '') {
                markers[0] = [$address, $latitude, $longitude];
                infoWindowContent[0] = [$address];
            }
            
            var infoWindow = new google.maps.InfoWindow(), marker, i;
            
            for (i = 0; i < markers.length; i++) {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position, map: map, title: markers[i][0], icon: $pin_icon
                });
                if ($map_style == '1') {
                    
                    if (infoWindowContent[i][0].length > 1) {
                        infoWindow.setContent('<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="lebe-map-info">' + '<h4 class="map-title">' + $title_maps + '</h4>' + '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' + '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' + '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' + '</div>');
                    }
                    
                    infoWindow.open(map, marker);
                    
                }
                if ($map_style == '2') {
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            if (infoWindowContent[i][0].length > 1) {
                                infoWindow.setContent('<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="lebe-map-info">' + '<h4 class="map-title">' + $title_maps + '</h4>' + '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' + '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' + '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' + '</div>');
                            }
                            
                            infoWindow.open(map, marker);
                        }
                    })(marker, i));
                }
                
                map.fitBounds(bounds);
            }
            
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
                this.setZoom($zoom);
                google.maps.event.removeListener(boundsListener);
            });
        });
    }
    
    //EQUAL ELEM
    function better_equal_elems() {
        setTimeout(function () {
            $('.equal-container.better-height').each(function () {
                var $this = $(this);
                if ($this.find('.equal-elem').length) {
                    $this.find('.equal-elem').css({
                        'height': 'auto'
                    });
                    var elem_height = 0;
                    $this.find('.equal-elem').each(function () {
                        var this_elem_h = $(this).height();
                        if (elem_height < this_elem_h) {
                            elem_height = this_elem_h;
                        }
                    });
                    $this.find('.equal-elem').height(elem_height);
                }
            });
        }, 1000);
    }
    
    
    /* update wishlist count */
    function update_wishlist_count() {
        var lebe_update_wishlist_count = function () {
            $.ajax({
                beforeSend: function () {
                
                }, complete: function () {
                
                }, data: {
                    action: 'lebe_update_wishlist_count'
                }, success: function (data) {
                    //do something
                    $('.block-wishlist .count').text(data);
                },
                
                url: yith_wcwl_l10n.ajax_url
            });
        };
        
        $('body').on('added_to_wishlist removed_from_wishlist', lebe_update_wishlist_count);
    }
    
    /* Toggle filter */
    $(document).on('click', '.filter-toggle', function (e) {
        if ($('.prdctfltr_woocommerce_filter').length) {
            $('.prdctfltr_woocommerce_filter').trigger('click');
        }
        $(this).toggleClass('active');
        e.preventDefault();
    });
    
    /* ---------------------------------------------
     AJAX LOADMORE
     -----------------------------------------------*/
    
    var initAjaxLoad = function () {
        var button = $('.lebe-ajax-load:not(.already-init)');
        
        button.each(function (i, val) {
            $(this).addClass('already-init');
            var _option = $(this).data('load-more');
            var _mode = $(this).data('mode');
            
            if (_option !== undefined) {
                var page = _option.page,
                    container = _option.container,
                    layout = _option.layout,
                    isLoading = false,
                    anchor = $(val).find('a'),
                    next = $(anchor).attr('href'),
                    cur_page = 2;
                
                if (layout == 'loadmore') {
                    $(val).on('click', 'a', function (e) {
                        e.preventDefault();
                        cur_page = parseInt($(val).attr('data-cur_page'));
                        var total_page = parseInt($(val).attr('data-total_page'));
                        anchor = $(val).find('a');
                        next = $(anchor).attr('href');
                        if (total_page <= cur_page) {
                            anchor.text(lebe_theme_frontend['text']['no_more_product']).addClass('disabled lebe-loadmore-disabled');
                            return false;
                        }
                        
                        $(anchor).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                        
                        getData();
                    });
                } else {
                    var animationFrame = function () {
                        cur_page = parseInt($(val).attr('data-cur_page'));
                        var total_page = parseInt($(val).attr('data-total_page'));
                        anchor = $(val).find('a');
                        next = $(anchor).attr('href');
                        if (total_page <= cur_page) {
                            anchor.text(lebe_theme_frontend['text']['no_more_product']);
                            return false;
                        }
                        
                        var bottomOffset = $('.' + container).offset().top + $('.' + container).height() - $(window).scrollTop();
                        if (bottomOffset < window.innerHeight && bottomOffset > 0 && !isLoading) {
                            if (!next) return;
                            isLoading = true;
                            $(anchor).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                            
                            getData();
                        }
                    }
                    
                    var scrollHandler = function () {
                        requestAnimationFrame(animationFrame);
                    };
                    
                    $(window).scroll(scrollHandler);
                }
                
                var getData = function () {
                    $.get(next + '', function (data) {
                        var content = $('.' + container, data).wrapInner('').html(),
                            newElement = $('.' + container, data).find('.product-item');
                        
                        $(content).imagesLoaded(function () {
                            next = $(anchor, data).attr('href');
                            $('.' + container).append(newElement);
                            lebe_init_products_size();
                            lebe_init_lazy_load();
                        });
                        lebe_init_products_size();
                        lebe_init_lazy_load();
                        
                        $(anchor).text(lebe_theme_frontend['text']['load_more']); // lebe_theme_frontend
                        
                        if (page > cur_page) {
                            cur_page++;
                            if (ZIGG_Data_Js != undefined && ZIGG_Data_Js['permalink'] == 'plain') {
                                var link = next.replace(/paged=+[0-9]+/gi, 'paged=' + cur_page);
                            } else {
                                var link = next.replace(/page\/+[0-9]+\//gi, 'page/' + cur_page + '/');
                            }
                            
                            if (cur_page >= page) {
                                $(anchor).text(lebe_theme_frontend['text']['no_more_product']);
                                $(anchor).addClass('disabled lebe-loadmore-disabled');
                            }
                            
                            $(anchor).attr('href', link);
                        } else {
                            $(anchor).text(lebe_theme_frontend['text']['no_more_product']);
                            $(anchor).addClass('disabled lebe-loadmore-disabled');
                        }
                        isLoading = false;
                        
                        // cur_page++;
                        $(val).attr('data-cur_page', cur_page);
                    });
                }
            }
        });
    }
    
    function lebe_get_url_var(key, url) {
        var result = new RegExp(key + "=([^&]*)", "i").exec(url);
        return result && result[1] || "";
    }
    
    function lebe_remove_product() {
        $(document).on('click', '.minicart-items .product-cart .product-remove a.remove', function (e) {
            var $this = $(this);
            var thisItem = $this.closest('.product-cart');
            var remove_url = $this.attr('href');
            var product_id = $this.attr('data-product_id');
            if (thisItem.is('.loading')) {
                return false;
            }
            
            if ($.trim(remove_url) !== '' && $.trim(remove_url) !== '#') {
                
                thisItem.addClass('loading');
                
                var nonce = lebe_get_url_var('_wpnonce', remove_url);
                var cart_item_key = lebe_get_url_var('remove_item', remove_url);
                
                var data = {
                    action: 'lebe_remove_cart_item_via_ajax',
                    product_id: product_id,
                    cart_item_key: cart_item_key,
                    nonce: nonce
                };
                
                $.post(lebe_theme_frontend['ajaxurl'], data, function (response) {
                    
                    if (response['err'] != 'yes') {
                        $('.lebe-minicart').html(response['mini_cart_html']);
                    }
                    thisItem.removeClass('loading');
                    
                });
                
                e.preventDefault();
            }
            
            return false;
            
        });
        
    }
    
    // Single product: Title, Price And Stars Outside Sumary
    function lebe_update_single_product_title_price_stars_outside_summary() {
        $('.single-product .summary.title-price-stars-outside-summary').each(function () {
            var $this = $(this);
            var thisProduct = $this.closest('.product');
            if (thisProduct.is('.product-mobile-layout')) {
                return;
            }
            var stars_html = $this.find('.woocommerce-product-rating').html();
            var title_html = $this.find('.product_title').html();
            var price_html = $this.find('.price').html();
            var outside_html = '';
            if ($.trim(stars_html) != '') {
                outside_html += '<div class="woocommerce-product-rating woocommerce-product-rating-outside">' + stars_html + '</div>';
            }
            if ($.trim(title_html) != '') {
                outside_html += '<h2 class="product_title entry-title product_title-outside">' + title_html + '</h2>';
            }
            if ($.trim(price_html) != '') {
                outside_html += '<p class="price price-outside">' + price_html + '</p>';
            }
            if ($.trim(outside_html) != '') {
                outside_html = '<div class="outside-title-price-stars-wrap">' + outside_html + '</div>';
            }
            thisProduct.find('.outside-title-price-stars-wrap').remove();
            thisProduct.find('.main-content-product').append(outside_html);
        });
    }
    
    lebe_update_single_product_title_price_stars_outside_summary();
    
    // Single product mobile more detail
    $(document).on('click', '.product-toggle-more-detail', function (e) {
        var thisSummary = $(this).closest('.summary');
        thisSummary.find('.product-mobile-more-detail-wrap').toggleClass('active').slideToggle();
        if (thisSummary.find('.product-mobile-more-detail-wrap').is('.active')) {
            $(this).addClass('active').text(lebe_theme_frontend['text']['less_detail']);
        } else {
            $(this).removeClass('active').text(lebe_theme_frontend['text']['more_detail']);
        }
        e.preventDefault();
    });
    
    // Single product mobile structure
    function lebe_single_product_mobile_structure() {
        $('.product-mobile-layout').each(function (e) {
            var $this = $(this);
            var thisSummary = $this.find('.summary');
            if (thisSummary.is('.moved-some-elems')) {
                return;
            }
            
            // Star rating
            if (!thisSummary.is('.moved-star-rating')) {
                if (thisSummary.find('.woocommerce-product-rating').length) {
                    var star_rating_html = thisSummary.find('.woocommerce-product-rating').html();
                    thisSummary.find('.woocommerce-product-rating').remove();
                    thisSummary.find('.product_title').after('<div class="woocommerce-product-rating woocommerce-product-rating-clone">' + star_rating_html + '</div>');
                }
                thisSummary.addClass('moved-star-rating');
            }
            thisSummary.addClass('moved-some-elems');
        });
    }
    
    lebe_single_product_mobile_structure();
    
    $(document).on('change', '.quantity-clone .input-qty', function (e) {
        var $this = $(this);
        var thisSummary = $this.closest('.summary');
        var this_val = $this.val();
        thisSummary.find('.cart .quantity .input-qty').val(this_val).trigger('change');
    });
    
    // Single product mobile add to cart fixed button
    $(document).on('click', '.add-to-cart-fixed-btn,.lebe-single-add-to-cart-deal', function (e) {
        if ($('.product .summary button.single_add_to_cart_button').length) {
            $('.product .summary button.single_add_to_cart_button').trigger('click');
        }
        e.preventDefault();
    });
    // Single product mobile add to cart fixed button
    $(document).on('click', '.lebe-single-add-to-cart-fixed-top', function (e) {
        if ($('.product .summary button.single_add_to_cart_button').length) {
            $('.product .summary button.single_add_to_cart_button').trigger('click');
        }
        e.preventDefault();
    });
    $(document).on('wc_variation_form', '.variations_form', function (e) {
        $(this).addClass('fami-active-wc_variation_form');
    });
    
    // Single product attributes
    function lebe_variations_custom() {
        $('.product-item .variations_form:not(.moved-reset_variations)').each(function () {
            $(this).find('.reset_variations').appendTo($(this));
            $(this).addClass('moved-reset_variations');
        });
        
        $('.variations_form').find('.data-val').html('');
        $('.variations_form select, .fami_variations_form select').each(function () {
            var _this = $(this);
            _this.find('option').each(function () {
                var _ID = $(this).parent().data('id'),
                    _data = $(this).data(_ID),
                    _value = $(this).attr('value'),
                    _name = $(this).data('name'),
                    _data_type = $(this).data('type'),
                    _itemclass = _data_type;
                
                if ($(this).is(':selected')) {
                    _itemclass += ' active';
                }
                if (_value !== '') {
                    if (_data_type == 'color' || _data_type == 'photo') {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" style="background: ' + _data + ';background-size: cover; background-repeat: no-repeat " data-value="' + _value + '"></a>');
                    } else {
                        _this.parent().find('.data-val').append('<a class="change-value ' + _itemclass + '" href="#" data-value="' + _value + '">' + _name + '</a>');
                    }
                }
            });
        });
    }
    
    function lebe_variations_custom_ajax() {
        if ($('.products .variations_form:not(.fami-active-wc_variation_form)').length) {
            $('.products .variations_form:not(.fami-active-wc_variation_form)').each(function () {
                $(this).wc_variation_form();
            });
        }
        lebe_variations_custom();
    }
    
    
    $(document).on('click', '.variations_form .change-value', function (e) {
        var _this = $(this), _change = _this.data('value');
        
        _this.parent().parent().children('select').val(_change).trigger('change');
        _this.addClass('active').siblings().removeClass('active');
        lebe_single_product_mobile_structure();
        lebe_update_single_product_title_price_stars_outside_summary();
        if (_this.closest('.product-item').length) {
            var $thisProduct = _this.closest('.product-item');
            $thisProduct.removeAttr('srcset').removeAttr('data-o_sizes').removeAttr('data-o_srcset').removeAttr('sizes');
        }
        e.preventDefault();
    });
    
    $(document).on('woocommerce_variation_has_changed wc_variation_form', function () {
        lebe_variations_custom();
        $('.product-item').find('.images .fami-img').removeAttr('data-o_sizes').removeAttr('data-o_srcset').removeAttr('sizes').removeAttr('srcset');
    });
    
    function lebe_fix_responsive_img_issue_product_var() {
        $('.product-item .variations_form:not(.fami-fixed-res-img)').each(function () {
            var $this = $(this);
            var product_variations = $this.attr('data-product_variations');
            
            product_variations = JSON.parse(product_variations);
            for (var i = 0; i < product_variations.length; i++) {
                if (product_variations[i].image.hasOwnProperty('srcset')) {
                    product_variations[i].image.srcset = '';
                }
            }
            product_variations = JSON.stringify(product_variations);
            $this.attr('data-product_variations', product_variations);
            $this.addClass('fami-fixed-res-img');
        });
    }
    
    lebe_fix_responsive_img_issue_product_var();
    
    function lebe_add_to_cart_single() {
        /* SINGLE ADD TO CART */
        $(document).on('click', '.product:not(.product-type-external) .single_add_to_cart_button', function (e) {
            
            e.preventDefault();
            var _this = $(this);
            var _product_id = _this.val();
            var _form = _this.closest('form');
            var _form_data = _form.serialize();
            
            if (_product_id != '') {
                var _data = 'add-to-cart=' + _product_id + '&' + _form_data;
            } else {
                var _data = _form_data;
            }
            if (_this.is('.disabled') || _this.is('.wc-variation-selection-needed')) {
                return false;
            }
            $('body,html').toggleClass('minicart-canvas-open');
            $('.lebe-minicart').addClass('is-adding-to-cart');
            var atcUrl = wc_add_to_cart_params.wc_ajax_url.toString().replace('wc-ajax=%%endpoint%%', 'add-to-cart=' + _product_id + '&lebe-ajax-add-to-cart=1');
            $.ajax({
                type: 'POST',
                url: atcUrl,
                data: _data,
                dataType: 'html',
                cache: false,
                headers: {'cache-control': 'no-cache'},
                success: function () {
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document).on('added_to_cart', function () {
                        $('.lebe-minicart').removeClass('is-adding-to-cart');
                    });
                    $('.single_add_to_cart_button-clone').removeClass('loading').addClass('added');
                    $('.variable_mobile').removeClass('show-box-content');
                    $('body,html').removeClass('variable_mobile_show');
                }
            });
            
            
        });
        $(document).on('click', '.famibt-add-all-to-cart', function (e) {
            $('body,html').toggleClass('minicart-canvas-open');
            if ($('.lebe-minicart').length) {
                $('.lebe-minicart').addClass('is-adding-to-cart');
            }
        });
        
    }
    
    /* Categories filter */
    $(document).on('click', '.category-filter a', function (e) {
        var $this = $(this);
        var toolBarProducts = $this.closest('.toolbar-products');
        var parentWrap = toolBarProducts.parent();
        var cat_slug = $this.closest('*[data-cat_slug]').data('cat_slug');
        
        if ($.trim(cat_slug) != '') {
            if ($('.shop-prdctfltr-filter-wrap .prdctfltr_ft_' + cat_slug).length) {
                parentWrap.find('.shop-prdctfltr-filter-wrap .prdctfltr_ft_' + cat_slug).trigger('click');
                e.preventDefault();
            }
            
        } else {
            if ($('.shop-prdctfltr-filter-wrap .prdctfltr_ft_none').length) {
                parentWrap.find('.shop-prdctfltr-filter-wrap .prdctfltr_ft_none').trigger('click');
                e.preventDefault();
            }
        }
        
    });
    if ($('.products-grid').hasClass('active')) {
        $('.main-content .products').addClass('grid-size');
    }
    
    /* Products size */
    $(document).on('click', '.products-sizes .products-size', function (e) {
        var $this = $(this);
        var product_size = parseInt($this.attr('data-products_num'));
        var thisParent = $this.closest('.products-sizes');
        var thisContainer = $this.closest('.main-container');
        var is_shortcode = thisParent.is('.products-sizes-shortcode');
        if (is_shortcode) {
            thisContainer = $this.closest('.prdctfltr_sc_products');
        }
        var productsList = thisContainer.find('.products');
        var product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6'; // 4 items
        
        // Remove all classes with prefix "products_list-size-"
        productsList.removeClass(function (index, class_name) {
            return (class_name.match(/\bproducts_list-size-\S+/g) || []).join(' '); // removes anything that starts with "products_list-size-"
        }).addClass('products_list-size-' + product_size);
        // Remove all classes with prefix "col-"
        productsList.find('.product-item').removeClass(function (index, class_name) {
            return (class_name.match(/\bcol-\S+/g) || []).join(' '); // removes anything that starts with "col-"
        });
        
        switch (product_size) {
            case 6:
                product_item_classes = 'col-bg-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ts-6';
                break;
            case 5:
                product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-4';
                break;
            case 4:
                product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6';
                break;
            case 3:
                product_item_classes = 'col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-6';
                break;
        }
        
        productsList.find('.product-item').addClass(product_item_classes);
        thisParent.find('.products-size').removeClass('active');
        $this.addClass('active');
        
        
        if ($this.hasClass('products-grid')) {
            productsList.addClass('grid-size');
        } else {
            productsList.removeClass('grid-size');
        }
        
        e.preventDefault();
    });
    
    function lebe_init_products_size() {
        $('.products-sizes .products-size.active').each(function () {
            var $this = $(this);
            var product_size = parseInt($this.attr('data-products_num'));
            var thisParent = $this.closest('.products-sizes');
            var thisContainer = $this.closest('.main-container');
            var is_shortcode = thisParent.is('.products-sizes-shortcode');
            if (is_shortcode) {
                thisContainer = $this.closest('.prdctfltr_sc_products');
            }
            var productsList = thisContainer.find('.products');
            var product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6'; // 4 items
            
            // Remove all classes with prefix "col-"
            productsList.find('.product-item').removeClass(function (index, class_name) {
                return (class_name.match(/\bcol-\S+/g) || []).join(' '); // removes anything that starts with "col-"
            });
            
            switch (product_size) {
                case 6:
                    product_item_classes = 'col-bg-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 col-ts-6';
                    break;
                case 5:
                    product_item_classes = 'col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6';
                    break;
                case 4:
                    product_item_classes = 'col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-6';
                    break;
                case 3:
                    product_item_classes = 'col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-6';
                    break;
            }
            productsList.find('.product-item').addClass(product_item_classes);
        });
    }
    
    function lebe_add_class_active_to_list_cats_on_top_bar_filter() {
        if ($('.prdctfltr_filter[data-filter="product_cat"] .prdctfltr_active').length) {
            $('.part-cats-list-wrap .category-filter *[data-cat_slug]').removeClass('active');
            $('.prdctfltr_filter[data-filter="product_cat"] .prdctfltr_active').each(function () {
                var cat_slug = $(this).find('input').val();
                if ($.trim(cat_slug) != '') {
                    $('.part-cats-list-wrap .category-filter *[data-cat_slug="' + cat_slug + '"]').addClass('active');
                }
            });
        } else {
            if ($('.prdctfltr_filter[data-filter="product_cat"]').length) {
                $('.part-cats-list-wrap .category-filter *[data-cat_slug]').removeClass('active');
                $('.part-cats-list-wrap .category-filter *[data-cat_slug=""]').addClass('active');
            }
        }
    }
    
    function prdctfltr_custom_remove() {
        var remove_prdctfltr_topbar = $('.vc_desktop .prdctfltr_topbar');
        var prdcfltr_reset_clone = $('.main-product .prdctfltr_reset span');
        remove_prdctfltr_topbar.clone().appendTo('.part-filter-wrap .actions-wrap');
        prdcfltr_reset_clone.addClass('reset-filter-product').clone().appendTo('.part-filter-wrap .prdctfltr_topbar');
        $('.part-filter-wrap .prdctfltr_title_selected a').removeClass('prdctfltr_title_remove').addClass('prdctfltr_title_remove_clone')
        $('.vc_desktop .prdctfltr_topbar:not(:last-child)').remove();
        $(document).on('click', '.prdctfltr_title_remove_clone', function (e) {
            $('.prdctfltr_title_remove').trigger('click');
            $(this).parent().remove();
            return false;
        })
        $(document).on('click', '.reset-filter-product', function (e) {
            $('.prdctfltr_reset > label').trigger('click');
            $('.actions-wrap .prdctfltr_topbar').remove();
            return false;
        })
    }
    
    /* ---------------------------------------------
     Load More Product (will remove)
     -----------------------------------------------*/
    function lebe_product_loadmore() {
        $('.woo-product-loadmore').on('click', function (e) {
            $('.woo-product-loadmore').addClass('loading');
            // get post ID in array
            var except_post_ids = Array();
            var _product_wrap = $(this).closest('.lebe-products');
            
            _product_wrap.find('.product-item').each(function () {
                var post_id = $(this).attr('id').replace('post-', '');
                except_post_ids.push(post_id);
            });
            // get post ID in array
            var attr = $(this).attr('data-attribute');
            var cats = $(this).attr('data-cats');
            var id = $(this).data('id');
            var page = parseInt($(this).attr('page'));
            var data = {
                action: 'lebe_loadmore_product',
                except_post_ids: except_post_ids,
                security: lebe_theme_frontend.security,
                attr: attr,
                cats: cats,
                page: page,
            };
            $.post(lebe_theme_frontend.ajaxurl, data, function (response) {
                var items = $('' + response['html'] + '');
                if ($.trim(response['success']) == 'ok') {
                    var tab_id = '.' + id;
                    $('#' + id).append(items);
                    if ($.trim(response['show_bt']) == '0') {
                        $(tab_id + ' .woo-product-loadmore').html('No More Product');
                    } else {
                        $(tab_id + ' .woo-product-loadmore').attr('page', page + 1);
                    }
                } else {
                    $('#' + id).append('<p class="return-message bg-success">Not ok</p>');
                }
                $('.woo-product-loadmore').removeClass('loading');
            });
            return false;
        });
    }
    
    /* ---------------------------------------------
     Scripts scroll
     --------------------------------------------- */
    
    // Back to top button
    function backToTop() {
        var el = $('.totop-holder');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 180) {
                el.addClass('b_show');
            } else {
                el.removeClass('b_show');
            }
        });
        el.on('click', function (e) {
            $('body,html').animate({
                scrollTop: 0
            }, 500);
            return false;
        });
    }
    
    /* ---------------------------------------------
     // Woof Ajax done
     --------------------------------------------- */
    function woof_ajax_done_handler(e) {
        if ($('img.lazy').length > 0) {
            $('img.lazy').each(function () {
                if ($(this).data('original')) {
                    $(this).attr('src', $(this).data('original'));
                }
            });
        }
    }
    
    function lebe_scrolldown() {
        $('.rev_slider_wrapper').on('mousewheel', function (event) {
            var $this = $(this);
            if ($this.find('.arrow-down').length) {
                if (!$this.find('.arrow-down').is('.has-scrolled')) {
                    $this.find('.arrow-down').trigger('click').addClass('has-scrolled');
                }
            }
        });
    }
    
    
    /* Search Instant */
    function lebe_search_json(search_key, cat_slug, json_args) {
        var all_results = Array();
        $.each(json_args, function (i, v) {
            var regex = new RegExp(search_key, "i");
            if (v.post_title.search(new RegExp(regex)) != -1 || v.sku.search(new RegExp(regex)) != -1) {
                if (cat_slug != '') {
                    var regex_cat_slug = new RegExp(cat_slug, "i");
                    if (v.cat_slugs.search(new RegExp(regex_cat_slug)) != -1) {
                        all_results.push(v);
                    }
                } else {
                    all_results.push(v);
                }
            }
        });
        
        return all_results;
    }
    
    var products_data_array = {};
    
    // Instant Search
    function lebe_search_instant() {
        $('.header-search-content').addClass('show-content-search');
        $('.site-header').addClass('show-search');
        // $('.site-header .search-field').focus();
        
        if (!$('.instant-search').length) {
            return;
        }
        
        if ($.isEmptyObject(products_data_array) && !$('.instant-search-disabled').length) {
            var data = {
                action: 'lebe_instant_search_data', security: lebe_theme_frontend.security,
            };
            $.post(lebe_theme_frontend.ajaxurl, data, function (response) {
                // $('.site-header .search-field').focus();
                products_data_array = response['array'];
                var $modal = $('.search-block'), $form = $modal.find('form');
                var url = $form.attr('action') + '?' + $form.serialize();
                if ($.trim(response['success']) == 'yes') {
                    $(document).on('keyup', '.instant-search .search-fields input[name="s"]', function (e) {
                        var $this = $(this);
                        var thisSeachForm = $this.closest('.instant-search');
                        if (thisSeachForm.is('.instant-search-disabled')) {
                            return false;
                        }
                        var searchWrap = $this.closest('.search-fields').find('.search-results-container .search-results-container-inner');
                        var search_key = $.trim($this.val());
                        var cat_slug = ''; // All cats
                        if ($('.header-search-box .instant-search input[name="product_cat"]:checked').length) {
                            cat_slug = $('.header-search-box .instant-search input[name="product_cat"]:checked').val();
                        }
                        searchWrap.find('.container-search').remove();
                        $(this).removeClass('search-has-results');
                        if (products_data_array && search_key != '') {
                            var search_results = lebe_search_json(search_key, cat_slug, products_data_array);
                            if (search_results) {
                                $(this).addClass('search-has-results');
                                searchWrap.html('<div class="container-search"><div class="search-results-wrap row auto-clear"></div></div>');
                                var max_instant_search_results = parseInt(lebe_theme_frontend['max_instant_search_results']);
                                if (isNaN(max_instant_search_results) || max_instant_search_results <= 0) {
                                    max_instant_search_results = 100;
                                }
                                for (var i = 0; i < search_results.length && i < max_instant_search_results; i++) {
                                    searchWrap.find('.container-search .search-results-wrap').append(search_results[i]['post_html']);
                                }
                                if ($('.product-item-search').length > 0) {
                                    searchWrap.find('.container-search').append('<a class="search-view" href="' + url + '">View All</a>');
                                }
                                $('.reset-instant-search-wrap').prepend('<span class="reset-instant-search flaticon-close"></span>')
                                $('.reset-instant-search').on('click', function (e) {
                                    var searchWrap = $(this).closest('.search-fields');
                                    searchWrap.find('.container-search').remove();
                                    $('.search-field').val('');
                                    $('.reset-instant-search').remove();
                                });
                                $('.reset-instant-search:not(:last-child)').remove()
                            }
                            
                        } else {
                            $('.reset-instant-search').remove();
                        }
                        
                    });
                    $('.search-fields input[name="s"]').trigger('keyup');
                    $('.product-cats input[name="product_cat"]').on('click', function (e) {
                        $('.search-fields input[name="s"]').trigger('keyup');
                        $(this).parent().addClass('selected').siblings().removeClass('selected');
                        e.preventDefault();
                    });
                    
                    
                }
            });
            
        }
    }
    
    function fami_main_header_sticky() {
        if ($('.menu-sticky-smart').length) {
            var mainHeader = $('.header-position');
            var mainMobile = $('.header-mobile');
            var top_spacing = 0;
            var curentScrollTop = 0;
            var admin_bar_h = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
            top_spacing += admin_bar_h;
            mainHeader.sticky({topSpacing: top_spacing});
            mainMobile.sticky({topSpacing: top_spacing});
            //Menu Sticky
            if ($(window).width() > 1024) {
                $(window).on('scroll', function () {
                    var scroll = $(this).scrollTop(), begenScroll = $('.header-wrap').outerHeight();
                    if (scroll > begenScroll) {
                        $('.menu-sticky-smart').addClass('header-setup-sticky')
                    } else {
                        $('.menu-sticky-smart').removeClass('header-setup-sticky')
                    }
                    if (scroll < curentScrollTop) {
                        $('.menu-sticky-smart').addClass('header-sticky')
                    } else if (scroll > curentScrollTop) {
                        $('.menu-sticky-smart').removeClass('header-sticky')
                    }
                    curentScrollTop = scroll;
                });
            }
        }
        
    }
    
    function lebe_popover_tooltip() {
        $('.product-item .button-loop-action a').each(function () {
            var $titler = $(this).text();
            if (!$(this).hasClass('add_to_cart_button')) {
                $(this).tooltip({
                    title: $titler, trigger: 'hover', placement: 'top',
                });
            }
            ;$(this).closest('.add-to-cart').tooltip({
                title: $titler, trigger: 'hover', placement: 'top',
            });
        });
        $('.product-item .list-button-action a').each(function () {
            var $titler = $(this).text();
            if (!$(this).hasClass('add_to_cart_button')) {
                $(this).tooltip({
                    title: $titler, trigger: 'hover', placement: 'top',
                });
            }
            ;$(this).closest('.add-to-cart').tooltip({
                title: $titler, trigger: 'hover', placement: 'top',
            });
        });
    }
    
    // Real Single Product mobile
    function single_product_mobile() {
        var $singleSummary = $('.single-product-mobile .summary');
        var $variationsForm = $singleSummary.find('.variable_mobile');
        if ($('.single-product-mobile').length && $variationsForm.length) {
            var qty_html = '';
            var close_btn_html = '<a href="#" class="close-box-content flaticon-close"></a>';
            var add_to_cart_text = $variationsForm.find('.single_add_to_cart_button').text();
            if ($singleSummary.find('.quantity input.qty').length) {
                var qty_text = $singleSummary.find('.quantity .qty-label').text();
                var cur_qty = $singleSummary.find('input.qty').val();
                qty_html = '<span class="qty-label">' + qty_text + '</span>' + '<div class="control">' + '     <a class="btn-number qtyminus quantity-minus" href="#">-</a>' + '     <input type="text" data-step="1" min="1" max="" name="quantity" value="' + cur_qty + '" class="input-qty input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric">' + '     <a class="btn-number qtyplus quantity-plus" href="#" style="pointer-events: auto;">+</a>' + '</div>';
                qty_html = '<div class="quantity">' + qty_html + '</div>';
            }
            if ($singleSummary.find('.single_variation_wrap').length) {
                $('.single_variation_wrap').prepend('' + qty_html + '');
            }
            ;$('.variable_mobile').append('<button type="button" class="single_add_to_cart_button-clone button alt">' + add_to_cart_text + '</button>' + close_btn_html + '');
        }
        
    }
    
    function cloneThumbsSingleMobile() {
        if ($('.variable_mobile .woocommerce-product-gallery__wrapper').length) {
            $('.lebe-content-single-product-mobile .single-left').prepend('<div class="woocommerce-product-gallery-wrap-clone"></div>')
        }
        var clone_thumbs = $('.variable_mobile .woocommerce-product-gallery__wrapper');
        clone_thumbs.addClass('woocommerce-product-gallery-clone').clone().appendTo('.woocommerce-product-gallery-wrap-clone');
        $('.woocommerce-product-gallery-wrap-clone .woocommerce-product-gallery-clone').each(function () {
            $(this).slick({
                slidesToShow: 1, slidesToScroll: 1, arrows: false, dots: true, infinite: false,
            });
        });
        if ($('.woocommerce-product-gallery-wrap-clone').length) {
            $('.lebe-content-single-product-mobile .single-left .woocommerce-product-gallery').remove();
        }
    }
    
    cloneThumbsSingleMobile();
    
    // Clone Logo header 1
    function clone_logo_header1_res() {
        if ($('.item-logo').length) {
            var logo_clone = $('header .item-logo span.logo').html();
            $('.header-action-res .logo a').html(logo_clone);
        }
    }
    
    // Mini Cart height
    function height_mini_cart() {
        if ($(window).width() < 1025) {
            $('.header .minicart-list-items').css({'height': $(window).outerHeight() - 183 + 'px'});
        }
    }
    
    /**
     *
     * @param $form
     * @param response
     * @param position  top or bottom.
     */
    function display_multi_messages($form, response, position) {
        $form.find('.fami-message').remove();
        
        var msg_class = '';
        
        if (response['err'] === 'yes') {
            msg_class += 'alert-danger notice notice-error';
        } else {
            msg_class += 'alert-success updated notice notice-success';
        }
        
        if ($.type(response['message']) === 'string') {
            if (response['message'] !== '') {
                if (position === 'top') {
                    $form.prepend('<div class="fami-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                } else {
                    $form.append('<div class="fami-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
            }
        } else {
            $.each(response['message'], function (index, item) {
                if (position === 'top') {
                    $form.prepend('<div class="fami-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                } else {
                    $form.append('<div class="fami-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
            });
        }
    }
    
    /* ---------------------------------------------
     Scripts bind
     --------------------------------------------- */
    
    $(window).on('load', function () {
        better_equal_elems();
        number_dots_rev();
        $('.scrollbar-macosx').scrollbar();
    });
    /* ---------------------------------------------
     Scripts load
     --------------------------------------------- */
    
    window.addEventListener('load', function (ev) {
        if ($('body.single-product').length) {
            thumbnail_product();
        }
        if ($('.product-item.style-01').length) {
            product_gallery();
        }
        if ($('.lebe-instagramshopwrap.style-04 .ziss-row').length) {
            instagram_shop_slick();
        }
        //Remove added Single Add to cart mobile
        $('.single_add_to_cart_button-clone').removeClass('added');
        lebe_init_lazy_load();
        better_equal_elems();
        lebe_init_carousel();
        lebe_single_product_mobile_structure();
        lebe_update_single_product_title_price_stars_outside_summary();
    });
    
    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */
    
    $(window).on("resize", function () {
        $('.scrollbar-macosx').scrollbar();
        if ($('body.single-product').length) {
            thumbnail_product();
        }
        better_equal_elems();
        lebe_clone_main_menu();
        lebe_resizeMegamenu();
        if ($('.lebe-instagramshopwrap.style-02 .ziss-row').length) {
            lebe_instagramshopslide();
        }
        sticky_scrollup();
        if ($('.product-item.style-01').length) {
            product_gallery();
        }
        if ($('.lebe-instagramshopwrap.style-04 .ziss-row').length) {
            instagram_shop_slick();
        }
    });
    
    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    
    function lebe_all_functions_on_ready() {
        lebe_clone_append_category();
        fami_main_header_sticky();
        lebe_clone_main_menu();
        sticky_scrollup();
        lebe_search_instant();
        lebe_init_products_size();
        $('.scrollbar-macosx').scrollbar();
        lebe_add_class_active_to_list_cats_on_top_bar_filter();
        lebe_add_to_cart_single();
        lebe_scrolldown();
        lebe_remove_product();
        toggle_form();
        lebe_sidebar_offcanvas();
        lebe_minicart_offcanvas();
        lebe_instant_search_close();
        lebe_vertical_menu();
        initAjaxLoad();
        lebe_countdown();
        lebe_woo_quantily();
        lebe_tab_fade_effect();
        lebe_google_maps();
        lebe_resizeMegamenu();
        if ($('.lebe-instagramshopwrap.style-02 .ziss-row').length) {
            lebe_instagramshopslide();
        }
        update_wishlist_count();
        lebe_singleProduct_popup();
        lebe_product_loadmore();
        backToTop();
        sticky_detail_content();
        lebe_init_popup();
        number_dots_rev();
        lebe_popover_tooltip();
        single_product_mobile();
        clone_logo_header1_res();
        height_mini_cart();
        $(document).on('click', '.prdctfltr_filter:not(.prdctfltr_byprice) label', function (e) {
            prdctfltr_custom_remove();
        });
        
        
        $(document).on('click', '.mobile-navigation', function (e) {
            $('body').toggleClass('box-mobile-menu-open');
        });
        $(document).on('click', '.box-mobile-menu .close-menu, .body-overlay,.box-mibile-overlay,.close-menu,.close-menu-horizon', function (e) {
            $('body').removeClass('box-mobile-menu-open real-mobile-show-menu');
            $('.hamburger').removeClass('is-active');
        });
        $(document).on('click', '.body-overlay,.enable-shop-page-mobile .prdctfltr_showing', function (e) {
            $('html,body').removeClass('wc-prdctfltr-active variable_mobile_show');
            $('.variable_mobile').removeClass('show-box-content');
            $('.prdctfltr_woocommerce_filter').trigger('click');
        });
        // Menu hover Mouse
        $('.lebe-categorylist').mouseenter(function () {
            $('.lebe-categorylist').addClass('item-hover');
            $(this).removeClass('item-hover');
            $(this).addClass('item-active');
        }).mouseleave(function () {
            $('.lebe-categorylist').removeClass('item-hover item-active');
        });
        //Count Cart
        $('.lebe-categorylist').each(function () {
            var count = $('.lebe-categorylist').length;
            if (count == 6) {
                $(this).addClass('col-lg-2');
            } else if (count == 5) {
                $(this).addClass('col-lg-15');
            } else if (count == 4) {
                $(this).addClass('col-lg-3');
            } else if (count == 3) {
                $(this).addClass('col-lg-4');
            } else if (count == 2) {
                $(this).addClass('col-lg-6');
            }
            $('.header-top').addClass('has-' + count + '-element');
        });
        //Single tabs desc
        $(document).on("click", '.button-togole', function (e) {
            var $this = $(this);
            $this.parent().addClass('tab-show');
            $('html').addClass('body-hide');
            e.preventDefault();
            return false;
        });
        $(document).on("click", '.close-tab', function (e) {
            var $this = $(this);
            $('.tabs-mobile-content').removeClass('tab-show');
            $('html').removeClass('body-hide');
            e.preventDefault();
            return false;
        });
        //Single Sticky tabs desc
        $(document).on("click", '.button-togole-tab', function (e) {
            var $this = $(this);
            var thisTab = $this.closest('.tabs-sticky-content');
            var thisShow = thisTab.closest('.woocommerce-tabs-sticky');
            if (thisTab.is('.tab-open')) {
                thisTab.find('> .content-tab-sticky-element').stop().slideUp(function () {
                    $(document).trigger('resize');
                });
                thisTab.removeClass('tab-open');
            } else {
                thisShow.find('> .tab-open > .content-tab-sticky-element').stop().slideUp();
                thisShow.find('> .tab-open').removeClass('tab-open');
                thisTab.find('> .content-tab-sticky-element').stop().slideDown(function () {
                    $(document).trigger('resize');
                });
                thisTab.addClass('tab-open');
            }
            return false;
            
        });
        // close-notice
        $(document).on('click', '.close-notice', function (e) {
            if ($('.header-topbar').length) {
                $('.header-topbar').remove();
            }
            if ($('.single .header-pos-absolute').length) {
                $('.site-header').addClass('hide-topbar');
            }
            if ($('.style-07').length) {
                $('.site-header').addClass('hide-topbar');
            }
            e.preventDefault();
        });
        
        /*  Mobile Menu on real mobile (if header mobile is enabled) */
        $(document).on('click', '.mobile-hamburger-navigation ', function (e) {
            $(this).find('.hamburger').addClass('is-active');
            if ($(this).find('.hamburger').is('.is-active')) {
                $('body').addClass('real-mobile-show-menu box-mobile-menu-open');
            } else {
                $('body').removeClass('real-mobile-show-menu box-mobile-menu-open');
            }
            e.preventDefault();
        });
        
        /* Mobile Tabs on real mobile */
        $(document).on('click', '.box-tabs .box-tab-nav', function (e) {
            var $this = $(this);
            var thisTab = $this.closest('.box-tabs');
            var tab_id = $this.attr('href');
            
            if ($this.is('.active')) {
                return false;
            }
            
            thisTab.find('.box-tab-nav').removeClass('active');
            $this.addClass('active');
            
            thisTab.find('.box-tab-content').removeClass('active');
            thisTab.find(tab_id).addClass('active');
            
            e.preventDefault();
        });
        
        // Wish list on real menu mobile
        if ($('.box-mobile-menu .wish-list-mobile-menu-link-wrap').length) {
            if (!$('.box-mobile-menu').is('.moved-wish-list')) {
                var wish_list_html = $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').html();
                $('.box-mobile-menu .wish-list-mobile-menu-link-wrap').remove();
                $('.box-mobile-menu .main-menu').append('<li class="menu-item-for-wish-list menu-item menu-item-type-custom menu-item-object-custom">' + wish_list_html + '</li>');
                $('.box-mobile-menu').addClass('moved-wish-list');
            }
        }
        // Lang real menu mobile
        if ($('.box-mobile-menu .header-lang-mobile').length) {
            if (!$('.box-mobile-menu').is('.moved-lang-mobile')) {
                var lang_mobile_html = $('.box-mobile-menu .header-lang-mobile').html();
                $('.box-mobile-menu .header-lang-mobile').remove();
                $('.box-mobile-menu .main-menu').append('' + lang_mobile_html + '');
                $('.box-mobile-menu').addClass('moved-lang-mobile');
            }
        }
        /* Loading wishlist */
        $('.add_to_wishlist').on('click', function () {
            $(this).addClass('loading');
        });
        
        //Scroll Down
        $('.scroll-down a').on('click', function (e) {
            var url_wl = $(this).attr("href");
            var taget_wl = $(url_wl).offset().top;
            $('html, body').animate({scrollTop: taget_wl - 70}, 'slow');
            e.preventDefault();
        });
        if ($('.big_images').length) {
            $('.product-toolbar').addClass('product-big');
        }
        // Submit when choose sort by
        $(document).on('change', 'form.fami-woocommerce-ordering select[name="orderby"]', function () {
            var $this = $(this);
            var thisForm = $this.closest('form');
            var order_val = $this.val();
            var trigger_submit = true;
            $('.prdctfltr_wc .prdctfltr_woocommerce_ordering').each(function () {
                if ($(this).closest('.prdctfltr_sc_products').length == 0) {
                    if ($(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val + ' input[type="checkbox"]').length) {
                        $(this).find('.prdctfltr_orderby .prdctfltr_ft_' + order_val).click();
                        trigger_submit = false;
                        return false;
                    }
                }
            });
            
            if (trigger_submit) {
                thisForm.submit();
            }
        });
        $(document).on('click', '.prdctfltr_orderby .prdctfltr_checkboxes label', function () {
            var $this = $(this);
            var order_val = $this.find('input[type="checkbox"]').val();
            if ($('form.fami-woocommerce-ordering select[name="orderby"]').length) {
                $('form.fami-woocommerce-ordering select[name="orderby"]').val(order_val);
                var selected_index = $('form.fami-woocommerce-ordering select[name="orderby"]').prop('selectedIndex');
                var order_text = $('form.fami-woocommerce-ordering select[name="orderby"] option:selected').text();
                $('form.fami-woocommerce-ordering .chosen-results .active-result').removeClass('result-selected highlighted');
                $('form.fami-woocommerce-ordering .chosen-results .active-result[data-option-array-index="' + selected_index + '"]').addClass('result-selected highlighted');
                $('form.fami-woocommerce-ordering .chosen-single span').text(order_text);
                
            }
        });
        if ($('.toolbar-products select').length > 0) {
            // $('.toolbar-products select').chosen();
        }
        if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
            $('body').addClass('safari');
        }
        $(document).on('click', '.search-icon', function () {
            $(this).closest('.search-block').find('.search-form').slideToggle();
        });
        // Click add to cart clone button
        $(document).on('click', '.single_add_to_cart_button-clone', function (e) {
            $('.summary .variations_form .single_add_to_cart_button').trigger('click');
            $(this).addClass('loading').removeClass('added');
            $('body,html').toggleClass('minicart-canvas-open');
            e.preventDefault();
            return false;
        });
        $(document).on('click', '.toggle-variations-select-mobile', function (e) {
            var $this = $(this);
            $('html,body').toggleClass('variable_mobile_show');
            $this.parent().find('.variable_mobile').toggleClass('show-box-content');
            e.preventDefault();
            return false;
        });
        $(document).on('click', '.close-box-content', function (e) {
            $('html,body').removeClass('variable_mobile_show');
            $('.variable_mobile').removeClass('show-box-content');
            e.preventDefault();
            return false;
        });
        $('.single_add_to_cart_button-clone').removeClass('added');
        // Change clone qty
        $(document).on('change', '.single_variation_wrap > .quantity input.qty', function () {
            var this_val = $(this).val();
            $('.summary .variations_form .woocommerce-variation-add-to-cart input.qty').val(this_val).trigger('change');
        });
        // Change clone qty Deals
        $(document).on('change', '.deal-add-to-cart .quantity input.qty', function () {
            var this_val = $(this).val();
            $('.summary > .cart input.qty').val(this_val).trigger('change');
        });
        if ($('.no_background').length) {
            $('.site-header').addClass('header-no-background');
        }
        
    }
    
    // Reinit some important things after ajax
    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings.hasOwnProperty('data')) {
            lebe_variations_custom_ajax();
            lebe_init_lazy_load();
            lebe_init_products_size();
        }
        lebe_fix_responsive_img_issue_product_var();
        initAjaxLoad();
        lebe_single_product_mobile_structure();
        lebe_update_single_product_title_price_stars_outside_summary();
        lebe_add_class_active_to_list_cats_on_top_bar_filter();
        lebe_init_carousel();
        prdctfltr_custom_remove();
        $('.scrollbar-macosx').scrollbar();
        lebe_remove_product();
        number_dots_rev();
        height_mini_cart();
        
    });
    
    $(document).on("woof_ajax_done", woof_ajax_done_handler);
    $(document).on('adding_to_cart', function () {
        $('body,html').toggleClass('minicart-canvas-open');
        if ($('.lebe-minicart').length) {
            $('.lebe-minicart').addClass('is-adding-to-cart');
        }
    });
    $(document).on('added_to_cart', function () {
        if ($('.lebe-minicart').length) {
            $('.lebe-minicart').removeClass('is-adding-to-cart');
        }
    });
    $(document).ready(function () {
        lebe_all_functions_on_ready();
        
        // Login via ajax (woocommerce-login)
        $(document).on('submit', '.login-form form.login', function () {
            var $thisForm = $(this);
            var user_name = $thisForm.find('input[name="username"]').val();
            var password = $thisForm.find('input[name="password"]').val();
            var rememberme = 'no';
            if ($thisForm.find('input[name="rememberme"]').is(':checked')) {
                rememberme = 'yes';
            }
            var nonce = $thisForm.find('input[name="woocommerce-login-nonce"]').val();
            
            if ($thisForm.is('.processing')) {
                return false;
            }
            
            $thisForm.addClass('processing');
            
            var data = {
                action: 'lebe_login_via_ajax',
                user_name: user_name,
                password: password,
                rememberme: rememberme,
                nonce: nonce
            };
            
            $.post(lebe_theme_frontend['ajaxurl'], data, function (response) {
                
                $thisForm.removeClass('processing');
                display_multi_messages($thisForm.parent(), response, 'bottom');
                if (response['err'] == 'no') {
                    $thisForm.replaceWith('');
                    location.reload();
                }
                
            });
            
            return false;
        });
        // Multivendor custom
        if ($('.vendor_description_background').length) {
            $('.main-product .vendor_description_background').insertAfter('.main-product .prdctfltr_wc');
        }
        
    });
})(jQuery); // End of use strict