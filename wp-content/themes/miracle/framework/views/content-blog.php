<?php
/**
 * Output blog
 */

$post_wrap = '';
$post_classes = array( 'post' );
$image_size = 'full';
$post_image_classes = array();
$post_content_classes = array();
$is_timeline = false;
if ( !isset( $show_pagination ) ) {
	$show_pagination = true;
}
if ( !isset( $is_sidebar ) ) {
	$is_sidebar = MiracleHelper::check_sidebar();
}

if ( $layout == 'timeline' ) {
	$is_timeline = true;
	if ( $is_sidebar ) {
		$layout = 'full';
	} else {
		$layout = 'masonry';
		$columns = 2;
	}
}
switch ($layout) {
	case 'masonry':
		$post_classes[] = 'post-masonry';
		$post_wrap = '<div class="iso-item">%s</div>';
		$image_size = MiracleHelper::get_thumbnail_size( (int)$columns );
		break;
	case 'grid':
		$post_classes[] = 'post-grid';
		if ( isset( $display_mode ) && $display_mode == 'carousel' ) {
			$post_wrap = '%s';
		} else {
			switch ( $columns ) {
				case '1':
					$post_wrap = '<div class="col-xs-12">%s</div>';
					break;
				case '2':
					$post_wrap = '<div class="col-sm-6">%s</div>';
					break;
				case '3':
					$post_wrap = '<div class="col-sm-6 col-md-4">%s</div>';
					break;
				case '4':
					$post_wrap = '<div class="col-sms-6 col-sm-6 col-md-3">%s</div>';
					break;
				default:
					$post_wrap = '<div class="col-sms-6 col-sm-4 col-md-3 col-lg-2">%s</div>';
					break;
			}
		}
		$image_size = MiracleHelper::get_thumbnail_size( (int)$columns );
		break;
	case 'full':
		$post_classes[] = 'post-full';
		if ( $is_sidebar )  {
			$post_image_classes[] = 'col-md-5';
			$post_content_classes[] = 'col-md-7';
		} else {
			$post_image_classes[] = 'col-sm-5';
			$post_content_classes[] = 'col-sm-7';
		}
		if ( $is_sidebar ) {
			$image_size = MiracleHelper::get_thumbnail_size( 2 );
		} else {
			$image_size = MiracleHelper::get_thumbnail_size( 2 );
		}
		break;
	case 'classic':
		$post_classes[] = 'post-classic';
		$image_size = MiracleHelper::get_thumbnail_size( 1 );
		break;
}
set_query_var( 'post_classes', $post_classes );
set_query_var( 'image_size', $image_size );
set_query_var( 'layout', $layout );
set_query_var( 'post_image_classes', $post_image_classes );
set_query_var( 'post_content_classes', $post_content_classes );
set_query_var( 'is_timeline', $is_timeline );

if ( !isset( $prev_post_month ) ) {
	$prev_post_month = '';
}

$result = '';

global $wp_query;
if ( isset( $main_query_args ) ) {
	$the_query = new WP_Query( $main_query_args );
} else {
	$the_query = $wp_query;
}
while ( $the_query->have_posts() ) { $the_query->the_post();
	if ( $layout == 'full' && post_password_required() ) {
		set_query_var( 'post_image_classes', array() );
		set_query_var( 'post_content_classes', array() );
	}

	ob_start();
	$post_format = get_post_format();
	if ( $post_format ) {
		$post_format = 'format-' . $post_format;
	}
	get_template_part( MIRACLE_VIEWS_PATH . 'content', $post_format );

	$post_html = ob_get_contents();
	ob_end_clean();
	if ( $post_wrap != '' ) {
		$post_html = sprintf( $post_wrap, $post_html );
	}
	if ( $is_timeline && $layout == 'full' ) {
		$post_timestamp = strtotime($post->post_date);
		$post_month = date('n', $post_timestamp);
		if( $prev_post_month != $post_month ) {
			$result .= '<div class="timeline-date">' . get_the_date( 'M Y' ) . '</div>';
		}
		$prev_post_month = $post_month;
	}
	$result .= $post_html;
}
if ( isset( $main_query_args ) ) {
	wp_reset_postdata();
}

