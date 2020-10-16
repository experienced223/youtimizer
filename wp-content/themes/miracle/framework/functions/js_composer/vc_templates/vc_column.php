<?php
$output = $font_color = $el_class = $width = $offset = '';
extract(shortcode_atts(array(
	'font_color'      => '',
    'el_class' => '',
    'width' => '1/1',
    'css' => '',
	'offset' => '',
	'animation_type'=> '',
	'animation_delay' => '',
	'animation_duration' => ''
), $atts));

$el_class = $this->getExtraClass($el_class);
$width = wpb_translateColumnWidthToSpan($width);
$width = vc_column_offset_class_merge($offset, $width);
$el_class .= ' wpb_column vc_column_container';
$style = $this->buildStyle( $font_color );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$column_attrs = '';
if ( !empty( $animation_type ) ) {
    $css_class .= ' animated';
    $column_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
    if ( !empty( $animation_duration ) )  {
        $column_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
    }
    if ( !empty( $animation_delay ) )  {
        $column_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
    }
}

$output .= "\n\t".'<div class="'.$css_class.'"'.$style.$column_attrs.'>';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment($el_class) . "\n";

echo miracle_html_filter( $output );