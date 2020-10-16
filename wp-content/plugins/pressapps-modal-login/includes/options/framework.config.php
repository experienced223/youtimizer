<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * Options Page settings
 * @var $settings
 */
$settings = array(
	'header_title' => __( 'Modal Login', 'pressapps-modal-login' ),
	'menu_title'   => __( 'Modal Login', 'pressapps-modal-login' ),
	'menu_type'    => 'add_submenu_page',
	'menu_slug'    => 'pressapps-modal-login',
	'ajax_save'    => false,
);

/**
 * Options sections & fields
 * @var $options
 */
$options = array();

/**
 * Login Tab Section & options
 */
$options[] = array(
	'name'   => 'ml-login',
	'title'  => __( 'Login Form', 'pressapps-modal-login' ),
	'icon'   => 'si-lock',
	'fields' => array(
		array(
		  'type'    => 'subheading',
		  'content' => 'Content',
		),
		array(
			'id'         => 'login_title',
			'type'       => 'text',
			'title'      => __( 'Title', 'pressapps-modal-login' ),
			'default'    => 'Login',
		),
		array(
			'id'    => 'login_subtitle',
			'type'  => 'textarea',
			'title' => __( 'Subtitle', 'pressapps-modal-login' ),
			'attributes'    => array(
		    'rows'        => 3,
		    'cols'        => 60,
		  ),
		)
	)
);

/**
 * Registration Tab Section & options
 */
$options[] = array(
	'name'   => 'ml-registration',
	'title'  => __( 'Registration Form', 'pressapps-modal-login' ),
	'icon'   => 'si-avatar',
	'fields' => array(
		array(
		  'type'    => 'subheading',
		  'content' => 'Content',
		),
		array(
			'id'         => 'registration_title',
			'type'       => 'text',
			'title'      => __( 'Title', 'pressapps-modal-login' ),
			'default'    => 'Register',
		),
		array(
			'id'    => 'registration_subtitle',
			'type'  => 'textarea',
			'title' => __( 'Subtitle', 'pressapps-modal-login' ),
			'attributes'    => array(
		    'rows'        => 3,
		    'cols'        => 60,
		  ),
		),
		array(
		  'type'    => 'subheading',
		  'content' => 'Form Fields',
		),
		array(
			'id'    => 'user_fullname',
			'type'  => 'switcher',
			'title' => __( 'User Name', 'pressapps-modal-login' ),
			'default'    => false,
			'desc'      => __( 'Allow users to enter their first and last name during registration.', 'pressapps-modal-login' ),
		),
		array(
			'id'    => 'user_password',
			'type'  => 'switcher',
			'title' => __( 'User Generated Password', 'pressapps-modal-login' ),
			'default'    => false,
			'desc'      => __( 'Allow users to enter their own password during registration.', 'pressapps-modal-login' ),
		),
		array(
		  'type'    => 'subheading',
		  'content' => 'Google reCAPTCHA',
		  'info'  => __( 'Enable Google reCAPTCHA in registration form, register <a target="_blank" href="https://www.google.com/recaptcha/admin#list">API keys</a>. If left blank reCAPTCHA will be disabled.', 'pressapps-modal-login' ),
		),
		array(
			'id'         => 'google_captcha_sitekey',
			'type'       => 'text',
			'title'      => __( 'Site Key', 'pressapps-modal-login' ),
		),
		array(
			'id'         => 'google_captcha_secretkey',
			'type'       => 'text',
			'title'      => __( 'Secret Key', 'pressapps-modal-login' ),
		),
		array(
		  'type'    => 'subheading',
		  'content' => 'Email Template',
		),
		array(
			'id'         => 'registration_email_subject',
			'type'       => 'text',
			'title'      => __( 'Subject', 'pressapps-modal-login' ),
		),
		array(
			'id'    => 'registration_email_body',
			'type'  => 'textarea',
			'title' => __( 'Body', 'pressapps-modal-login' ),
			'info'  => __( 'Available variables: <code>%username%</code>, <code>%password%</code>, <code>%loginlink%</code>. Leave blank to use default email template.', 'pressapps-modal-login' ),
			'attributes'    => array(
		    'rows'        => 10,
		    'cols'        => 90,
		  ),
		)
	)
);

/**
 * Redirects Tab Section & options
 */
