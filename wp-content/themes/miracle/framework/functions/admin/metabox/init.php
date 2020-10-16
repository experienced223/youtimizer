<?php

/*
 * Define functions for meta box and intialize meta boxes
 */

require_once MIRACLE_EXT_PATH . '/meta-box/meta-box.php';

if ( !function_exists( 'miracle_register_meta_boxes' ) ) :
	function miracle_register_meta_boxes( $meta_boxes ) {

		// Quote
		$meta_boxes[] = array(
			'id' => 'miracle-metabox-quote',
			'title' => __( 'Quote Post Settings', LANGUAGE_ZONE ),
			//'description' => __( '', LANGUAGE_ZONE ),
			'pages' => array( 'post' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name' => __( 'The Quote', LANGUAGE_ZONE ),
					'desc' => __( 'Input your quote.', LANGUAGE_ZONE ),
					'id'   => '_miracle_quote_quote',
					'type' => 'textarea',
					'std'  => ''
				),
				array(
					'name' => __( 'Author', LANGUAGE_ZONE ),
					'desc' => __( 'Input the name of author who originally said.', LANGUAGE_ZONE ),
					'id'   => '_miracle_quote_cite',
					'type' => 'text',
					'std'  => ''
				)
			)
		);

		// Video
		$video_fields = array(
			array(
				'name' => __( 'Video Ratio', LANGUAGE_ZONE ),
				//'desc' => __( '', LANGUAGE_ZONE ),
				'id'   => '_miracle_video_ratio',
				'type' => 'select',
				'std'  => '',
				'options' => array(
					'16:9' => '16:9',
					'5:3' => '5:3',
					'5:4' => '5:4',
					'4:3' => '4:3',
					'3:2' => '3:2',
				)
			),
			array(
				'name' => __( 'MP4 File URL', LANGUAGE_ZONE ),
				//'desc' => __( '', LANGUAGE_ZONE ),
				'id'   => '_miracle_video_url',
				'type' => 'text',
				'std'  => ''
			),
			array(
				'name' => __( 'Embedded Video Code', LANGUAGE_ZONE ),
				'desc' => __( 'If you want to use embedded video such as YouTube or Vimeo, write the embed code here.', LANGUAGE_ZONE ),
				'id'   => '_miracle_video_embed',
				'type' => 'textarea',
				'std'  => ''
			),
			/*array(
				'name' => __( 'Full Width', LANGUAGE_ZONE ),
				'desc' => __( 'If you want to display video with full width, please check this.', LANGUAGE_ZONE ),
				'id'   => '_miracle_video_full',
				'type' => 'checkbox',
				'std'  => ''
			)*/
		);
		$meta_boxes[] = array(
			'id' => 'miracle-metabox-video',
			'title' => __( 'Video Post Settings', LANGUAGE_ZONE ),
			'description' => __( 'This enables you to embed video into your post.', LANGUAGE_ZONE ),
			'pages' => array( 'post' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => $video_fields
		);

		// Audio
		$meta_boxes[] = array(
			'id'			=> 'miracle-metabox-audio',
			'title'			=> __( 'Audio Post Settings', LANGUAGE_ZONE ),
			'description' 	=> __( 'This enables you to embed audio into your post.', LANGUAGE_ZONE ),
			'pages' 		=> array( 'post' ),
			'context'	 	=> 'normal',
			'priority'		=> 'high',
			'fields'	  	=> array(
				array(
					'name' => __( 'Audio URL', LANGUAGE_ZONE ),
					//'desc' => __( '', LANGUAGE_ZONE ),
					'id'   => '_miracle_audio_mp3',
					'type' => 'text',
					'std'  => ''
				),
				array(
					'name' => __( 'Embedded Audio Code', LANGUAGE_ZONE ),
					'desc' => __( 'If you want to use embedded audio such as Soundcloud, write the embed code here.', LANGUAGE_ZONE ),
					'id'   => '_miracle_audio_embed',
					'type' => 'textarea',
					'std'  => ''
				)
			)
		);


		// sidebar options
		$widget_areas = MiracleHelper::get_registered_sidebars();
		if ( empty($widget_areas ) ) {
			$widget_areas = array('none' => __('None', LANGUAGE_ZONE));
		}

		$meta_boxes[] = array(
			'id' => 'miracle-metabox-page-sidebar',
			'title' => __( 'Page layout', LANGUAGE_ZONE ),
			'pages' => array( 'page', 'post', 'm_portfolio', 'product' ),
			'context' => 'side',
			'priority' => 'low',
			'fields' => array(
				// Sidebar option
				array(
					'name' => __( 'Sidebar position:', LANGUAGE_ZONE ),
					'id' => '_miracle_sidebar_position',
					'type' => 'radio',
					'std' => 'disabled',
					'options' => array(
						'left' => __( 'Left', LANGUAGE_ZONE ),
						'right' => __( 'Right', LANGUAGE_ZONE ),
						'disabled' => __( 'No Sidebar', LANGUAGE_ZONE )
					)
				),

				// Sidebar widget area
				array(
					'name' => __( 'Sidebar widget area:', LANGUAGE_ZONE ),
					'id' => '_miracle_sidebar_widget_area',
					'type' => 'select',
					'options' => $widget_areas,
					'std' => 'sidebar_1'
				),
			),

		);


		// Portfolio Item Settings
		$meta_boxes[] = array(
			'id' => 'miracle-meta-box-portfolio-item',
			'title' => __( 'Portfolio Item Settings', LANGUAGE_ZONE ),
			'description' => __( 'Select the appropriate options for your portfolio item.', LANGUAGE_ZONE ),
			'pages' => array( 'm_portfolio' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name' => __( 'Project Link', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_item_project_link',
					'desc' => __( 'Provide an external link for this project.', LANGUAGE_ZONE ),
					'type' => 'text',
				),
				array(
					'name' => __( 'Media Type', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_item_media_type',
					'type' => 'radio',
					'std' => 'image',
					'options' => array(
						'image' => __( 'Image', LANGUAGE_ZONE ),
						'gallery' => __( 'Gallery', LANGUAGE_ZONE ),
						'video' => __( 'Video', LANGUAGE_ZONE )
					)
				),
				array(
					'name' => __( 'Gallery View Style', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_item_gallery_view_style',
					'type' => 'radio',
					'options' => array(
						'slider1' => __( 'Simple slider', LANGUAGE_ZONE ),
						'slider2' => __( 'Slider with caption', LANGUAGE_ZONE ),
						'list' => __( 'List Gallery', LANGUAGE_ZONE ),
						'gallery1' => __( 'Grid Gallery', LANGUAGE_ZONE ),
						'gallery2' => __( 'Masonry Gallery', LANGUAGE_ZONE ),
					),
					'std' => 'slider1'
				),
				array(
					'name' => __( 'Columns in Gallery View Style', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_item_gallery_columns',
					'desc' => __( 'Select the columns of gallery.', LANGUAGE_ZONE ),
					'type' => 'radio',
					'options' => array(
						'1' => 'One',
						'2' => 'Two',
						'3' => 'Three',
						'4' => 'Four',
						'5' => 'Five',
					),
					'std' => '3'
				),
				array(
					'name' => __( 'Project View Options', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_item_view_options',
					'desc' => __( 'Select vertical if you want to display portfolio media content to the left and portfolio content to the right.', LANGUAGE_ZONE ),
					'type' => 'radio',
					'options' => array(
						'wide' => __( 'Wide', LANGUAGE_ZONE ),
						'vertical' => __( 'Vertical', LANGUAGE_ZONE )
					),
					'std' => 'wide'
				)
			)
		);

		$meta_boxes[] = array(
			'id' => 'miracle-metabox-portfolio-video',
			'title' => __( 'Video Portfolio Item Settings', LANGUAGE_ZONE ),
			'description' => __( 'This enables you to embed video into your portfolio.', LANGUAGE_ZONE ),
			'pages' => array( 'm_portfolio' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => $video_fields
		);

		// Portfolio page template.
		$meta_boxes[] = array(
			'id' => 'miracle-meta-box-portfolio-page',
			'title' => __( 'Portfolio Settings', LANGUAGE_ZONE ),
			'description' => __( 'Here you will find various options you can use to setup your portfolio.', LANGUAGE_ZONE ),
			'pages' => array( 'page' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name' => __( 'Category Select', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_category_filters',
					'type' => 'taxonomy_advanced',
					'placeholder' => __( 'All Categories', LANGUAGE_ZONE ),
					'options' => array(
						'taxonomy' => 'm_portfolio_category',
						'type' => 'select_advanced',
						'args' => array(
						),
					),
					'multiple' => true
				),
				array(
					'name' => __( 'Columns', LANGUAGE_ZONE ),
					'desc' => __( 'Select how many columns you would like to display for your portfolio.', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_columns',
					'type' => 'radio',
					'std' => '2',
					'options' => array(
						'1' => 'One',
						'2' => 'Two',
						'3' => 'Three',
						'4' => 'Four',
						'5' => 'Five',
						'6' => 'Six'
					)
				),
				array(
					'name' => __( 'Full Width?', LANGUAGE_ZONE ),
					'desc' => __( 'Sidebar is disabled if this option is checked.', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_is_fullwidth',
					'type' => 'checkbox',
					'std' => array()
				),
				array(
					'name' => __( 'Posts Per Page', LANGUAGE_ZONE ),
					'desc' => __( 'Select how many posts you would like to display per page for your portfolio.', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_posts_per_page',
					'type' => 'text',
					'std' => '24'
				),
				array(
					'name' => __( 'Style', LANGUAGE_ZONE ),
					'desc' => __( 'Select the style of layout.', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_style',
					'type' => 'radio',
					'std' => 'fancy',
					'options' => array(
						'fancy' => __( 'Fancy Grid', LANGUAGE_ZONE ),
						'grid' => __( 'Grid', LANGUAGE_ZONE ),
						'masonry1' => __( 'Masonry 1', LANGUAGE_ZONE ),
						'masonry2' => __( 'Masonry 2', LANGUAGE_ZONE ),
						'masonry3' => __( 'Masonry with details', LANGUAGE_ZONE ),
					)
				),
				array(
					'name' => __( 'Display Content Area', LANGUAGE_ZONE ),
					'desc' => __( 'If you don\'t want to display page content, please select No. Otherwise select the content area position to display page content.' , LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_content_area',
					'type' => 'select',
					'std' => 'no',
					'options' => array(
						'no' => __( 'No', LANGUAGE_ZONE ),
						'before_items_first_page' => __( 'Before items on only first page', LANGUAGE_ZONE ),
						'before_items_all_page' => __( 'Before items on all pages', LANGUAGE_ZONE ),
						'after_items_first_page' => __( 'After items on only first page', LANGUAGE_ZONE ),
						'after_items_all_page' => __( 'After items on all pages', LANGUAGE_ZONE ),
					)
				),
				array(
					'name' => __( 'Disable Filtering', LANGUAGE_ZONE ),
					'desc' => __( 'Turning off the portfolio filters will remove the ability to sort portfolio items by category.', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_disable_filtering',
					'type' => 'checkbox',
					'std' => array()
				),
				array(
					'name' => __( 'Loading Style', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_loading_style',
					'type' => 'radio',
					'std' => 'default',
					'options' => array(
						'default' => __( 'Default', LANGUAGE_ZONE ),
						'ajax' => __( 'Ajax Pagination', LANGUAGE_ZONE ),
						'load_more' => __( 'Ajax Load More', LANGUAGE_ZONE ),
					)
				),
				array(
					'name' => __( 'Order By', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_orderby',
					'type' => 'select',
					'std' => 'date',
					'options' => array(
						'date' => __( 'Date', LANGUAGE_ZONE ),
						'name' => __( 'Name', LANGUAGE_ZONE )
					)
				),
				array(
					'name' => __( 'Order', LANGUAGE_ZONE ),
					'id' => '_miracle_portfolio_order',
					'type' => 'radio',
					'std' => 'DESC',
					'options' => array(
						'ASC' => __( 'ASC', LANGUAGE_ZONE ),
						'DESC' => __( 'DESC', LANGUAGE_ZONE )
					)
				),
			)
		);

		// Page Settings
		$fields = array();
		$fields[] = array(
			'name' => __( 'Header Settings', LANGUAGE_ZONE ),
			'type' => 'heading',
			'id' => '_miracle_header_settings'
		);
		$fields[] = array(
			'name' => __( 'Header Style', LANGUAGE_ZONE ),
			'id' => "_miracle_header_style",
			'desc' => __( 'Disable Header hides page title container which contains page title and breadcrumbs.', LANGUAGE_ZONE ),
			'type' => 'radio',
			'options' => array(
				'none' => __( 'Disable Header', LANGUAGE_ZONE ),
				'color' => __( 'Background Color', LANGUAGE_ZONE ),
				'style1' => __( 'Background Clip Image', LANGUAGE_ZONE ),
				'style2' => __( 'Background Image', LANGUAGE_ZONE ),
				'style3' => __( 'Background Parallax', LANGUAGE_ZONE ),
				'map' => __( 'Google Map', LANGUAGE_ZONE ),
				'video' => __( 'Video', LANGUAGE_ZONE )
			),
			'std' => miracle_get_option( 'header_inner_style', 'style1' )
		);
		$fields[] = array(
			'name' => __( 'Background Color', LANGUAGE_ZONE ),
			'id' => '_miracle_header_background_color',
			'type' => 'color',
			'std' => miracle_get_option( 'header_background_color' ),
			'class' => 'header-style-color-visible'
		);
		$fields[] = array(
			'name' => __( 'Background Clip Image', LANGUAGE_ZONE ),
			'id' => '_miracle_header_background_clip_image',
			'type' => 'image_advanced',
			'max_file_uploads' => 1,
			'class' => 'header-style-style1-visible'
		);
		$fields[] = array(
			'name' => __( 'Background Image', LANGUAGE_ZONE ),
			'id' => '_miracle_header_background_image',
			'type' => 'image_advanced',
			'class' => 'header-style-style2-visible header-style-style3-visible'
		);
		$fields[] = array(
			'name' => __( 'Parallax Ratio (0 ~ 1)', LANGUAGE_ZONE ),
			'id' => '_miracle_header_parallax_ratio',
			'type' => 'text',
			'std' => miracle_get_option( 'header_background_parallax_ratio', 0.5 ),
			/*'js_options' => array(
				'min'   => 0,
				'max'   => 1,
				'step'  => 0.1,
			),*/
			'class' => 'header-style-style3-visible'
		);
		$fields[] = array(
			'name' => __( 'Map Address', LANGUAGE_ZONE ),
			'id' => '_miracle_header_map_address',
			'type' => 'text',
			'class' => 'header-style-map'
		);
		$fields[] = array(
			'name' => __( 'Map Location', LANGUAGE_ZONE ),
			'id' => '_miracle_header_map_code',
			'type' => 'map',
			'style' => 'width: 500px; height: 300px',
			'address_field' => '_miracle_header_map_address',
			'class' => 'header-style-map'
		);
		$fields[] = array(
			'name' => __( 'Zoom', LANGUAGE_ZONE ),
			'id' => '_miracle_header_map_zoom',
			'type' => 'number',
			'std' => '14',
			'class' => 'header-style-map'
		);
		$fields[] = array(
			'name' => __( 'Marker Icon', LANGUAGE_ZONE ),
			'id' => '_miracle_header_map_marker_icon',
			'type' => 'image_advanced',
			'class' => 'header-style-map miracle-header-map-marker-icon'
		);
		$fields[] = array(
			'name' => __( 'Video URL', LANGUAGE_ZONE ),
			'desc' => __( 'You can input youtube, vimeo or mp4 file url.', LANGUAGE_ZONE ),
			'id' => '_miracle_header_video_url',
			'type' => 'text',
			'class' => 'header-style-video-visible full-width'
		);
		$fields[] = array(
			'name' => __( 'Video Ratio', LANGUAGE_ZONE ),
			'id' => '_miracle_header_video_ratio',
			'type' => 'select',
			'options' => array(
				'16:9' => '16:9',
				'4:3' => '4:3',
				'5:4' => '5:4',
				'5:3' => '5:3'
			),
			'std' => '16:9',
			'class' => 'header-style-video-visible'
		);
		$fields[] = array(
			'name' => __( 'Header Inner Height', LANGUAGE_ZONE ) . ' (px)',
			'desc' => __( 'If you want to display full screen header, please input "0" in this field.', LANGUAGE_ZONE ),
			'id' => '_miracle_header_height',
			'type' => 'number',
			'class' => 'header-style-none-hidden'
		);
		$fields[] = array(
			'name' => __( 'Header Font Color', LANGUAGE_ZONE ),
			'id' => '_miracle_header_font_color',
			'type' => 'color',
			'std' => miracle_get_option( 'header_font_color', '#000000' )
		);
		$fields[] = array(
			'name' => __( 'Header Caption', LANGUAGE_ZONE ),
			'desc' => __( 'If header caption is enabled, default page title isn\'t displayed.', LANGUAGE_ZONE ),
			'id' => '_miracle_header_caption',
			'type' => 'select',
			'options' => array(
				'none' => __( 'None', LANGUAGE_ZONE ),
				'style1' => __( 'Style 1', LANGUAGE_ZONE ),
				'style2' => __( 'Style 2', LANGUAGE_ZONE ),
				'style3' => __( 'Style 3', LANGUAGE_ZONE )
			),
			'std' => 'none',
			'class' => 'header-style-none-hidden header-style-map-hidden'
		);
		$fields[] = array(
			'name' => __( 'Header Caption Title', LANGUAGE_ZONE ),
			'id' => '_miracle_header_caption_title',
			'type' => 'text',
			'class' => 'header-style-none-hidden header-style-map-hidden header-caption-none-hidden full-width'
		);
		$fields[] = array(
			'name' => __( 'Header Caption Sub Title', LANGUAGE_ZONE ),
			'id' => '_miracle_header_caption_sub_title',
			'type' => 'text',
			'class' => 'header-style-none-hidden header-style-map-hidden header-caption-none-hidden full-width'
		);
		$fields[] = array(
			'name' => __( 'Header Caption Extra Class', LANGUAGE_ZONE ),
			'id' => '_miracle_header_caption_class',
			'type' => 'text',
			'class' => 'header-style-none-hidden header-style-map-hidden header-caption-none-hidden full-width'
		);
		$fields[] = array(
			'name' => __( 'Header Caption Extra HTML', LANGUAGE_ZONE ),
			'id' => '_miracle_header_caption_html',
			'type' => 'wysiwyg',
			'class' => 'header-style-none-hidden header-style-map-hidden header-caption-none-hidden full-width'
		);
		$fields[] = array(
			'name' => __( 'Show Page Title', LANGUAGE_ZONE ),
			'id' => '_miracle_show_page_title',
			'type' => 'checkbox',
			'std' => miracle_get_option( 'header_show_page_title', '1' ),
			'class' => 'header-style-none-hidden header-style-map-hidden'
		);

		if ( class_exists( 'RevSlider' ) ) {
			$fields[] = array(
				'name' => __( 'Revolution Slider', LANGUAGE_ZONE ),
				'desc' => __( 'To activate your slider, select an option from the dropdown. To deactivate your slider, set the dropdown back to "Deactivated."', LANGUAGE_ZONE ),
				'id' => "_miracle_rev_slider",
				'type' => 'rev_slider',
				'std' => 'Deactivated',
				'placeholder' => 'Deactivated'
			);
		}

		$fields[] = array(
			'name' => __( 'Page Settings', LANGUAGE_ZONE ),
			'type' => 'heading',
			'id' => '_miracle_custom_settings'
		);
		$fields[] = array(
			'name' => __( 'One Page Navigation', LANGUAGE_ZONE ),
			'id' => "_miracle_one_page_nav",
			'desc' => __( 'To activate your one page navigation, select a menu from the dropdown.', LANGUAGE_ZONE ),
			'type' => 'taxonomy_advanced',
			'options' => array(
				'taxonomy' => 'nav_menu',
				'type' => 'select_advanced',
			),
		);
		$fields[] = array(
			'name' => __( 'Custom CSS', LANGUAGE_ZONE ),
			'id' => "_miracle_custom_css",
			'desc' => __( 'Enter custom css code here.', LANGUAGE_ZONE ),
			'type' => 'textarea',
		);
		$meta_boxes[] = array(
			'id' => 'miracle-metabox-page-settings',
			'title' => __( 'Page Settings', LANGUAGE_ZONE ),
			'pages' => array( 'page', 'm_portfolio', 'post', 'product' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => $fields
		);


		// woocommerce product hover image
		$fields = array();
		$fields[] = array(
			//'name' => __( '', LANGUAGE_ZONE ),
			'id' => "_miracle_product_hover_img",
			'desc' => __( 'If you want to display a hover effect on overview pages and replace the default product image, please upload the hover image here.', LANGUAGE_ZONE ),
			'type' => 'image_advanced',
			'max_file_uploads' => 1,
		);
		$meta_boxes[] = array(
			'id' => 'miracle-metabox-product-hover-image',
			'title' => __( 'Product Hover', LANGUAGE_ZONE ),
			'pages' => array( 'product' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => $fields
		);

		return $meta_boxes;
	}
endif;

add_filter( 'rwmb_meta_boxes', 'miracle_register_meta_boxes' );


if ( !function_exists( 'miracle_metabox_update_selectfield' ) ) :

    function miracle_metabox_update_selectfield( $value ) {
        $value = str_replace( "<option></option>", "", $value );
        $value = str_replace( "<option value=''></option>", "", $value );
        return $value;
    }
endif;
add_filter( 'rwmb_select_html', 'miracle_metabox_update_selectfield' );