<?php

/*
 * Add Shortcode Generator Button
 */
class MiracleShortcodeGenerator {

    function __construct() {
        require_once dirname( __FILE__ ) . '/shortcodes.php';
        $miracle_shortcodes = new MiracleShortcodes();
        add_action( 'init', array( $this, 'init' ) );
    }

    function init() {
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }

        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', array( $this, 'shortcodes_plugin' ) );
            add_filter( 'mce_buttons', array( $this,'shortcodes_register' ) );
        }
    }

    function shortcodes_register( $buttons ) {
        array_push( $buttons, "|", "miracle_shortcode_button" );
        return $buttons;
    }

    function shortcodes_plugin( $plugin_array ) {
        if ( floatval( get_bloginfo( 'version' ) ) >= 3.9 ) {
            $tinymce_js = MIRACLE_URL . '/framework/functions/shortcode/js/tinymce.min.js';
        } else {
            $tinymce_js = MIRACLE_URL . '/framework/functions/shortcode/js/tinymce-legacy.min.js';
        }
        $plugin_array['miracle_shortcode'] = $tinymce_js;
        return $plugin_array;
    }
}

$miracle_shortcodes = new MiracleShortcodeGenerator();