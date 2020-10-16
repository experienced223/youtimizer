<?php

// ! File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


// ! Removing unwanted shortcodes
/* vc_remove_element("vc_widget_sidebar"); */
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_gallery");
vc_remove_element("vc_teaser_grid");
vc_remove_element("vc_button");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_posts_grid");
vc_remove_element("vc_images_carousel");

vc_remove_element("vc_posts_slider");
vc_remove_element("vc_carousel");

vc_remove_element("vc_message");
vc_remove_element("vc_progress_bar");

// Replace rows and columns classes
function custom_css_classes_for_vc_row_and_vc_column($class_string, $tag, $atts) {
	if ($tag =='vc_row' || $tag =='vc_row_inner') {
		$class_string = str_replace('vc_row-fluid', 'row', $class_string);
		if ( !empty( $atts['add_clearfix'] ) ) {
			$class_string .= ' add-clearfix';
		}
		if ( !empty( $atts['children_same_height'] ) ) {
			$class_string .= ' same-height';
		}
	}

	if ($tag =='vc_column' || $tag =='vc_column_inner') {
		if ( !(function_exists('vc_is_inline') && vc_is_inline()) ) {
			if( preg_match('/vc_col-(\w{2})-(\d{1,2})/', $class_string) ) {
                $class_string = str_replace('vc_column_container', '', $class_string);
            }
			$class_string = preg_replace('/vc_col-(\w{2})-(\d{1,2})/', 'col-$1-$2', $class_string);
			$class_string = preg_replace('/vc_hidden-(\w{2})/', 'hidden-$1', $class_string);
		}
	}

	if ( $tag == 'vc_tabs' ) {
		$class_string .= ' tab-container ' . esc_attr( $atts['style'] );
	}

	if ( !empty( $atts['margin_bottom_class'] ) ) {
		switch ( $atts['margin_bottom_class'] ) {
			case "none":
				$class_string .= ' no-bmargin';
			case "small": // margin-bottom : 20
				$class_string .= ' box-sm';
				break;
			case "medium": // margin-bottom : 30
				$class_string .= ' box';
				break;
			case "large": // margin-bottom : 40
				$class_string .= ' box-lg';
				break;
			case "x-large": // margin-bottom : 60
				$class_string .= ' block';
				break;
		}
	}

	return $class_string;
}
add_filter('vc_shortcodes_css_class', 'custom_css_classes_for_vc_row_and_vc_column', 10, 3);

$margin_bottom = array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Margin Bottom", LANGUAGE_ZONE),
	"param_name" => "margin_bottom_class",
	"value" => array(
		__( "Inherit", LANGUAGE_ZONE ) => "",
		__( "None", LANGUAGE_ZONE ) => "none",
		__( "Small", LANGUAGE_ZONE ) => "small",
		__( "Medium", LANGUAGE_ZONE ) => "medium",
		__( "Large", LANGUAGE_ZONE ) => "large",
		__( "Extra large", LANGUAGE_ZONE ) => "x-large"
	),
	"description" => ""
);

$content_area = array(
	"type" => "textarea_html",
	"heading" => __( "Content", LANGUAGE_ZONE ),
	"param_name" => "content",
	"description" => __( "Enter your content.", LANGUAGE_ZONE )
);

$extra_class = array(
	'type' => 'textfield',
	'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
	'param_name' => 'class',
	'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
);

$posts_fields = array(
	array(
		'type' => 'posttypes',
		'heading' => __( 'Post types', LANGUAGE_ZONE ),
		'param_name' => 'post_type',
		'description' => __( 'Select post types to populate posts from.', LANGUAGE_ZONE )
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Post IDs', LANGUAGE_ZONE ),
		'param_name' => 'ids',
		'description' => __( 'Fill this field with page/posts IDs separated by commas (,), to retrieve only them. Use this in conjunction with "Post types" field.', LANGUAGE_ZONE )
	),
	array(
		'type' => 'exploded_textarea',
		'heading' => __( 'Categories', LANGUAGE_ZONE ),
		'param_name' => 'category',
		'description' => __( 'If you want to narrow output, enter category names here. Note: Only listed categories will be included. Divide categories with linebreaks (Enter) . ', LANGUAGE_ZONE )
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Order by', LANGUAGE_ZONE ),
		'param_name' => 'orderby',
		'value' => array(
			'',
			__( 'Date', LANGUAGE_ZONE ) => 'date',
			__( 'ID', LANGUAGE_ZONE ) => 'ID',
			__( 'Author', LANGUAGE_ZONE ) => 'author',
			__( 'Title', LANGUAGE_ZONE ) => 'title',
			__( 'Modified', LANGUAGE_ZONE ) => 'modified',
			__( 'Random', LANGUAGE_ZONE ) => 'rand',
			__( 'Comment count', LANGUAGE_ZONE ) => 'comment_count',
			__( 'Menu order', LANGUAGE_ZONE ) => 'menu_order'
		),
		'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
	),
	array(
		'type' => 'dropdown',
		'heading' => __( 'Order', LANGUAGE_ZONE ),
		'param_name' => 'order',
		'value' => array(
			__( 'Descending', LANGUAGE_ZONE ) => 'DESC',
			__( 'Ascending', LANGUAGE_ZONE ) => 'ASC'
		),
		'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' )
	)
);

/* CSS3 Animation Params */
$add_css3_animation = array(
	// animation type
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Animation Type", LANGUAGE_ZONE),
		"admin_label" => false,
		"param_name" => "animation_type",
		"value" => "",
		"description" => "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see framework/assets/css/animate.css for the animation types. <br />Note: Works only in modern browsers."
	),

	// animation duration
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Animation Duration", LANGUAGE_ZONE),
		"param_name" => "animation_duration",
		"value" => "1",
		"description" => "Input the duration of animation in seconds.",
		"dependency" => array(
			"element" => "animation_type",
			"not_empty" => true
		)
	),

	// animation delay
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __("Animation Delay", LANGUAGE_ZONE),
		"param_name" => "animation_delay",
		"value" => "0",
		"description" => "Input the delay of animation in seconds.",
		"dependency" => array(
			"element" => "animation_type",
			"not_empty" => true
		)
	)
);

/* Container */
vc_map(
	array(
	  'base'			=> 'vc_container',
	  'name'			=> __( 'Container', LANGUAGE_ZONE ),
	  'weight'		  => 990,
	  'class'		   => '',
	  'show_settings_on_create' => false,
	  'icon'			=> 'container',
	  'category'		=> __( 'Structure', LANGUAGE_ZONE ),
	  'description'	 => __( 'Include a container in your content', LANGUAGE_ZONE ),
	  /*'as_parent'	   => array( 'only' => 'vc_row' ),*/
	  'is_container'	=> true,
	  'content_element' => true,
	  'js_view'		 => 'VcColumnView',
	  'params'		  => array(
	  	array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', LANGUAGE_ZONE ),
			'param_name' => 'class',
			'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', LANGUAGE_ZONE )
		)
	  ),
	)
  );

