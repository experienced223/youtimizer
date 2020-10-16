<?php

/*-----------------------------------------------------------------------------------*/
/* Core modal login class */

/*-----------------------------------------------------------------------------------*/

class PAML_Class {

	// Set the version number
	public $plugin_version = '2.0.0';
	public $plugin_name = 'pressapps-modal-login';


	/*-----------------------------------------------------------------------------------*/
	/* Loads all of required hooks and filters and other cool doodads */
	/*-----------------------------------------------------------------------------------*/

	public function __construct() {

		// Register source code with the wp_footer().
		add_action( 'wp_footer', array( $this, 'paml_login_form' ) );

		// Add JavaScript and Stylesheets to the front-end.
		add_action( 'wp_enqueue_scripts', array( $this, 'paml_resources' ) );

		// Javascript
		add_action( 'wp_footer', array( $this, 'paml_print_style_js' ) );

		// Add lost password field.
		add_action( 'after_modal_form', array( $this, 'paml_additional_options' ) );

		// Add shortcode action.
		add_shortcode( 'modal_login', array( $this, 'paml_shortcode' ) );

		// Register widget.
		add_action( 'widgets_init', function() {
			register_widget( "PAML_Widget" );
		});

		// Run Ajax on the login.
		add_action( 'wp_ajax_nopriv_ajaxlogin', array( $this, 'paml_ajax_login' ) );
		add_action( 'wp_ajax_ajaxlogin', array( $this, 'paml_ajax_login' ) );

		// Add nav menu metabox
		add_filter( 'admin_init', array( $this, 'paml_add_nav_menu_metabox' ) );

		// Use the right label when displaying modal login/logout link
		add_filter( 'wp_nav_menu_objects', array( $this, 'paml_filter_frontend_modal_link_label' ) );

		// Setup modal links attributes
		add_filter( 'nav_menu_link_attributes', array( $this, 'paml_filter_frontend_modal_link_atts' ), 10, 3 );

		// Hide registration link occasionally
		add_filter( 'wp_nav_menu_objects', array( $this, 'paml_filter_frontend_modal_link_register_hide' ) );

		add_action( 'plugin_row_meta', array( $this, 'row_links' ), 10, 2 );
		add_action( 'plugin_action_links_' . $this->plugin_name . "/" . $this->plugin_name . ".php", array( $this, 'settings_link' ) );

	}

	/**
	 * Adds a link to the plugin settings page.
	 *
	 * @since    1.0.0
	 */
	public function settings_link( $links ) {

	  $settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=' . $this->plugin_name ), __( 'Settings', 'pressapps-modal-login' ) );

	  array_unshift( $links, $settings_link );

	  return $links;
	}

	/**
	 * Adds links to the plugin links row.
	 *
	 * @since    1.0.0
	 */
	public function row_links( $links, $file ) {

	  if ( strpos( $file, $this->plugin_name . '.php' ) !== false ) {

	    $link = '<a href="https://pressapps.co" target="_blank">' . __( 'Help', 'pressapps-modal-login' ) . '</a>';

	    array_push( $links, $link );

	  }

	  return $links;

	}



	/*-----------------------------------------------------------------------------------*/
	/* Add menu link metabox */
	/*-----------------------------------------------------------------------------------*/

	public function paml_add_nav_menu_metabox() {
		add_meta_box( 'paml_metabox_modal_link', __( 'Modal Login Link', 'pressapps-modal-login' ), array(
			$this,
			'paml_callback_metabox_modal_link'
		), 'nav-menus', 'side', 'high' );

		wp_register_script( 'paml-admin-nav-menus', PAML_PLUGIN_ASSETS_URL . 'js/menu-admin.js', array( 'jquery' ) );
		wp_enqueue_script( 'paml-admin-nav-menus' );


		$strings = array(
			'label_login'  => __( 'Login Label', 'pressapps-modal-login' ),
			'label_logout' => __( 'Logout Label', 'pressapps-modal-login' ),
		);
		wp_localize_script( 'paml-admin-nav-menus', 'paml_strings', $strings );

	}


	/*-----------------------------------------------------------------------------------*/
	/* Add all scripts and styles to WordPress. */
	/*-----------------------------------------------------------------------------------*/

