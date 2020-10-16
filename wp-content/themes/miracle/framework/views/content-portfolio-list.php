<?php
/**
 * Output portfolio list
 */

$template_name = '';
if ( $style == 'masonry1' || $style == 'masonry2' || $style == 'masonry3' ) {
	$template_name = 'masonry';
} else {
	$template_name = $style;
}
set_query_var( 'post_classes', 'post' );
if ( $style === 'masonry3' ) {
	set_query_var( 'view_details', 'true' );
}
$classes = array( 'iso-item', 'filter-all' );

if ( !empty( $image_double_size ) ) {
	$classes[] = 'double-width';
}
ob_start();
miracle_get_template( 'portfolio', $template_name . '-content' );
$post_html = ob_get_contents();
ob_end_clean();

$terms = wp_get_object_terms( $post->ID, 'm_portfolio_category' );
foreach ( $terms as $term ) {
	$classes[] = 'filter-' . md5( $term->slug );
}
printf( '<div class="%s">%s</div>', implode( ' ', $classes ), $post_html );