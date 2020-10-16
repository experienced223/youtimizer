<?php

extract( shortcode_atts( array(
	'tab_id'	=> '',
	'active'=> '',
	'class' => ''
), $atts) );

$classes = array( 'tab-content' );
if ( $active == 'true' ) {
	$classes[] = 'active';
}
if ( $class != '' )  {
	$classes[] = $class;
}
echo sprintf( '<div id="%s" class="%s"><div class="tab-pane">%s</div></div>',
	esc_attr( $tab_id ),
	esc_attr( implode(' ', $classes) ),
	wpb_js_remove_wpautop( $content )
);