<?php
/**
 * Defines actions for writing and compiling scss
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function miracle_get_scss_lists() {

	static $scss_lists = null;
	if ( $scss_lists === null ) {
		$scss_lists = array();
		$version = wp_get_theme()->get( 'Version' );
		$scss_lists['style'] = array(
			'scss_uri' => MIRACLE_URL . '/framework/assets/scss/style.scss',
			'scss_path' => MIRACLE_PATH . '/framework/assets/scss/style.scss',
			'css_path' => MIRACLE_PATH . '/framework/assets/css/style.css',
			'import_scss_list' => array(
				MIRACLE_PATH . '/framework/assets/scss/_variables.scss',
				MIRACLE_PATH . '/framework/assets/scss/_base.scss',
				MIRACLE_PATH . '/framework/assets/scss/_mixin.scss'
			),
			'version' => $version
		);
		$scss_lists['responsive'] = array(
			'scss_uri' => MIRACLE_URL . '/framework/assets/scss/responsive.scss',
			'scss_path' => MIRACLE_PATH . '/framework/assets/scss/responsive.scss',
			'css_path' => MIRACLE_PATH . '/framework/assets/css/responsive.css',
			'import_scss_list' => array(
				MIRACLE_PATH . '/framework/assets/scss/_mixin.scss'
			),
			'version' => $version
		);
	}
	return $scss_lists;
}

function miracle_scss_get_saved_option( $option_name, $saved_options = null ) {
	if ( $saved_options == null ) {
		return miracle_get_option( $option_name );
	}
	return $saved_options[$option_name];
}

function miracle_set_style_scss_variables( $saved_options = null ) {
	$options_var = array();

	$font_family = ', Arial, Helvetica, sans-serif';
	$type_font = miracle_scss_get_saved_option('typography_basic_font', $saved_options);
	$options_var['font-stack'] = "'" . $type_font['font-family'] . "'" . $font_family;
	$options_var['primary-color'] = miracle_scss_get_saved_option('general_primary_color', $saved_options);
	$options_var['primary-light-color'] = miracle_scss_get_saved_option('general_primary_light_color', $saved_options);
	$options_var['heading-color'] = miracle_scss_get_saved_option('general_heading_color', $saved_options);
	$options_var['secondary-color'] = miracle_scss_get_saved_option('general_secondary_color', $saved_options);
	$options_var['mainbg-color'] = miracle_scss_get_saved_option('general_mainbg_color', $saved_options);

	$options_var['theme-skin-color'] = miracle_scss_get_saved_option('general_skin_color', $saved_options);
	$options_var['theme-skin-light-bgcolor'] = miracle_scss_get_saved_option('general_skin_light_bgcolor', $saved_options);
	$options_var['theme-skin-light-fontcolor'] = miracle_scss_get_saved_option('general_skin_light_font_color', $saved_options);

	$miracle_max_width = miracle_scss_get_saved_option('general_site_max_width', $saved_options);
	if ( empty( $miracle_max_width ) ) {
		$miracle_max_width = '1170';
	}
	$options_var['site-max-width'] = $miracle_max_width . 'px';

	if ( !empty( $type_font['font-size'] ) ) {
		$options_var['body-font-size'] = $type_font['font-size'];
		$int_font_size = floatval($type_font['font-size']);
		$int_line_height = floatval($type_font['line-height']);
		$options_var['body-line-height'] = ($int_line_height / $int_font_size) . "em";
	} else {
		$options_var['body-font-size'] = "12px";
		$options_var['body-line-height'] = "1.5em";
	}
	
	$miracle_paragraph_font = miracle_scss_get_saved_option('typography_paragraph_font', $saved_options);
	if ( !empty( $miracle_paragraph_font['font-family'] ) ) {
		$options_var['paragraph-font-family'] = $miracle_paragraph_font['font-family'];
	} else {
		$options_var['paragraph-font-family'] = 'inherit';
	}
	if ( !empty( $miracle_paragraph_font['font-size'] ) ) {
		$options_var['paragraph-font-size'] = $miracle_paragraph_font['font-size'];
		$int_font_size = floatval($miracle_paragraph_font['font-size']);
		$int_line_height = floatval($miracle_paragraph_font['line-height']);
		$options_var['paragraph-line-height'] = ($int_line_height / $int_font_size) . "em";
	} else {
		$options_var['paragraph-font-size'] = "13px";
		$options_var['paragraph-line-height'] = "1.8em";
	}

	$miracle_options_typography_caption_font = miracle_scss_get_saved_option('typography_caption_font_family1', $saved_options);
	$options_var['custom-font1'] = $miracle_options_typography_caption_font['font-family'];
	$miracle_options_typography_caption_font = miracle_scss_get_saved_option('typography_caption_font_family2', $saved_options);
	$options_var['custom-font2'] = $miracle_options_typography_caption_font['font-family'];
	$miracle_options_typography_caption_font = miracle_scss_get_saved_option('typography_caption_font_family3', $saved_options);
	$options_var['custom-font3'] = $miracle_options_typography_caption_font['font-family'];

	$branding_logo_width = miracle_scss_get_saved_option('branding_logo_width', $saved_options);
	if ( empty( $branding_logo_width ) ) {
		$branding_logo_width = 'auto';
	} else {
		$branding_logo_width = floatval($branding_logo_width);
		if ( $branding_logo_width <= 0 ) {
			$branding_logo_width = "auto";
		} else {
			$branding_logo_width .= 'px';
		}
	}
	$options_var['branding-logo-width'] = $branding_logo_width;

	return $options_var;
}

function miracle_set_responsive_scss_variables( $saved_options = null ) {
	return array();
}


function miracle_cssdir_is_not_writable() {

	if ( get_option( 'miracle_cssdir_is_writable' ) ) {

		update_option( 'miracle_cssdir_is_writable', 0 );
	}
}
add_action( 'wp-scss_save_stylesheet_error', 'miracle_cssdir_is_not_writable' );

function miracle_cssdir_is_writable() {

	update_option( 'miracle_cssdir_is_writable', 1 );
}
add_action( 'wp-scss_stylesheet_save_post', 'miracle_cssdir_is_writable' );


if ( !function_exists( 'miracle_generate_scss' ) ) :

	function miracle_generate_scss( $handler = 'custom', $src, $css_path = null, $saved_options = null ) {

		$function_name = 'miracle_set_' . $handler . '_scss_variables';
		if ( !function_exists( $function_name ) ) {
			return;
		}

		get_template_part( '/lib/wp-scss/bootstrap-for-theme' );
		if ( class_exists('WPScssPlugin') ) {
			$scss = WPScssPlugin::getInstance();
			$scss->dispatch();
		}

		$handler .= '.scss';
		if ( !wp_style_is($handler, 'registered') ) {

			if ( !$src ) {
				$src = MIRACLE_URL . '/framework/assets/scss/' . $handler . '.scss';
			}
			wp_register_style( $handler, $src );
		}

		$scss->setImportPaths( MIRACLE_PATH . "/framework/assets/scss/" );
		try {
			$variables = $function_name($saved_options);
		} catch (Exception $e) {
			$variables = false;
		}
		if ( $variables === false ) {
			return;
		}

		if ( !empty( $variables ) ) {
			$scss->setVariables( $variables );
		}

		$config = $scss->getConfiguration();

		WPScssStylesheet::$upload_dir = $config->getUploadDir();
		WPScssStylesheet::$upload_uri = $config->getUploadUrl();

		// remove filter to make url() CSS as relative path
		remove_filter('wp-scss_stylesheet_save', array($scss, 'filterStylesheetUri'), 10);

		$scss->getCompiler()->setFormatter('Leafo\ScssPhp\Formatter\Nested');
		$stylesheet =  $scss->processStylesheet( $handler, true );
		if ( $css_path !== null && is_writable( $css_path ) ) {
			file_put_contents( $css_path, file_get_contents( $stylesheet->getTargetPath() ) );
		}

		// generate min.css
		$scss->getCompiler()->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
		$stylesheet =  $scss->processStylesheet( $handler, true );
		$css_path = str_replace( '.css', '.min.css', $css_path );
		@file_put_contents( $css_path, file_get_contents( $stylesheet->getTargetPath() ) );

		return $stylesheet;
	}
endif;

if ( !function_exists( 'miracle_compile_scss_before_including' ) ) :

	function miracle_compile_scss_before_including() {

		$scss_lists = miracle_get_scss_lists();
		foreach ( $scss_lists as $key => $scss ) {
			$recompile = false;
			if ( filemtime($scss['scss_path']) > filemtime($scss['css_path']) ) { // need compile
				$recompile = true;
			} else if ( !empty( $scss['import_scss_list'] ) ) {
				foreach ( $scss['import_scss_list'] as $file ) {
					if ( filemtime($file) > filemtime($scss['css_path']) ) {
						$recompile = true;
						break;
					}
				}
			}
			if ( $recompile ) {
				miracle_generate_scss( $key, $scss['scss_uri'], $scss['css_path'] );
			}
		}
	}
endif;

if ( !function_exists( 'miracle_enqueue_dynamic_styles' ) ) :

	function miracle_enqueue_dynamic_styles() {

		miracle_compile_scss_before_including();
	}
endif;
add_action( 'wp_enqueue_scripts', 'miracle_enqueue_dynamic_styles' );



if ( !function_exists( 'miracle_generate_scss_after_options_save' ) ) :

	function miracle_generate_scss_after_options_save( $options ) {

		$scss_lists = miracle_get_scss_lists();
		foreach( $scss_lists as $key => $scss ) {
			miracle_generate_scss( $key, $scss['scss_uri'], $scss['css_path'], $options );
		}
	}
endif;
add_action( "redux/options/miracle_options/compiler", "miracle_generate_scss_after_options_save", 11, 1 );


function miracle_set_color_variables_of_selected_skin(&$plugin_options, $options, $changed_values) {
	$old_color_skin = $options['color_skins'];

	if ( !is_writable( MIRACLE_PATH . "/framework/assets/css/style.min.css" ) || !is_writable( MIRACLE_PATH . "/framework/assets/css/responsive.min.css" ) ) {
		echo json_encode( array( 'status' => 'error', 'status' => sprintf( __( '%s directory should be write permitted.', LANGUAGE_ZONE ), '"<strong>' . MIRACLE_PATH . '/framework/assets/css/</strong>"' ) ) );
		die ();
	}
	if ( $plugin_options['color_skins'] != $old_color_skin ) {
		$variables = get_option( 'miracle_optionsframework_color_skins_vars', array() );
		$presets = miracle_options_predefined_skin_variables();
		$variables[$old_color_skin] = array();
		foreach ( $presets[$old_color_skin] as $key => $val ) {
			$variables[$old_color_skin][$key] = $options[$key];
		}
		update_option( 'miracle_optionsframework_color_skins_vars', $variables );

		$new_skin = $plugin_options['color_skins'];
		$default_vars = array();
		if ( empty( $variables[$new_skin] ) ) {
			$default_vars = $presets[$new_skin];
		} else {
			$default_vars = $variables[$new_skin];
		}
		foreach ($default_vars as $key => $value) {
			$plugin_options[$key] = $value;
		}

		$redux = ReduxFrameworkInstances::get_instance( 'miracle_options' );
		$redux->set_options( $plugin_options );
		miracle_generate_scss_after_options_save( $plugin_options );
		echo json_encode( array( 'status' => 'success', 'action' => 'reload' ) );
		die ();
	}
}
add_action('redux/options/miracle_options/validate', 'miracle_set_color_variables_of_selected_skin', 10, 3);