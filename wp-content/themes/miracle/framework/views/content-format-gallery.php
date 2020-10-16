<?php
/**
 * Blog Gallery Post Content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

if ( !isset( $post_classes ) ) {
	$post_classes = array();
}
if ( !$image_size ) {
	$image_size = MiracleHelper::get_thumbnail_size(1);
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
$post_content_classes = implode( " ", $post_content_classes );
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>

<?php miracle_get_before_post_image( $layout, empty($is_timeline) ? false : $is_timeline ); ?>

<?php

	$thumb_id = get_post_thumbnail_id();
	$full_thumb_meta = wp_get_attachment_image_src( $thumb_id, 'full' );

	$gravatar_alt = esc_html($author_name);
	$gravatar = '';
	if ( $layout == 'masonry' ) {
		$author_email = get_the_author_meta( 'user_email' );
		$gravatar = miracle_get_avatar( array( 'id' => get_the_author_meta( 'ID' ), 'email' => $author_email, 'size' => '81'  ), false );
	}

	$author_url = get_the_author_meta('user_url', $post->post_author);
	if ( empty($author_url) ) {
		$author_url = "javascript:void(0)";
	}

	$gallery = get_post_gallery( $post->ID, false );
	if ( !empty($gallery['ids']) ) {

		$media_items = array_map( 'trim', explode( ',', $gallery['ids'] ) );
		/*if ( has_post_thumbnail() ) {
			array_unshift( $media_items, get_post_thumbnail_id() );
		}*/

		$attachments = miracle_get_attachment_post_data( $media_items, $image_size );
		if ($layout == 'full') {
			echo '<div class="post-image ' . esc_attr( $post_image_classes ) . '">';
			miracle_get_post_gallery( $attachments, '' );
			echo '</div>';
		} else {
			miracle_get_post_gallery( $attachments );
		}
	} else if ( has_post_thumbnail() ) {
		if ($layout == 'full') {
			echo '<div class="post-image ' . esc_attr( $post_image_classes ) . '">';
			the_post_thumbnail( $image_size );
			echo '</div>';
		} else {
			the_post_thumbnail( $image_size );
		}
	}
?>
<?php if ( $layout == 'masonry' && !post_password_required() && !empty( $gravatar ) ) { ?>
	<div class="post-content">
		<div class="post-author"><a href="<?php echo esc_url( $author_url ); ?>"><?php echo ($gravatar); ?></a></div>
<?php } else if ( $layout == 'masonry' ) { ?>
	<div class="post-content no-author-img">
<?php } else { ?>
	<div class="post-content <?php echo esc_attr( $post_content_classes ); ?>">
<?php } ?>
		<?php echo miracle_get_post_content( $layout ); ?>

		<?php if ( $layout == 'full' ) : ?>
			<div class="post-action">
				<?php echo miracle_get_post_action(); ?>
			</div>
		<?php endif ?>
	</div>

<?php if ( $layout == 'masonry' ) : ?>
	<div class="post-action">
		<?php echo miracle_get_post_action(); ?>
	</div>
<?php endif ?>
</article>

<?php do_action('miracle_after_post'); ?>