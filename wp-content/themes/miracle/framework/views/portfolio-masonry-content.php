<?php

/**
 * Portfolio content for masonry text style
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

if ( !isset( $post_classes ) ) {
	$post_classes = array();
}
if ( !isset( $image_size ) ) {
	$image_size = MiracleHelper::get_thumbnail_size(1);
}
$post_full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

if ( !isset( $view_details ) ) {
	$view_details = 'false';
}
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>
	<a class="image soap-mfp-popup hover-style3" href="<?php echo esc_url( $post_full_image_url[0] ); ?>">
		<?php the_post_thumbnail( $image_size ); ?>
		<span class="image-extras"></span>
	</a>

	<?php if ( $view_details === 'true' ) :
		$post_link = get_permalink();

		$miracle_option_portfolio_masonry_date_format = miracle_get_option('portfolio_masonry_date_format');
		$post_date = get_the_date( $miracle_option_portfolio_masonry_date_format );

		$categories_list = get_the_term_list( get_the_ID(), 'm_portfolio_category', '', ', ' );
		if ( $categories_list && !is_wp_error($categories_list) ) {
			$categories_list = str_replace( array( 'rel="tag"', 'rel="category tag"' ), '', $categories_list);
			$categories_list = trim($categories_list);
		}
	?>
		<div class="portfolio-content">
			<h3 class="portfolio-title"><?php the_title(); ?></h3>
			<div class="portfolio-meta"><?php echo empty( $categories_list ) ? '' : sprintf( __( 'In %s', LANGUAGE_ZONE ), $categories_list ) . '  .  '; ?><?php echo esc_html( $post_date ); ?></div>
			<div class="text">
				<?php the_excerpt(); ?>
			</div>
			<a href="<?php echo esc_url( $post_link ); ?>" class="btn btn-sm style2"><?php esc_html_e( 'More', LANGUAGE_ZONE ); ?></a>
		</div>
	<?php endif; ?>
</article>

<?php do_action('miracle_after_post'); ?>