<?php
/**
 * Template Name: Page Template Full Width
 */
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
?>
		<section id="content">
			<?php the_content(); ?>
		</section>

		<?php do_action('miracle_after_content'); ?>
	<?php endwhile;
endif;
get_footer();