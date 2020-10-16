<?php
/**
 * WOOCOMMERCE/LOOP/PAGINATION.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<nav class="woocommerce-pagination">
<?php echo miracle_pagination(); ?>
</nav>