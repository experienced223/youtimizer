<?php

/**
 * The template for displaying all single posts
 */
get_header();
?>

	<section id="content">
		<div class="container">
			<div class="row">
				<div id="main" class="<?php echo miracle_get_main_content_class(); ?>" role="main">
				<?php  if ( have_posts() ) { while ( have_posts() ) : the_post(); ?>
					<?php miracle_get_template( 'content-single', str_replace( 'm_', '', get_post_type() ) ); ?>
				<?php endwhile; } ?>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>

	<?php do_action('miracle_after_content'); ?>
<?php 
get_footer();