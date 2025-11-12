<?php
/**
 * JobPosting Schema Type.
 *
 * Generates JSON-LD structured data for JobPosting schema type
 * following schema.org/JobPosting specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JobPosting schema class.
 *
 * Handles generation of JobPosting schema markup for job listings
 * and employment opportunities.
 */
class FLS_Schema_JobPosting {

	/**
	 * Generate JobPosting schema.
	 *
	 * Creates a schema.org/JobPosting structured data object with
	 * job details, requirements, salary, and application information.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing job posting information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'JobPosting',
		);

		// Required fields.
		if ( ! empty( $data['title'] ) ) {
			$schema['title'] = sanitize_text_field( $data['title'] );
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = wp_kses_post( $data['description'] );
		}

		if ( ! empty( $data['date_posted'] ) ) {
			$schema['datePosted'] = sanitize_text_field( $data['date_posted'] );
		} elseif ( $post_id ) {
			$schema['datePosted'] = get_the_date( 'c', $post_id );
		}

		// Hiring organization (required).
		$hiring_org = self::build_hiring_organization( $data );
		if ( ! empty( $hiring_org ) ) {
			$schema['hiringOrganization'] = $hiring_org;
		}

		// Job location (required).
		$location = self::build_job_location( $data );
		if ( ! empty( $location ) ) {
			// If it's a remote job, add jobLocationType instead of jobLocation.
			if ( isset( $location['jobLocationType'] ) ) {
				$schema['jobLocationType'] = $location['jobLocationType'];
			}

			if ( isset( $location['jobLocation'] ) ) {
				$schema['jobLocation'] = $location['jobLocation'];
			}
		}

		// Employment type (e.g., FULL_TIME, PART_TIME, CONTRACTOR).
		if ( ! empty( $data['employment_type'] ) ) {
			if ( is_array( $data['employment_type'] ) ) {
				$schema['employmentType'] = array_map( 'sanitize_text_field', $data['employment_type'] );
			} else {
				$schema['employmentType'] = sanitize_text_field( $data['employment_type'] );
			}
		}

		// Valid through (expiration date).
		if ( ! empty( $data['valid_through'] ) ) {
			$schema['validThrough'] = sanitize_text_field( $data['valid_through'] );
		}

		// Salary/compensation.
		$salary = self::build_salary( $data );
		if ( ! empty( $salary ) ) {
			$schema['baseSalary'] = $salary;
		}

		// Application URL.
		if ( ! empty( $data['application_url'] ) ) {
			$schema['directApply'] = true;
			$schema['url'] = esc_url_raw( $data['application_url'] );
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Job benefits.
		if ( ! empty( $data['job_benefits'] ) ) {
			$schema['jobBenefits'] = sanitize_textarea_field( $data['job_benefits'] );
		}

		// Education requirements.
		if ( ! empty( $data['education_requirements'] ) ) {
			$schema['educationRequirements'] = sanitize_text_field( $data['education_requirements'] );
		}

		// Experience requirements.
		if ( ! empty( $data['experience_requirements'] ) ) {
			$schema['experienceRequirements'] = sanitize_text_field( $data['experience_requirements'] );
		}

		// Qualifications.
		if ( ! empty( $data['qualifications'] ) ) {
			$schema['qualifications'] = sanitize_textarea_field( $data['qualifications'] );
		}

		// Skills.
		if ( ! empty( $data['skills'] ) ) {
			if ( is_array( $data['skills'] ) ) {
				$schema['skills'] = array_map( 'sanitize_text_field', $data['skills'] );
			} else {
				$schema['skills'] = sanitize_text_field( $data['skills'] );
			}
		}

		return apply_filters( 'fls_jobposting_schema', $schema, $data, $post_id );
	}

	/**
	 * Build hiring organization object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Hiring organization object or null if missing data.
	 */
	private static function build_hiring_organization( $data ) {
		if ( empty( $data['hiring_organization'] ) ) {
			return null;
		}

		$org = array(
			'@type' => 'Organization',
			'name'  => sanitize_text_field( $data['hiring_organization'] ),
		);

		if ( ! empty( $data['organization_url'] ) ) {
			$org['sameAs'] = esc_url_raw( $data['organization_url'] );
		}

		if ( ! empty( $data['organization_logo'] ) ) {
			$org['logo'] = esc_url_raw( $data['organization_logo'] );
		}

		return $org;
	}

	/**
	 * Build job location object.
	 *
	 * Handles both physical and remote job locations.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array Job location data.
	 */
	private static function build_job_location( $data ) {
		$location_data = array();

		if ( ! empty( $data['location_type'] ) && 'remote' === $data['location_type'] ) {
			// Remote job.
			$location_data['jobLocationType'] = 'TELECOMMUTE';
		} else {
			// Physical location.
			$place = array( '@type' => 'Place' );

			// Build address.
			if ( ! empty( $data['address_locality'] ) || ! empty( $data['address_region'] ) || ! empty( $data['address_country'] ) ) {
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

				$place['address'] = $address;
			}

			if ( ! empty( $place['address'] ) ) {
				$location_data['jobLocation'] = $place;
			}
		}

		return $location_data;
	}

	/**
	 * Build salary object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Schema data.
	 * @return array|null Salary object or null if missing data.
	 */
	private static function build_salary( $data ) {
		if ( empty( $data['salary_currency'] ) || empty( $data['salary_value'] ) ) {
			return null;
		}

		$compensation = array(
			'@type'    => 'MonetaryAmount',
			'currency' => sanitize_text_field( $data['salary_currency'] ),
			'value'    => array(
				'@type' => 'QuantitativeValue',
				'value' => sanitize_text_field( $data['salary_value'] ),
			),
		);

		// Add unit (e.g., HOUR, DAY, WEEK, MONTH, YEAR).
		if ( ! empty( $data['salary_unit'] ) ) {
			$compensation['value']['unitText'] = sanitize_text_field( $data['salary_unit'] );
		}

		return $compensation;
	}
}
