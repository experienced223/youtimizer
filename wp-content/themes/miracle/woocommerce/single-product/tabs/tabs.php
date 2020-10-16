<?php
/**
 * WOOCOMMERCE/SINGLE-PRODUCT/TABS/TABS.PHP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

$first = true;

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-single-tabs tab-container vertical-tab clearfix box">
		<ul class="tabs">
			<?php foreach ( $tabs as $key => $tab ) : ?>

				<li class="<?php echo esc_attr( $key ) ?>_tab<?php if ( $first ) { echo ' active'; $first = false; } ?>">
					<a href="#tab-<?php echo esc_attr( $key ) ?>" data-toggle="tab"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
				</li>

			<?php endforeach; ?>
		</ul>
		<?php
		$first = true;
		foreach ( $tabs as $key => $tab ) : ?>

			<div class="tab-content panel entry-content<?php if ( $first ) { echo ' in active'; $first = false; } ?>" id="tab-<?php echo esc_attr( $key ) ?>">
				<div class="tab-pane">
					<?php call_user_func( $tab['callback'], $key, $tab ) ?>
				</div>
			</div>

		<?php endforeach; ?>
	</div>

<?php endif; ?>
