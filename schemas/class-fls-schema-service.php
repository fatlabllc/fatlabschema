<?php
/**
 * Service Schema Type.
 *
 * Generates JSON-LD structured data for Service schema type
 * following schema.org/Service specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Service schema class.
 *
 * Handles generation of Service schema markup for services
 * offered by businesses and organizations.
 */
class FLS_Schema_Service {

	/**
	 * Generate Service schema.
	 *
	 * Creates a schema.org/Service structured data object with
	 * service details, pricing, provider, and availability information.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing service information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Service',
		);

		// Required fields.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		if ( ! empty( $data['service_type'] ) ) {
			$schema['serviceType'] = sanitize_text_field( $data['service_type'] );
		}

		// Description (recommended).
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Image.
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// URL - use provided or fall back to post permalink.
		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = esc_url_raw( $data['url'] );
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Provider (organization or person offering the service).
		$provider = self::build_provider( $data );
		if ( ! empty( $provider ) ) {
			$schema['provider'] = $provider;
		}

		// Area served (geographic area where service is provided).
		if ( ! empty( $data['area_served'] ) ) {
			if ( is_array( $data['area_served'] ) ) {
				$schema['areaServed'] = array_map( 'sanitize_text_field', $data['area_served'] );
			} else {
				$schema['areaServed'] = sanitize_text_field( $data['area_served'] );
			}
		}

		// Offers (pricing).
		$offers = self::build_offers( $data );
		if ( ! empty( $offers ) ) {
			$schema['offers'] = $offers;
		}

		// Service output (what the service produces).
		if ( ! empty( $data['service_output'] ) ) {
			$schema['serviceOutput'] = sanitize_text_field( $data['service_output'] );
		}

		// Availability.
		if ( ! empty( $data['hours_available'] ) ) {
			$schema['hoursAvailable'] = array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => ! empty( $data['days_of_week'] ) ? $data['days_of_week'] : 'Monday-Friday',
			);
		}

		// Category.
		if ( ! empty( $data['category'] ) ) {
			$schema['category'] = sanitize_text_field( $data['category'] );
		}

		// Brand.
		if ( ! empty( $data['brand'] ) ) {
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name'  => sanitize_text_field( $data['brand'] ),
			);
		}

		return apply_filters( 'fls_service_schema', $schema, $data, $post_id );
	}

	/**
	 * Build provider object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Provider object or null if no provider data.
	 */
	private static function build_provider( $data ) {
		if ( empty( $data['provider_name'] ) ) {
			return null;
		}

		$provider = array(
			'@type' => 'Organization',
			'name'  => sanitize_text_field( $data['provider_name'] ),
		);

		if ( ! empty( $data['provider_url'] ) ) {
			$provider['url'] = esc_url_raw( $data['provider_url'] );
		}

		if ( ! empty( $data['provider_telephone'] ) ) {
			$provider['telephone'] = sanitize_text_field( $data['provider_telephone'] );
		}

		return $provider;
	}

	/**
	 * Build offers object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Offers object or null if no pricing data.
	 */
	private static function build_offers( $data ) {
		if ( empty( $data['price'] ) && empty( $data['price_range'] ) ) {
			return null;
		}

		$offer = array(
			'@type' => 'Offer',
		);

		// Specific price.
		if ( isset( $data['price'] ) ) {
			$offer['price']         = sanitize_text_field( $data['price'] );
			$offer['priceCurrency'] = ! empty( $data['price_currency'] ) ?
				sanitize_text_field( $data['price_currency'] ) : 'USD';
		}

		// Price range (e.g., $100-$500).
		if ( ! empty( $data['price_range'] ) ) {
			$offer['priceSpecification'] = array(
				'@type'      => 'PriceSpecification',
				'price'      => sanitize_text_field( $data['price_range'] ),
				'priceCurrency' => ! empty( $data['price_currency'] ) ?
					sanitize_text_field( $data['price_currency'] ) : 'USD',
			);
		}

		// Availability.
		if ( ! empty( $data['availability'] ) ) {
			$offer['availability'] = sanitize_text_field( $data['availability'] );
		}

		return $offer;
	}
}
