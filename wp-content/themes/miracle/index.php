<?php
/**
 * The main template file
 */
?>

<?php get_header(); ?>

<section id="content">
	<div class="container">
		<div class="row">
			<div id="main" class="<?php echo miracle_get_main_content_class(); ?>" role="main">
				<?php miracle_get_template( 'content', 'index' ); ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php get_footer();