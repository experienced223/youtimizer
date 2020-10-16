<?php
/**
 * The template for displaying 404 page (Not Found)
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!DOCTYPE html>
<!--[if IE 7 ]>	<html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>	<html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE   ]>	<html class="ie" <?php language_attributes(); ?>> <![endif]-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php if ( floatval( get_bloginfo( 'version' ) ) < 4.1 ) : ?>
	<!-- Page Title -->
	<title><?php wp_title( '-', true, 'right' ); ?></title>
<?php endif; ?>

	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<?php
	$miracle_favicon_icon = miracle_get_option('branding_favicon_icon');
	if ( ! empty( $miracle_favicon_icon['url'] ) ): ?>
	<link rel="shortcut icon" href="<?php echo esc_url( $miracle_favicon_icon['url'] ); ?>" type="image/x-icon" />
	<?php
	endif;
	?>

	<!-- CSS for IE -->
	<!--[if lte IE 9]>
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/framework/assets/css/ie.css" />
	<![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="<?php echo get_template_directory_uri(); ?>/framework/assets/js/html5.js"></script>
	  <script type='text/javascript' src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
	<![endif]-->

	<?php wp_head();?>
</head>
<body <?php body_class(); ?>>
	<div id="page-wrapper">
		<header id="header">
			<div class="container">
				<div class="header-inner">
					<div class="branding">
						<h1 class="logo">
						<?php
							$miracle_option_branding_logo = miracle_get_option('branding_logo');
						?>
							<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo('name'); ?> - <?php _e( 'Home', LANGUAGE_ZONE ); ?>">
								<img src="<?php echo $miracle_option_branding_logo['url']; ?>" alt="<?php bloginfo('name'); ?>">
								<?php echo miracle_get_option('branding_logo_text', ''); ?>
							</a>
						</h1>
					</div>
					<nav id="nav" class="visible-mobile">
						<ul class="header-top-nav">
							<li style="visibility: hidden;">
								<a href="#mobile-nav-wrapper" data-toggle="collapse"><i class="fa fa-bars has-circle"></i></a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

		<section id="content">
			<div class="container">
				<div id="main" class="col-md-10 col-lg-8">
					<hr class="color-light1 box-lg">
					<div class="heading-box">
						<h2 class="box-title"><?php _e( 'Oops! page not found', LANGUAGE_ZONE ); ?></h2>
						<p class="desc-sm"><?php _e( 'Please go back or select any page from the menu below', LANGUAGE_ZONE ); ?></p>
					</div>
					<div class="error-message-404 block"><span></span></div>
					<hr class="color-light1 box-lg">
					<a href="#" class="btn btn-md style4 btn-go-back"><?php _e( 'Go Back', LANGUAGE_ZONE ); ?></a>
					<!-- <a href="#" class="btn btn-md style4">Get Help</a> -->
				</div>
			</div>
		</section>

		<footer id="footer" class="style4">
			<div class="footer-bottom-area">
				<div class="container">
					<div class="copyright-area">
						<?php if ( has_nav_menu( 'footer-menu' ) ) {
							wp_nav_menu( array( 'theme_location' => 'footer-menu', 'container' => 'nav', 'container_class'=>'secondary-menu', 'menu_class' => 'nav nav-pills', 'walker'=> new Miracle_Walker_Nav_Menu ) ); 
						} else { ?>
							<nav class="secondary-menu hidden-mobile">
								<ul class="nav nav-pills">
									<li><a href="<?php echo esc_url( home_url() ); ?>"><?php _e('Home', LANGUAGE_ZONE); ?></a></li>
									<li><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', LANGUAGE_ZONE); ?></a></li>
								</ul>
							</nav>
						<?php } ?>
						<div class="copyright">
							&copy; <?php echo stripslashes( htmlspecialchars_decode ( esc_html( miracle_get_option('footer_copyright_content') ) ) ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
<?php wp_footer(); ?>
</body>
</html>