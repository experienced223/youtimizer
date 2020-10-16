<?php
$variables = array( 'style'=>'style1', 'active_tab_index' => '1', 'class'=>'', 'img_id'=>'', 'has_full_width' => '', 'tab_id' => '' );
extract( shortcode_atts( $variables, $atts ) );

$result = '';

preg_match_all( '/\[vc_tab(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tab_titles = array();

if ( isset( $matches[0] ) ) {
	$tab_titles = $matches[0];
}
if ( count( $tab_titles ) ) {
	if ( ( $style == 'transparent-tab' ) && ( ! empty( $img_id ) ) ) {
		$attachments = miracle_get_attachment_post_data( array( $img_id ) );
		$img_alt = " alt='" . $attachments[0]['alt'] . "'";
		$img_width = " width='" . $attachments[0]['width'] . "'";
		$img_height = " height='" . $attachments[0]['height'] . "'";
		$result .= '<div class="image-container"><img src="' . esc_url( $attachments[0]['full'] ) . '"' . esc_html( $img_alt . $img_width . $img_height ) . '/></div>';
	}

	$classes = array( 'tab-container', 'clearfix', $style );
	if ( !empty( $has_full_width ) ) {
		$classes[] = 'full-width';
	}
	if ( $class != '' ) {
		$classes[] = $class;
	}

	$result .= sprintf( '<div class="%s"><ul class="tabs clearfix">', esc_attr( implode(' ', $classes) ) );
	$uid = uniqid();
	foreach ( $tab_titles as $i => $tab ) {
		preg_match( '/title="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
		if ( isset( $tab_matches[1][0] ) ) {
			$active_class = '';
			$active_attr = '';
			if ( $active_tab_index - 1 == $i ) {
				$active_class = ' class="active"';
				$active_attr = ' active="true"';
			}

			preg_match( '/ icon_class="([^\"]+)"/i', $tab[0], $icon_matches, PREG_OFFSET_CAPTURE );
			$icon_html = '';
			if ( !empty( $icon_matches[1][0] ) ) {
				$icon_html = sprintf( '<i class="%s"></i>', esc_attr( $icon_matches[1][0] ) );
			}

			preg_match( '/ tab_id="([^\"]+)"/i', $tab[0], $tab_id_matches, PREG_OFFSET_CAPTURE );
			$tid = '';
			if ( !empty( $tab_id_matches[1][0] ) ) {
				$tid = esc_attr( $tab_id_matches[1][0] );
			}

			$result .= '<li '. $active_class . '><a href="#' . $tid . '" data-toggle="tab">' . $icon_html . esc_html( $tab_matches[1][0] ) . '</a></li>';
			$before_content = substr($content, 0, $tab[1]);
			$current_content = substr($content, $tab[1]);
			$current_content = preg_replace('/\[vc_tab/', '[vc_tab' . $active_attr, $current_content, 1);
			$content = $before_content . $current_content;
		}
	}
	$result .= '</ul>';
	$result .= wpb_js_remove_wpautop( $content );
	$result .= '</div>';
} else {
	$result .= wpb_js_remove_wpautop( $content );
}

echo miracle_html_filter( $result );