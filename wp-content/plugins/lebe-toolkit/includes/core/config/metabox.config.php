<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

$data_meta = new Lebe_ThemeOption();
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// META BOX OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options = array();
// -----------------------------------------
// Page Meta box Options                   -
// -----------------------------------------
$options[] = array(
	'id'        => '_custom_metabox_theme_options',
	'title'     => esc_html__( 'Custom Options', 'lebe-toolkit' ),
	'post_type' => 'page',
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => 'header_footer_theme_options', // !??
			'title'  => esc_html__( 'Header Settings', 'lebe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'type'    => 'subheading',
					'content' => esc_html__( 'Header Settings', 'lebe-toolkit' ),
				),
				array(
					'id'      => 'enable_custom_header',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Custom Header', 'lebe-toolkit' ),
					'default' => false,
					'desc'    => esc_html__( 'The default is off. If you want to use separate custom page header, turn it on.', 'lebe-toolkit' ),
				),
				array(
					'id'         => 'enable_sticky_menu',
					'type'       => 'select',
					'title'      => esc_html__( 'Sticky Header', 'lebe-toolkit' ),
					'options'    => array(
						'none'  => esc_html__( 'Disable', 'lebe-toolkit' ),
						'smart' => esc_html__( 'Sticky Header', 'lebe-toolkit' ),
					),
					'default'    => 'none',
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'enable_topbar',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Enable topbar', 'lebe-toolkit' ),
					'default'    => false,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'topbar-text',
					'type'       => 'text',
					'title'      => esc_html__( 'Text Topbar', 'lebe-toolkit' ),
					'dependency' => array( 'enable_custom_header|enable_topbar', '==', 'true|true' ),
				),
                array(
                    'id'      => 'background_color_topbar_text',
                    'type'    => 'color_picker',
                    'title'   => esc_html__( 'Background Color Topbar Text ', 'lebe-toolkit' ),
                    'default' => '#000',
                    'rgba'    => true,
                    'dependency'   => array( 'enable_topbar|enable_custom_header', '==|==|==', 'true|true' ),
                ),
				array(
					'id'         => 'metabox_lebe_logo',
					'type'       => 'image',
					'title'      => esc_html__( 'Custom Logo', 'lebe-toolkit' ),
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'lebe_metabox_used_header',
					'type'       => 'select_preview',
					'title'      => esc_html__( 'Header Layout', 'lebe-toolkit' ),
					'desc'       => esc_html__( 'Select a header layout', 'lebe-toolkit' ),
					'options'    => $data_meta->header_options,
					'default'    => 'logo_l_menu_c_icons_r_bg_trans',
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'header_text_color',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Header Text Color', 'lebe-toolkit' ),
					'default'    => '#000',
					'rgba'       => true,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),

				array(
					'id'         => 'header_bg_color',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Header Background Color', 'lebe-toolkit' ),
					'default'    => 'rgba(0,0,0,0)',
					'rgba'       => true,
					'dependency' => array( 'enable_custom_header', '==', true ),
				),
				array(
					'id'         => 'header_position',
					'type'       => 'select',
					'title'      => esc_html__( 'Header Type', 'lebe-toolkit' ),
					'options'    => array(
						'relative' => esc_html__( 'Header No Transparent', 'lebe-toolkit' ),
						'absolute' => esc_html__( 'Header Transparent', 'lebe-toolkit' ),
					),
					'default'    => 'relative',
					'dependency' => array( 'enable_custom_header|lebe_metabox_used_header', '==|!=', 'true|sidebar' ),
				),
			
			)
		),
		array(
			'name'   => 'page_banner_settings',
			'title'  => esc_html__( 'Page Banner Settings', 'lebe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'id'      => 'enable_custom_banner',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Page Custom Banner', 'lebe-toolkit' ),
					'default' => false,
					'desc'    => esc_html__( 'The default is off. If you want to use separate custom page banner, turn it on.', 'lebe-toolkit' ),
				),
				array(
					'id'         => 'hero_section_type',
					'type'       => 'select',
					'title'      => esc_html__( 'Banner Type', 'lebe-toolkit' ),
					'options'    => array(
						'disable'        => esc_html__( 'Disable', 'lebe-toolkit' ),
						'has_background' => esc_html__( 'Has Background', 'lebe-toolkit' ),
						'no_background'  => esc_html__( 'No Background ', 'lebe-toolkit' ),
						'rev_background' => esc_html__( 'Revolution', 'lebe-toolkit' ),
					),
					'default'    => 'no_background',
					'dependency' => array( 'enable_custom_banner', '==', true ),
				),
				array(
					'id'         => 'bg_banner_page',
					'type'       => 'background',
					'title'      => esc_html__( 'Background Banner', 'lebe-toolkit' ),
					'default'    => array(
						'image'      => '',
						'repeat'     => 'repeat-x',
						'position'   => 'center center',
						'attachment' => 'fixed',
						'size'       => 'cover',
						'color'      => '#ffbc00',
					),
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|has_background' ),
				),
				array(
					'id'         => 'colortext_banner_page',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Banner Text Color', 'lebe-toolkit' ),
					'default'    => '#ffffff',
					'rgba'       => true,
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|has_background' ),
				),
				array(
					'id'         => 'lebe_metabox_header_rev_slide',
					'type'       => 'select',
					'options'    => lebe_rev_slide_options(),
					'title'      => esc_html__( 'Revolution', 'lebe-toolkit' ),
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|rev_background' ),
				),
				array(
					'id'         => 'page_banner_full_width',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Banner Background Full Width', 'lebe-toolkit' ),
					'default'    => 1,
					'dependency' => array( 'enable_custom_banner|hero_section_type', '==|==', 'true|has_background' ),
				),
				array(
					'id'         => 'page_banner_breadcrumb',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Enable Breadcrumb', 'lebe-toolkit' ),
					'default'    => 0,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
					'desc'       => esc_html__( 'This option has no effect on front page and blog page', 'lebe-toolkit' )
				),
				array(
					'id'         => 'page_height_banner',
					'type'       => 'number',
					'title'      => esc_html__( 'Banner Height', 'lebe-toolkit' ),
					'default'    => 420,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
				),
				array(
					'id'         => 'page_margin_top',
					'type'       => 'number',
					'title'      => esc_html__( 'Margin Top', 'lebe-toolkit' ),
					'default'    => 55,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
				),
				array(
					'id'         => 'page_margin_bottom',
					'type'       => 'number',
					'title'      => esc_html__( 'Margin Bottom', 'lebe-toolkit' ),
					'default'    => 100,
					'dependency' => array(
						'enable_custom_banner|hero_section_type',
						'==|any',
						'true|no_background,has_background'
					),
				),
				array(
					'id'         => 'show_hero_section_on_header_mobile',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Show Header Banner On Mobile', 'lebe-toolkit' ),
					'default'    => false,
					'desc'       => esc_html__( 'If enabled, the "Header Banner" is still displayed on the mobile. This option only works when the mobile header is enabled in Theme Options', 'lebe-toolkit' ),
					'dependency' => array( 'enable_custom_banner', '==', 'true' ),
				),
			),
		),
		array(
			'name'   => 'footer_settings',
			'title'  => esc_html__( 'Footer Settings', 'lebe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'id'      => 'enable_custom_footer',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Custom Footer', 'lebe-toolkit' ),
					'default' => false,
				),
				array(
					'id'         => 'lebe_metabox_footer_options',
					'type'       => 'select',
					'title'      => esc_html__( 'Select Footer Builder', 'lebe-toolkit' ),
					'options'    => 'posts',
					'query_args' => array(
						'post_type'      => 'footer',
						'orderby'        => 'post_date',
						'order'          => 'ASC',
						'posts_per_page' => - 1
					),
					'dependency' => array( 'enable_custom_footer', '==', true ),
				),
			)
		),
	),
);

