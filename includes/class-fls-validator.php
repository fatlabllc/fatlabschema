<?php
/**
 * Schema validation functionality.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validator class.
 */
class FLS_Validator {

	/**
	 * Validation errors.
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Validation warnings.
	 *
	 * @var array
	 */
	private $warnings = array();

	/**
	 * Validate schema data.
	 *
	 * @param string $schema_type Schema type.
	 * @param array  $data Schema data.
	 * @return bool
	 */
	public function validate_schema_data( $schema_type, $data ) {
		$this->errors = array();
		$this->warnings = array();

		if ( empty( $schema_type ) ) {
			$this->errors[] = __( 'Schema type is required.', 'fatlabschema' );
			return false;
		}

		$method = 'validate_' . $schema_type;

		if ( method_exists( $this, $method ) ) {
			return $this->$method( $data );
		}

		// Default validation
		return $this->validate_basic_fields( $data );
	}

	/**
	 * Validate basic fields (name, description).
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_basic_fields( $data ) {
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'Name is required.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate LocalBusiness schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_localbusiness( $data ) {
		// Required fields
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'Business name is required.', 'fatlabschema' );
		}

		if ( empty( $data['street_address'] ) ) {
			$this->errors[] = __( 'Street address is required.', 'fatlabschema' );
		}

		if ( empty( $data['address_locality'] ) ) {
			$this->errors[] = __( 'City is required.', 'fatlabschema' );
		}

		// Recommended fields
		if ( empty( $data['telephone'] ) ) {
			$this->warnings[] = __( 'Telephone number is recommended for LocalBusiness schema.', 'fatlabschema' );
		}

		if ( empty( $data['opening_hours'] ) ) {
			$this->warnings[] = __( 'Business hours are recommended for better visibility.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate Event schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_event( $data ) {
		// Required fields
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'Event name is required.', 'fatlabschema' );
		}

		if ( empty( $data['start_date'] ) ) {
			$this->errors[] = __( 'Start date is required.', 'fatlabschema' );
		}

		// Location or virtual event
		if ( empty( $data['location_type'] ) ) {
			$this->errors[] = __( 'Location type (physical or virtual) is required.', 'fatlabschema' );
		} elseif ( 'physical' === $data['location_type'] ) {
			if ( empty( $data['location_name'] ) && empty( $data['street_address'] ) ) {
				$this->errors[] = __( 'Location name or address is required for physical events.', 'fatlabschema' );
			}
		} elseif ( 'virtual' === $data['location_type'] ) {
			if ( empty( $data['virtual_url'] ) ) {
				$this->errors[] = __( 'Virtual event URL is required for online events.', 'fatlabschema' );
			}
		}

		// Recommended fields
		if ( empty( $data['description'] ) ) {
			$this->warnings[] = __( 'Event description is recommended.', 'fatlabschema' );
		}

		if ( empty( $data['image'] ) ) {
			$this->warnings[] = __( 'Event image is recommended for better visibility.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate FAQPage schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_faqpage( $data ) {
		if ( empty( $data['questions'] ) || ! is_array( $data['questions'] ) ) {
			$this->errors[] = __( 'At least one question is required.', 'fatlabschema' );
			return false;
		}

		foreach ( $data['questions'] as $index => $qa ) {
			if ( empty( $qa['question'] ) ) {
				$this->errors[] = sprintf(
					/* translators: %d: Question number */
					__( 'Question #%d: Question text is required.', 'fatlabschema' ),
					$index + 1
				);
			}

			if ( empty( $qa['answer'] ) ) {
				$this->errors[] = sprintf(
					/* translators: %d: Question number */
					__( 'Question #%d: Answer text is required.', 'fatlabschema' ),
					$index + 1
				);
			}
		}

		return empty( $this->errors );
	}

	/**
	 * Validate Article schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_article( $data ) {
		// Required fields
		if ( empty( $data['headline'] ) ) {
			$this->errors[] = __( 'Headline is required.', 'fatlabschema' );
		}

		if ( empty( $data['author_name'] ) ) {
			$this->errors[] = __( 'Author name is required.', 'fatlabschema' );
		}

		if ( empty( $data['datePublished'] ) ) {
			$this->errors[] = __( 'Published date is required.', 'fatlabschema' );
		}

		// Recommended fields
		if ( empty( $data['image'] ) ) {
			$this->warnings[] = __( 'Article image is recommended for rich results.', 'fatlabschema' );
		}

		if ( empty( $data['publisher_name'] ) ) {
			$this->warnings[] = __( 'Publisher information is recommended. Configure Organization schema.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate Service schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_service( $data ) {
		// Required fields
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'Service name is required.', 'fatlabschema' );
		}

		if ( empty( $data['service_type'] ) ) {
			$this->errors[] = __( 'Service type is required.', 'fatlabschema' );
		}

		// Recommended fields
		if ( empty( $data['provider_name'] ) ) {
			$this->warnings[] = __( 'Service provider information is recommended.', 'fatlabschema' );
		}

		if ( empty( $data['description'] ) ) {
			$this->warnings[] = __( 'Service description is recommended.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate HowTo schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_howto( $data ) {
		// Required fields
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'HowTo name is required.', 'fatlabschema' );
		}

		if ( empty( $data['steps'] ) || ! is_array( $data['steps'] ) ) {
			$this->errors[] = __( 'At least one step is required.', 'fatlabschema' );
			return false;
		}

		foreach ( $data['steps'] as $index => $step ) {
			if ( empty( $step['text'] ) && empty( $step['name'] ) ) {
				$this->errors[] = sprintf(
					/* translators: %d: Step number */
					__( 'Step #%d: Step text or name is required.', 'fatlabschema' ),
					$index + 1
				);
			}
		}

		// Recommended fields
		if ( empty( $data['description'] ) ) {
			$this->warnings[] = __( 'Description is recommended for HowTo schema.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate Person schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_person( $data ) {
		// Required fields
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'Person name is required.', 'fatlabschema' );
		}

		// Recommended fields
		if ( empty( $data['job_title'] ) ) {
			$this->warnings[] = __( 'Job title is recommended for Person schema.', 'fatlabschema' );
		}

		if ( empty( $data['image'] ) ) {
			$this->warnings[] = __( 'Photo is recommended for Person schema.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate JobPosting schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_jobposting( $data ) {
		// Required fields
		if ( empty( $data['title'] ) ) {
			$this->errors[] = __( 'Job title is required.', 'fatlabschema' );
		}

		if ( empty( $data['description'] ) ) {
			$this->errors[] = __( 'Job description is required.', 'fatlabschema' );
		}

		if ( empty( $data['date_posted'] ) ) {
			$this->errors[] = __( 'Date posted is required.', 'fatlabschema' );
		}

		if ( empty( $data['hiring_organization'] ) ) {
			$this->errors[] = __( 'Hiring organization name is required.', 'fatlabschema' );
		}

		// Location validation
		if ( empty( $data['location_type'] ) ) {
			$this->errors[] = __( 'Job location type is required.', 'fatlabschema' );
		} elseif ( 'physical' === $data['location_type'] ) {
			if ( empty( $data['address_locality'] ) ) {
				$this->errors[] = __( 'City is required for physical job locations.', 'fatlabschema' );
			}
			if ( empty( $data['address_region'] ) ) {
				$this->errors[] = __( 'State/Region is required for physical job locations.', 'fatlabschema' );
			}
			if ( empty( $data['address_country'] ) ) {
				$this->errors[] = __( 'Country is required for physical job locations.', 'fatlabschema' );
			}
		}

		// Recommended fields
		if ( empty( $data['employment_type'] ) ) {
			$this->warnings[] = __( 'Employment type is recommended for better job visibility.', 'fatlabschema' );
		}

		if ( empty( $data['salary_value'] ) ) {
			$this->warnings[] = __( 'Salary information is strongly recommended for attracting candidates.', 'fatlabschema' );
		}

		if ( empty( $data['valid_through'] ) ) {
			$this->warnings[] = __( 'Expiration date is recommended for job postings.', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Validate Course schema.
	 *
	 * @param array $data Schema data.
	 * @return bool
	 */
	private function validate_course( $data ) {
		// Required fields
		if ( empty( $data['name'] ) ) {
			$this->errors[] = __( 'Course name is required.', 'fatlabschema' );
		}

		if ( empty( $data['description'] ) ) {
			$this->errors[] = __( 'Course description is required.', 'fatlabschema' );
		}

		if ( empty( $data['provider_name'] ) ) {
			$this->errors[] = __( 'Course provider name is required.', 'fatlabschema' );
		}

		// Recommended fields
		if ( empty( $data['course_mode'] ) ) {
			$this->warnings[] = __( 'Course format (online/in-person) is recommended.', 'fatlabschema' );
		}

		if ( empty( $data['image'] ) ) {
			$this->warnings[] = __( 'Course image is recommended for better visibility.', 'fatlabschema' );
		}

		if ( ! isset( $data['price'] ) || '' === $data['price'] ) {
			$this->warnings[] = __( 'Pricing information is recommended (set to 0 for free courses).', 'fatlabschema' );
		}

		return empty( $this->errors );
	}

	/**
	 * Get validation errors.
	 *
	 * @return array
	 */
	public function get_validation_errors() {
		return $this->errors;
	}

	/**
	 * Get validation warnings.
	 *
	 * @return array
	 */
	public function get_validation_warnings() {
		return $this->warnings;
	}

	/**
	 * Validate URL format.
	 *
	 * @param string $url URL to validate.
	 * @return bool
	 */
	public static function is_valid_url( $url ) {
		return filter_var( $url, FILTER_VALIDATE_URL ) !== false;
	}

	/**
	 * Validate email format.
	 *
	 * @param string $email Email to validate.
	 * @return bool
	 */
	public static function is_valid_email( $email ) {
		return is_email( $email ) !== false;
	}

	/**
	 * Validate date format.
	 *
	 * @param string $date Date to validate.
	 * @param string $format Expected format.
	 * @return bool
	 */
	public static function is_valid_date( $date, $format = 'Y-m-d' ) {
		$d = DateTime::createFromFormat( $format, $date );
		return $d && $d->format( $format ) === $date;
	}
}
