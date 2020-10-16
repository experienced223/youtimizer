<?php
$output = $class = $active_tab = $toggle_type = $style = '';
//
extract(shortcode_atts(array(
    'class' => ''
), $atts));

$class = empty( $class )?'':( ' ' . $class );
$result = '<div class="container' . esc_attr( $class ) . '">';
$result .= wpb_js_remove_wpautop($content);
$result .= '</div>';

echo miracle_html_filter( $result );