// -----------------------------------------
// Product Meta box Options
// -----------------------------------------
$global_product_style      = lebe_toolkit_get_option( 'lebe_woo_single_product_layout', 'default' );
$all_product_styles        = array(
	'default'           => esc_html__( 'Default', 'lebe-toolkit' ),
	'vertical_thumnail' => esc_html__( 'Thumbnail Vertical', 'lebe-toolkit' ),
	'sticky_detail'     => esc_html__( 'Sticky Detail', 'lebe-toolkit' ),
	'gallery_detail'    => esc_html__( 'Gallery Detail', 'lebe-toolkit' ),
	'big_images'        => esc_html__( 'Big Images', 'lebe-toolkit' ),
);
$global_product_style_text = isset( $all_product_styles[ $global_product_style ] ) ? $all_product_styles[ $global_product_style ] : $global_product_style;
$options[]                 = array(
	'id'        => '_custom_product_metabox_theme_options',
	'title'     => esc_html__( 'Custom Options', 'lebe-toolkit' ),
	'post_type' => 'product',
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => 'product_options',
			'title'  => esc_html__( 'Product Configure', 'lebe-toolkit' ),
			'icon'   => 'fa fa-cube',
			'fields' => array(
				array(
					'id'         => 'size_guide',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Size guide', 'lebe-toolkit' ),
					'desc'       => esc_html__( 'On or Off Size guide', 'lebe-toolkit' ),
					'default'    => false,
				),
				array(
					'id'         => 'lebe_sizeguide_options',
					'type'       => 'select',
					'title'      => esc_html__( 'Select Size Guide Builder', 'lebe-toolkit' ),
					'options'    => 'posts',
					'dependency' => array( 'size_guide', '==', true ),
					'query_args' => array(
						'post_type'      => 'sizeguide',
						'orderby'        => 'post_date',
						'order'          => 'ASC',
						'posts_per_page' => - 1
					),
				),
				array(
					'id'      => 'product_style',
					'type'    => 'select',
					'title'   => esc_html__( 'Choose Style', 'lebe-toolkit' ),
					'desc'    => esc_html__( 'Choose Product Style', 'lebe-toolkit' ),
					'options' => array(
						'global'            => sprintf( esc_html__( 'Use Theme Options Style: %s', 'lebe-toolkit' ), $global_product_style_text ),
						'default'           => esc_html__( 'Default', 'lebe-toolkit' ),
						'vertical_thumnail' => esc_html__( 'Thumbnail Vertical', 'lebe-toolkit' ),
						'sticky_detail'     => esc_html__( 'Sticky Detail', 'lebe-toolkit' ),
						'gallery_detail'    => esc_html__( 'Gallery Detail', 'lebe-toolkit' ),
						'big_images'        => esc_html__( 'Big Images', 'lebe-toolkit' ),
					),
					'default' => 'global',
				),
				array(
					'id'         => 'product_img_bg_color',
					'type'       => 'color_picker',
					'title'      => esc_html__( 'Image Background Color', 'lebe-toolkit' ),
					'default'    => 'rgba(0,0,0,0)',
					'rgba'       => true,
					'dependency' => array(
						'product_style',
						'==',
						'big_images'
					),
					'desc'       => esc_html__( 'For "Big Images" style only. Default: transparent', 'lebe-toolkit' ),
				),
				array(
					'id'         => 'product_sum_border',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Summary Border', 'lebe-toolkit' ),
					'default'    => false,
					'dependency' => array(
						'product_style',
						'any',
						'default,vertical_thumnail,sticky_detail'
					),
				),
				array(
					'id'         => 'title_price_stars_outside_sum',
					'type'       => 'switcher',
					'title'      => esc_html__( 'Title, Price And Stars Outside Sumary', 'lebe-toolkit' ),
					'default'    => false,
					'dependency' => array(
						'product_style',
						'any',
						'default,vertical_thumnail,sticky_detail'
					),
				),
			)
		),
	)
);
// -----------------------------------------
// Page Footer Meta box Options            -
// -----------------------------------------
$options[] = array(
	'id'        => '_custom_footer_options',
	'title'     => esc_html__( 'Custom Footer Options', 'lebe-toolkit' ),
	'post_type' => 'footer',
	'context'   => 'normal',
	'priority'  => 'high',
	'sections'  => array(
		array(
			'name'   => esc_html__( 'FOOTER STYLE', 'lebe-toolkit' ),
			'fields' => array(
				array(
					'id'       => 'lebe_footer_style',
					'type'     => 'select',
					'title'    => esc_html__( 'Footer Style', 'lebe-toolkit' ),
					'subtitle' => esc_html__( 'Select a Footer Style', 'lebe-toolkit' ),
					'options'  => $data_meta->footer_options,
					'default'  => 'default',
				),
			),
		),
	),
);
// -----------------------------------------
// Page Testimonials Meta box Options      -
// -----------------------------------------
if ( class_exists( 'WooCommerce' ) ) {
	$options[] = array(
		'id'        => '_custom_post_woo_options',
		'title'     => esc_html__( 'Post Meta Data', 'lebe-toolkit' ),
		'post_type' => 'post',
		'context'   => 'normal',
		'priority'  => 'high',
		'sections'  => array(
			array(
				'name'   => 'post-products',
				'title'  => esc_html__( 'Products', 'lebe-toolkit' ),
				'icon'   => 'fa fa-picture-o',
				'fields' => array(
					array(
						'id'         => 'lebe_product_options',
						'type'       => 'select',
						'title'      => esc_html__( 'Select products', 'lebe-toolkit' ),
						'options'    => 'posts',
						'query_args' => array(
							'post_type'      => 'product',
							'orderby'        => 'post_date',
							'order'          => 'ASC',
							'posts_per_page' => - 1
						),
						'class'      => 'chosen',
						'attributes' => array(
							'placeholder' => 'Select product',
							'multiple'    => 'multiple',
							'style'       => 'width: 600px;'
						),
						'desc'       => esc_html__( 'Select product for post. It will display slide in loop.' ),
					),
				),
			),
			array(
				'name'   => 'post-format-setting',
				'title'  => esc_html__( 'Post Format Settings', 'lebe-toolkit' ),
				'icon'   => 'fa fa-picture-o',
				'fields' => array(
					array(
						'id'    => 'audio-video-url',
						'type'  => 'text',
						'title' => esc_html__( 'Upload Video or Audio Url', 'lebe-toolkit' ),
						'desc'  => esc_html__( 'Using when you choose post format video or audio.' ),
					),
					array(
						'id'          => 'post-gallery',
						'type'        => 'gallery',
						'title'       => esc_html__( 'Gallery', 'lebe-toolkit' ),
						'desc'        => esc_html__( 'Using when you choose post format gallery.' ),
						'add_title'   => esc_html__( 'Add Images', 'lebe-toolkit' ),
						'edit_title'  => esc_html__( 'Edit Images', 'lebe-toolkit' ),
						'clear_title' => esc_html__( 'Remove Images', 'lebe-toolkit' ),
					),
					array(
						'id'    => 'page_extra_class',
						'type'  => 'text',
						'title' => esc_html__( 'Extra Class', 'lebe-toolkit' ),
					),
				),
			),
		),
	);
	$options[] = array(
		'id'        => '_custom_product_woo_options',
		'title'     => esc_html__( 'Product Options', 'lebe-toolkit' ),
		'post_type' => 'product',
		'context'   => 'side',
		'priority'  => 'high',
		'sections'  => array(
			array(
				'name'   => 'meta_product_option',
				'fields' => array(
					array(
						'id'          => '360gallery',
						'type'        => 'gallery',
						'title'       => esc_html__( 'Gallery 360', 'lebe-toolkit' ),
						'add_title'   => esc_html__( 'Add Images', 'lebe-toolkit' ),
						'edit_title'  => esc_html__( 'Edit Images', 'lebe-toolkit' ),
						'clear_title' => esc_html__( 'Remove Images', 'lebe-toolkit' ),
					),
					array(
						'id'    => 'youtube_url',
						'type'  => 'text',
						'title' => esc_html__( 'Product Video', 'lebe-toolkit' ),
						'desc'  => esc_html__( 'Supported video Youtube, Vimeo .' ),
					),
				),
			),
		
		
		),
	);
}
// -----------------------------------------
// Page Side Meta box Options              -
// -----------------------------------------
$options[] = array(
	'id'        => '_custom_page_side_options',
	'title'     => esc_html__( 'Custom Page Side Options', 'lebe-toolkit' ),
	'post_type' => 'page',
	'context'   => 'side',
	'priority'  => 'default',
	'sections'  => array(
		array(
			'name'   => 'page_option',
			'fields' => array(
				array(
					'id'      => 'sidebar_page_layout',
					'type'    => 'image_select',
					'title'   => esc_html__( 'Single Post Sidebar Position', 'lebe-toolkit' ),
					'desc'    => esc_html__( 'Select sidebar position on Page.', 'lebe-toolkit' ),
					'options' => array(
						'left'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/left-sidebar.png',
						'right' => LEBE_TOOLKIT_URL . '/includes/core/assets/images/right-sidebar.png',
						'full'  => LEBE_TOOLKIT_URL . '/includes/core/assets/images/default-sidebar.png',
					),
					'default' => 'left',
				),
				array(
					'id'         => 'page_sidebar',
					'type'       => 'select',
					'title'      => esc_html__( 'Page Sidebar', 'lebe-toolkit' ),
					'options'    => $data_meta->sidebars,
					'default'    => 'blue',
					'dependency' => array( 'sidebar_page_layout_full', '==', false ),
				),
				array(
					'id'    => 'page_extra_class',
					'type'  => 'text',
					'title' => esc_html__( 'Extra Class', 'lebe-toolkit' ),
				),
			),
		),
	
	),
);
// -----------------------------------------
// Post Side Meta box Options              -
// -----------------------------------------

CSFramework_Metabox::instance( $options );
