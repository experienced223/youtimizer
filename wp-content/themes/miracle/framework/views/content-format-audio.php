<?php
/**
 * Blog Video Post Content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

if ( !isset( $post_classes ) ) {
	$post_classes = array( 'post' );
}
if ( !isset( $layout ) ) {
	$layout = 'masonry';
}
if ( $layout =='full' ) {
	$post_classes = array( 'post post-masonry' );
}
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>

<?php miracle_get_before_post_image( $layout, empty($is_timeline) ? false : $is_timeline ); ?>

<?php

	if ( !post_password_required() ) :

		$audio_url = esc_url( get_post_meta( $post->ID, '_miracle_audio_mp3', true ) );
		$audio_embed_code = esc_html( get_post_meta( $post->ID, '_miracle_audio_embed', true ) );

		if ( empty( $audio_url ) ) {
			$media = get_attached_media( 'audio', $post->ID );
			if ( !empty( $media ) ) {
				reset( $media );
				$audio_url = wp_get_attachment_url( $media->ID );
			}
		}
		miracle_get_post_audio( $audio_url, $audio_embed_code );

	endif;
?>
	<div class="post-content no-author-img">
		<?php echo miracle_get_post_content( $layout ); ?>
	</div>
<?php if ( $layout != 'grid' && $layout != 'classic' ) : ?>
	<div class="post-action">
		<?php echo miracle_get_post_action(); ?>
	</div>
<?php endif ?>
</article>

<?php do_action('miracle_after_post'); ?>