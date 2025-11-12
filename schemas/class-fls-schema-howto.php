<?php
/**
 * HowTo Schema Type.
 *
 * Generates JSON-LD structured data for HowTo schema type
 * following schema.org/HowTo specifications.
 *
 * @package FatLab_Schema_Wizard
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * HowTo schema class.
 *
 * Handles generation of HowTo schema markup for instructional
 * and tutorial content with step-by-step guidance.
 */
class FLS_Schema_HowTo {

	/**
	 * Generate HowTo schema.
	 *
	 * Creates a schema.org/HowTo structured data object with
	 * steps, tools, materials, and other instructional elements.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data     Schema data containing HowTo information.
	 * @param int   $post_id  Optional. Post ID for context. Default null.
	 * @return array Schema array ready for JSON-LD encoding.
	 */
	public static function generate( $data, $post_id = null ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'HowTo',
			'step'     => array(),
		);

		// Required field - name.
		if ( ! empty( $data['name'] ) ) {
			$schema['name'] = sanitize_text_field( $data['name'] );
		}

		// Description (recommended).
		if ( ! empty( $data['description'] ) ) {
			$schema['description'] = sanitize_textarea_field( $data['description'] );
		}

		// Image (recommended).
		if ( ! empty( $data['image'] ) ) {
			$schema['image'] = esc_url_raw( $data['image'] );
		}

		// Total time required.
		if ( ! empty( $data['total_time'] ) ) {
			$schema['totalTime'] = sanitize_text_field( $data['total_time'] );
		}

		// Estimated cost.
		if ( ! empty( $data['estimated_cost'] ) ) {
			$schema['estimatedCost'] = array(
				'@type'    => 'MonetaryAmount',
				'currency' => ! empty( $data['cost_currency'] ) ? sanitize_text_field( $data['cost_currency'] ) : 'USD',
				'value'    => sanitize_text_field( $data['estimated_cost'] ),
			);
		}

		// Supply (tools/materials needed).
		if ( ! empty( $data['supply'] ) && is_array( $data['supply'] ) ) {
			$schema['supply'] = array_map( function( $item ) {
				return array(
					'@type' => 'HowToSupply',
					'name'  => sanitize_text_field( $item ),
				);
			}, $data['supply'] );
		}

		// Tools needed.
		if ( ! empty( $data['tool'] ) && is_array( $data['tool'] ) ) {
			$schema['tool'] = array_map( function( $item ) {
				return array(
					'@type' => 'HowToTool',
					'name'  => sanitize_text_field( $item ),
				);
			}, $data['tool'] );
		}

		// Process steps.
		if ( ! empty( $data['steps'] ) && is_array( $data['steps'] ) ) {
			foreach ( $data['steps'] as $index => $step_data ) {
				$step = self::build_step( $step_data, $index + 1 );
				if ( ! empty( $step ) ) {
					$schema['step'][] = $step;
				}
			}
		}

		// Yield/result.
		if ( ! empty( $data['yield'] ) ) {
			$schema['yield'] = sanitize_text_field( $data['yield'] );
		}

		return apply_filters( 'fls_howto_schema', $schema, $data, $post_id );
	}

	/**
	 * Build a single step object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $step_data Step data.
	 * @param int   $position  Step position/number.
	 * @return array|null Step object or null if invalid data.
	 */
	private static function build_step( $step_data, $position ) {
		// Step must have either text or name.
		if ( empty( $step_data['text'] ) && empty( $step_data['name'] ) ) {
			return null;
		}

		$step = array(
			'@type'    => 'HowToStep',
			'position' => absint( $position ),
		);

		if ( ! empty( $step_data['name'] ) ) {
			$step['name'] = sanitize_text_field( $step_data['name'] );
		}

		if ( ! empty( $step_data['text'] ) ) {
			$step['text'] = sanitize_textarea_field( $step_data['text'] );
		}

		if ( ! empty( $step_data['image'] ) ) {
			$step['image'] = esc_url_raw( $step_data['image'] );
		}

		if ( ! empty( $step_data['url'] ) ) {
			$step['url'] = esc_url_raw( $step_data['url'] );
		}

		return $step;
	}
}
