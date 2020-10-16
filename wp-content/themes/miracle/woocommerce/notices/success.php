<?php
/**
 * WOOCOMMERCE/NOTICES/SUCCESS.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ){
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-message alert alert-success"><?php echo wp_kses_post( $message ); ?><span class="close"></span></div>
<?php endforeach; ?>
