<?php
/*
 * Shortcodes
 */
if ( !class_exists( 'MiracleShortcodes' ) ) :

class MiracleShortcodes {
	public $shortcodes = array(
		/* Layout */
		"row",
		"column",
		"five_column",
		"one_half",
		"one_third",
		"one_fourth",
		"two_third",
		"three_fourth",
		"container",
		"block",

		/* Typography */
		"dropcap",
		"blockquote",
		"headline",
		"highlight",
		"divider",
		"icon_box",
		"social_links",
		"social_link",
		"bullet_list",
		"table",
		"td",

		/* Content */
		"animation",
		"blog_posts",
		"post_slider",
		"post_carousel",
		"banner",
		"toggles",
		"toggle",
		"tabs",
		"tab",
		"button",
		"section_with_title",
		"alert",
		"note",
		"callout_box",
		"process",
		"process_item",
		"pricing_table_container",
		"pricing_table",
		/*"pricing_table_content",
		"pricing_table_features",
		"pricing_table_action",*/
		"progress_bars",
		"progress_bar",
		"image_box",
		"infographic_pie",
		"isotope",
		"iso_item",
		"counter",
		"team_member",
		"testimonials",
		"testimonial",
		"contact_addresses",
		"contact_address",

		/* Media */
		"carousel",
		"slide",
		"image",
		"image_ads",
		"image_banner",
		"banner_caption",
		"image_gallery",
		"image_parallax",
		"logo_slider",
		"logo",
		"video_parallax",
		"video_caption",

		/* Plugin Additions */
		"masonry_products",
	);

	function __construct() {
		add_action( 'init', array( $this, 'add_shortcodes' ) );
	}

	/**
	 * Add shortcodes
	 */
	function add_shortcodes() {
		foreach ( $this->shortcodes as $shortcode ) {
			$function_name = 'shortcode_' . $shortcode;
			add_shortcode( $shortcode, array( $this, $function_name ) );
		}
		// to avoid nested shortcode issue for block
		for ( $i = 1; $i < 10; $i++ ) {
			add_shortcode( 'block' . $i, array( $this,'shortcode_block' ) );
		}
		add_shortcode( 'box', array( $this,'shortcode_block' ) );
	}


	/* Layout */
	// shortcode row
	function shortcode_row( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'add_clearfix' => '',
			'children_same_height' => '',
			'id' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $add_clearfix == 'yes' ) $class .= ' add-clearfix';
		if ( $children_same_height == 'yes' ) $class .= ' same-height';

		$row_attrs = '';
		if ( !empty( $id ) ) {
			$row_attrs .= ' id="' . esc_attr( $id ) . '"';
		}

