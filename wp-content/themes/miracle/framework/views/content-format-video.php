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
if ( !isset( $post_image_classes ) ) {
	$post_image_classes = array();
}
$post_image_classes = implode( " ", $post_image_classes );

if ( !isset( $post_content_classes ) ) {
	$post_content_classes = array();
}
if ( $layout == 'masonry' ) {
	$post_content_classes[] = 'no-author-img';
}
$post_content_classes = implode( " ", $post_content_classes );
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>

<?php miracle_get_before_post_image( $layout, empty($is_timeline) ? false : $is_timeline ); ?>

<?php

	$video_url = esc_url( get_post_meta( $post->ID, '_miracle_video_url', true ) );
	$video_embed_code = esc_html( get_post_meta( $post->ID, '_miracle_video_embed', true ) );
	$video_ratio = esc_attr( get_post_meta( $post->ID, '_miracle_video_ratio', true ) );

	if ($layout == 'full') {
		echo '<div class="post-image ' . esc_attr( $post_image_classes ) . '">';
		miracle_get_post_video( $video_url, $video_embed_code, $video_ratio );
		echo '</div>';
	} else {
		miracle_get_post_video( $video_url, $video_embed_code, $video_ratio );
	}
?>
	<div class="post-content <?php echo esc_attr( $post_content_classes ); ?>">
		<?php echo miracle_get_post_content( $layout ); ?>
		<?php if ( $layout == 'full' ) : ?>
			<div class="post-action">
				<?php echo miracle_get_post_action(); ?>
			</div>
		<?php endif; ?>
	</div>
<?php if ( $layout == 'masonry' ) : ?>
	<div class="post-action">
		<?php echo miracle_get_post_action(); ?>
	</div>
<?php endif ?>
</article>

<?php do_action('miracle_after_post'); ?>