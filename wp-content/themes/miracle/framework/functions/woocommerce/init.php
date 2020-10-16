<?php

/**
 * WooCommerce configuration
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Image Sizes.
GLOBAL $pagenow;

function miracle_woocommerce_image_dimensions() {
  $catalog = array(
    'width'  => '450',
    'height' => '500',
    'crop'   => 1
  );

  $single = array(
    'width'  => '862',
    'height' => '9999',
    'crop'   => 0
  );

  $thumbnail = array(
    'width'  => '154',
    'height' => '175',
    'crop'   => 1
  );

  update_option( 'shop_catalog_image_size', $catalog );
  update_option( 'shop_single_image_size', $single );
  update_option( 'shop_thumbnail_image_size', $thumbnail );
  update_option( 'woocommerce_single_image_crop', 'no' );
}

if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
	add_action( 'init', 'miracle_woocommerce_image_dimensions', 1 );
}

// main content
add_action( 'after_setup_theme', 'miracle_add_woocommerce_support', 16 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'miracle_woocommerce_before_main_content', 10 );
add_action( 'woocommerce_after_main_content', 'miracle_woocommerce_after_main_content', 10 );
add_action( 'woocommerce_sidebar', 'miracle_woocommerce_sidebar', 20 );

function miracle_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

function miracle_woocommerce_before_main_content() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	echo '<div id="content" class="content" role="main">';
	echo '<div class="container">';
	echo '<div class="row">';
	echo '<div id="main" class="' . miracle_get_main_content_class() . '" role="main">';
}

function miracle_woocommerce_after_main_content() {
	echo '</div>';
}

function miracle_woocommerce_sidebar() {
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

// Remove Page Title
function miracle_woocommerce_show_page_title() {
  return false;
}

add_filter( 'woocommerce_show_page_title', 'miracle_woocommerce_show_page_title' );

// Remove Product Sale Badge
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

// Remove Add to cart
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// Shop Columns
function miracle_woocommerce_shop_columns() {
	if ( ( is_shop() || is_product_category() || is_product_tag() ) && miracle_get_option('shop_layout', 'grid') == 'list' ) {
		return 1;
	}
	if ( is_page_template( 'template-shop-list-style.php' ) ) {
		return 1;
	}
	return miracle_get_option('shop_columns');
}
add_filter( 'loop_shop_columns', 'miracle_woocommerce_shop_columns' );

// Shop Posts Per Page
function miracle_woocommerce_shop_posts_per_page() {
	return miracle_get_option('shop_posts_count');
}
add_filter( 'loop_shop_per_page', 'miracle_woocommerce_shop_posts_per_page' );

// Shop thumbnail
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'miracle_woocommerce_shop_thumbnail', 10 );

function miracle_woocommerce_shop_thumbnail() {
	$id = get_the_ID();
	echo '<a href="' . get_permalink() . '" class="product-image">';
	woocommerce_show_product_sale_flash();
	echo '<div class="first-img">';
	echo get_the_post_thumbnail( $id ,'shop_catalog' );
	echo '</div>';
	$hover_img = get_post_meta( $id, '_miracle_product_hover_img', true );
	if ( !empty( $hover_img ) ) {
		echo '<div class="back-img">';
		echo wp_get_attachment_image( $hover_img, 'shop_catalog' );
		echo '</div>';
	}
	echo "</a>";
}

// Add shop product content wrap
add_action( 'woocommerce_before_shop_loop_item_title', 'miracle_woocommerce_before_shop_loop_item_title', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'miracle_woocommerce_after_shop_loop_item_title', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'miracle_woocommerce_after_shop_loop_item', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 4 );

function miracle_woocommerce_before_shop_loop_item_title() {
	if ( ( ( is_shop() || is_product_category() || is_product_tag() ) && miracle_get_option('shop_layout', 'grid') == 'list' ) || is_page_template( 'template-shop-list-style.php' ) ) {
		echo '<div class="product-meta-wrap">';
	}
	echo '<div class="product-content">';
}

function miracle_woocommerce_after_shop_loop_item_title() {
	$excerpt = get_the_excerpt();
	if ( ( ( ( is_shop() || is_product_category() || is_product_tag() ) && miracle_get_option('shop_layout', 'grid') == 'list' ) || is_page_template( 'template-shop-list-style.php' ) ) && !empty( $excerpt ) ) {
		echo '<div class="text">';
		echo wp_kses_post( $excerpt );
		echo '</div>';
	}
	echo '</div>';
}

function miracle_woocommerce_after_shop_loop_item() {
	echo '<div class="product-action">';
	global $product;
	if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
        woocommerce_template_loop_add_to_cart( array(
			'class'    => implode( ' ', array_filter( array(
					'button',
					'product_type_' . $product->product_type,
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
			) ) )
		) );
    } else {
        woocommerce_get_template( 'loop/add-to-cart.php' );
    }
	echo '<a href="#" class="btn btn-quick-view" title="'. __( 'Quick View', LANGUAGE_ZONE ) .'" data-id="' . get_the_ID() . '"><i class="fa fa-search"></i></a>';
	echo '</div>';
	if ( ( ( is_shop() || is_product_category() || is_product_tag() ) && miracle_get_option('shop_layout', 'grid') == 'list' ) || is_page_template( 'template-shop-list-style.php' ) ) {
		echo '</div>';
	}
}

// add to cart text
add_filter( 'woocommerce_product_add_to_cart_text', 'miracle_woocommerce_product_add_to_cart_text' );
function miracle_woocommerce_product_add_to_cart_text() {
	return __( 'Add To Cart', LANGUAGE_ZONE );
}

// shop product quick view
function miracle_woocommerce_product_quick_view_thumbnails_columns() {
	return '3';
}
function miracle_enqueue_miracle_ajax_product_quickview() {
	if ( wp_script_is( 'wc-add-to-cart-variation', 'registered' ) && !wp_script_is( 'wc-add-to-cart-variation' ) ) {
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}
}
add_action( 'wp_enqueue_scripts', 'miracle_enqueue_miracle_ajax_product_quickview' );
function miracle_ajax_product_quickview() {
	$nonce = $_POST['nonce'];
	$product_id = $_POST['productid'];
	$product = get_product($product_id);
	if ( !$nonce || !$product_id || !$product || !wp_verify_nonce( $nonce, 'miracle-ajax' ) ) {
		$response = array( 'success' => false, 'reason' => 'Incorrect data' );
	} else {
		$response = array( 'success' => true );
		$post = get_post( $product_id );
		$GLOBALS['product'] = $product;
		$GLOBALS['post'] = $post;
		$GLOBALS['ajax_quick_view'] = true;
		setup_postdata( $post );

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'miracle_woocommerce_output_related_products', 20 );
		remove_action( 'woocommerce_after_single_product_summary', 'miracle_woocommerce_output_upsells', 19 );
		add_filter( 'woocommerce_product_thumbnails_columns', 'miracle_woocommerce_product_quick_view_thumbnails_columns' );

		ob_start();
		wc_get_template_part( 'content', 'single-product' );
		wp_reset_postdata();
		$html = ob_get_contents();
		ob_end_clean();
		$response['html'] = $html;
	}
	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;
}
add_action( 'wp_ajax_nopriv_miracle_ajax_product_quickview', 'miracle_ajax_product_quickview' );
add_action( 'wp_ajax_miracle_ajax_product_quickview', 'miracle_ajax_product_quickview' );


/* Single Product */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 45 );

