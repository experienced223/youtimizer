<?php

/**
 * The Header
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
$miracle_header_show_sticky = miracle_get_option('header_show_sticky_header');
$body_class = $miracle_header_show_sticky == true ? '' : 'no-sticky-menu';
?>

<!DOCTYPE html>
<!--[if IE 8]>		  <html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>		  <html class="ie ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!-->  <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if ( floatval( get_bloginfo( 'version' ) ) < 4.1 ) : ?>
	<!-- Page Title -->
	<title><?php wp_title( '-', true, 'right' ); ?></title>
<?php endif; ?>
	<?php
	$miracle_favicon_icon = miracle_get_option('branding_favicon_icon');
	if ( ! empty( $miracle_favicon_icon['url'] ) ):
	?>
	<link rel="shortcut icon" href="<?php echo esc_url( $miracle_favicon_icon['url'] ); ?>" type="image/x-icon" />
	<?php endif; ?>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- CSS for IE -->
	<!--[if lte IE 9]>
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/assets/css/ie.css" />
	<![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri(); ?>/framework/assets/js/html5.js"></script>
		<script type='text/javascript' src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<script src="https://wchat.freshchat.com/js/widget.js"></script>
</head>
<script>
  window.fcWidget.init({
    token: "ea0f9795-53b0-4480-9861-4301576871e0",
    host: "https://wchat.freshchat.com"
  });
</script>
<body <?php body_class( $body_class ); ?>>
	<div id="page-wrapper">
		<?php
			if ( is_home() ) {
				$page_id = get_option( 'page_for_posts' );
			} else if ( is_singular() ) {
				$page_id = get_the_ID();
			} else {
				$page_id = null;
			}
			$header_font_color = get_post_meta( $page_id, '_miracle_header_font_color', true );
			if ( empty( $header_font_color ) ) {
				$header_font_color = miracle_get_option('header_font_color');
			}
		?>
		<header id="header" style="color: <?php echo esc_attr( $header_font_color ); ?>">
			<div class="container">
				<div class="header-inner">
					<?php miracle_get_template( 'branding', '', 'header' ) ?>
					<?php miracle_get_template( 'nav', 'header', 'header' ) ?>
				</div>
			</div>
			<?php miracle_get_template( 'nav', 'mobile', 'header' ) ?>
		</header>

		<?php miracle_get_template( 'page-title', '', 'header' ) ?>

		<?php miracle_display_rev_slider(); ?>
