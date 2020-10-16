<?php
/**
 * Add user profile fields
 */

if ( ! function_exists( 'miracle_modify_contact_methods' ) ) {
	function miracle_modify_contact_methods( $profile_fields ) {

		// Add new fields
		$profile_fields['country_code'] = __( 'Country Code', LANGUAGE_ZONE );
		$profile_fields['phone'] = __( 'Phone Number', LANGUAGE_ZONE );
		$profile_fields['birthday'] = __( 'Date of Birth', LANGUAGE_ZONE );
		$profile_fields['address'] = __( 'Address', LANGUAGE_ZONE );
		$profile_fields['city'] = __( 'City', LANGUAGE_ZONE );
		$profile_fields['country'] = __( 'Country', LANGUAGE_ZONE );
		$profile_fields['zip'] = __( 'Zip Code', LANGUAGE_ZONE );
		$profile_fields['author_facebook'] = __( 'Facebook ', LANGUAGE_ZONE );
		$profile_fields['author_twitter'] = __( 'Twitter', LANGUAGE_ZONE );
		$profile_fields['author_linkedin'] = __( 'LinkedIn', LANGUAGE_ZONE );
		$profile_fields['author_dribbble'] = __( 'Dribbble', LANGUAGE_ZONE );
		$profile_fields['author_gplus'] = __( 'Google+', LANGUAGE_ZONE );
		$profile_fields['author_custom'] = __( 'Custom Message', LANGUAGE_ZONE );
		$profile_fields['photo_url'] = __( 'Custom User Photo Url', LANGUAGE_ZONE );

		return $profile_fields;
	}
}
add_filter('user_contactmethods', 'miracle_modify_contact_methods');