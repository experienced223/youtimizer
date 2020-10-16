<?php
/**
 * WOOCOMMERCE/NOTICES/ERROR.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $messages ) ){
	return;
}

?>
<div class="woocommerce-error alert alert-error">
	<ul>
		<?php foreach ( $messages as $message ) : ?>
			<li><?php echo wp_kses_post( $message ); ?></li>
		<?php endforeach; ?>
	</ul>
	<span class="close"></span>
</div>
