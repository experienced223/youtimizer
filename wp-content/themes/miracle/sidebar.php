<?php

/**
 * The Sidebar containing the default widget areas.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

global $post;

$sidebar = MiracleHelper::check_sidebar();

?>

<?php if ( $sidebar && ( !defined( 'WP_MIRACLE_DEV' ) || !WP_MIRACLE_DEV || !isset($_GET['nosidebar']) || $_GET['nosidebar'] != 'true' ) ) :
	if ( is_singular() ) {
		$sidebar_id = get_post_meta( $post->ID, '_miracle_sidebar_widget_area', true );
	}
	if ( empty( $sidebar_id ) ) {
		$sidebar_id = apply_filters( 'miracle_default_sidebar', 'sidebar-main' );
	}
?>
	<aside class="sidebar col-sm-4 col-md-3">
		<div class="sidebar-content">
		<?php do_action( 'miracle_before_sidebar_widgets' ); ?>
		<?php dynamic_sidebar( $sidebar_id ); ?>
		</div>
	</aside>
<?php endif; ?>