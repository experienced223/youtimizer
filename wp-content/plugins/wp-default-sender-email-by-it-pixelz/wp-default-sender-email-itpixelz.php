<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://profiles.wordpress.org/itpixelz/
 * @since             1.0.0
 * @package           Wp_Default_Sender_Email_By_It_Pixelz
 *
 * @wordpress-plugin
 * Plugin Name:       Wp Default Sender Email by IT Pixelz
 * Plugin URI:        https://wordpress.org/plugins/wp-default-sender-email-by-it-pixelz/
 * Description:       Get rid of default email from like this wordpress@domain.com, use your own brand name!
 * Version:           2.0.1
 * Author:            Umar Draz
 * Author URI:        https://profiles.wordpress.org/itpixelz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-default-sender-email-by-it-pixelz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_VERSION', '2.0.0' );
define( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_OPTIONS_KEY', 'wdsei_itpixelz_options' );
define( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_BASE_FILE', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in
 * includes/class-wp-default-sender-email-by-it-pixelz-activator.php
 */
function activate_wp_default_sender_email_by_it_pixelz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-default-sender-email-by-it-pixelz-activator.php';
	Wp_Default_Sender_Email_By_It_Pixelz_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in
 * includes/class-wp-default-sender-email-by-it-pixelz-deactivator.php
 */
function deactivate_wp_default_sender_email_by_it_pixelz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-default-sender-email-by-it-pixelz-deactivator.php';
	Wp_Default_Sender_Email_By_It_Pixelz_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_default_sender_email_by_it_pixelz' );
register_deactivation_hook( __FILE__, 'deactivate_wp_default_sender_email_by_it_pixelz' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific and common hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-default-sender-email-by-it-pixelz.php';

/**
 * Begins execution of the plugin.
 *
 * @since    2.0.0
 */
function run_wp_default_sender_email_by_it_pixelz() {

	$plugin = new Wp_Default_Sender_Email_By_It_Pixelz();
	$plugin->run();

}

run_wp_default_sender_email_by_it_pixelz();
