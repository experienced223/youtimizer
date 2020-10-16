<?php
/*
 * Includes all shortcode functions for miracle theme
 */

function shortcode_init() {
	require_once 'shortcodes.php';
}

add_action( 'init', 'shortcode_init' );


function filter_eliminate_autop( $content ) {
	$content = do_shortcode( shortcode_unautop($content) );
	return $content;
}
//add_filter( 'the_content', 'filter_eliminate_autop', 8 );
//add_filter( 'widget_text', 'filter_eliminate_autop', 8 );

function miracle_clean_shortcodes( $content ) {
	$array = array (
	  '<p>['	=> '[',
	  ']</p>'   => ']',
	  ']<br />' => ']',
	);

	$content = strtr( $content, $array );
	$content = preg_replace( "/<br \/>.\[/s", "[", $content );
	return $content;
}
add_filter( 'the_content', 'miracle_clean_shortcodes' );

add_filter( 'widget_text', 'do_shortcode' );

require_once 'shortcode-generator.php';