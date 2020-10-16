<?php

/**
 * This file contains functions to implement mega menu by improving wordpress menu
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'MiracleMegaMenu', false ) ) :

	class MiracleMegaMenu {

		function __construct() {

			// enqueue css and javascript to the menu page
			add_action( 'admin_menu', array($this, 'enqueue_admin_menu') );

			// add mega menu fields to menu
			add_filter( 'wp_setup_nav_menu_item', array($this, 'add_mega_nav_fields') );

			// replace menu admin walker
			add_filter( 'wp_edit_nav_menu_walker', array($this,'replace_admin_walker'), 100 );

			// save mega menu fields
			add_action( 'wp_update_nav_menu_item', array($this,'update_menu_fields'), 100, 3 );
		}

		function enqueue_admin_menu() {

			if( basename( $_SERVER['PHP_SELF']) == "nav-menus.php" ) {
				wp_enqueue_style( 'miracle_admin_menu', MIRACLE_URL . '/framework/assets/css/admin/' . 'admin-menu.css' );
				wp_enqueue_script( 'miracle_mega_menu' , MIRACLE_URL . '/framework/assets/js/admin/' . 'mega-menu.js', array('jquery', 'jquery-ui-sortable'), false, true );
			}
		}

		function add_mega_nav_fields( $menu_item ) {

			$menu_item->miracle_mega_menu_icon = get_post_meta( $menu_item->ID, '_menu_item_miracle_mega_menu_icon', true );

			$menu_item->miracle_mega_menu_enabled = get_post_meta( $menu_item->ID, '_menu_item_miracle_mega_menu_enabled', true );
			$menu_item->miracle_mega_menu_columns = get_post_meta( $menu_item->ID, '_menu_item_miracle_mega_menu_columns', true );

			$menu_item->miracle_mega_menu_new_row = get_post_meta( $menu_item->ID, '_menu_item_miracle_mega_menu_new_row', true );

			return $menu_item;
		}

		function replace_admin_walker($name) {

			return 'MiracleEditMenuWalker';
		}

		function update_menu_fields( $menu_id, $menu_item_db_id, $args ) {

			// mega menu enabled
			$enable_mega_menu = isset($_REQUEST['menu-item-miracle-enable-mega-menu'], $_REQUEST['menu-item-miracle-enable-mega-menu'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_miracle_mega_menu_enabled', $enable_mega_menu );

			// icon
			if ( isset($_REQUEST['menu-item-miracle-icon']) && is_array( $_REQUEST['menu-item-miracle-icon'] ) ) {
				$icon = !empty( $_REQUEST['menu-item-miracle-icon'][$menu_item_db_id] ) ? $_REQUEST['menu-item-miracle-icon'][$menu_item_db_id] : '';
				update_post_meta( $menu_item_db_id, '_menu_item_miracle_mega_menu_icon', $icon );
			}

			// columns
			if ( isset($_REQUEST['menu-item-miracle-columns']) && is_array( $_REQUEST['menu-item-miracle-columns'] ) ) {
				$columns = absint($_REQUEST['menu-item-miracle-columns'][$menu_item_db_id]);
				update_post_meta( $menu_item_db_id, '_menu_item_miracle_mega_menu_columns', $columns );
			}

			// new row
			$new_row = isset($_REQUEST['menu-item-miracle-new-row'], $_REQUEST['menu-item-miracle-new-row'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_miracle_mega_menu_new_row', $new_row );
		}
	}

endif;

if ( ! class_exists( 'MiracleEditMenuWalker' ) ) :

	class MiracleEditMenuWalker extends Walker_Nav_Menu {

		/**
		 * @see Walker_Nav_Menu::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int $depth Depth of page.
		 */
		function start_lvl(&$output, $depth = 0, $args = array()) {}

		/**
		 * @see Walker_Nav_Menu::end_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int $depth Depth of page.
		 */
		function end_lvl(&$output, $depth = 0, $args = array()) {}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param object $args
		 * @param int $current_id Menu item ID.
		 */
		function start_el(&$output, $item, $depth = 0, $args = array(), $current_id = 0 ) {
			global $_wp_nav_menu_max_depth;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

			ob_start();
			$item_id = esc_attr( $item->ID );
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);

			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title = get_the_title( $original_object->ID );
			}

			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
			);

			$title = $item->title;

			if ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf( __('%s (Pending)', LANGUAGE_ZONE), $item->title );
			}

			$title = empty( $item->label ) ? $title : $item->label;

			// set default item fields
			$default_mega_menu_fields = array(
				'miracle_mega_menu_icon' => '',
				'miracle_mega_menu_enabled' => 0,
				'miracle_mega_menu_columns' => 3,
				'miracle_mega_menu_new_row' => 0,
			);

			// set default values
			foreach ( $default_mega_menu_fields as $field=>$value ) {
				if ( !isset($item->$field) ) {
					$item->$field = $value;
				}
			}

			if ( empty( $item->miracle_mega_menu_columns ) ) {
				$item->miracle_mega_menu_columns = 3;
			}

			$mega_menu_container_classes = array( 'miracle-mega-menu-fields' );
			if ( !empty($item->miracle_mega_menu_enabled) ) {
				$classes[] = 'field-miracle-mega-menu-enabled';
			}
			if ( !empty($item->miracle_mega_menu_icon) ) {
				$mega_menu_container_classes[] = 'field-miracle-mega-menu-icon';
			}

			$mega_menu_container_classes = implode( ' ', $mega_menu_container_classes );

			?>
			<li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo (0 == $depth ? 'style="display: none;"' : ''); ?>><?php _e( 'sub item', LANGUAGE_ZONE ); ?></span></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-order hide-if-js">
								<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-up-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									);
								?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
								|
								<a href="<?php
									echo wp_nonce_url(
										add_query_arg(
											array(
												'action' => 'move-down-menu-item',
												'menu-item' => $item_id,
											),
											remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
										),
										'move-menu_item'
									);
								?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
							</span>
							<a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
								echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
							?>"><?php _e( 'Edit Menu Item', LANGUAGE_ZONE ); ?></a>
						</span>
					</dt>
				</dl>

				<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
					<?php if( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
								<?php _e( 'URL', LANGUAGE_ZONE ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-thin">
						<label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
							<span class='miracle-default-label'><?php _e( 'Navigation Label', LANGUAGE_ZONE ); ?></span>
							<span class='miracle-mega-menu-title-label'><?php _e( 'Mega Menu Column Title <small>(if you dont want to display a title leave it blank. )</small>', LANGUAGE_ZONE ); ?></span>
							<br />
							<input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="description description-thin description-title">
						<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
							<?php _e( 'Title Attribute', LANGUAGE_ZONE ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php _e( 'Open link in a new window/tab', LANGUAGE_ZONE ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
							<?php _e( 'CSS Classes (optional)', LANGUAGE_ZONE ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
							<?php _e( 'Link Relationship (XFN)', LANGUAGE_ZONE ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
							<?php _e( 'Description', LANGUAGE_ZONE ); ?><br />
							<textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
							<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', LANGUAGE_ZONE); ?></span>
						</label>
					</p>

					<!-- Mega Menu Start -->
					<div style="clear: both;"></div>
					<div class="<?php echo esc_attr( $mega_menu_container_classes ); ?>">

						<p class="field-miracle-icon description description-wide">
							<label>
								<?php _ex( 'Iconfont code', 'edit menu walker', LANGUAGE_ZONE ); ?><br />
								<textarea class="widefat edit-menu-item-icon" rows="3" cols="20" name="menu-item-miracle-icon[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->miracle_mega_menu_icon ); // textarea_escaped ?></textarea>
							</label>
						</p>

						<!-- first level -->
						<p class="field-miracle-enable-mega-menu">
							<label for="edit-menu-item-miracle-enable-mega-menu-<?php echo esc_attr($item_id); ?>">
								<input id="edit-menu-item-miracle-enable-mega-menu-<?php echo esc_attr($item_id); ?>" type="checkbox" class="menu-item-miracle-enable-mega-menu" name="menu-item-miracle-enable-mega-menu[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->miracle_mega_menu_enabled ); ?>/>
								<?php _ex( 'Enable Mega Menu', 'edit menu walker', LANGUAGE_ZONE ); ?>
							</label>
						</p>
						<!--<p class="field-miracle-columns description description-wide">
							<?php _ex( 'Number of columns: ', 'edit menu walker', LANGUAGE_ZONE ); ?>
							<select name="menu-item-miracle-columns[<?php echo esc_attr($item_id); ?>]" id="edit-menu-item-miracle-columns-<?php echo esc_attr($item_id); ?>">
								<?php foreach( array( '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5 ) as $title=>$value): ?>
									<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $item->miracle_mega_menu_columns); ?>><?php echo esc_html($title); ?></option>
								<?php endforeach; ?>
							</select>
						</p>-->

						<!-- second level -->
						<p class="field-miracle-new-row">
							<label for="edit-menu-item-miracle-new-row-<?php echo esc_attr($item_id); ?>">
								<input id="edit-menu-item-miracle-new-row-<?php echo esc_attr($item_id); ?>" type="checkbox" name="menu-item-miracle-new-row[<?php echo esc_attr($item_id); ?>]" <?php checked( $item->miracle_mega_menu_new_row ); ?>/>
								<?php _ex( 'This item should start a new row', 'edit menu walker', LANGUAGE_ZONE ); ?>
							</label>
						</p>

					</div>

					<?php do_action( 'miracle_edit_menu_walker_print_item_settings', $item, $depth, $args, $item_id ); ?>

					<!-- Mega Menu End -->

					<p class="field-move hide-if-no-js description description-wide">
						<label>
							<span><?php _e( 'Move', LANGUAGE_ZONE ); ?></span>
							<a href="#" class="menus-move-up"><?php _e( 'Up one', LANGUAGE_ZONE ); ?></a>
							<a href="#" class="menus-move-down"><?php _e( 'Down one', LANGUAGE_ZONE ); ?></a>
							<a href="#" class="menus-move-left"></a>
							<a href="#" class="menus-move-right"></a>
							<a href="#" class="menus-move-top"><?php _e( 'To the top', LANGUAGE_ZONE ); ?></a>
						</label>
					</p>

					<div class="menu-item-actions description-wide submitbox">
						<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
							<p class="link-to-original">
								<?php printf( __('Original: %s', LANGUAGE_ZONE), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
						echo wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'delete-menu-item',
									'menu-item' => $item_id,
								),
								admin_url( 'nav-menus.php' )
							),
							'delete-menu_item_' . $item_id
						); ?>"><?php _e( 'Remove', LANGUAGE_ZONE ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
							?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php _e('Cancel', LANGUAGE_ZONE); ?></a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				</div>
				<ul class="menu-item-transport"></ul>
		<?php
			$output .= ob_get_clean();

		}
	}

endif;