<?php
/**
 * Set up the default widget area
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// register widget area
if ( !function_exists( 'miracle_widgets_init' ) ) :

	function miracle_widgets_init() {

		register_sidebar( array(
			'name'			=> __( 'Default Sidebar', LANGUAGE_ZONE ),
			'id'			=> 'sidebar-main',
			'description'   => __( 'Sidebar primary widget area', LANGUAGE_ZONE ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		$footer_columns = miracle_get_option( 'footer_widget_areas', '4' );
		if ( $footer_columns !== 'none' ) {
			for ( $i = 1; $i <= $footer_columns; $i++ ) {
				register_sidebar(array(
					'name' 			=> 'Footer - column' . $i,
					'id'			=> 'sidebar-footer-' . $i,
					'before_widget'	=> '<div id="%1$s" class="widget %2$s">', 
					'after_widget'	=> '</div>', 
					'before_title'	=> '<h5 class="section-title box">', 
					'after_title'	=> '</h5>', 
				));
			}
		}
	}
	add_action( 'widgets_init', 'miracle_widgets_init' );

endif;