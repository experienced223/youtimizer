<?php
/**
 * Function Set used in the theme
 */

if ( !function_exists( 'miracle_display_page_title' ) ) :
	function miracle_display_page_title() {
		if ( is_category() ) {
			single_cat_title();
		} elseif ( is_tag() ) {
			single_tag_title();
		} elseif ( is_author() ) {
			the_author_meta('display_name');
		} elseif ( is_date() ) {
			single_month_title( ' ' );
		} elseif ( is_search() ) {
			printf( __( 'Search Results for: %s', LANGUAGE_ZONE ), '<span>' . get_search_query() . '</span>' );
		} elseif ( is_tax() ){
			single_term_title();
		} else if ( is_archive() && function_exists( 'is_shop' ) && is_shop() ) {
			echo miracle_get_option( 'shop_page_title', __( 'Shop', LANGUAGE_ZONE ) );
		} else if ( is_home() ) {
			echo miracle_get_option( 'blog_page_title', __( 'Blog', LANGUAGE_ZONE ) );
		} else {
			the_title();
		}
	}
endif;


if ( ! function_exists( 'miracle_breadcrumbs' ) ) :

	/**
	 * Breadcrumbs
	 */
	function miracle_breadcrumbs() {
		global $post;

		$shop_page_title = miracle_get_option( 'shop_page_title', __( 'Shop', LANGUAGE_ZONE ) );
		if ( function_exists( 'woocommerce_get_page_id' ) ) {
			$shop_page_url  = get_permalink( woocommerce_get_page_id( 'shop' ) );
			$shop_page_link = '<li><a href="'. $shop_page_url .'">' . esc_html($shop_page_title) . '</a></li>';
		}
		if ( is_home() ) {}
		else {
			echo '<ul class="breadcrumbs">';

			if ( !is_front_page() ) {
				echo '<li><a href="' . home_url() . '" title="' . __('Home', LANGUAGE_ZONE) . '">' . __('Home', LANGUAGE_ZONE) . '</a></li>';
			}

			$current_title = get_the_title();
			if( is_single() ) {
				if ( ( $post->post_type == 'post' ) ) {
					// default blog post breadcrumb
					$categories_1 = get_the_category($post->ID);
					if($categories_1):
						foreach($categories_1 as $cat_1):
							$cat_1_ids[] = $cat_1->term_id;
						endforeach;
						$cat_1_line = implode(',', $cat_1_ids);
					endif;
					$categories = get_categories(array(
						'include' => $cat_1_line,
						'orderby' => 'id'
					));
					if ( $categories ) :
						foreach ( $categories as $cat ) :
							$cats[] = '<li><a href="' . get_category_link( $cat->term_id ) . '" title="' . esc_attr($cat->name) . '">' . esc_html($cat->name) . '</a></li>';
						endforeach;
						echo join( '', $cats );
					endif;
					if ( !empty( $current_title ) ) {
						echo '<li class="active">'.$current_title.'</li>';
					}
				} else if ( function_exists( 'is_product' ) && is_product() ) {
					echo ($shop_page_link);
					if ( !empty( $current_title ) ) {
						echo '<li class="active">'.$current_title.'</li>';
					}
				} else {
					// other single post breadcrumb - accommodation etc
					if ( !empty( $current_title ) ) {
						echo '<li class="active">'.$current_title.'</li>';
					}
				}
			} else if ( is_page() && ! is_front_page() ) {
				$parents = array();
				$parent_id = $post->post_parent;
				while ( $parent_id ) :
					$page = get_page( $parent_id );
					$parents[] = '<li><a href="' . get_permalink( $page->ID ) . '" title="' . get_the_title( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a></li>';
					$parent_id = $page->post_parent;
				endwhile;
				$parents = array_reverse( $parents );
				echo join( '', $parents );
				if ( !empty( $current_title ) ) {
					echo '<li class="active">'.$current_title.'</li>';
				}
			} else if ( is_category() ) {
				$category = get_category( get_query_var( 'cat' ) );
				$parents = array();
				$parent_cat = $category;
				while( ! empty( $parent_cat->parent ) ) {
					$parent_cat = get_category( $parent_cat->parent );
					$parents[] = '<li><a href="' . get_category_link( $parent_cat->cat_ID ) . '">' . esc_html($parent_cat->cat_name) . '</a></li>';
				}
				$parents = array_reverse( $parents );
				echo join( '', $parents );
				echo '<li class="active">' . $category->cat_name . '</li>';
			} else if ( function_exists( 'is_product_category' ) && is_product_category() ) {
				echo ($shop_page_link);
				echo '<li class="active">' . single_cat_title( '', false ) . '</li>';
			} else if ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
				echo ($shop_page_link);
				echo '<li class="active">' . single_tag_title( '', false ) . '</li>';
			} else if ( is_tax() ) {
				$taxonomy = get_query_var( 'taxonomy' );
				$term = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
				$parents = array();
				$parent_term = $term;
				while ( ! empty( $parent_term->parent ) ) :
					$parent_term = get_term( $parent_term->parent, $taxonomy );
					$parents[] = '<li><a href="' . get_term_link( $parent_term->term_id, $taxonomy ) . '" title="' . esc_attr($parent_term->name) . '">' . esc_html($parent_term->name) . '</a></li>';
				endwhile;
				$parents = array_reverse( $parents );
				echo join( '', $parents );
				if ( ! empty( $term->parent ) ) {
				}
				echo '<li class="active">'.$term->name.'</li>';
			} else if ( is_tag() ){
				echo '<li class="active">' . single_tag_title( '', FALSE ) . '</li>';
			} else if ( is_404() ){
				echo '<li class="active">'.__("404 - Page not Found", LANGUAGE_ZONE).'</li>';
			} else if ( is_search() ) {
				echo '<li class="active">';
				echo get_post_type( $post ) . ' ';
				echo __('SEARCH RESULTS', LANGUAGE_ZONE);
				echo "</li>";
			} else if ( is_author() ) {
				$userdata = get_userdata(get_query_var( 'author' ));
				echo '<li class="active">' . __( 'Author:', LANGUAGE_ZONE ) . ' ' . esc_html($userdata->display_name) . '</li>';
			} else if ( is_archive() && function_exists( 'is_shop' ) && is_shop() ) {
				echo '<li class="active">' . $shop_page_title . '</li>';
			} else if( is_archive() ){
				echo '<li class="active">' . __( 'Archives', LANGUAGE_ZONE ) . '</li>';
			}

			if ( get_query_var( 'paged' ) ) {
				echo '<li class="active">' . '(' . __( 'Page', LANGUAGE_ZONE ) . ' ' . get_query_var( 'paged' ) . ')' . '</li>';
			}

			echo '</ul>';
		}
	}
