<?php
/**
 * Organization Schema Type.
 *
 * Generates JSON-LD structured data for Organization schema type
 * following schema.org/Organization specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Organization schema class.
 *
 * Handles generation of Organization schema markup including
 * NGO, PoliticalOrganization, and other organization subtypes.
 */
class FLS_Schema_Organization {

	/**
	 * Generate Organization schema.
	 *
	 * Creates a schema.org/Organization structured data object with support
	 * for various organization types including NGO and Political organizations.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing organization information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => ! empty( $data['type'] ) ? $data['type'] : 'Organization',
		);

		// Required fields.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = esc_url_raw( $data['url'] );
		}

		// Recommended fields.
		if ( ! empty( $data['logo'] ) ) {
			$schema['logo'] = esc_url_raw( $data['logo'] );
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Address.
		$address = self::build_address( $data );
		if ( ! empty( $address ) ) {
			$schema['address'] = $address;
		}

		// Contact information.
		$contact = self::build_contact_point( $data );
		if ( ! empty( $contact ) ) {
			$schema['contactPoint'] = $contact;
		}

		// Social media profiles.
		$same_as = self::build_social_profiles( $data );
		if ( ! empty( $same_as ) ) {
			$schema['sameAs'] = $same_as;
		}

		// Organization type-specific fields.
		$schema = self::add_type_specific_fields( $schema, $data );

		return apply_filters( 'fls_organization_schema', $schema, $data, $post_id );
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
		// Check if we have any address data.
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
	 * Build contact point object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Contact point object or null if no contact data.
	 */
	private static function build_contact_point( $data ) {
		// Check if we have any contact data.
		if ( empty( $data['telephone'] ) && empty( $data['email'] ) ) {
			return null;
		}

		$contact = array( '@type' => 'ContactPoint' );

		if ( ! empty( $data['telephone'] ) ) {
			$contact['telephone'] = sanitize_text_field( $data['telephone'] );
		}

		if ( ! empty( $data['email'] ) ) {
			$contact['email'] = sanitize_email( $data['email'] );
		}

		if ( ! empty( $data['contact_type'] ) ) {
			$contact['contactType'] = sanitize_text_field( $data['contact_type'] );
		}

		return $contact;
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

		$social_platforms = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube' );

		foreach ( $social_platforms as $platform ) {
			if ( ! empty( $data[ $platform ] ) ) {
				$same_as[] = esc_url_raw( $data[ $platform ] );
			}
		}

		return $same_as;
	}

	/**
	 * Add organization type-specific fields.
	 *
	 * Adds fields specific to NGO, Political Organizations, and other subtypes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schema Current schema array.
	 * @param array $data   Schema data.
	 * @return array Modified schema array.
	 */
	private static function add_type_specific_fields( $schema, $data ) {
		$org_type = $data['type'] ?? 'Organization';

		// NGO and Political Organization specific fields.
		if ( in_array( $org_type, array( 'NGO', 'PoliticalOrganization' ), true ) ) {
			if ( ! empty( $data['mission_statement'] ) ) {
				$schema['description'] = sanitize_textarea_field( $data['mission_statement'] );
			}

			if ( ! empty( $data['founding_date'] ) ) {
				$schema['foundingDate'] = sanitize_text_field( $data['founding_date'] );
			}

			if ( ! empty( $data['nonprofit_status'] ) ) {
				$schema['nonprofitStatus'] = sanitize_text_field( $data['nonprofit_status'] );
			}
		}

		// Add founder if present.
		if ( ! empty( $data['founder'] ) ) {
			$schema['founder'] = array(
				'@type' => 'Person',
				'name'  => sanitize_text_field( $data['founder'] ),
			);
		}

		// Add number of employees if present.
		if ( ! empty( $data['number_of_employees'] ) ) {
			$schema['numberOfEmployees'] = absint( $data['number_of_employees'] );
		}

		return $schema;
	}
}
