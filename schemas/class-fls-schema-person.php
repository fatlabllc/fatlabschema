<?php
/**
 * Person Schema Type.
 *
 * Generates JSON-LD structured data for Person schema type
 * following schema.org/Person specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Person schema class.
 *
 * Handles generation of Person schema markup for individual
 * people, authors, staff members, and public figures.
 */
class FLS_Schema_Person {

	/**
	 * Generate Person schema.
	 *
	 * Creates a schema.org/Person structured data object with
	 * biographical information, contact details, and social profiles.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing person information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Person',
		);

		// Required field - name.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		// Image (recommended).
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// Job title/occupation.
		if ( ! empty( $data['job_title'] ) ) {
			$schema['jobTitle'] = sanitize_text_field( $data['job_title'] );
		}

		// Description/bio.
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Contact information.
		if ( ! empty( $data['email'] ) ) {
			$schema['email'] = sanitize_email( $data['email'] );
		}

		if ( ! empty( $data['telephone'] ) ) {
			$schema['telephone'] = sanitize_text_field( $data['telephone'] );
		}

		// URL - use provided or fall back to post permalink.
		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = esc_url_raw( $data['url'] );
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Affiliation (organization person works for).
		if ( ! empty( $data['affiliation'] ) ) {
			$schema['affiliation'] = array(
				'@type' => 'Organization',
				'name'  => sanitize_text_field( $data['affiliation'] ),
			);
		}

		// Member of (organizations person belongs to).
		if ( ! empty( $data['member_of'] ) ) {
			$schema['memberOf'] = array(
				'@type' => 'Organization',
				'name'  => sanitize_text_field( $data['member_of'] ),
			);
		}

		// Alumni of (educational institutions).
		if ( ! empty( $data['alumni_of'] ) ) {
			$schema['alumniOf'] = array(
				'@type' => 'Organization',
				'name'  => sanitize_text_field( $data['alumni_of'] ),
			);
		}

		// Birth date.
		if ( ! empty( $data['birth_date'] ) ) {
			$schema['birthDate'] = sanitize_text_field( $data['birth_date'] );
		}

		// Address.
		$address = self::build_address( $data );
		if ( ! empty( $address ) ) {
			$schema['address'] = $address;
		}

		// Social profiles.
		$same_as = self::build_social_profiles( $data );
		if ( ! empty( $same_as ) ) {
			$schema['sameAs'] = $same_as;
		}

		// Knows language.
		if ( ! empty( $data['knows_language'] ) ) {
			if ( is_array( $data['knows_language'] ) ) {
				$schema['knowsLanguage'] = array_map( 'sanitize_text_field', $data['knows_language'] );
			} else {
				$schema['knowsLanguage'] = sanitize_text_field( $data['knows_language'] );
			}
		}

		return apply_filters( 'fls_person_schema', $schema, $data, $post_id );
	}

	/**
	 * Build postal address object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Postal address object or null if no address data.
	 */
	private static function build_address( $data ) {
		if ( empty( $data['street_address'] ) && empty( $data['address_locality'] ) ) {
			return null;
		}

		$address = array( '@type' => 'PostalAddress' );

		if ( ! empty( $data['street_address'] ) ) {
			$address['streetAddress'] = sanitize_text_field( $data['street_address'] );
		}

		if ( ! empty( $data['address_locality'] ) ) {
			$address['addressLocality'] = sanitize_text_field( $data['address_locality'] );
		}

		if ( ! empty( $data['address_region'] ) ) {
			$address['addressRegion'] = sanitize_text_field( $data['address_region'] );
		}

		if ( ! empty( $data['postal_code'] ) ) {
			$address['postalCode'] = sanitize_text_field( $data['postal_code'] );
		}

		if ( ! empty( $data['address_country'] ) ) {
			$address['addressCountry'] = sanitize_text_field( $data['address_country'] );
		}

		return $address;
	}

	/**
	 * Build social media profile URLs array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array Array of social profile URLs.
	 */
	private static function build_social_profiles( $data ) {
		$same_as = array();

		$social_platforms = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'github' );

		foreach ( $social_platforms as $platform ) {
			if ( ! empty( $data[ $platform ] ) ) {
				$same_as[] = esc_url_raw( $data[ $platform ] );
			}
		}

		return $same_as;
	}
}
