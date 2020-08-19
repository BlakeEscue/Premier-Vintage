<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$single_id            = lebe_get_single_page_id();
$meta_data            = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
$enable_custom_header = false;
if ( $single_id > 0 && isset( $meta_data['enable_custom_header'] ) ) {
	$enable_custom_header = $meta_data['enable_custom_header'];
}
$enable_topbar = false;
$topbar_text   = '';
if ( $enable_custom_header ) {
	$enable_topbar       = $meta_data['enable_topbar'];
	$topbar_text         = $meta_data['topbar-text'];
    $background_color_topbar_text   = $meta_data['background_color_topbar_text'];
} else {
	$enable_topbar       = lebe_get_option( 'enable_topbar', false );
	$topbar_text         = lebe_get_option( 'topbar-text', '' );
    $background_color_topbar_text   = lebe_get_option( 'background_color_topbar_text', '' );
}

?>

<?php if ( $enable_topbar && trim( $topbar_text ) != '' ): ?>
    <div class="header-topbar" style="background-color: <?php echo esc_attr($background_color_topbar_text); ?>;">
        <div class="header-container container">
            <?php echo esc_attr( $topbar_text ); ?>
            <span class="close-notice"></span>
        </div>
    </div>
<?php endif; ?>