<?php
/**
 * WOOCOMMERCE/CHECKOUT/THANKYOU.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', LANGUAGE_ZONE ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', LANGUAGE_ZONE );
			else
				_e( 'Please attempt your purchase again.', LANGUAGE_ZONE );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', LANGUAGE_ZONE ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', LANGUAGE_ZONE ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', LANGUAGE_ZONE ), $order ); ?></p>

		<ul class="order_details order-info">
			<li class="order">
				<?php _e( 'Order Number:', LANGUAGE_ZONE ); ?>
				<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', LANGUAGE_ZONE ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', LANGUAGE_ZONE ); ?>
				<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', LANGUAGE_ZONE ); ?>
				<strong><?php echo wp_kses_post( $order->payment_method_title ); ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', LANGUAGE_ZONE ), null ); ?></p>

<?php endif; ?>
