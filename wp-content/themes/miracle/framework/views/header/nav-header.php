<?php
/**
 * Outputs the main nav
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

$main_menu_class = '';
$miracle_menu_style = miracle_get_option( 'header_main_menu_style', '' );
if ( defined( 'WP_MIRACLE_DEV' ) && WP_MIRACLE_DEV && !empty( $_COOKIE['miracleMenuStyle'] ) ) {
	$main_menu_class = ' class="' . esc_attr( 'style-' . $_COOKIE['miracleMenuStyle'] ) . '"';
} else if ( !empty( $miracle_menu_style ) ) {
	$main_menu_class = ' class="' . esc_attr( 'style-' . $miracle_menu_style ) . '"';
}

?>

<nav id="nav" role="navigation"<?php echo empty( $main_menu_class ) ? '' : $main_menu_class; ?>>
	<ul class="header-top-nav">
	<?php if ( class_exists( 'Woocommerce' ) && miracle_get_option('cart_show_mini_cart') ) : ?>
		<li class="mini-cart menu-item-has-children">
			<a href="#"><i class="fa fa-shopping-cart has-circle"></i></a>
			<div class="sub-nav cart-content">
				<?php woocommerce_mini_cart(); ?>
			</div>
		</li>
	<?php endif; ?>
		<li class="mini-search">
			<a href="#"><i class="fa fa-search has-circle"></i></a>
			<div class="main-nav-search-form">
				<form method="get" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="search-box">
						<input type="text" id="s" name="s" value="">
						<button type="submit"><i class="fa fa-search"></i></button>
					</div>
				</form>
			</div>
		</li>
		<li class="visible-mobile">
			<a href="#mobile-nav-wrapper" data-toggle="collapse"><i class="fa fa-bars has-circle"></i></a>
		</li>
	</ul>
<?php 
	if ( !empty( $one_page_nav ) ) { // if one page navigation
		wp_nav_menu( array(
			'menu' => $one_page_nav,
			'container'	  => false,
			'menu_class'	 => 'hidden-mobile miracle-scroll-nav nav',
			'walker'		 => new MiracleMegaMenuWalker()
		) );
	} else if ( $has_primary_nav ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'	  => false,
			'menu_class'	 => 'hidden-mobile',
			'walker'		 => new MiracleMegaMenuWalker()
		) );
	} else {
		 echo '<ul class="hidden-mobile"><li><a href="' . home_url( '/' ) . 'wp-admin/nav-menus.php">' . __(' Assign a Menu', LANGUAGE_ZONE ) . '</a></li></ul>';
	}
?>
</nav>