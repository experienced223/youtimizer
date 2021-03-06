<?php

/**
 * Portfolio content for grid style
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

$post_link = get_permalink();

$category = get_the_category(); 
$category_name = '';
if ( !empty( $category[0] ) ) {
	$category_name =  $category[0]->cat_name;
}
?>

<?php do_action('miracle_before_post'); ?>

<article <?php post_class( $post_classes ); ?>>
	<figure><?php echo miracle_get_portfolio_thumbnail( $post->ID , $image_size ); ?></figure>
	<div class="portfolio-hover-holder style1">
		<div class="portfolio-text">
			<div class="portfolio-text-inner">
				<div class="st-td">
					<h5 class="portfolio-title"><?php the_title(); ?></h5><span class="portfolio-category"><?php echo esc_html( $category_name ); ?></span>
				</div>
				<div class="st-td">
					<?php if ( function_exists('wp_ulike') ) { ?>
						<?php echo wp_ulike('put'); ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<span class="portfolio-action">
			<a href="<?php echo esc_url( $post_link ); ?>"><i class="fa fa-chain has-circle"></i></a>
		<?php if ( !empty( $post_full_image_url[0] ) ) : ?>
			<a href="<?php echo esc_url( $post_full_image_url[0] ); ?>" class="soap-mfp-popup"><i class="fa fa-eye has-circle"></i></a>
		<?php endif; ?>
		</span>
	</div>
</article>

<?php do_action('miracle_after_post'); ?>