<?php
$output = $title = $parent_id = $active = '';

extract(shortcode_atts(array(
	'title' => __("Section", LANGUAGE_ZONE),
	'parent_id' => '',
	'active' => ''
), $atts));

$class_in = ( $active === 'yes') ? ' in':'';
$class_collapsed = ( $active === 'yes') ? '' : ' class="collapsed"';

$accordion_attrs = "";
if ( !empty( $parent_id ) ) {
	$accordion_attrs = ' data-parent="#' . $parent_id . '"';
}
$uid = uniqid("miracle-tg");
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'panel', $this->settings['base'], $atts );
$output .= "\n\t\t\t" . '<div class="'.$css_class.'">';
    $output .= "\n\t\t\t\t" . '<h5 class="panel-title"><a href="#'.$uid.'" data-toggle="collapse"' . $class_collapsed . $accordion_attrs . '>'.$title.'<span class="open-sub"</span></a></h5>';
    $output .= "\n\t\t\t\t" . '<div id="' . $uid . '" class="panel-collapse collapse' . $class_in . '">';
    	$output .= "\n\t\t\t\t\t" . '<div class="panel-content">';
        $output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", LANGUAGE_ZONE) : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
        $output .= "\n\t\t\t\t\t" . '</div>';
        $output .= "\n\t\t\t\t" . '</div>';
    $output .= "\n\t\t\t" . '</div> ' . "\n";

echo miracle_html_filter( $output );