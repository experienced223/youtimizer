<?php
/**
 * Outputs the page title container including breadcrumbs
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( is_home() ) {
	$page_id = get_option( 'page_for_posts' );
} else if ( is_singular() ) {
	$page_id = get_the_ID();
} else {
	$page_id = null;
}

$page_header_style = get_post_meta( $page_id, '_miracle_header_style', true );
if ( empty( $page_header_style ) ) {
	$header_style = miracle_get_option( 'header_inner_style', 'style1' );
} else {
	$header_style = $page_header_style;
}

$show_page_title = miracle_get_option('header_show_page_title');
$show_breadcrumbs = miracle_get_option('header_show_breadcrumbs') == true ? true : false;

if ( $header_style != 'none' && !is_page_template( 'template-home.php' ) ) :

	$header_caption = 'none';
	if ( !empty( $page_header_style ) && is_singular() ) {
		$header_caption = get_post_meta( $page_id, '_miracle_header_caption', true );
		if ( $header_caption != 'none' ) {
			$header_caption_title = get_post_meta( $page_id, '_miracle_header_caption_title', true );
			$header_caption_sub_title = get_post_meta( $page_id, '_miracle_header_caption_sub_title', true );
		}

		$show_page_title = get_post_meta( $page_id, '_miracle_show_page_title', true );
	}

	$page_title_style = array();
	$page_title_classes = array( 'page-title-container' );
	$page_title_attrs = '';
	$page_title_inner_classes = array();

	if ( $header_caption !== 'none' && !empty( $header_caption_title ) ) {
		$show_page_title = false;
		$page_title_inner_classes[] = 'banner';
	} else {
		$page_title_inner_classes[] = 'page-title';
	}

	$show_page_title = $show_page_title ? true : false;


	$header_height = get_post_meta( $page_id, '_miracle_header_height', true );
	$page_title_inner_attrs = "";
	if ( !empty( $header_height ) ) {
		//$page_title_style[] = 'height: ' . esc_attr( $header_height ) . 'px';
		$page_title_inner_attrs = ' style="height: ' . esc_attr( $header_height ) . 'px;"';
	} else if ( $header_height === '0' ) {
		$page_title_classes[] = 'header-fullscreen';
	}

	if ( $header_style == 'style1' ) {
		$bg_img = get_post_meta( $page_id, '_miracle_header_background_clip_image', true );
		if ( empty( $bg_img ) ) {
			$bg_img = miracle_get_option('header_background_clip_image');
			if ( !empty( $bg_img['url'] ) ) {
				$bg_img = $bg_img['url'];
			} else {
				$bg_img = '';
			}
		} else {
			$bg_img_arr = miracle_get_attachment_post_data( array( $bg_img ) );
			$bg_img = $bg_img_arr[0]['full'];
		}
		if ( !empty( $bg_img ) ) {
			$page_title_style[] = "background-image: url('" . esc_url( $bg_img ) . "')";
			$page_title_style[] = 'background-repeat: repeat';
		}
	} else if ( $header_style == 'style2' || $header_style == 'style3' ) {
		$bg_img = get_post_meta( $page_id, '_miracle_header_background_image', true );
		if ( empty( $bg_img ) ) {
			$bg_img = miracle_get_option('header_background_image');
			if ( !empty( $bg_img['url'] ) ) {
				$bg_img = $bg_img['url'];
			} else {
				$bg_img = '';
			}
		} else {
			$bg_img_arr = miracle_get_attachment_post_data( array( $bg_img ) );
			$bg_img = $bg_img_arr[0]['full'];
		}
		if ( !empty( $bg_img ) ) {
			$page_title_style[] = "background-image: url('" . esc_url( $bg_img ) . "')";
			$page_title_style[] = 'background-repeat: no-repeat';
			if ( $header_style == 'style3' ) {
				$parallax_ratio = get_post_meta( $page_id, 'header_background_parallax_ratio', true );
				if ( empty( $parallax_ratio ) ) {
					$parallax_ratio = miracle_get_option('header_background_parallax_ratio');
				}
				$page_title_classes[] = 'style2';
				$page_title_classes[] = 'parallax';
				$page_title_attrs .= ' data-stellar-background-ratio="' . esc_attr( $parallax_ratio ) . '"';
			} else {
				$page_title_style[] = 'background-size: cover';
			}
		}
	} else if ( $header_style == 'map' ) {
		$page_title_classes[] = 'style-map';
	} else if ( $header_style == 'video' ) {
		$page_title_classes[] = 'style-video';
	} else if ( $header_style == 'color' ) {
		$bg_color = get_post_meta( $page_id, '_miracle_header_background_color', true );
		if ( empty( $bg_color ) ) {
			$bg_color = miracle_get_option('header_background_color');
		}
		if ( !empty( $bg_color ) ) {
			$page_title_style[] = "background: " . esc_attr( $bg_color );
		}
	}

	if ( $header_caption == 'style1' ) {
		$page_title_classes[] = 'style4';
	} else if ( $header_caption == 'style2' ) {
		$page_title_classes[] = 'style5';
	} else if ( $header_caption == 'style3' ) {
		$page_title_classes[] = 'style6';
	}

	if ( !empty( $page_title_style ) ) {
		$page_title_style = ' style="' . implode('; ', $page_title_style) . '"';
	} else {
		$page_title_style = '';
	}

	$header_font_color = get_post_meta( $page_id, '_miracle_header_font_color', true );
	if ( empty( $header_font_color ) ) {
		$header_font_color = miracle_get_option('header_font_color');
	}


	// if homepage
	if ( is_front_page()) :
		$show_breadcrumbs = false;
		$show_page_title = false;
	endif;
	if ( is_home() ) :
		$show_breadcrumbs = false;
		$show_page_title = true;
	endif;

	$page_title_wrap = '<div class="' . esc_attr( implode(' ', $page_title_classes) ) . '"' . $page_title_attrs . $page_title_style . '>%s</div>';
	if ( $header_style == 'style1' || $header_style == 'style2' || $header_style == 'style3' ) {

	} else if ( $header_style == 'video' ) {

		$video_url = get_post_meta( $page_id, '_miracle_header_video_url', true );
		if ( !empty( $video_url ) ) {
			$video_ratio = get_post_meta( $page_id, '_miracle_header_video_ratio', true );
			$page_title_wrap = sprintf( '<div class="%s"%s>[video_parallax src="%s" ratio="1" video_ratio="%s" autoplay="yes"]',
				esc_attr( implode(' ', $page_title_classes ) ),
				$page_title_attrs . $page_title_style,
				esc_url( $video_url ),
				esc_attr( $video_ratio )
			) . '%s</div>';
		}

	} else if ( $header_style == 'map' ) {

		$map_code = get_post_meta( $page_id, '_miracle_header_map_code', true );
		$map_zoom = get_post_meta( $page_id, '_miracle_header_map_zoom', true );
		$map_marker_icon = get_post_meta( $page_id, '_miracle_header_map_marker_icon', true );
		if ( empty( $map_zoom ) ) {
			$map_zoom = '14';
		} else {
			$map_marker_icon = wp_get_attachment_image_src( $map_marker_icon );
		}
		if ( !empty( $map_code ) ) {
			$page_title_wrap = sprintf( $page_title_wrap, '<div class="page-title"%s></div><div class="soap-google-map" data-map-code="%s" data-zoom="%s"%s></div>%s' );
			$page_title_wrap = sprintf( $page_title_wrap,
				$page_title_inner_attrs,
				esc_attr( $map_code ),
				$map_zoom,
				empty($map_marker_icon) ? '' : ' data-marker-icon = "' . esc_url( $map_marker_icon[0] ) . '"',
				'%s'
			);
		}

	}

	ob_start();
	if ( $header_style != 'map' ) {
?>
	<div class="<?php echo esc_attr( implode(' ', $page_title_inner_classes) ); ?>"<?php echo empty( $page_title_inner_attrs ) ? '' : $page_title_inner_attrs; ?>>
		<div class="container">
		<?php if ( $header_caption !== 'none' && !empty( $header_caption_title ) ) { ?>
		<?php
			$caption_wrapper_class = get_post_meta( $page_id, '_miracle_header_caption_class', true );
		?>
			<div class="caption-wrapper position-right<?php echo empty( $caption_wrapper_class ) ? '' : ' ' . $caption_wrapper_class; ?>">
				<div class="caption">
					<h2 class="caption-lg"><?php echo stripslashes( htmlspecialchars_decode( esc_html( $header_caption_title ) ) ); ?></h2>
					<?php if ( !empty( $header_caption_sub_title ) ) { ?>
					<h5 class="caption-sm"><?php echo stripslashes( htmlspecialchars_decode( esc_html( $header_caption_sub_title ) ) ); ?></h5>
					<?php } ?>
					<?php
						$html_content = get_post_meta( $page_id, '_miracle_header_caption_html', true );
						if ( !empty( $html_content ) ) {
							echo do_shortcode( $html_content );
						}
					?>
				</div>
			</div>
		<?php } else if ( $show_page_title ) { ?>
			<h1 class="entry-title" style="color: <?php echo esc_attr( $header_font_color ); ?>"><?php miracle_display_page_title(); ?></h1>
		<?php } ?>
		</div>
	</div>
<?php
	}

	if ( $show_breadcrumbs ) {
		miracle_breadcrumbs();
	}

	$page_title_content_html = ob_get_contents();
	ob_end_clean();
	if ( $header_style == 'video' ) {
		echo do_shortcode( sprintf( $page_title_wrap, $page_title_content_html ) );
	} else {
		printf( $page_title_wrap, $page_title_content_html );
	}
?>
<?php endif; ?>