$options[] = array(
	'name'   => 'ml-redirects',
	'title'  => __( 'Redirects', 'pressapps-modal-login' ),
	'icon'   => 'si-forward-arrow',
	'fields' => array(
		array(
			'id'      => 'login_redirect',
			'type'    => 'radio',
			'title'   => __( 'Login Redirect', 'pressapps-modal-login' ),
			'options' => array(
				'home'     => __( 'Home Page', 'pressapps-modal-login' ),
				'current'  => __( 'Current Page', 'pressapps-modal-login' ),
				'custom'   => __( 'Custom URL ', 'pressapps-modal-login' ),
			),
			'default' => 'home',
			'desc'		=> __( 'Where to redirect subcriber user role after successful login.', 'pressapps-modal-login' ),
		),
		array(
			'id'         => 'login_redirect_url',
			'type'       => 'text',
			'title'      => __( 'Login Redirect URL', 'pressapps-modal-login' ),
			'dependency' => array( 'paml_login_redirect_custom', '==', 'true' ),
			'info'  => __( 'Enter full URL e.g. ' .site_url() . '/redirectpage/', 'pressapps-modal-login' ),
		),
		array(
			'id'         => 'logout_redirect_url',
			'type'       => 'text',
			'title'      => __( 'Logout Redirect URL', 'pressapps-modal-login' ),
			'desc'  => __( 'Where to redirect user after logout.', 'pressapps-modal-login' ),
			'info'  => __( 'Enter full URL e.g. ' .site_url() . '/redirectpage/', 'pressapps-modal-login' ),
		),
		array(
			'id'         => 'registration_redirect_url',
			'type'       => 'text',
			'title'      => __( 'Registration Redirect URL', 'pressapps-modal-login' ),
			'desc'  => __( 'Where to redirect user after successful registration.', 'pressapps-modal-login' ),
			'info'  => __( 'Enter full URL e.g. ' .site_url() . '/redirectpage/', 'pressapps-modal-login' ),
		),
	)
);

/**
 * Style Tab & options
 */
$options[] = array(
	'name'   => 'ml-style',
	'title'  => __( 'Styling', 'pressapps-modal-login' ),
	'icon'   => 'si-brush',
	'fields' => array(
		array(
			'id'      => 'labels',
			'type'    => 'radio',
			'title'   => __( 'Display Labels', 'pressapps-modal-login' ),
			'options' => array(
				'labels'        => __( 'Labels', 'pressapps-modal-login' ),
				'placeholders'        => __( 'Placeholders', 'pressapps-modal-login' ),
			),
			'default' => 'placeholders',
			'desc'		=> __( 'Display input field labels or placeholders.', 'pressapps-modal-login' ),
		),
		array(
			'id'    => 'align_center',
			'type'  => 'switcher',
			'title' => __( 'Center Align', 'pressapps-modal-login' ),
			'default'    => true,
		),
		array(
			'id'    => 'form_shadow',
			'type'  => 'switcher',
			'title' => __( 'Form Shadow', 'pressapps-modal-login' ),
			'default'    => true,
		),
		array(
			'id'      => 'form_border_radius',
			'type'    => 'number',
			'title'   => __( 'Form Border Radius', 'pressapps-knowledge-base' ),
			'default' => '3',
			'after'	  => 'px',
		),
		array(
			'id'      => 'input_border_radius',
			'type'    => 'number',
			'title'   => __( 'Input Border Radius', 'pressapps-knowledge-base' ),
			'default' => '3',
			'after'	  => 'px',
		),
		array(
			'id'      => 'backdrop_color',
			'type'    => 'color_picker',
			'title'   => __( 'Backdrop Color', 'pressapps-modal-login' ),
			'rgba'    => true,
			'default' => 'rgba(0,0,0,0.8)',
		),
		array(
			'id'      => 'form_background_color',
			'type'    => 'color_picker',
			'title'   => __( 'Form Background Color', 'pressapps-modal-login' ),
			'default' => '#03A9F4',
			'rgba'    => false,
		),
		array(
			'id'      => 'font_color',
			'type'    => 'color_picker',
			'title'   => __( 'Font Color', 'pressapps-modal-login' ),
			'default' => '#f9f9f9',
			'rgba'    => false,
		),
		array(
			'id'      => 'link_color',
			'type'    => 'color_picker',
			'title'   => __( 'Link Color', 'pressapps-modal-login' ),
			'default' => '#f9f9f9',
			'rgba'    => false,
		),
		array(
			'id'      => 'button_background_color',
			'type'    => 'color_picker',
			'title'   => __( 'Button Color', 'pressapps-modal-login' ),
			'default' => '#181818',
			'rgba'    => false,
		),
		array(
			'id'    => 'custom_css',
			'type'  => 'textarea',
			'title' => __( 'Custom CSS', 'pressapps-modal-login' ),
			'attributes'    => array(
		    'rows'        => 5,
		    'cols'        => 60,
		  ),
		)
	)
);

// Register Framework page settings and options fields
SkeletFramework::instance( $settings, $options );
