<?php
/**
 * Contact Info Widget
 */

if ( ! class_exists( 'MiracleContactInfoWidget') ) :
class MiracleContactInfoWidget extends WP_Widget {

	public static $widget_defaults = array(
		'title'       => '',
		'text'        => '',
		'show_social' => '1',
		'bottom_text' => ''
	);

	function __construct() {
		$widget_ops = array( 'description' => _x( 'Miracle Contact Info', 'widget', LANGUAGE_ZONE ) );

		parent::__construct(
			'miracle-contact-info-widget',
			_x( 'Miracle Contact Info', 'widget', LANGUAGE_ZONE ),
			$widget_ops
		);
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		extract( $args );
		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$title = apply_filters( 'widget_title', $instance['title'] );

		echo wp_kses_post( $before_widget );
		// title
		if ( $title ) echo wp_kses_post( $before_title . $title . $after_title );

		if ( $instance['text'] ) {
			echo '<p>' . esc_html( $instance['text'] ) . '</p>';
		}

		if ( $instance['show_social'] == 1 ) {
			echo '<div class="social-icons">';
			$social_links = miracle_get_social_site_names();
			foreach ( $social_links as $key => $name ) {
				$miracle_option_social_url = miracle_get_option('social_' . $key . '_url');
				if ( !empty( $miracle_option_social_url ) ) {
					echo '<a href="' . esc_url( $miracle_option_social_url ) . '" class="social-icon" target="_blank">';
					echo '<i class="fa fa-' . $key . ' has-circle" title="' . esc_attr( $name ) . '" data-toggle="tooltip" data-placement="top"></i>';
					echo '</a>';
				}
			}
			echo '</div>';
		}

		if ( $instance['bottom_text'] ) {
			echo stripslashes( htmlspecialchars_decode( esc_html( $instance['bottom_text'] ) ) );
		}

		echo wp_kses_post( $after_widget );

	}

	function update( $new, $old ) {
		$instance = $old;

		$instance['title']       = $new['title'];
		$instance['text']        = $new['text'];
		$instance['show_social'] = $new['show_social'];
		$instance['bottom_text'] = $new['bottom_text'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_ZONE); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_ZONE); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" rows="10" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>"><?php _ex('Show Social Links', 'widget', LANGUAGE_ZONE); ?>
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_social' ) ); ?>" value="1" <?php checked($instance['show_social']); ?> />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'bottom_text' ) ); ?>"><?php _ex('Bottom HTML:', 'widget',  LANGUAGE_ZONE); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'bottom_text' ) ); ?>" rows="10" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'bottom_text' ) ); ?>"><?php echo esc_textarea($instance['bottom_text']); ?></textarea>
		</p>

	<?php
	}
}
endif;

/* Register Widget */
add_action( 'widgets_init', 'miracle_load_contact_info_widget' );

function miracle_load_contact_info_widget() {
	register_widget( 'MiracleContactInfoWidget' );
}