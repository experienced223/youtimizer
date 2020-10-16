<?php
/**
 * Defines ajax actions
 */

function miracle_ajax_pagination() {

	$nonce = $_POST['nonce'];
	$post_id = $_POST['postID'];
	$post_paged = $_POST['page_num'];
	if ( !$nonce || !$post_id || !isset($post_paged) || !wp_verify_nonce( $nonce, 'miracle-ajax' ) ) {
		$response = array( 'success' => false, 'reason' => 'Incorrect data' );
	} else {
		$response = array();
		global $post;
		$post = get_post( $post_id );
		setup_postdata( $post );
		if ( $_POST['post_type'] == 'post' ) { // blog posts
			$ajax_data = array(
				'post_type' => isset($_POST['post_type']) ? $_POST['post_type'] : 'post',
				'ids' => isset($_POST['ids']) ? $_POST['ids'] : '',
				'category' => isset($_POST['category']) ? $_POST['category'] : '',
				'count' => isset($_POST['count']) ? $_POST['count'] : -1,
				'orderby' => isset($_POST['orderby']) ? $_POST['orderby'] : NULL,
				'order' => isset($_POST['order']) ? $_POST['order'] : 'DESC',
				'author_id' => isset($_POST['author_id']) ? $_POST['author_id'] : '',
			);
			$is_sidebar = !empty($_POST['is_sidebar']) && $_POST['is_sidebar'] ? true : false;
			$query_args = miracle_shortcode_query_posts_args( $ajax_data );
			$query_args['paged'] = $post_paged + 1;

			set_query_var( 'paged', $post_paged + 1 );

			$the_query = new WP_Query( $query_args );

			if ( $the_query->max_num_pages == $post_paged + 1 ) {
				$response['last_page'] = true;
			}
			if ( $post_paged == 0 ) {
				$response['first_page'] = true;
			}

			$html = '';
			if ( $the_query->have_posts() ) {

				set_query_var( 'layout', $_POST['layout'] );
				set_query_var( 'columns', $_POST['columns'] );
				if ( !empty( $_POST['author_id'] ) ) {
					set_query_var( 'author_id', $_POST['author_id'] );
				}
				set_query_var( 'is_sidebar', $is_sidebar );
				set_query_var( 'is_ajax', true );
				if ( !empty( $_POST['post_month'] ) ) {
					set_query_var( 'prev_post_month', $_POST['post_month'] );
				}
				set_query_var('main_query_args', $query_args);
				ob_start();
				miracle_get_template( 'content', 'blog' );
				$html = ob_get_contents();
				ob_end_clean();

				if ( $_POST['layout'] == 'timeline' ) {
					$post_timestamp = strtotime($post->post_date);
					$post_month = date('n', $post_timestamp);
					$response['post_month'] = $post_month;
				}
				$response['pagination_html'] = miracle_pagination( false, false, false, $the_query );
				$response['success'] = true;
				$response['html'] = $html;
			} else {
				$response['success'] = false;
				$response['reason'] = 'No result';
			}
		} else if ( $_POST['post_type'] == 'portfolio' ) { //portfolio

			$count = isset($_POST['count']) ? $_POST['count'] : -1 ;
			$filters = explode(',', $_POST['filters']);
			$orderby = isset($_POST['orderby']) ? $_POST['orderby'] : '';
			$order = isset($_POST['order']) ? $_POST['order'] : '';
			if ( !empty( $_POST['ids'] ) ) {
				$ids = explode(',', $_POST['ids']);
			} else {
				$ids = '';
			}
			$pagination_style = isset($_POST['pagination']) ? $_POST['pagination'] : '';
			if ( $pagination_style == 'ajax' && ( $_POST['layout'] == 'masonry2' || $_POST['layout'] == 'masonry3' ) ) {
				$image_double_size = MiracleHelper::get_masonry_thumbnail_double_size( $_POST['image_size'] );
			}

			set_query_var( 'paged', $post_paged + 1 );


			$the_query = new WP_Query( miracle_get_portfolio_query_args( $filters, $count, $post_paged + 1, $ids, $orderby, $order ) );
			$html = '';
			if ( $the_query->have_posts() ) :
				set_query_var( 'style', $_POST['layout'] );
				ob_start();
				while ( $the_query->have_posts() ) : $the_query->the_post();
					if ( isset( $image_double_size ) ) {
						set_query_var( 'image_double_size', $image_double_size );
						set_query_var( 'image_size', $image_double_size );
						unset( $image_double_size );
					} else {
						set_query_var( 'image_double_size', '' );
						set_query_var( 'image_size', $_POST['image_size'] );
					}
					miracle_get_template( 'content', 'portfolio-list' );
				endwhile;
				wp_reset_postdata();
				$html = ob_get_contents();
				ob_end_clean();
			endif;
			$response['pagination_html'] = miracle_pagination( false, false, false, $the_query );
			if ( $the_query->max_num_pages == $post_paged + 1 ) {
				$response['last_page'] = true;
			}
			if ( $post_paged == 0 ) {
				$response['first_page'] = true;
			}
			$response['success'] = true;
			$response['html'] = $html;
		}
	}

	header( "Content-Type: application/json" );
	echo json_encode( $response );
	exit;
}
add_action( 'wp_ajax_nopriv_miracle_ajax_pagination', 'miracle_ajax_pagination' );
add_action( 'wp_ajax_miracle_ajax_pagination', 'miracle_ajax_pagination' );