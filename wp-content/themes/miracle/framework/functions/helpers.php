<?php
/**
 * Miracle Helpers
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Miracle get options value
 */
if ( !function_exists( 'miracle_get_option' ) ) :

	function miracle_get_option( $option_name, $default = null ) {
		global $miracle_options;
		if ( isset( $miracle_options[$option_name] ) ) {
			return $miracle_options[$option_name];
		}
		if ( $default !== null ) {
			return $default;
		}
		return false;
	}
endif;

if ( !class_exists( 'MiracleHelper' ) ) {

	class MiracleHelper {

		/**
		 * get all available sliders
		 */
		static function get_registered_sidebars( $sidebars = array(), $exclude = array() ) {

			global $wp_registered_sidebars;

			foreach($wp_registered_sidebars as $sidebar) {
				if( !in_array($sidebar['id'], $exclude) ) {
					$sidebars[$sidebar['id']] = $sidebar['name']; 
				}
			}
			
			return apply_filters("get_registered_sidebars", $sidebars);
		}

		/**
		 * get cleared content
		 */
		static function get_cleared_content( $post_content = null ) {

			if ( $post_content !== null ) {
				$content = $post_content;
			} else {
				$content = get_the_content( '' );
			}
			$content = strip_shortcodes( $content );
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );

			return $content;
		}

		/**
		 * Check if there is sidebar in current page
		 */
		static function check_sidebar() {

			global $post;
			$result = false;
			if ( is_home() ) {
				if ( miracle_get_option('blog_show_sidebar') != 'disabled' ) {
					$result = miracle_get_option('blog_show_sidebar');
				}
			} else if ( is_archive() ) {
				if ( ( ( function_exists( 'is_shop' ) && is_shop() ) ||
					( function_exists( 'is_product_category' ) && is_product_category() ) ||
					( function_exists( 'is_product_tag' ) && is_product_tag() ) ) &&
					miracle_get_option('shop_show_sidebar') != 'disabled' ) {
					$result = miracle_get_option('shop_show_sidebar');
				} else if ( miracle_get_option('blog_archive_show_sidebar') != 'disabled' ) {
					$result = miracle_get_option('blog_archive_show_sidebar');
				}
			} else if ( is_search() ) {
				if ( miracle_get_option('blog_search_show_sidebar') != 'disabled' ) {
					$result = miracle_get_option('blog_search_show_sidebar');
				}
			} else if ( is_singular() ) {
				$sidebar = get_post_meta($post->ID, '_miracle_sidebar_position', true);
				$sidebar_id = get_post_meta($post->ID, '_miracle_sidebar_widget_area', true);
				if ( $sidebar !== 'disabled' && is_active_sidebar( $sidebar_id ) ) {
					$result = $sidebar;
				}
			}
			return apply_filters( 'miracle_check_sidebar', $result );
		}

		/**
		 * Get thumbnail size
		 */
		static function get_thumbnail_size( $columns = 1, $is_sidebar = -1, $is_masonry = false ) {
			$columns = (int)$columns;
			if ( $is_sidebar === -1 ) {
				$is_sidebar = MiracleHelper::check_sidebar();
			}
			if ( $is_masonry ) {
				if ( $columns === 1 ) {
					return 'full';
				} else if ( $columns === 2 ) {
					return 'masonry';
				} else {
					return 'masonry_medium';
				}
			}
			if ( $columns === 1 ) {
				if ( $is_sidebar ) {
					return 'large_sidebar';
				} else {
					return 'large';
				}
			} else if ( $columns === 2 ) {
				return 'gallery';
			} else if ( $columns <= 4 ) {
				return 'gallery_medium';
			} else if ( $columns > 4 ) {
				return 'gallery_small';
			}
			return 'full';
		}

		/**
		 * Get the double size in masonry style
		 */
		static function get_masonry_thumbnail_double_size( $image_size ) {
			if ( $image_size == 'masonry_medium' ) {
				return 'masonry';
			}
			return 'full';
		}

		/**
		 * Check for the existence of a gravatar
		 */
		static function validate_gravatar( $email ) {
			// Craft a potential url and test its headers
			$hash = md5(strtolower(trim($email)));
			$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
			$headers = @get_headers($uri);
			if ( !preg_match("|200|", $headers[0]) ) {
				$has_valid_avatar = FALSE;
			} else {
				$has_valid_avatar = TRUE;
			}
			return $has_valid_avatar;
		}
	}

}