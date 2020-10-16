<?php

/**
 * The footer
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$footer_class = miracle_get_option('footer_skin');
if ( defined( 'WP_MIRACLE_DEV' ) && WP_MIRACLE_DEV && isset( $_COOKIE['miracleFooterStyle'] ) ) {
	$footer_class = $_COOKIE['miracleFooterStyle'];
}
?>
		<footer id="footer" class="<?php echo esc_attr( $footer_class ); ?>">
		  <?php miracle_get_template( 'widget-areas', '', 'footer' ); ?>
		  <?php if ( miracle_get_option('footer_show_bottom_bar') == true ) : ?>
			<div class="footer-bottom-area">
				<div class="container">
					<div class="copyright-area">
						<?php
							if ( has_nav_menu( 'bottom' ) ) :
							  wp_nav_menu( array(
							    'theme_location' => 'bottom',
							    'container'      => 'nav',
							    'container_class'=> 'secondary-menu',
							    'menu_class'     => 'nav nav-pills',
							    'depth'          => 1
							  ) );
							endif;
						?>
						<div class="copyright"><?php echo miracle_get_option('footer_copyright_content'); ?></div>
					</div>
				</div>
			</div>
		  <?php endif; ?>
		</footer>

	</div> <!-- end page wrapper -->
	<?php wp_footer(); ?>
</body>
</html>