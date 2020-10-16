<?php

/**
 * Initialize Visual Composer
 */

if ( class_exists( 'Vc_Manager', false ) ) {
    add_action( 'vc_before_init', 'miracle_vcSetAsTheme' );
    function miracle_vcSetAsTheme() {
        vc_set_as_theme(true);
    }

    if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
        //vc_set_default_editor_post_types( array( 'page', 'post', 'm_portfolio' ) );
    }

    if ( function_exists( 'vc_disable_frontend' ) ) :
      vc_disable_frontend();
    endif;

    add_action( 'vc_before_init', 'miracle_load_js_composer' );

    function miracle_load_js_composer() {
        require_once MIRACLE_FUNC_PATH . '/js_composer/js_composer.php';
    }

    if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
        vc_set_shortcodes_templates_dir( MIRACLE_FUNC_PATH . '/js_composer/vc_templates' );
    }
}

