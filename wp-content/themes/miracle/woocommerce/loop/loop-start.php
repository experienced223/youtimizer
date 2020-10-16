<?php
/**
 * WOOCOMMERCE/LOOP/LOOP-START.PHP
 */
$class = 'products';
global $woocommerce_loop;
if ( is_shop() || is_product_category() || is_product_tag() || is_page_template( 'template-shop-list-style.php' ) ) {
	$class .= miracle_get_option('shop_layout', 'grid') == 'list' || is_page_template( 'template-shop-list-style.php' ) ? ' layout-list' : ' cols-' . miracle_get_option('shop_columns');
} else if ( isset( $woocommerce_loop['columns'] ) ) {
	$class .= ' cols-' . esc_attr( $woocommerce_loop['columns'] );
}
?>
<ul class="<?php echo esc_attr( $class ); ?>">