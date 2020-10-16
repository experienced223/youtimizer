<?php
/**
 * Blog Quote Post Content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;


if ( !isset( $layout ) ) {
	$layout = 'masonry';
}
$post_classes = array( 'post' );
if ( $layout == 'classic' ) {
	$post_classes[] = 'post-classic';
} else {
	$post_classes[] = 'post-masonry';
}
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>

<?php miracle_get_before_post_image( $layout, empty($is_timeline) ? false : $is_timeline ); ?>
	<?php
		$quote_text = get_post_meta( $post->ID, '_miracle_quote_quote', true );
		if ( empty( $quote_text ) ) {
			preg_match( '!<blockquote>!', $post->post_content, $start_matches, PREG_OFFSET_CAPTURE );
			preg_match( '!<\/blockquote>!', $post->post_content, $end_matches, PREG_OFFSET_CAPTURE );
			if ( isset( $start_matches[0] ) && isset( $end_matches[0] ) ) {
				$quote_text = substr( $post->post_content, $start_matches[0][1] + 12, $end_matches[0][1] - $start_matches[0][1] - 12 );
			}
		}
	?>
	<blockquote class="style1">
	<?php
		echo "<p>" . stripslashes( htmlspecialchars_decode( esc_html( $quote_text ) ) ) . "</p>";
		$author = get_post_meta( $post->ID, '_miracle_quote_cite', true );
		if ( !empty($author) ) {
			echo '<span class="name">&#8212;' . esc_html( $author ) .'</span>';
		}
	?>
	</blockquote>



<?php if ( $layout == 'grid' ) { ?>
	<div class="post-action text-center">
		<a href="<?php echo get_permalink(); ?>" class="btn btn-sm style3 post-read-more float-none"><?php esc_html_e( 'More', LANGUAGE_ZONE ); ?></a>
	</div>
<?php } else if ( $layout != 'classic' ) { ?>
	<div class="post-action">
		<?php echo miracle_get_post_action(); ?>
	</div>
<?php } ?>
</article>

<?php do_action('miracle_after_post'); ?>