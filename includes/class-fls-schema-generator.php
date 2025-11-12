<?php
/**
 * Schema generator for JSON-LD output.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema generator class.
 */
class FLS_Schema_Generator {

	/**
	 * Generate JSON-LD schema.
	 *
	 * @param string $schema_type Schema type.
	 * @param array  $data Schema data.
	 * @param int    $post_id Post ID (optional).
	 * @return array|null Schema array or null on error.
	 */
	public static function generate_json_ld( $schema_type, $data, $post_id = null ) {
		if ( empty( $schema_type ) || empty( $data ) ) {
			return null;
		}

		$method = 'generate_' . $schema_type . '_schema';

		if ( method_exists( __CLASS__, $method ) ) {
			$schema = self::$method( $data, $post_id );
			return apply_filters( 'fls_schema_output', $schema, $schema_type, $data, $post_id );
		}

		return null;
	}

	/**
	 * Generate Organization schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID (unused for organization).
	 * @return array
	 */
	public static function generate_organization_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => ! empty( $data['type'] ) ? $data['type'] : 'Organization',
		);

		// Required fields
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = $data['url'];
		}

		// Recommended fields
		if ( ! empty( $data['logo'] ) ) {
			$schema['logo'] = $data['logo'];
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		// Address
		if ( ! empty( $data['street_address'] ) || ! empty( $data['address_locality'] ) ) {
			$address = array( '@type' => 'PostalAddress' );

			if ( ! empty( $data['street_address'] ) ) {
				$address['streetAddress'] = $data['street_address'];
			}

			if ( ! empty( $data['address_locality'] ) ) {
				$address['addressLocality'] = $data['address_locality'];
			}

			if ( ! empty( $data['address_region'] ) ) {
				$address['addressRegion'] = $data['address_region'];
			}

			if ( ! empty( $data['postal_code'] ) ) {
				$address['postalCode'] = $data['postal_code'];
			}

			if ( ! empty( $data['address_country'] ) ) {
				$address['addressCountry'] = $data['address_country'];
			}

			$schema['address'] = $address;
		}

		// Contact info
		if ( ! empty( $data['telephone'] ) || ! empty( $data['email'] ) ) {
			$contact = array( '@type' => 'ContactPoint' );

			if ( ! empty( $data['telephone'] ) ) {
				$contact['telephone'] = $data['telephone'];
			}

			if ( ! empty( $data['email'] ) ) {
				$contact['email'] = $data['email'];
			}

			$schema['contactPoint'] = $contact;
		}

		// Social profiles
		$same_as = array();
		if ( ! empty( $data['facebook'] ) ) {
			$same_as[] = $data['facebook'];
		}

		if ( ! empty( $data['twitter'] ) ) {
			$same_as[] = $data['twitter'];
		}

		if ( ! empty( $data['linkedin'] ) ) {
			$same_as[] = $data['linkedin'];
		}

		if ( ! empty( $data['instagram'] ) ) {
			$same_as[] = $data['instagram'];
		}

		if ( ! empty( $same_as ) ) {
			$schema['sameAs'] = $same_as;
		}

		// NGO-specific
		if ( 'NGO' === $data['type'] || 'PoliticalOrganization' === $data['type'] ) {
			if ( ! empty( $data['mission_statement'] ) ) {
				$schema['description'] = $data['mission_statement'];
			}

			if ( ! empty( $data['founding_date'] ) ) {
				$schema['foundingDate'] = $data['founding_date'];
			}
		}

		return $schema;
	}

	/**
	 * Generate LocalBusiness schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_localbusiness_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => ! empty( $data['business_type'] ) ? $data['business_type'] : 'LocalBusiness',
		);

		// Required fields
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		// Address (required)
		$address = array( '@type' => 'PostalAddress' );

		if ( ! empty( $data['street_address'] ) ) {
			$address['streetAddress'] = $data['street_address'];
		}

		if ( ! empty( $data['address_locality'] ) ) {
			$address['addressLocality'] = $data['address_locality'];
		}

		if ( ! empty( $data['address_region'] ) ) {
			$address['addressRegion'] = $data['address_region'];
		}

		if ( ! empty( $data['postal_code'] ) ) {
			$address['postalCode'] = $data['postal_code'];
		}

		if ( ! empty( $data['address_country'] ) ) {
			$address['addressCountry'] = $data['address_country'];
		}

		$schema['address'] = $address;

		// Recommended fields
		if ( ! empty( $data['telephone'] ) ) {
			$schema['telephone'] = $data['telephone'];
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = $data['url'];
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Opening hours
		if ( ! empty( $data['opening_hours'] ) ) {
			$schema['openingHours'] = $data['opening_hours'];
		}

		// Price range
		if ( ! empty( $data['price_range'] ) ) {
			$schema['priceRange'] = $data['price_range'];
		}

		// Geo coordinates
		if ( ! empty( $data['latitude'] ) && ! empty( $data['longitude'] ) ) {
			$schema['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => $data['latitude'],
				'longitude' => $data['longitude'],
			);
		}

		return $schema;
	}

	/**
	 * Generate Event schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_event_schema( $data, $post_id = null ) {
		$schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Event',
			'eventStatus' => 'https://schema.org/EventScheduled',
		);

		// Required fields
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		if ( ! empty( $data['start_date'] ) ) {
			$schema['startDate'] = self::format_datetime( $data['start_date'], $data['start_time'] ?? '' );
		}

		// End date
		if ( ! empty( $data['end_date'] ) ) {
			$schema['endDate'] = self::format_datetime( $data['end_date'], $data['end_time'] ?? '' );
		}

		// Location
		if ( ! empty( $data['location_type'] ) ) {
			if ( 'virtual' === $data['location_type'] ) {
				$schema['eventAttendanceMode'] = 'https://schema.org/OnlineEventAttendanceMode';

				if ( ! empty( $data['virtual_url'] ) ) {
					$schema['location'] = array(
						'@type' => 'VirtualLocation',
						'url'   => $data['virtual_url'],
					);
				}
			} else {
				$schema['eventAttendanceMode'] = 'https://schema.org/OfflineEventAttendanceMode';

				$location = array( '@type' => 'Place' );

				if ( ! empty( $data['location_name'] ) ) {
					$location['name'] = $data['location_name'];
				}

				// Address for physical location
				if ( ! empty( $data['street_address'] ) || ! empty( $data['address_locality'] ) ) {
					$address = array( '@type' => 'PostalAddress' );

					if ( ! empty( $data['street_address'] ) ) {
						$address['streetAddress'] = $data['street_address'];
					}

					if ( ! empty( $data['address_locality'] ) ) {
						$address['addressLocality'] = $data['address_locality'];
					}

					if ( ! empty( $data['address_region'] ) ) {
						$address['addressRegion'] = $data['address_region'];
					}

					if ( ! empty( $data['postal_code'] ) ) {
						$address['postalCode'] = $data['postal_code'];
					}

					if ( ! empty( $data['address_country'] ) ) {
						$address['addressCountry'] = $data['address_country'];
					}

					$location['address'] = $address;
				}

				$schema['location'] = $location;
			}
		}

		// Recommended fields
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		// Organizer
		if ( ! empty( $data['organizer_name'] ) ) {
			$organizer = array(
				'@type' => 'Organization',
				'name'  => $data['organizer_name'],
			);

			if ( ! empty( $data['organizer_url'] ) ) {
				$organizer['url'] = $data['organizer_url'];
			}

			$schema['organizer'] = $organizer;
		}

		// Offers (tickets/registration)
		if ( ! empty( $data['offers_url'] ) ) {
			$offer = array(
				'@type'        => 'Offer',
				'url'          => $data['offers_url'],
				'availability' => 'https://schema.org/InStock',
			);

			if ( isset( $data['offers_price'] ) ) {
				$offer['price'] = $data['offers_price'];
				$offer['priceCurrency'] = ! empty( $data['offers_currency'] ) ? $data['offers_currency'] : 'USD';
			}

			$schema['offers'] = $offer;
		}

		return $schema;
	}

	/**
	 * Generate FAQPage schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_faqpage_schema( $data, $post_id = null ) {
		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array(),
		);

		if ( ! empty( $data['questions'] ) && is_array( $data['questions'] ) ) {
			foreach ( $data['questions'] as $qa ) {
				if ( empty( $qa['question'] ) || empty( $qa['answer'] ) ) {
					continue;
				}

				$schema['mainEntity'][] = array(
					'@type'          => 'Question',
					'name'           => $qa['question'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $qa['answer'],
					),
				);
			}
		}

		return $schema;
	}

	/**
	 * Generate Article schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_article_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => ! empty( $data['article_type'] ) ? $data['article_type'] : 'Article',
		);

		// Required fields
		if ( ! empty( $data['headline'] ) ) {
			$schema['headline'] = $data['headline'];
		}

		if ( ! empty( $data['author_name'] ) ) {
			$schema['author'] = array(
				'@type' => 'Person',
				'name'  => $data['author_name'],
			);
		}

		if ( ! empty( $data['datePublished'] ) ) {
			$schema['datePublished'] = $data['datePublished'];
		}

		// Recommended fields
		if ( ! empty( $data['dateModified'] ) ) {
			$schema['dateModified'] = $data['dateModified'];
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		// Publisher
		if ( ! empty( $data['publisher_name'] ) ) {
			$publisher = array(
				'@type' => 'Organization',
				'name'  => $data['publisher_name'],
			);

			if ( ! empty( $data['publisher_logo'] ) ) {
				$publisher['logo'] = array(
					'@type' => 'ImageObject',
					'url'   => $data['publisher_logo'],
				);
			}

			$schema['publisher'] = $publisher;
		}

		return $schema;
	}

	/**
	 * Generate Service schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_service_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Service',
		);

		// Required fields
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		if ( ! empty( $data['service_type'] ) ) {
			$schema['serviceType'] = $data['service_type'];
		}

		// Recommended fields
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = $data['url'];
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Provider
		if ( ! empty( $data['provider_name'] ) ) {
			$schema['provider'] = array(
				'@type' => 'Organization',
				'name'  => $data['provider_name'],
			);
		}

		// Area served
		if ( ! empty( $data['area_served'] ) ) {
			$schema['areaServed'] = $data['area_served'];
		}

		// Pricing
		if ( ! empty( $data['price'] ) ) {
			$offer = array(
				'@type'         => 'Offer',
				'price'         => $data['price'],
				'priceCurrency' => ! empty( $data['price_currency'] ) ? $data['price_currency'] : 'USD',
			);
			$schema['offers'] = $offer;
		}

		return $schema;
	}

	/**
	 * Generate HowTo schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_howto_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'HowTo',
			'step'     => array(),
		);

		// Required fields
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		// Recommended fields
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		if ( ! empty( $data['total_time'] ) ) {
			$schema['totalTime'] = $data['total_time'];
		}

		// Steps
		if ( ! empty( $data['steps'] ) && is_array( $data['steps'] ) ) {
			foreach ( $data['steps'] as $index => $step_data ) {
				if ( empty( $step_data['text'] ) && empty( $step_data['name'] ) ) {
					continue;
				}

				$step = array(
					'@type'    => 'HowToStep',
					'position' => $index + 1,
				);

				if ( ! empty( $step_data['name'] ) ) {
					$step['name'] = $step_data['name'];
				}

				if ( ! empty( $step_data['text'] ) ) {
					$step['text'] = $step_data['text'];
				}

				if ( ! empty( $step_data['image'] ) ) {
					$step['image'] = $step_data['image'];
				}

				$schema['step'][] = $step;
			}
		}

		return $schema;
	}

	/**
	 * Generate Person schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_person_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Person',
		);

		// Required field
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		// Recommended fields
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		if ( ! empty( $data['job_title'] ) ) {
			$schema['jobTitle'] = $data['job_title'];
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['email'] ) ) {
			$schema['email'] = $data['email'];
		}

		if ( ! empty( $data['telephone'] ) ) {
			$schema['telephone'] = $data['telephone'];
		}

		if ( ! empty( $data['url'] ) ) {
			$schema['url'] = $data['url'];
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		// Affiliation
		if ( ! empty( $data['affiliation'] ) ) {
			$schema['affiliation'] = array(
				'@type' => 'Organization',
				'name'  => $data['affiliation'],
			);
		}

		// Member of
		if ( ! empty( $data['member_of'] ) ) {
			$schema['memberOf'] = array(
				'@type' => 'Organization',
				'name'  => $data['member_of'],
			);
		}

		// Social profiles
		$same_as = array();
		if ( ! empty( $data['facebook'] ) ) {
			$same_as[] = $data['facebook'];
		}

		if ( ! empty( $data['twitter'] ) ) {
			$same_as[] = $data['twitter'];
		}

		if ( ! empty( $data['linkedin'] ) ) {
			$same_as[] = $data['linkedin'];
		}

		if ( ! empty( $data['instagram'] ) ) {
			$same_as[] = $data['instagram'];
		}

		if ( ! empty( $same_as ) ) {
			$schema['sameAs'] = $same_as;
		}

		return $schema;
	}

	/**
	 * Generate JobPosting schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_jobposting_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'JobPosting',
		);

		// Required fields
		if ( ! empty( $data['title'] ) ) {
			$schema['title'] = $data['title'];
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		if ( ! empty( $data['date_posted'] ) ) {
			$schema['datePosted'] = $data['date_posted'];
		}

		// Hiring organization
		if ( ! empty( $data['hiring_organization'] ) ) {
			$schema['hiringOrganization'] = array(
				'@type' => 'Organization',
				'name'  => $data['hiring_organization'],
			);

			if ( ! empty( $data['organization_url'] ) ) {
				$schema['hiringOrganization']['sameAs'] = $data['organization_url'];
			}

			if ( ! empty( $data['organization_logo'] ) ) {
				$schema['hiringOrganization']['logo'] = $data['organization_logo'];
			}
		}

		// Job location
		if ( ! empty( $data['location_type'] ) ) {
			if ( 'remote' === $data['location_type'] ) {
				$schema['jobLocationType'] = 'TELECOMMUTE';
			} else {
				// Physical location
				$location = array(
					'@type' => 'Place',
				);

				if ( ! empty( $data['address_locality'] ) || ! empty( $data['address_region'] ) || ! empty( $data['address_country'] ) ) {
					$address = array( '@type' => 'PostalAddress' );

					if ( ! empty( $data['street_address'] ) ) {
						$address['streetAddress'] = $data['street_address'];
					}

					if ( ! empty( $data['address_locality'] ) ) {
						$address['addressLocality'] = $data['address_locality'];
					}

					if ( ! empty( $data['address_region'] ) ) {
						$address['addressRegion'] = $data['address_region'];
					}

					if ( ! empty( $data['postal_code'] ) ) {
						$address['postalCode'] = $data['postal_code'];
					}

					if ( ! empty( $data['address_country'] ) ) {
						$address['addressCountry'] = $data['address_country'];
					}

					$location['address'] = $address;
				}

				$schema['jobLocation'] = $location;
			}
		}

		// Recommended fields
		if ( ! empty( $data['employment_type'] ) ) {
			$schema['employmentType'] = $data['employment_type'];
		}

		if ( ! empty( $data['valid_through'] ) ) {
			$schema['validThrough'] = $data['valid_through'];
		}

		// Salary/compensation
		if ( ! empty( $data['salary_currency'] ) && ! empty( $data['salary_value'] ) ) {
			$compensation = array(
				'@type'    => 'MonetaryAmount',
				'currency' => $data['salary_currency'],
				'value'    => array(
					'@type' => 'QuantitativeValue',
					'value' => $data['salary_value'],
				),
			);

			if ( ! empty( $data['salary_unit'] ) ) {
				$compensation['value']['unitText'] = $data['salary_unit'];
			}

			$schema['baseSalary'] = $compensation;
		}

		// Application URL
		if ( ! empty( $data['application_url'] ) ) {
			$schema['directApply'] = true;
			$schema['url'] = $data['application_url'];
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		return $schema;
	}

	/**
	 * Generate Course schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_course_schema( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Course',
		);

		// Required fields
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = $data['name'];
		}

		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = $data['description'];
		}

		// Provider
		if ( ! empty( $data['provider_name'] ) ) {
			$schema['provider'] = array(
				'@type' => 'Organization',
				'name'  => $data['provider_name'],
			);

			if ( ! empty( $data['provider_url'] ) ) {
				$schema['provider']['url'] = $data['provider_url'];
			}
		}

		// Recommended fields
		if ( ! empty( $data['course_code'] ) ) {
			$schema['courseCode'] = $data['course_code'];
		}

		if ( ! empty( $data['course_url'] ) ) {
			$schema['url'] = $data['course_url'];
		} elseif ( $post_id ) {
			$schema['url'] = get_permalink( $post_id );
		}

		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = $data['image'];
		}

		// Course mode (online, in-person, blended)
		if ( ! empty( $data['course_mode'] ) ) {
			$schema['courseMode'] = $data['course_mode'];
		}

		// Offers (pricing)
		if ( isset( $data['price'] ) ) {
			$offer = array(
				'@type'         => 'Offer',
				'price'         => $data['price'],
				'priceCurrency' => ! empty( $data['price_currency'] ) ? $data['price_currency'] : 'USD',
			);

			if ( ! empty( $data['availability'] ) ) {
				$offer['availability'] = $data['availability'];
			}

			$schema['offers'] = $offer;
		}

		// Course prerequisites
		if ( ! empty( $data['course_prerequisites'] ) ) {
			$schema['coursePrerequisites'] = $data['course_prerequisites'];
		}

		// Educational level
		if ( ! empty( $data['educational_level'] ) ) {
			$schema['educationalLevel'] = $data['educational_level'];
		}

		// Time required
		if ( ! empty( $data['time_required'] ) ) {
			$schema['timeRequired'] = $data['time_required'];
		}

		// Instructor
		if ( ! empty( $data['instructor_name'] ) ) {
			$schema['instructor'] = array(
				'@type' => 'Person',
				'name'  => $data['instructor_name'],
			);

			if ( ! empty( $data['instructor_url'] ) ) {
				$schema['instructor']['url'] = $data['instructor_url'];
			}
		}

		return $schema;
	}

	/**
	 * Format datetime for schema.org.
	 *
	 * @param string $date Date string.
	 * @param string $time Time string (optional).
	 * @return string
	 */
	private static function format_datetime( $date, $time = '' ) {
		if ( empty( $time ) ) {
			return date( 'Y-m-d', strtotime( $date ) );
		}

		return date( 'c', strtotime( $date . ' ' . $time ) );
	}
}
