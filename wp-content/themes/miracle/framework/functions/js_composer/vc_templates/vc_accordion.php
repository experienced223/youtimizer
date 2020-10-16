<?php
$output = $el_class = $active_tab = $toggle_type = $style = '';
//
extract(shortcode_atts(array(
    'toggle_type' => 'accordion',
    'style' => 'style1',
    'el_class' => '',
    'active_tab' => '1'
), $atts));

preg_match_all( '/\[vc_accordion_tab(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tabs_arr = array();
if ( isset( $matches[0] ) ) {
	$tabs_arr = $matches[0];
}

foreach ( $tabs_arr as $i => $tab ) {
	if ( $i === (int)$active_tab - 1 ) {
		$before_content = substr($content, 0, $tab[1]);
		$current_content = substr($content, $tab[1]);
		$current_content = preg_replace('/\[vc_accordion_tab/', '[vc_accordion_tab active="yes"' , $current_content, 1);
		$content = $before_content . $current_content;
	}
}

$uid = uniqid('miracle-tgg-');
if ( $toggle_type == 'accordion' ) {
	foreach ( $tabs_arr as $i => $tab ) {
		$before_content = substr($content, 0, $tab[1]);
		$current_content = substr($content, $tab[1]);
		$current_content = preg_replace('/\[vc_accordion_tab/', '[vc_accordion_tab parent_id="' . $uid . '"' , $current_content, 1);
		$content = $before_content . $current_content;
	}
}


$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim('panel-group ' . $style . ' ' . $el_class), $this->settings['base'], $atts );
$output .= "\n\t".'<div class="'. esc_attr( $css_class ) .'" id="' . $uid . '">';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t".'</div> ';

echo miracle_html_filter( $output );