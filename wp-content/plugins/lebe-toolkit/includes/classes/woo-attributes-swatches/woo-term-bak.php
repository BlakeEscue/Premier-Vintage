<?php

if ( !class_exists( 'Lebe_Term' ) ) {
	class Lebe_Term
	{

		public $attribute_meta_key;
		public $term_id;
		public $term;
		public $term_label;
		public $term_slug;
		public $taxonomy_slug;
		public $selected;
		public $type;
		public $color;
		public $thumbnail_src;
		public $thumbnail_id;
		public $size;
		public $width  = 40;
		public $height = 40;

		public function __construct( $attribute_data_key, $term_id, $taxonomy, $selected = false, $size = 'swatches_image_size' )
		{

			$this->attribute_meta_key = $attribute_data_key;
			$this->term_id            = $term_id;
			$this->term               = get_term( $term_id, $taxonomy );
			$this->term_label         = $this->term->name;
			$this->term_slug          = $this->term->slug;

			$this->taxonomy_slug = $taxonomy;
			$this->selected      = $selected;
			$this->size          = $size;

			$this->on_init();
		}

		public function on_init()
		{
			global $woocommerce, $_wp_additional_image_sizes;

			$this->init_size( $this->size );

			$type = get_term_meta( $this->term_id, $this->meta_key() . '_type', true );

			$color               = get_term_meta( $this->term_id, $this->meta_key() . '_color', true );
			$this->thumbnail_id  = get_term_meta( $this->term_id, $this->meta_key() . '_photo', true );
			$this->thumbnail_src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
			$this->color         = '#FFFFFF';
			$this->type          = $type;
			if ( $type == 'photo' ) {
				if ( $this->thumbnail_id ) {
					$imgsrc = wp_get_attachment_image_src( $this->thumbnail_id, $this->size );
					if ( $imgsrc && is_array( $imgsrc ) ) {
						$this->thumbnail_src = current( $imgsrc );
					} else {
						$this->thumbnail_src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
					}
				} else {
					$this->thumbnail_src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
				}
			} elseif ( $type == 'color' ) {
				$this->color = $color;
			}
		}

		public function init_size( $size )
		{
			global $woocommerce, $_wp_additional_image_sizes;
			$this->size = $size;
			$the_size   = isset( $_wp_additional_image_sizes[ $size ] ) ? $_wp_additional_image_sizes[ $size ] : '';
			if ( isset( $the_size[ 'width' ] ) && isset( $the_size[ 'height' ] ) ) {
				$this->width  = $the_size[ 'width' ];
				$this->height = $the_size[ 'height' ];
			} else {
				$this->width  = 40;
				$this->height = 40;
			}
		}

		public function get_output( $placeholder = true, $placeholder_src = 'default' )
		{
			global $woocommerce;

			$picker = '';

			$href         = apply_filters( 'woocommerce_swatches_get_swatch_href', '#', $this );
			$anchor_class = apply_filters( 'woocommerce_swatches_get_swatch_anchor_css_class', 'swatch-anchor', $this );
			$image_class  = apply_filters( 'woocommerce_swatches_get_swatch_image_css_class', 'swatch-img', $this );
			$image_alt    = apply_filters( 'woocommerce_swatches_get_swatch_image_alt', 'thumbnail', $this );

			if ( $this->type == 'photo' || $this->type == 'image' ) {
				$picker .= '<a href="' . $href . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;" title="' . $this->term_label . '" class="' . $anchor_class . '">';
				$picker .= '<img src="' . apply_filters( 'woocommerce_swatches_get_swatch_image', $this->thumbnail_src, $this->term_slug, $this->taxonomy_slug, $this ) . '" alt="' . $image_alt . '" class="wp-post-image swatch-photo' . $this->meta_key() . ' ' . $image_class . '" width="' . $this->width . '" height="' . $this->height . '"/>';
				$picker .= '</a>';
			} elseif ( $this->type == 'color' ) {
				$picker .= '<a href="' . $href . '" style="font-size:0px; display:block;width:' . $this->width . 'px;height:' . $this->height . 'px;background-color:' . apply_filters( 'woocommerce_swatches_get_swatch_color', $this->color, $this->term_slug, $this->taxonomy_slug, $this ) . ';" title="' . $this->term_label . '" class="' . $anchor_class . '">' . $this->term_label . '</a>';
			} elseif ( $placeholder ) {
				if ( $placeholder_src == 'default' ) {
					$src = $woocommerce->plugin_url() . '/assets/images/placeholder.png';
				} else {
					$src = $placeholder_src;
				}

				$picker .= '<a href="' . $href . '" style="width:' . $this->width . 'px;height:' . $this->height . 'px;" title="' . $this->term_label . '"  class="' . $anchor_class . '">';
				$picker .= '<img src="' . $src . '" alt="' . $image_alt . '" class="wp-post-image swatch-photo' . $this->meta_key() . ' ' . $image_class . '" width="' . $this->width . '" height="' . $this->height . '"/>';
				$picker .= '</a>';
			} else {
				return '';
			}

			$out = '<div class="select-option swatch-wrapper" data-name="' . esc_attr( $this->term_label ) . '" data-value="' . md5( $this->term_slug ) . '" ' . ( $this->selected ? 'data-default="true"' : '' ) . '>';
			$out .= apply_filters( 'woocommerce_swatches_picker_html', $picker, $this );
			$out .= '</div>';

			return $out;
		}

		public function get_type()
		{
			return $this->type;
		}

		public function get_color()
		{
			return $this->color;
		}

		public function get_image_src()
		{
			return $this->thumbnail_src;
		}

		public function get_image_id()
		{
			return $this->thumbnail_id;
		}

		public function get_width()
		{
			return $this->width;
		}

		public function get_height()
		{
			return $this->height;
		}

		public function meta_key()
		{
			return $this->taxonomy_slug . '_' . $this->attribute_meta_key;
		}

	}
}