endif;

if ( !function_exists( 'miracle_link_pages' ) ) :
	/**
	 * Link Pages
	 */
	function miracle_link_pages() {
		global $post;

		$wrap = '<div class="post-pagination">%s%s%s</div>';

		$prev_post = get_previous_post();
		$prev_link = '';
		if ( !empty( $prev_post ) ) {
			$prev_link = sprintf( '<a href="%s" class="nav-prev"></a>', get_permalink( $prev_post->ID ) );
		} else {
			$prev_link = '<a href="#" class="nav-prev disabled" onclick="return false;"></a>';
		}

		$next_post = get_next_post();
		$next_link = '';
		if ( !empty( $next_post ) ) {
			$next_link = sprintf( '<a href="%s" class="nav-next"></a>', get_permalink( $next_post->ID ) );
		} else {
			$next_link = '<a href="#" class="nav-next disabled" onclick="return false;"></a>';
		}
		$link_pages = wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', LANGUAGE_ZONE ),
			'after'  => '</div>',
			'echo'   => 0
		) );
		printf( $wrap, $prev_link, $link_pages, $next_link );
	}
endif;

if ( !function_exists( 'miracle_pagination' ) ) :
	function miracle_shop_remove_add_cart_query_arg( $link, $is_shop = false ) {
		if ( $is_shop ) {
			return remove_query_arg( 'add-to-cart', $link );
		} else {
			return $link;
		}
	}
	/**
	 * Displays a page pagination
	 */
	function miracle_pagination( $is_timeline = false, $is_sidebar = false, $is_shop = false, $query = null ) {

		global $paged, $miracle_pagination_style, $wp_query;

		if ( empty( $query ) ) {
			$query = $wp_query;
		}

		if( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} else if( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		if ( !empty( $miracle_pagination_style ) && $miracle_pagination_style == 'load_more' ) {
			if ( $is_timeline && $is_sidebar ) {
				return get_next_posts_link( '', $query->max_num_pages ) ? '<a href="' . miracle_shop_remove_add_cart_query_arg( get_pagenum_link( $paged + 1 ), $is_shop ) . '" class="btn load-more style4">' . __( 'Read More', LANGUAGE_ZONE ) . '</a>' : '';
			} else if ( $is_timeline ) {
				return get_next_posts_link( '', $query->max_num_pages ) ? '<a href="' . miracle_shop_remove_add_cart_query_arg( get_pagenum_link( $paged + 1 ), $is_shop ) . '" class="load-more"><i class="fa fa-angle-double-down"></i></a>' : '';
			}
			return get_next_posts_link( '', $query->max_num_pages ) ? '<div class="text-center"><a href="' . miracle_shop_remove_add_cart_query_arg( get_pagenum_link( $paged + 1 ), $is_shop ) . '" class="btn style4 hover-blue load-more">' . __( 'Load More', LANGUAGE_ZONE ) . '</a></div>' : '';
		}
		$output = "";
		$prev = $paged - 1;
		$next = $paged + 1;
		$range = 2;
		$showitems = ($range * 2) + 1;

		$total_pages = $query->max_num_pages;
		if( !$total_pages ) {
			$total_pages = 1;
		}

		if( 1 != $total_pages ) {
			$output .= '<div class="post-pagination">';
			if ( $paged > 1 ) {
				$output .= '<a href="'.miracle_shop_remove_add_cart_query_arg( get_pagenum_link($prev), $is_shop ).'" class="nav-prev" data-page_num="'.$prev.'"></a>';
			}

			$output .= '<div class="page-links">';
			for ( $i = 1; $i <= $total_pages; $i++ ) {
				if ( 1 != $total_pages && ( ($i < $paged+$range+1 && $i > $paged-$range-1) || $total_pages <= $showitems ) ) {
					if ( $paged == $i ) {
						$output .= '<span class="active">' . $i . '</span>';
					} else {
						$output .= '<a href="' . miracle_shop_remove_add_cart_query_arg( get_pagenum_link($i), $is_shop ) . '" data-page_num="'.$i.'">' . $i . '</a>';
					}
				}
			}
			$output .= '</div>';

			if ( $paged < $total_pages ) {
				$output .= '<a href="'.miracle_shop_remove_add_cart_query_arg( get_pagenum_link($next), $is_shop ).'" class="nav-next" data-page_num="'.$next.'"></a>';
			}
			$output .= '</div>';
		}

		return $output;
	}
endif;

if ( !function_exists( 'miracle_shortcode_query_posts_args' ) ) :
	function miracle_shortcode_query_posts_args( $atts ) {
		extract( shortcode_atts( array(
			'post_type'		=> 'post',
			'ids'			=> '',
			'category'		=> '',
			'count'			=> -1,
			'orderby'		=> NULL,
			'post_status'	=> 'publish',
			'pagination'	=> 'no',
			'order'			=> 'DESC',
			'author_id'	=> '',
		), $atts ) );
		/*if ( empty( $ids ) && empty( $category ) ) {
			return FALSE;
		}*/
		if ( empty( $post_type ) ) {
			return FALSE;
		}

		if ( empty( $count ) ) {
			$count = -1;
		}
		if ( $post_type == 'portfolio' ) {
			$post_type = 'm_portfolio';
		}

		$post_args = array( 'posts_per_page' => $count );
		$post_args['post_status'] = $post_status;

		$pt = array();
		$posttypes = explode( ",", $post_type );
		foreach ( $posttypes as $posttype ) {
			array_push( $pt, $posttype );
		}
		$post_args['post_type'] = $pt;

		if ( !empty( $ids ) ) {
			$post_args = array_merge( $post_args, array( 'post__in' => explode(',', $ids) ) );
		} else if ( !empty( $category ) ) {
			$categories = explode( ",", $category );
			$gc = array();
			foreach ( $categories as $grid_cat ) {
				array_push( $gc, $grid_cat );
			}
			$gc = implode( ",", $gc );
			$post_args['category_name'] = $gc;

			$taxonomies = get_taxonomies( '', 'object' );
			$post_args['tax_query'] = array( 'relation' => 'OR' );
			foreach ( $taxonomies as $t ) {
				if ( in_array( $t->object_type[0], $pt ) ) {
					$post_args['tax_query'][] = array(
						'taxonomy' => $t->name, //$t->name,//'portfolio_category',
						'terms' => $categories,
						'field' => 'slug',
					);
				}
			}
		}

		if ( !empty( $author_id ) ) {
			$post_args = array_merge( $post_args, array( 'author__in' => array( $author_id) ) );
		}

		if ( $orderby != NULL ) {
			$post_args['orderby'] = $orderby;
		}
		$post_args['order'] = $order;

		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		if( !$page || $pagination == 'no' ) {
			$page = 1;
		}
		$post_args['paged'] = $page;

		return $post_args;
	}
endif;

if ( !function_exists( 'miracle_shortcode_get_posts' ) ) :

	/**
	 * Get posts in shortcode
	 */
	function miracle_shortcode_get_posts( $atts ) {
		$posts = get_posts( miracle_shortcode_query_posts_args( $atts ) );
		return $posts;
	}
endif;

if ( !function_exists( 'miracle_shortcode_query_posts' ) ) :

	/**
	 * Create posts query in shortcode
	 */
	function miracle_shortcode_query_posts( $atts ) {
		return new WP_Query( miracle_shortcode_query_posts_args( $atts ) );
	}
endif;


if ( ! function_exists( 'miracle_get_attachment_post_data' ) ) :

	/**
	 * Get attachments post data.
	 */
	function miracle_get_attachment_post_data( $media_items, $image_size = 'full', $orderby = 'post__in', $order = 'DESC', $posts_per_page = -1 ) {
		global $post;

		if ( empty( $media_items ) ) {
			$id = $post ? $post->ID : 0;
			if ( $orderby == 'post__in' ) {
				$orderby = 'menu_order ID';
			}
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		} else {
			$_attachments = get_posts( array( 'include' => implode(',', $media_items), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		}
		if ( empty( $attachments ) ) {
			return array();
		}

		$result = array();
		foreach ( $attachments as $post_id => $attachment ) {
			$data = array();
			$data['full'] = $data['width'] = $data['height'] = '';
			$meta_full = wp_get_attachment_image_src( $post_id, 'full' );
			if ( !empty($meta_full) ) {
				$data['full'] = esc_url($meta_full[0]);
				$data['width'] = absint($meta_full[1]);
				$data['height'] = absint($meta_full[2]);
			}
			if ( $image_size != 'full' ) {
				$meta_size = wp_get_attachment_image_src( $post_id, $image_size );
				if ( !empty($meta_size) ) {
					$data['img'] = esc_url($meta_size[0]);
					$data['img_width'] = absint($meta_size[1]);
					$data['img_height'] = absint($meta_size[2]);
				} else if ( !empty($meta_full) ) {
					$data['img'] = esc_url($meta_full[0]);
					$data['img_width'] = absint($meta_full[1]);
					$data['img_height'] = absint($meta_full[2]);
				}
			} else {
				$data['img'] = esc_url($meta_full[0]);
				$data['img_width'] = absint($meta_full[1]);
				$data['img_height'] = absint($meta_full[2]);
			}

			$data['thumbnail'] = wp_get_attachment_image_src( $post_id, 'thumbnail' );

			$data['alt'] = esc_attr( get_post_meta( $post_id, '_wp_attachment_image_alt', true ) );
			$data['caption'] = wp_kses_post( $attachment->post_excerpt );
			$data['description'] = wp_kses_post( $attachment->post_content );
			$data['title'] = get_the_title( $post_id );
			$data['permalink'] = get_permalink( $post_id );
			$data['link'] = esc_url( get_post_meta( $post_id, 'dt-img-link', true ) );
			$data['ID'] = $post_id;

			$result[] = apply_filters( 'miracle_get_attachment_post_data', $data, $media_items );
		}

		return $result;
	}

endif;

if ( !function_exists( 'miracle_get_before_post_image' ) ) :
	function miracle_get_before_post_image( $layout, $is_timeline = false ) {
		global $post;
		if ( $layout == 'classic' ) {
			echo '<div class="post-date">';
			echo '<span class="day">' . get_the_date( 'j' ) . '</span>';
			echo '<span class="month uppercase">' . get_the_date( 'M' ) . '</span>';
			echo '</div>';
		} else if ( $is_timeline === true && $layout == 'masonry' ) {
			$miracle_blog_timeline_date_format = miracle_get_option('blog_timeline_post_date_format');
			echo '<div class="post-date">' . get_the_date( $miracle_blog_timeline_date_format ) . '</div>';
		} else if ( $is_timeline === true && $layout == 'full' ) {
			$post_format_class = '';
			$post_format = get_post_format();
			if ( $post_format == 'image' ) {
				$post_format_class = 'fa fa-picture-o';
			} else if ( $post_format == 'gallery' ) {
				$post_format_class = 'fa fa-picture-o';
			} else if ( $post_format == 'video' ) {
				$post_format_class = 'fa fa-video-camera';
			} else if ( $post_format == 'audio' ) {
				$post_format_class = 'fa fa-volume-down';
			} else if ( $post_format == 'quote' ) {
				$post_format_class = 'fa fa-quote-left';
			} else {
				$post_format_class = 'fa fa-picture-o';
			}
			echo '<div class="element-type"><i class="' . $post_format_class . '"></i></div>';
		}
	}
endif;

if ( !function_exists( 'miracle_get_post_content' ) ) :
	function miracle_get_post_content( $layout = '', $echo = true ) {
		global $post;

		$post_link = get_permalink();
		$result = '';
		switch ( $layout ) {
			case 'masonry':
				$result .= miracle_get_post_meta( false );
				$result .= '<h3 class="entry-title"><a href="'. esc_url( $post_link ) .'">' . get_the_title() . '</a></h3><p>' . get_the_excerpt() . '</p>';
				break;
			case 'grid':
				global $post;
				$extra_class = "";
				if ( get_post_format() == "audio" ) {
					$extra_class = " no-margin";
				}
				$post_date = get_the_date( 'j F Y' );
				$result .= '<div class="post-date' . esc_attr( $extra_class ) . '"><span>' . $post_date . '</span></div>';
				$result .= '<h4 class="entry-title"><a href="'. esc_url( $post_link ) .'">' . get_the_title() . '</a></h4>';
				$result .= miracle_get_post_meta( false );
				$result .='<p>' . get_the_excerpt() . '</p>';
				$result .= '<div class="post-action"><a href="'. esc_url( $post_link ) .'" class="btn btn-sm style3 post-read-more">' . __( 'More', LANGUAGE_ZONE ) . '</a></div>';
				break;
			case 'full':
				$result .= '<h3 class="entry-title post-title"><a href="'. esc_url( $post_link ) .'">' . get_the_title() . '</a></h3>';
				$result .= miracle_get_post_meta( false );
				$result .= '<p>' . get_the_excerpt() . '</p>';
				break;
			case 'classic':
				$result .= miracle_print_post_share_buttons( false );
				$result .= '<h2 class="entry-title"><a href="'. esc_url( $post_link ) .'">' . get_the_title() . '</a></h2>';
				$result .= miracle_get_post_meta( false, false, true );
				$result .= '<p>' . get_the_excerpt() . '</p>';
				$result .= '<a href="'. esc_url( $post_link ) .'" class="btn style4 read-more hover-blue">' . __( 'Read More', LANGUAGE_ZONE ) . '</a>';
				break;
			default:
				break;
		}
		if ($echo) {
			return $result;
		}
		echo wp_kses_post( $result );
	}

endif;

if ( !function_exists( 'miracle_get_post_meta' ) ) :

	function miracle_get_post_meta( $echo = true, $show_date = true, $show_comment = false ) {
		global $post;

		$author_name = get_the_author_meta( 'display_name', $post->post_author );
		$miracle_blog_post_date_format = miracle_get_option('blog_post_meta_date_format');
		$post_date = get_the_date( $miracle_blog_post_date_format );

		$category = get_the_category(); 
		$category_name = '';
		$category_link = 'javascript:void(0)';
		if ( isset( $category[0] ) ) {
			$category_link = esc_url( get_category_link( $category[0]->cat_ID ) );
			$category_name =  $category[0]->cat_name;
		}

		$result = '';
		$result .= '<div class="post-meta">';
		$result .= '<span class="entry-author fn">' . sprintf(__( 'By <a href="%s">%s</a>', LANGUAGE_ZONE ), get_author_posts_url( $post->post_author ), esc_html( $author_name ) ) . '</span>';
		if ( $show_date ) {
			$result .= '<span class="entry-time"><span class="published">' .$post_date . '</span></span>';
		}
		if ( !empty( $category_name ) ) {
			$result .= '<span class="post-category">' . sprintf(__( 'in <a href="%s">%s</a>', LANGUAGE_ZONE ), esc_url( $category_link ), esc_html( $category_name ) ) . '</span>';
		}
		if ( $show_comment ) {
			$result .= '<span class="post-comment"><a href="' . get_permalink() . '#comments">';
			$comment_number = get_comments_number( $post->ID );
			if ( $comment_number == 0 ) {
				$result .= __( 'No Comments', LANGUAGE_ZONE );
			} elseif ( $comment_number == 1 ) {
				$result .= __( '1 Comment', LANGUAGE_ZONE );
			} else {
				$result .= esc_html( $comment_number . ' ' );
				$result .= __( 'Comments', LANGUAGE_ZONE );
			}
			$result .= '</a></span>';
		}
		$result .= '</div>';

		if ( $echo ) {
			echo wp_kses_post( $result );
		} else {
			return $result;
		}
	}
endif;


if ( !function_exists( 'miracle_get_post_action' ) ) :

	function miracle_get_post_action( $echo = true ) {

		global $post;

		$post_link = get_permalink();
		$result = '';
		$result .= '<a href="' . esc_url( $post_link ) . '#comments" class="btn btn-sm style3 post-comment"><i class="fa fa-comment"></i>' . get_comments_number( $post->ID ) . '</a>';
		if ( function_exists('wp_ulike') && miracle_get_option('blog_show_post_like_button') == 1 ) {
			$result .= wp_ulike('put');
		}
		if ( miracle_get_option('blog_show_post_share_button') == 1  ) {
			$result .= '<span class="post-share"><a href="javascript:void(0)" class="btn btn-sm style3"><i class="fa fa-share"></i>' . __( 'Share', LANGUAGE_ZONE ) . '</a>';
			$result .= miracle_display_share_buttons();
		}
		$result .= '</span><a href="'. esc_url( $post_link ) .'" class="btn btn-sm style3 post-read-more">' . __( 'More', LANGUAGE_ZONE ) . '</a>';
		if ($echo) {
			return $result;
		}
		echo wp_kses_post( $result );
	}

endif;

if ( !function_exists( 'miracle_print_post_share_buttons' ) ) :
	function miracle_print_post_share_buttons( $echo = true ) {
		global $post;
		
		if ( get_post_type() == 'm_portfolio' ) {
			$class = 'portfolio-action';
		} else {
			$class = 'post-action';
		}
		$result = '';
		$result .= '<div class="' . $class . '">';
		//$result .= '<a href="javascript:void(0)" class="btn btn-sm"><i class="fa fa-heart"></i>480</a>';
		if ( function_exists('wp_ulike') ) {
			$result .= wp_ulike('put');
		}
		$result .= '<span class="post-share"><a href="#" class="btn btn-sm"><i class="fa fa-share"></i>' . __( 'Share', LANGUAGE_ZONE ) . '</a>';
		$result .= miracle_display_share_buttons();
		$result .= '</span>';
		$result .= '</div>';
		if ( $echo ) {
			echo miracle_html_filter( $result );
		}
		return $result;
	}
endif;

if ( !function_exists( 'miracle_print_post_tags' ) ) :
	function miracle_print_post_tags() {
		global $post;

		$posttags = get_the_tags();
		if ( $posttags ) {
			echo '<div class="tags">';
			foreach( $posttags as $tag ) {
				echo '<a href="'. get_tag_link($tag->term_id) .'" class="tag">';
				echo esc_html( $tag->name ); 
				echo '</a>';
			}
			echo '</div>';
		}
	}
endif;

if ( !function_exists( 'miracle_display_post_author' ) ) :
	function miracle_display_post_author( $extra_class = '' ) {
		global $post;

		$class = 'about-author';
		if ( !empty( $extra_class ) ) {
			$class .= ' ' . esc_attr( $extra_class );
		}
		$author_name = get_the_author_meta( 'display_name', $post->post_author );
		$author_email = get_the_author_meta( 'user_email', $post->post_author );
		$gravatar_alt = esc_html( $author_name );


		echo '<div class="' . $class . '">';
		
		echo '<div class="author-img"><span>';
		echo miracle_get_avatar( array( 'id' => $post->post_author, 'email' => $author_email, 'size' => '134' ) );
		echo '</span></div>';

		echo '<div class="about-author-content">';
		echo '<div class="social-icons">';
		$author_facebook = get_user_meta( $post->post_author, 'author_facebook', true );
		$author_twitter = get_user_meta( $post->post_author, 'author_twitter', true );
		$author_linkedin = get_user_meta( $post->post_author, 'author_linkedin', true );
		$author_dribbble = get_user_meta( $post->post_author, 'author_dribbble', true );
		$author_gplus = get_user_meta( $post->post_author, 'author_gplus', true );

		if ( !empty( $author_facebook ) ) {
			echo '<a class="social-icon" href="' . esc_url( $author_facebook ) . '">';
			echo '<i class="fa fa-facebook"></i>';
			echo '</a>';
		}
		if ( !empty( $author_twitter ) ) {
			echo '<a class="social-icon" href="' . esc_url( $author_twitter ) . '">';
			echo '<i class="fa fa-twitter"></i>';
			echo '</a>';
		}
		if ( !empty( $author_linkedin ) ) {
			echo '<a class="social-icon" href="' . esc_url( $author_linkedin ) . '">';
			echo '<i class="fa fa-linkedin"></i>';
			echo '</a>';
		}
		if ( !empty( $author_dribbble ) ) {
			echo '<a class="social-icon" href="' . esc_url( $author_dribbble ) . '">';
			echo '<i class="fa fa-dribbble"></i>';
			echo '</a>';
		}
		if ( !empty( $author_gplus ) ) {
			echo '<a class="social-icon" href="' . esc_url( $author_gplus ) . '">';
			echo '<i class="fa fa-google-plus"></i>';
			echo '</a>';
		}

		echo '</div>';
		echo '<h4><a href="' . get_author_posts_url( $post->post_author ) . '">' . esc_html( $author_name ) . '</a></h4>';
		echo '<p>';
		the_author_meta('description');
		echo '</p>';
		echo '</div>';
		echo '</div>';
	}
endif;

if ( !function_exists( 'miracle_display_related_posts' ) ) :
	function miracle_display_related_posts() {
		global $post;
		$category = get_the_category(); 
		$category_name = '';
		$category_link = 'javascript:void(0)';
		if ( isset( $category[0] ) ) {
			$category_link = esc_url( get_category_link( $category[0]->cat_ID ) );
			$category_name =  $category[0]->cat_name;
		}

		//$terms = array();
		//$terms = wp_get_object_terms( $post->ID, 'category', array('fields' => 'ids') );
		$args = '';
		$args = wp_parse_args($args, array(
			'post__not_in' => array($post->ID),
			'ignore_sticky_posts' => 0,
			'category__in' => wp_get_post_categories($post->ID),
			'posts_per_page' => intval( miracle_get_option( 'blog_rel_posts_max', '6' ) )
		));
		$query = new WP_Query($args);
		if ( $query->have_posts() ) {
			echo '<h3 class="font-normal">' . __( 'Related Posts', LANGUAGE_ZONE ) . '</h3>';
			echo '<div class="related-posts clearfix box-sm same-height">';
			while($query->have_posts()): $query->the_post();
			?>
				<div class="related-post col-sm-6 col-md-4">
					<article class="post">
						<div class="post-image">
							<div class="img">
							<?php 
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'thumbnail' );
								} else {
									$post_format = get_post_format();
									if ( $post_format == 'video' ) {
										echo '<i class="fa fa-film"></i>';
									} else if ( $post_format == 'gallery' ) {
										echo '<i class="fa fa-picture-o"></i>';
									} else if ( $post_format == 'audio' ) {
										echo '<i class="fa fa-file-audio-o"></i>';
									} else if ( $post_format == 'quote' ) {
										echo '<i class="fa fa-quote-left"></i>';
									} else if ( $post_format == 'image' ) {
										echo '<i class="fa fa-file-image-o"></i>';
									} else {
										echo '<i class="fa fa-file-text-o"></i>';
									}
								}
							?>
							</div>
						</div>
						<div class="details">
							<h5 class="post-title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h5>
							<div class="post-meta">
								<span>by <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></span>
								<span><?php echo get_the_date( 'j M, Y' ); ?></span>
								<span>in <a href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $category_name ); ?></a></span>
							</div>
						</div>
					</article>
				</div>
			<?php
			endwhile;
			echo '</div>';
		}
		wp_reset_postdata();
	}