if ( !isset( $blog_posts_attrs ) ) {
	$blog_posts_attrs = '';
}

if ( isset( $is_ajax ) && $is_ajax ) :
	echo ( $result );
else:

	$posts_container_classes = array();
	switch ( $layout ) {
		case 'masonry':
			$posts_container_classes[] = 'iso-container';
			$posts_container_classes[] = 'iso-col-' . $columns;
			$posts_container_classes[] = 'style-masonry';
			$posts_container_classes[] = 'has-column-width';
			break;
		case 'grid':
			if ( isset( $display_mode ) && $display_mode == 'carousel' ) {
				$blog_posts_attrs .= ' data-items="' . esc_attr( $columns ) . '"';
				switch ( $columns ) {
					case 2:
						$blog_posts_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 1], [992, 2], [1200, 2]]"';
						break;
					case 3:
						$blog_posts_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 3]]"';
						break;
					case 4:
						$blog_posts_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 4]]"';
						break;
					case 5:
						$blog_posts_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 5]]"';
						break;
					case 6:
						$blog_posts_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 6]]"';
						break;
				}
				$posts_container_classes[] = 'owl-carousel post-slider style7 post-wrapper';
				$show_pagination = false;
			} else {
				$posts_container_classes[] = 'row';
				$posts_container_classes[] = 'add-clearfix';
			}
		case 'full':
			break;
	}
	if ( !empty( $extra_class ) ) {
		$posts_container_classes[] = $extra_class;
	}

	global $miracle_pagination_style;
	if ( !isset( $author_id ) ) {
		$author_id = '';
	}

	if ( $show_pagination && ( $miracle_pagination_style == 'ajax' || $miracle_pagination_style == 'load_more' ) ) {
		if ( empty( $extra_class ) ) {
			$extra_class = 'ajax-loading';
		} else {
			$extra_class .= ' ajax-loading';
		}

		if( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} else if( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		$blog_posts_attrs .= ' data-layout="' . ($is_timeline ? 'timeline' : $layout) . '"';
		$blog_posts_attrs .= ' data-columns="' . $columns . '"';
		$blog_posts_attrs .= ' data-author_id="' . $author_id . '"';
		$blog_posts_attrs .= ' data-page_num="' . $paged . '"';
		$blog_posts_attrs .= ' data-pagination="' . $miracle_pagination_style . '"';
		if ( isset( $post_month ) ) {
			$blog_posts_attrs .= ' data-post_month="' . $post_month . '"';
		}
	}

	if ( !$is_timeline ) {
		$posts_container_classes[] = 'blog-posts';
		printf( '<div class="%s"%s>%s</div>',
			esc_attr( implode(' ', $posts_container_classes) ),
			$blog_posts_attrs,
			$result
		);
		if ( $show_pagination ) {
			echo miracle_pagination( false, false, false, $the_query );
		}
	} else {
		if ( $is_sidebar ) {
			printf( '<div class="blog-posts layout-timeline layout-single%s"%s>%s%s</div>',
				empty( $extra_class ) ? '' : ' ' . $extra_class,
				$blog_posts_attrs,
				$result,
				$show_pagination && $miracle_pagination_style == 'load_more' ? miracle_pagination( true, true, false, $the_query ) : ''
			);
			if ( $show_pagination && $miracle_pagination_style == 'ajax' ) {
				echo miracle_pagination( false, false, false, $the_query );
			}
		} else {
			$author_html = '';
			if ( !empty( $author_id ) ) {
				$author_html = sprintf( '<div class="timeline-author">%s</div>',
					miracle_get_avatar( array( 'id' => $author_id, 'email' => get_the_author_meta( 'user_email', $author_id ) ) )
				);
			}
			printf( '<div class="blog-posts layout-timeline layout-fullwidth%s"%s>%s<div class="%s">%s</div>%s</div>',
				empty( $extra_class ) ? '' : ' ' . $extra_class,
				$blog_posts_attrs,
				$author_html,
				esc_attr( implode(' ', $posts_container_classes) ),
				$result,
				$show_pagination ? miracle_pagination( true, false, false, $the_query ) : ''
			);
		}
	}
endif;