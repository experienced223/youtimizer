<?php

/* Functions.php
 *
 * Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! isset( $content_width ) ) {
    $content_width = 1170; /* pixels */
}
// set path
define( 'MIRACLE_URL', get_template_directory_uri() );
define( 'MIRACLE_IMAGE_URL', get_template_directory_uri() . '/framework/assets/images' );
define( 'MIRACLE_PATH', get_template_directory() );
define( 'MIRACLE_FUNC_PATH', MIRACLE_PATH .'/framework/functions' );
define( 'MIRACLE_ADMIN_PATH', MIRACLE_PATH .'/framework/functions/admin' );
define( 'MIRACLE_EXT_PATH', MIRACLE_PATH .'/lib' );
define( 'MIRACLE_VIEWS_PATH', 'framework/views/' );
define( 'MIRACLE_WIDGETS_PATH', MIRACLE_FUNC_PATH . '/widgets' );
define( 'RWMB_URL', MIRACLE_URL . '/lib/meta-box/' );

// set languige zone
if ( !defined( 'LANGUAGE_ZONE' ) ) {
    define( 'LANGUAGE_ZONE', 'miracle' );
}

// require admin functions
require_once MIRACLE_FUNC_PATH . '/function-admin.php';

// require files
require_once MIRACLE_FUNC_PATH . '/init.php';

// miracle helper
require_once MIRACLE_FUNC_PATH . '/helpers.php';

// core functions
require_once MIRACLE_FUNC_PATH . '/function-set.php';
require_once MIRACLE_FUNC_PATH . '/function-ajax.php';

require_once MIRACLE_ADMIN_PATH . '/widgets.php';
require_once MIRACLE_ADMIN_PATH . '/metabox/init.php';
require_once MIRACLE_ADMIN_PATH . '/media.php';
require_once MIRACLE_ADMIN_PATH . '/user-profile.php';
require_once MIRACLE_ADMIN_PATH . '/scss.php';

require_once MIRACLE_FUNC_PATH . '/shortcode/init.php';
require_once MIRACLE_FUNC_PATH . '/js_composer/init.php';

// requre enqueue styles and scripts
require_once MIRACLE_FUNC_PATH . '/enqueue/styles.php';
require_once MIRACLE_FUNC_PATH . '/enqueue/javascripts.php';

// global functions
require_once MIRACLE_FUNC_PATH . '/content.php';

if ( class_exists( 'Woocommerce' ) ) {
	require_once MIRACLE_FUNC_PATH . '/woocommerce/init.php';
}

// widgets
require_once( MIRACLE_WIDGETS_PATH . '/flickr.php' );
require_once( MIRACLE_WIDGETS_PATH . '/tweet.php' );
require_once( MIRACLE_WIDGETS_PATH . '/blog-posts.php' );
require_once( MIRACLE_WIDGETS_PATH . '/contact-info.php' );