/* ROW */

vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Add clearfix", LANGUAGE_ZONE),
	"param_name" => "add_clearfix",
	"value" => array(
		"" => "false"
	)
));

vc_add_param("vc_row_inner", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Add clearfix", LANGUAGE_ZONE),
	"param_name" => "add_clearfix",
	"value" => array(
		"" => "false"
	)
));

vc_add_param("vc_row", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Make children has same height", LANGUAGE_ZONE),
	"param_name" => "children_same_height",
	"value" => array(
		"" => "false"
	)
));
vc_add_param("vc_row_inner", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Make children has same height", LANGUAGE_ZONE),
	"param_name" => "children_same_height",
	"value" => array(
		"" => "false"
	)
));

vc_add_param("vc_row", $margin_bottom);

/* Column */
vc_add_param("vc_column", $margin_bottom);

vc_add_param("vc_row_inner", $margin_bottom);

vc_add_param("vc_column_inner", $margin_bottom);

vc_add_param("vc_column_text", $margin_bottom);

vc_add_params("vc_row", $add_css3_animation);
vc_add_params("vc_row_inner", $add_css3_animation);
vc_add_params("vc_column", $add_css3_animation);
vc_add_params("vc_column_inner", $add_css3_animation);

vc_remove_param("vc_column_text", "css_animation");
vc_remove_param("vc_toggle", "css_animation");
vc_remove_param("vc_single_image", "css_animation");
vc_remove_param("vc_cta_button2", "css_animation");


/* Animation Shortcode */
vc_map( array(
	"name" => __("CSS3 Animation", LANGUAGE_ZONE),
	"base" => "animation",
	"icon" => "animation",
	"class" => "",
	"controls" => "full",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		// animation type
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Type", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "type",
			"value" => "",
			"description" => "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see framework/assets/css/animate.css for the animation types. <br />Note: Works only in modern browsers."
		),

		// animation duration
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Duration", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "duration",
			"value" => "1",
			"description" => "Input the duration of animation in seconds.",
			"dependency" => array(
				"element" => "type",
				"not_empty" => true
			)
		),

		// animation delay
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Delay", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "delay",
			"value" => "0",
			"description" => "Input the delay of animation in seconds.",
			"dependency" => array(
				"element" => "type",
				"not_empty" => true
			)
		),

		array(
			"type" => "textarea_html",
			"holder" => "div",
			"class" => "",
			"heading" => __( "Content to be animated", LANGUAGE_ZONE ),
			"param_name" => "content",
			"value" => __( "<p>I am test text block. Click edit button to change this text.</p>", LANGUAGE_ZONE ),
			"description" => __( "Enter your content.", LANGUAGE_ZONE )
		)
	),
	//'js_view' => 'VcAnimationView'
) );


/* Blockquote Shortcode */
vc_map( array(
	"name" => __("Blockquote", LANGUAGE_ZONE),
	"base" => "blockquote",
	"icon" => "blockquote",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2",
				__( "Style 3", LANGUAGE_ZONE ) => "style3"
			),
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Author", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "author",
			"description" => __( "The Author who said this at first", LANGUAGE_ZONE ),
		),
		array(
			"type" => "textarea_html",
			"holder" => "",
			"class" => "",
			"heading" => __( "Content", LANGUAGE_ZONE ),
			"param_name" => "content",
			"value" => __( "<p>I am test text block. Click edit button to change this text.</p>", LANGUAGE_ZONE ),
			"description" => __( "Enter your content.", LANGUAGE_ZONE )
		)
	)
) );

/* Blockquote Shortcode */
vc_map( array(
	"name" => __("Headline", LANGUAGE_ZONE),
	"base" => "headline",
	"icon" => "headline",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Level", LANGUAGE_ZONE),
			"param_name" => "level",
			"admin_label" => true,
			"value" => array(
				"H1" => "h1",
				"H2" => "h2",
				"H3" => "h3",
				"H4" => "h4",
				"H5" => "h5",
				"H6" => "h6"
			),
			"std" => "h2",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Extra Class for Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title_class",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Sub Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "sub_title",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Width on Large Device", LANGUAGE_ZONE),
			"param_name" => "lg",
			"value" => array(
				"Select" => "",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
				"7" => "7",
				"8" => "8",
				"9" => "9",
				"10" => "10",
				"11" => "11",
				"12" => "12"
			),
			"std" => "",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Width on Medium Device", LANGUAGE_ZONE),
			"param_name" => "md",
			"value" => array(
				"Select" => "",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
				"7" => "7",
				"8" => "8",
				"9" => "9",
				"10" => "10",
				"11" => "11",
				"12" => "12"
			),
			"std" => "",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Width on Small Device", LANGUAGE_ZONE),
			"param_name" => "sm",
			"value" => array(
				"Select" => "",
				"1" => "1",
				"2" => "2",
				"3" => "3",
				"4" => "4",
				"5" => "5",
				"6" => "6",
				"7" => "7",
				"8" => "8",
				"9" => "9",
				"10" => "10",
				"11" => "11",
				"12" => "12"
			),
			"std" => "",
			"description" => ""
		),
		$content_area
	)
) );

/* Icon Box Shortcode */
vc_map( array(
	"name" => __("Icon Box", LANGUAGE_ZONE),
	"base" => "icon_box",
	"icon" => "icon_box",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Class for icon", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "icon_class",
			'description' => 'f.e: fa fa-coffee'
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				__( "Centered 1", LANGUAGE_ZONE ) => "centered1",
				__( "Centered 2", LANGUAGE_ZONE ) => "centered2",
				__( "Centered 3", LANGUAGE_ZONE ) => "centered3",
				__( "Centered 4", LANGUAGE_ZONE ) => "centered4",
				__( "Centered 5", LANGUAGE_ZONE ) => "centered5",
				__( "Centered 6", LANGUAGE_ZONE ) => "centered6",
				__( "Side 1", LANGUAGE_ZONE ) => "side1",
				__( "Side 2", LANGUAGE_ZONE ) => "side2",
				__( "Side 3", LANGUAGE_ZONE ) => "side3",
				__( "Side 4", LANGUAGE_ZONE ) => "side4",
				__( "Side 5", LANGUAGE_ZONE ) => "side5",
				__( "Side 6", LANGUAGE_ZONE ) => "side6",
				__( "Side 7", LANGUAGE_ZONE ) => "side7",
				__( "Boxed 1", LANGUAGE_ZONE ) => "boxed1",
				__( "Boxed 2", LANGUAGE_ZONE ) => "boxed2",
				__( "Boxed 3", LANGUAGE_ZONE ) => "boxed3",
				__( "Boxed 4", LANGUAGE_ZONE ) => "boxed4"
			),
			"std" => "centered1",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Icon Color", LANGUAGE_ZONE),
			"param_name" => "icon_color",
			"value" => array(
				"Default" => "default",
				"Blue" => "blue",
			),
			"std" => "default",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"side1", "side3", "boxed1", "boxed3"
				)
			)
		),
		$content_area,
		$extra_class,
	)
) );
vc_add_params("icon_box", $add_css3_animation);


