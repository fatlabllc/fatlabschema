<?php
/**
 * Event Schema Type.
 *
 * Generates JSON-LD structured data for Event schema type
 * following schema.org/Event specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Event schema class.
 *
 * Handles generation of Event schema markup for both
 * physical and virtual events.
 */
class FLS_Schema_Event {

	/**
	 * Generate Event schema.
	 *
	 * Creates a schema.org/Event structured data object with support
	 * for physical locations, virtual events, and hybrid formats.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing event information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Event',
			'eventStatus' => 'https://schema.org/EventScheduled',
		);

		// Required fields.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		// Start date (required).
		if ( ! empty( $data['start_date'] ) ) {
			$schema['startDate'] = self::format_datetime(
				$data['start_date'],
				$data['start_time'] ?? ''
			);
		}

		// End date.
		if ( ! empty( $data['end_date'] ) ) {
			$schema['endDate'] = self::format_datetime(
				$data['end_date'],
				$data['end_time'] ?? ''
			);
		}

		// Description.
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Image.
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// Location (required) - can be physical or virtual.
		$location = self::build_location( $data );
		if ( ! empty( $location ) ) {
			$schema = array_merge( $schema, $location );
		}

		// Organizer.
		$organizer = self::build_organizer( $data );
		if ( ! empty( $organizer ) ) {
			$schema['organizer'] = $organizer;
		}

		// Offers (tickets/registration).
		$offers = self::build_offers( $data );
		if ( ! empty( $offers ) ) {
			$schema['offers'] = $offers;
		}

		// Performer.
		if ( ! empty( $data['performer_name'] ) ) {
			$schema['performer'] = array(
				'@type' => 'Person',
				'name'  => sanitize_text_field( $data['performer_name'] ),
			);
		}

		return apply_filters( 'fls_event_schema', $schema, $data, $post_id );
	}

	/**
	 * Build location object.
	 *
	 * Handles both physical and virtual event locations.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array Location data with attendance mode.
	 */
	private static function build_location( $data ) {
		$location_data = array();

		if ( empty( $data['location_type'] ) ) {
			return $location_data;
		}

		if ( 'virtual' === $data['location_type'] ) {
			// Virtual event.
			$location_data['eventAttendanceMode'] = 'https://schema.org/OnlineEventAttendanceMode';

			if ( ! empty( $data['virtual_url'] ) ) {
				$location_data['location'] = array(
					'@type' => 'VirtualLocation',
					'url'   => esc_url_raw( $data['virtual_url'] ),
				);
			}
		} else {
			// Physical event.
			$location_data['eventAttendanceMode'] = 'https://schema.org/OfflineEventAttendanceMode';

			$place = array( '@type' => 'Place' );

			if ( ! empty( $data['location_name'] ) ) {
				$place['name'] = sanitize_text_field( $data['location_name'] );
			}

			// Build address.
			$address = self::build_address( $data );
			if ( ! empty( $address ) ) {
				$place['address'] = $address;
			}

			$location_data['location'] = $place;
		}

		return $location_data;
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
	 * Build organizer object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Organizer object or null if no organizer data.
	 */
	private static function build_organizer( $data ) {
		if ( empty( $data['organizer_name'] ) ) {
			return null;
		}

		$organizer = array(
			'@type' => 'Organization',
			'name'  => sanitize_text_field( $data['organizer_name'] ),
		);

		if ( ! empty( $data['organizer_url'] ) ) {
			$organizer['url'] = esc_url_raw( $data['organizer_url'] );
		}

		return $organizer;
	}

	/**
	 * Build offers object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Offers object or null if no offers data.
	 */
	private static function build_offers( $data ) {
		if ( empty( $data['offers_url'] ) ) {
			return null;
		}

		$offer = array(
			'@type'        => 'Offer',
			'url'          => esc_url_raw( $data['offers_url'] ),
			'availability' => 'https://schema.org/InStock',
		);

		if ( isset( $data['offers_price'] ) ) {
			$offer['price']         = sanitize_text_field( $data['offers_price'] );
			$offer['priceCurrency'] = ! empty( $data['offers_currency'] ) ?
				sanitize_text_field( $data['offers_currency'] ) : 'USD';
		}

		if ( ! empty( $data['offers_valid_from'] ) ) {
			$offer['validFrom'] = sanitize_text_field( $data['offers_valid_from'] );
		}

		return $offer;
	}

	/**
	 * Format datetime for schema.org.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date Date string.
	 * @param string $time Time string (optional).
	 * @return string Formatted datetime in ISO 8601 format.
	 */
	private static function format_datetime( $date, $time = '' ) {
		if ( empty( $time ) ) {
			return gmdate( 'Y-m-d', strtotime( $date ) );
		}

		return gmdate( 'c', strtotime( $date . ' ' . $time ) );
	}
}