// Sharing
add_action( 'woocommerce_share', 'miracle_woocommerce_share' );
function miracle_woocommerce_share() {
	$miracle_shop_product_shares = miracle_get_option('product_sharing');
	if ( !empty( $miracle_shop_product_shares ) ) {
		$empty = true;
		foreach ( $miracle_shop_product_shares as $site ) {
			if ( !empty( $site ) ) {
				$empty = false;
				break;
			}
		}
		if ( $empty ) {
			return;
		}
		echo '<div class="social-wrap">';
		echo '<label>';
		_e( 'Share with friends', LANGUAGE_ZONE );
		echo '</label>';
		echo miracle_display_share_buttons( 'div', 'social-icons', 'social-icon', 'has-circle', 'bottom' );
		echo '</div>';
	}
}

// Related Products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 10 );
add_action( 'woocommerce_after_single_product_summary', 'miracle_woocommerce_output_related_products', 20 );
function miracle_woocommerce_output_related_products() {

	if ( miracle_get_option('product_show_related_products') == 1 ) {
		$count   = miracle_get_option('product_related_product_count');
		$columns = miracle_get_option('product_related_product_columns');

		$args = array(
			'posts_per_page' => $count,
			'columns'        => $columns,
			'orderby'        => 'rand'
		);

		woocommerce_related_products( $args, true, true );
	}
}

// Upsells Output
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display', 10 );
add_action( 'woocommerce_after_single_product_summary', 'miracle_woocommerce_output_upsells', 19 );
function miracle_woocommerce_output_upsells() {

	$count  = miracle_get_option('product_upsell_product_count');
	$columns = miracle_get_option('product_upsell_product_columns');

	woocommerce_upsell_display( $count, $columns, 'rand' );

}

// Comment
if ( !function_exists( 'miracle_woocommerce_comments' ) ) :
	function miracle_woocommerce_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
?>
		<li id="comment-<?php comment_ID() ?>" class="comment clearfix">
			<div class="author-img">
				<span>
					<?php echo miracle_get_avatar( array( 'id' => $comment->user_id, 'email' => $comment->comment_author_email, 'size' => 72 ) ); ?>
				</span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="meta"><em><?php _e( 'Your comment is awaiting approval', 'woocommerce' ); ?></em></p>

				<?php else : ?>
					<h5 itemprop="author" class="comment-author-name"><?php comment_author(); ?></h5><?php

						if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
							if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) )
								echo '<em class="verified">(' . __( 'verified owner', 'woocommerce' ) . ')</em> ';

					?>
					<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>

						<span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'woocommerce' ), $rating ) ?>">
							<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"></span>
						</span>

					<?php endif; ?>
					<time itemprop="datePublished" class="comment-date" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php comment_date(); ?></time>
				<?php endif; ?>
				<div itemprop="description" class="description"><p><?php comment_text(); ?></p></div>
			</div>
		</li>
	<?php
	}
endif;

/* Cart */
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action( 'woocommerce_cart_actions', 'woocommerce_button_proceed_to_checkout', 10 );