/* Accordion Shortcode */

vc_add_param("vc_accordion", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Toggle Type", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "toggle_type",
	"value" => array(
		"Accordion" => "accordion",
		"Toggle" => "toggle"
	),
	"std" => "accordion",
	"description" => ""
));

vc_add_param("vc_accordion", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", LANGUAGE_ZONE),
	"admin_label" => true,
	"param_name" => "style",
	"value" => array(
		__( "Style 1", LANGUAGE_ZONE ) => "style1",
		__( "Style 2", LANGUAGE_ZONE ) => "style2",
		__( "Style 3", LANGUAGE_ZONE ) => "style3",
		__( "Style 4", LANGUAGE_ZONE ) => "style4",
		__( "Style 5", LANGUAGE_ZONE ) => "style5",
		__( "Style 6", LANGUAGE_ZONE ) => "style6",
		__( "Faq Style", LANGUAGE_ZONE ) => "faqs",
	),
	"std" => "style1",
	"description" => ""
));

vc_remove_param('vc_accordion', 'title');
vc_remove_param('vc_accordion', 'interval');
vc_remove_param('vc_accordion', 'collapsible');

vc_map_update("vc_accordion", array(
	'is_container' => false,
	'as_parent' => array( 'only' => 'vc_accordion_tab' ),
));

/* Banner Shortcode */
vc_map( array(
	"name" => __("Banner", LANGUAGE_ZONE),
	"base" => "banner",
	"icon" => "banner",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array_merge( $posts_fields, array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', LANGUAGE_ZONE ),
			'param_name' => 'count'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => array(
				__( 'Standard', LANGUAGE_ZONE ) => 'standard',
				__( 'Animated', LANGUAGE_ZONE ) => 'animated'
			),
			'std' => 'animated',
			'admin_label' => true
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', LANGUAGE_ZONE ),
			'param_name' => 'columns',
			'value' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6'
			),
			'std' => '3',
			'admin_label' => true
		),
		$extra_class
	) )
) );

/* Blog Posts Shortcode */
$updated_post_fields = array_slice( $posts_fields, 1 );
$updated_post_fields[2] = array(
	'type' => 'dropdown',
	'heading' => __( 'Order by', LANGUAGE_ZONE ),
	'param_name' => 'orderby',
	'value' => array(
		'',
		__( 'Date', LANGUAGE_ZONE ) => 'date',
		__( 'ID', LANGUAGE_ZONE ) => 'ID',
		__( 'Author', LANGUAGE_ZONE ) => 'author',
		__( 'Title', LANGUAGE_ZONE ) => 'title',
		__( 'Modified', LANGUAGE_ZONE ) => 'modified',
		__( 'Random', LANGUAGE_ZONE ) => 'rand',
		__( 'Comment count', LANGUAGE_ZONE ) => 'comment_count',
		__( 'Menu order', LANGUAGE_ZONE ) => 'menu_order'
	),
	'description' => sprintf( __( 'Select how to sort retrieved posts. More at %s.', LANGUAGE_ZONE ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
	/*'dependency' => array(
		'element' => 'style',
		'value' => array(
			'masonry', 'grid', 'full', 'classic'
		)
	)*/
);
vc_map( array(
	"name" => __("Blog Posts", LANGUAGE_ZONE),
	"base" => "blog_posts",
	"icon" => "blog_posts",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array_merge(
		array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Post Type', LANGUAGE_ZONE ),
				'param_name' => 'post_type',
				'admin_label' => true,
				'value' => array(
					__( 'Post', LANGUAGE_ZONE ) => 'post',
					__( 'Portfolio', LANGUAGE_ZONE ) => 'm_portfolio',
				),
				'std' => 'post'
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Style', LANGUAGE_ZONE ),
				'param_name' => 'style',
				'value' => array(
					__( 'Masonry', LANGUAGE_ZONE ) => 'masonry',
					__( 'Grid', LANGUAGE_ZONE ) => 'grid',
					__( 'Full', LANGUAGE_ZONE ) => 'full',
					__( 'Classic', LANGUAGE_ZONE ) => 'classic',
					__( 'Timeline', LANGUAGE_ZONE ) => 'timeline'
				),
				'std' => 'masonry',
				'dependency' => array(
					'element' => 'post_type',
					'value' => array( 'post' )
				)
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Style', LANGUAGE_ZONE ),
				'param_name' => 'portfolio_style',
				'value' => array(
					__( 'Fancy Grid', LANGUAGE_ZONE ) => 'fancy',
					__( 'Grid', LANGUAGE_ZONE ) => 'grid'
				),
				'std' => 'fancy',
				'dependency' => array(
					'element' => 'post_type',
					'value' => array( 'm_portfolio' )
				)
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Display Mode', LANGUAGE_ZONE ),
				'param_name' => 'display_mode',
				'value' => array(
					__( 'Default', LANGUAGE_ZONE ) => 'default',
					__( 'Carousel', LANGUAGE_ZONE ) => 'carousel'
				),
				'std' => 'default',
				'dependency' => array(
					'element' => 'style',
					'value' => array( 'grid' )
				)
			)
		),
		$updated_post_fields,
		array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Author ID', LANGUAGE_ZONE ),
				'param_name' => 'author_id',
				'description' => __( 'Fill this field with a author ID.', LANGUAGE_ZONE )
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Number of posts to show per page', LANGUAGE_ZONE ),
				'param_name' => 'count'
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Columns', LANGUAGE_ZONE ),
				'param_name' => 'columns',
				'value' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6'
				),
				'std' => '3',
				'dependency' => array(
					'element' => 'style',
					'value' => array( 'masonry', 'grid' )
				)
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Columns', LANGUAGE_ZONE ),
				'param_name' => 'portfolio_columns',
				'value' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6'
				),
				'std' => '3',
				'dependency' => array(
					'element' => 'post_type',
					'value' => array( 'm_portfolio' )
				)
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Pagination', LANGUAGE_ZONE ),
				'param_name' => 'pagination',
				'description' => __( 'Should a pagination be displayed?', LANGUAGE_ZONE ),
				'value' => array(
					__( 'Yes', LANGUAGE_ZONE ) => 'yes',
					__( 'No', LANGUAGE_ZONE ) => 'no'
				),
				'std' => 'no',
				/*'dependency' => array(
					'element' => 'post_type',
					'value' => array( 'post' )
				)*/
			),
			array(
				'type' => 'dropdown',
				'heading' => __( 'Load Style', LANGUAGE_ZONE ),
				'param_name' => 'load_style',
				'value' => array(
					__( 'Default', LANGUAGE_ZONE ) => 'default',
					__( 'Ajax', LANGUAGE_ZONE ) => 'ajax',
					__( 'Load More', LANGUAGE_ZONE ) => 'load_more'
				),
				'std' => 'default',
				'dependency' => array(
					'element' => 'pagination',
					'value' => 'yes'
				)
			),
			$extra_class
		)
	)
) );

