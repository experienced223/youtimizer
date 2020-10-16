<?php
/**
 * Plugin Name:       PressApps Modal Login
 * Description:       Modal login form with Google reCAPTCHA, redirect, email and styling options.
 * Version:           2.0.0
 * Author:            PressApps
 * Author URI:        https://codecanyon.net/user/pressapps
 * Text Domain:       pressapps-modal-login
 * Domain Path:       /languages
 */

 /**
  * The code that runs during plugin activation.
  * This action is documented in includes/class-pressapps-modal-login-activator.php
  */
function activate_pressapps_modal_login() {

	// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
	if ( version_compare( PHP_VERSION, '5.3.0', '<' )  ) {
		deactivate_plugins( plugin_basename( dirname( __FILE__ )  ) );
		wp_die( __( 'The minimum PHP version required for this plugin is 5.3.0 Please upgrade the PHP version or contact your hosting provider to do it for you.', 'pressapps-modal-login' ) );
	}
}

register_activation_hook( __FILE__, 'activate_pressapps_modal_login' );


/**
* Skelet Config
*/
$skelet_paths[] = array(
   'prefix'      => 'paml',
   'dir'         => wp_normalize_path(  plugin_dir_path( __FILE__ ).'includes/' ),
   'uri'         => plugin_dir_url( __FILE__ ).'includes/skelet',
);

/**
* Load Skelet Framework
*/
if( ! class_exists( 'Skelet_LoadConfig' ) ){
  include_once dirname( __FILE__ ) .'/includes/skelet/skelet.php';
}

/**
 * Global Variables
 */
if ( class_exists( 'Skelet' ) && ! isset( $paml ) ) {
	$paml        = new Skelet( 'paml' );
}


/*-----------------------------------------------------------------------------------*/
/* Return option page data */
/*-----------------------------------------------------------------------------------*/
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){
  if($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
      $_SERVER['HTTPS'] = 'on';
}

/*-----------------------------------------------------------------------------------*/
/* Define Constants */
/*-----------------------------------------------------------------------------------*/

define( 'PAML_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PAML_PLUGIN_URL', plugins_url("", __FILE__) );

define( 'PAML_PLUGIN_INCLUDES_DIR', PAML_PLUGIN_DIR . "/includes/" );
define( 'PAML_PLUGIN_INCLUDES_URL', PAML_PLUGIN_URL . "/includes/" );

define( 'PAML_PLUGIN_ASSETS_DIR', PAML_PLUGIN_DIR . "/assets/" );
define( 'PAML_PLUGIN_ASSETS_URL', PAML_PLUGIN_URL . "/assets/" );

/*-----------------------------------------------------------------------------------*/
/* Load text domain */
/*-----------------------------------------------------------------------------------*/

function paml_load_textdomain() {
	load_plugin_textdomain( 'pressapps-modal-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'paml_load_textdomain' );

/*-----------------------------------------------------------------------------------*/
/* Load primary class */
/*-----------------------------------------------------------------------------------*/

require_once PAML_PLUGIN_INCLUDES_DIR. 'modal-login-class.php';

/*-----------------------------------------------------------------------------------*/
/* Load widget class */
/*-----------------------------------------------------------------------------------*/

require_once PAML_PLUGIN_INCLUDES_DIR . 'widget/modal-login-widget.php';

/*-----------------------------------------------------------------------------------*/
/* Login / logout links */
/*-----------------------------------------------------------------------------------*/

function add_modal_login_link( $login_text = 'Login', $logout_text = 'Logout', $show_admin = false ) {
	global $paml_class;

	if ( isset( $paml_class ) ) {
		echo $paml_class->modal_login_btn( $login_text, $logout_text, $show_admin );
	} else {
		echo __( 'Error: Modal Login class failed to load', 'pressapps-modal-login' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Register link */
/*-----------------------------------------------------------------------------------*/

function add_modal_register_link( $register_text = 'Register', $logged_in_text = 'You are alredy logged in' ) {
	global $paml_class;

	if ( isset( $paml_class ) ) {
		echo $paml_class->modal_register_btn( $register_text, $logged_in_text );
	} else {
		echo __( 'Error: Modal Login class failed to load', 'pressapps-modal-login' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Shortcode function  */
/*-----------------------------------------------------------------------------------*/
function modal_login( $params = array() ) {
	$params_str = '';
	foreach( $params as $parameter => $value ) {
		if( $value ) {
			$params_str .= sprintf( ' %s="%s"', $parameter, $value);
		}
	}
	echo do_shortcode( "[modal_login $params_str]" );
}

/*-----------------------------------------------------------------------------------*/
/* Load modal login class */
/*-----------------------------------------------------------------------------------*/

if ( class_exists( 'PAML_Class' ) ) {
	$paml_class = new PAML_Class;
}
