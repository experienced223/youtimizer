<?php
/**
 * Defines an array of options used in ReduxFramework
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Redux' ) ) {
    return;
}

require_once "default-options.php";

$opt_name = "miracle_options";

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => $theme->get( 'Name' ),
    // Name that appears at the top of your panel
    'display_version'      => $theme->get( 'Version' ),
    // Version that appears at the top of your panel
    'menu_type'            => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => __( 'Theme Options', LANGUAGE_ZONE ),
    'page_title'           => __( 'Theme Options', LANGUAGE_ZONE ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => false,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    // Show the time the page took to load, etc
    'update_notice'        => false,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => false,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => '',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'miracle_options',
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'system_info'          => false,
    // REMOVE

    'compiler'             => true,

    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

$args['admin_bar_links'][] = array(
    'id'    => 'miracle-docs',
    'href'  => 'http://www.soaptheme.net/wordpress/miracle/documentation',
    'title' => __( 'Documentation', LANGUAGE_ZONE ),
);

$args['admin_bar_links'][] = array(
    'id'    => 'miracle-support',
    'href'  => 'http://www.soaptheme.net/',
    'title' => __( 'Support', LANGUAGE_ZONE ),
);

$args['share_icons'][] = array(
    'url'   => 'http://twitter.com/soaptheme',
    'title' => 'Follow us on Twitter',
    'icon'  => 'el el-twitter'
);

Redux::setArgs( $opt_name, $args );


Redux::setSection( $opt_name, array(
    'title' => __( 'Color Skin', LANGUAGE_ZONE ),
    'icon'  => 'el el-brush',
    'fields'     => array(
    	array(
            'title'    => __( 'Color Skins', LANGUAGE_ZONE ),
            'subtitle' => __( 'Please choose a predefined color skin.', LANGUAGE_ZONE ),
            'id'       => 'color_skins',
            'type' => 'image_select',
			'options' => array(
				'default' => array(
					'alt' => __( 'Default Style', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/default.jpg'
				),
				'blue' => array(
					'alt' => __( 'Blue color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/blue.jpg'
				),
				'gold' => array(
					'alt' => __( 'Gold color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/gold.jpg'
				),
				'gray' => array(
					'alt' => __( 'Gray color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/gray.jpg'
				),
				'green' => array(
					'alt' => __( 'Green color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/green.jpg'
				),
				'navy' => array(
					'alt' => __( 'Navy color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/navy.jpg'
				),
				'orange' => array(
					'alt' => __( 'Orange color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/orange.jpg'
				),
				'purple' => array(
					'alt' => __( 'Purple color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/purple.jpg'
				),
				'red' => array(
					'alt' => __( 'Red color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/red.jpg'
				),
				'sea' => array(
					'alt' => __( 'Sea color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/sea.jpg'
				),
				'yellow' => array(
					'alt' => __( 'Yellow color', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/skin/yellow.jpg'
				),
			),
			'default' => 'default',
			'compiler' => true,
			'class' => 'disable_empty_field'
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'General', LANGUAGE_ZONE ),
    'icon'  => 'el el-home'
) );
Redux::setSection( $opt_name, array(
    'title' => __( 'Site Layout', LANGUAGE_ZONE ),
    'subsection' => true,
    'fields'     => array(
    	array(
            'id'       => 'general_site_max_width',
            'type'     => 'text',
            'title'    => __( 'Site Max Width(px)', LANGUAGE_ZONE ),
            'default'  => '1170',
            'compiler' => true
        ),
        array(
			'title' => __( 'Page Load Progress Bar', LANGUAGE_ZONE ),
			'subtitle' => __( 'Enable page load progress bar while page loading.', LANGUAGE_ZONE ),
			'id' => 'general_pace_loading',
			'default' => false,
			'type' => 'switch',
			'on'       => 'Enabled',
            'off'      => 'Disabled'
		)
    )
) );

$skin_variables = miracle_options_predefined_skin_variables();
Redux::setSection( $opt_name, array(
	'title' => __( 'Colors', LANGUAGE_ZONE ),
	'subsection' => true,
	'desc' => __( 'By changing colors in the below section, you can simply create new color skins.', LANGUAGE_ZONE ),
	'fields' => array(
		array(
			'title' => __( 'Primary Color', LANGUAGE_ZONE ),
			'id' => 'general_primary_color',
			'type' => 'color',
			'default' => '#939faa',
			'compiler' => true
		),
		array(
			'title' => __( 'Primary Light Color', LANGUAGE_ZONE ),
			'id' => 'general_primary_light_color',
			'type' => 'color',
			'default' => '#b6c0c9',
			'compiler' => true
		),
		array(
			'title' => __( 'Heading Color', LANGUAGE_ZONE ),
			'id' => 'general_heading_color',
			'subtitle' => __( 'The color of heading tags (h1,...,h6)', LANGUAGE_ZONE ),
			'type' => 'color',
			'default' => '#1b4268',
			'compiler' => true
		),
		array(
			'title' => __( 'Secondary Color', LANGUAGE_ZONE ),
			'id' => 'general_secondary_color',
			'type' => 'color',
			'default' => '#d4dde5',
			'compiler' => true
		),
		array(
			'title' => __( 'Main Background Color', LANGUAGE_ZONE ),
			'id' => 'general_mainbg_color',
			'type' => 'color',
			'default' => '#edf6ff',
			'compiler' => true
		),
		array(
			'title' => __( 'Skin Color', LANGUAGE_ZONE ),
			'id' => 'general_skin_color',
			'type' => 'color',
			'default' => $skin_variables['default']['general_skin_color'],
			'compiler' => true
		),
		array(
			'title' => __( 'Skin Light Background Color', LANGUAGE_ZONE ),
			'id' => 'general_skin_light_bgcolor',
			'type' => 'color',
			'default' => $skin_variables['default']['general_skin_light_bgcolor'],
			'compiler' => true
		),
		array(
			'title' => __( 'Skin Light Font Color', LANGUAGE_ZONE ),
			'id' => 'general_skin_light_font_color',
			'type' => 'color',
			'default' => $skin_variables['default']['general_skin_light_font_color'],
			'compiler' => true
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Background', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
            'id'       => 'general_background',
            'type'     => 'background',
            'output'   => array( 'body' ),
            'title'    => __( 'Body Background', LANGUAGE_ZONE ),
            'subtitle' => __( 'Body background with image, color, etc.', LANGUAGE_ZONE ),
            'default'  => array(
		        'background-color' => '#ffffff',
		    ),
		    'class'    => 'retain_empty_field'
        ),
	)
) );

Redux::setSection( $opt_name, array(
	'title' => __( 'Custom', LANGUAGE_ZONE ),
	'icon' => 'el el-edit',
	'desc' => __( 'Quickly add custom CSS or JavaScript to your site without any complicated setups.', LANGUAGE_ZONE ),
	'fields' => array(
		array(
			'title' => __( 'Custom CSS', LANGUAGE_ZONE ),
			'id' => 'custom_css',
			'type' => 'ace_editor',
			'mode' => 'css',
			'theme' => 'chrome'
		),
		array(
			'title' => __( 'Custom Javascript', LANGUAGE_ZONE ),
			'id' => 'custom_javascript',
			'type' => 'ace_editor',
			'mode' => 'javascript',
			'theme' => 'chrome'
		)
	)
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Typography', LANGUAGE_ZONE ),
    'icon'  => 'el el-font'
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Basic Font', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Main Site Font', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the site font properties', LANGUAGE_ZONE ),
			'id' => 'typography_basic_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => false,
			'subsets' => false,
			'text-align' => false,
			'color' => false,
			'default' => array(
				'font-family' => 'Open Sans',
				'font-size' => '12px',
				'line-height' => '18px'
			),
			'compiler' => true,
			'font_family_clear' => false
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Heading Font', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'H1', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the h1 font properties', LANGUAGE_ZONE ),
			'id' => 'typography_h1_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => true,
			'subsets' => false,
			'text-align' => false,
			'line-height' => false,
			'color' => false,
			'unit' => 'em',
			'default' => array(
				'font-family' => 'Open Sans',
				'font-weight' => '300',
				'font-size' => '2.5em'
			),
			'output'   => array( 'h1' )
		),
		array(
			'title' => __( 'H2', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the h2 font properties', LANGUAGE_ZONE ),
			'id' => 'typography_h2_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => true,
			'subsets' => false,
			'text-align' => false,
			'line-height' => false,
			'color' => false,
			'unit' => 'em',
			'default' => array(
				'font-family' => 'Open Sans',
				'font-weight' => '300',
				'font-size' => '2em'
			),
			'output'   => array( 'h2' )
		),
		array(
			'title' => __( 'H3', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the h3 font properties', LANGUAGE_ZONE ),
			'id' => 'typography_h3_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => true,
			'subsets' => false,
			'text-align' => false,
			'line-height' => false,
			'color' => false,
			'unit' => 'em',
			'default' => array(
				'font-family' => 'Open Sans',
				'font-weight' => '300',
				'font-size' => '1.6667em'
			),
			'output'   => array( 'h3' )
		),
		array(
			'title' => __( 'H4', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the h4 font properties', LANGUAGE_ZONE ),
			'id' => 'typography_h4_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => true,
			'subsets' => false,
			'text-align' => false,
			'line-height' => false,
			'color' => false,
			'unit' => 'em',
			'default' => array(
				'font-family' => 'Open Sans',
				'font-weight' => '400',
				'font-size' => '1.3333em'
			),
			'output'   => array( 'h4' )
		),
		array(
			'title' => __( 'H5', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the h5 font properties', LANGUAGE_ZONE ),
			'id' => 'typography_h5_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => true,
			'subsets' => false,
			'text-align' => false,
			'line-height' => false,
			'color' => false,
			'unit' => 'em',
			'default' => array(
				'font-family' => 'Open Sans',
				'font-weight' => '400',
				'font-size' => '1.1666em'
			),
			'output'   => array( 'h5' )
		),
		array(
			'title' => __( 'H6', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the h6 font properties', LANGUAGE_ZONE ),
			'id' => 'typography_h6_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => true,
			'subsets' => false,
			'text-align' => false,
			'line-height' => false,
			'color' => false,
			'unit' => 'em',
			'default' => array(
				'font-family' => 'Open Sans',
				'font-weight' => '400',
				'font-size' => '1.0833em'
			),
			'output'   => array( 'h6' )
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Paragraph Font', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Paragraph Font', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the paragraph font properties', LANGUAGE_ZONE ),
			'id' => 'typography_paragraph_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'font-weight' => false,
			'subsets' => false,
			'text-align' => false,
			'color' => false,
			'default' => array(
				'font-family' => 'Open Sans',
				'font-size' => '13px',
				'line-height' => '23.4px'
			),
			'compiler' => true
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Custom Font', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Caption Font Family 1', LANGUAGE_ZONE ),
			'subtitle' => __( 'This font is used in captions for banner slider and image banner, curreny symbol of pricing table, counter box, textimonial and logo slider.', LANGUAGE_ZONE ),
			'id' => 'typography_caption_font_family1',
			'type' => 'typography',
			'font-style' => false,
			'font-weight' => false,
			'subsets' => false,
			'text-align' => false,
			'font-size' => false,
			'line-height' => false,
			'color' => false,
			'default' => array(
				'font-family' => 'Playfair Display'
			),
			'compiler' => true,
			'font_family_clear' => false
		),
		array(
			'title' => __( 'Caption Font Family 2', LANGUAGE_ZONE ),
			'subtitle' => __( 'This font is used in banner caption, inner style 6 and logo slider.', LANGUAGE_ZONE ),
			'id' => 'typography_caption_font_family2',
			'type' => 'typography',
			'font-style' => false,
			'font-weight' => false,
			'subsets' => false,
			'text-align' => false,
			'font-size' => false,
			'line-height' => false,
			'color' => false,
			'default' => array(
				'font-family' => 'Open Sans Condensed'
			),
			'compiler' => true,
			'font_family_clear' => false
		),
		array(
			'title' => __( 'Caption Font Family 3', LANGUAGE_ZONE ),
			'subtitle' => __( 'This font is used in slider style 4 caption, logo text, menu, 404 page and coming soon page.', LANGUAGE_ZONE ),
			'id' => 'typography_caption_font_family3',
			'type' => 'typography',
			'font-style' => false,
			'font-weight' => false,
			'subsets' => false,
			'text-align' => false,
			'font-size' => false,
			'line-height' => false,
			'color' => false,
			'default' => array(
				'font-family' => 'Dosis'
			),
			'compiler' => true,
			'font_family_clear' => false
		),
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Logo Font', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Logo Font', LANGUAGE_ZONE ),
			'subtitle' => __( 'Specify the logo font properties', LANGUAGE_ZONE ),
			'id' => 'typography_logo_font',
			'type' => 'typography',
			'google'   => true,
			'font-style' => false,
			'line-height' => false,
			'subsets' => false,
			'text-align' => false,
			'color' => false,
			'default' => array(
				'font-family' => 'Dosis',
				'font-size' => '20px',
				'font-weight' => '600'
			),
			'output'   => array( '#header .logo' )
		)
	)
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Branding', LANGUAGE_ZONE ),
    'icon'  => 'el el-picture'
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Logo', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Logo in header', LANGUAGE_ZONE ),
			'id' => 'branding_logo',
			'type' => 'media',
			'url' => true,
			'default' => array( 'url' => MIRACLE_URL . '/framework/assets/images/logo.png' )
		),
		array(
			'title' => __( 'Logo Width (px)', LANGUAGE_ZONE ),
			'id' => 'branding_logo_width',
			'type' => 'text',
			'validate' => 'numeric',
			'default' => '25',
			'compiler' => true
		),
		array(
			'title' => __( 'Logo in loading page', LANGUAGE_ZONE ),
			'id' => 'branding_loading_logo',
			'type' => 'media',
			'url' => true,
			'default' => array( 'url' => MIRACLE_URL . '/framework/assets/images/logo.png' )
		),
		array(
			'title' => __( 'Logo text', LANGUAGE_ZONE ),
			'id' => 'branding_logo_text',
			'type' => 'text',
			'default' => 'MIRACLE'
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Favicon', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Favicon Icon (32x32 px)', LANGUAGE_ZONE ),
			'id' => 'branding_favicon_icon',
			'type' => 'media',
			'url' => true,
			'default' => array( 'url' => MIRACLE_URL . '/framework/assets/images/favicon.png' )
		)
	)
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Header', LANGUAGE_ZONE ),
    'icon'  => 'el el-list-alt',
    'fields' => array(
    	array(
    		'id'     => 'opt-info-normal',
            'type'   => 'info',
            'notice' => false,
            'style'  => 'info',
            'title'  => __( 'Note', LANGUAGE_ZONE ),
            'desc' => __( 'You can override these settings using page settings meta box in the page.', LANGUAGE_ZONE ),
    	),
    	array(
			'title' => __( 'Header Style', LANGUAGE_ZONE ),
			'type' => 'radio',
			'id' => 'header_inner_style',
			'default' => 'style1',
			'options' => array(
				'none' => __( 'No Header', LANGUAGE_ZONE ),
				'color' => __( 'Background Color', LANGUAGE_ZONE ),
				'style1' => __( 'Background Clip Image (repeat)', LANGUAGE_ZONE ),
				'style2' => __( 'Background Image (no-repeat)', LANGUAGE_ZONE ),
				'style3' => __( 'Background Image Parallax', LANGUAGE_ZONE )
			)
		),
		array(
			'title' => __( 'Header Background Color', LANGUAGE_ZONE ),
			'id' => 'header_background_color',
			'type' => 'color',
			'transparent' => false,
			'required' => array( 'header_inner_style', '=', 'color' )
		),
		array(
			'title' => __( 'Header Background Image', LANGUAGE_ZONE ),
			'id' => 'header_background_clip_image',
			'type' => 'media',
			'url' => true,
			'required' => array( 'header_inner_style', '=', 'style1' ),
			'default' => array( 'url' => MIRACLE_URL . '/framework/assets/images/inner/style1-pattern.png' )
		),
		array(
			'title' => __( 'Header Background Image', LANGUAGE_ZONE ),
			'id' => 'header_background_image',
			'type' => 'media',
			'url' => true,
			'required' => array( 'header_inner_style', '=', array( 'style2', 'style3' ) ),
		),
		array(
			'title' => __( 'Header Background Parallax Ratio', LANGUAGE_ZONE ),
			'id' => 'header_background_parallax_ratio',
			'type' => 'slider',
			'default' => .5,
			'min'           => 0,
            'step'          => .1,
            'max'           => 1,
            'display_value' => 'text',
            'resolution'    => 0.1,
            'required' => array( 'header_inner_style', '=', 'style3' ),
		),
		array(
			'title' => __( 'Show Page Title', LANGUAGE_ZONE ),
			'id' => 'header_show_page_title',
			'type' => 'switch',
			'default' => true,
			'required' => array( 'header_inner_style', '!=', 'no' ),
		),
		array(
			'title' => __( 'Show Breadcrumbs', LANGUAGE_ZONE ),
			'id' => 'header_show_breadcrumbs',
			'type' => 'switch',
			'default' => true,
			'required' => array( 'header_inner_style', '!=', 'no' ),
		),
		array(
			'title' => __( 'Show Sticky Header', LANGUAGE_ZONE ),
			'id' => 'header_show_sticky_header',
			'type' => 'switch',
			'default' => true
		),
		array(
			'title' => __( 'Header Font Color', LANGUAGE_ZONE ),
			'id' => 'header_font_color',
			'type' => 'color',
			'default' => '#000000',
		),
		array(
			'title' => __( 'Main Menu Style', LANGUAGE_ZONE ),
			'id' => 'header_main_menu_style',
			'type' => 'button_set',
			'default' => '',
			'options' => array(
				'' => __( 'White', LANGUAGE_ZONE ),
				'dark' => __( 'Black', LANGUAGE_ZONE ),
				'colored' => __( 'Colored', LANGUAGE_ZONE )
			)
		)
    )
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Footer', LANGUAGE_ZONE ),
    'icon'  => 'el el-photo',
    'fields' => array(
    	array(
			'title' => __( 'Footer Skin', LANGUAGE_ZONE ),
			'subtitle' => __( 'Select a Footer Skin', LANGUAGE_ZONE ),
			'id' => 'footer_skin',
			'default' => "style1",
			'type' => 'button_set',
			'options' => array(
				'style1' => __( 'Skin 1', LANGUAGE_ZONE ),
				'style2' => __( 'Skin 2', LANGUAGE_ZONE ),
				'style3' => __( 'Skin 3', LANGUAGE_ZONE ),
				'style4' => __( 'Skin 4', LANGUAGE_ZONE ),
			)
		),
		array(
			'title' => __( 'Footer Widget Areas', LANGUAGE_ZONE ),
			'id' => 'footer_widget_areas',
			'type' => 'select',
			'options' => array(
				'none' => __( 'None (Disable)', LANGUAGE_ZONE ),
				'1' => __( 'One', LANGUAGE_ZONE ),
				'2' => __( 'Two', LANGUAGE_ZONE ),
				'3' => __( 'Three', LANGUAGE_ZONE ),
				'4' => __( 'Four', LANGUAGE_ZONE ),
			),
			'default' => '4',
			'class' => 'disable_empty_field'
		),
		array(
			'title' => __( 'Show Bottom Bar', LANGUAGE_ZONE ),
			'id' => 'footer_show_bottom_bar',
			'type' => 'switch',
			'default' => true,
		),
		array(
			'title' => __( 'Copyright Content', LANGUAGE_ZONE ),
			'id' => 'footer_copyright_content',
			'type' => 'textarea',
			'default' => '&copy; 2015 Miracle by <a href="http://www.soaptheme.net/">SoapTheme</a>',
		),
		array(
			'title' => __( 'Show the Scroll Top Anchor', LANGUAGE_ZONE ),
			'id' => 'footer_show_scroll_top_anchor',
			'type' => 'switch',
			'default' => true
		),
		array(
			'type' => 'background',
			'id' => 'branding_footer_logo',
			'title' => __( 'Logo icon in the anchor for scrolling to top', LANGUAGE_ZONE ),
			'background-color' => false,
			'transparent' => false,
			'background-repeat' => false,
			'background-attachment' => false,
			'background-position' => false,
			'preview' => false,
			'default' => array(
				'background-image' => MIRACLE_URL . '/framework/assets/images/icon/scroll-to-top.png'
			),
			'output' => array( '#footer .back-to-top:before' ),
			'required' => array( 'footer_show_scroll_top_anchor', '=', true )
		),
    )
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Blog', LANGUAGE_ZONE ),
    'icon'  => 'el el-book'
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Blog Layout', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Blog Page Title', LANGUAGE_ZONE ),
			'id' => 'blog_page_title',
			'type' => 'text',
			'default' => __( 'Blog', LANGUAGE_ZONE )
		),
		array(
			'title' => __( 'Blog Style', LANGUAGE_ZONE ),
			'id' => 'blog_style',
			'type' => 'image_select',
			'options' => array(
				'masonry' => array(
					'alt' => __( 'Masonry', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/masonry.jpg'
				),
				'grid' => array(
					'alt' => __( 'Grid', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/grid.jpg'
				),
				'full' => array(
					'alt' => __( 'Full', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full.jpg'
				),
				'classic' => array(
					'alt' => __( 'Classic', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/classic.jpg'
				),
				'timeline' => array(
					'alt' => __( 'Timeline', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/timeline.jpg'
				),
			),
			'default' => 'masonry'
		),
		array(
			'title' => __( 'Blog Page Sidebar Position', LANGUAGE_ZONE ),
			'subtitle' => __( 'Please select Left/Right if you want to display sidebar in the blog pages.', LANGUAGE_ZONE ),
			'id' => 'blog_show_sidebar',
			'type' => 'image_select',
			'options' => array(
				'disabled' => array(
					'alt' => __( 'No Sidebar', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full-width.jpg'
				),
				'left' => array(
					'alt' => __( 'Left', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/left-sidebar.jpg'
				),
				'right' => array(
					'alt' => __( 'Right', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/right-sidebar.jpg'
				)
			),
			'default' => 'disabled'
		),
		array(
			'title' => __( 'Blog Columns', LANGUAGE_ZONE ),
			'id' => 'blog_columns',
			'type' => 'slider',
			'default' => 3,
			'min'           => 1,
            'step'          => 1,
            'max'           => 6,
            'display_value' => 'text',
            'required' => array( 'blog_style', '=', array( 'masonry', 'grid' ) )
		),
		array(
			'title' => __( 'Blog Pagination Style', LANGUAGE_ZONE ),
			'id' => 'blog_pagination_style',
			'type' => 'button_set',
			'options' => array(
				'default' => __( 'Default', LANGUAGE_ZONE ),
				'ajax' => __( 'Ajax Pagination', LANGUAGE_ZONE ),
				'load_more' => __( 'Infinite Blog with load more button', LANGUAGE_ZONE )
			),
			'default' => 'default'
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Archives Layout', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Archive Style', LANGUAGE_ZONE ),
			'id' => 'blog_archive_style',
			'type' => 'image_select',
			'options' => array(
				'masonry' => array(
					'alt' => __( 'Masonry', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/masonry.jpg'
				),
				'grid' => array(
					'alt' => __( 'Grid', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/grid.jpg'
				),
				'full' => array(
					'alt' => __( 'Full', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full.jpg'
				),
				'classic' => array(
					'alt' => __( 'Classic', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/classic.jpg'
				),
				'timeline' => array(
					'alt' => __( 'Timeline', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/timeline.jpg'
				),
			),
			'default' => 'masonry'
		),
		array(
			'title' => __( 'Archive Page Sidebar Position', LANGUAGE_ZONE ),
			'subtitle' => __( 'Please select Left/Right if you want to display sidebar in the archive page.', LANGUAGE_ZONE ),
			'id' => 'blog_archive_show_sidebar',
			'type' => 'image_select',
			'options' => array(
				'disabled' => array(
					'alt' => __( 'No Sidebar', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full-width.jpg'
				),
				'left' => array(
					'alt' => __( 'Left', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/left-sidebar.jpg'
				),
				'right' => array(
					'alt' => __( 'Right', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/right-sidebar.jpg'
				)
			),
			'default' => 'disabled'
		),
		array(
			'title' => __( 'Archive Blog Columns', LANGUAGE_ZONE ),
			'id' => 'blog_archive_columns',
			'type' => 'slider',
			'default' => 3,
			'min'           => 1,
            'step'          => 1,
            'max'           => 6,
            'display_value' => 'text',
            'required' => array( 'blog_archive_style', '=', array( 'masonry', 'grid' ) )
		),
		array(
			'title' => __( 'Archive Blog Pagination Style', LANGUAGE_ZONE ),
			'id' => 'blog_archive_pagination_style',
			'type' => 'button_set',
			'options' => array(
				'default' => __( 'Default', LANGUAGE_ZONE ),
				'ajax' => __( 'Ajax Pagination', LANGUAGE_ZONE ),
				'load_more' => __( 'Infinite Blog with load more button', LANGUAGE_ZONE )
			),
			'default' => 'default'
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Search Page Layout', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Search Page Style', LANGUAGE_ZONE ),
			'id' => 'blog_search_style',
			'type' => 'image_select',
			'options' => array(
				'masonry' => array(
					'alt' => __( 'Masonry', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/masonry.jpg'
				),
				'grid' => array(
					'alt' => __( 'Grid', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/grid.jpg'
				),
				'full' => array(
					'alt' => __( 'Full', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full.jpg'
				),
				'classic' => array(
					'alt' => __( 'Classic', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/classic.jpg'
				),
				'timeline' => array(
					'alt' => __( 'Timeline', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/timeline.jpg'
				),
			),
			'default' => 'masonry'
		),
		array(
			'title' => __( 'Search Page Sidebar Position', LANGUAGE_ZONE ),
			'subtitle' => __( 'Please select Left/Right if you want to display sidebar in the search result page.', LANGUAGE_ZONE ),
			'id' => 'blog_search_show_sidebar',
			'type' => 'image_select',
			'options' => array(
				'disabled' => array(
					'alt' => __( 'No Sidebar', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full-width.jpg'
				),
				'left' => array(
					'alt' => __( 'Left', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/left-sidebar.jpg'
				),
				'right' => array(
					'alt' => __( 'Right', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/right-sidebar.jpg'
				)
			),
			'default' => 'disabled',
		),
		array(
			'title' => __( 'Search Page Columns', LANGUAGE_ZONE ),
			'id' => 'blog_search_columns',
			'type' => 'slider',
			'default' => 3,
			'min'           => 1,
            'step'          => 1,
            'max'           => 6,
            'display_value' => 'text',
            'required' => array( 'blog_search_style', '=', array( 'masonry', 'grid' ) )
		),
		array(
			'title' => __( 'Search Page Pagination Style', LANGUAGE_ZONE ),
			'id' => 'blog_search_pagination_style',
			'type' => 'button_set',
			'options' => array(
				'default' => __( 'Default', LANGUAGE_ZONE ),
				'ajax' => __( 'Ajax Pagination', LANGUAGE_ZONE ),
				'load_more' => __( 'Infinite Blog with load more button', LANGUAGE_ZONE )
			),
			'default' => 'default'
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Blog Posts', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Excerpt Length', LANGUAGE_ZONE ),
			'id' => 'blog_excerpt_length',
			'type' => 'text',
			'default' => '40'
		),
		array(
			'title' => __( 'Post Meta Date Format', LANGUAGE_ZONE ),
			'id' => 'blog_post_meta_date_format',
			'type' => 'text',
			'default' => 'j F Y'
		),
		array(
			'title' => __( 'Timeline Post Date Format', LANGUAGE_ZONE ),
			'id' => 'blog_timeline_post_date_format',
			'type' => 'text',
			'default' => 'j M, Y'
		),
		array(
			'title' => __( 'Sharing', LANGUAGE_ZONE ),
			'id' => 'blog_post_sharing',
			'type' => 'button_set',
			'multi' => true,
			'options' => array(
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'googleplus' => 'Google+',
				'linkedin' => 'LinkedIn',
				'pinterest' => 'Pinterest'
			),
			'default' => array( 'facebook', 'twitter' )
		),
		array(
			'title' => __( 'Show Post Like Button', LANGUAGE_ZONE ),
			'id' => 'blog_show_post_like_button',
			'type' => 'switch',
			'default' => true
		),
		array(
			'title' => __( 'Show Post Share Button', LANGUAGE_ZONE ),
			'id' => 'blog_show_post_share_button',
			'type' => 'switch',
			'default' => true
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Single Post', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Show Author info in posts', LANGUAGE_ZONE ),
			'id' => 'blog_show_author_in_posts',
			'type' => 'switch',
			'default' => true
		),
		array(
			'title' => __( 'Show Related posts', LANGUAGE_ZONE ),
			'id' => 'blog_show_related_posts',
			'type' => 'switch',
			'default' => true
		),
		array(
			'title' => __( 'Maximum number of related posts', LANGUAGE_ZONE ),
			'id' => 'blog_rel_posts_max',
			'type' => 'text',
			'validate' => 'numeric',
			'default' => '6',
			'required' => array( 'blog_show_related_posts', '=', true )
		)
	)
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Portfolio', LANGUAGE_ZONE ),
    'icon'  => 'el el-tasks',
    'fields' => array(
    	array(
			'title' => __( 'Single Portfolio Date Format', LANGUAGE_ZONE ),
			'id' => 'portfolio_single_date_format',
			'type' => 'text',
			'default' => 'j F Y'
		),
	    array(
			'title' => __( 'Portfolio Masonry Date Format', LANGUAGE_ZONE ),
			'id' => 'portfolio_masonry_date_format',
			'type' => 'text',
			'default' => 'j M, Y'
		),
	    array(
			'title' => __( 'Sharing', LANGUAGE_ZONE ),
			'id' => 'portfolio_sharing',
			'type' => 'button_set',
			'multi' => true,
			'options' => array(
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'googleplus' => 'Google+',
				'linkedin' => 'LinkedIn',
				'pinterest' => 'Pinterest'
			),
			'default' => array(
				'facebook',
				'twitter'
			)
		)
    )
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'WooCommerce', LANGUAGE_ZONE ),
    'icon'  => 'el el-shopping-cart'
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Shop', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Shop Page Title', LANGUAGE_ZONE ),
			'id' => 'shop_page_title',
			'type' => 'text',
			'default' => __( 'Shop', LANGUAGE_ZONE )
		),
		array(
			'title' => __( 'Shop Layout', LANGUAGE_ZONE ),
			'id' => 'shop_layout',
			'type' => 'image_select',
			'options' => array(
				'grid' => array(
					'alt' => __( 'Grid', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/grid.jpg'
				),
				'list' => array(
					'alt' => __( 'List', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full.jpg'
				)
			),
			'default' => 'grid'
		),
		array(
			'title' => __( 'Shop Sidebar Position', LANGUAGE_ZONE ),
			'subtitle' => __( 'Please select Left/Right if you want to display sidebar in the shop pages.', LANGUAGE_ZONE ),
			'id' => 'shop_show_sidebar',
			'type' => 'image_select',
			'options' => array(
				'disabled' => array(
					'alt' => __( 'No Sidebar', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/full-width.jpg'
				),
				'left' => array(
					'alt' => __( 'Left', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/left-sidebar.jpg'
				),
				'right' => array(
					'alt' => __( 'Right', LANGUAGE_ZONE ),
					'img' => MIRACLE_URL . '/framework/assets/images/icon/right-sidebar.jpg'
				)
			),
			'default' => 'disabled'
		),
		array(
			'title' => __( 'Shop Columns', LANGUAGE_ZONE ),
			'id' => 'shop_columns',
			'type' => 'button_set',
			'default' => '4',
			'options' => array( '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' )
		),
		/*array(
			'title' => __( 'Shop Pagination Style', LANGUAGE_ZONE ),
			'id' => 'shop_pagination_style',
			'type' => 'button_set',
			'options' => array(
				'default' => __( 'Default', LANGUAGE_ZONE ),
				'ajax' => __( 'Ajax Pagination', LANGUAGE_ZONE ),
				'load_more' => __( 'Infinite Blog with load more button', LANGUAGE_ZONE )
			),
			'default' => 'default'
		),*/
		array(
			'title' => __( 'Products Per Page', LANGUAGE_ZONE ),
			'id' => 'shop_posts_count',
			'type' => 'text',
			'default' => '12'
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Single Product', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Show Related Products', LANGUAGE_ZONE ),
			'subtitle' => __( 'If you want to show replated products in the single product page, please check this option.', LANGUAGE_ZONE ),
			'id' => 'product_show_related_products',
			'type' => 'switch',
			'default' => true,
		),
		array(
			'title' => __( 'Related Product Columns', LANGUAGE_ZONE ),
			'id' => 'product_related_product_columns',
			'type' => 'button_set',
			'default' => '4',
			'options' => array( '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' )
		),
		array(
			'title' => __( 'Related Product Count', LANGUAGE_ZONE ),
			'id' => 'product_related_product_count',
			'type' => 'text',
			'default' => '4'
		),
		array(
			'title' => __( 'Show Upsells', LANGUAGE_ZONE ),
			'subtitle' => __( 'If you want to show upsells in the single product page, please check this option.', LANGUAGE_ZONE ),
			'id' => 'product_show_upsell_products',
			'type' => 'switch',
			'default' => false,
		),
		array(
			'title' => __( 'Upsell Columns', LANGUAGE_ZONE ),
			'id' => 'product_upsell_product_columns',
			'type' => 'button_set',
			'default' => '4',
			'options' => array( '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' )
		),
		array(
			'title' => __( 'Upsell Product Count', LANGUAGE_ZONE ),
			'id' => 'product_upsell_product_count',
			'type' => 'text',
			'default' => '4'
		),
		array(
			'title' => __( 'Sharing', LANGUAGE_ZONE ),
			'id' => 'product_sharing',
			'type' => 'button_set',
			'multi' =>  true,
			'options' => array(
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
				'googleplus' => 'Google+',
				'linkedin' => 'LinkedIn',
				'pinterest' => 'Pinterest'
			),
			'default' => array(
				'facebook',
				'twitter'
			)
		)
	)
) );
Redux::setSection( $opt_name, array(
	'title' => __( 'Cart', LANGUAGE_ZONE ),
	'subsection' => true,
	'fields' => array(
		array(
			'title' => __( 'Show Cross Sells', LANGUAGE_ZONE ),
			'subtitle' => __( 'If you want to show cross sells in the cart page, please check this option.', LANGUAGE_ZONE ),
			'id' => 'cart_show_cross_sells',
			'type' => 'switch',
			'default' => false,
		),
		array(
			'title' => __( 'Cross Sell Columns', LANGUAGE_ZONE ),
			'id' => 'cart_cross_sells_columns',
			'type' => 'button_set',
			'default' => '4',
			'options' => array( '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' )
		),
		array(
			'title' => __( 'Cross Sell Product Count', LANGUAGE_ZONE ),
			'id' => 'cart_cross_sells_count',
			'type' => 'text',
			'default' => '4'
		),
		array(
			'title' => __( 'Show Mini Cart', LANGUAGE_ZONE ),
			'subtitle' => __( 'If you want to show mini cart in top bar, please check this option.', LANGUAGE_ZONE ),
			'id' => 'cart_show_mini_cart',
			'type' => 'switch',
			'default' => true,
		)
	)
) );

