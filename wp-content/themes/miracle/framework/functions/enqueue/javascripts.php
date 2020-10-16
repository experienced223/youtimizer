<?php

/*
 * Include all Javascripts
 */

if ( ! function_exists( 'miracle_enqueue_frontend_scripts' ) ) :
    function miracle_enqueue_frontend_scripts() {
        if ( ! is_admin() ) {
            //wp_register_script( 'miracle-jquery', MIRACLE_URL . '/framework/assets/js/jquery-1.11.1.min.js', array(), NULL, false );
            wp_register_script( 'miracle-jquery-no-conflict', MIRACLE_URL . '/framework/assets/js/jquery.noconflict.js', array( 'jquery' ), NULL, false );
            wp_register_script( 'miracle-js-modernizr', MIRACLE_URL . '/framework/assets/js/modernizr.2.8.3.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-jquery-migrate', MIRACLE_URL . '/framework/assets/js/jquery-migrate-1.2.1.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-jquery-ui', MIRACLE_URL . '/framework/assets/js/jquery-ui.1.10.4.min.js', array( 'jquery' ), NULL, true );

            wp_register_script( 'miracle-js-bootstrap', MIRACLE_URL . '/framework/assets/js/bootstrap.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-js-plugins', MIRACLE_URL . '/framework/assets/js/jquery.plugins.min.js', array( 'jquery' ), NULL, true );

            wp_register_script( 'miracle-js-popup', MIRACLE_URL . '/framework/assets/components/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-js-owl-carousel', MIRACLE_URL . '/framework/assets/components/owl-carousel/owl.carousel.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-js-carou-fredsel', MIRACLE_URL . '/framework/assets/components/carouFredSel-6.2.1/jquery.carouFredSel-6.2.1.min.js', array( 'jquery' ), NULL, true );

            wp_register_script( 'miracle-js-stellar', MIRACLE_URL . '/framework/assets/js/jquery.stellar.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-js-waypoints', MIRACLE_URL . '/framework/assets/js/waypoints.min.js', array( 'jquery' ), NULL, true );
            
            wp_register_script( 'miracle-js-vimeo', 'http://f.vimeocdn.com/js/froogaloop2.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-js-mediaelement', MIRACLE_URL . '/framework/assets/components/mediaelement/mediaelement-and-player.js', array( 'jquery' ), NULL, true );

            wp_register_script( 'miracle-js-skycarousel', MIRACLE_URL . '/framework/assets/components/jquery.sky.carousel/jquery.sky.carousel-1.0.2.min.js', array( 'jquery' ), NULL, true );

            wp_register_script( 'miracle-js-jquerycarousel-mousewheel', MIRACLE_URL . '/framework/assets/components/jquery.carousel-1.1/jquery.mousewheel.min.js', array( 'jquery' ), NULL, true );
            wp_register_script( 'miracle-js-jquerycarousel', MIRACLE_URL . '/framework/assets/components/jquery.carousel-1.1/jquery.carousel-1.1.min.js', array( 'jquery' ), NULL, true );

            wp_register_script( 'miracle-js-main', MIRACLE_URL . '/framework/assets/js/main.js', array( 'jquery' ), NULL, true );


            wp_register_script( 'miracle-js-pace', MIRACLE_URL . '/framework/assets/js/pace.min.js', array(), NULL, false );
            wp_register_script( 'miracle-js-loadingpage', MIRACLE_URL . '/framework/assets/js/page-loading.js', array( 'miracle-js-pace' ), NULL, false );

            //wp_enqueue_script( 'miracle-jquery' );
            wp_enqueue_script( 'miracle-jquery-no-conflict' );

            if ( miracle_get_option('general_pace_loading') == 1 || is_page_template( 'template-loading-page.php' ) ) {
				$loading_page_logo_arr = miracle_get_option('branding_loading_logo');
                $loading_page_logo = $loading_page_logo_arr['url'];
				$branding_logo_arr = miracle_get_option('branding_logo');
                $miracle_option_branding_logo = empty($loading_page_logo) ? $branding_logo_arr['url'] : $loading_page_logo;
                $miracle_option_branding_logo_text = miracle_get_option('branding_logo_text');
                wp_localize_script( 'miracle-js-loadingpage', 'miracle_logo_url', $miracle_option_branding_logo );
                wp_localize_script( 'miracle-js-loadingpage', 'miracle_logo_text', $miracle_option_branding_logo_text );
                wp_enqueue_script( 'miracle-js-pace' );
                wp_enqueue_script( 'miracle-js-loadingpage' );
            }

            global $post;
            $miracle_local = array(
                'postID'        => empty( $post->ID ) ? null : $post->ID,
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'ajaxNonce'     => wp_create_nonce('miracle-ajax'),
            );
            wp_localize_script( 'miracle-js-main', 'miracleLocal', $miracle_local );

            wp_enqueue_script( 'miracle-js-modernizr' );
            wp_enqueue_script( 'miracle-jquery-migrate' );
            wp_enqueue_script( 'miracle-jquery-ui' );
            wp_enqueue_script( 'miracle-js-bootstrap' );
            wp_enqueue_script( 'miracle-js-plugins' );
            wp_enqueue_script( 'miracle-js-popup' );
            wp_enqueue_script( 'miracle-js-owl-carousel' );
            wp_enqueue_script( 'miracle-js-carou-fredsel' );
            wp_enqueue_script( 'miracle-js-stellar' );
            wp_enqueue_script( 'miracle-js-waypoints' );
            wp_enqueue_script( 'miracle-js-vimeo' );
            wp_enqueue_script( 'miracle-js-mediaelement' );
            wp_enqueue_script( 'miracle-js-skycarousel' );
            wp_enqueue_script( 'miracle-js-jquerycarousel-mousewheel' );
            wp_enqueue_script( 'miracle-js-jquerycarousel' );

            if ( is_singular() ) {
                $entry_id = get_the_ID();
            } else if ( is_home() ) {
                $entry_id = get_option( 'page_for_posts' );
            }

            if ( !empty( $entry_id ) ) {
                $header_style = get_post_meta( $entry_id, '_miracle_header_style', true );
                if ( $header_style == 'map' ) {
                    wp_enqueue_script( 'miracle-js-google-map', '//maps.google.com/maps/api/js?sensor=false&language=en', array( 'jquery' ) );
                    wp_enqueue_script( 'miracle-js-gmap3', MIRACLE_URL . '/framework/assets/js/gmap3.js', array( 'jquery', 'miracle-js-google-map' ) );
                    wp_localize_script( 'miracle-js-main', 'miracleThemeObj', array( 'image_url' => MIRACLE_URL . '/framework/assets/images/' ) );
                }
            }

            wp_enqueue_script( 'miracle-js-main' );

            if ( is_singular() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }

            if ( class_exists( 'Woocommerce' ) ) {
                wp_enqueue_script( 'miracle-js-custom', MIRACLE_URL . '/framework/assets/js/woocommerce-custom.js', array( 'jquery' ), NULL, true );
            }
        }
    }
endif;
add_action( 'wp_enqueue_scripts', 'miracle_enqueue_frontend_scripts' );

if ( ! function_exists( 'miracle_enqueue_backend_scripts' ) ) :
    function miracle_enqueue_backend_scripts($page) {
        if ( $page != 'post.php' && $page != 'post-new.php' ) {
            return;
        }

        wp_register_script( 'miracle-admin-js-post-meta', MIRACLE_URL . '/framework/assets/js/admin/post-meta.js', array( 'jquery' ), NULL, true );
        wp_register_script( 'miracle-js-composer-js-custom-views', MIRACLE_URL . '/framework/functions/js_composer/js/composer-custom-views.js', array( 'wpb_js_composer_js_custom_views' ), NULL, true );


        wp_enqueue_script( 'miracle-admin-js-post-meta' );
        if ( class_exists( 'Vc_Manager', false ) ) {
            wp_enqueue_script( 'miracle-js-composer-js-custom-views' );
        }
    }

    add_action( 'admin_enqueue_scripts', 'miracle_enqueue_backend_scripts' );
endif;