/* Posts Slider Shortcode */
vc_map( array(
	"name" => __("Posts Slider", LANGUAGE_ZONE),
	"base" => "post_slider",
	"icon" => "posts_slider",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array_merge( array_slice( $posts_fields, 1 ), array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', LANGUAGE_ZONE ),
			'param_name' => 'count'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => array(
				__( 'Style1', LANGUAGE_ZONE ) => 'style1',
				__( 'Style2', LANGUAGE_ZONE ) => 'style2',
				__( 'Style3', LANGUAGE_ZONE ) => 'style3',
				__( 'Style4', LANGUAGE_ZONE ) => 'style4',
				__( 'Style5', LANGUAGE_ZONE ) => 'style5',
				__( 'Style6', LANGUAGE_ZONE ) => 'style6'
			),
			'std' => 'style1'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Auto play time', LANGUAGE_ZONE ),
			'param_name' => 'autoplay',
			'value' => '5000',
			'description' => __( 'Input the auto play time in milliseconds. If you don\'t want auto play, leave this field blank.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Transition Effect', LANGUAGE_ZONE ),
			'param_name' => 'transition_effect',
			'value' => array(
				__( 'Slide', LANGUAGE_ZONE ) => '',
				__( 'Fade', LANGUAGE_ZONE ) => 'fade'
			),
			'std' => '',
			'description' => __( 'Important! transition works only in modern browsers that support CSS3 translate3d methods and only with single item on screen.', LANGUAGE_ZONE )
		),
		$extra_class
	) )
) );

/* Posts Carousel Shortcode */
vc_map( array(
	"name" => __("Post Carousel", LANGUAGE_ZONE),
	"base" => "post_carousel",
	"icon" => "post_carousel",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array_merge( $posts_fields, array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts to show', LANGUAGE_ZONE ),
			'param_name' => 'count'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'admin_label' => true,
			'value' => array(
				__( 'Style1', LANGUAGE_ZONE ) => 'style1',
				__( 'Style2', LANGUAGE_ZONE ) => 'style2'
			),
			'std' => 'style1'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', LANGUAGE_ZONE ),
			'param_name' => 'columns',
			'value' => array(
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6'
			),
			'std' => '4',
			'description' => __( 'Input the slide count to be displayed in the screen.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'description' => __( 'Title for this slider', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Auto play time', LANGUAGE_ZONE ),
			'param_name' => 'autoplay',
			'value' => '5000',
			'description' => __( 'Input the auto play time in milliseconds. If you don\'t want auto play, leave this field blank.', LANGUAGE_ZONE )
		),
		$extra_class
	) )
) );

/* Logo Slider Shortcode */
vc_map( array(
	"name" => __("Logo Slider", LANGUAGE_ZONE),
	"base" => "logo_slider",
	'is_container' => true,
	"icon" => "logo_slider",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	'as_parent' => array( 'only' => 'logo' ),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', LANGUAGE_ZONE ),
			'param_name' => 'columns',
			'value' => array(
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6'
			),
			'std' => '4',
			'description' => __( 'Input the slide count to be displayed in the screen.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', LANGUAGE_ZONE ),
			'param_name' => 'style',
			'value' => array(
				__( 'Style1', LANGUAGE_ZONE ) => 'style1',
				__( 'Style2', LANGUAGE_ZONE ) => 'style2'
			),
			'std' => 'style1'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Auto play time', LANGUAGE_ZONE ),
			'param_name' => 'autoplay',
			'value' => '5000',
			'description' => __( 'Input the auto play time in milliseconds. If you don\'t want auto play, leave this field blank.', LANGUAGE_ZONE )
		),
		$extra_class
	),
	'default_content' => '[logo src="" alt="" url=""][logo src="" alt="" url=""]',
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => __("Logo", LANGUAGE_ZONE),
	"base" => "logo",
	"icon" => "logo_slider",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"allowed_container_element" => 'logo_slider',
	"content_element" => true,
	"is_container" => false,
	'as_child' => array( 'only' => 'logo_slider' ),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Url', LANGUAGE_ZONE ),
			'param_name' => 'src',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Alt', LANGUAGE_ZONE ),
			'param_name' => 'alt'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Link Url', LANGUAGE_ZONE ),
			'param_name' => 'url',
			'description' => __( 'If you want to open new page when you click this logo, please use this field. Leave it blank if you don\'t want to open new page.', LANGUAGE_ZONE )
		),
		$extra_class
	)
) );

/* Image Container */
vc_map( array(
	"name" => __("Image Container", LANGUAGE_ZONE),
	"base" => "image",
	"icon" => "image_container",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Url', LANGUAGE_ZONE ),
			'param_name' => 'src',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Alt', LANGUAGE_ZONE ),
			'param_name' => 'alt'
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Is Full Width?", LANGUAGE_ZONE),
			"param_name" => "is_fullwidth",
			"value" => array(
				__( "Yes", LANGUAGE_ZONE ) => "yes",
				__( "No", LANGUAGE_ZONE ) => "no"
			),
			"std" => "yes"
		),
		$extra_class
	)
) );
vc_add_params("image", $add_css3_animation);