		$result = '<div class="row' . esc_attr( $class ) . '"' . $row_attrs . '>';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	// shortcode column
	function shortcode_column( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'lg'		=> '',
			'md'		=> '',
			'sm'		=> '',
			'sms'	   => '',
			'xs'		=> '',
			'lgoff'	 => '',
			'mdoff'	 => '',
			'smoff'	 => '',
			'smsoff'	=> '',
			'xsoff'	 => '',
			'lghide'	=> '',
			'mdhide'	=> '',
			'smhide'	=> '',
			'smshide'   => '',
			'xshide'	=> '',
			'lgclear'   => '',
			'mdclear'   => '',
			'smclear'   => '',
			'smsclear'  => '',
			'xsclear'   => '',
			'class'	 => ''
		), $atts ) );

		$devices = array( 'lg', 'md', 'sm', 'sms', 'xs' );
		$classes = array();
		foreach ( $devices as $device ) {

			//grid column class
			if ( ${$device} != '' ) $classes[] = 'col-' . $device . '-' . ${$device};

			//grid offset class
			$device_off = $device . 'off';
			if ( ${$device_off} != '' ) $classes[] = 'col-' . $device . '-offset-' . ${$device_off};

			//grid hide class
			$device_hide = $device . 'hide';
			if ( ${$device_hide} == 'yes' ) $classes[] = 'hidden-' . $device;

			//grid clear class
			$device_clear = $device . 'clear';
			if ( ${$device_clear} == 'yes' ) $classes[] = 'clear-' . $device;

		}
		if ( ! empty( $class ) ) $classes[] = $class;

		$result = '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	// shortcode one_half
	function shortcode_one_half( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-6' . esc_attr( $class ) . ' one-half">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	// shortcode one_third
	function shortcode_one_third( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-4' . esc_attr( $class ) . ' one-third">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	// shortcode two_third
	function shortcode_two_third( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-8' . esc_attr( $class ) . ' two-third">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	// shortcode one_fourth
	function shortcode_one_fourth( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-3 ' . esc_attr( $class ) . ' one-fourth">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	// shortcode three_fourth
	function shortcode_three_fourth( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-9 ' . esc_attr( $class ) . ' three-fourth">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode container
	function shortcode_container( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '<div class="container' . esc_attr( $class ) . '">';
		$result .= do_shortcode($content);
		$result .= '</div>';
		return $result;
	}

	// shortcode block
	function shortcode_block( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'id' => '',
			'type' => '',
			'class' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		switch ( $type ) {
			case "small": // margin-bottom : 20
				$class = 'box-sm' . $class;
				break;
			case "medium": // margin-bottom : 30
				$class = 'box' . $class;
				break;
			case "large": // margin-bottom : 40
				$class = 'box-lg' . $class;
				break;
			case "x-large": // margin-bottom : 60
				$class = 'block' . $class;
				break;
			default:
				$class = 'box' . $class;
				break;
		}

		$result = sprintf( '<div class="' . esc_attr( $class ) . '"%s>', empty( $id ) ? '' : ' id="' . esc_attr( $id ) . '"' );
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* Typography */
	// Dropcap
	function shortcode_dropcap( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'style1',
			'class'	=> ''
		), $atts ) );

		$classes = array( 'dropcap', $style );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<span class="%s">%s</span>', esc_attr( implode(' ', $classes) ), $content );
	}

	// Blockquote
	function shortcode_blockquote( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'style1',
			'author'=> '',
			'class'	=> ''
		), $atts ) );

		$classes = array( 'blockquote', $style );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		$author_block = '';
		if ( !empty( $author ) ) {
			$author_block = sprintf( '<p class="name">&#8212;%s</p>', esc_html( $author ) );
		}
		return sprintf( '<blockquote class="%s"><p>%s</p>%s</blockquote>',
			esc_attr( implode(' ', $classes) ),
			esc_html( strtr($content, array('<p>' => '', '</p>' => '')) ),
			$author_block
		);
	}

	//Headline
	function shortcode_headline( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'level' => 'h2',
			'title'	=> '',
			'title_class' => '',
			'sub_title' => '',
			'lg'	=> '',
			'md'	=> '',
			'sm'	=> '',
			'class' => ''
		), $atts ) );
		$classes = array( 'heading-box' );
		if ( !empty( $lg ) ) {
			$classes[] = 'col-lg-' . $lg;
		}
		if ( !empty( $md ) ) {
			$classes[] = 'col-md-' . $md;
		}
		if ( !empty( $sm ) ) {
			$classes[] = 'col-sm-' . $sm;
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}

		$title_classes = 'box-title';
		if ( !empty( $title_class ) ) {
			$title_classes .= ' ' . $title_class;
		}
		$result = '';
		$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
		$result .= '<' . esc_html( $level ) . ' class="' . esc_attr( $title_classes ) . '">' . esc_html( $title ) . '</' . esc_html( $level ) . '>';
		if ( !empty( $sub_title ) ) {
			$result .= '<p class="desc-lg">' . esc_html( $sub_title ) . '</p>';
		}
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	// Highlight
	function shortcode_highlight( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );
		$classes = array( 'highlight' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<span class="%s">%s</span>', esc_attr( implode(' ', $classes) ), esc_html( $content ) );
	}

	// Divider
	function shortcode_divider( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'solid',
			'color'	=> '',
			'class'	=> ''
		), $atts ) );

		$classes = array();
		switch( $style ) {
			case 'dotted':
				$classes[] = 'dotted';
				break;
			case 'thick':
				$classes[] = 'thick';
				break;
			default: //solid
				break;
		}
		switch( $color ) {
			case 'heading':
				$classes[] = 'color-heading';
				break;
			case 'text':
				$classes[] = 'color-text';
				break;
			case 'light1':
				$classes[] = 'color-light';
				break;
			case 'light2':
				$classes[] = 'color-light1';
				break;
			default: //skin color
				break;
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<hr class="%s">', esc_attr( implode(' ', $classes) ) );
	}

	// Icon Box
	function shortcode_icon_box( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'			=> '',
			'icon_class'	=> '',
			'style'			=> 'centered1',
			'icon_color'	=> 'default',
			'class'			=> '',
			'animation_type'=> '',
			'animation_delay' => '',
			'animation_duration' => '',
		), $atts ) );
		if ( $icon_class == '' ) {
			return;
		}
		$classes = array( 'icon-box', 'style-' . substr($style, 0, strlen($style) - 1) . '-' . substr($style, -1) );
		if ($icon_color == 'blue' ) {
			$classes[] = 'icon-color-blue';
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}

		$icon_box_wrap = '';
		if ( strpos( $style, "centered" ) !== FALSE ) {
			$icon_box_wrap = '<div class="%s"%s><i class="%s"></i><h4 class="box-title">%s</h4><div class="box-content"><p>%s</p></div></div>';
		} else if ( strpos( $style, "side" ) !== FALSE && $style != "side5" && $style != "side6" ) {
			$icon_box_wrap = '<div class="%s"%s><i class="%s"></i><div class="box-content"><h4 class="box-title">%s</h4><p>%s</p></div></div>';
		} else {
			$icon_box_wrap = '<div class="%s"%s><div class="icon-container"><i class="%s"></i></div><div class="box-content"><h4 class="box-title">%s</h4><p>%s</p></div></div>';
		}

		$icon_box_attrs = '';
		if ( !empty( $animation_type ) ) {
			$classes[] = 'animated';
			$icon_box_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
			if ( !empty( $animation_duration ) )  {
				$icon_box_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
			}
			if ( !empty( $animation_delay ) )  {
				$icon_box_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
			}
		}

		return sprintf( $icon_box_wrap,
			esc_attr( implode(' ', $classes) ),
			$icon_box_attrs,
			esc_attr( $icon_class ),
			esc_html( $title ),
			do_shortcode( $content )
		);
	}

	// Social links
	function shortcode_social_links( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'style1',
			'size'	=> 'normal',
			'class'	=> ''
		), $atts ) );
		switch ( $size ) {
			case 'large':
				$size = 'size-lg';
				break;
			case 'small':
				$size = 'size-sm';
				break;
			case 'medium':
				$size = 'size-md';
				break;
			default:
				$size = '';
				break;
		}
		$classes = array( 'social-icons', $style );
		if ( $size != '' ) {
			$classes[] = $size;
		}
		if ( $class != '' ) {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}
	function shortcode_social_link( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon_class'	=> '',
			'link'	=> '',
			'class'	=> ''
		), $atts ) );
		if ( $icon_class == '' ) {
			return;
		}
		$classes = array( 'social-icon' );
		if ( $class != '' ) {
			$classes[] = $class;
		}
		return sprintf( '<a href="%s" class="%s"><i class="%s"></i></a>',
			esc_url( $link ),
			esc_attr( implode(' ', $classes) ),
			esc_attr( $icon_class )
		);
	}

	// Bullet list
	function shortcode_bullet_list( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'arrow',
			'size' => '',
			'has_hover_effect'	=> 'no',
			'class'	=> ''
		), $atts ) );

		$class = empty( $class ) ? '' : ( ' ' . $class );
		$class = $style . $class;
		if ( !empty( $size ) ) {
			$class = $class . ' size-' . $size;
		}
		if ( $has_hover_effect == 'yes' ) {
			$class = $class . ' hover-effect';
		}
		$result = str_replace( '<ul>', '<ul class="' . esc_attr( $class ) . '">', $content );
		$result = str_replace( '<li>', '<li>', $result );
		$result = do_shortcode( $result );

		return $result;
	}

	// Table Style
	function shortcode_table( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );
		$classes = array( 'st-table' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode($content) );
	}

	// Table Cell Style
	function shortcode_td( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'vertical_align'	=> 'middle',
			'class'				=> ''
		), $atts ) );
		$classes = array( 'st-td' );
		$inline_style = array();
		if ( $vertical_align !== 'middle' ) {
			$inline_style[] = 'vertical-align: ' . esc_attr( $vertical_align );
		}
		if ( !empty( $inline_style ) ) {
			$inline_style = ' style="' . implode('; ', $inline_style) . '"';
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s"%s>%s</div>', esc_attr( implode(' ', $classes) ), $inline_style, do_shortcode( $content ) );
	}


	/* Content */
	// Animation
	function shortcode_animation( $atts, $content = null ) {
		$variables = array( 'type' => 'fadeInUp', 'duration' => '1', 'delay' => '0', 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		if ( empty( $type ) ) {
			return;
		}
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<div class="animated' . esc_attr( $class ) . '" data-animation-type="' . esc_attr( $type ) . '" data-animation-duration="' . esc_attr( $duration ) . '" data-animation-delay="' . esc_attr( $delay ) . '" >';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	// blog posts
	function shortcode_blog_posts( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'post_type'     => 'post',
			'portfolio_style' => 'fancy',
			'portfolio_columns' => '3',
			'style'         => 'masonry',
			'display_mode'  => 'default',
			'columns'       => '3',
			'ids'			=> '',
			'category'		=> '',
			'count'			=> -1,
			'pagination'    => 'no',
			'author_id'     => '',
			'load_style'    => 'default',
			'orderby'       => 'date',
			'order'         => 'DESC',
			'class'         => ''
		), $atts ) );

		if ( $post_type == 'portfolio' ) {
			$post_type = 'm_portfolio';
		}

		if ($style == 'timeline') {
			$orderby = 'date';
			$atts['orderby'] = 'date';
		}

		if ($columns > 6) { $columns = 6; }

		global $miracle_pagination_style;
		if ( $pagination == 'yes' ) {
			$miracle_pagination_style = $load_style;
		}
		if ( $class != '' ) {
			$class .= ' ';
		}
		$class .= 'shortcode';

		$is_sidebar = MiracleHelper::check_sidebar();


		$atts['post_type'] = $post_type;
		$query_args = miracle_shortcode_query_posts_args( $atts );
		if( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} else if( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}
		$query_args['paged'] = $paged;
		$the_query = new WP_Query( $query_args );

		$result = '';

		if ( $the_query->have_posts() ) {

			if ( $post_type == 'post' ) {

				if ( $style == 'grid' && $display_mode == 'carousel' ) {
					set_query_var( 'display_mode', 'carousel' );
				} 
				set_query_var( 'layout', $style );
				set_query_var( 'columns', $columns );
				if ( !empty( $author_id ) ) {
					set_query_var( 'author_id', $author_id );
				}
				set_query_var( 'extra_class', $class );
				set_query_var( 'show_pagination', $pagination == 'no' ? false : true );
				set_query_var( 'is_sidebar', $is_sidebar );
				set_query_var( 'blog_posts_attrs', ' data-query_ordery="' . esc_attr( $orderby ) . '" data-query_order="' . esc_attr( $order ) . '" data-ids="' . $ids . '" data-category="' . $category . '" data-count="' . $count . '" data-is_sidebar="' . $is_sidebar . '"' );
				set_query_var( 'main_query_args', $query_args );
				ob_start();
				miracle_get_template( 'content', 'blog' );
				$result = ob_get_contents();
				ob_end_clean();

			} else if ( $post_type == 'm_portfolio' ) {

				if ( !empty( $portfolio_style ) ) {
					$style = $portfolio_style;
				}
				if ( !empty( $portfolio_columns ) ) {
					$columns = $portfolio_columns;
				}
				$classes = array( 'iso-container', 'iso-col-' . esc_attr( $columns ) );
				$classes[] = 'style-' . esc_attr( $style );
				$image_size = MiracleHelper::get_thumbnail_size( $columns );

				$container_attrs = "";
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				if ( $miracle_pagination_style == "ajax" || $miracle_pagination_style == "load_more" ) {
					$container_attrs .= ' data-pagination="' . esc_attr( $miracle_pagination_style ) . '"';
					$container_attrs .= ' data-layout="' . esc_attr( $style ) . '"';
					$container_attrs .= ' data-count="' . esc_attr( $count ) . '"';
					$container_attrs .= ' data-page_num="' . esc_attr( $paged ) . '"';
					$container_attrs .= ' data-orderby="' . esc_attr( $orderby ) . '"';
					$container_attrs .= ' data-order="' . esc_attr( $order ) . '"';
					$container_attrs .= ' data-image_size="' . esc_attr( $image_size ) . '"';

					if ( empty( $category ) ) {
						$filters = 'All Categories';
					} else {
						$categories = explode( ",", $category );
						$filters = array();
						foreach ( $categories as $c ) {
							$c_id = get_terms( 'm_portfolio_category', 'name__like=' . $c );
							if ( !empty( $c_id[0]->term_id ) ) {
								$filters[] = $c_id[0]->term_id;
							}
						}
						$filters = implode(',', $filters);
					}
					$container_attrs .= ' data-filters="' . $filters . '"';

					if ( !empty( $ids ) ) {
						$container_attrs .= ' data-ids="' . esc_attr( $ids ) . '"';
					}
				}

				$result .= '<div class="post-wrapper portfolio-container' . ( empty( $class ) ? '' : ' ' . esc_attr( $class ) ) . '"' . $container_attrs . '>';
				$result .= '<div class="'. esc_attr( implode(' ', $classes) ) .'">';
				while ( $the_query->have_posts() ) : $the_query->the_post(); 
					$portfolio_classes = array( 'iso-item', 'filter-all' );

					set_query_var( 'image_size', $image_size );
					set_query_var( 'post_classes', array( 'post' ) );
					ob_start();
					miracle_get_template( 'portfolio', $style . '-content' );
					$post_html = ob_get_contents();
					ob_end_clean();

					$result .= sprintf( '<div class="%s">%s</div>', implode( ' ', $portfolio_classes ), $post_html );
				endwhile;
				wp_reset_postdata();

				$result .= '</div>';
				if ( $pagination == 'yes' ) {
					$result .= miracle_pagination( false, false, false, $the_query );
				}
				$result .= '</div>';
			}
		}

		return $result;
	}

	// Post Slider
	function shortcode_post_slider( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'		=> 'style1',
			'transition_effect' => '',
			'autoplay' => '5000',
			'class'		=> ''
		), $atts ) );

		global $post;
		$posts = miracle_shortcode_get_posts( $atts );
		if ( empty( $posts ) ) {
			return;
		}

		if ( $class != '' ) {
			$class = $class . ' ';
		}

		$imgClass = '';
		switch ( $style ) {
			case 'style1':
				$class .= 'post-slider style1 owl-carousel';
				break;
			case 'style2':
				$class .= 'post-slider style2 owl-carousel';
				break;
			case 'style3':
				$class .= 'post-slider style3 owl-carousel';
				break;
			case 'style4':
				$class .= 'post-slider soap-gallery style2';
				$imgClass = 'sgImg';
				break;
			case 'style5':
				$class .= 'post-slider style4 owl-carousel';
				break;
			case 'style6':
				$class .= 'post-slider style5 owl-carousel';
				break;
			default:
				return;
		}
		$slider_attrs = '';
		if ( !empty( $transition_effect ) ) {
			$slider_attrs .= 'data-transitionstyle="' . esc_attr( $transition_effect ) . '"';
		}
		if ( !empty( $autoplay ) ) {
			$slider_attrs .= ' data-autoplay="' . esc_attr( $autoplay ) . '"';
		}
		$result = sprintf( '<div class="' . esc_attr( $class ) . '" %s>', $slider_attrs );
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$alt = esc_attr( get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true ) );

			$imgAttrs = '';
			if ( $style == 'style6' ) {
				$result .= '<div>';
			}
			if ( $style == 'style4' ) {
				$thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
				$imgAttrs = ' data-thumb="' . $thumbnail_url[0] . '"';
			}
			$result .= '<a href="' . get_permalink() . '" class="' . $imgClass . '"'. $imgAttrs . '>';
			$result .= '<img src="' . $image_url[0] . '" alt="' . $alt . '">';
			switch ( $style ) {
				case 'style2':
					$result .= '<div class="slide-text">';
					$result .= '<h4 class="slide-title">' . get_the_title( $post->ID ) . '</h4>';
					$result .= '<span class="meta-info">';
					$category = get_the_category( $post->ID ); 
					if ( $category[0] ){
						$result .= 'In ' . $category[0]->cat_name . '  .  ';
					}
					$result .= get_the_date( '', $post->ID );
					$result .= '</span>';
					$result .= '</div>';
					break;
				case 'style3':
					$result .= '<div class="slide-text caption-animated" data-animation-type="slideInLeft" data-animation-duration="2">';
					$result .= '<h4 class="slide-title">' . get_the_title( $post->ID ) . '</h4>';
					$result .= '<p>' . $post->post_excerpt . '</p>';
					$result .= '</div>';
					break;
				case 'style5':
					$result .= '<div class="slide-text"><div class="caption-wrapper">';
					$result .= '<h2 class="caption caption-animated size-lg" data-animation-type="fadeInLeft" data-animation-duration="2" data-animation-delay="0">' . get_the_title( $post->ID ) . '</h2>';
					if ( !empty ( $post->post_excerpt ) ) {
						$result .= '<br><h3 class="caption caption-animated size-md" data-animation-type="fadeInLeft" data-animation-duration="2" data-animation-delay="1">' . $post->post_excerpt . '</h3>';
					}
					$result .= '</div></div>';
					break;
				case 'style6':
					$result .= '</a>';
					$result .= '<div class="slide-text"><div class="caption-wrapper">';
					$result .= '<p>' . get_the_excerpt() . '</p>';
					$result .= '<a href="' . get_permalink() . '" class="btn btn-sm style4">' . __( 'More', LANGUAGE_ZONE ) . '</a>';
					$result .= '</div></div>';
					break;
				default:
					break;
			}
			wp_reset_postdata();

			if ( $style != 'style6' ) {
				$result .= '</a>';
			}
			if ( $style == 'style6' ) {
				$result .= '</div>';
			}
		}
		$result .= '</div>';

		return $result;
	}

	// Post Carousel
	function shortcode_post_carousel( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'			=> 'style1',
			'columns'		=> '4',
			'title'			=> '',
			'autoplay'		=> '5000',
			'class'			=> ''
		), $atts ) );

		$posts = miracle_shortcode_get_posts( $atts );
		if ( empty( $posts ) ) {
			return;
		}

		// set max columns
		if ( $columns <= 0 ) {
			$columns = 1;
		}
		if ( $columns > 6 ) {
			$columns = 6;
		}

		if ( $class != '' ) {
			$class = ' ' . $class;
		}

		$slider_attrs = array( 'data-items="' . $columns . '"' );
		switch ( $columns ) {
			case 2:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 1], [992, 2], [1200, 2]]"';
				break;
			case 3:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 3]]"';
				break;
			case 4:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 4]]"';
				break;
			case 5:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 5]]"';
				break;
			case 6:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 6]]"';
				break;
		}
		if ( !empty( $autoplay ) ) {
			$slider_attrs[] = 'data-autoplay="' . esc_attr( $autoplay ) . '"';
		}

		$slider_wrap = '';
		$slider_classes = array( 'owl-carousel', 'post-slider' );
		$template_name = '';
		switch ( $style ) {
			case 'style1':
				$slider_wrap .= '<div class="post-wrapper' . esc_attr( $class ) . '">
									<div class="%s" %s>%s</div>
									<div class="title-section">
										<div class="title-section-wrapper">
											<div class="container">
												<p>' . esc_html( $title ) . '</p>
											</div>
										</div>
									</div>
								</div>';
				$slider_classes[] = 'style6';
				$template_name = 'fancy-content';
				break;
			case 'style2':
				$slider_wrap .= '<div class="overflow-hidden' . esc_attr( $class ) . '">';
				$slider_wrap .= empty($title) ? '' : '<h3>' . esc_html( $title ) . '</h3>';
				$slider_wrap .= '<div class="%s" %s>%s</div></div>';
				$slider_classes[] = 'style7';
				$slider_classes[] = 'post-wrapper';
				$template_name = 'grid-content';
				break;
			default:
				return;
		}

		$result = '';
		set_query_var( 'image_size', MiracleHelper::get_thumbnail_size( ( $columns > 1 ? $columns - 1 : 1 ) ) ); // for full width style
		set_query_var( 'post_classes', array( 'post' ) );
		$post_wrap = '';
		global $post;

		foreach ($posts as $post) {
			setup_postdata( $post );
			ob_start();

			get_template_part( MIRACLE_VIEWS_PATH . 'portfolio', $template_name );

			$post_html = ob_get_contents();
			ob_end_clean();
			if ( !empty( $post_wrap ) ) {
				$post_html = sprintf( $post_wrap, $post_html );
			}
			$result .= $post_html;
		}
		wp_reset_postdata();

		$result = sprintf( $slider_wrap,
			esc_attr( implode(' ', $slider_classes) ),
			stripslashes( htmlspecialchars_decode( esc_html( implode(' ', $slider_attrs) ) ) ),
			$result
		);

		return $result;
	}

	// Post Carousel
	function shortcode_banner( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'columns'	=> '3',
			'style'		=> 'animated',
			'class'		=> ''
		), $atts ) );

		$the_query = miracle_shortcode_query_posts( $atts );
		if ( !$the_query->have_posts() ) {
			return;
		}

		$col_classes = array();
		if ( $columns == 1 ) {
			$col_classes[] = 'col-xs-12';
		} else if ( $columns == 2 ) {
			$col_classes[] = 'col-sm-6';
		} else if ( $columns == 3 ) {
			$col_classes[] = 'col-sm-6 col-md-4';
		} else {
			$col_classes[] = 'col-sm-6 col-md-3';
		}
		$classes = array( 'shortcode-banner' );
		if ( $style == 'animated' ) {
			$classes[] = 'style-' . $style;
		}
		$row_class = 'row';
		if ( $class != '' ) {
			$row_class .= ' ' . $class;
		}

		$result = '';
		$image_size = MiracleHelper::get_thumbnail_size( $columns );
		$result .= '<div class="' . esc_attr( $row_class ) . '">';
		global $post;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$result .= '<div class="' . esc_attr( implode(' ', $col_classes) ) . '">';
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
			$result .= get_the_post_thumbnail( $post->ID, $image_size );
			$result .= '<div class="shortcode-banner-inside"><div class="shortcode-banner-content">';
			if ( $style != 'animated' ) {
				$result .= '<h4 class="banner-title">' . get_the_title() . '</h4>';
			} else {
				$result .= '<h3 class="banner-title">' . get_the_title() . '</h3>';
			}
			$result .= '<div class="details"><p>' . get_the_excerpt() . '</p></div>';
			if ( $style != 'animated' ) {
				$result .= '<a href="' . get_permalink() . '" class="btn style4 btn-sm">' . __( 'More', LANGUAGE_ZONE ) . '</a>';
			}
			$result .= '</div>';
			$result .= '</div></div>';
			$result .= '</div>';
		endwhile;
		$result .= '</div>';
		wp_reset_postdata();

		return $result;

	}

	// toggles
	public $miracle_toggles_index = 1; //to generate unique accordion id
	public $miracle_toggles_type = 'toggle'; //toggle type ( accordion|toggle )
	public $miracle_toggles_style = 'style1';

	function shortcode_toggles( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'toggle_type'	=> 'toggle',
			'style'		=> 'style1',
			'class' 		=> ''
		), $atts ) );

		if ( empty( $style ) ) {
			$style = 'style1';
		}
		$this->miracle_toggles_style = $style;
		$this->miracle_toggles_type = $toggle_type;
		$classes = 'panel-group ' . $style;
		$result = '<div class="' . esc_attr( $classes ) . '" id="miracle-toggles-' . $this->miracle_toggles_index . '">';
		$result .= do_shortcode( $content );
		$result .= "</div>";
		$this->miracle_toggles_index++;
		return $result;
	}

	// toggle
	function shortcode_toggle( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'		=> '',
			'active' => 'no',
			'class' 	=> ''
		), $atts ) );

		static $toggle_id = 1;

		$data_parent = '';
		if ( $this->miracle_toggles_type == "accordion" ) {
			$data_parent = ' data-parent="#miracle-toggles-' . $this->miracle_toggles_index . '"';
		}

		$result = '';
		$class = 'panel' . (empty( $class ) ? '': ( ' ' . $class ));
		$class_in = ( $active === 'yes') ? ' in':'';
		$class_collapsed = ( $active === 'yes') ? '' : ' class="collapsed"';

		$result .= '<div class="' . esc_attr( $class ) . '">';
		$result .= '<h5 class="panel-title"><a href="#miracle-toggle-' . $toggle_id . '" data-toggle="collapse"' . $data_parent . $class_collapsed . '>';
		if ( $this->miracle_toggles_style != 'style6' ) {
			$result .= '<span class="open-sub"></span>';
		}
		$result .= esc_html( $title ) . '</a></h5>';
		$result .= '<div class="panel-collapse collapse' . $class_in . '" id="miracle-toggle-' . $toggle_id . '"><div class="panel-content"><p>';
		$result .= do_shortcode( $content );
		$result .= '</p></div></div></div>';

		$toggle_id++;

		return $result;
	}

	// Tabs
	function shortcode_tabs($atts, $content = null) {
		$variables = array( 'style'=>'style1', 'active_tab_index' => '1', 'class'=>'', 'img_src'=>'', 'img_height'=>'', 'img_width'=>'', 'img_alt'=>'', 'has_full_width' => 'no' );
		extract( shortcode_atts( $variables, $atts ) );

		$result = '';

		//preg_match_all( '/\[tab(.*?)title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
		preg_match_all( '/\[tab(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
		$tab_titles = array();

		if ( isset( $matches[0] ) ) {
			$tab_titles = $matches[0];
		}
		if ( count( $tab_titles ) ) {
			if ( ( $style == 'transparent-tab' ) && ( ! empty( $img_src ) ) ) {
				$img_alt = " alt='$img_alt'";
				$img_width = ( $img_width != '')?" width='$img_width'":'';
				$img_height = ( $img_height != '')?" height='$img_height'":'';
				$result .= '<div class="image-container"><img src="' . esc_url( $img_src ) . '"' . esc_html( $img_alt . $img_width . $img_height ) . '/></div>';
			}

			$classes = array( 'tab-container', 'clearfix', $style );
			if ( $has_full_width == 'yes' ) {
				$classes[] = 'full-width';
			}
			if ( $class != '' ) {
				$classes[] = $class;
			}

			$result .= sprintf( '<div class="%s"><ul class="tabs clearfix">', esc_attr( implode(' ', $classes) ) );
			$uid = uniqid();
			foreach ( $tab_titles as $i => $tab ) {
				preg_match( '/title="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
				if ( isset( $tab_matches[1][0] ) ) {
					$active_class = '';
					$active_attr = '';
					if ( $active_tab_index - 1 == $i ) {
						$active_class = ' class="active"';
						$active_attr = ' active="true"';
					}

					preg_match( '/ icon_class="([^\"]+)"/i', $tab[0], $icon_matches, PREG_OFFSET_CAPTURE );
					$icon_html = '';
					if ( !empty( $icon_matches[1][0] ) ) {
						$icon_html = sprintf( '<i class="%s"></i>', esc_attr( $icon_matches[1][0] ) );
					}

					$result .= '<li '. $active_class . '><a href="#' . $uid . $i . '" data-toggle="tab">' . $icon_html . esc_html( $tab_matches[1][0] ) . '</a></li>';

					$before_content = substr($content, 0, $tab[1]);
					$current_content = substr($content, $tab[1]);
					$current_content = preg_replace('/\[tab/', '[tab id="' . $uid . $i . '"' . $active_attr, $current_content, 1);
					$content = $before_content . $current_content;
				}
			}
			$result .= '</ul>';
			$result .= do_shortcode( $content );
			$result .= '</div>';
		} else {
			$result .= do_shortcode( $content );
		}

		return $result;
	}
	function shortcode_tab($atts, $content = null) {
		extract( shortcode_atts( array(
			'title' => '',
			'id'	=> '',
			'active'=> '',
			'icon_class' => '',
			'class' => ''
		), $atts) );

		$classes = array( 'tab-content' );
		if ( $active == 'true' ) {
			$classes[] = 'active';
		}
		if ( $class != '' )  {
			$classes[] = $class;
		}
		return sprintf( '<div id="%s" class="%s"><div class="tab-pane">%s</div></div>',
			esc_attr( $id ),
			esc_attr( implode(' ', $classes) ),
			do_shortcode( $content )
		);
	}

	// Button
	function shortcode_button( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'href'	=> '',
			'title'	=> '',
			'style'	=> 'style4',
			'target'=> '',
			'size'	=> 'md',
			'class' => ''
		), $atts ) );
		$classes = array( 'btn', 'btn-' . $size, $style );
		if ( $class != '' )  {
			$classes[] = $class;
		}
		$attrs = '';
		if ( $title != '' ) {
			$attrs .= ' title="' . esc_attr( $title ) . '"';
		}
		if ( $target != '' ) {
			$attrs .= ' target="' . esc_attr( $target ) . '"';
		}
		return sprintf( '<a href="%s" class="%s"%s>%s</a>',
			esc_attr( $href ),
			esc_attr( implode(' ', $classes) ),
			$attrs,
			do_shortcode( $content )
		);
	}

	// Title section
	function shortcode_section_with_title( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => '',
			'class' => ''
		), $atts ) );
		$classes = array( 'section-info' );
		if ( $class != '' )  {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s"><h3 class="section-title">%s</h3>%s</div>',
			esc_attr( implode(' ', $classes) ),
			esc_html( $title ),
			do_shortcode( $content )
		);
	}

	// Alert box
	function shortcode_alert( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type'	=> 'general',
			'class'	=> ''
		), $atts ) );
		$classes = array( 'alert', 'alert-' . $type );
		if ( $class != '' )  {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s<span class="close"></span></div>',
			esc_attr( implode(' ', $classes) ),
			do_shortcode( $content )
		);
	}

	// Note
	function shortcode_note( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'style1',
			'class'	=> ''
		), $atts ) );
		$classes = array( 'sticky-note', $style );
		if ( $class != '' )  {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}

	// Callout box
	function shortcode_callout_box( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'			=> 'style1',
			'title'			=> '',
			'message'		=> '',
			'img_position'	=> 'left',
			'img_src'		=> '',
			'img_width'		=> '',
			'img_height'	=> '',
			'img_alt'		=> '',
			'bgcolor'		=> '',
			'img_animation_type' => '',
			'img_animation_delay' => '',
			'content_animation_type' => '',
			'content_animation_delay' => '',
			'img2_src'		=> '',
			'img2_width'	=> '',
			'img2_height'	=> '',
			'img2_alt'		=> '',
			'img_id'		=> '',
			'img2_id'		=> '',
			'class'			=> ''
		), $atts ) );

		if ( !empty( $img_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $img_id ) );
			if ( isset( $attachments[0] ) ) {
				$img_src = $attachments[0]['full'];
				$img_alt = $attachments[0]['alt'];
				$img_width = $attachments[0]['width'];
				$img_height = $attachments[0]['height'];
			}
		}
		if ( !empty( $img2_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $img2_id ) );
			if ( isset( $attachments[0] ) ) {
				$img2_src = $attachments[0]['full'];
				$img2_alt = $attachments[0]['alt'];
				$img2_width = $attachments[0]['width'];
				$img2_height = $attachments[0]['height'];
			}
		}

		$index = 1;
		$buttons_text = array();
		$buttons_href = array();
		$buttons_target = array();
		if ( !empty( $atts ) ) {
			foreach ( $atts as $key => $val ) {
				if ( $key === ('button' . $index . '_target') ) {
					$buttons_target[$index] = $val;
				}
				if ( $key === ('button' . $index . '_text') ) {
					$buttons_text[$index] = $val;
				}
				if ( $key === ('button' . $index . '_href') ) {
					$buttons_href[$index] = $val;
					$index++;
				}
			}
		}
		if ( $style != 'style5' ) {
			$classes = array( 'callout-box', $style );
		} else {
			$classes = array( 'callout-box', 'style1' );
		}
		if ( $class != '' )  {
			$classes[] = $class;
		}

		$result = '';
		if ( $style == 'style1' || $style == 'style5' ) {
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
			$result .= '<div class="callout-box-wrapper"' . ( empty($bgcolor) ? '' : ' style="background: ' . esc_attr( $bgcolor ) . '"' ) . '>';
			$result .= '<div class="container"><div class="row same-height">';

			$content_container_extra_classes = '';
			if ( $img_position == "left" ) {
				$content_container_extra_classes .= ' pull-right';
			}

			$animation_attrs = '';
			if ( !empty( $content_animation_type ) ) {
				$content_container_extra_classes .= ' animated';
				$animation_attrs .= ' data-animation-type="' . esc_attr( $content_animation_type ) . '"';
				if ( !empty( $content_animation_delay ) ) {
					$animation_attrs .= ' data-animation-delay="' . esc_attr( $content_animation_delay ) . '"';
				}
			}

			if ( empty( $img_src )) {
				$content_container_extra_classes .= ' col-xs-12';
			} else if ( $style == 'style1' ) {
				$content_container_extra_classes .= ' col-sm-8';
			} else {
				$content_container_extra_classes .= ' col-md-6';
			}

			$result .= sprintf( '<div class="callout-content-container' . $content_container_extra_classes . '"%s><div class="callout-content">', $animation_attrs );
			if ( $style == 'style1' ) {
				
				$content_extra_classes = '';
				if ( !empty( $content ) ) {
					$content_extra_classes = ' box-xl';
				}
				$result .= '<div class="st-table' . $content_extra_classes . '">';
				if ( empty( $content ) ) {
					$result .= '<div class="callout-text"><h2>' . esc_html( $title ) . '</h2></div>';
				} else {
					$result .= '<div class="callout-text"><h3>' . esc_html( $title ) . '</h3></div>';
				}
				$result .= '<div class="callout-action">';
				foreach( $buttons_text as $index => $text ) {
					$target_html = '';
					if ( !empty( $buttons_target[$index] ) ) {
						$target_html = ' target="' . esc_attr( $buttons_target[$index] ) . '"';
					}
					$result .= sprintf( '<a href="%s" class="btn style4">%s</a>', esc_attr( $buttons_href[$index] ), esc_html( $buttons_text[$index] ) );
				}
				$result .= '</div>'; //end callout action
				$result .= '</div>'; //end st table
			}
			if ( !empty( $content ) ) {
				$result .= do_shortcode( $content );
			}
			$result .= '</div></div>'; //end callout content container

			if ( !empty( $img_src ) ) {
				$img_section_class = '';
				if ( $style == 'style1' ) {
					$img_section_class .= 'col-sm-4';
				} else {
					$img_section_class .= 'col-md-6';
				}

				$animation_attrs = '';
				if ( !empty( $img_animation_type ) ) {
					$img_section_class .= ' animated';
					$animation_attrs .= ' data-animation-type="' . esc_attr( $img_animation_type ) . '"';
					if ( !empty( $img_animation_delay ) ) {
						$animation_attrs .= ' data-animation-delay="' . esc_attr( $img_animation_delay ) . '"';
					}
				}

				$result .= sprintf( '<div class="' . $img_section_class . '"%s><div class="callout-image-container"><div class="callout-image%s">', $animation_attrs, ( empty( $img2_src ) ? '' : ' hide-children' ) );
				$img_attrs = ' alt="' . $img_alt . '"';
				if ( !empty( $img_width ) ) {
					$img_attrs .= ' width="' . $img_width . '"';
				}
				if ( !empty( $img_height ) ) {
					$img_attrs .= ' height="' . $img_height . '"';
				}
				$img_class = '';
				if ( !empty( $img2_src ) ) {
					$img_class = ' class="active"';
				}
				$result .= '<img' . $img_class . ' src="' . esc_url( $img_src ) . '"'. $img_attrs . '>';


				if ( !empty( $img2_src ) ) {
					$img_attrs = ' alt="' . $img2_alt . '"';
					if ( !empty( $img2_width ) ) {
						$img_attrs .= ' width="' . $img2_width . '"';
					}
					if ( !empty( $img2_height ) ) {
						$img_attrs .= ' height="' . $img2_height . '"';
					}
					$result .= '<img src="' . esc_url( $img2_src ) . '"'. $img_attrs . '>';
				}

				$result .= '</div></div></div>';
			}
			$result .= '</div></div>'; //end container
			if ( !empty( $message ) ) {
				$result .= '<div class="container callout-stripe-container"><div class="callout-stripe">';
				$result .= htmlspecialchars_decode( esc_html( $message ) );
				$result .= '</div></div>';
			}

			$result .= '</div></div>'; //end callout box
		} else if ( $style == 'style2' || $style == 'style3' ) {
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '"' . /*( empty($bgcolor) ? '' : ' style="background: ' . esc_attr( $bgcolor ) . '"' ) .*/ '>';
			$result .= '<div class="container"><div class="callout-content">';

			$result .= '<div class="callout-text">';
			if ( $style == 'style3' ) {
				$result .= '<h2>' . esc_html( $title ) . '</h2>';
			} else {
				$result .= '<h4>' . esc_html( $title ) . '</h4>';
			}
			$result .= '</div>';
			$result .= '<div class="callout-action">';
			$btn_style = 'btn';
			if ( $style == 'style2' ) {
				$btn_style .= ' style4';
			} else {
				$btn_style .= ' style3';
			}
			foreach( $buttons_text as $index => $text ) {
				$target_html = '';
				if ( !empty( $buttons_target[$index] ) ) {
					$target_html = ' target="' . esc_attr( $buttons_target[$index] ) . '"';
				}
				$result .= sprintf( '<a href="%s" class="%s">%s</a>', esc_attr( $buttons_href[$index] ), esc_attr( $btn_style ), esc_html( $buttons_text[$index] ) );
			}
			$result .= '</div>'; //end callout action

			$result .= '</div></div>';
			$result .= '</div>';
		} else if ( $style == 'style4' ) {
			$classes = array( 'callout-box', 'style1' );
			if ( $class != '' )  {
				$classes[] = $class;
			}
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '"' . /*( empty($bgcolor) ? '' : ' style="background: ' . esc_attr( $bgcolor ) . '"' ) .*/ '>';
			$result .= '<div class="section container">';
			$result .= '<div class="st-table block-sms box-sm width-auto">';
			$result .= '<div class="st-td"><h1 class="color-white">' . stripslashes( htmlspecialchars_decode ( esc_html( $title ) ) ) . '</h1></div>';
			$result .= '<div class="st-td">&nbsp;&nbsp;&nbsp;';
			foreach( $buttons_text as $index => $text ) {
				$target_html = '';
				if ( !empty( $buttons_target[$index] ) ) {
					$target_html = ' target="' . esc_attr( $buttons_target[$index] ) . '"';
				}
				$result .= sprintf( '<a href="%s" class="btn style4 color-white">%s</a>', esc_attr( $buttons_href[$index] ), esc_html( $buttons_text[$index] ) );
				$result .= '&nbsp;';
			}
			$result .= '</div>';
			$result .= '</div>';
			$result .= do_shortcode( $content );
			$result .= '</div>';
			$result .= '</div>';
		} else {
			return do_shortcode( $content );
		}
		return $result;
	}

	// Miracle process
	public $miracle_process_style = 'simple';
	public $miracle_process_has_animate = 'no';
	public $miracle_process_animate_type = 'fadeInLeft';
	function shortcode_process( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style' => 'simple',
			'has_animate' => 'yes',
			'animate_type' => 'fadeInLeft',
			'class' => ''
		), $atts ) );
		preg_match_all( '/\[process_item(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
		if ( !isset( $matches[0] ) ) {
			return do_shortcode( $content );
		}
		$this->miracle_process_style = $style;
		$this->miracle_process_has_animate = $has_animate;
		if ( !empty($animate_type) ) {
			$this->miracle_process_animate_type = $animate_type;
		}
		$classes = array( 'process-builder', 'style-' . $style, 'items-' . count($matches[0]), 'clearfix', 'same-height' );
		if ( $class != '' ) {
			$classes[] = $class;
		}
		return sprintf( '<ul class="%s">%s</ul>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}
	function shortcode_process_item( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'		=> '',
			'desc'		=> '',
			'icon_class'=> '',
			'img_src'	=> '',
			'img_alt'	=> '',
			'img_width'	=> '',
			'img_height'=> '',
			'is_active' => 'no',
			'is_asset'	=> 'no',
			'has_animate'=> 'no',
			'img_id' => '',
			'class'		=> ''
		), $atts ) );
		if ( !empty( $img_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $img_id ) );
			if ( isset( $attachments[0] ) ) {
				$img_src = $attachments[0]['full'];
				$img_alt = $attachments[0]['img_alt'];
				$img_width = $attachments[0]['width'];
				$img_height = $attachments[0]['height'];
			}
		}
		$classes = array( 'process-item' );
		if ( $is_active == 'yes' ) {
			$classes[] = 'active';
		}
		if ( $class != '' ) {
			$classes[] = $class;
		}
		if ( $this->miracle_process_style == 'simple' ) {
			return sprintf( '<li class="%s">
				<div class="process-icon"><i class="%s"></i></div>
				<div class="process-details"><h4 class="process-title">%s</h4><p>%s</p></div>
				</li>',
				esc_attr( implode(' ', $classes) ),
				esc_attr( $icon_class ),
				esc_html( $title ),
				esc_html( $desc )
			);
		} else if ( $this->miracle_process_style == 'creative' ) {
			if ( $is_asset === 'yes' ) {
				$classes = array( 'assets-item' );
				if ( $class != '' ) {
					$classes[] = $class;
				}
			}
			static $count = 0;
			$item_attrs = '';
			$arrow_attrs = '';
			if ( $this->miracle_process_has_animate === 'yes' ) {
				$item_attrs .= ' data-animation-type="' . esc_attr( $this->miracle_process_animate_type ) . '"';
				if ( $is_asset === 'yes' ) {
					$item_attrs .= ' data-animation-duration="2"';
				} else {
					$item_attrs .= ' data-animation-duration="1"';
				}
				$arrow_attrs = $item_attrs;
				$item_attrs .= ' data-animation-delay="' . ($count) . '"';
				$arrow_attrs .= ' data-animation-delay="' . ($count + 0.5) . '"';
			}

			$count++;
			$img_attrs = '';
			if ( !empty( $img_width ) ) {
				$img_attrs .= ' width="' . esc_attr( $img_width ) . '"';
			}
			if ( !empty( $img_height ) ) {
				$img_attrs .= ' height="' . esc_attr( $img_height ) . '"';
			}
			if ( $is_asset === 'yes' ) {
				return sprintf( '<li class="%s"><div class="%s"%s><img src="%s" alt="%s"%s></div></li>',
					esc_attr( implode(' ', $classes) ),
					'process-image' . ( $this->miracle_process_has_animate === 'yes' ? ' animated' : '' ),
					$item_attrs,
					esc_url( $img_src ),
					esc_attr( $img_alt ),
					$img_attrs
				);
			} else {
				return sprintf( '<li class="%s">
						<div class="%s"%s>
							<div class="process-image"><img src="%s" alt="%s"%s></div>
							<div class="process-details"><h4 class="process-title">%s</h4><p>%s</p></div>
						</div>
						<div class="%s"%s></div>
					</li>',
					esc_attr( implode(' ', $classes) ),
					'process-inside' . ( $this->miracle_process_has_animate === 'yes' ? ' animated' : '' ),
					$item_attrs,
					esc_url( $img_src ),
					esc_attr( $img_alt ),
					$img_attrs,
					esc_html( $title ),
					esc_html( $desc ),
					'arrow' . ( $this->miracle_process_has_animate === 'yes' ? ' animated' : '' ),
					$arrow_attrs
				);
			}
		}
	}

	// Pricing Table Container
	public $miracle_pricing_table_columns = 4;
	public $miracle_pricing_table_container = false;
	function shortcode_pricing_table_container( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'columns' => '4',
			'class'	=> ''
		), $atts ) );
		$classes = array( 'pricing-table-container', 'row' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		$this->miracle_pricing_table_columns = (int)$columns;
		$this->miracle_pricing_table_container = true;
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}

	// Pricing Table

	function shortcode_pricing_table( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'style1',
			'active'=> 'false',
			'currency_symbol'	=> '$',
			'price'				=> '0',
			'unit_text'			=> 'per month',
			'pricing_type'		=> '',
			'desc'				=> '',
			'btn_title'			=> '',
			'btn_url'			=> '',
			'btn_target'		=> '',
			'class'	=> '',
			'animation_type'=> '',
			'animation_delay' => '',
			'animation_duration' => ''
		), $atts ) );
		$classes = array( 'pricing-table', $style );
		if ( $active === 'true' ) {
			$classes[] = 'active';
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}

		$pricing_table_attrs = '';
		if ( !empty( $animation_type ) ) {
			$classes[] = 'animated';
			$pricing_table_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
			if ( !empty( $animation_duration ) )  {
				$pricing_table_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
			}
			if ( !empty( $animation_delay ) )  {
				$pricing_table_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
			}
		}
		$result = '';
		$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '"' . $pricing_table_attrs . '>';

		$classes = array( 'pricing-table-header' );
		$wrap_html = '';
		$wrap_html .= '<div class="%s"><div class="pricing-row">';
		$temp_wrap = '%s';
		if ( $style === 'style2' ) {
			$temp_wrap = '<div class="st-table"><div class="st-td">%s</div></div>';
		}
		$wrap_html .= sprintf( $temp_wrap, '<span class="currency-symbol">%s</span><span class="price-value">%s</span><small>%s</small>' );
		$wrap_html .= '</div>%s<h4 class="pricing-type">%s</h4></div>';
		$result .= sprintf( $wrap_html,
			esc_attr( implode(' ', $classes) ),
			esc_html( $currency_symbol ),
			esc_html( $price ),
			esc_html( $unit_text ),
			( $style === 'style1' ? '' : '<p>' . esc_html( $desc ) . '</p>' ),
			esc_html( $pricing_type )
		);

		$classes = array( 'pricing-table-content' );
		$result .= sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );

		if ( !empty( $btn_title ) ) {
			$classes = array( 'pricing-table-footer' );
			$action_html = sprintf( '<a class="btn style4" href="%s"%s>%s</a>',
				esc_url( $btn_url ),
				empty( $btn_target ) ? '' : ' target="' . esc_attr( $btn_target ) . '"',
				esc_html( $btn_title )
			);
			$result .= sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), $action_html );
		}

		$result .= '</div>';
		if ( $this->miracle_pricing_table_container ) {
			$column_class = '';
			if ( $this->miracle_pricing_table_columns == 1 ) {
				$column_class = 'col-xs-12';
			} else if ( $this->miracle_pricing_table_columns == 2 ) {
				$column_class = 'col-sm-6';
			} else if ( $this->miracle_pricing_table_columns == 3 ) {
				$column_class = 'col-sms-6 col-sm-6 col-md-4';
			} else if ( $this->miracle_pricing_table_columns == 4 ) {
				$column_class = 'col-sms-6 col-sm-6 col-md-3';
			} else if ( $this->miracle_pricing_table_columns == 6 ) {
				$column_class = 'col-sms-6 cols-sm-4 col-md-2';
			}
			if ( !empty( $column_class ) ) {
				$result = sprintf( '<div class="%s">%s</div>', $column_class, $result );
			}
		}
		return $result;
	}
	/*public $miracle_pricing_table_style = '';
	function shortcode_pricing_table( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> '',
			'active'=> 'false',
			'class'	=> ''
		), $atts ) );
		$classes = array( 'pricing-table', $style );
		if ( $active === 'true' ) {
			$classes[] = 'active';
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		$this->miracle_pricing_table_style = $style;
		return sprintf( '<div class="%s">%s</div>',
			esc_attr( implode(' ', $classes) ),
			do_shortcode( $content )
		);
	}
	function shortcode_pricing_table_content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'currency_symbol'	=> '$',
			'price'				=> '0',
			'unit_text'			=> 'per month',
			'pricing_type'		=> '',
			'class'				=> ''
		), $atts ) );
		$classes = array( 'pricing-table-header' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		$wrap_html = '';
		$wrap_html .= '<div class="%s"><div class="pricing-row">';
		$temp_wrap = '%s';
		if ( $this->miracle_pricing_table_style === 'style2' ) {
			$temp_wrap = '<div class="st-table"><div class="st-td">%s</div></div>';
		}
		$wrap_html .= sprintf( $temp_wrap, '<span class="currency-symbol">%s</span><span class="price-value">%s</span><small>%s</small>' );
		$wrap_html .= '</div>%s<h4 class="pricing-type">%s</h4></div>';
		return sprintf( $wrap_html,
			esc_attr( implode(' ', $classes) ),
			esc_html( $currency_symbol ),
			esc_html( $price ),
			esc_html( $unit_text ),
			( $this->miracle_pricing_table_style === 'style1' ? '' : '<div>' . do_shortcode( $content ) . '</div>' ),
			esc_html( $pricing_type )
		);
	}
	function shortcode_pricing_table_features( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );
		$classes = array( 'pricing-table-content' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}
	function shortcode_pricing_table_action( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );
		$classes = array( 'pricing-table-footer' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}*/


	// Progress Bars
	public $miracle_progress_bar_style = 'default';
	function shortcode_progress_bars( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'	=> 'default',
			'class'	=> ''
		), $atts ) );
		$classes = array( 'progress-bar-container' );
		$this->miracle_progress_bar_style = $style;
		if ( $style == 'skill-meter' ) {
			$classes[] = 'skill-meter';
		} else if ( $style == 'icons' ) {
			return do_shortcode( $content );
		} else if ( $style == 'colored' ) {
			$classes[] = 'style-colored';
		} else if ( $style == 'vertical' ) {
			$classes[] = 'style-vertical';
			return sprintf( '<div class="%s"><div class="progress-bar-wrapper">%s</div></div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		return sprintf( '<div class="%s">%s</div>', esc_attr( implode(' ', $classes) ), do_shortcode( $content ) );
	}
	function shortcode_progress_bar( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'label'			=> '',
			'percent'		=> '',
			'icon_class'	=> '',
			'total_numbers' => '',
			'active_numbers'=> '0',
			'color_style'	=> 'default',
			'bar_color_code'=> '',
			'has_animate'	=> 'yes',
			'class'			=> ''
		), $atts ) );
		$classes = array();
		if ( $this->miracle_progress_bar_style !== 'icons' ) {
			$classes[] = 'progress-bar';
		}
		if ( $has_animate === 'yes' ) {
			$classes[] = 'animate-progress';
		}
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		if ( $this->miracle_progress_bar_style === 'default' ) {
			if ( $color_style === 'blue' ) {
				$classes[] = 'bar-color-blue';
			}
			return sprintf( '<div class="%s">
					<div class="progress-label"><span>%s</span></div>
					<div class="progress-wrap"><div class="progress"><span class="progress-inner" data-width="%s" style="%s"></span></div></div>
					<div class="progress-percent"><span>%s</span></div>
				</div>',
				esc_attr( implode(' ', $classes) ),
				esc_html( $label ),
				esc_attr( $percent ),
				'width: ' . esc_attr( $percent ) . '%',
				esc_attr( $percent ) . '%'
			);
		} else if ( $this->miracle_progress_bar_style === 'skill-meter' ) {
			$classes[] = 'skill-meter';
			if ( $color_style === 'blue' ) {
				$classes[] = 'label-color-blue';
			}
			return sprintf( '<div class="%s">
					<div class="progress-label"><i class="%s"></i><span>%s</span></div>
					<div class="progress-wrap">
						<div class="progress"><span class="progress-inner" data-width="%s" style="%s"></span></div>
						<div class="progress-percent"><span>%s</span></div>
					</div>
				</div>',
				esc_attr( implode(' ', $classes) ),
				esc_attr( $icon_class ),
				esc_html( $label ),
				esc_attr( $percent ),
				'width: ' . esc_attr( $percent ) . '%',
				esc_attr( $percent ) . '%'
			);
		} else if ( $this->miracle_progress_bar_style === 'icons' ) {
			if ( $total_numbers == '' ) {
				return;
			}
			$classes[] = 'progress-bar-icons';
			$classes[] = 'clearfix';
			if ( $color_style == 'blue' ) {
				$classes[] = 'color-blue';
			}
			$result = '';
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '" data-number="' . esc_attr( $active_numbers ) . '">';
			for ( $i = 0; $i < $total_numbers; $i++ ) {
				$result .= '<div class="progress"><i class="' . esc_attr( $icon_class ) . '"></i></div>';
			}
			$result .= '</div>';
			return $result;
		} else if ( $this->miracle_progress_bar_style === 'colored' ) {
			$classes[] = 'style-colored';
			return sprintf( '<div class="%s">
					<div class="progress-label"><span>%s</span></div>
					<div class="progress"><span class="progress-inner" data-width="%s" style="%s"><span class="progress-percent"><span>%s</span></span></span></div>
				</div>',
				esc_attr( implode(' ', $classes) ),
				esc_html( $label ),
				esc_attr( $percent ),
				'width: ' . esc_attr( $percent ) . '%;' . ( !empty( $bar_color_code ) ? ' background-color:' . esc_attr( $bar_color_code ) : '' ),
				esc_attr( $percent ) . '%'
			);
		} else if ( $this->miracle_progress_bar_style === 'vertical' ) {
			$classes[] = 'style-colored';
			return sprintf( '<div class="%s"><div class="progress-percent"><div>%s</div></div>
					<div class="progress"><span class="progress-inner" data-percent="%s" style="%s"><span class="progress-label"><span>%s</span></span></span></div>
				</div>',
				esc_attr( implode(' ', $classes) ),
				esc_attr( $percent ) . '%',
				esc_attr( $percent ),
				'height: ' . esc_attr( $percent ) . '%;',
				esc_html( $label )
			);
		}
	}

	// Image Box
	function shortcode_image_box( $atts, $content = null ) {
		extract( shortcode_atts(  array(
			'title' => '',
			'img_src' => '',
			'img_width' => '',
			'img_height' => '',
			'img_alt' => '',
			'img_id' => '',
			'class' => '',
			'animation_type'=> '',
			'animation_delay' => '',
			'animation_duration' => ''
		), $atts) );
		if ( !empty( $img_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $img_id ) );
			if ( isset( $attachments[0] ) ) {
				$img_src = $attachments[0]['full'];
				$img_alt = $attachments[0]['alt'];
				$img_width = $attachments[0]['width'];
				$img_height = $attachments[0]['height'];
			}
		}
		$classes = array( 'image-box', 'row' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		$img_attrs = '';
		if ( !empty( $img_width ) ) {
			$img_attrs .= ' width="' . esc_attr( $img_width ) . '"';
		}
		if ( !empty( $img_height ) ) {
			$img_attrs .= ' height="' . esc_attr( $img_height ) . '"';
		}

		$image_box_attrs = '';
		if ( !empty( $animation_type ) ) {
			$classes[] = 'animated';
			$image_box_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
			if ( !empty( $animation_duration ) )  {
				$image_box_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
			}
			if ( !empty( $animation_delay ) )  {
				$image_box_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
			}
		}
		return sprintf( '<div class="%s"%s>
				<div class="col-sms-4 col-sm-4 image-container fixed"><img src="%s" alt="%s"%s></div>
				<div class="col-sms-8 col-sm-8 details"><h3 class="title">%s</h3>%s</div>
			</div>',
			esc_attr( implode(' ', $classes) ),
			$image_box_attrs,
			esc_url( $img_src ),
			esc_attr( $img_alt ),
			$img_attrs,
			esc_html( $title ),
			do_shortcode( $content )
		);
	}

	// Infographic Pie
	function shortcode_infographic_pie( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'			=> '',
			'desc'			=> '',
			'bgcolor'		=> '',
			'fgcolor'		=> '',
			'percent'		=> '',
			'percent_text'	=> '',
			'dimension'		=> '',
			'bordersize'	=> '',
			'fontsize'		=> '30',
			'fontcolor'		=> 'default',
			'borderstyle'	=> 'default',
			'fill_borderwidth' => '',
			'startdegree'	=> '',
			'style'			=> 'style1',
			'class'			=> ''
		), $atts ) );

		$decoded_percent_text = base64_decode( $percent_text, true );
		if ( preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $percent_text) && base64_encode( $decoded_percent_text ) == $percent_text ) {
			$percent_text = htmlentities( rawurldecode( $decoded_percent_text ), ENT_COMPAT, 'UTF-8' );
		}
		$classes = array( 'circle-wrap' );
		if ( $class != '' ) {
			$classes[] = $class;
		}
		$title_html = '';
		if ( !empty( $title ) ) {
			if ( $style === 'style3' ) {
				$title_html = '<p>' . esc_html( $title ) . '</p>';
			} else if ( $style === 'style2' ) {
				$title_html = '<div class="fontsize-lg">' . esc_html( $title ) . '</div>';
			} else {
				$title_html = '<h4>' . esc_html( $title ) . '</h4>';
			}
		}
		$pie_attrs = '';
		$pie_attrs .= empty($fgcolor) ? '' : ' data-fgcolor="' . esc_attr( $fgcolor ) . '"';
		$pie_attrs .= ($borderstyle === 'outline' ? ' data-border="outline"' : '');
		$pie_attrs .= (!empty( $fill_borderwidth ) ? ' data-width="' . esc_attr( $fill_borderwidth ) . '"' : '');
		$pie_attrs .= (empty( $startdegree ) ? '' : ' data-startdegree="' . esc_attr($startdegree) . '"');
		return sprintf( '<div class="%s">
							<div class="circle-progress circliful' . ($fontcolor === 'blue' ? ' color-blue' : '') . '" data-bgcolor="%s"%s data-percent="%s" data-text="%s" data-dimension="%s" data-bordersize="%s" data-fontsize="%s"></div>
							%s<p>%s</p>
						</div>',
			esc_attr( implode(' ', $classes) ),
			esc_attr( $bgcolor ),
			$pie_attrs,
			esc_attr( $percent ),
			($style === 'style3' && strpos($class, 'has-text-block') !== FALSE ? esc_attr('<span>' . str_replace(array("\"", '"', '\"'), "'", $percent_text) . '</span>') : esc_attr( $percent_text )),
			esc_attr( $dimension ),
			esc_attr( $bordersize ),
			esc_attr( $fontsize ),
			$title_html,
			empty($desc) ? '' : esc_html( $desc )
		);
	}

	// Counter
	function shortcode_counter( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'			=> 'style1',
			'label'			=> '',
			'number'		=> '',
			'img_src'		=> '',
			'img_alt'		=> '',
			'img_width'		=> '',
			'img_height'	=> '',
			'img_id'		=> '',
			'class'			=> ''
		), $atts ) );
		if ( !empty( $img_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $img_id ) );
			if ( isset( $attachments[0] ) ) {
				$img_src = $attachments[0]['full'];
				$img_alt = $attachments[0]['alt'];
				$img_width = $attachments[0]['width'];
				$img_height = $attachments[0]['height'];
			}
		}
		$classes = array( 'counters-box', $style );
		if ( $class != '' ) {
			$classes[] = $class;
		}
		$result = '';
		$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
		if ( !empty( $img_src ) ) {
			$img_attrs = '';
			if ( !empty( $img_width ) ) {
				$img_attrs .= ' width="'. esc_attr( $img_width ) . '"';
			}
			if ( !empty( $img_height ) ) {
				$img_attrs .= ' height="'. esc_attr( $img_height ) . '"';
			}
			$result .= '<div class="icon-wrap"><img src="' . esc_html( $img_src ) . '" alt="' . esc_attr( $img_alt ) . '"'. $img_attrs . '></div>';
		}
		if ( $style === 'style1' ) {
			$result .= '<dl><dt>' . esc_html( $label ) . '</dt><dd class="display-counter" data-value="' . esc_attr( $number ) . '">' . esc_attr( $number ) . '</dd></dl>';
		} else {
			$result .= '<dl><dt class="display-counter" data-value="' . esc_attr( $number ) . '">' . esc_attr( $number ) . '</dt><dd>' . esc_html( $label ) . '</dd></dl>';
		}
		$result .= '</div>';
		return $result;
	}

	// Team Member
	function shortcode_team_member( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style'			=> 'default',
			'name'			=> '',
			'job'			=> '',
			'desc'			=> '',
			'photo_url'		=> '',
			'photo_alt'		=> '',
			'photo_width'	=> '',
			'photo_height'	=> '',
			'photo_id'		=> '',
			'class'			=> '',
			'animation_type'=> '',
			'animation_delay' => '',
			'animation_duration' => ''
		), $atts ) );
		$classes = array( 'team-member', 'style-' . $style );
		if ( $class != '' ) {
			$classes[] = $class;
		}
		if ( !empty( $photo_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $photo_id ) );
			if ( !empty( $attachments[0] ) ) {
				$photo_url = $attachments[0]['full'];
				$photo_alt = $attachments[0]['alt'];
				$photo_width = $attachments[0]['width'];
				$photo_height = $attachments[0]['height'];
			}
		}
		$photo_attrs = '';
		if ( !empty( $photo_width ) ) {
			$photo_attrs .= ' width="' . esc_attr( $photo_width ) . '"';
		}
		if ( !empty( $photo_height ) ) {
			$photo_attrs .= ' height="' . esc_attr( $photo_height ) . '"';
		}

		$team_member_attrs = '';
		if ( !empty( $animation_type ) ) {
			$classes[] = 'animated';
			$team_member_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
			if ( !empty( $animation_duration ) )  {
				$team_member_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
			}
			if ( !empty( $animation_delay ) )  {
				$team_member_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
			}
		}
		if ( $style == 'default' ) {
			return sprintf( '<div class="%s"%s>
					<div class="image-container"><img src="%s" alt="%s"%s></div>
					<div class="team-member-author"><h4 class="team-member-name">%s</h4><div class="team-member-job">%s</div></div>
					<div class="team-member-social">%s</div>
				</div>',
				esc_attr( implode(' ' , $classes) ),
				$team_member_attrs,
				esc_url( $photo_url ),
				esc_attr( $photo_alt ),
				$photo_attrs,
				esc_html( $name ),
				esc_html( $job ),
				do_shortcode( $content )
			);
		} else if ( $style == 'colored' ) {
			return sprintf( '<div class="%s"%s>
					<div class="image-container">
						<img src="%s" alt="%s"%s><div class="team-member-social">%s</div>
					</div>
					<div class="team-member-author"><h4 class="team-member-name">%s</h4><div class="team-member-job">%s</div></div>
					<div class="team-member-desc"><p>%s</p></div>
				</div>',
				esc_attr( implode(' ' , $classes) ),
				$team_member_attrs,
				esc_url( $photo_url ),
				esc_attr( $photo_alt ),
				$photo_attrs,
				do_shortcode( $content ),
				esc_html( $name ),
				esc_html( $job ),
				esc_html( $desc )
			);
		} else {
			return do_shortcode( $content );
		}
	}

	// Testimonials
	public $miracle_testimonial_style = 'style1';
	public $miracle_testimonial_author_img_size = '90';
	public $miracle_testimonial_font_size = 'normal';
	public $miracle_testimonial_count = 0;
	function shortcode_testimonials( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title'		=> '',
			'style'		=> 'style1',
			'author_img_size'	=> '90',
			'font_size' => '',
			'columns' => '1',
			'class'		=> ''
		), $atts ) );
		if ( empty( $author_img_size ) ) {
			$author_img_size = '90';
		}
		$this->miracle_testimonial_style = $style;
		$this->miracle_testimonial_author_img_size = $author_img_size;
		$classes = array( 'testimonials', $style );
		if ( $class != '' ) {
			$classes[] = $class;
		}

		$result = '';
		if ( $style === 'style1' ) {
			$classes[] = 'owl-carousel';
			$this->miracle_testimonial_font_size = $font_size;
			preg_match_all( '/\[testimonial(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
			$this->miracle_testimonial_count = count($matches[0]);

			if ( $columns > 4 ) {
				$columns = 4;
			}
			$slider_attrs = '';
			switch ( $columns ) {
				case 2:
					$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 1], [992, 2], [1200, 2]]"';
					break;
				case 3:
					$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 3]]"';
					break;
				case 4:
					$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 4]]"';
					break;
			}
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '" data-items="' . esc_attr( $columns ) . '"' . $slider_attrs . '>';
			$result .= do_shortcode( $content );
			$result .= '</div>';
		} else if ( $style === 'style2' ) {
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '"><div class="container">';
			if ( !empty( $title ) ) {
				$result .= '<h2 class="testimonials-title">' . esc_html( $title ) . '</h2>';
			}
			$result .= '<div class="sky-carousel testimonial-carousel"><div class="sky-carousel-wrapper"><ul class="sky-carousel-container">';
			$result .= do_shortcode( $content );
			$result .= '</ul></div></div>';
			$result .= '</div></div>';
		} else if ( $style == 'style3' || $style == 'style4' ) {
			$classes[] = 'owl-carousel';
			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
			$result .= do_shortcode( $content );
			$result .= '</div>';
		}
		return $result;
	}
	function shortcode_testimonial( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'author_name' => '',
			'author_job' => '',
			'author_link' => '',
			'author_img_url' => '',
			'author_img_size' => '',
			'font_size' => 'normal',
			'author_img_id' => '',
			'class' => ''
		), $atts) );

		if ( $this->miracle_testimonial_count > 0 ) {
			$this->miracle_testimonial_count--;
		} else if ( $this->miracle_testimonial_count == 0 ) {
			$this->miracle_testimonial_font_size = 'normal';
		}

		if ( !empty( $author_img_id ) ) {
			$attachments = miracle_get_attachment_post_data( array( $author_img_id ) );
			if ( !empty( $attachments[0] ) ) {
				$author_img_url = $attachments[0]['thumbnail'][0];
				$author_img_size = $attachments[0]['thumbnail'][1];
			}
		}

		if ( empty( $author_img_size ) ) {
			$author_img_size = $this->miracle_testimonial_author_img_size;
		}
		if ( empty( $font_size ) ) {
			$font_size = $this->miracle_testimonial_font_size;
		}
		$result = '';
		$author_name_html = '';
		$classes = array( 'testimonial', $this->miracle_testimonial_style );
		if ( $class != '' ) {
			$classes[] = $class;
		}

		if ( $this->miracle_testimonial_style == 'style1' ) {
			if ( !empty( $author_link ) ) {
				$author_name_html .= sprintf( '<a href="%s"><span class="testimonial-author-name">%s</span></a>', esc_url( $author_link ), esc_html( $author_name ) );
			} else {
				$author_name_html .= sprintf( '<span class="testimonial-author-name">%s</span>', esc_html( $author_name ) );
			}
			if ( !empty( $author_job ) ) {
				$author_name_html .= sprintf( ' - <span class="testimonial-author-job">%s</span>', esc_html( $author_job ) );
			}

			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
			if ( !empty( $author_img_url ) ) {
				$result .= sprintf( '<div class="testimonial-image"><img src="%s" alt=""%s></div>',
					esc_url( $author_img_url ),
					( empty( $author_img_size ) ? '' : ' width="' . esc_attr( $author_img_size ) . '"  height="' . esc_attr( $author_img_size ) . '"' )
				);
			}
			$result .= '<div class="testimonial-content' . ( $font_size === 'large' ? ' fontsize-lg' : '' ) . '">' . do_shortcode( $content ) . '</div>';
			$result .= '<div class="testimonial-author">' . $author_name_html . '</div>';
			$result .= '</div>';
		} else if ( $this->miracle_testimonial_style == 'style2' ) { // fullwidth style
			if ( !empty( $author_link ) ) {
				$author_name_html .= sprintf( '<a href="%s">%s</a>', esc_url( $author_link ), esc_html( $author_name ) );
			} else {
				$author_name_html .= esc_html( $author_name );
			}
			if ( !empty( $author_job ) ) {
				$author_name_html .= sprintf( '<small>%s</small>', esc_html( $author_job ) );
			}
			$result .= '<li>';
			$result .= sprintf( '<img src="%s" alt=""%s />',
				esc_url( $author_img_url ),
				( empty( $author_img_size ) ? '' : ' width="' . esc_attr( $author_img_size ) . '"  height="' . esc_attr( $author_img_size ) . '"' )
			);
			$result .= '<div class="sc-content">';
			$result .= '<h2 class="testimonial-author">' . $author_name_html . '</h2>';
			//$result .= do_shortcode( $content );
			$content = strtr($content, array('<p>' => '', '</p>' => ''));
			$result .= '<p>' . do_shortcode( $content ) . '</p>';
			$result .= '</div>';
			$result .= '</li>';
		} else if ( $this->miracle_testimonial_style == 'style3' || $this->miracle_testimonial_style == 'style4' ) {
			if ( !empty( $author_link ) ) {
				$author_name_html .= sprintf( '<a href="%s"><span class="testimonial-author-name">%s</span></a>', esc_url( $author_link ), esc_html( $author_name ) );
			} else {
				$author_name_html .= sprintf( '<span class="testimonial-author-name">%s</span>', esc_html( $author_name ) );
			}
			if ( !empty( $author_job ) && !empty( $author_img_url ) ) {
				$author_name_html .= sprintf( ' - <span class="testimonial-author-job">%s</span>', esc_html( $author_job ) );
			}

			$result .= '<div class="' . esc_attr( implode(' ', $classes) ) . '">';
			if ( $this->miracle_testimonial_style == 'style4' ) {
				$result .= sprintf( '<div class="testimonial-image"><img src="%s" alt=""%s></div>',
					esc_url( $author_img_url ),
					( empty( $author_img_size ) ? '' : ' width="' . esc_attr( $author_img_size ) . '"  height="' . esc_attr( $author_img_size ) . '"' )
				);
			}
			$result .= '<div class="testimonial-content">' . do_shortcode( $content ) . '</div>';
			$result .= '<div class="testimonial-author">' . $author_name_html . '</div>';
			$result .= '</div>';
		}
		return $result;
	}

	// Contact address
	function shortcode_contact_addresses( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style' => 'style1',
			'class' => ''
		), $atts ) );
		$classes = array( 'contact-address', $style );
		if ( $style === 'style2' ) {
			$classes[] = 'col-md-9';
		}
		if ( $class != '' ) {
			$classes[] = $class;
		}
		return sprintf( '<ul class="%s">%s</ul>',
			esc_attr( implode(' ', $classes) ),
			do_shortcode( $content )
		);
	}
	function shortcode_contact_address( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon_class' => '',
			'title' => '',
			'class' => ''
		), $atts ) );
		$classes = '';
		if ( !empty( $class ) ) {
			$classes = ' ' . $class;
		}
		$result = '';
		$result .= '<li ' . ( !empty( $classes ) ? 'class="' . esc_attr( $classes ) . '"' : '' ) . '>';
		if ( !empty( $icon_class ) ) {
			$result .= '<i class="' . esc_attr( $icon_class ) . '"></i>';
		}
		$result .= '<div class="details">';
		$result .= '<h5>' . esc_html( $title ) . '</h5>';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		$result .= '</li>';
		return $result;
	}


	/* Media */

	// Image
	function shortcode_image( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'src'			=> '',
			'alt'			=> '',
			'is_fullwidth'	=>'yes',
			'class'			=> '',
			'animation_type'=> '',
			'animation_delay' => '',
			'animation_duration' => '',
		), $atts ) );

		if ( empty($src) ) {
			return;
		}

		$image_classes = array( 'image-container' );
		if ( $class != '' ) {
			$image_classes[] = $class;
		}
		if ( $is_fullwidth == "no" ) {
			$image_classes[] = "fixed";
		}

		$image_attrs = '';
		if ( !empty( $animation_type ) ) {
			$image_classes[] = 'animated';
			$image_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
			if ( !empty( $animation_duration ) )  {
				$image_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
			}
			if ( !empty( $animation_delay ) )  {
				$image_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
			}
		}

		$result = '<div class="' . esc_attr( implode(' ', $image_classes) ) . '"' . $image_attrs . '><img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '"></div>';
		return $result;
	}

	// Image banner
	function shortcode_image_banner($atts, $content = null ) {

		extract( shortcode_atts( array(
			'bg_img'	=> '',
			'bg_color'		=> '',
			'class'			=> '',
			'animation_type'=> '',
			'animation_delay' => '',
			'animation_duration' => ''
		), $atts ) );

		$banner_classes = array( 'image-banner' );
		if ( $class != '' ) {
			$banner_classes[] = $class;
		}

		$inline_styles = array();
		if ( $bg_img != '' ) {
			$inline_styles[] = 'background-image: url(\'' . esc_url( $bg_img ) . '\')';
		}
		if ( $bg_color != '' ) {
			$inline_styles[] = 'background-color: ' . esc_attr( $bg_color );
		}

		$image_banner_attrs = '';
		if ( !empty( $animation_type ) ) {
			$banner_classes[] = 'animated';
			$image_banner_attrs .= ' data-animation-type="' . esc_attr( $animation_type ) . '"';
			if ( !empty( $animation_duration ) )  {
				$image_banner_attrs .= ' data-animation-duration="' . esc_attr( $animation_duration ) . '"';
			}
			if ( !empty( $animation_delay ) )  {
				$image_banner_attrs .= ' data-animation-delay="' . esc_attr( $animation_delay ) . '"';
			}
		}

		$result = sprintf( '<div class="%s" style="%s"%s>%s</div>',
			esc_attr( implode(' ', $banner_classes) ),
			implode('; ', $inline_styles),
			$image_banner_attrs,
			do_shortcode( $content )
		);

		return $result;
	}

	// caption in the image banner
	function shortcode_banner_caption( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'position' => 'middle',
			'class'    => ''
		), $atts ) );

		$caption_classes = array( 'caption-wrapper' );
		if ( $position != 'full' ) {
			$caption_classes[] = 'position-' . esc_attr( $position );
		}
		if ( $class != '' ) {
			$caption_classes[] = $class;
		}
		$caption_wrap = '';
		if ( $position == 'middle' ) {
			$caption_wrap = '<div class="%s"><div class="st-table"><div class="st-td"><div class="captions">%s</div></div></div></div>';
		} else if ( $position == 'full' ) {
			$caption_wrap = '<div class="%s">%s</div>';
		}else {
			$caption_wrap = '<div class="%s"><div class="captions">%s</div></div>';
		}

		$result = sprintf( $caption_wrap,
			esc_attr( implode(' ', $caption_classes) ),
			do_shortcode( $content )
		);
		return $result;
	}

	// Image Gallery
	function shortcode_image_gallery( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'ids'   => '',
			'mode' => 'gallery1',
			'is_thumb_full' => '',
			'columns' => '3',
			'front_width' => '',
			'front_height' => '',
			'slide_count' => '5',
			'halign' => 'center',
			'valign' =>'center',
			'autoplay' => '5000',
			'class' => ''
		), $atts ) );
		//if ($ids == "") { return ''; }
		
		if ($class != '') {
			$class = ' ' . $class;
		}
		
		global $post;
		$result = '';

		switch ($mode) {
			case 'slider':
				$slider_attrs = ' data-items="' . esc_attr( $columns ) . '"';
				switch ( $columns ) {
					case 2:
						$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 1], [992, 2], [1200, 2]]"';
						break;
					case 3:
						$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 3]]"';
						break;
					case 4:
						$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 4]]"';
						break;
					case 5:
						$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 5]]"';
						break;
					case 6:
						$slider_attrs .= ' data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 6]]"';
						break;
				}
				if ( !empty( $autoplay ) ) {
					$slider_attrs .= ' data-autoplay="' . esc_attr( $autoplay ) . '"';
				}

				$result .= sprintf( '<div class="owl-carousel post-slider"%s>', $slider_attrs );
				$attachments = miracle_get_attachment_post_data( explode( ",", $ids ), 'full' );
				foreach( $attachments as $attachment ) {
					$result .= '<img src="' . esc_url( $attachment['img'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '" width="' . $attachment['img_width'] . '" height="' .  $attachment['img_height'] . '">';
				}
				$result .= '</div>';
				break;
			case 'gallery1': case 'gallery2':
				$attachments = miracle_get_attachment_post_data( explode( ",", $ids ), MiracleHelper::get_thumbnail_size(1) );
				$class .= ($mode == 'gallery1' ? 'soap-gallery owl-carousel style1' : 'soap-gallery style2' . ($is_thumb_full == 'yes' ? ' thumbnail-full' : ''));
				$slider_attrs = '';
				if ( !empty( $autoplay ) ) {
					$slider_attrs .= ' data-autoplay="' . esc_attr( $autoplay ) . '"';
				}
				$result .= '<div class="' . esc_attr( $class ) . '"'. $slider_attrs .'>';

				foreach( $attachments as $attachment ) {
					$result .= '<img class="sgImg" src="' . esc_url($attachment['img']) . '" alt="' . esc_attr($attachment['alt']) . '" width="' . $attachment['img_width'] . '" height="' .  $attachment['img_height'] . '" data-thumb="' . esc_url($attachment['thumbnail'][0]) . '">';
				}

				$result .= '</div>';
				break;
			case 'frame':
				$attachments = miracle_get_attachment_post_data( explode( ",", $ids ), 'frame' );
				$result .= '<div class="soap-gallery frame-holder effect-shine">';
				$result .= '<div class="owl-carousel">';

				foreach( $attachments as $attachment ) {
					$result .= '<img src="' . esc_url( $attachment['img'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '" width="' . $attachment['img_width'] . '" height="' .  $attachment['img_height'] . '">';
				}

				$result .= '</div>';
				$result .= '<div class="gallery-frame"><img src="' . MIRACLE_URL . '/framework/assets/images/laptop.png" alt=""></div></div>';
				break;
			case 'metro1': case 'metro2':
				$attachments = miracle_get_attachment_post_data( explode( ",", $ids ), MiracleHelper::get_thumbnail_size($columns) );
				$result .= '<div class="soap-gallery metro-style gallery-col-' . $columns . '">';
				$result .= '<div class="gallery-wrapper">';

				foreach( $attachments as $index => $attachment ) {
					$item_class = 'image hover-style3';
					if ( $mode == 'metro2' && $index == 0 ) {
						$attachment_images = miracle_get_attachment_post_data( array($attachment['ID']), 'gallery_large' );
						$attachment = $attachment_images[0];
						$item_class .= ' double-width';
					}
					$result .= '<a class="' . $item_class . '" href="' . esc_url( $attachment['full'] ) . '">';
					$result .= '<img src="' . esc_url( $attachment['img'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '" width="' . esc_attr( $attachment['img_width'] ) . '" height="' . esc_attr( $attachment['img_height'] ) . '">';
					$result .= '<div class="image-extras"></div>';
					$result .= '</a>';
				}

				$result .= '</div>';
				$result .= '</div>';
				break;
			case 'carousel':
				$image_size = '';
				if ( $slide_count <= 2 ) {
					$image_size = MiracleHelper::get_thumbnail_size(1, MiracleHelper::check_sidebar(), true);
				} else if ( $slide_count <= 5 ) {
					$image_size = MiracleHelper::get_thumbnail_size(2, MiracleHelper::check_sidebar(), true);
				} else {
					$image_size = MiracleHelper::get_thumbnail_size(3, MiracleHelper::check_sidebar(), true);
				}
				if ( empty( $image_size ) ) {
					$image_size = 'full';
				}
				$attachments = miracle_get_attachment_post_data( explode( ",", $ids ), $image_size );

				$classes = array( 'soap-gallery', 'carousel-style1', 'carousel' );
				if ( !empty( $class ) ) {
					$classes[] = $class;
				}
				$gallery_attrs = '';
				if ( !empty( $front_width ) ) {
					$gallery_attrs .= ' data-front-width="' . esc_attr( $front_width ) . '"';
				}
				if ( !empty( $front_height ) ) {
					$gallery_attrs .= ' data-front-height="' . esc_attr( $front_height ) . '"';
				}
				if ( !empty( $slide_count ) ) {
					$gallery_attrs .= ' data-slides="' . esc_attr( $slide_count ) . '"';
				}
				if ( !empty( $halign ) ) {
					$gallery_attrs .= ' data-hAlign="' . esc_attr( $halign ) . '"';
				}
				if ( !empty( $valign ) ) {
					$gallery_attrs .= ' data-vAlign="' . esc_attr( $valign ) . '"';
				}

				$gallery_html = '';
				foreach( $attachments as $index => $attachment ) {
					$gallery_html .= '<div>';
					$gallery_html .= '<a href="' . esc_url( $attachment['full'] ) . '" class="soap-mfp-popup">';
					$gallery_html .= '<img src="' . esc_url( $attachment['img'] ) . '" alt="' . esc_attr( $attachment['alt'] ) . '" width="' . esc_attr( $attachment['img_width'] ) . '" height="' . esc_attr( $attachment['img_height'] ) . '">';
					$gallery_html .= '</a>';
					$gallery_html .= '</div>';
				}

				$result .= sprintf( '<div class="%s"%s><div class="slides">%s</div></div>',
					esc_attr( implode(' ', $classes) ),
					$gallery_attrs,
					$gallery_html
				);
				break;
			default:
				break;
		}

		return $result;
	}

	// Image Parallax
	function shortcode_image_parallax( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'src'				=> '',
			'ratio'				=> '0.5',
			'height'			=> '',
			'has_middle_caption'=> 'no',
			'class'				=> ''
		), $atts ) );
		if ( $src == '' ) {
			return;
		}

		$parallax_classes = array( 'parallax' );
		if ( $class != '' ) {
			$parallax_classes[] = $class;
		}
		if ( $has_middle_caption == "yes" ) {
			$parallax_classes[] = 'has-caption';
		}
		$result = sprintf( '<div class="%s" %s %s>%s</div>',
			esc_attr( implode(' ', $parallax_classes) ),
			'data-stellar-background-ratio="' . esc_attr( $ratio ) . '"',
			'style="background-image: url(' . esc_url( $src ) . ')' . ($height != '' ? "; height: " . $height . "px" : '') . '"',
			do_shortcode( $content )
		);
		return $result;
	}

	// Logo slider
	function shortcode_logo_slider( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'columns'	=> '4',
			'style'		=> 'style1',
			'autoplay' => '5000',
			'class'		=> ''
		), $atts ) );

		$slider_classes = array( 'brand-slider', 'owl-carousel' );
		if ( $class != '' ) {
			$slider_classes[] = $class;
		}
		if ( $style == 'style1' ) {

		} else {
			$slider_classes[] = 'style1';
		}

		$slider_attrs = array( 'data-items="' . $columns . '"' );
		switch ( $columns ) {
			case 2:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 1], [992, 2], [1200, 2]]"';
				break;
			case 3:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 3]]"';
				break;
			case 4:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 2], [992, 3], [1200, 4]]"';
				break;
			case 5:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 5]]"';
				break;
			case 6:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 6]]"';
				break;
		}
		if ( !empty( $autoplay ) ) {
			$slider_attrs[] = 'data-autoplay="' . esc_attr( $autoplay ) . '"';
		}

		return sprintf( '<div class="overflow-hidden"><div class="%s" %s>%s</div></div>',
			esc_attr( implode(' ', $slider_classes) ),
			stripslashes( htmlspecialchars_decode( esc_html( implode(' ', $slider_attrs) ) ) ),
			do_shortcode( $content )
		);
	}

	// logo
	function shortcode_logo( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'src'	=> '',
			'alt'	=> '',
			'url'	=> '',
			'class'	=> ''
		), $atts ) );
		if ( $src == '' ) {
			return;
		}
		if ( $url == '' ) {
			$url = "javascript:void(0)";
		}

		return sprintf( '<a href="%s" class="%s"><img src="%s" alt="%s"></a>',
			esc_attr( $url ),
			esc_attr( $class ),
			esc_attr( $src ),
			esc_attr( $alt )
		);
	}

	// Video Parallax
	function shortcode_video_parallax( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'src'		=> '',
			'ratio'			=> '0.5',
			'video_ratio'	=> '16:9',
			'poster'		=> '',
			'autoplay'		=> 'no',
			'class'			=> ''
		), $atts ) );

		$video_type = '';
		if ( strrpos($src, '.mp4') !== FALSE) {
			$video_type = 'video/mp4';
		}
		if ( strpos($src, "youtube.com") !== FALSE ) {
			$video_type = 'video/youtube';
		}
		if ( strpos($src, "vimeo.com") !== FALSE ) {
			$video_type = 'video/vimeo';
		}
		if ( $video_type == '' ) {
			return;
		}

		$parallax_classes = array( 'parallax-elem' );
		if ( $class != '' ) {
			$parallax_classes[] = $class;
		}

		$video_attrs = array( 'preload="none"', 'data-stellar-ratio="' . $ratio . '"', 'data-video-format="' . $video_ratio . '"' );
		if ( !empty( $poster ) ) {
			$video_attrs[] = 'poster="' . $poster . '"';
		}
		if ( $autoplay == 'yes' ) {
			$video_attrs[] = 'autoplay';
		}
		return sprintf( '<div class="%s"><div class="video-container mejs-skin"><video %s><source src="%s" type="%s" /></video>%s</div></div>',
			esc_attr( implode(' ', $parallax_classes) ),
			stripslashes( htmlspecialchars_decode( esc_html( implode(' ', $video_attrs) ) ) ),
			esc_url( $src ),
			esc_attr( $video_type ),
			do_shortcode( $content )
		);

	}

	// Video caption
	function shortcode_video_caption( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );

		$caption_classes = array( 'video-text' );
		if ( $class != '' ) {
			$caption_classes[] = $class;
		}

		$result = sprintf( '<div class="%s"><div class="container"><div class="heading-box">%s</div></div></div>', 
			esc_attr( implode(' ', $caption_classes) ),
			do_shortcode( $content )
		);
		return $result;
	}

	// Carousel
	function shortcode_carousel( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'columns' => '3',
			'autoplay' => '5000',
			'show_on_header' => 'no',
			'insert_container' => 'no',
			'class' => ''
		), $atts ) );

		$wrap = '%s';
		if ( $show_on_header == 'yes' ) {
			$wrap = '<div class="ads-carousel-wrap">%s</div>';
		}
		if ( $insert_container == 'yes' ) {
			$wrap = sprintf( $wrap, '<div class="container">%s</div>' );
		}
		$result = '';
		$classes = array( 'owl-carousel' );
		if ( !empty( $class ) ) {
			$classes[] = $class;
		}
		$slider_attrs = array( 'data-items="' . esc_attr( $columns ) . '"' );
		switch ( $columns ) {
			case 2:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 1], [768, 1], [992, 2], [1200, 2]]"';
				break;
			case 3:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 2], [992, 3], [1200, 3]]"';
				break;
			case 4:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 2], [992, 3], [1200, 4]]"';
				break;
			case 5:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 5]]"';
				break;
			case 6:
				$slider_attrs[] = 'data-itemsPerDisplayWidth="[[0, 1], [480, 2], [768, 3], [992, 4], [1200, 6]]"';
				break;
		}

		$result = sprintf( '<div class="%s" %s>%s</div>',
			esc_attr( implode( ' ', $classes ) ),
			implode( ' ', $slider_attrs ),
			do_shortcode( $content )
		);
		
		return sprintf( $wrap, $result );
	}
	function shortcode_slide( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );
		return sprintf( '<div class="item">%s</div>', do_shortcode( $content ) );
	}

	// Shortcode Isotope
	function shortcode_isotope( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'columns' => '4',
			'has_column_width' => 'yes',
			'class' => ''
		), $atts ) );
		$classes = array( 'iso-container', 'style-masonry', 'iso-col-' . esc_attr( $columns ) );
		if ( $has_column_width == 'yes' ) {
			$classes[] = 'has-column-width';
		}
		if ( !empty( $class ) ) {
			$classes[] = esc_attr( $class );
		}
		return sprintf( '<div class="%s">%s</div>', implode(' ', $classes), do_shortcode( $content ) );

	}
	function shortcode_iso_item( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'has_double_width' => 'no',
			'class' => ''
		), $atts ) );
		$classes = array( 'iso-item' );
		if ( $has_double_width == 'yes' ) {
			$classes[] = 'double-width';
		}
		if ( !empty( $class ) ) {
			$classes[] = esc_attr( $class );
		}
		return sprintf( '<div class="%s">%s</div>', implode(' ', $classes), do_shortcode( $content ) );
	}

	// Image Advertisement
	function shortcode_image_ads( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'link_url' => '#',
			'caption_text' => '',
			'caption_style' => 'default',
			'hover_style' => 'style2',
			'class' => ''
		), $atts ) );
		preg_match_all( '/\[image(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
		if ( !isset( $matches[0] ) || count( $matches[0] ) == 0 ) {
			return;
		}
		$count = count( $matches[0] );
		$result = '';

		$caption_html = '<div class="caption-wrapper' . ($caption_style == 'default' ? '' : ' ' . esc_attr( $caption_style )) . '">';
		$caption_html .= '<h4 class="caption">' . esc_html( $caption_text ) . '</h4>';
		$caption_html .= '</div>';

		$classes = '';
		if ( $count >= 2 ) { // if multiple items, display slider.
			$classes = 'item';
			if ( !empty( $class ) ) {
				$classes .= ' ' . esc_attr( $class );
			}
			$result = '<div class="' . $classes . '"><div class="post-slider owl-carousel">%s</div>' . $caption_html . '</div>';
		} else { // single item
			$result = '%s';
		}

		$ads_html = '';
		if ( !empty( $matches[0] ) ) {
			foreach( $matches[0] as $item_match ) {
				preg_match( '/src="([^\"]+)"/i', $item_match[0], $item_matches, PREG_OFFSET_CAPTURE );
				if ( isset( $item_matches[1][0] ) ) {
					$image_url = $item_matches[1][0];
					$ads_html .= '<a href="' . esc_url( $link_url ) . '" class="image hover-' . esc_attr( $hover_style ) . ( $count === 1 && !empty( $class ) ? ' ' . esc_attr( $class ) : '' ) . '">';
					$ads_html .= '<img src="' . esc_url( $image_url ) . '" alt=""><span class="image-extras"></span>';
					if ( $count === 1 ) {
						$ads_html .= $caption_html;
					}
					$ads_html .= '</a>';
				}
			}
		}
		return sprintf( $result, $ads_html );
	}

	// Masonry Products
	function shortcode_masonry_products( $atts, $content = null ) {
		if ( !class_exists( 'Woocommerce' ) ) {
			return;
		}
		extract( shortcode_atts( array(
			'columns' => '4',
			'count' => '8',
			'pagination' => 'no',
			'class' => ''
		), $atts ) );

		$page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
		if ( !$page || $params['paginate'] == 'no' ) $page = 1;
		$query = array( 'orderby' 	=> 'date',
						'order' 	=> 'DESC',
						'paged' 	=> $page,
						'post_type' => 'product',
						'posts_per_page' => $count );
		$the_query = new WP_Query( $query );
		$result = '';
		if ( $the_query->have_posts() ) {
			$result .= '<div class="product-wrapper post-wrapper">';
			$result .= '<div class="post-filters">';
			$result .= '<h3 class="font-normal filter-title">' . __( 'All Products', LANGUAGE_ZONE ) . '</h3>';
			$result .= '<a href="#" class="btn btn-sm style4 hover-blue active" data-filter="filter-all" title="' . __( 'All Products', LANGUAGE_ZONE ) . '">' . __( 'All', LANGUAGE_ZONE ) . '</a>';

			$sort_terms = get_terms( 'product_cat' , array('hide_empty'=>true) );
			$current_page_terms	= array();
			$term_count 		= array();

			foreach ( $the_query->posts as $entry ) {
				if ( $current_item_terms = get_the_terms( $entry->ID, 'product_cat' ) ) {
					if ( !empty($current_item_terms) ) {
						foreach ( $current_item_terms as $current_item_term ) {
							$current_page_terms[$current_item_term->term_id] = $current_item_term->term_id;

							if ( !isset($term_count[$current_item_term->term_id] ) ) {
								$term_count[$current_item_term->term_id] = 0;
							}
							$term_count[$current_item_term->term_id] ++;
						}
					}
				}
			}

			foreach ( $sort_terms as $term ) {
				$show_item = in_array($term->term_id, $current_page_terms) ? 'show_filter' : 'hide_filter';
				if ( !isset($term_count[$term->term_id]) ) {
					$term_count[$term->term_id] = 0;
				}
				$result .= '<a href="#" class="btn btn-sm style4 hover-blue ' . $show_item . '" data-filter="filter-' . md5( $term->slug ) . '">' . $term->name . '</a>';
			}
			$result .= '</div>';

			$result .= '<div class="iso-container iso-col-' . esc_attr($columns) . ' style-masonry has-column-width products">';

			global $woocommerce_loop;
			$woocommerce_loop['columns'] = $columns;
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$item_terms = get_the_terms( get_the_ID(), 'product_cat' );

				$sort_classes = array( 'iso-item', 'filter-all' );
				if( is_object($item_terms) || is_array($item_terms) ) {
					foreach ( $item_terms as $term ) {
						$sort_classes[] = 'filter-' . md5( $term->slug );
					}
				}
				$result .= '<div class="' . implode(' ', $sort_classes) . '">';
				$result .= '<div class="' . implode(' ', get_post_class()) . '">';
				ob_start();
				do_action( 'woocommerce_before_shop_loop_item' );
				do_action( 'woocommerce_before_shop_loop_item_title' );
				echo '<h5 class="product-title"><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></h5>';
				do_action( 'woocommerce_after_shop_loop_item_title' );
				do_action( 'woocommerce_after_shop_loop_item' );
				$result .= ob_get_contents();
				ob_end_clean();
				$result .= '</div>';
				$result .= '</div>';
			endwhile;

			$result .= '</div>';
			$result .= '</div>';

			if ( $pagination == 'yes' ) {
				$result .= miracle_pagination( false, false, true, $the_query );
			}
			wp_reset_postdata();
		}
		return $result;
	}

}

endif;