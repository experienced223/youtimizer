<?php
/**
 * The template for displaying Comments.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( post_password_required() ) {
	return;
}
?>

<?php if ( have_comments() ) : ?>
	<div class="comments-container block" id="comments">
		<h3 class="font-normal"><?php comments_number( __( 'No Comments', LANGUAGE_ZONE ), __( 'One Comment', LANGUAGE_ZONE ), '% '.__( 'Comments', LANGUAGE_ZONE) );?></h3>
		<ul class="commentlist">
			<?php wp_list_comments('callback=miracle_display_comment'); ?>
		</ul>
		<?php paginate_comments_links( array( 'type' => 'list' ) ); ?>
	</div>
<?php else : // this is displayed if there are no comments so far ?>
	
	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="no-comments"><?php echo __('Comments are closed.', LANGUAGE_ZONE); ?></p>

	<?php endif; ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<div class="comment-respond" id="respond">
		<?php
			$args = array(  'comment_field' => '<div id="comment-textarea" class="form-group"><textarea id="comment" name="comment" rows="6" aria-required="true"  class="input-text full-width textarea-comment" placeholder="' . __( 'Comment Type here', LANGUAGE_ZONE ) . '*"></textarea></div>',
							'title_reply' => __( 'Post A Comment', LANGUAGE_ZONE ),
							'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published. Required fields are marked *', LANGUAGE_ZONE ) . '</p>',
							'id_submit' => 'comment-submit',
							'label_submit' => __( 'Submit Message', LANGUAGE_ZONE ),
							'fields' => array(
								'author' => '<div class="col-sm-4 form-group"> <input name="author" type="text" class="input-text full-width" value="" placeholder="' . __( 'Full Name', LANGUAGE_ZONE ) . '*"> </div>',
								'email' => '<div class="col-sm-4 form-group"> <input name="email" type="text" class="input-text full-width" value="" placeholder="' . __( 'Email Address', LANGUAGE_ZONE ) . '*"> </div>',
								'website' => '<div class="col-sm-4 form-group"> <input name="website" type="text" class="input-text full-width" value="" placeholder="' . __( 'Your Website', LANGUAGE_ZONE ) . '"> </div>',
							),
						);
		 ?>
		<?php comment_form($args); ?>
	</div>
<?php endif;