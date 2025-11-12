<?php
/**
 * LocalBusiness Schema Type.
 *
 * Generates JSON-LD structured data for LocalBusiness schema type
 * following schema.org/LocalBusiness specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * LocalBusiness schema class.
 *
 * Handles generation of LocalBusiness schema markup including
 * various business subtypes like Restaurant, Store, HealthClinic, etc.
 */
class FLS_Schema_LocalBusiness {

	/**
	 * Generate LocalBusiness schema.
	 *
	 * Creates a schema.org/LocalBusiness structured data object with support
	 * for location, hours, pricing, and other business information.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing business information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => ! empty( $data['business_type'] ) ? $data['business_type'] : 'LocalBusiness',
		);

		// Required fields.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		// Address (required for local business).
		$address = self::build_address( $data );
		if ( ! empty( $address ) ) {
			$schema['address'] = $address;
		}

		// Recommended fields.
		if ( ! empty( $data['telephone'] ) ) {
			$schema['telephone'] = sanitize_text_field( $data['telephone'] );
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// URL - use provided or fall back to post permalink.
		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = esc_url_raw( $data['url'] );
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Opening hours.
		if ( ! empty( $data['opening_hours'] ) ) {
			$schema['openingHours'] = self::format_opening_hours( $data['opening_hours'] );
		}

		// Price range.
		if ( ! empty( $data['price_range'] ) ) {
			$schema['priceRange'] = sanitize_text_field( $data['price_range'] );
		}

		// Geographic coordinates.
		$geo = self::build_geo_coordinates( $data );
		if ( ! empty( $geo ) ) {
			$schema['geo'] = $geo;
		}

		// Additional business fields.
		$schema = self::add_business_fields( $schema, $data );

		return apply_filters( 'fls_localbusiness_schema', $schema, $data, $post_id );
	}

	/**
	 * Build postal address object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array Postal address object.
	 */
	private static function build_address( $data ) {
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
	 * Build geographic coordinates object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null GeoCoordinates object or null if missing data.
	 */
	private static function build_geo_coordinates( $data ) {
		if ( empty( $data['latitude'] ) || empty( $data['longitude'] ) ) {
			return null;
		}

		return array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => floatval( $data['latitude'] ),
			'longitude' => floatval( $data['longitude'] ),
		);
	}

	/**
	 * Format opening hours for schema.org.
	 *
	 * Handles various opening hours formats and ensures schema.org compliance.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $opening_hours Opening hours data (string or array).
	 * @return array|string Opening hours in schema.org format.
	 */
	private static function format_opening_hours( $opening_hours ) {
		if ( is_array( $opening_hours ) ) {
			return array_map( 'sanitize_text_field', $opening_hours );
		}

		return sanitize_text_field( $opening_hours );
	}

	/**
	 * Add additional business-specific fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schema Current schema array.
	 * @param array $data   Schema data.
	 * @return array Modified schema array.
	 */
	private static function add_business_fields( $schema, $data ) {
		// Payment methods accepted.
		if ( ! empty( $data['payment_accepted'] ) ) {
			$schema['paymentAccepted'] = sanitize_text_field( $data['payment_accepted'] );
		}

		// Currencies accepted.
		if ( ! empty( $data['currencies_accepted'] ) ) {
			$schema['currenciesAccepted'] = sanitize_text_field( $data['currencies_accepted'] );
		}

		// Email.
		if ( ! empty( $data['email'] ) ) {
			$schema['email'] = sanitize_email( $data['email'] );
		}

		// Serves cuisine (for restaurants).
		if ( ! empty( $data['serves_cuisine'] ) ) {
			$schema['servesCuisine'] = sanitize_text_field( $data['serves_cuisine'] );
		}

		// Menu URL (for food establishments).
		if ( ! empty( $data['menu_url'] ) ) {
			$schema['hasMenu'] = esc_url_raw( $data['menu_url'] );
		}

		// Area served.
		if ( ! empty( $data['area_served'] ) ) {
			$schema['areaServed'] = sanitize_text_field( $data['area_served'] );
		}

		// Aggregate rating.
		if ( ! empty( $data['rating_value'] ) && ! empty( $data['rating_count'] ) ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => floatval( $data['rating_value'] ),
				'ratingCount' => absint( $data['rating_count'] ),
			);

			if ( ! empty( $data['best_rating'] ) ) {
				$schema['aggregateRating']['bestRating'] = floatval( $data['best_rating'] );
			}
		}

		// Social profiles.
		$same_as = self::build_social_profiles( $data );
		if ( ! empty( $same_as ) ) {
			$schema['sameAs'] = $same_as;
		}

		return $schema;
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

		$social_platforms = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'yelp' );

		foreach ( $social_platforms as $platform ) {
			if ( ! empty( $data[ $platform ] ) ) {
				$same_as[] = esc_url_raw( $data[ $platform ] );
			}
		}

		return $same_as;
	}
}
