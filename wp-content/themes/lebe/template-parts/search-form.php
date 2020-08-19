<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_type                     = class_exists( 'WooCommerce' ) ? 'product' : '';
$enable_instant_product_search = lebe_get_option( 'enable_instant_product_search', false );
$search_form_class             = 'instant-search';
if ( ! $enable_instant_product_search ) {
	$post_type = '';
}

if ( $post_type != 'product' ) {
	$search_form_class .= ' instant-search-disabled';
}

?>
<div class="search-block">
    <div class="search-icon"><span class="flaticon-magnifying-glass-browser icon"></span></div>
    <form autocomplete="off" method="get" class="search-form <?php echo esc_attr( $search_form_class ); ?>"
          action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <div class="search-fields">
            <div class="search-input">
                <span class="reset-instant-search-wrap"></span> 
                <input type="search" class="search-field"
                       placeholder="<?php echo esc_attr__( 'Searching for ...', 'lebe' ); ?>" value="" name="s">
				<?php if ( $post_type != '' ) { ?>
                    <input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
				<?php } ?>
                <button type="submit" class="search-submit"><span class="flaticon-magnifying-glass-browser"></span>
                </button>
                <input type="hidden" name="lang" value="en">
                <div class="search-results-container search-results-croll scrollbar-macosx">
                    <div class="search-results-container-inner">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>