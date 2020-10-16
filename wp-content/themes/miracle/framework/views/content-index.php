<?php
/**
 * Index Page output
 */
?>

<?php
global $miracle_pagination_style;

if ( is_home() ) {
	$layout = miracle_get_option('blog_style');
	$columns = miracle_get_option('blog_columns');
	$miracle_pagination_style = miracle_get_option('blog_pagination_style');
} else if ( is_archive() ) {
	$layout = miracle_get_option('blog_archive_style');
	$columns = miracle_get_option('blog_archive_columns');
	if ( is_author() ) {
		$author_id = get_query_var( 'author' );
	}
	$miracle_pagination_style = miracle_get_option('blog_archive_pagination_style');
} else if ( is_search() ) {
	$layout = miracle_get_option('blog_search_style');
	$columns = miracle_get_option('blog_search_columns');
	$miracle_pagination_style = miracle_get_option('blog_search_pagination_style');
}

if ( have_posts() ):

	set_query_var( 'layout', $layout );
	set_query_var( 'columns', $columns );
	if ( isset( $author_id ) ) {
		set_query_var( 'author_id', $author_id );
	}
	miracle_get_template( 'content', 'blog' );
else:
	miracle_get_template( 'content', 'no-result' );
endif; ?>