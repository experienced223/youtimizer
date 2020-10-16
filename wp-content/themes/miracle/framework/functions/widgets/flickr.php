<?php

/**
 * Flickr widget
 */

if ( ! class_exists( 'MiracleFlickrWidget') ) :
class MiracleFlickrWidget extends WP_Widget {

  function __construct() {
    $widget_ops  = array( 'classname' => 'miracle-flickr-widget', 'description' =>  __( 'Display your latest Flickr photos', LANGUAGE_ZONE ) );
    $control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'miracle-flickr-widget' );
    parent::__construct( 'miracle-flickr-widget', __( 'Miracle Flickr Widget', LANGUAGE_ZONE ), $widget_ops, $control_ops );
  }

  function widget( $args, $instance ) {
    extract( $args );
    $flickr_title = apply_filters( 'widget_title', $instance['flickr_title'] );
    $flickr_id    = $instance['flickr_id'];
    $flickr_count = $instance['flickr_count'];
    echo wp_kses_post( $before_widget );
    ?>

    <div class="flickr-widget">
      <?php if ( $flickr_title ) {
       echo "<h4>" . $before_title . $flickr_title . $after_title . "</h4>"; 
      } ?>
      <div class="miracle-flickr-wrapper">
        <ul id="flickr-<?php echo esc_attr( $args['widget_id'] ); ?>" class="flickr-feeds column-3">
        <?php

        if ( $flickr_id != '' ) {

          $images      = array();
          $regx        = "/<img(.+)\/>/";
          $rss_url     = 'http://api.flickr.com/services/feeds/photos_public.gne?ids=' . $flickr_id . '&lang=en-us&format=rss_200';
          $flickr_feed = simplexml_load_file( $rss_url );

          foreach( $flickr_feed->channel->item as $item ) {
            preg_match( $regx, $item->description, $matches );
            $images[] = array(
              'link'  => $item->link,
              'thumb' => $matches[ 0 ]
            );
          }

          $image_count = 0;
          if ( $flickr_count == '' ) $flickr_count = 5;

          foreach( $images as $img ) {
            if ( $image_count < $flickr_count ) {
              $img_tag = str_replace( '_m', '_b', $img[ 'thumb' ] );
              echo '<li><a href="' . $img[ 'link' ] . '">' . $img_tag . '</a></li>';
              $image_count++;
            }
          }

        }

        ?>
        </ul>
      </div>
    </div>
    <?php
    echo wp_kses_post( $after_widget );
  }

  function update( $new_instance, $old_instance ) {
    $instance                 = $old_instance;
    $instance['flickr_title'] = strip_tags( $new_instance['flickr_title'] );
    $instance['flickr_id']    = strip_tags( $new_instance['flickr_id'] );
    $instance['flickr_count'] = strip_tags( $new_instance['flickr_count'] );
    return $instance;
  }

  function form( $instance ) {
    $idgettr = 'http://idgettr.com/';
    ?>

    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', LANGUAGE_ZONE ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'flickr_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_title' ) ); ?>" value="<?php if ( isset( $instance['flickr_title'] ) ) echo esc_attr( $instance['flickr_title'] ); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>"><?php _e( 'Your Flickr User ID:', LANGUAGE_ZONE ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_id' ) ); ?>" value="<?php if ( isset( $instance['flickr_id'] ) ) echo esc_attr( $instance['flickr_id'] ); ?>" />
      <small>Not sure what to put here? Try <a href="<?php echo esc_url( $idgettr ); ?>" target="_blank">idGettr</a>.</small>
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'flickr_count' ) ); ?>"><?php _e( 'No. of Photos:', LANGUAGE_ZONE ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'flickr_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_count' ) ); ?>" value="<?php if ( isset( $instance['flickr_count'] ) ) echo esc_attr( $instance['flickr_count'] ); ?>" />
    </p>

    <?php
  }
}
endif;


/* Register Widget */
add_action( 'widgets_init', 'miracle_load_flickr_widget' );

function miracle_load_flickr_widget() {
  register_widget( 'MiracleFlickrWidget' );
}