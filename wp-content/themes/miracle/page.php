<?php
/**
 * The template for displaying all pages
 */

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
?>

	<section id="content">
		<div class="container">
			<div class="row">
				<div id="main" class="<?php echo miracle_get_main_content_class(); ?>">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', LANGUAGE_ZONE ), 'after' => '</div>' ) ); ?>
					<?php edit_post_link( __( 'Edit', LANGUAGE_ZONE ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- #main -->
				<?php get_sidebar(); ?>
			</div>
		</div>
	</section><!-- #content -->
	<?php do_action('miracle_after_content'); ?>

<?php endwhile; endif; ?>
<?php get_footer();
