<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $ziss;

$pin_data_str            = get_post_meta( $post->ID, 'ziss_pin_data', true );
$pin_data_str_encode_uri = get_post_meta( $post->ID, 'ziss_pin_data_encode_uri', true );

$pin_data_json   = array();
$used_imgs_class = '';
if ( trim( $pin_data_str ) != '' ) {
	$pin_data_json   = json_decode( $pin_data_str );
	$pin_data_str    = htmlentities2( $pin_data_str );
	$used_imgs_class .= 'has-img';
} else {
	if ( $pin_data_str_encode_uri != '' ) {
		$pin_data_json     = unserialize( $pin_data_str_encode_uri );
		$pin_data_json_tmp = array();
		
		if ( $pin_data_json ) {
			foreach ( $pin_data_json as $pin_data_json_item ) {
				$pin_data_json_tmp[] = ziss_array_to_object( $pin_data_json_item );
				if ( isset( $pin_data_json_item['instagram_data_encode_uri'] ) ) {
					// $pin_data_json   = json_decode( $pin_data_str );
				}
				
			}
		}
		$pin_data_json = $pin_data_json_tmp;
	}
}

//echo '<pre>';
//print_r( $pin_data_json );
//echo '</pre>';

?>

<div class="ziss-used-wrap">
    <textarea style="display: none;" name="social_shop_pin"><?php echo $pin_data_str; ?></textarea>
    <textarea style="display: none;" name="social_shop_pin_encode_uri"><?php echo $pin_data_str_encode_uri; ?></textarea>
    <div class="ziss-used-imgs <?php echo esc_attr( $used_imgs_class ); ?>">
        <div class="used-img-items row ziss-sortable">
			<?php if ( ! empty( $pin_data_json ) ) { ?>
				<?php foreach ( $pin_data_json as $pin_datas ) { ?>
					<?php
					$img_w = isset( $pin_datas->img_width ) ? intval( $pin_datas->img_width ) : '';
					$img_h = isset( $pin_datas->img_height ) ? intval( $pin_datas->img_height ) : '';
					
					$pin_data_html = '';
					$i             = 0;
					if ( isset( $pin_datas->pin_data ) ) {
						if ( $pin_datas->pin_data ) {
							foreach ( $pin_datas->pin_data as $pin_data ) {
								$i ++;
								$hotspot_top_percent  = $pin_data->top_percent;
								$hotspot_left_percent = $pin_data->left_percent;
								$hotspot_top_percent  = ( $pin_data->top_percent * $pin_datas->img_height + 50 * $pin_datas->img_width - 50 * $pin_datas->img_height ) / $pin_datas->img_width;
								
								if ( ziss_post_exist_by_id( $pin_data->product_id ) ) {
									$pin_data_html .= '<div class="ziss-hotspot-wrap" data-product_id="' . esc_attr( $pin_data->product_id ) . '" data-top_percent="' . esc_attr( $pin_data->top_percent ) . '" data-left_percent="' . esc_attr( $pin_data->left_percent ) . '" style="top: ' . esc_attr( $hotspot_top_percent ) . '%; left: ' . esc_attr( $hotspot_left_percent ) . '%;">
											<div data-hotspot_num="' . $i . '" class="hotspot-num ziss-cursor-default">
												<div class="ziss-hotspot-text">' . $i . '</div>
											</div>
										</div>';
								}
							}
						}
					}
					
					$instagram_img_id = isset( $pin_datas->instagram_id ) ? $pin_datas->instagram_id : '';
					$instagram_data   = isset( $pin_datas->instagram_data ) ? json_encode( $pin_datas->instagram_data ) : '';
					
					?>
                    <div class="used-img-item-wrap col-md-3">
                        <div class="used-img-item hover-zoom-img">
                            <figure data-pin_data="<?php echo htmlentities2( json_encode( $pin_datas->pin_data ) ); ?>"
                                    data-instagram_data="<?php echo htmlentities2( $instagram_data ); ?>"
                                    data-src="<?php echo esc_url( $pin_datas->img_src ); ?>"
                                    data-instagram_img_id="<?php echo esc_attr( $instagram_img_id ); ?>"
                                    data-width="<?php echo esc_attr( $img_w ); ?>"
                                    data-height="<?php echo esc_attr( $img_h ); ?>"
                                    data-social_source="<?php echo esc_attr( $pin_datas->social_source ); ?>"
                                    style="background-image: url(<?php echo esc_url( $pin_datas->img_src ); ?>);"></figure>
                            <a class="remove-img-item" href="#"
                                    data-src="<?php echo esc_url( $pin_datas->img_src ); ?>"
                                    title="Remove"><i class="fa fa-minus"></i></a>
	                        <?php echo $pin_data_html; ?>
                        </div>
                    </div>
				<?php } ?>
			<?php } ?>
        </div>
        <div class="updated no-img-message"><p><?php esc_html_e( 'No image chosen', 'ziss' ); ?></p></div>
    </div>
</div>
