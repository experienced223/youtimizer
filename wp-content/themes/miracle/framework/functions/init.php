<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'ReduxFramework' ) ) {
    require_once MIRACLE_EXT_PATH . '/redux-framework/ReduxCore/framework.php';
    //require_once MIRACLE_EXT_PATH . '/redux-framework/sample/sample-config.php';
    require_once MIRACLE_FUNC_PATH . '/admin/option-config.php';
}

if ( ! function_exists( 'miracle_load_text_domain' ) ) :

    function miracle_load_text_domain() {
        /*
         * Make theme available for translation
         */
        load_theme_textdomain( LANGUAGE_ZONE, MIRACLE_PATH . '/framework/languages' );
    }

endif;

add_action( 'after_setup_theme', 'miracle_load_text_domain', 15 );

if ( ! function_exists( 'miracle_init' ) ) :

    /*
     * setup theme defaults
     */
    function miracle_init() {

        // Localization
        load_theme_textdomain( LANGUAGE_ZONE, MIRACLE_PATH . '/framework/languages' );

        add_editor_style();

        add_theme_support( 'post-thumbnails' );

        // register additional image sizes
        add_image_size( 'large', 1500, 555, true ); //images for full screen pages
        add_image_size( 'large_sidebar', 1030, 515, true ); //images for full screen pages
        add_image_size( 'frame', 818, 512, true ); // images for shortcode laptop frame slider
        add_image_size( 'masonry', 710, 9999 ); // images for masonry portfolio
        add_image_size( 'masonry_medium', 480, 9999 ); // images for masonry portfolio
        add_image_size( 'gallery_large', 818, 546, true ); //images for double width column
        add_image_size( 'gallery', 710, 474, true ); //images for grid post (2 columns)
        add_image_size( 'gallery_medium', 480, 320, true ); // images for grid post (3, 4 columns)
        add_image_size( 'gallery_small', 360, 240, true ); // images for grid post (5, 6 columns)
		add_image_size( 'widget', 60, 60, true ); // thumbnails for widgets

        wp_upload_dir();

        /*
         * Enable support for Post Formats
         */
        add_theme_support( 'post-formats', array( 'image', 'gallery', 'quote', 'video', 'audio' ) );

        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'title-tag' );

        add_theme_support( 'custom-header' );

        add_theme_support( 'custom-background' );


        register_nav_menus( array(
            'primary'   => __( 'Primary Menu', LANGUAGE_ZONE ),
            'bottom'    => __( 'Bottom Menu', LANGUAGE_ZONE ),
        ) );

        /* Include admin functions */
        if ( is_admin() ) {
            require_once MIRACLE_FUNC_PATH . '/classes/class-tgm-plugin-activation.php';
        } else {
            /* Include custom menu */
            get_template_part( '/framework/functions/classes/custom', 'menu' );
            get_template_part( '/framework/functions/classes/custom', 'mobile-menu' );
        }

        /*
         * multiple sidebar
         */
        if ( !class_exists( 'sidebar_generator' ) ) {
            require_once MIRACLE_EXT_PATH . '/multiple_sidebars.php';
        }

        /* mega menu */
        if ( apply_filters( 'miracle_enable_mega_menu', true ) ) {

            include MIRACLE_FUNC_PATH . '/classes/mega-menu.php';
            $mega_menu = new MiracleMegaMenu();
        }
    }

endif;
add_action( 'after_setup_theme', 'miracle_init', 15 );

if ( ! function_exists( 'miracle_add_theme_options' ) ) {
    function miracle_add_theme_options() {
        return array( 'framework/functions/admin/options.php' );
    }
}


/*
 * function to register required plugins
 */
if ( ! function_exists( 'miracle_register_required_plugins' ) ) {
    function miracle_register_required_plugins() {
        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(
            // This is an example of how to include a plugin pre-packaged with a theme.
            array(
                'name'               => 'Miracle Custom Post', // The plugin name.
                'slug'               => 'miracle-custom-post', // The plugin slug (typically the folder name).
                'source'             => get_template_directory_uri() . '/framework/plugins/miracle-custom-post.zip', // The plugin source.
                'required'           => true, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
                'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            ),
            array(
                'name'               => 'Revolution Slider', // The plugin name.
                'slug'               => 'revslider', // The plugin slug (typically the folder name).
                'source'             => get_template_directory_uri() . '/framework/plugins/revslider.zip', // The plugin source.
                'required'           => false, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '5.2.5', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            ),

			array(
                'name'               => 'WP ULike', // The plugin name.
                'slug'               => 'wp-ulike', // The plugin slug (typically the folder name).
                'source'             => get_template_directory_uri() . '/framework/plugins/wp-ulike.2.3.zip', // The plugin source.
                'required'           => false, // If false, the plugin is only 'recommended' instead of required.
                'version'            => '2.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
                'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
                'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
                'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            ),
			array(
				'name'               => 'Contact Form 7',
				'slug'               => 'contact-form-7',
				'required'           => false,
			),
            array(
                'name'          => 'WPBakery Visual Composer', // The plugin name
                'slug'          => 'js_composer', // The plugin slug (typically the folder name)
                'source'            => get_template_directory_uri() . '/framework/plugins/js_composer.zip', // The plugin source
                'required'          => true, // If false, the plugin is only 'recommended' instead of required
                'version'           => '4.11.2.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url'      => '', // If set, overrides default API URL and points to an external URL
            )
        );
        if ( class_exists( 'Woocommerce' ) ) {
            $plugins[] = array(
                'name'               => 'YITH WooCommerce Ajax Navigation',
                'slug'               => 'yith-woocommerce-ajax-navigation',
                'required'           => false,
            );
        }


        /**
         * Array of configuration settings. Amend each line as needed.
         * If you want the default strings to be available under your own theme domain,
         * leave the strings uncommented.
         * Some of the strings are added into a sprintf, so see the comments at the
         * end of each line for what each argument will be.
         */
        $config = array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ),
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'tgmpa'
            ),
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'tgmpa'
            ),
            'notice_cannot_install'           => _n_noop(
                'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
                'tgmpa'
            ),
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'tgmpa'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'tgmpa'
            ),
            'notice_cannot_update'            => _n_noop(
                'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
                'tgmpa'
            ),
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'tgmpa'
            ),
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'tgmpa'
            ),
            'notice_cannot_activate'          => _n_noop(
                'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
                'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
                'tgmpa'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'tgmpa'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'tgmpa'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'tgmpa'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'dashboard'                       => __( 'Return to the dashboard', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'tgmpa' ),
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'tgmpa' ),
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'tgmpa' ),
            'dismiss'                         => __( 'Dismiss this notice', 'tgmpa' ),
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'tgmpa' ),
        );
        tgmpa( $plugins, $config );
     
    }
}
add_action( 'tgmpa_register', 'miracle_register_required_plugins' );