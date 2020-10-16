<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

$attachment_ids = $product->get_gallery_attachment_ids();

?>
<div class="product-images">
<?php
	if ( $attachment_ids ) {
?>
	<div id="sync1_<?php echo get_the_ID(); ?>" class="owl-carousel images">
	<?php
		foreach ( $attachment_ids as $attachment_id ) {

			$image_title = esc_attr( get_the_title( $attachment_id ) );
			$image_link  = wp_get_attachment_url( $attachment_id );
			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );

			printf( '<div class="item easyzoom easyzoom--overlay"><a href="%s" itemprop="image">%s</a></div>', $image_link, $image );
		}
	?>
	</div>
<?php
	} else if ( has_post_thumbnail() ) {
		$image_title 	= esc_attr( get_the_title( get_post_thumbnail_id() ) );
		$image_link  	= wp_get_attachment_url( get_post_thumbnail_id() );
		$image       	= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
			'title'	=> $image_title,
			'alt'	=> $image_title
			) );

		$gallery = '';

		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="item easyzoom easyzoom--overlay"><a href="%s" itemprop="image" title="%s">%s</a></div>', $image_link, $image_title, $image ), $post->ID );
	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="item"><img src="%s" alt="%s" /></div>', wc_placeholder_img_src(), __( 'Placeholder', LANGUAGE_ZONE ) ), $post->ID );
	}
?>
	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

</div>
