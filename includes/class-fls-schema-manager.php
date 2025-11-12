<?php
/**
 * Schema Manager - handles multiple schemas per post.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema Manager class.
 */
class FLS_Schema_Manager {

	/**
	 * Get all schemas for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return array Array of schemas.
	 */
	public static function get_schemas( $post_id ) {
		// Check if migration is needed (with transient caching)
		$migration_checked = get_transient( 'fls_migration_checked_' . $post_id );
		if ( false === $migration_checked ) {
			self::maybe_migrate_post( $post_id );
			// Cache the fact we've checked migration for 1 week
			set_transient( 'fls_migration_checked_' . $post_id, true, WEEK_IN_SECONDS );
		}

		$schemas = get_post_meta( $post_id, '_fatlabschema_schemas', true );

		if ( empty( $schemas ) || ! is_array( $schemas ) ) {
			return array();
		}

		// Sort by order
		uasort( $schemas, function( $a, $b ) {
			$order_a = isset( $a['order'] ) ? (int) $a['order'] : 0;
			$order_b = isset( $b['order'] ) ? (int) $b['order'] : 0;
			return $order_a - $order_b;
		});

		return $schemas;
	}

	/**
	 * Get a single schema by ID.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $schema_id Schema ID.
	 * @return array|null Schema data or null if not found.
	 */
	public static function get_schema( $post_id, $schema_id ) {
		$schemas = self::get_schemas( $post_id );

		if ( isset( $schemas[ $schema_id ] ) ) {
			return $schemas[ $schema_id ];
		}

		return null;
	}

	/**
	 * Add a new schema to a post.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $type Schema type.
	 * @param array  $data Schema data.
	 * @param bool   $enabled Whether schema is enabled.
	 * @return string New schema ID.
	 */
	public static function add_schema( $post_id, $type, $data, $enabled = true ) {
		$schemas = self::get_schemas( $post_id );

		// Generate unique ID
		$schema_id = 'schema_' . uniqid();

		// Get next order number
		$max_order = 0;
		foreach ( $schemas as $schema ) {
			if ( isset( $schema['order'] ) && $schema['order'] > $max_order ) {
				$max_order = $schema['order'];
			}
		}

		// Add new schema
		$schemas[ $schema_id ] = array(
			'type'    => $type,
			'data'    => $data,
			'enabled' => $enabled,
			'order'   => $max_order + 1,
		);

		update_post_meta( $post_id, '_fatlabschema_schemas', $schemas );

		// Clear cache
		FLS_Output::clear_cache( $post_id );

		return $schema_id;
	}

	/**
	 * Update an existing schema.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $schema_id Schema ID.
	 * @param string $type Schema type.
	 * @param array  $data Schema data.
	 * @param bool   $enabled Whether schema is enabled.
	 * @return bool Success status.
	 */
	public static function update_schema( $post_id, $schema_id, $type, $data, $enabled = true ) {
		$schemas = self::get_schemas( $post_id );

		if ( ! isset( $schemas[ $schema_id ] ) ) {
			return false;
		}

		$schemas[ $schema_id ]['type']    = $type;
		$schemas[ $schema_id ]['data']    = $data;
		$schemas[ $schema_id ]['enabled'] = $enabled;

		update_post_meta( $post_id, '_fatlabschema_schemas', $schemas );

		// Clear cache
		FLS_Output::clear_cache( $post_id );

		return true;
	}

	/**
	 * Remove a schema from a post.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $schema_id Schema ID.
	 * @return bool Success status.
	 */
	public static function remove_schema( $post_id, $schema_id ) {
		$schemas = self::get_schemas( $post_id );

		if ( ! isset( $schemas[ $schema_id ] ) ) {
			return false;
		}

		unset( $schemas[ $schema_id ] );

		if ( empty( $schemas ) ) {
			delete_post_meta( $post_id, '_fatlabschema_schemas' );
		} else {
			update_post_meta( $post_id, '_fatlabschema_schemas', $schemas );
		}

		// Clear cache
		FLS_Output::clear_cache( $post_id );

		return true;
	}

	/**
	 * Check if a post has any schemas.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	public static function has_schemas( $post_id ) {
		$schemas = self::get_schemas( $post_id );
		return ! empty( $schemas );
	}

	/**
	 * Migrate old single-schema format to new multi-schema format.
	 *
	 * @param int $post_id Post ID.
	 * @return bool Whether migration was performed.
	 */
	public static function maybe_migrate_post( $post_id ) {
		// Check if already migrated
		$migrated = get_post_meta( $post_id, '_fatlabschema_migrated', true );
		if ( $migrated ) {
			return false;
		}

		// Check if new format already exists
		$schemas = get_post_meta( $post_id, '_fatlabschema_schemas', true );
		if ( ! empty( $schemas ) && is_array( $schemas ) ) {
			update_post_meta( $post_id, '_fatlabschema_migrated', true );
			return false;
		}

		// Check if old format exists
		$old_type = get_post_meta( $post_id, '_fatlabschema_type', true );
		$old_data = get_post_meta( $post_id, '_fatlabschema_data', true );
		$old_enabled = get_post_meta( $post_id, '_fatlabschema_enabled', true );

		// If no old data, mark as migrated and return
		if ( empty( $old_type ) || 'none' === $old_type ) {
			update_post_meta( $post_id, '_fatlabschema_migrated', true );
			return false;
		}

		// Migrate old data to new format
		$schema_id = 'schema_' . uniqid();
		$new_schemas = array(
			$schema_id => array(
				'type'    => $old_type,
				'data'    => $old_data,
				'enabled' => (bool) $old_enabled,
				'order'   => 0,
			),
		);

		update_post_meta( $post_id, '_fatlabschema_schemas', $new_schemas );
		update_post_meta( $post_id, '_fatlabschema_migrated', true );

		return true;
	}

	/**
	 * Get count of enabled schemas for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return int Count of enabled schemas.
	 */
	public static function get_enabled_count( $post_id ) {
		$schemas = self::get_schemas( $post_id );
		$count = 0;

		foreach ( $schemas as $schema ) {
			if ( isset( $schema['enabled'] ) && $schema['enabled'] ) {
				$count++;
			}
		}

		return $count;
	}
}
