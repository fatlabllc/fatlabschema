<?php
/**
 * Conflict detection for other SEO plugins.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Conflict detector class.
 */
class FLS_Conflict_Detector {

	/**
	 * Detect active SEO plugins.
	 *
	 * @return array
	 */
	public static function detect_active_seo_plugins() {
		$active_plugins = array();

		// Check for Yoast SEO
		if ( defined( 'WPSEO_VERSION' ) || class_exists( 'WPSEO_Options' ) ) {
			$active_plugins['yoast'] = array(
				'name'    => 'Yoast SEO',
				'version' => defined( 'WPSEO_VERSION' ) ? WPSEO_VERSION : 'unknown',
			);
		}

		// Check for Rank Math
		if ( defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' ) ) {
			$active_plugins['rankmath'] = array(
				'name'    => 'Rank Math SEO',
				'version' => defined( 'RANK_MATH_VERSION' ) ? RANK_MATH_VERSION : 'unknown',
			);
		}

		// Check for All in One SEO
		if ( defined( 'AIOSEO_VERSION' ) || class_exists( 'AIOSEO\\Plugin\\AIOSEO' ) ) {
			$active_plugins['aioseo'] = array(
				'name'    => 'All in One SEO',
				'version' => defined( 'AIOSEO_VERSION' ) ? AIOSEO_VERSION : 'unknown',
			);
		}

		// Check for SEOPress
		if ( defined( 'SEOPRESS_VERSION' ) || class_exists( 'SEOPress' ) ) {
			$active_plugins['seopress'] = array(
				'name'    => 'SEOPress',
				'version' => defined( 'SEOPRESS_VERSION' ) ? SEOPRESS_VERSION : 'unknown',
			);
		}

		return apply_filters( 'fls_active_seo_plugins', $active_plugins );
	}

	/**
	 * Get conflicting schema types for a post.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $our_type Our schema type.
	 * @return array
	 */
	public static function get_conflicting_schema_types( $post_id, $our_type ) {
		$conflicts = array();
		$active_plugins = self::detect_active_seo_plugins();

		// Only check for Article schema conflicts (most common)
		if ( 'article' === $our_type || 'scholarly' === $our_type ) {
			// Check Yoast
			if ( isset( $active_plugins['yoast'] ) ) {
				$yoast_schema = get_post_meta( $post_id, '_yoast_wpseo_schema_article_type', true );
				if ( ! empty( $yoast_schema ) || self::check_yoast_article_schema( $post_id ) ) {
					$conflicts[] = array(
						'plugin' => 'yoast',
						'type'   => 'Article',
						'name'   => 'Yoast SEO',
					);
				}
			}

			// Check Rank Math
			if ( isset( $active_plugins['rankmath'] ) ) {
				$rm_schema = get_post_meta( $post_id, 'rank_math_schema_Article', true );
				if ( ! empty( $rm_schema ) ) {
					$conflicts[] = array(
						'plugin' => 'rankmath',
						'type'   => 'Article',
						'name'   => 'Rank Math SEO',
					);
				}
			}

			// Check All in One SEO
			if ( isset( $active_plugins['aioseo'] ) ) {
				$aioseo_schema = get_post_meta( $post_id, '_aioseo_schema_type', true );
				if ( 'article' === $aioseo_schema || 'blogposting' === $aioseo_schema ) {
					$conflicts[] = array(
						'plugin' => 'aioseo',
						'type'   => 'Article',
						'name'   => 'All in One SEO',
					);
				}
			}
		}

		return apply_filters( 'fls_conflicting_schema_types', $conflicts, $post_id, $our_type );
	}

	/**
	 * Check if Yoast is adding Article schema.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	private static function check_yoast_article_schema( $post_id ) {
		// Yoast automatically adds Article schema to posts by default
		$post = get_post( $post_id );

		if ( ! $post ) {
			return false;
		}

		// Check if it's a post type that Yoast adds Article schema to
		if ( 'post' === $post->post_type ) {
			return true;
		}

		return false;
	}

	/**
	 * Show conflict warning.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $our_type Our schema type.
	 * @return string|null HTML warning or null.
	 */
	public static function show_conflict_warning( $post_id, $our_type ) {
		// Check if conflict detection is enabled
		$settings = get_option( 'fatlabschema_settings', array() );
		if ( ! isset( $settings['conflict_detection'] ) || ! $settings['conflict_detection'] ) {
			return null;
		}

		$conflicts = self::get_conflicting_schema_types( $post_id, $our_type );

		if ( empty( $conflicts ) ) {
			return null;
		}

		$conflict = $conflicts[0]; // Show first conflict

		ob_start();
		?>
		<div class="fls-conflict-warning">
			<div class="fls-warning-icon">
				<span class="dashicons dashicons-warning"></span>
			</div>
			<div class="fls-warning-content">
				<h4><?php esc_html_e( 'Schema Conflict Detected', 'fatlabschema' ); ?></h4>
				<p>
					<?php
					printf(
						/* translators: 1: Plugin name, 2: Schema type */
						esc_html__( '%1$s is already adding %2$s schema to this page. Having duplicate schema can confuse search engines and harm your SEO.', 'fatlabschema' ),
						'<strong>' . esc_html( $conflict['name'] ) . '</strong>',
						'<strong>' . esc_html( $conflict['type'] ) . '</strong>'
					);
					?>
				</p>
				<p class="fls-recommendation">
					<strong><?php esc_html_e( 'We recommend:', 'fatlabschema' ); ?></strong>
				</p>
				<ul>
					<li>
						<?php
						printf(
							/* translators: 1: Plugin name, 2: Schema type */
							esc_html__( 'Let %1$s handle %2$s schema (it\'s working!)', 'fatlabschema' ),
							esc_html( $conflict['name'] ),
							esc_html( $conflict['type'] )
						);
						?>
					</li>
					<li><?php esc_html_e( 'Use FatLab Schema Wizard for other schema types (Event, Service, HowTo, etc.)', 'fatlabschema' ); ?></li>
				</ul>
				<div class="fls-warning-actions">
					<label>
						<input type="checkbox" name="fatlabschema_override_conflict" value="1" />
						<?php esc_html_e( 'I understand the risks - use FatLab Schema anyway', 'fatlabschema' ); ?>
					</label>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Check if there are any SEO plugins active.
	 *
	 * @return bool
	 */
	public static function has_active_seo_plugins() {
		$plugins = self::detect_active_seo_plugins();
		return ! empty( $plugins );
	}

	/**
	 * Get SEO plugin names (for display).
	 *
	 * @return string
	 */
	public static function get_seo_plugin_names() {
		$plugins = self::detect_active_seo_plugins();

		if ( empty( $plugins ) ) {
			return '';
		}

		$names = array();
		foreach ( $plugins as $plugin ) {
			$names[] = $plugin['name'];
		}

		return implode( ', ', $names );
	}
}