	public function paml_resources() {
		global $paml;


		//will check the value for the login redirect
		switch ( $paml->get( 'login_redirect' ) ) {
			case 'home':
				$login_url = home_url();
				break;
			case 'custom':
				$login_url = filter_var( $paml->get( 'login_redirect_url' ), FILTER_VALIDATE_URL ) ? $paml->get( 'login_redirect_url' ) : $_SERVER['REQUEST_URI'];
				break;
			case 'current':
			default:
				$login_url = $_SERVER['REQUEST_URI'];
				break;
		}

		$registration_url = filter_var( $paml->get( 'registration_redirect_url' ), FILTER_VALIDATE_URL ) ? $paml->get( 'registration_redirect_url' ) : $_SERVER['REQUEST_URI'];
		$labels = $paml->get( 'labels' );

		wp_enqueue_style( $this->plugin_name, PAML_PLUGIN_ASSETS_URL . 'css/pressapps-modal-login-public.css', null, $this->plugin_version, 'screen' );
		wp_add_inline_style( $this->plugin_name, wp_kses( $this->custom_css(), array( '\"', "\'" ) ) );

		if ( $labels == 'placeholders' ) {
			wp_enqueue_style( 'labels', PAML_PLUGIN_ASSETS_URL . 'css/labels.css', null, $this->plugin_version, 'screen' );
		}

		wp_enqueue_script( 'paml-modal', PAML_PLUGIN_ASSETS_URL . 'js/modal.js', array( 'jquery' ), $this->plugin_version, true );
		wp_enqueue_script( 'paml-script', PAML_PLUGIN_ASSETS_URL . 'js/modal-login.js', array( 'jquery' ), $this->plugin_version, true );
		wp_enqueue_script( 'paml-spin-cdn', '//cdnjs.cloudflare.com/ajax/libs/spin.js/1.2.7/spin.min.js', array(), '1.2.7', true );

		// Only run our ajax stuff when the user isn't logged in.
		if ( ! is_user_logged_in() ) {
			wp_localize_script( 'paml-script', 'modal_login_script', array(
				'ajax'                  => admin_url( 'admin-ajax.php' ),
				'redirecturl'           => $login_url,
				'registration_redirect' => $registration_url,
				'loadingmessage'        => __( 'Checking Credentials...', 'pressapps-modal-login' ),
			) );
			// If user is not logged in and google captcha key are assigned, load Google captcha's api
			if($paml->get( 'google_captcha_sitekey' )){
				wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
				wp_enqueue_script('google-recaptcha');
      }
		}
	}

	public function custom_css() {
		global $paml;

		$custom_css = '';
		// Custom CSS
		if ( $paml->get( 'custom_css' ) ) {
			$custom_css .= $paml->get( 'custom_css' );
		}

		$backdrop_color = sanitize_text_field( $paml->get( 'backdrop_color' ) );
		if ( $backdrop_color ) {
			$custom_css .= '.paml-backdrop { background: ' . $backdrop_color . " }\n";
		}

		$form_background_color = sanitize_text_field( $paml->get( 'form_background_color' ) );
		if ( $form_background_color ) {
			$custom_css .= '.ml-content { background-color: ' . sanitize_text_field( $form_background_color ) . "}\n";
			if ( $form_background_color != '#ffffff' ) {
				$custom_css .= '#modal-login input[type="text"], #modal-login input[type="password"] { border: solid 1px ' . $form_background_color . "; }\n";
				$custom_css .= '#modal-login input[type="text"]:focus, #modal-login input[type="password"]:focus { border: solid 1px ' . $form_background_color . "; }\n";
			}
		}

		$font_color = sanitize_text_field( $paml->get( 'font_color' ) );
		if ( $font_color ) {
			$custom_css .= '#modal-login, #modal-login h2, .ml-content a.ml-close-btn { color: ' . sanitize_text_field( $font_color ) . "}\n";
		}

		$link_color = sanitize_text_field( $paml->get( 'link_color' ) );
		if ( $link_color ) {
			$custom_css .= '#additional-settings, #additional-settings a, #additional-settings a:hover { color: ' . sanitize_text_field( $link_color ) . "}\n";
		}

		$button_background_color = sanitize_text_field( $paml->get( 'button_background_color' ) );
		if ( $button_background_color ) {
			$custom_css .= '#modal-login .submit .button { background: ' . sanitize_text_field( $button_background_color ) . "}\n";
			$custom_css .= '#modal-login .submit .button { border: none; ' . "}\n";
		}

		$form_border_radius = sanitize_text_field( $paml->get( 'form_border_radius' ) );
		if ( $form_border_radius ) {
			$custom_css .= '.ml-content { border-radius: ' . sanitize_text_field( $form_border_radius ) . 'px' . "}\n";
		}

		$input_border_radius = sanitize_text_field( $paml->get( 'input_border_radius' ) );
		if ( $input_border_radius ) {
			$custom_css .= '#modal-login .ml-content input, #modal-login #wp-submit, #modal-login .message { border-radius: ' . sanitize_text_field( $input_border_radius ) . 'px' . "}\n";
		}

		return $custom_css;
	}

