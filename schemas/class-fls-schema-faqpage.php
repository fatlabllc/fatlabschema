<?php
/**
 * FAQPage Schema Type.
 *
 * Generates JSON-LD structured data for FAQPage schema type
 * following schema.org/FAQPage specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FAQPage schema class.
 *
 * Handles generation of FAQPage schema markup for pages
 * containing frequently asked questions.
 */
class FLS_Schema_FAQPage {

	/**
	 * Generate FAQPage schema.
	 *
	 * Creates a schema.org/FAQPage structured data object with
	 * Question and Answer entities.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing FAQ information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array(),
		);

		// Process questions and answers.
		if ( ! empty( $data['questions'] ) && is_array( $data['questions'] ) ) {
			foreach ( $data['questions'] as $qa ) {
				$question = self::build_question( $qa );
				if ( ! empty( $question ) ) {
					$schema['mainEntity'][] = $question;
				}
			}
		}

		return apply_filters( 'fls_faqpage_schema', $schema, $data, $post_id );
	}

	/**
	 * Build a single question object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $qa Question and answer data.
	 * @return array|null Question object or null if invalid data.
	 */
	private static function build_question( $qa ) {
		// Both question and answer are required.
		if ( empty( $qa['question'] ) || empty( $qa['answer'] ) ) {
			return null;
		}

		return array(
			'@type'          => 'Question',
			'name'           => sanitize_text_field( $qa['question'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_kses_post( $qa['answer'] ),
			),
		);
	}
}