/* Image Banner */
vc_map( array(
	"name" => __("Image Banner", LANGUAGE_ZONE),
	"base" => "image_banner",
	"icon" => "image_banner",
	"class" => "",
	/*"is_container" => true,*/
	'content_element' => true,
	'as_parent' => array( 'only' => 'banner_caption, vc_column_text, vc_container, vc_row, image' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Background Image Url', LANGUAGE_ZONE ),
			'param_name' => 'bg_img'
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Color', LANGUAGE_ZONE ),
			'param_name' => 'bg_color'
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
	/*'custom_markup' => '%content%'*/
) );
vc_add_params('image_banner', $add_css3_animation);

vc_map( array(
	"name" => __("Image Banner Caption", LANGUAGE_ZONE),
	"base" => "banner_caption",
	"icon" => "image_banner",
	"class" => "",
	"is_container" => true,
	"as_child" => array( 'only' => 'image_banner' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Position', LANGUAGE_ZONE ),
			'param_name' => 'position',
			'value' => array(
				__( 'Left', LANGUAGE_ZONE ) => 'left',
				__( 'Right', LANGUAGE_ZONE ) => 'right',
				__( 'Middle', LANGUAGE_ZONE ) => 'middle',
				__( 'Full', LANGUAGE_ZONE ) => 'full'
			),
			'std' => 'middle'
		),
		$content_area,
		$extra_class
	)
) );

/* Image Gallery */
vc_map( array(
	"name" => __("Image Gallery", LANGUAGE_ZONE),
	"base" => "image_gallery",
	"icon" => "image_gallery",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'attach_images',
			'heading' => __( 'Images', LANGUAGE_ZONE ),
			'param_name' => 'ids',
			'value' => '',
			'description' => __( 'Select images from media library.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Mode', LANGUAGE_ZONE ),
			'param_name' => 'mode',
			'value' => array(
				__( 'Simple slider', LANGUAGE_ZONE ) => 'slider',
				__( 'Gallery 1', LANGUAGE_ZONE ) => 'gallery1',
				__( 'Gallery 2', LANGUAGE_ZONE ) => 'gallery2',
				__( 'Frame slider', LANGUAGE_ZONE ) => 'frame',
				__( 'Metro 1', LANGUAGE_ZONE ) => 'metro1',
				__( 'Metro 2', LANGUAGE_ZONE ) => 'metro2',
				__( 'Carousel', LANGUAGE_ZONE ) => 'carousel'
			),
			'std' => 'gallery1',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Auto play time', LANGUAGE_ZONE ),
			'param_name' => 'autoplay',
			'value' => '5000',
			'description' => __( 'Input the auto play time in milliseconds. If you don\'t want auto play, leave this field blank.', LANGUAGE_ZONE ),
			'dependency' => array(
				"element" => "mode",
				"value" => array(
					"slider", "gallery1", "gallery2"
				)
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', LANGUAGE_ZONE ),
			'param_name' => 'columns',
			'value' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5'
			),
			'std' => '3',
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"slider", "metro1", "metro2"
				)
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Thumbnails have full width?", LANGUAGE_ZONE),
			"param_name" => "is_thumb_full",
			"value" => array(
				"" => "false"
			),
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"gallery2"
				)
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Front Width', LANGUAGE_ZONE ),
			'param_name' => 'front_width',
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"carousel"
				)
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Front Height', LANGUAGE_ZONE ),
			'param_name' => 'front_height',
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"carousel"
				)
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slide Count', LANGUAGE_ZONE ),
			'param_name' => 'slide_count',
			'value' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10'
			),
			'std' => '5',
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"carousel"
				)
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'HAlign', LANGUAGE_ZONE ),
			'param_name' => 'halign',
			'value' => array(
				__( 'Left', LANGUAGE_ZONE ) => 'left',
				__( 'Right', LANGUAGE_ZONE ) => 'right',
				__( 'Center', LANGUAGE_ZONE ) => 'center'
			),
			'std' => 'center',
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"carousel"
				)
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'VAlign', LANGUAGE_ZONE ),
			'param_name' => 'valign',
			'value' => array(
				__( 'Top', LANGUAGE_ZONE ) => 'top',
				__( 'Bottom', LANGUAGE_ZONE ) => 'bottom',
				__( 'Center', LANGUAGE_ZONE ) => 'center'
			),
			'std' => 'center',
			"dependency" => array(
				"element" => "mode",
				"value" => array(
					"carousel"
				)
			)
		),
		$extra_class
	)
) );

/* Image Parallax */
vc_map( array(
	"name" => __("Image Parallax", LANGUAGE_ZONE),
	"base" => "image_parallax",
	"icon" => "image_parallax",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Url', LANGUAGE_ZONE ),
			'param_name' => 'src'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax Ratio (0 ~ 1)', LANGUAGE_ZONE ),
			'param_name' => 'ratio'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Height (in px)', LANGUAGE_ZONE ),
			'param_name' => 'height'
		),
		$extra_class
	),
	'js_view'		 => 'VcColumnView'
) );

/* Video Parallax */
vc_map( array(
	"name" => __("Video Parallax", LANGUAGE_ZONE),
	"base" => "video_parallax",
	"icon" => "video_parallax",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Video Url', LANGUAGE_ZONE ),
			'param_name' => 'src',
			'description' => __( 'You can input youtube, vimeo or mp4 file url.', LANGUAGE_ZONE )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax Ratio (0 ~ 1)', LANGUAGE_ZONE ),
			'param_name' => 'ratio',
			'value' => '0.5'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Video Ratio', LANGUAGE_ZONE ),
			'param_name' => 'video_ratio',
			'value' => array(
				'16:9' => '16:9',
				'4:3' => '4:3',
				'5:4' => '5:4',
				'5:3' => '5:3'
			),
			'std' => '16:9'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Poster Image Url', LANGUAGE_ZONE ),
			'param_name' => 'poster'
		),
		$extra_class
	),
	'js_view'		 => 'VcColumnView'
) );

vc_map( array(
	"name" => __("Video Parallax Caption", LANGUAGE_ZONE),
	"base" => "video_caption",
	"icon" => "video_parallax",
	"class" => "",
	/*"is_container" => true,*/
	"as_child" => array( 'only' => 'video_parallax' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		$content_area,
		$extra_class
	)
) );

/* Tabs */
vc_remove_param("vc_tabs", "interval");
vc_add_param("vc_tabs", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", LANGUAGE_ZONE),
	"param_name" => "style",
	"value" => array(
		__( "Style 1", LANGUAGE_ZONE ) => "style1",
		__( "Style 2", LANGUAGE_ZONE ) => "style2",
		__( "Vertical 1", LANGUAGE_ZONE ) => "vertical-tab",
		__( "Vertical 2", LANGUAGE_ZONE ) => "vertical-tab-1",
		__( "Transparent", LANGUAGE_ZONE ) => "transparent-tab"
	),
	"std" => 'style1',
	"description" => ""
));

vc_add_param("vc_tabs", array(
	'type' => 'textfield',
	'heading' => __( 'Active Tab Index', LANGUAGE_ZONE ),
	'param_name' => 'active_tab_index',
	'value' => '1'
));