	public function paml_print_style_js() {

		// Many themes break the menu behaviour by forgetting an 'apply_filter' at 'nav_menu_link_attributes', that's why we re-add links attributes with jQuery
		if ( ! is_user_logged_in() ) {
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( 'a[href="#pa_modal_login"]' )
						.attr( 'href', '#modal-login' )
						.attr( 'data-toggle', 'ml-modal' )
					;
					$( 'a[href="#pa_modal_register"]' )
						.attr( 'href', '#modal-register' )
						.attr( 'data-toggle', 'ml-modal' )
					;
				} );
			</script>
			<?php
		} else {
			?>
			<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					$( 'a[href="#pa_modal_login"]' ).attr( 'href', '<?php echo wp_logout_url() ?>'.replace( '&amp;', '&' ) );
				} );
			</script>
			<?php
		}

	}

	/*-----------------------------------------------------------------------------------*/
	/* The main Ajax function  */
	/*-----------------------------------------------------------------------------------*/

	public function paml_ajax_login() {
		global $paml;
		// Check our nonce and make sure it's correct.
		if ( is_user_logged_in() ) {
			echo json_encode( array(
				'loggedin' => false,
				'message'  => __( 'You are already logged in', 'pressapps-modal-login' ),
			) );
			die();
		}
		check_ajax_referer( 'ajax-form-nonce', 'security' );

		// Get our form data.
		$data = array();

		// Check that we are submitting the login form
		if ( isset( $_REQUEST['login'] ) ) {

			$data['user_login']    = sanitize_user( $_REQUEST['username'] );
			$data['user_password'] = sanitize_text_field( $_REQUEST['password'] );
			$data['remember']      = ( sanitize_text_field( $_REQUEST['rememberme'] ) == 'TRUE' ) ? true : false;
			$user_login            = wp_signon( $data, is_ssl() );

			// Check the results of our login and provide the needed feedback
			if ( is_wp_error( $user_login ) ) {
				echo json_encode( array(
					'loggedin' => false,
					'message'  => __( 'Wrong Username or Password!', 'pressapps-modal-login' ),
				) );
			} else {
				echo json_encode( array(
					'loggedin' => true,
					'message'  => __( 'Login Successful!', 'pressapps-modal-login' ),
				) );
			}
		} // Check if we are submitting the register form
		elseif ( isset( $_REQUEST['register'] ) ) {
			// If a Google Captcha Site Key is set, check for processed captcha or return error
			if($paml->get( 'google_captcha_sitekey' ) && $paml->get( 'google_captcha_sitekey' ) != ""){
				$recaptcha = $_REQUEST['recaptcha'];
				$google_url = "https://www.google.com/recaptcha/api/siteverify";
				$secret = $paml->get( 'google_captcha_secretkey' );
				$ip = $_SERVER['REMOTE_ADDR'];
				$url = $google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_TIMEOUT, 10);
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
				$results = curl_exec($curl);
				curl_close($curl);
				$res= json_decode($results, true);
				if(!$res['success']){
					echo json_encode( array(
					'registerd' => false,
					'message'   => __('reCAPTCHA invalid'),
				) );
				die();
				}
			}

			// If userdefine_fullname is set register user with fullname
			$user_data = array();
			if($paml->get( 'user_fullname' )){
				$user_data['first_name'] = sanitize_user($_REQUEST['firstname']);
				$user_data['last_name'] = sanitize_user($_REQUEST['lastname']);
			}
			$user_data['user_login'] = sanitize_user($_REQUEST['username']);
			$user_data['user_email'] = sanitize_email($_REQUEST['email']);
			$user_register = $this->paml_register_new_user( $user_data );

			// Check if there were any issues with creating the new user
			if ( is_wp_error( $user_register ) ) {
				echo json_encode( array(
					'registerd' => false,
					'message'   => $user_register->get_error_message(),
				) );
			} else {
				if ( $paml->get( 'user_password' ) ) {
					$success_message = __( 'Registration complete.', 'pressapps-modal-login' );
				} else {
					$success_message = __( 'Registration complete. Check your email.', 'pressapps-modal-login' );
				}
				echo json_encode( array(
					'registerd' => true,
					'message'   => $success_message,
				) );
			}
		} // Check if we are submitting the forgotten pwd form
		elseif ( isset( $_REQUEST['forgotten'] ) ) {

			// Check if we are sending an email or username and sanitize it appropriately
			if ( is_email( $_REQUEST['username'] ) ) {
				$username = sanitize_email( $_REQUEST['username'] );
			} else {
				$username = sanitize_user( $_REQUEST['username'] );
			}

			// Send our information
			$user_forgotten = $this->paml_retrieve_password( $username );

			// Check if there were any errors when requesting a new password
			if ( is_wp_error( $user_forgotten ) ) {
				echo json_encode( array(
					'reset'   => false,
					'message' => $user_forgotten->get_error_message(),
				) );
			} else {
				echo json_encode( array(
					'reset'   => true,
					'message' => __( 'Password Reset. Please check your email.', 'pressapps-modal-login' ),
				) );
			}
		}

		die();
	}


	/*-----------------------------------------------------------------------------------*/
	/* Sanitize user entered information */
	/*-----------------------------------------------------------------------------------*/

	public function paml_register_new_user( $user_information ) {
		global $paml, $wp_hasher;
		$labels = $paml->get( 'labels' );

		$errors               = new WP_Error();
		$sanitized_user_login = sanitize_user( $user_information['user_login'] );
		$user_email = apply_filters( 'user_registration_email', $user_information['user_email'] );

		// Same as username, ensures First and lastname are sanitized
		if($paml->get( 'user_fullname' )){
			$sanitized_First_Name = sanitize_user( $user_information['first_name'] );
			$sanitized_Last_Name = sanitize_user( $user_information['last_name'] );
			// Check the firstname was sanitized
			if ( $sanitized_First_Name == '' ) {
				$errors->add( 'empty_firstname', __( 'Please enter a First Name.', 'pressapps-modal-login' ) );
			} elseif ( !validate_username( $user_information['first_name'] ) ) {
				$errors->add( 'invalid_firstname', __( 'The First Name is invalid because it uses illegal characters. Please enter a valid username.', 'pressapps-modal-login' ) );
				$sanitized_First_Name = '';
			}
			// Check the lastname was sanitized
			if ( $sanitized_Last_Name == '' ) {
				$errors->add( 'empty_lastname', __( 'Please enter a Last Name.', 'pressapps-modal-login' ) );
			} elseif ( !validate_username( $user_information['last_name'] ) ) {
				$errors->add( 'invalid_lastname', __( 'The Last Name is invalid because it uses illegal characters. Please enter a valid username.', 'pressapps-modal-login' ) );
				$sanitized_Last_Name = '';
			}
		}

		// Check the username was sanitized
		if ( $sanitized_user_login == '' ) {
			$errors->add( 'empty_username', __( 'Please enter a username.', 'pressapps-modal-login' ) );
		} elseif ( ! validate_username( $user_information['user_login'] ) ) {
			$errors->add( 'invalid_username', __( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'pressapps-modal-login' ) );
			$sanitized_user_login = '';
		} elseif ( username_exists( $sanitized_user_login ) ) {
			$errors->add( 'username_exists', __( 'This username is already registered. Please choose another one.', 'pressapps-modal-login' ) );
		}

		// Check the email address
		if ( $user_email == '' ) {
			$errors->add( 'empty_email', __( 'Please type your email address.', 'pressapps-modal-login' ) );
		} elseif ( ! is_email( $user_information['user_email'] ) ) {
			$errors->add( 'invalid_email', __( 'The email address isn\'t correct.', 'pressapps-modal-login' ) );
			$user_email = '';
		} elseif ( email_exists( $user_information['user_email'] ) ) {
			$errors->add( 'email_exists', __( 'This email is already registered, please choose another one.', 'pressapps-modal-login' ) );
		}
		/**
		 * password Validation if the User Defined Password Is Allowed
		 */
		if ( $paml->get( 'user_password' ) ) {
			if ( empty( $_REQUEST['password'] ) ) {
				$errors->add( 'empty_password', __( 'Please type your password.', 'pressapps-modal-login' ) );
			} elseif ( strlen( $_REQUEST['password'] ) < 6 ) {
				$errors->add( 'minlength_password', __( 'Password must be 6 character long.', 'pressapps-modal-login' ) );
			} elseif ( $_REQUEST['password'] != $_REQUEST['cpassword'] ) {
				$errors->add( 'unequal_password', __( 'Passwords do not match.', 'pressapps-modal-login' ) );
			}
		}

		$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

		if ( $errors->get_error_code() ) {
			return $errors;
		}
		$user_pass = ( $paml->get( 'user_password' ) ) ? $_REQUEST['password'] : wp_generate_password( 12, false );

		$userdata = array();
		if($paml->get( 'user_fullname' )){
			 $userdata['first_name'] = $sanitized_First_Name;
			 $userdata['last_name'] = $sanitized_Last_Name;
			 $userdata['nickname'] = $sanitized_First_Name." ".$sanitized_Last_Name;
		}
		$userdata['user_login'] = $sanitized_user_login;
		$userdata['user_nicename'] = $sanitized_user_login;
		$userdata['user_email'] = $user_email;
		$userdata['user_pass'] = $user_pass;

		$user_id = wp_insert_user( $userdata ) ;

		if ( ! $user_id ) {
			$errors->add( 'registerfail', __( 'Couldn\'t register you... please contact the site administrator', 'pressapps-modal-login' ) );

			return $errors;
		}

		// update_user_option( $user_id, 'default_password_nag', true, true ); // Set up the Password change nag.
		//
		// if ( $paml->get( 'user_password' ) ) {
		// 	$data['user_login']    = $user_login;
		// 	$data['user_password'] = $user_pass;
		// 	$user_login            = wp_signon( $data, false );
		// }

		$user = get_userdata( $user_id );
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

		$message = sprintf( __( 'New user registration on your site %s:', 'pressapps-modal-login' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'pressapps-modal-login' ), $user->user_login ) . "\r\n\r\n";
		$message .= sprintf( __( 'Email: %s', 'pressapps-modal-login' ), $user->user_email ) . "\r\n";

		@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration', 'pressapps-modal-login' ), $blogname ), $message );

		if ( empty( $user_pass ) ) {
			return;
		}


		$message = sprintf( __( 'Username: %s', 'pressapps-modal-login' ), $user->user_login ) . "\r\n\r\n";
		$message .= __('To set your password, visit the following address:', 'pressapps-modal-login') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=lostpassword") . ">\r\n\r\n";
		$message .= wp_login_url() . "\r\n";

		$new_user_notification_email = array(
			'to'      => $user->user_email,
			/* translators: Password change notification email subject. %s: Site title */
			'subject' => __( '[%s] Your username and password info', 'pressapps-modal-login' ),
			'message' => $message,
			'headers' => ''
		);

		$custom_pattern     = array( '#\%username\%#', '#\%password\%#', '#\%loginlink\%#', '#\%xxxx\%#' );
		$custom_replacement = array( $user->user_login, $user_pass, wp_login_url(), $blogname );
		$custom_subject     = $paml->get( 'registration_email_subject' );
		$custom_body        = $paml->get( 'registration_email_body' );

		if ( $custom_subject != '' ) {
			$new_user_notification_email['subject'] = @preg_replace( $custom_pattern, $custom_replacement, $custom_subject );
		}

		if ( $custom_body != '' ) {
			$new_user_notification_email['message'] = @preg_replace( $custom_pattern, $custom_replacement, $custom_body );
		}

		wp_mail(
			$new_user_notification_email['to'],
			wp_specialchars_decode( sprintf( $new_user_notification_email['subject'], $blogname ) ),
			$new_user_notification_email['message'],
			$new_user_notification_email['headers']
		);

		return $user_id;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Setup password retrieve function */
	/*-----------------------------------------------------------------------------------*/

	function paml_retrieve_password( $user_data ) {
		global $wpdb, $current_site, $wp_hasher;

		$errors = new WP_Error();

		if ( empty( $user_data ) ) {
			$errors->add( 'empty_username', __( 'Please enter a username or e-mail address.', 'pressapps-modal-login' ) );
		} else if ( strpos( $user_data, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $user_data ) );
			if ( empty( $user_data ) ) {
				$errors->add( 'invalid_email', __( 'There is no user registered with that email address.', 'pressapps-modal-login' ) );
			}
		} else {
			$login     = trim( $user_data );
			$user_data = get_user_by( 'login', $login );
		}

		do_action( 'lostpassword_post' );

		if ( $errors->get_error_code() ) {
			return $errors;
		}

		if ( ! $user_data ) {
			$errors->add( 'invalidcombo', __( 'Invalid username or e-mail.', 'pressapps-modal-login' ) );

			return $errors;
		}

		// redefining user_login ensures we return the right case in the email
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		do_action( 'retreive_password', $user_login );  // Misspelled and deprecated
		do_action( 'retrieve_password', $user_login );

		$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

		if ( ! $allow ) {
			return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user', 'pressapps-modal-login' ) );
		} else if ( is_wp_error( $allow ) ) {
			return $allow;
		}

		$key = wp_generate_password( 20, false );

		do_action( 'retrieve_password_key', $user_login, $key );

		// Now insert the key, hashed, into the DB.
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed    = time() . ':' . $wp_hasher->HashPassword( $key );
		$key_saved = $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
		if ( false === $key_saved ) {
			return new WP_Error( 'no_password_key_update', __( 'Could not save password reset key to database.' ) );
		}

		$message = __( 'Someone requested that the password be reset for the following account:', 'pressapps-modal-login' ) . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf( __( 'Username: %s', 'pressapps-modal-login' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'pressapps-modal-login' ) . "\r\n\r\n";
		$message .= __( 'To reset your password, visit the following address:', 'pressapps-modal-login' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";

		if ( is_multisite() ) {
			$blogname = $GLOBALS['current_site']->site_name;
		} else {
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$title   = sprintf( __( '[%s] Password Reset', 'pressapps-modal-login' ), $blogname );
		$title   = apply_filters( 'retrieve_password_title', $title );
		$message = apply_filters( 'retrieve_password_message', $message, $key );

		if ( $message && ! wp_mail( $user_email, $title, $message ) ) {
			$errors->add( 'noemail', __( 'The e-mail could not be sent. Possible reason: your host may have disabled the mail() function.', 'pressapps-modal-login' ) );

			return $errors;

			wp_die();
		}

		return true;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Login / Register / Forgotten forms */
	/*-----------------------------------------------------------------------------------*/

	public function paml_login_form() {
		global $user_ID, $user_identity, $paml;
		$multisite_reg = get_site_option( 'registration' );

		$labels = $paml->get( 'labels' );
		$login_title  = $paml->get( 'login_title' );
		$registration_title  = $paml->get( 'registration_title' );
		$login_subtitle  = $paml->get( 'login_subtitle' );
		$registration_subtitle  = $paml->get( 'registration_subtitle' );
		$font_color = sanitize_text_field( $paml->get( 'font_color' ) );
		?>

		<div id="modal-login" class="ml-modal fade" tabindex="-1" role="dialog" aria-hidden="true">

			<?php do_action( 'before_modal_title' ); ?>

			<?php if ( ! $user_ID ) : ?>

			<div class="modal-login-dialog">
				<div class="ml-content<?php echo ($paml->get( 'align_center' ) ? ' ml-center' : ''); ?><?php echo ($paml->get( 'form_shadow' ) ? ' ml-shadow' : ''); ?>">

					<a href="#" class="ml-close-btn">
						<svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" ratio="1"><line fill="none" stroke="<?php echo $font_color; ?>" stroke-width="1.1" x1="1" y1="1" x2="13" y2="13"></line><line fill="none" stroke="<?php echo $font_color; ?>" stroke-width="1.1" x1="13" y1="1" x2="1" y2="13"></line></svg>
					</a>

					<div class="section-container">
						<div id="paml-spinner"></div>
						<?php // Login Form ?>
						<div id="login" class="modal-login-content">

							<h2>
								<?php
								 if ($login_title != '') {
									 echo $login_title;
								 } else {
									 _e( 'Login', 'pressapps-modal-login' );
								 }  ?>
							</h2>

							<?php echo ( $login_subtitle != '' ? '<p class="ml-subtitle">' . $login_subtitle . '</p>' : ''); ?>

							<?php do_action( 'before_modal_login' ); ?>

							<form action="login" method="post" id="form-login" class="group" name="loginform">

								<?php do_action( 'inside_modal_login_first' ); ?>

								<p class="mluser">
									<label class="field-titles"
									       for="login_user"><?php _e( 'Username', 'pressapps-modal-login' ); ?></label>
									<input type="text" name="log" id="login_user" class="input"
									       placeholder="<?php if ( $labels == 'placeholders' ) {
										       _e( 'Username', 'pressapps-modal-login' );
									       } ?>" value="<?php if ( isset( $user_login ) ) {
										echo esc_attr( $user_login );
									} ?>" size="20"/>
								</p>

								<p class="mlpsw">
									<label class="field-titles"
									       for="login_pass"><?php _e( 'Password', 'pressapps-modal-login' ); ?></label>
									<input type="password" name="pwd" id="login_pass" class="input"
									       placeholder="<?php if ( $labels == 'placeholders' ) {
										       _e( 'Password', 'pressapps-modal-login' );
									       } ?>" value="" size="20"/>
								</p>

								<?php do_action( 'paml_login_form' ); ?>

								<p id="forgetmenot">
									<label class="forgetmenot-label" for="rememberme"><input name="rememberme"
																																					 class="ml-checkbox"
									                                                         type="checkbox"
									                                                         placeholder="<?php if ( $labels == 'placeholders' ) {
										                                                         _e( 'Password', 'pressapps-modal-login' );
									                                                         } ?>" id="rememberme"
									                                                         value="forever"/> <?php _e( 'Remember Me', 'pressapps-modal-login' ); ?>
									</label>
								</p>

								<p class="submit">

									<?php do_action( 'inside_modal_login_submit' ); ?>
									<input type="submit" name="wp-sumbit" id="wp-submit"
									       class="button button-primary button-large"
									       value="<?php _e( 'Log In', 'pressapps-modal-login' ); ?>"/>
									<input type="hidden" name="login" value="true"/>
									<?php wp_nonce_field( 'ajax-form-nonce', 'security-login' ); ?>

								</p><!--[END .submit]-->

								<?php do_action( 'inside_modal_login_last' ); ?>

							</form>
							<!--[END #loginform]-->
						</div>
						<!--[END #login]-->

						<?php // Registration form ?>
						<?php if ( ( get_option( 'users_can_register' ) && ! is_multisite() ) || ( $multisite_reg == 'all' || $multisite_reg == 'blog' || $multisite_reg == 'user' ) ) : ?>
							<div id="register" class="modal-login-content" style="display:none;">

								<h2>
									<?php
									 if ($registration_title != '') {
										 echo $registration_title;
									 } else {
										 _e( 'Register', 'pressapps-modal-login' );
									 }  ?>
								</h2>

								<?php echo ( $registration_subtitle != '' ? '<p class="ml-subtitle">' . $registration_subtitle . '</p>' : ''); ?>

								<?php do_action( 'before_modal_register' ); ?>

								<form action="register" method="post" id="form-register" class="group" name="loginform">

									<?php do_action( 'inside_modal_register_first' ); ?>

									<?php if($paml->get( 'user_fullname' )){ ?>
										<p class="mlfirst">
											<label class="field-titles" for="reg_firstname"><?php _e( 'First Name', 'pressapps-modal-login' ); ?></label>
											<input type="text" name="first_name" id="reg_firstname" class="input" placeholder="<?php if ( $labels == 'placeholders' ) { _e( 'First', 'pressapps-modal-login' ); } ?>"  />
										</p>
										<p class="mllast">
											<label class="field-titles" for="reg_lastname"><?php _e( 'Last Name', 'pressapps-modal-login' ); ?></label>
											<input type="text" name="last_name" id="reg_lastname" class="input" placeholder="<?php if ( $labels == 'placeholders' ) { _e( 'Last', 'pressapps-modal-login' ); } ?>"  />
										</p>
									<?php } ?>

									<p class="mluser">
										<label class="field-titles" for="reg_user"><?php _e( 'Username', 'pressapps-modal-login' ); ?></label>
										<input type="text" name="user_login" id="reg_user" class="input"
										       placeholder="<?php if ( $labels == 'placeholders' ) {
											       _e( 'Username', 'pressapps-modal-login' );
										       } ?>" value="<?php if ( isset( $user_login ) ) {
											echo esc_attr( stripslashes( $user_login ) );
										} ?>" size="20"/>
									</p>

									<p class="mlemail">
										<label class="field-titles"
										       for="reg_email"><?php _e( 'Email', 'pressapps-modal-login' ); ?></label>
										<input type="text" name="user_email" id="reg_email" class="input"
										       placeholder="<?php if ( $labels == 'placeholders' ) {
											       _e( 'Email', 'pressapps-modal-login' );
										       } ?>" value="<?php if ( isset( $user_email ) ) {
											echo esc_attr( stripslashes( $user_email ) );
										} ?>" size="20"/>
									</p>
									<?php
									if ( $paml->get( 'user_password' ) ) {
										?>
										<p class="mlregpsw">
											<label class="field-titles"
											       for="reg_password"><?php _e( 'Password', 'pressapps-modal-login' ); ?></label>
											<input type="password" name="reg_password" id="reg_password" class="input"
											       placeholder="<?php if ( $labels == 'placeholders' ) {
												       _e( 'Password', 'pressapps-modal-login' );
											       } ?>"/>
										</p>
										<p class="mlregpswconf">
											<label class="field-titles"
											       for="reg_cpassword"><?php _e( 'Confirm Password', 'pressapps-modal-login' ); ?></label>
											<input type="password" name="reg_cpassword" id="reg_cpassword" class="input"
											       placeholder="<?php if ( $labels == 'placeholders' ) {
												       _e( 'Confirm Password', 'pressapps-modal-login' );
											       } ?>"/>
										</p>
										<?php
									}
									?>

									<?php if( $paml->get( 'google_captcha_sitekey' ) != "" ){ ?>
										<div class="g-recaptcha" data-sitekey="<?php echo $paml->get( 'google_captcha_sitekey' ); ?>" style="display:block;"></div>
									<?php } ?>

									<?php do_action( 'register_form' ); ?>

									<p class="submit">

										<?php do_action( 'inside_modal_register_submit' ); ?>

										<input type="submit" name="user-submit-register" id="user-submit-register"
										       class="button button-primary button-large"
										       value="<?php esc_attr_e( 'Sign Up', 'pressapps-modal-login' ); ?>"/>
										<input type="hidden" name="register" value="true"/>
										<?php wp_nonce_field( 'ajax-form-nonce', 'security-register' ); ?>

									</p><!--[END .submit]-->

									<?php do_action( 'inside_modal_register_last' ); ?>

								</form>

							</div><!--[END #register]-->
						<?php endif; ?>

						<?php // Forgotten Password ?>
						<div id="forgotten" class="modal-login-content" style="display:none;">

							<h2><?php _e( 'Forgotten Password?', 'pressapps-modal-login' ); ?></h2>

							<?php do_action( 'before_modal_forgotten' ); ?>

							<form action="forgotten" method="post" id="form-forgotten" class="group" name="loginform">

								<?php do_action( 'inside_modal_forgotton_first' ); ?>

								<p class="mlforgt">
									<label class="field-titles"
									       for="forgot_login"><?php _e( 'Username or Email', 'pressapps-modal-login' ); ?></label>
									<input type="text" name="forgot_login" id="forgot_login" class="input"
									       placeholder="<?php if ( $labels == 'placeholders' ) {
										       _e( 'Username or Email', 'pressapps-modal-login' );
									       } ?>" value="<?php if ( isset( $user_login ) ) {
										echo esc_attr( stripslashes( $user_login ) );
									} ?>" size="20"/>
								</p>

								<?php do_action( 'paml_login_form', 'resetpass' ); ?>

								<p class="submit">

									<?php do_action( 'inside_modal_forgotten_submit' ); ?>

									<input type="submit" name="user-submit-forgotten" id="user-submit-forgotten"
									       class="button button-primary button-large"
									       value="<?php esc_attr_e( 'Reset Password', 'pressapps-modal-login' ); ?>">
									<input type="hidden" name="forgotten" value="true"/>
									<?php wp_nonce_field( 'ajax-form-nonce', 'security-forgotten' ); ?>

								</p>

								<?php do_action( 'inside_modal_forgotten_last' ); ?>

							</form>

						</div>
						<!--[END #forgotten]-->
					</div>
					<!--[END .section-container]-->
					<?php endif; ?>

					<?php do_action( 'after_modal_form' ); ?>

				</div>
			</div>
		</div><!--[END #modal-login]-->
	<?php }


	/*-----------------------------------------------------------------------------------*/
	/* Add additional fields to the login_form(). Hooked through 'after_modal_form' */
	/*-----------------------------------------------------------------------------------*/

	public function paml_additional_options() {

		$multisite_reg = get_site_option( 'registration' );

		echo '<div id="additional-settings">';

		if ( ( get_option( 'users_can_register' ) && ! is_multisite() ) || ( $multisite_reg == 'all' || $multisite_reg == 'blog' || $multisite_reg == 'user' ) ) {
			echo '<a href="#register" class="modal-login-nav">' . __( 'Register', 'pressapps-modal-login' ) . '</a> | ';
		}

		echo '<a href="#forgotten" class="modal-login-nav">' . __( 'Lost your password?', 'pressapps-modal-login' ) . '</a>';

		echo '<div class="hide-login"> | <a href="#login" class="modal-login-nav">' . __( 'Back to Login', 'pressapps-modal-login' ) . '</a></div>';

		echo '</div>';
	}


	/*-----------------------------------------------------------------------------------*/
	/* "Back to login form" button */
	/*-----------------------------------------------------------------------------------*/

	public function paml_back_to_login() {
		echo '<a href="#login" class="modal-login-nav">' . __( 'Login', 'pressapps-modal-login' ) . '</a>';
	}


	/*-----------------------------------------------------------------------------------*/
	/* HTML for login link */
	/*-----------------------------------------------------------------------------------*/

	public function modal_login_btn( $login_text = 'Login', $logout_text = 'Logout', $show_admin = true ) {
		// Check if we have an over riding logout redirection set. Other wise, default to the home page.
		global $paml;
		$logout_url = $paml->get( 'logout_redirect_url' );
		if ( isset( $logout_url ) && $logout_url == '' ) {
			$logout_url = home_url();
		}

		// Is the user logged in? If so, serve them the logout button, else we'll show the login button.
		if ( is_user_logged_in() ) {
			$link = '<a href="' . wp_logout_url( esc_url( $logout_url ) ) . '" class="login">' . $logout_text . '</a>';
			if ( $show_admin ) {
				$link .= ' | <a href="' . esc_url( admin_url() ) . '">' . __( 'View Admin', 'pressapps-modal-login' ) . '</a>';
			}
		} else {
			$link = '<a href="#modal-login" class="login" data-toggle="ml-modal">' . $login_text . '</a>';
		}

		$link = apply_filters( 'paml_modal_login_btn', $link, $login_text, $logout_text, $show_admin );

		return $link;
	}


	/*-----------------------------------------------------------------------------------*/
	/* HTML for register link */
	/*-----------------------------------------------------------------------------------*/

	public function modal_register_btn( $register_text = 'Register', $logged_in_text = 'You are alredy logged in' ) {

		if ( ! is_user_logged_in() ) {
			$link = '<a href="#modal-register" class="register" data-toggle="ml-modal">' . $register_text . '</a>';
		} else {
			$link = $logged_in_text;
		}

		return $link;
	}


	/*-----------------------------------------------------------------------------------*/
	/* The shortcode function [wp-modal-login] */
	/*-----------------------------------------------------------------------------------*/

	public function paml_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'login_text'     => __( 'Login', 'pressapps-modal-login' ),
			'logout_text'    => __( 'Logout', 'pressapps-modal-login' ),
			'logged_in_text' => __( 'You are already registered and logged in', 'pressapps-modal-login' ),
			'register_text'  => __( 'Register', 'pressapps-modal-login' ),
			'form'           => 'login',
		), $atts ) );

		global $paml;
		$logout_url = $paml->get( 'logout_redirect_url' );
		if ( isset( $logout_url ) && $logout_url == '' ) {
			$logout_url = home_url();
		}

		if ( 'register' === $form ) {
			if ( ! is_user_logged_in() ) {
				$link = '<a href="#modal-register" class="register" data-toggle="ml-modal">' . $register_text . '</a>';
			} else {
				$link = $logged_in_text;
			}
		} else if ( 'login' === $form ) {
			if ( is_user_logged_in() ) {
				$link = '<a href="' . wp_logout_url( esc_url( $logout_url ) ) . '" class="login" data-toggle="ml-modal">' . sprintf( _x( '%s', 'Shortcode Logout Text', 'pressapps-modal-login' ), sanitize_text_field( $logout_text ) ) . '</a>';
			} else {
				$link = '<a href="#modal-login" class="login" data-toggle="ml-modal">' . sprintf( _x( '%s', 'Shortcode Login Text', 'pressapps-modal-login' ), sanitize_text_field( $login_text ) ) . '</a>';
			}
		}

		return $link;
	}


	/*-----------------------------------------------------------------------------------*/
	/* Menu link metabox generator function */
	/*-----------------------------------------------------------------------------------*/

	public function paml_callback_metabox_modal_link() {

		?>
		<div id="posttype-paml-modal-link" class="posttypediv">
			<div id="tabs-panel-paml-modal-link" class="tabs-panel tabs-panel-active">
				<ul id="paml-modal-link-checklist" class="categorychecklist form-no-clear">
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]"
							       value="-1"> <?php _e( 'Login', 'pressapps-modal-login' ); ?>
							/ <?php _e( 'Logout', 'pressapps-modal-login' ); ?>
						</label>
						<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
						<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]"
						       value="<?php _e( 'Login', 'pressapps-modal-login' ); ?> // <?php _e( 'Logout', 'pressapps-modal-login' ); ?>">
						<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]"
						       value="#pa_modal_login">
					</li>
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]"
							       value="-1"> <?php _e( 'Register', 'pressapps-modal-login' ); ?>
						</label>
						<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
						<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]"
						       value="<?php _e( 'Register', 'pressapps-modal-login' ); ?>">
						<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]"
						       value="#pa_modal_register">
					</li>
				</ul>
			</div>
			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right"
					       value="<?php _e( 'Add to Menu' ); ?>" name="add-post-type-menu-item"
					       id="submit-posttype-paml-modal-link">
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}

	/*-----------------------------------------------------------------------------------*/
	/* Use the right labelfor the modal link */
	/*-----------------------------------------------------------------------------------*/
	public function paml_filter_frontend_modal_link_label( $items ) {
		foreach ( $items as $i => $item ) {
			if ( '#pa_modal_login' === $item->url ) {
				$item_parts = explode( ' // ', $item->title );
				if ( is_user_logged_in() ) {
					$items[ $i ]->title = array_pop( $item_parts );
				} else {
					$items[ $i ]->title = array_shift( $item_parts );
				}
			}
		}

		return $items;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Use the right label for the modal link */
	/*-----------------------------------------------------------------------------------*/
	public function paml_filter_frontend_modal_link_atts( $atts, $item, $args ) {

		// Only apply when URL is #pa_modal_login/#pa_modal_register
		if ( '#pa_modal_login' === $atts['href'] ) {
			// Check if we have an over riding logout redirection set. Other wise, default to the home page.
			global $paml;
			$logout_url = $paml->get( 'logout_redirect_url' );
			if ( isset( $logout_url ) && $logout_url == '' ) {
				$logout_url = home_url();
			}

			// Is the user logged in? If so, serve them the logout button, else we'll show the login button.
			if ( is_user_logged_in() ) {
				$atts['href'] = wp_logout_url( esc_url( $logout_url ) );
			} else {
				$atts['href']        = '#modal-login';
				$atts['data-toggle'] = 'ml-modal';
			}
		} else if ( '#pa_modal_register' === $atts['href'] ) {
			$atts['href']        = '#modal-register';
			$atts['data-toggle'] = 'ml-modal';
		}

		return $atts;
	}

	/*-----------------------------------------------------------------------------------*/
	/* Hide registration link from menus for logged in users */
	/*-----------------------------------------------------------------------------------*/
	public function paml_filter_frontend_modal_link_register_hide( $items ) {
		foreach ( $items as $i => $item ) {
			if ( '#pa_modal_register' === $item->url ) {
				if ( is_user_logged_in() ) {
					unset( $items[ $i ] );
				}
			}
		}

		return $items;
	}

}
