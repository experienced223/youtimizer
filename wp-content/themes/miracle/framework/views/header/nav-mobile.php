<?php
/**
 * Outputs the mobile menu
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$has_primary_nav = has_nav_menu( 'primary' );

if ( is_home() ) {
	$entry_id = get_option( 'page_for_posts' );
} else if ( is_singular() ) {
	$entry_id = get_the_ID();
}
if ( !empty( $entry_id ) ) {
	$one_page_nav = get_post_meta( $entry_id, '_miracle_one_page_nav', true );
}
?>

<div class="mobile-nav-wrapper collapse visible-mobile" id="mobile-nav-wrapper">
<?php 
	if ( !empty( $one_page_nav ) ) { // if one page navigation
		wp_nav_menu( array(
			'menu' => $one_page_nav,
			'container'      => false,
			'menu_class'     => 'mobile-nav miracle-scroll-nav',
			'walker'         => new MiracleMobileMenuWalker()
		) );
	} else if ( $has_primary_nav ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'mobile-nav',
			'walker'         => new MiracleMobileMenuWalker()
		) );
	} else {
		 echo '<ul class="mobile-nav"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . __(' Assign a Menu', LANGUAGE_ZONE ) . '</a></li></ul>';
	}
?>
</div>