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
		return FLS_Schema_Organization::generate( $data, $post_id );
	}

	/**
	 * Generate LocalBusiness schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_localbusiness_schema( $data, $post_id = null ) {
		return FLS_Schema_LocalBusiness::generate( $data, $post_id );
	}

	/**
	 * Generate Event schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_event_schema( $data, $post_id = null ) {
		return FLS_Schema_Event::generate( $data, $post_id );
	}

	/**
	 * Generate FAQPage schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_faqpage_schema( $data, $post_id = null ) {
		return FLS_Schema_FAQPage::generate( $data, $post_id );
	}

	/**
	 * Generate Article schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_article_schema( $data, $post_id = null ) {
		return FLS_Schema_Article::generate( $data, $post_id );
	}

	/**
	 * Generate Service schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_service_schema( $data, $post_id = null ) {
		return FLS_Schema_Service::generate( $data, $post_id );
	}

	/**
	 * Generate HowTo schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_howto_schema( $data, $post_id = null ) {
		return FLS_Schema_HowTo::generate( $data, $post_id );
	}

	/**
	 * Generate Person schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_person_schema( $data, $post_id = null ) {
		return FLS_Schema_Person::generate( $data, $post_id );
	}

	/**
	 * Generate JobPosting schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_jobposting_schema( $data, $post_id = null ) {
		return FLS_Schema_JobPosting::generate( $data, $post_id );
	}

	/**
	 * Generate Course schema.
	 *
	 * @param array $data Schema data.
	 * @param int   $post_id Post ID.
	 * @return array
	 */
	public static function generate_course_schema( $data, $post_id = null ) {
		return FLS_Schema_Course::generate( $data, $post_id );
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