vc_add_param("vc_tabs", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Tabs have full width?", LANGUAGE_ZONE),
	"param_name" => "has_full_width",
	"value" => array(
		"" => "true"
	),
	'dependency' => array(
		'element' => 'style',
		'value' => array( 'style1', 'style2', 'transparent-tab' )
	)
));

vc_add_param("vc_tabs", array(
	'type' => 'attach_image',
	'heading' => 'Attach Image',
	'param_name' => 'img_id',
	'dependency' => array(
		'element' => 'style',
		'value' => 'transparent-tab'
	)
));

vc_map_update("vc_tabs", array(
	'is_container' => false,
	'as_parent' => array( 'only' => 'vc_tab' ),
));

vc_add_param("vc_tab", array(
	'type' => 'textfield',
	'heading' => __( 'Icon Class', LANGUAGE_ZONE ),
	'param_name' => 'icon_class',
	'description' => 'f.e: fa fa-coffee'
));

/* Team Member */
vc_map( array(
	"name" => __("Team Member", LANGUAGE_ZONE),
	"base" => "team_member",
	"icon" => "team_member",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				__( "Default", LANGUAGE_ZONE ) => "default",
				__( "Colored", LANGUAGE_ZONE ) => "colored",
			),
			"std" => 'default',
			"description" => ""
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Name', LANGUAGE_ZONE ),
			'param_name' => 'name',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Job', LANGUAGE_ZONE ),
			'param_name' => 'job'
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'Description', LANGUAGE_ZONE ),
			'param_name' => 'desc'
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Photo', LANGUAGE_ZONE ),
			'param_name' => 'photo_id'
		),
		array(
			"type" => "textarea_html",
			"heading" => __( "Social Links", LANGUAGE_ZONE ),
			"param_name" => "content",
			"description" => __( "Insert social links.", LANGUAGE_ZONE ),
			'value' => '[social_links style="style1" size="small"][social_link icon_class="" link=""][/social_links]'
		)
	)
) );
vc_add_params("team_member", $add_css3_animation);

/* Testimonial */
vc_map( array(
	"name" => __("Testimonials", LANGUAGE_ZONE),
	"base" => "testimonials",
	"icon" => "testimonials",
	"class" => "",
	/*"is_container" => true,*/
	"as_parent" => array( 'only' => 'testimonial' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', LANGUAGE_ZONE ),
			'param_name' => 'title',
			'admin_label' => true
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2",
				__( "Style 3", LANGUAGE_ZONE ) => "style3",
				__( "Style 4", LANGUAGE_ZONE ) => "style4"
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', LANGUAGE_ZONE ),
			'param_name' => 'columns',
			'value' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4'
			),
			'std' => '1',
			'dependency' => array(
				'element' => 'style',
				'value' => array( 'style1' )
			)
		),
		/*array(
			'type' => 'textfield',
			'heading' => __( 'Author Image Size', LANGUAGE_ZONE ),
			'param_name' => 'author_img_size',
			'value' => '90'
		),*/
		$extra_class
	),
	'js_view' => 'VcColumnView',
	'default_content' => '[testimonial][/testimonial]'
) );

vc_map( array(
	"name" => __("Testimonial", LANGUAGE_ZONE),
	"base" => "testimonial",
	"icon" => "testimonial",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Name', LANGUAGE_ZONE ),
			'param_name' => 'author_name',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Job', LANGUAGE_ZONE ),
			'param_name' => 'author_job',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Link', LANGUAGE_ZONE ),
			'param_name' => 'author_link'
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Photo of Author', LANGUAGE_ZONE ),
			'param_name' => 'author_img_id'
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Font Size', LANGUAGE_ZONE ),
			'param_name' => 'font_size',
			'value' => array(
				__( 'Normal', LANGUAGE_ZONE ) => 'normal',
				__( 'Large', LANGUAGE_ZONE ) => 'large'
			),
			'std' => 'normal'
		),
		$content_area,
		$extra_class
	)
) );

/* Alert Box */
vc_map( array(
	"name" => __("Alert Box", LANGUAGE_ZONE),
	"base" => "alert",
	"icon" => "icon-wpb-information-white",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', LANGUAGE_ZONE ),
			'param_name' => 'type',
			'value' => array(
				__( 'General', LANGUAGE_ZONE ) => 'general',
				__( 'Notice', LANGUAGE_ZONE ) => 'notice',
				__( 'Success', LANGUAGE_ZONE ) => 'success',
				__( 'Error', LANGUAGE_ZONE ) => 'error',
				__( 'Help', LANGUAGE_ZONE ) => 'help'
			),
			'admin_label' => true,
			'std' => 'general'
		),
		$content_area,
		$extra_class
	)
) );

/* Button */
vc_map( array(
	"name" => __("Button", LANGUAGE_ZONE),
	"base" => "button",
	"icon" => "button",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Link", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "href",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2",
				__( "Style 3", LANGUAGE_ZONE ) => "style3",
				__( "Style 4", LANGUAGE_ZONE ) => "style4"
			),
			"std" => 'style4',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Size", LANGUAGE_ZONE),
			"param_name" => "size",
			"value" => array(
				__( "Small", LANGUAGE_ZONE ) => "sm",
				__( "Medium", LANGUAGE_ZONE ) => "md",
				__( "Large", LANGUAGE_ZONE ) => "lg",
				__( "Extra Large", LANGUAGE_ZONE ) => "xl"
			),
			"std" => 'md',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Target", LANGUAGE_ZONE),
			"param_name" => "target",
			"value" => array(
				"_self" => "",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '',
			"description" => ""
		),
		$content_area,
		$extra_class
	)
) );

