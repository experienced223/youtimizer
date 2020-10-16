<?php

if( !class_exists( 'MiracleMegaMenuWalker' ) ) {

	/**
	 * frontend walker to display the menu
	 * @package WordPress
	 * @since 1.0.0
	 * @uses Walker
	 */
	class MiracleMegaMenuWalker extends Walker {
		/**
		 * @see Walker::$tree_type
		 * @var string
		 */
		var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

		/**
		 * @see Walker::$db_fields
		 * @todo Decouple this.
		 * @var array
		 */
		var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

		/**
		 * @var int $columns
		 */
		var $columns = 0;

		/**
		 * @var int $rows
		 */
		var $rows = 1;

		var $columns_per_row = array();

		/**
		 * @var string $mega_menu_enabled hold information whetever we are currently rendering a mega menu or not
		 */
		var $mega_menu_enabled = 0;

		/**
		 * @var stores if we already have an active first level main menu item.
		 */
		var $active_item = false;


		/**
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function start_lvl(&$output, $depth = 0, $args = array()) {
			$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class=\"sub-nav\">\n";
		}

		/**
		 * @see Walker::end_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul>\n";

			if($depth === 0) {
				if($this->mega_menu_enabled) {
					foreach($this->columns_per_row as $row_index => $columns) {
						$output = str_replace("{mega-row-" . $row_index . "}", "mega-column-" . $columns, $output);
					}
					$this->columns = 0;
					$this->rows = 1;
					$this->columns_per_row = array();
				}
			}
		}

		/**
		 * @see Walker::start_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
			$item_output = "";

			if ($depth === 0) {
				$this->mega_menu_enabled = get_post_meta( $item->ID, '_menu_item_miracle_mega_menu_enabled', true );
			}

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$icon_code = get_post_meta( $item->ID, '_menu_item_miracle_mega_menu_icon', true );

			if ($depth === 1 && $this->mega_menu_enabled) {

				$this->columns++;

				if (get_post_meta( $item->ID, '_menu_item_miracle_mega_menu_new_row', true ) && $this->columns != 1) {

					$this->columns = 1;
					$this->rows++;
					$classes[] = 'new-row';
				}

				$this->columns_per_row[$this->rows] = $this->columns;

				$title = apply_filters( 'the_title', $item->title, $item->ID );

				if (!empty($title)) {
					$heading_title = do_shortcode($title);
					if ( !empty( $icon_code ) ) {
						$heading_title = sprintf( '<i class="%s"></i><span>%s</span>', esc_attr( $icon_code ), $heading_title );
					}
					if (!empty($item->url) ) {
						if ( $item->url != "#" && $item->url != 'http://' ) {
							$heading_title = "<a href='".$item->url."'>{$heading_title}</a>";
						} else {
							$heading_title = "<a href='#' onclick='return false;'>{$heading_title}</a>";
						}
					}

					$item_output .= $heading_title;
				}
			} else if ($depth >= 2 && $this->mega_menu_enabled) {
				$title = apply_filters( 'the_title', $item->title, $item->ID );

				if (!empty($title)) {
					$heading_title = do_shortcode($title);
					if ( !empty( $icon_code ) ) {
						$heading_title = sprintf( '<i class="%s"></i><span>%s</span>', esc_attr( $icon_code ), $heading_title );
					}
					if (!empty($item->url) ) {
						if ( $item->url != "#" && $item->url != 'http://' ) {
							$heading_title = "<a href='".$item->url."'>{$heading_title}</a>";
						} else {
							$heading_title = "<a href='#' onclick='return false;'>{$heading_title}</a>";
						}
					}

					$item_output .= $heading_title;
				}
			} else {
				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

				$item_output .= $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . do_shortcode(apply_filters('the_title', $item->title, $item->ID)) . $args->link_after;
				$item_output .= '</a>';
				$item_output .= $args->after;
			}

			$class_names = '';
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			if ($depth === 0 && $key = array_search('current-menu-item', $classes)) {
				if ($this->active_item) {
					 unset($classes[$key]);
				} else {
					$this->active_item = true;
				}
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
			if ($depth === 0 && $this->mega_menu_enabled) {
				$class_names .= " mega-menu-item";
			}
			if($depth === 1 && $this->mega_menu_enabled) {
				$class_names .= " {mega-row-" . $this->rows . "}";
			}

			$class_names = ' class="' . esc_attr( $class_names ) .'"';

			$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $class_names .'>';



			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * @see Walker::end_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 */
		function end_el(&$output, $item, $depth = 0, $args = array()) {
			$output .= "</li>\n";
		}
	}
}