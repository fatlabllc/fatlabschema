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
	 * Cached plugin settings.
	 *
	 * @var array|null
	 */
	private static $settings_cache = null;

	/**
	 * Cached active SEO plugins.
	 *
	 * @var array|null
	 */
	private static $active_plugins_cache = null;

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
	 * Detect active SEO plugins.
	 *
	 * @return array
	 */
	public static function detect_active_seo_plugins() {
		// Return cached result if available
		if ( null !== self::$active_plugins_cache ) {
			return self::$active_plugins_cache;
		}

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

		$active_plugins = apply_filters( 'fls_active_seo_plugins', $active_plugins );

		// Cache the result
		self::$active_plugins_cache = $active_plugins;

		return $active_plugins;
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
		$settings = self::get_settings();
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
						<?php
						$override = get_post_meta( $post_id, '_fatlabschema_override_conflict', true );
						?>
						<input type="checkbox" name="fatlabschema_override_conflict" value="1" <?php checked( $override, true ); ?> />
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

	/**
	 * Detect Organization schema conflicts.
	 *
	 * Checks if other SEO plugins are outputting Organization schema.
	 *
	 * @return array Array of plugins outputting Organization schema.
	 */
	public static function detect_organization_conflicts() {
		$conflicts = array();
		$active_plugins = self::detect_active_seo_plugins();

		// Check each active plugin for Organization schema output
		foreach ( $active_plugins as $plugin_id => $plugin_data ) {
			$has_org_schema = false;

			switch ( $plugin_id ) {
				case 'yoast':
					$has_org_schema = self::check_yoast_organization_schema();
					break;
				case 'rankmath':
					$has_org_schema = self::check_rankmath_organization_schema();
					break;
				case 'aioseo':
					$has_org_schema = self::check_aioseo_organization_schema();
					break;
				case 'seopress':
					$has_org_schema = self::check_seopress_organization_schema();
					break;
			}

			if ( $has_org_schema ) {
				$conflicts[] = array(
					'plugin' => $plugin_id,
					'name'   => $plugin_data['name'],
					'version' => $plugin_data['version'],
				);
			}
		}

		return apply_filters( 'fls_organization_conflicts', $conflicts );
	}

	/**
	 * Check if Yoast is outputting Organization schema.
	 *
	 * @return bool
	 */
	private static function check_yoast_organization_schema() {
		// Yoast outputs Organization schema if configured in Search Appearance > General
		// Company settings are stored in wpseo_titles option
		if ( ! class_exists( 'WPSEO_Options' ) ) {
			return false;
		}

		// Check wpseo_titles option where company info is stored
		$options = get_option( 'wpseo_titles' );

		// Check if organization type is set
		if ( empty( $options ) || empty( $options['company_or_person'] ) ) {
			return false;
		}

		// If set to "company", check if company name is configured
		if ( 'company' === $options['company_or_person'] && ! empty( $options['company_name'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if Rank Math is outputting Organization schema.
	 *
	 * @return bool
	 */
	private static function check_rankmath_organization_schema() {
		// Rank Math outputs Organization schema if configured in General Settings > Local SEO
		if ( ! function_exists( 'rank_math' ) ) {
			return false;
		}

		// Check if organization name is set
		$org_name = get_option( 'rank_math_knowledgegraph_name' );
		$knowledgegraph_type = get_option( 'rank_math_knowledgegraph_type' );

		// If organization type is selected and name is set
		if ( 'company' === $knowledgegraph_type && ! empty( $org_name ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if All in One SEO is outputting Organization schema.
	 *
	 * @return bool
	 */
	private static function check_aioseo_organization_schema() {
		// AIOSEO outputs Organization schema if configured in Search Appearance > Global Settings
		if ( ! function_exists( 'aioseo' ) ) {
			return false;
		}

		// Check AIOSEO options
		$options = get_option( 'aioseo_options' );

		if ( empty( $options ) ) {
			return false;
		}

		// Check if organization is selected and name is set
		if ( isset( $options['searchAppearance']['global']['schema']['organizationName'] )
		     && ! empty( $options['searchAppearance']['global']['schema']['organizationName'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if SEOPress is outputting Organization schema.
	 *
	 * @return bool
	 */
	private static function check_seopress_organization_schema() {
		// SEOPress outputs Organization schema if configured in Titles and Metas > Social Networks
		if ( ! function_exists( 'seopress_get_service' ) ) {
			return false;
		}

		$org_name = get_option( 'seopress_social_knowledge_name' );
		$org_type = get_option( 'seopress_social_knowledge_type' );

		// If organization type and name are set
		if ( 'Organization' === $org_type && ! empty( $org_name ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get Organization conflict warning message.
	 *
	 * @return string|null HTML warning or null.
	 */
	public static function get_organization_conflict_warning() {
		$conflicts = self::detect_organization_conflicts();

		if ( empty( $conflicts ) ) {
			return null;
		}

		$conflict = $conflicts[0]; // Show first conflict

		ob_start();
		?>
		<div class="notice notice-warning" style="padding: 12px; margin: 20px 0;">
			<h3 style="margin-top: 0;">
				<span class="dashicons dashicons-warning" style="color: #f0b849;"></span>
				<?php esc_html_e( 'Organization Schema Conflict Detected', 'fatlabschema' ); ?>
			</h3>
			<p>
				<?php
				printf(
					/* translators: 1: Plugin name */
					esc_html__( '%s is already configured to output Organization schema on your site. Having duplicate Organization schema on every page can confuse search engines and harm your SEO.', 'fatlabschema' ),
					'<strong>' . esc_html( $conflict['name'] ) . '</strong>'
				);
				?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Recommendation:', 'fatlabschema' ); ?></strong>
				<?php esc_html_e( 'Choose one plugin to manage Organization schema site-wide.', 'fatlabschema' ); ?>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}
}