$social_links = miracle_get_social_site_names();
$miracle_social_options = array();
foreach ( $social_links as $key => $name ) {
	$miracle_social_options[] = array(
		'title' => sprintf(__( '%s Profile URL', LANGUAGE_ZONE ), $name),
		'id'   => 'social_' . $key . '_url',
		'type' => 'text',
		'default'  => ''
	);
}
Redux::setSection( $opt_name, array(
    'title' => __( 'Social', LANGUAGE_ZONE ),
    'icon'  => 'el el-torso',
    'fields' => $miracle_social_options
) );



function removeDemoModeLink($framework) {
    // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
        remove_filter( 'plugin_row_meta', array(
            ReduxFrameworkPlugin::instance(),
            'plugin_metalinks'
        ), null, 2 );

        // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
        remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
    }

    remove_action( 'admin_notices', array( $framework, '_admin_notices' ), 99 );
}
add_action( 'redux/loaded', 'removeDemoModeLink', 10, 1 );

/*function remove_demo() {

    // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
        remove_filter( 'plugin_row_meta', array(
            ReduxFrameworkPlugin::instance(),
            'plugin_metalinks'
        ), null, 2 );

        // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
        remove_action( 'admin_notices', array( Redux_Welcome::instance(), 'admin_notices' ) );
    }
}*/

function miracle_redux_update_current_version() {
	$saveVer = Redux_Helpers::major_version( get_option( 'redux_version_upgraded_from' ) );
	if ( empty( $saveVer ) ) {
		update_option( 'redux_version_upgraded_from', ReduxFramework::$_version );
	}
}
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
	add_action( 'redux/loaded', 'miracle_redux_update_current_version', 1 );
}