endif;

if ( !function_exists( 'miracle_get_post_gallery' ) ) :

	function miracle_get_post_gallery( $attachments, $hover_style = 'hover-style3', $popup = true, $caption = false, $extra_slider_class = '' ) {

		$wrap = '<div>%s%s</div>';
		echo '<div class="post-slider style3 owl-carousel';
		if ( !empty( $extra_slider_class ) ) {
			echo ' ' . $extra_slider_class;
		}
		echo '">';
		foreach ( $attachments as $attachment ) {
			if ( $popup ) {
				$wrap = '<div class="image ' . esc_attr( $hover_style ) . '">%s%s<div class="image-extras"><a href="'. esc_url( $attachment['full'] ) . '" class="post-gallery soap-mfp-popup"></a></div></div>';
			}
			$caption_html = '';
			if ( $caption ) {
				$caption_html .= '<div class="slide-text caption-animated" data-animation-type="slideInLeft" data-animation-duration="2">';
				if ( !empty( $attachment['title'] ) ) {
					$caption_html .= '<h4 class="slide-title">' . esc_html( $attachment['title'] ) . '</h4>';
				}
				if ( !empty( $attachment['description'] ) ) {
					$caption_html .= '<p>' . esc_html( $attachment['description'] ) . '</p>';
				}
				$caption_html .= '</div>';
			}
			echo sprintf( $wrap, '<img src="' . esc_url( $attachment['img'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '" width="' . esc_attr( $attachment['img_width'] ) . '" height="' . esc_attr( $attachment['img_height'] ) . '">', $caption_html );
		}
		echo '</div>';
	}

endif;


if ( !function_exists( 'miracle_get_post_video' ) ) :

	function miracle_get_post_video( $video_url, $embed_code, $video_ratio = '16:9' ) {

		if ( empty( $video_url ) && empty( $embed_code ) ) {
			return;
		}
		if ( !empty( $video_url ) ) {
			$poster = "";
			if ( has_post_thumbnail() ) {
				$poster = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				$poster = ' poster="' . esc_url( $poster[0] ) . '"';
			}
			echo '<div class="video-container mejs-skin"><div class="full-video">';
			echo '<video data-video-format="' . esc_attr( $video_ratio ) . '"' . $poster . '>';
			echo '<source src="' . esc_url( $video_url ) . '" type="video/mp4" />';
			echo '</video>';
			echo '</div></div>';
		} else {
			echo '<div class="video-container"><div class="full-video">';
			echo stripslashes( htmlspecialchars_decode( esc_html( $embed_code ) ) );
			echo '</div></div>';
		}
	}

endif;


if ( !function_exists( 'miracle_get_post_audio' ) ) :

	function miracle_get_post_audio( $audio_url, $embed_code ) {

		if ( empty( $audio_url ) && empty( $audio_code ) ) {
			return;
		}
		if ( !empty( $audio_url ) ) {
			echo '<div class="audio-container">';
			echo '<audio class="mejs-player" src="' . esc_attr( $audio_url ) . '" controls="controls" data-mejsoptions=\'{"audioHeight": 40}\'></audio>';
			echo '</div>';
		} else {
			echo '<div class="audio-container">';
			echo stripslashes( htmlspecialchars_decode( esc_html( $embed_code ) ) );
			echo '</div>';
		}
	}

endif;


if ( !function_exists( 'miracle_print_portfolio_meta' ) ) :

	function miracle_print_portfolio_meta() {
		global $post;

		$categories_list = get_the_term_list( get_the_ID(), 'm_portfolio_category', '', ', ' );
		if ( $categories_list && !is_wp_error($categories_list) ) {
			$categories_list = str_replace( array( 'rel="tag"', 'rel="category tag"' ), '', $categories_list);
			$categories_list = trim($categories_list);
		}

		echo '<h5>' . esc_html(__( 'Date', LANGUAGE_ZONE )) .'</h5>';
		$miracle_portfolio_single_date_format = miracle_get_option('portfolio_single_date_format');
		echo '<p>' . get_the_date( $miracle_portfolio_single_date_format ) . '</p>';
		echo '<h5>' . esc_html(__( 'Category', LANGUAGE_ZONE )) . '</h5>';
		echo '<p>' . $categories_list . '</p>';
		echo '<h5>' . esc_html(__( 'Author', LANGUAGE_ZONE )) . '</h5>';
		echo '<p>' . sprintf(__( 'By <a href="%s" class="author vcard" title="%s" rel="author">%s</a>', LANGUAGE_ZONE ), 
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', LANGUAGE_ZONE ), get_the_author() ) ),
					'<span class="fn">' . get_the_author() . '</span>'
			) . '</p>';
		do_action( 'miracle_portfolio_meta' );
	}

endif;


if ( !function_exists( 'miracle_nav_menu' ) ) :

	function miracle_nav_menu() {

	}

endif;

if ( !function_exists( 'miracle_get_template' ) ) :
	function miracle_get_template( $base, $extension = '', $stack = '' ) {
		if ( !empty( $stack ) ) {
			$stack .= '/';
		}
		get_template_part( 'framework/views/' . $stack . $base, $extension );
	}
endif;

if ( !function_exists( 'miracle_get_main_content_class' ) ) :
	function miracle_get_main_content_class( $class = '' ) {

		global $post;
		$sidebar = false;
		$is_full = false;

		$classes = array( 'entry-content' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}

		$sidebar = MiracleHelper::check_sidebar();

		if ( $sidebar && ( !defined( 'WP_MIRACLE_DEV' ) || !WP_MIRACLE_DEV || !isset($_GET['nosidebar']) || $_GET['nosidebar'] != 'true' ) ) {
			$classes[] = 'col-sm-8 col-md-9';
			if ( $sidebar === 'left' ) {
				$classes[] = 'pull-right';
			}
		} else {
			$classes[] = 'col-xs-12';
		}

		return esc_attr( implode(' ', $classes) );
	}
endif;

if ( !function_exists( 'miracle_display_rev_slider' ) ) :
	function miracle_display_rev_slider() {
		if ( !class_exists( 'RevSlider' ) ) {
			return;
		}
		global $post;
		$slider_active = get_post_meta( get_the_ID(), '_miracle_rev_slider', true );
		$slider		= ( $slider_active == '' ) ? 'Deactivated' : $slider_active;
		if ( $slider != 'Deactivated' ) {
			echo '<div id="slideshow">';
			do_action( 'miracle_display_slider_html' );
			echo '<div class="revolution-slider rev_slider fullscreenbanner">';
			putRevSlider( $slider );
			echo '</div></div>';
		}
	}
endif;

/**
 * get avatar function
 */
if ( ! function_exists('miracle_get_avatar') ) :
	function miracle_get_avatar( $user_data = array(), $show_default_avatar = true ) {
		$size = empty($user_data['size']) ? 96 : $user_data['size'];
		$photo = '';
		if ( ! empty( $user_data['id'] ) ) {
			$photo_url = get_user_meta( $user_data['id'], 'photo_url', true );
			if ( ! empty( $photo_url ) ) {
				$photo = '<img width="' . $size . '" height="' . $size . '" alt="avatar" src="' . $photo_url . '">';
			}
		}
		if ( empty( $photo ) ) {
			if ( MiracleHelper::validate_gravatar( $user_data['email'], $size ) ) {
				$photo = get_avatar( $user_data['email'], $size );
			} else if ( $show_default_avatar ) {
				$photo = '<img width="' . $size . '" height="' . $size . '" alt="avatar" src="' . MIRACLE_IMAGE_URL . '/avatar.jpg' . '">';
			} else {
				return '';
			}
		}
		return wp_kses_post( $photo );
	}
endif;

/**
 * comment template
 */
if ( ! function_exists( 'miracle_display_comment' ) ) :
	function miracle_display_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID() ?>">
			<div class="comment">
				<div class="author-img">
					<span>
						<?php echo miracle_get_avatar( array( 'id' => $comment->user_id, 'email' => $comment->comment_author_email, 'size' => 72 ) ); ?>
					</span>
				</div>
				<div class="comment-content">
					<div class="comment-author">
						<h5 class="comment-author-name"><?php echo get_comment_author_link() ?></h5>
						<span class="comment-date"><?php comment_date(); ?></span>
						<div class="row">
							<div class="comment-text col-sm-9 col-md-10">
								<p><?php comment_text(); ?></p>
							</div>
							<div class="col-sm-3 col-md-2 text-right">
								<?php $comment_reply_link = get_comment_reply_link(array_merge( $args, array('reply_text' => __('REPLY', LANGUAGE_ZONE), 'depth' => $depth )));
								$comment_reply_link = str_replace("class='comment-reply-link", "class='comment-reply-link btn btn-sm hover-blue style4", $comment_reply_link);
								echo miracle_html_filter( $comment_reply_link ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</li>
	<?php }
endif;

/**
 * Display share buttons
 */
if ( !function_exists( 'miracle_display_share_buttons' ) ) :
	function miracle_display_share_buttons( $wrap_tag = 'span', $wrap_class = '', $item_class = '', $icon_extra_class = '', $hover_title = 'right' ) {
		global $post;
		$buttons = null;
		if ( get_post_type() == 'post' ) {
			$buttons = miracle_get_option('blog_post_sharing');
		} else if ( get_post_type() == 'm_portfolio' ) {
			$buttons = miracle_get_option('portfolio_sharing');
		} else if ( get_post_type() == 'product' ) {
			$buttons = miracle_get_option('product_sharing');
		}
		if ( empty( $buttons ) ) {
			return;
		}

		$protocol = "http";
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) $protocol = "https";

		$html = '';
		$social_sites = miracle_get_social_site_names();
		$post_title = $post->post_title;
		$post_url = get_permalink();

		if ( empty( $wrap_class ) ) {
			$wrap_class = 'share-links';
		}
		$html .= '<' . $wrap_tag . ' class="' . esc_attr( $wrap_class ) . '">';

		if ( !isset( $buttons ) ) {
			$buttons = array();
		}

		foreach ( $social_sites as $button => $desc ) {

			if ( !in_array( $button, $buttons ) ) {
				continue;
			}
			$classes = array();
			$share_title = __( 'share', LANGUAGE_ZONE );
			$custom = '';
			$url = '';
			$icon_class = esc_attr('fa fa-' . $button);

			if ( $button == 'twitter' ) {
				$classes[] = 'twitter';
				$share_title = __( 'tweet', LANGUAGE_ZONE );
				$url = add_query_arg( array('status' => urlencode($post_title . ' ' . $post_url) ), $protocol . '://twitter.com/home' );
			} else if ( $button == 'facebook' ) {
				$url_args = array( 's=100', urlencode('p[url]') . '=' . esc_url($post_url), urlencode('p[title]') . '=' . urlencode($post_title) );
				if ( has_post_thumbnail() ) {
					$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
					if ( $thumbnail ) {
						$url_args[] = urlencode('p[images][0]') . '=' . esc_url($thumbnail[0]);
					}
				}

				// mobile args
				$url_args[] = 't=' . urlencode($post_title);
				$url_args[] = 'u=' . esc_url($post_url);

				$classes[] = 'facebook';

				$url = $protocol . '://www.facebook.com/sharer.php?' . implode( '&', $url_args );
			} else if ( $button == 'googleplus' ) {
				$icon_class = 'fa fa-google';
				$post_title = str_replace(' ', '+', $post_title);
				$classes[] = 'google';
				$url = add_query_arg( array('url' => $post_url, 'title' => $post_title), $protocol . '://plus.google.com/share' );
			} else if ( $button == 'pinterest' ) {
				$url = '//pinterest.com/pin/create/button/';
				$custom = ' data-pin-config="above" data-pin-do="buttonBookmark"';

				if ( wp_attachment_is_image() ) {
					$image = wp_get_attachment_image_src($post->ID, 'full');

					if ( !empty($image) ) {
						$url = add_query_arg( array(
							'url'			=> $post_url,
							'media'			=> $image[0],
							'description'	=> $post_title
							), $url
						);

						$custom = '';
					}
				}

				$classes[] = 'pinterest';
				$share_title = __( 'pin it', LANGUAGE_ZONE );
			} else if ( $button == 'linkedin' ) {
				$post_title = str_replace(' ', '+', $post_title);
				$classes[] = 'linkedin';
				$url = add_query_arg( array('mini'=> 'true', 'url' => urlencode($post_url), 'title' => $post_title, 'summary' => '', 'source' => str_replace(' ', '+', get_bloginfo('name'))), $protocol . '://www.linkedin.com/shareArticle' );
			}

			$desc = esc_attr($desc);
			$share_title = esc_attr($share_title);
			$url = esc_url( $url );
			if ( !empty( $item_class ) ) {
				$classes[] = $item_class;
			}
			if ( !empty( $icon_extra_class ) ) {
				$icon_class .= ' ' . esc_attr( $icon_extra_class );
			}

			$share_button = sprintf(
				'<a href="%s" class="%s" target="_blank" title="%s"%s data-toggle="tooltip" data-placement="' . esc_attr( $hover_title ) . '"><i class="%s"></i></a>',
				$url,
				esc_attr( implode(' ', $classes) ),
				$desc,
				$custom,
				$icon_class
			);

			$html .= apply_filters( 'miracle_share_button', $share_button, $button, $classes, $url, $desc );
		}

		$html .= '</' . $wrap_tag . '>';
		$html = apply_filters( 'miracle_display_share_buttons', $html );

		return $html;
	}
endif;

/**
 * Get portfolio thumbnail
 */
if ( !function_exists( 'miracle_get_portfolio_thumbnail' ) ) :
	function miracle_get_portfolio_thumbnail( $post_id, $image_size = 'full' ) {
		$post_image = get_the_post_thumbnail( $post_id , $image_size );
		if ( !empty( $post_image ) ) {
			return $post_image;
		}
		$media_type = get_post_meta( $post_id, '_miracle_portfolio_item_media_type', true );
		if ( $media_type == 'gallery' ) {
			$gallery = get_post_gallery( $post_id, false );
			if ( !empty($gallery['ids']) ) {
				$gallery_ids = explode( ',', $gallery['ids'] );
				$first_id = $gallery_ids[0];
				return wp_get_attachment_image( $first_id, $image_size );
			}
		} else if ( $media_type == 'video' ) {
			$video_url = esc_url( get_post_meta( $post_id, '_miracle_video_url', true ) );
			$video_embed_code = esc_html( get_post_meta( $post_id, '_miracle_video_embed', true ) );
			$video_ratio = esc_attr( get_post_meta( $post_id, '_miracle_video_ratio', true ) );
			ob_start();
			echo '<div class="video-container"><div class="full-video">';
			miracle_get_post_video( $video_url, $video_embed_code, $video_ratio );
			echo '</div></div>';
			$result = ob_get_contents();
			ob_end_clean();
			return $result;
		}
		return FALSE;
	}
endif;

/**
 * Get Portfolio Query Args
 */
if ( !function_exists( 'miracle_get_portfolio_query_args' ) ) :
	function miracle_get_portfolio_query_args( $filters, $count, $paged = -1, $ids = '', $oderby = '', $order = '' ) {
		if ( $paged === -1 ) {
			if( get_query_var('paged') ) {
				$paged = get_query_var('paged');
			} else if( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}
		}
		if ( count( $filters ) == 1 && in_array( 'All Categories', $filters ) ) {

			$args = array(
				'post_type' => 'm_portfolio',
				'posts_per_page' => $count,
				'paged' => $paged
			);
		} else {
			$args = array(
				'post_type' => 'm_portfolio',
				'posts_per_page' => $count,
				'paged' => $paged,
				'tax_query' => array(
					array(
						'taxonomy' => 'm_portfolio_category',
						'field' => 'id',
						'terms' => $filters
					)
				)
			);
		}
		if ( !empty( $ids ) ) {
			$args = array_merge( $args, array( 'post__in' => $ids ) );
		}

		if ( !empty( $orderby ) ) {
			$args['orderby'] = $orderby;
		}
		if ( !empty( $order ) ) {
			$args['order'] = $order;
		}
		$args['post_status'] = 'publish';
		return $args;
	}
endif;

if ( !function_exists( 'miracle_html_filter' ) ) :
	function miracle_html_filter( $content ) {
		//return wp_kses_post( $content );
		return $content;
	}
endif;