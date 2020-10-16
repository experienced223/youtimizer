<?php
/**
 * Add action for content section
 */

if ( ! function_exists( 'miracle_blog_title' ) ) :

	/**
	 * Display blog title.
	 */
	function miracle_blog_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}
		
		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title = "$title $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title = "$title $sep " . sprintf( __( 'Page %s', LANGUAGE_ZONE ), max( $paged, $page ) );
		}

		return $title;
	}
	if ( floatval( get_bloginfo( 'version' ) ) < 4.1 ) {
		add_filter( 'wp_title', 'miracle_blog_title', 10, 2 );
	}
endif;


// comments
if ( ! function_exists( 'miracle_comment_form_before_fields' ) ) :
	function miracle_comment_form_before_fields( $fields ) {
		if ( get_post_type() != 'product' ) { 
			echo '<div class="row">';
		}
	}
endif;
if ( ! function_exists( 'miracle_comment_form_after_fields' ) ) :
	function miracle_comment_form_after_fields( $fields ) {
		if ( get_post_type() != 'product' ) { 
			echo '</div>';
		}
	}
endif;
add_action( 'comment_form_before_fields', 'miracle_comment_form_before_fields' );
add_action( 'comment_form_after_fields', 'miracle_comment_form_after_fields' );

// excerpt
if ( ! function_exists( 'miracle_excerpt_length' ) ) :
	function miracle_excerpt_length( $length ) {

		return miracle_get_option( 'blog_excerpt_length', 40 );
	}
	add_filter( 'excerpt_length', 'miracle_excerpt_length' );
endif;

// excerpt more string
if ( ! function_exists( 'miracle_excerpt_string' ) ) :
	function miracle_excerpt_string( $more ) {

		return ' ...';
	}
	add_filter( 'excerpt_more', 'miracle_excerpt_string' );
endif;

// content more string
if ( ! function_exists( 'miracle_content_string' ) ) :
	function miracle_content_string( $more ) {

		return '<a href="' . get_permalink() . '" class="more-link">' . __( 'Read More', LANGUAGE_ZONE ) . '</a>';
	}
	add_filter( 'the_content_more_link', 'miracle_content_string' );
endif;

// Ouputs Generated CSS
function miracle_output_generated_css() {

	ob_start();
	echo '<style id="miracle-generated-css-output" type="text/css">';

	require_once( MIRACLE_FUNC_PATH . '/enqueue/internal-styles.php' );

	do_action( 'miracle_head_css' );

	echo '</style>';

	$css = ob_get_contents(); ob_end_clean();

	$output = preg_replace( '#/\*.*?\*/#s', '', $css );
	$output = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $output );
	$output = preg_replace( '/\s\s+(.*)/', '$1', $output );

	echo ( $output );

}
add_action( 'wp_head', 'miracle_output_generated_css', 9998, 0 );

/* Outputs Custom Javascript */
function miracle_output_custom_javascript() {
	$custom_scripts = miracle_get_option( 'custom_javascript', '' );
	if ( !empty( $custom_scripts ) ) {
		echo '<script>' . $custom_scripts . '</script>';
	}
}
add_action( 'wp_footer', 'miracle_output_custom_javascript', 9999, 0 );

/* Tags Widget */
function miracle_change_tag_cloud( $return, $args ) {
	$wrap = '<div class="tags">%s</div>';
	$return = str_replace('tag-link-', 'tag tag-link-', $return);
	return sprintf( $wrap, $return );
}
add_filter( 'wp_tag_cloud', 'miracle_change_tag_cloud', 10, 2 );

/* ULike */
remove_filter( 'the_content', 'wp_ulike_put_posts' );
remove_filter( 'comment_text', 'wp_ulike_put_comments' );

/* header carousel */
function miracle_display_carousel_in_header() {
	global $post;
	if ( is_singular() ) {
		preg_match( '!\[carousel(.*?) show_on_header="yes".+?\]!', $post->post_content, $start_matches, PREG_OFFSET_CAPTURE );
		preg_match( '!\[\/carousel\]!', $post->post_content, $end_matches, PREG_OFFSET_CAPTURE );
		$carousel_shortcode = '';
		if ( isset( $start_matches[0] ) && isset( $end_matches[0] ) ) {
			$carousel_shortcode = substr( $post->post_content, $start_matches[0][1], $end_matches[0][1] - $start_matches[0][1] + 11 );
			echo do_shortcode( $carousel_shortcode );
			$post->post_content = str_replace( $carousel_shortcode, '', $post->post_content );
		}
	}
}
add_action( 'miracle_display_slider_html', 'miracle_display_carousel_in_header' );

/* flush rewrite rules */
function miracle_flush_rewrite_rules() {

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'miracle_flush_rewrite_rules' );

/* wordpress default shortcodes */
function miracle_shortcode_gallery( $gallery_html, $atts ) {
	extract( shortcode_atts( array(
		'columns' => '3',
		'mode' => 'slideshow',
		'ids' => '',
		'class' => ''
	), $atts ) );

	if ( empty( $atts['mode'] ) ) {
		return $gallery_html;
	}

	if ( $mode == 'slideshow' ) {
		if ( empty( $ids ) ) {
			$ids = array();
		} else {
			$ids = explode( ',', $ids );
		}
		$image_size = MiracleHelper::get_thumbnail_size( 1 );
		$attachments = miracle_get_attachment_post_data( $ids, $image_size );
		ob_start();
		miracle_get_post_gallery( $attachments, '', false );
		$result = ob_get_contents();
		ob_end_clean();
	} else if ( $mode == 'metro' ) {
		$result = do_shortcode( '[image_gallery ids="' . $ids . '" mode="metro2" columns="' . $columns . '"]' );
	}
	return $result;
}
add_filter( 'post_gallery', 'miracle_shortcode_gallery', 10, 2 );


function miracle_shortcode_audio( $audio_html, $atts, $content, $instances ) {
	return $audio_html;
}
add_filter( 'wp_audio_shortcode_override', 'miracle_shortcode_audio', 10, 4 );