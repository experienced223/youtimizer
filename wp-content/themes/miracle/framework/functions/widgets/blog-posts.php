<?php
/**
 * Blog Posts widget
 */

if ( ! class_exists( 'MiracleBlogPostsWidget') ) :
class MiracleBlogPostsWidget extends WP_Widget {

	/* Widget defaults */
	public static $widget_defaults = array( 
		'title'     => '',
		'order'     => 'DESC',
		'orderby'   => 'date',
		'count'      => 3,
		'category'  => ''
    );

    function __construct() {  
        $widget_ops = array( 'description' => _x( 'Miracle Recent Posts', 'widget', LANGUAGE_ZONE ) );

		parent::__construct(
            'miracle-blog-posts-widget',
            _x( 'Miracle Recent Posts', 'widget', LANGUAGE_ZONE ),
            $widget_ops
        );
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		extract( $args );

		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		$title = apply_filters( 'widget_title', $instance['title'] );

		$category = $instance['category'];
		if ( !empty( $category ) ) {
			$category = str_replace(PHP_EOL, ',', $category);
		}

		$post_args = $instance;
		$post_args['category'] = $category;

		global $post;
		$html = '';

		$the_query = miracle_shortcode_query_posts( $post_args );
		if ( $the_query->have_posts() ) {
			$html .= '<ul class="recent-posts">';
			while ( $the_query->have_posts() ) { $the_query->the_post();
				$html .= '<li>';
				$html .= '<a href="' . get_permalink() . '" class="post-author-avatar"><span>';
				if ( has_post_thumbnail() ) {
					$html .= get_the_post_thumbnail( $post->ID, 'widget' );
				} else {
					$html .= '<img src="' . MIRACLE_IMAGE_URL . '/no-thumb.jpg" width="60" height="60" alt="">';
				}
				$html .= '</span></a>';
				$html .= '<div class="post-content">';
				$html .= '<a href="' . get_permalink() . '" class="post-title">' . get_the_title() . '</a>';
				$html .= '<p class="post-meta">' . _x( 'By', 'widget', LANGUAGE_ZONE ) . ' <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a>';
				$miracle_option_blog_timeline_post_date_format = miracle_get_option('blog_timeline_post_date_format');
				$html .= '  .  ' . get_the_date( $miracle_option_blog_timeline_post_date_format );
				$html .= '</p>';
				$html .= '</div>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			wp_reset_postdata();
		}

		echo wp_kses_post( $before_widget );

		if ( $title ) echo wp_kses_post( $before_title . $title . $after_title );
		echo wp_kses_post( $html );

		echo wp_kses_post( $after_widget );

	}

	function update( $new, $old ) {
		$instance = $old;

		$instance['title']    = strip_tags( $new['title'] );
		$instance['order']    = esc_attr( $new['order'] );
		$instance['orderby']  = esc_attr( $new['orderby'] );
		$instance['category'] = esc_attr( $new['category'] );
		$instance['count']    = esc_attr( $new['count'] );

		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, self::$widget_defaults );
		$orderby_list = array(
			'ID'        => _x( 'Order by ID', 'widget', LANGUAGE_ZONE ),
			'author'    => _x( 'Order by author', 'widget', LANGUAGE_ZONE ),
			'title'     => _x( 'Order by title', 'widget', LANGUAGE_ZONE ),
			'date'      => _x( 'Order by date', 'widget', LANGUAGE_ZONE ),
			'modified'  => _x( 'Order by modified', 'widget', LANGUAGE_ZONE ),
			'rand'      => _x( 'Order by rand', 'widget', LANGUAGE_ZONE ),
			'menu_order'=> _x( 'Order by menu', 'widget', LANGUAGE_ZONE )
		);

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_ZONE); ?></label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _ex('Category Names:', 'widget',  LANGUAGE_ZONE); ?></label>
			<textarea id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"  class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>"><?php echo esc_attr( $instance['category'] ); ?></textarea>
			<small><?php _ex( 'If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter) .', 'widget', LANGUAGE_ZONE ); ?></small>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _ex('Number of posts:', 'widget', LANGUAGE_ZONE); ?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr($instance['count']); ?>" size="2" maxlength="2" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php _ex('Sort by:', 'widget', LANGUAGE_ZONE); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
				<?php foreach( $orderby_list as $value=>$name ): ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['orderby'], $value ); ?>><?php echo esc_html( $name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		</p>
			<label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" value="ASC" type="radio" <?php checked( $instance['order'], 'ASC' ); ?> /><?php _ex('Ascending', 'widget', LANGUAGE_ZONE); ?>
			</label>
			<label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" value="DESC" type="radio" <?php checked( $instance['order'], 'DESC' ); ?> /><?php _ex('Descending', 'widget', LANGUAGE_ZONE); ?>
			</label>
		</p>

	<?php
	}
}
endif;

/* Register Widget */
add_action( 'widgets_init', 'miracle_load_blog_posts_widget' );

function miracle_load_blog_posts_widget() {
	register_widget( 'MiracleBlogPostsWidget' );
}