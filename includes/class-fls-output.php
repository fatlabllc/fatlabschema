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
	 * Cached plugin settings.
	 *
	 * @var array|null
	 */
	private static $settings_cache = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'output_schema_json_ld' ), 10 );
	}

	/**
	 * Get plugin settings with caching.
	 *
	 * @return array
	 */
	private static function get_settings() {
		if ( null === self::$settings_cache ) {
			self::$settings_cache = get_option( 'fatlabschema_settings', array() );
		}
		return self::$settings_cache;
	}

	/**
	 * Output schema JSON-LD in the head.
	 */
	public function output_schema_json_ld() {
		// Check if plugin is enabled
		$settings = self::get_settings();
		if ( ! isset( $settings['enabled'] ) || ! $settings['enabled'] ) {
			return;
		}

		$schemas = array();

		// Always output Organization schema if configured
		$org_schema = $this->get_organization_schema();
		if ( ! empty( $org_schema ) ) {
			$schemas[] = $org_schema;
		}

		// Output page-specific schemas if on a singular post
		if ( is_singular() ) {
			$post_id = get_the_ID();
			$page_schemas = $this->get_page_schema( $post_id );

			if ( ! empty( $page_schemas ) && is_array( $page_schemas ) ) {
				// Can be array of schemas (new format) or single schema (old format)
				if ( isset( $page_schemas['@type'] ) ) {
					// Single schema (old format for backward compatibility)
					$schemas[] = $page_schemas;
				} else {
					// Multiple schemas (new format)
					$schemas = array_merge( $schemas, $page_schemas );
				}
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

		// Get all schemas for this post
		$schemas = FLS_Schema_Manager::get_schemas( $post_id );

		if ( empty( $schemas ) ) {
			return null;
		}

		// Generate output for all enabled schemas
		$output_schemas = array();

		foreach ( $schemas as $schema_id => $schema ) {
			// Check if schema is enabled (defaults to true if not set)
			$enabled = isset( $schema['enabled'] ) ? $schema['enabled'] : true;
			if ( ! $enabled ) {
				continue;
			}

			$schema_type = $schema['type'];
			$schema_data = $schema['data'];

			if ( empty( $schema_type ) || empty( $schema_data ) || 'none' === $schema_type ) {
				continue;
			}

			// Check for conflicts if conflict detection is enabled
			$settings = self::get_settings();
			if ( isset( $settings['conflict_detection'] ) && $settings['conflict_detection'] ) {
				$conflicts = FLS_Conflict_Detector::get_conflicting_schema_types( $post_id, $schema_type );

				// Don't output if there's a conflict (unless user explicitly overrode)
				if ( ! empty( $conflicts ) ) {
					$override = get_post_meta( $post_id, '_fatlabschema_override_conflict_' . $schema_id, true );
					if ( ! $override ) {
						continue;
					}
				}
			}

			// Generate schema
			$generated = FLS_Schema_Generator::generate_json_ld( $schema_type, $schema_data, $post_id );
			if ( ! empty( $generated ) ) {
				$output_schemas[] = $generated;
			}
		}

		return $output_schemas;
	}

	/**
	 * Check if schema should be output for this post.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	private function should_output_schema( $post_id ) {
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

	/**
	 * Clear settings cache.
	 * Call this when settings are updated.
	 */
	public static function clear_settings_cache() {
		self::$settings_cache = null;
	}
}