/* Call out Box */
vc_map( array(
	"name" => __("Call to Action", LANGUAGE_ZONE),
	"base" => "callout_box",
	"icon" => "call_to_action",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2",
				__( "Style 3", LANGUAGE_ZONE ) => "style3",
				__( "Style 4", LANGUAGE_ZONE ) => "style4",
				__( "Style 5", LANGUAGE_ZONE ) => "style5"
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array( "style1", "style2", "style3", "style4" )
			),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Message", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "message",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array( "style1" )
			),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button1 Text", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "button1_text",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array( "style1", "style2", "style3", "style4" )
			),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button1 Link", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "button1_href",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array( "style1", "style2", "style3", "style4" )
			),
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Button1 Target", LANGUAGE_ZONE),
			"param_name" => "button1_target",
			"value" => array(
				"_self" => "",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '',
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array( "style1", "style2", "style3", "style4" )
			),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button2 Text", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "button2_text",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4"
				)
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button2 Link", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "button2_href",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4"
				)
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Button2 Target", LANGUAGE_ZONE),
			"param_name" => "button2_target",
			"value" => array(
				"_self" => "",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '',
			"description" => "",
			/*"dependency" => array(
				"element" => "button1_text",
				"not_empty" => true
			)*/
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4"
				)
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Image Position", LANGUAGE_ZONE),
			"param_name" => "img_position",
			"value" => array(
				__( "Left", LANGUAGE_ZONE ) => "left",
				__( "Right", LANGUAGE_ZONE ) => "right"
			),
			"std" => 'left',
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4", "style5"
				)
			)
		),
		array(
			"type" => "attach_image",
			"heading" => __( 'Image 1', LANGUAGE_ZONE ),
			"param_name" => 'img_id',
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4", "style5"
				)
			)
		),
		array(
			"type" => "attach_image",
			"heading" => __( 'Image 2', LANGUAGE_ZONE ),
			"param_name" => 'img2_id',
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style4", "style5"
				)
			)
		),
		array(
			//"type" => "colorpicker",
			"type" => "textfield",
			"heading" => __( 'Background Color', LANGUAGE_ZONE ),
			"param_name" => 'bgcolor',
			"std" => "none",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1"
				)
			)
		),
		$content_area + array("dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4", "style5"
				)
			)),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Image Animation Type", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "img_animation_type",
			"value" => "",
			"description" => "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see framework/assets/css/animate.css for the animation types. <br />Note: Works only in modern browsers.",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4", "style5"
				)
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Image Animation Delay", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "img_animation_delay",
			"value" => "0",
			"description" => "Input the delay of animation in seconds.",
			"dependency" => array(
				"element" => "img_animation_type",
				"not_empty" => true
			),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Content Animation Type", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "content_animation_type",
			"value" => "",
			"description" => "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see framework/assets/css/animate.css for the animation types. <br />Note: Works only in modern browsers.",
			"dependency" => array(
				"element" => "style",
				"value" => array(
					"style1", "style4", "style5"
				)
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Content Animation Delay", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "content_animation_delay",
			"value" => "0",
			"description" => "Input the delay of animation in seconds.",
			"dependency" => array(
				"element" => "content_animation_type",
				"not_empty" => true
			)
		),
		$extra_class
	)
) );

/* Counter */
vc_map( array(
	"name" => __("Counter Box", LANGUAGE_ZONE),
	"base" => "counter",
	"icon" => "counter_box",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2"
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Label", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "label",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "number",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => __("Image Url", LANGUAGE_ZONE),
			"param_name" => "img_id",
			"value" => "",
			"description" => "If you want to display image on the counter, please use this field.",
			/*"dependency" => array(
				"element" => "style",
				"value" => "style1"
			)*/
		),
		$extra_class
	)
) );

/* Image Box */
vc_map( array(
	"name" => __("Image Box", LANGUAGE_ZONE),
	"base" => "image_box",
	"icon" => "image_box",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => __("Image Url", LANGUAGE_ZONE),
			"param_name" => "img_id",
			"value" => ""
		),
		$content_area,
		$extra_class
	)
) );
vc_add_params("image_box", $add_css3_animation);

/* Infographic Pie */
vc_map( array(
	"name" => __("Infographic Pie", LANGUAGE_ZONE),
	"base" => "infographic_pie",
	"icon" => "infographic_pie",
	"class" => "",
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Description", LANGUAGE_ZONE),
			"param_name" => "desc",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2",
				__( "Style 3", LANGUAGE_ZONE ) => "style3"
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Border Background Color', LANGUAGE_ZONE ),
			"param_name" => 'bgcolor',
			"value" => "#edf6ff"
		),
		array(
			"type" => "colorpicker",
			"heading" => __( 'Border Foreground Color', LANGUAGE_ZONE ),
			"param_name" => 'fgcolor',
			"value" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Percentage (0 ~ 100)", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "percent",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textarea_raw_html",
			"class" => "",
			"heading" => __("Percent Text", LANGUAGE_ZONE),
			"admin_label" => false,
			"param_name" => "percent_text",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Dimension", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "dimension",
			"value" => "",
			"description" => __( "The diameter of the circle in px.", LANGUAGE_ZONE )
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Border Width", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "bordersize",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Font Size", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "fontsize",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Font Color", LANGUAGE_ZONE),
			"param_name" => "fontcolor",
			"value" => array(
				__( "Default", LANGUAGE_ZONE ) => "default",
				__( "Blue", LANGUAGE_ZONE ) => "blue"
			),
			"std" => 'default',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Border Style", LANGUAGE_ZONE),
			"param_name" => "borderstyle",
			"value" => array(
				__( "Default", LANGUAGE_ZONE ) => "default",
				__( "Outline", LANGUAGE_ZONE ) => "outline"
			),
			"std" => 'default',
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Filled Border Width", LANGUAGE_ZONE),
			"param_name" => "fill_borderwidth"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Start Degree (-360 ~ 360)", LANGUAGE_ZONE),
			"param_name" => "startdegree",
			"std" => "0"
		),
		$extra_class
	)
) );

/* Progress Bar */
vc_map( array(
	"name" => __("Progress Bars", LANGUAGE_ZONE),
	"base" => "progress_bars",
	"icon" => "icon-wpb-graph",
	"class" => "",
	/*"is_container" => true,*/
	"as_parent" => array( 'only' => 'progress_bar' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				__( "Default", LANGUAGE_ZONE ) => "default",
				__( "Skill Meter Bar", LANGUAGE_ZONE ) => "skill-meter",
				__( "Icons Bar", LANGUAGE_ZONE ) => "icons",
				__( "Colored Bar", LANGUAGE_ZONE ) => "colored",
				__( "Vertical Bar", LANGUAGE_ZONE ) => "vertical"
			),
			"std" => 'default',
			"description" => ""
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
	'default_content' => '[progress_bar][progress_bar]'
) );

