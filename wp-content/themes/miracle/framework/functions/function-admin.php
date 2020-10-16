<?php
/**
 * Function Set used in the backend
 */

if ( !function_exists( 'miracle_get_social_site_names' ) ) :
	function miracle_get_social_site_names() {
		return array(
			'facebook'   => __( 'Facebook', LANGUAGE_ZONE ),
			'twitter'    => __( 'Twitter', LANGUAGE_ZONE ),
			'googleplus' => __( 'Google+', LANGUAGE_ZONE ),
			'linkedin'   => __( 'LinkedIn', LANGUAGE_ZONE ),
			'pinterest'  => __( 'Pinterest', LANGUAGE_ZONE ),
			'youtube'    => __( 'Youtube', LANGUAGE_ZONE ),
			'vimeo'      => __( 'Vimeo', LANGUAGE_ZONE ),
			'dribbble'   => __( 'Dribbble', LANGUAGE_ZONE ),
			'tumblr'     => __( 'Tumblr', LANGUAGE_ZONE ),
			'instagram'   => __( 'Instagram', LANGUAGE_ZONE ),
		);
	}
endif;