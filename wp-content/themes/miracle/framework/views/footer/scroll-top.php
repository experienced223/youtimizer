<?php
/**
 * Output the button for scrolling to top
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<?php if ( miracle_get_option('footer_show_scroll_top_anchor') == true ) : ?>
	<a href="#" class="back-to-top"><span></span></a>
<?php endif; ?>