vc_map( array(
	"name" => __("Progress Bar", LANGUAGE_ZONE),
	"base" => "progress_bar",
	"icon" => "icon-wpb-graph",
	"class" => "",
	"as_child" => array( 'only' => 'progress_bars' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Label", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "label",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Percent(0 ~ 100)", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "percent",
			"value" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Class for icon", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "icon_class",
			'description' => 'f.e: fa fa-coffee'
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Total numbers", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "total_numbers",
			"value" => "",
			"description" => __( "Input the total number of icons. This is useful for only Icons Bar style.", LANGUAGE_ZONE ),
			"dependency" => array(
				"element" => "style",
				"value" => "icons"
			),
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Active numbers", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "active_numbers",
			"value" => "",
			"description" => __( "Input the active number of icons. This is useful for only Icons Bar style.", LANGUAGE_ZONE ),
			"dependency" => array(
				"element" => "style",
				"value" => "icons"
			),
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Color Style", LANGUAGE_ZONE),
			"param_name" => "color_style",
			"value" => array(
				__( "Default", LANGUAGE_ZONE ) => "default",
				__( "Blue", LANGUAGE_ZONE ) => "blue"
			),
			"std" => 'default',
			"description" => ""
		),
		array(
			"type" => "colorpicker",
			"heading" => __( "Bar Color", LANGUAGE_ZONE ),
			"param_name" => "bar_color_code",
			"value" => "#0ab596"
		),
		array(
			"type" => "dropdown",
			"heading" => __( "Has Animate?", LANGUAGE_ZONE ),
			"param_name" => "has_animate",
			"admin_label" => true,
			"value" => array(
				__( "Yes", LANGUAGE_ZONE ) => "yes",
				__( "No", LANGUAGE_ZONE ) => "no"
			),
			"std" => "yes"
		),
		$extra_class
	)
) );

/* Process */
vc_map( array(
	"name" => __("Process", LANGUAGE_ZONE),
	"base" => "process",
	"icon" => "process",
	"class" => "",
	"is_container" => true,
	"as_parent" => array( 'only' => 'process_item' ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				__( "Simple", LANGUAGE_ZONE ) => "simple",
				__( "Creative", LANGUAGE_ZONE ) => "creative"
			),
			"std" => 'simple',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => __( "Has Animate?", LANGUAGE_ZONE ),
			"param_name" => "has_animate",
			"admin_label" => true,
			"value" => array(
				__( "Yes", LANGUAGE_ZONE ) => "yes",
				__( "No", LANGUAGE_ZONE ) => "no"
			),
			"std" => "yes"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Type", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "animation_type",
			"value" => "fadeInLeft",
			"description" => __( "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see framework/assets/css/animate.css for the animation types. <br />Note: Works only in modern browsers.", LANGUAGE_ZONE ),
			"dependency" => array(
				"element" => "has_animate",
				"value" => "yes"
			)
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
	'default_content' => '[process_item is_asset="no"][process_item is_asset="no"]'
) );

vc_map( array(
	"name" => __("Process Item", LANGUAGE_ZONE),
	"base" => "process_item",
	"icon" => "process",
	"class" => "",
	"as_child" => array( "only" => "process" ),
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "title",
			"description" => ""
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'Description', LANGUAGE_ZONE ),
			'param_name' => 'desc'
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Class for icon", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "icon_class",
			'description' => 'f.e: fa fa-coffee<br/>Note: This field is useful for only simple style.'
		),
		array(
			'type' => 'attach_image',
			'heading' => 'Image',
			'param_name' => 'img_id',
			'description' => __( 'Note: This field is useful for only creative style.', LANGUAGE_ZONE )
		),
		array(
			"type" => "dropdown",
			"heading" => __( "Is Active Item?", LANGUAGE_ZONE ),
			"param_name" => "is_active",
			"admin_label" => true,
			"value" => array(
				__( "Yes", LANGUAGE_ZONE ) => "yes",
				__( "No", LANGUAGE_ZONE ) => "no"
			),
			"std" => "no"
		),
		array(
			"type" => "dropdown",
			"heading" => __( "Is Asset Item?", LANGUAGE_ZONE ),
			"param_name" => "is_asset",
			"admin_label" => true,
			"value" => array(
				__( "Yes", LANGUAGE_ZONE ) => "yes",
				__( "No", LANGUAGE_ZONE ) => "no"
			),
			"std" => "no"
		),
	)
) );

/* Pricing Table */
vc_map( array(
	"name" => __("Pricing Table Container", LANGUAGE_ZONE),
	"base" => "pricing_table_container",
	"icon" => "pricing_table_container",
	"class" => "",
	"as_parent" => array( "only" => "pricing_table" ),
	"is_container" => true,
	/*'show_settings_on_create' => false,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Columns', LANGUAGE_ZONE ),
			'param_name' => 'columns',
			'value' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'6' => '6'
			),
			'std' => '4',
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
	'default_content' => '[pricing_table][pricing_table]'
) );

vc_map( array(
	"name" => __("Pricing Table", LANGUAGE_ZONE),
	"base" => "pricing_table",
	"icon" => "pricing_table",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				__( "Style 1", LANGUAGE_ZONE ) => "style1",
				__( "Style 2", LANGUAGE_ZONE ) => "style2"
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Active", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "active",
			"value" => array(
				__( "Yes", LANGUAGE_ZONE ) => "true",
				__( "No", LANGUAGE_ZONE ) => "false"
			),
			"std" => 'false',
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Currency Symbol", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "currency_symbol",
			"value" => "$"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Price", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "price"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Interval", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "unit_text",
			"value" => __( "Per Month", LANGUAGE_ZONE )
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Pricing Table Name", LANGUAGE_ZONE),
			"admin_label" => true,
			"param_name" => "pricing_type"
		),
		array(
			"type" => "textarea",
			"heading" => __("Description", LANGUAGE_ZONE),
			"param_name" => "desc",
			"dependency" => array(
				"element" => "style",
				"value" => "style2"
			)
		),
		$content_area,
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button title", LANGUAGE_ZONE),
			"param_name" => "btn_title"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button Url", LANGUAGE_ZONE),
			"param_name" => "btn_url"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Target", LANGUAGE_ZONE),
			"param_name" => "btn_target",
			"value" => array(
				"_self" => "",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '',
			"description" => ""
		),
		$extra_class
	)
) );
vc_add_params("pricing_table", $add_css3_animation);


/* Section */
vc_map( array(
	"name" => __("Section Title", LANGUAGE_ZONE),
	"base" => "section_with_title",
	"icon" => "",
	"class" => "",
	'content_element' => true,
	"category" => __('by SoapTheme', LANGUAGE_ZONE),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", LANGUAGE_ZONE),
			"param_name" => "title",
			"admin_label" => true
		),
		$extra_class
	)
) );



if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Vc_Container extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Pricing_Table_Container extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Image_Banner extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Logo_Slider extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Process extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Progress_Bars extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Testimonials extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Video_Parallax extends WPBakeryShortCodesContainer {}

	class WPBakeryShortCode_Image_Parallax extends WPBakeryShortCodesContainer {}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Logo extends WPBakeryShortCode {}

	class WPBakeryShortCode_Banner_Caption extends WPBakeryShortCode {}

	class WPBakeryShortCode_Video_Caption extends WPBakeryShortCode {}

	class WPBakeryShortCode_Progress_Bar extends WPBakeryShortCode {}

	class WPBakeryShortCode_Process_Item extends WPBakeryShortCode {}
}