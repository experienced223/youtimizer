<?php
/**
 * WOOCOMMERCE/NOTICES/NOTICE.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ){
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="woocommerce-info alert alert-notice"><?php echo wp_kses_post( $message ); ?><span class="close"></span></div>
<?php endforeach; ?>
