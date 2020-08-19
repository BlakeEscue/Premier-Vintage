<?php

class lebe_newsletter_widget extends WP_Widget {
	function __construct() {
		/* Widget settings. */
		$widget_ops = array(
			'classname'   => 'lebe_newsletter_widget',
			'description' => esc_html__( 'A widget that displays your newsletter', 'lebe' )
		);
		/* Create the widget. */
		parent::__construct( 'lebe_newsletter_widget', esc_html__( 'Lebe: Newsletter', 'lebe' ), $widget_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title       = apply_filters( 'widget_title', $instance['title'] );
		$placeholder = $instance['placeholder'];
		$desc        = $instance['desc'];
		$submit      = $instance['submit'];
		$css_class   = array( 'widget-lebe-newsletter' );
		echo balanceTags( $before_widget );
		?>
		<?php if ( $title ) : ?>
            <h2 class="widgettitle"><?php echo esc_attr( $title ); ?></h2>
		<?php endif; ?>
        <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
            <div class="newsletter-content">
                <div class="header-newsletter">
					<?php if ( $desc ): ?>
                        <div class="newsletter-subtitle"><?php echo esc_attr( $desc ); ?></div>
					<?php endif; ?>
                </div>
                <form class="newsletter-form-wrap">
                    <input class="email" type="email" name="email"
                           placeholder="<?php echo esc_attr( $placeholder ); ?>">
                    <button type="submit" name="submit_button"
                            class="btn-submit submit-newsletter"><?php echo esc_attr( $submit ); ?> <i
                                class="fa fa-caret-right"></i></button>
                </form>
            </div>
        </div>
		<?php
		echo balanceTags( $after_widget );
	}
	
	function update( $new_instance, $old_instance ) {
		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['desc']        = strip_tags( $new_instance['desc'] );
		$instance['placeholder'] = $new_instance['placeholder'];
		$instance['submit']      = strip_tags( $new_instance['submit'] );
		
		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array(
			'title'       => esc_html__( 'Newsletter', 'lebe' ),
			'desc'        => '',
			'placeholder' => esc_html__( 'Your email address...', 'lebe' ),
			'submit'      => esc_html__( 'Submit', 'lebe' )
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$desc_value = $instance['desc'];
		$desc_field = array(
			'id'    => $this->get_field_name( 'desc' ),
			'name'  => $this->get_field_name( 'desc' ),
			'type'  => 'textarea',
			'title' => esc_html__( 'Description: ', 'lebe' ),
		);
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'lebe' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   value="<?php echo balanceTags( $instance['title'] ); ?>"/>
        </p>
		<?php
		echo '<p>';
		echo function_exists( 'cs_add_element' ) ? cs_add_element( $desc_field, $desc_value ) : '';
		echo '</p>';
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"><?php esc_html_e( 'Text placeholder:', 'lebe' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>"
                   value="<?php echo esc_html( $instance['placeholder'] ); ?>"/>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>"><?php esc_html_e( 'Text submit:', 'lebe' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'submit' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'submit' ) ); ?>"
                   value="<?php echo esc_html( $instance['submit'] ); ?>"/>
        </p>
		<?php
	}
}

add_action( 'widgets_init', 'lebe_newsletter_widget_init' );
function lebe_newsletter_widget_init() {
	register_widget( 'lebe_newsletter_widget' );
}