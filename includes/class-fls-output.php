<?php
/**
 * Front-end JSON-LD output functionality.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output class for front-end schema.
 */
class FLS_Output {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'output_schema_json_ld' ), 10 );
	}

	/**
	 * Output schema JSON-LD in the head.
	 */
	public function output_schema_json_ld() {
		// Check if plugin is enabled
		$settings = get_option( 'fatlabschema_settings', array() );
		if ( ! isset( $settings['enabled'] ) || ! $settings['enabled'] ) {
			return;
		}

		$schemas = array();

		// Always output Organization schema if configured
		$org_schema = $this->get_organization_schema();
		if ( ! empty( $org_schema ) ) {
			$schemas[] = $org_schema;
		}

		// Output page-specific schema if on a singular post
		if ( is_singular() ) {
			$post_id = get_the_ID();
			$page_schema = $this->get_page_schema( $post_id );

			if ( ! empty( $page_schema ) ) {
				$schemas[] = $page_schema;
			}
		}

		// Output all schemas
		if ( ! empty( $schemas ) ) {
			foreach ( $schemas as $schema ) {
				$this->output_json_ld( $schema );
			}
		}
	}

	/**
	 * Get Organization schema.
	 *
	 * @return array|null
	 */
	public function get_organization_schema() {
		// Try cache first
		$cached = get_transient( 'fatlabschema_organization_output' );
		if ( false !== $cached ) {
			return $cached;
		}

		$organization = get_option( 'fatlabschema_organization', array() );

		if ( empty( $organization['name'] ) || empty( $organization['url'] ) ) {
			return null;
		}

		$schema = FLS_Schema_Generator::generate_json_ld( 'organization', $organization );

		// Cache for 24 hours
		set_transient( 'fatlabschema_organization_output', $schema, DAY_IN_SECONDS );

		return $schema;
	}

	/**
	 * Get page-specific schema.
	 *
	 * @param int $post_id Post ID.
	 * @return array|null
	 */
	public function get_page_schema( $post_id ) {
		// Check if schema should be output for this post
		if ( ! $this->should_output_schema( $post_id ) ) {
			return null;
		}

		// Try cache first
		$cached = get_transient( 'fatlabschema_output_' . $post_id );
		if ( false !== $cached ) {
			return $cached;
		}

		$schema_type = get_post_meta( $post_id, '_fatlabschema_type', true );
		$schema_data = get_post_meta( $post_id, '_fatlabschema_data', true );

		if ( empty( $schema_type ) || empty( $schema_data ) || 'none' === $schema_type ) {
			return null;
		}

		// Check for conflicts if conflict detection is enabled
		$settings = get_option( 'fatlabschema_settings', array() );
		if ( isset( $settings['conflict_detection'] ) && $settings['conflict_detection'] ) {
			$conflicts = FLS_Conflict_Detector::get_conflicting_schema_types( $post_id, $schema_type );

			// Don't output if there's a conflict (unless user explicitly overrode)
			if ( ! empty( $conflicts ) ) {
				$override = get_post_meta( $post_id, '_fatlabschema_override_conflict', true );
				if ( ! $override ) {
					return null;
				}
			}
		}

		// Generate schema
		$schema = FLS_Schema_Generator::generate_json_ld( $schema_type, $schema_data, $post_id );

		// Cache for 12 hours
		set_transient( 'fatlabschema_output_' . $post_id, $schema, 12 * HOUR_IN_SECONDS );

		return $schema;
	}

	/**
	 * Check if schema should be output for this post.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	private function should_output_schema( $post_id ) {
		// Check if schema is enabled for this post
		$enabled = get_post_meta( $post_id, '_fatlabschema_enabled', true );

		if ( ! $enabled ) {
			return false;
		}

		// Check if post is published
		$post = get_post( $post_id );
		if ( ! $post || 'publish' !== $post->post_status ) {
			return false;
		}

		return apply_filters( 'fls_should_output_schema', true, $post_id );
	}

	/**
	 * Output JSON-LD script tag.
	 *
	 * @param array $schema Schema array.
	 */
	private function output_json_ld( $schema ) {
		if ( empty( $schema ) ) {
			return;
		}

		// Remove empty values
		$schema = $this->remove_empty_values( $schema );

		// Convert to JSON
		$json = wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

		if ( ! $json ) {
			return;
		}

		echo "\n<!-- FatLab Schema Wizard -->\n";
		echo '<script type="application/ld+json">' . "\n";
		echo $json . "\n";
		echo '</script>' . "\n";
		echo "<!-- / FatLab Schema Wizard -->\n\n";
	}

	/**
	 * Remove empty values from schema array recursively.
	 *
	 * @param array $array Input array.
	 * @return array
	 */
	private function remove_empty_values( $array ) {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				$array[ $key ] = $this->remove_empty_values( $value );

				// Remove empty arrays
				if ( empty( $array[ $key ] ) && '@type' !== $key && '@context' !== $key ) {
					unset( $array[ $key ] );
				}
			} elseif ( empty( $value ) && '0' !== $value && 0 !== $value ) {
				// Don't remove @type or @context even if empty
				if ( '@type' !== $key && '@context' !== $key ) {
					unset( $array[ $key ] );
				}
			}
		}

		return $array;
	}

	/**
	 * Clear schema cache for a post.
	 *
	 * @param int $post_id Post ID.
	 */
	public static function clear_cache( $post_id ) {
		delete_transient( 'fatlabschema_output_' . $post_id );
	}

	/**
	 * Clear organization schema cache.
	 */
	public static function clear_organization_cache() {
		delete_transient( 'fatlabschema_organization_output' );
	}
}
