<?php
/**
 * Blog Image Post Content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

if ( !isset( $post_classes ) ) {
	$post_classes = array();
}
if ( !isset( $image_size ) ) {
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
$hover_style = '';
if ( $layout != 'full' ) {
	$hover_style = 'hover-style3';
} else if ( post_password_required() ) {
	$post_content_classes[] = 'full-width';
}
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>

<?php miracle_get_before_post_image( $layout, empty($is_timeline) ? false : $is_timeline ); ?>

<?php

	$gravatar_alt = esc_html($author_name);
	if ( $layout == 'masonry' ) {
		$author_email = get_the_author_meta( 'user_email' );
		$gravatar = miracle_get_avatar( array( 'id' => get_the_author_meta( 'ID' ), 'email' => $author_email, 'size' => '81'  ), false );
	}

	$author_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
	$post_link = get_permalink();


	$alt = esc_attr( get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true ) );

	if ( !post_password_required() && has_post_thumbnail() ) :
		echo '<div class="post-image ' . esc_attr( $post_image_classes ) . '">';
		if ( $layout == 'classic' ) {
			echo '<a href="' . $post_link . '">';
			the_post_thumbnail( $image_size );
			echo '</a>';
		} else {
			echo '<div class="image ' . esc_attr( $hover_style ) . '">';
			the_post_thumbnail( $image_size );
			echo '<div class="image-extras"><a href="' . $post_link . '" class="post-gallery"></a></div>';
			echo '</div>';
		}
		echo '</div>';
	else:
		if ( ( $key = array_search('col-md-7', $post_content_classes) ) !== false ) {
			unset($post_content_classes[$key]);
		}
		if ( ( $key = array_search('col-sm-7', $post_content_classes) ) !== false ) {
			unset($post_content_classes[$key]);
		}
	endif;

$post_content_classes = implode( ' ', $post_content_classes );
?>
<?php if ( $layout == 'masonry' && !post_password_required() && !empty( $gravatar ) ) { ?>
	<div class="post-content">
		<div class="post-author"><a href="<?php echo esc_url( $author_url ); ?>"><?php echo ($gravatar); ?></a></div>
<?php } else if ( $layout == 'masonry' ) { ?>
	<div class="post-content no-author-img <?php echo esc_attr( $post_content_classes ); ?>">
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