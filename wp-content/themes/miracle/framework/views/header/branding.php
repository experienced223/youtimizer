<?php
/**
 * Outputs the branding
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( is_home() ) {
	$entry_id = get_option( 'page_for_posts' );
} else if ( is_singular() ) {
	$entry_id = get_the_ID();
}

if ( !empty( $entry_id ) ) {
	$header_font_color = get_post_meta( $entry_id, '_miracle_header_font_color', true );
}
if ( empty( $header_font_color ) ) {
	$header_font_color = miracle_get_option('header_font_color');
}
$miracle_logo_text = miracle_get_option('branding_logo_text');
$miracle_logo_image_arr = miracle_get_option('branding_logo');
?>
<div class="branding">
	<h1 class="logo">
		<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo('name'); ?> - <?php _e( 'Home', LANGUAGE_ZONE ); ?>" style="color: <?php echo esc_attr( $header_font_color ); ?>">
			<img src="<?php echo $miracle_logo_image_arr['url']; ?>" alt="<?php bloginfo('name'); ?>"><?php echo empty( $miracle_logo_text ) ? '' : $miracle_logo_text; ?>
		</a>
	</h1>
</div>