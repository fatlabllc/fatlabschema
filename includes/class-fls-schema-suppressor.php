<?php
/**
 * Schema suppression for other SEO plugins.
 *
 * Handles disabling Organization schema from other SEO plugins when
 * FatLab Schema Wizard is set to take priority.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema suppressor class.
 */
class FLS_Schema_Suppressor {

	/**
	 * Initialize suppression hooks.
	 */
	public static function init() {
		// Check if organization suppression is enabled in plugin settings
		$settings = get_option( 'fatlabschema_settings', array() );

		if ( empty( $settings['organization_schema_priority'] ) ) {
			return;
		}

		// Only suppress if "suppress_others" is selected
		if ( 'suppress_others' !== $settings['organization_schema_priority'] ) {
			return;
		}

		// Detect conflicting plugins and suppress them
		$conflicts = FLS_Conflict_Detector::detect_organization_conflicts();

		if ( empty( $conflicts ) ) {
			return;
		}

		// Hook into each conflicting plugin to suppress Organization schema
		foreach ( $conflicts as $conflict ) {
			self::suppress_plugin_organization_schema( $conflict['plugin'] );
		}
	}

	/**
	 * Suppress Organization schema from a specific plugin.
	 *
	 * @param string $plugin_id Plugin identifier (yoast, rankmath, aioseo, seopress).
	 */
	private static function suppress_plugin_organization_schema( $plugin_id ) {
		switch ( $plugin_id ) {
			case 'yoast':
				self::suppress_yoast_organization();
				break;
			case 'rankmath':
				self::suppress_rankmath_organization();
				break;
			case 'aioseo':
				self::suppress_aioseo_organization();
				break;
			case 'seopress':
				self::suppress_seopress_organization();
				break;
		}
	}

	/**
	 * Suppress Yoast SEO Organization schema.
	 */
	private static function suppress_yoast_organization() {
		// Use Yoast's filter to remove Organization from schema graph
		add_filter( 'wpseo_schema_graph_pieces', array( __CLASS__, 'remove_yoast_organization_piece' ), 11, 2 );
	}

	/**
	 * Remove Yoast Organization piece from schema graph.
	 *
	 * @param array  $pieces  Schema pieces.
	 * @param object $context Schema context.
	 * @return array Filtered schema pieces.
	 */
	public static function remove_yoast_organization_piece( $pieces, $context ) {
		return array_filter(
			$pieces,
			function( $piece ) {
				// Check if Yoast's Organization class exists and filter it out
				if ( class_exists( '\Yoast\WP\SEO\Generators\Schema\Organization' ) ) {
					return ! $piece instanceof \Yoast\WP\SEO\Generators\Schema\Organization;
				}
				return true;
			}
		);
	}

	/**
	 * Suppress Rank Math Organization schema.
	 */
	private static function suppress_rankmath_organization() {
		// Use Rank Math's filter to remove publisher/organization data
		add_filter( 'rank_math/json_ld', array( __CLASS__, 'remove_rankmath_organization_data' ), 99, 2 );
	}

	/**
	 * Remove Rank Math Organization data from JSON-LD.
	 *
	 * @param array  $data   Schema data.
	 * @param object $jsonld JSON-LD object.
	 * @return array Filtered schema data.
	 */
	public static function remove_rankmath_organization_data( $data, $jsonld ) {
		// Remove publisher (Organization reference)
		if ( isset( $data['publisher'] ) ) {
			unset( $data['publisher'] );
		}

		// Remove place (used in LocalBusiness)
		if ( isset( $data['place'] ) ) {
			unset( $data['place'] );
		}

		// If this IS an Organization schema, return empty to remove it entirely
		if ( isset( $data['@type'] ) && 'Organization' === $data['@type'] ) {
			return array();
		}

		return $data;
	}

	/**
	 * Suppress All in One SEO Organization schema.
	 */
	private static function suppress_aioseo_organization() {
		// Use AIOSEO's filter to remove Organization from schema output
		add_filter( 'aioseo_schema_output', array( __CLASS__, 'remove_aioseo_organization_graph' ) );
	}

	/**
	 * Remove All in One SEO Organization graph from schema output.
	 *
	 * @param array $graphs Schema graphs.
	 * @return array Filtered schema graphs.
	 */
	public static function remove_aioseo_organization_graph( $graphs ) {
		foreach ( $graphs as $index => $graph ) {
			if ( isset( $graph['@type'] ) && 'Organization' === $graph['@type'] ) {
				unset( $graphs[ $index ] );
			}
		}

		return $graphs;
	}

	/**
	 * Suppress SEOPress Organization schema.
	 */
	private static function suppress_seopress_organization() {
		// SEOPress uses different hooks for schema output
		// Remove the organization schema action
		add_filter( 'seopress_social_knowledge_type', '__return_false' );
	}

	/**
	 * Get list of plugins being suppressed.
	 *
	 * @return array Array of suppressed plugins.
	 */
	public static function get_suppressed_plugins() {
		$settings = get_option( 'fatlabschema_settings', array() );

		// Only return if suppression is active
		if ( empty( $settings['organization_schema_priority'] ) || 'suppress_others' !== $settings['organization_schema_priority'] ) {
			return array();
		}

		// Return conflicts that would be suppressed
		return FLS_Conflict_Detector::detect_organization_conflicts();
	}
}
