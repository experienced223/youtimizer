<?php

/* Tweets Widget */

if ( ! class_exists( 'MiracleTweetsWidget') ) :
class MiracleTweetsWidget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'twitter-box', 'description' => '');
        $control_ops = array('id_base' => 'tweets-widget');
        parent::__construct('tweets-widget', _x( 'Miracle Twitter Widget', 'widget', LANGUAGE_ZONE ), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $consumer_key = empty($instance['consumer_key'])?'':$instance['consumer_key'];
        $consumer_secret = empty($instance['consumer_secret'])?'':$instance['consumer_secret'];
        $access_token = empty($instance['access_token'])?'':$instance['access_token'];
        $access_token_secret = empty($instance['access_token_secret'])?'':$instance['access_token_secret'];
        $twitter_id = empty($instance['twitter_id'])?'':$instance['twitter_id'];
        $count = empty($instance['count'])?'':(int) $instance['count'];

        echo wp_kses_post( $before_widget );

        if ( !empty( $title ) ) { echo wp_kses_post( $before_title . $title . $after_title ); }

        if($twitter_id && $consumer_key && $consumer_secret && $access_token && $access_token_secret && $count) {
        $transName = 'list_tweets_'.$args['widget_id'];
        $cacheTime = 10;
        if(false === ($twitterData = get_transient($transName))) {

            $token = get_option('cfTwitterToken');

            // getting new auth bearer only if we don't have one
            if(!$token) {
                // preparing credentials
                $credentials = $consumer_key . ':' . $consumer_secret;
                $toSend = base64_encode($credentials);

                // http post arguments
                $args = array(
                    'method' => 'POST',
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => 'Basic ' . $toSend,
                        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                    ),
                    'body' => array( 'grant_type' => 'client_credentials' )
                );

                add_filter('https_ssl_verify', '__return_false');
                $response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);

                $keys = json_decode(wp_remote_retrieve_body($response));

                if($keys) {
                    // saving token to wp_options table
                    update_option('cfTwitterToken', $keys->access_token);
                    $token = $keys->access_token;
                }
            }
            // we have bearer token wether we obtained it from API or from options
            $args = array(
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => "Bearer $token"
                )
            );

            add_filter('https_ssl_verify', '__return_false');
            $api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$twitter_id.'&count='.$count;
            $response = wp_remote_get($api_url, $args);
            $decoded_json = json_decode(wp_remote_retrieve_body($response), true);

            set_transient($transName, $decoded_json, 60 * $cacheTime);
        }
        $twitter = (array) get_transient($transName);
        if($twitter && is_array($twitter)) {
            //var_dump($twitter);
        ?>
        <div class="twitter-holder">
            <ul>
                <?php foreach($twitter as $tweet): ?>
                <li class="tweet">
                    <p class="tweet-text">
                        <!-- <a href="#">Miracle</a>,  -->
                        <?php
							if ( isset($tweet['text']) ) {
								$latestTweet = $tweet['text'];
								$latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet);
								$latestTweet = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet);
								echo wp_kses_post( $latestTweet );
							}
                        ?>
                    </p>
                    <a class="tweet-date" href="http://twitter.com/<?php echo esc_attr( $tweet['user']['screen_name'] ); ?>/statuses/<?php echo esc_attr( $tweet['id_str'] ); ?>">
                        <?php
							if ( isset($tweet['created_at']) ) {
								$twitterTime = strtotime($tweet['created_at']);
								$timeAgo = $this->ago($twitterTime);
								echo esc_html( $timeAgo );
							}
                        ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php }}

        echo wp_kses_post( $after_widget );
    }

    function ago($time) {
       $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
       $lengths = array("60","60","24","7","4.35","12","10");

       $now = time();

           $difference     = $now - $time;
           $tense         = "ago";

       for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
           $difference /= $lengths[$j];
       }

       $difference = round($difference);

       if($difference != 1) {
           $periods[$j].= "s";
       }

       return "$difference $periods[$j] ago ";
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['consumer_key'] = $new_instance['consumer_key'];
        $instance['consumer_secret'] = $new_instance['consumer_secret'];
        $instance['access_token'] = $new_instance['access_token'];
        $instance['access_token_secret'] = $new_instance['access_token_secret'];
        $instance['twitter_id'] = $new_instance['twitter_id'];
        $instance['count'] = $new_instance['count'];

        return $instance;
    }

    function form($instance) {
        $defaults = array( 
            'title' => 'Recent Tweets',
            'consumer_key'=>'',
            'consumer_secret'=>'',
            'access_token'=>'',
            'access_token_secret'=>'',
            'twitter_id' => '',
            'count' => 3 );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <p><a href="http://dev.twitter.com/apps"><?php echo __( 'Find or Create your Twitter App', LANGUAGE_ZONE ); ?></a></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e( 'Title', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('consumer_key') ); ?>"><?php _e( 'Consumer Key', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('consumer_key') ); ?>" name="<?php echo esc_attr( $this->get_field_name('consumer_key') ); ?>" value="<?php echo esc_attr( $instance['consumer_key'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('consumer_secret') ); ?>"><?php _e( 'Consumer Secret', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('consumer_secret') ); ?>" name="<?php echo esc_attr( $this->get_field_name('consumer_secret') ); ?>" value="<?php echo esc_attr( $instance['consumer_secret'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('access_token') ); ?>"><?php _e( 'Access Token', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('access_token') ); ?>" name="<?php echo esc_attr( $this->get_field_name('access_token') ); ?>" value="<?php echo esc_attr( $instance['access_token'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('access_token_secret') ); ?>"><?php _e( 'Access Token Secret', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('access_token_secret') ); ?>" name="<?php echo esc_attr( $this->get_field_name('access_token_secret') ); ?>" value="<?php echo esc_attr( $instance['access_token_secret'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('twitter_id') ); ?>"><?php _e( 'Twitter ID', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('twitter_id') ); ?>" name="<?php echo esc_attr( $this->get_field_name('twitter_id') ); ?>" value="<?php echo esc_attr( $instance['twitter_id'] ); ?>" />
        </p>

            <label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php _e( 'Number of Tweets', LANGUAGE_ZONE ); ?>:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>" value="<?php echo esc_attr( $instance['count'] ); ?>" />
        </p>

    <?php
    }
}
endif;

/* Register Widget */
add_action( 'widgets_init', 'miracle_load_tweet_widget' );

function miracle_load_tweet_widget() {
  register_widget( 'MiracleTweetsWidget' );
}