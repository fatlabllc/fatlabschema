<?php
/**
 * Course Schema Type.
 *
 * Generates JSON-LD structured data for Course schema type
 * following schema.org/Course specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Course schema class.
 *
 * Handles generation of Course schema markup for educational
 * courses, training programs, and online learning content.
 */
class FLS_Schema_Course {

	/**
	 * Generate Course schema.
	 *
	 * Creates a schema.org/Course structured data object with
	 * course details, provider, instructor, and pricing information.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing course information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Course',
		);

		// Required fields.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Provider (required).
		$provider = self::build_provider( $data );
		if ( ! empty( $provider ) ) {
			$schema['provider'] = $provider;
		}

		// Course code.
		if ( ! empty( $data['course_code'] ) ) {
			$schema['courseCode'] = sanitize_text_field( $data['course_code'] );
		}

		// URL - use provided or fall back to post permalink.
		if ( ! empty( $data['course_url'] ) ) {
			$schema['url'] = esc_url_raw( $data['course_url'] );
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Image.
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// Course mode (online, in-person, blended).
		if ( ! empty( $data['course_mode'] ) ) {
			$schema['courseMode'] = sanitize_text_field( $data['course_mode'] );
		}

		// Offers (pricing).
		$offers = self::build_offers( $data );
		if ( ! empty( $offers ) ) {
			$schema['offers'] = $offers;
		}

		// Course prerequisites.
		if ( ! empty( $data['course_prerequisites'] ) ) {
			if ( is_array( $data['course_prerequisites'] ) ) {
				$schema['coursePrerequisites'] = array_map( 'sanitize_text_field', $data['course_prerequisites'] );
			} else {
				$schema['coursePrerequisites'] = sanitize_textarea_field( $data['course_prerequisites'] );
			}
		}

		// Educational level.
		if ( ! empty( $data['educational_level'] ) ) {
			$schema['educationalLevel'] = sanitize_text_field( $data['educational_level'] );
		}

		// Time required (e.g., P6M for 6 months).
		if ( ! empty( $data['time_required'] ) ) {
			$schema['timeRequired'] = sanitize_text_field( $data['time_required'] );
		}

		// Instructor.
		$instructor = self::build_instructor( $data );
		if ( ! empty( $instructor ) ) {
			$schema['instructor'] = $instructor;
		}

		// In language.
		if ( ! empty( $data['in_language'] ) ) {
			$schema['inLanguage'] = sanitize_text_field( $data['in_language'] );
		}

		// Availability starts/ends.
		if ( ! empty( $data['availability_starts'] ) ) {
			$schema['hasCourseInstance'] = array(
				'@type'      => 'CourseInstance',
				'courseMode' => ! empty( $data['course_mode'] ) ? sanitize_text_field( $data['course_mode'] ) : 'Online',
			);

			if ( ! empty( $data['availability_starts'] ) ) {
				$schema['hasCourseInstance']['startDate'] = sanitize_text_field( $data['availability_starts'] );
			}

			if ( ! empty( $data['availability_ends'] ) ) {
				$schema['hasCourseInstance']['endDate'] = sanitize_text_field( $data['availability_ends'] );
			}
		}

		// Course workload (e.g., 5 hours per week).
		if ( ! empty( $data['course_workload'] ) ) {
			$schema['courseWorkload'] = sanitize_text_field( $data['course_workload'] );
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

		return apply_filters( 'fls_course_schema', $schema, $data, $post_id );
	}

	/**
	 * Build provider object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Provider object or null if missing data.
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

		return $provider;
	}

	/**
	 * Build instructor object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Instructor object or null if missing data.
	 */
	private static function build_instructor( $data ) {
		if ( empty( $data['instructor_name'] ) ) {
			return null;
		}

		$instructor = array(
			'@type' => 'Person',
			'name'  => sanitize_text_field( $data['instructor_name'] ),
		);

		if ( ! empty( $data['instructor_url'] ) ) {
			$instructor['url'] = esc_url_raw( $data['instructor_url'] );
		}

		if ( ! empty( $data['instructor_description'] ) ) {
			$instructor['description'] = sanitize_textarea_field( $data['instructor_description'] );
		}

		return $instructor;
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
		if ( ! isset( $data['price'] ) ) {
			return null;
		}

		$offer = array(
			'@type'         => 'Offer',
			'price'         => sanitize_text_field( $data['price'] ),
			'priceCurrency' => ! empty( $data['price_currency'] ) ?
				sanitize_text_field( $data['price_currency'] ) : 'USD',
		);

		// Availability.
		if ( ! empty( $data['availability'] ) ) {
			$offer['availability'] = sanitize_text_field( $data['availability'] );
		} else {
			$offer['availability'] = 'https://schema.org/InStock';
		}

		// Valid from/through dates.
		if ( ! empty( $data['valid_from'] ) ) {
			$offer['validFrom'] = sanitize_text_field( $data['valid_from'] );
		}

		if ( ! empty( $data['valid_through'] ) ) {
			$offer['validThrough'] = sanitize_text_field( $data['valid_through'] );
		}

		// URL for enrollment.
		if ( ! empty( $data['enrollment_url'] ) ) {
			$offer['url'] = esc_url_raw( $data['enrollment_url'] );
		}

		return $offer;
	}
}
