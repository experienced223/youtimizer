<?php
/**
 * WOOCOMMERCE/SINGLE-PRODUCT-REVIEWS.PHP
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews">
	<div id="comments">
		<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : ?>
			<a href="#" class="btn btn-sm style1 btn-write-review"><i class="fa fa-pencil"></i>Write Review</a>
		<?php endif; ?>
		<h3><?php
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) )
				printf( _n( '%s review for %s', '%s reviews for %s', $count, LANGUAGE_ZONE ), $count, get_the_title() );
			else
				_e( 'Reviews', LANGUAGE_ZONE );
		?></h3>

		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'miracle_woocommerce_comments' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php _e( 'There are no reviews yet.', LANGUAGE_ZONE ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : ?>

		<div id="review_form">
			<a href="#" class="btn btn-sm style1 btn-back-reviews"><i class="fa fa-long-arrow-left"></i>Back To Reviews</a>
			<?php
				$commenter = wp_get_current_commenter();

				$comment_form = array(
					'title_reply'          => have_comments() ? __( 'Add a review', LANGUAGE_ZONE ) : __( 'Be the first to review', LANGUAGE_ZONE ) . ' &ldquo;' . get_the_title() . '&rdquo;',
					'title_reply_to'       => __( 'Leave a Reply to %s', LANGUAGE_ZONE ),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<div class="form-group comment-form-author">' . '<label for="author">' . __( 'Name', LANGUAGE_ZONE ) . ' <span class="required">*</span></label> ' .
						            '<input id="author" name="author" type="text" class="input-text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></div>',
						'email'  => '<div class="form-group comment-form-email"><label for="email">' . __( 'Email', LANGUAGE_ZONE ) . ' <span class="required">*</span></label> ' .
						            '<input id="email" name="email" type="text" class="input-text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></div>',
					),
					'label_submit'  => __( 'Submit Review', LANGUAGE_ZONE ),
					'id_submit' => 'comment-submit',
					'logged_in_as'  => '',
					'comment_field' => ''
				);

				if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
					$comment_form['comment_field'] = '<div class="form-group"><label for="rating">' . __( 'Your Rating', LANGUAGE_ZONE ) .'</label><input type="hidden" id="review_score"><span class="input-star-rating">
						<input type="radio" value="5" name="rating" title="' . __( 'Perfect', LANGUAGE_ZONE ) . '">
						<input type="radio" value="4" name="rating" title="' . __( 'Good', LANGUAGE_ZONE ) . '">
						<input type="radio" value="3" name="rating" title="' . __( 'Average', LANGUAGE_ZONE ) . '">
						<input type="radio" value="2" name="rating" title="' . __( 'Not that bad', LANGUAGE_ZONE ) . '">
						<input type="radio" value="1" name="rating" title="' . __( 'Very Poor', LANGUAGE_ZONE ) . '">
					</span></div>';
				}

				$comment_form['comment_field'] .= '<div class="form-group"><label for="comment">' . __( 'Your Review', LANGUAGE_ZONE ) . '</label><textarea id="comment" name="comment" class="input-text full-width" rows="8" aria-required="true"></textarea></div>';

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
			?>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', LANGUAGE_ZONE ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
