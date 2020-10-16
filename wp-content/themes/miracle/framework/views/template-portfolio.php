<?php
/**
 * Template Name: Page Template Portfolio
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post, $miracle_pagination_style;

$page_id = get_the_ID();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$cols = get_post_meta( $page_id, '_miracle_portfolio_columns', true );
$is_fullwidth = get_post_meta( $page_id, '_miracle_portfolio_is_fullwidth', true );
$count = get_post_meta( $page_id, '_miracle_portfolio_posts_per_page', true );
$style = get_post_meta( $page_id, '_miracle_portfolio_style', true );
$content_area = get_post_meta( $page_id, '_miracle_portfolio_content_area', true );
$filters = get_post_meta( $page_id, '_miracle_portfolio_category_filters', true );
if ( empty( $filters ) ) {
	$filters = array( 'All Categories' );
} else {
	$filters = explode( ',', $filters );
}
$disable_filtering = get_post_meta( get_the_ID(), '_miracle_portfolio_disable_filtering', true );

$miracle_pagination_style = get_post_meta ( get_the_ID(), '_miracle_portfolio_loading_style', true );
$orderby = get_post_meta ( get_the_ID(), '_miracle_portfolio_orderby', true );
$order = get_post_meta ( get_the_ID(), '_miracle_portfolio_order', true );

$classes = array( 'iso-container', 'iso-col-' . esc_attr( $cols ) );
$template_name = '';
if ( $style == 'masonry1' || $style == 'masonry2' ) {
	$classes[] = 'style-masonry';
	$template_name = 'masonry';
} else if ( $style == 'masonry3' ) {
	$classes[] = 'style-masonry';
	$classes[] = 'has-column-width';
	$template_name = 'masonry';
} else {
	$classes[] = 'style-' . esc_attr( $style );
	$template_name = $style;
}


if ( strpos( $style, 'masonry') === 0 ) {
	$image_size = MiracleHelper::get_thumbnail_size( $cols, -1, true );
	if ( $style == 'masonry2' || $style == 'masonry3' ) {
		$image_double_size = MiracleHelper::get_thumbnail_size( $cols == 1 ? 1 : floor($cols / 2), -1, true );
	}
} else {
	if ( $is_fullwidth ) {
		$image_size = MiracleHelper::get_thumbnail_size( $cols - 1 );
	} else {
		$image_size = MiracleHelper::get_thumbnail_size( $cols );
	}
}
?>

<?php get_header(); ?>

<section id="content">
<?php if ( $is_fullwidth !== '1' ) { echo '<div class="container"><div class="row">'; } ?>
	<div id="main"<?php $is_fullwidth !== '1' ? ' class="' . miracle_get_main_content_class() . '"' : '' ?>>

		<?php
			if ( $content_area == 'before_items_all_page' || 
				( $content_area == 'before_items_first_page' && (int)$paged === 1 ) ) {
				echo '<div class="page-info box">';
				if ( have_posts() ) {
					while ( have_posts() ) : the_post();
						the_content();
					endwhile;
				}
				echo '</div>';
			}
		?>

		<?php
			$container_attrs = "";
			if ( $miracle_pagination_style == "ajax" || $miracle_pagination_style == "load_more" ) {
				$container_attrs .= ' data-pagination="' . esc_attr( $miracle_pagination_style ) . '"';
				$container_attrs .= ' data-layout="' . esc_attr( $style ) . '"';
				$container_attrs .= ' data-count="' . esc_attr( $count ) . '"';
				$container_attrs .= ' data-page_num="' . esc_attr( $paged ) . '"';
				$container_attrs .= ' data-filters="' . implode( ',', $filters ) . '"';
				$container_attrs .= ' data-orderby="' . esc_attr( $orderby ) . '"';
				$container_attrs .= ' data-order="' . esc_attr( $order ) . '"';
				$container_attrs .= ' data-image_size="' . esc_attr( $image_size ) . '"';
			}
		?>

		<div class="post-wrapper portfolio-container"<?php echo ' ' . $container_attrs; ?>>

		<?php if ( $disable_filtering !== '1' ) : 
			if ( count( $filters ) == 1 && in_array( 'All Categories', $filters ) ) {

				$terms = get_terms( 'm_portfolio_category' );

			} elseif ( count( $filters ) == 1 && !in_array( 'All Categories', $filters ) ) {

				$terms = array();
				foreach ( $filters as $filter ) {
					$children = get_term_children( $filter, 'm_portfolio_category' );
					$terms = array_merge( $children, $terms );
				}
				$terms = get_terms( 'm_portfolio_category', array( 'include' => $terms ) );

			} else {

				$terms = array();
				foreach ( $filters as $filter ) {
					$parent = array( $filter );
					$children = get_term_children( $filter, 'm_portfolio_category' );
					$terms = array_merge( $parent, $terms );
					$terms = array_merge( $children, $terms );
				}
				$terms = get_terms( 'm_portfolio_category', array( 'include' => $terms ) );
			}
		?>
			<div class="post-filters">
				<a href="#" class="btn btn-sm style4 hover-blue active" data-filter="filter-all"><?php _e( 'All', LANGUAGE_ZONE ); ?></a>
			<?php foreach ( $terms as $term ) { ?>
				<a href="#" class="btn btn-sm style4 hover-blue" data-filter="filter-<?php echo md5( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></a>
			<?php } ?>
			</div>
		<?php endif; ?>

		<?php
			$the_query = new WP_Query( miracle_get_portfolio_query_args( $filters, $count, $paged, '', $orderby, $order ) );
		?>
		<?php if ( $the_query->have_posts() ) : ?>
		<?php
			set_query_var( 'style', $style );
		?>
			<div class="<?php echo implode( ' ', $classes ); ?>">
			<?php
				while ( $the_query->have_posts() ) : $the_query->the_post();
					if ( isset( $image_double_size ) ) {
						set_query_var( 'image_double_size', $image_double_size );
						set_query_var( 'image_size', $image_double_size );
						unset( $image_double_size );
					} else {
						set_query_var( 'image_double_size', '' );
						set_query_var( 'image_size', $image_size );
					}
					miracle_get_template( 'content', 'portfolio-list' );
				endwhile;
			?>
			</div>
			<?php echo miracle_pagination( false, false, false, $the_query ); ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		</div>

		<?php
			if ( $content_area == 'after_items_all_page' || 
				( $content_area == 'after_items_first_page' && (int)$paged === 1 ) ) {
				if ( have_posts() ) {
					echo '<div class="box"></div><div class="page-info">';
					while ( have_posts() ) : the_post();
						the_content();
					endwhile;
					echo '</div>';
				}
			}
		?>

	</div>
	<?php get_sidebar(); ?>

	<?php if ( $is_fullwidth !== '1' ) { echo '</div></div>'; } ?>
</section>
<?php do_action('miracle_after_content'); ?>

<?php get_footer(); ?>

