<?php
/**
 * Plugin settings page view.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = get_option( 'fatlabschema_settings', array() );
?>

<div class="wrap fls-admin-page">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'fatlabschema_plugin_settings' );
		?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Enable Plugin', 'fatlabschema' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fatlabschema_settings[enabled]" value="1" <?php checked( $settings['enabled'] ?? true, true ); ?> />
						<?php esc_html_e( 'Enable FatLab Schema Wizard', 'fatlabschema' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Turn this off to disable all schema output without deactivating the plugin.', 'fatlabschema' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Conflict Detection', 'fatlabschema' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fatlabschema_settings[conflict_detection]" value="1" <?php checked( $settings['conflict_detection'] ?? true, true ); ?> />
						<?php esc_html_e( 'Detect conflicts with other SEO plugins', 'fatlabschema' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Recommended. Warns you when other plugins are adding duplicate schema.', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>

		<?php
		// Check if there are Organization schema conflicts
		$org_conflicts = FLS_Conflict_Detector::detect_organization_conflicts();
		if ( ! empty( $org_conflicts ) ) :
			$conflict = $org_conflicts[0];
			$schema_priority = $settings['organization_schema_priority'] ?? 'suppress_others';
			?>

			<div style="background: #f0f6fc; border: 2px solid #2271b1; border-radius: 6px; padding: 20px; margin: 20px 0;">
				<h2 style="margin-top: 0; color: #2271b1;">
					<span class="dashicons dashicons-shield" style="font-size: 24px; width: 24px; height: 24px; vertical-align: middle;"></span>
					<?php esc_html_e( 'Organization Schema Priority', 'fatlabschema' ); ?>
				</h2>

				<div style="background: #fff3cd; border-left: 4px solid #f0b849; padding: 12px; margin-bottom: 20px;">
					<p style="margin: 0;">
						<strong><?php esc_html_e( 'Conflict Detected:', 'fatlabschema' ); ?></strong>
						<?php
						printf(
							/* translators: %s: Plugin name */
							esc_html__( '%s is configured to output Organization schema. Choose how FatLab Schema should handle this:', 'fatlabschema' ),
							'<strong>' . esc_html( $conflict['name'] ) . '</strong>'
						);
						?>
					</p>
				</div>

				<table class="form-table" role="presentation" style="margin-top: 0;">
					<tr>
						<th scope="row" style="padding-top: 0;">
							<?php esc_html_e( 'How should Organization schema be managed?', 'fatlabschema' ); ?>
						</th>
						<td style="padding-top: 0;">
							<fieldset>
								<label style="display: block; margin-bottom: 15px; padding: 15px; background: white; border: 2px solid #2271b1; border-radius: 4px;">
									<input type="radio" name="fatlabschema_settings[organization_schema_priority]" value="suppress_others" <?php checked( $schema_priority, 'suppress_others' ); ?> style="margin-top: 0;" />
									<strong style="font-size: 14px;"><?php esc_html_e( 'Use FatLab Schema - suppress other plugins', 'fatlabschema' ); ?></strong>
									<span style="background: #2271b1; color: white; padding: 3px 10px; border-radius: 3px; font-size: 11px; font-weight: 600; margin-left: 8px;"><?php esc_html_e( 'RECOMMENDED', 'fatlabschema' ); ?></span>
									<p class="description" style="margin: 8px 0 0 25px;">
										<?php
										printf(
											/* translators: %s: Plugin name */
											esc_html__( 'Automatically disable Organization schema from %s. FatLab Schema will be the single source for Organization markup site-wide. This prevents duplicate schema issues.', 'fatlabschema' ),
											esc_html( $conflict['name'] )
										);
										?>
									</p>
								</label>

								<label style="display: block; margin-bottom: 15px; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 4px;">
									<input type="radio" name="fatlabschema_settings[organization_schema_priority]" value="warn_only" <?php checked( $schema_priority, 'warn_only' ); ?> style="margin-top: 0;" />
									<strong style="font-size: 14px;"><?php esc_html_e( 'Detect and warn only', 'fatlabschema' ); ?></strong>
									<p class="description" style="margin: 8px 0 0 25px;">
										<?php
										printf(
											/* translators: %s: Plugin name */
											esc_html__( 'Show warnings about conflicts. Both FatLab Schema and %s will output Organization schema (not recommended - can cause duplicate schema issues).', 'fatlabschema' ),
											esc_html( $conflict['name'] )
										);
										?>
									</p>
								</label>

								<label style="display: block; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 4px;">
									<input type="radio" name="fatlabschema_settings[organization_schema_priority]" value="allow_both" <?php checked( $schema_priority, 'allow_both' ); ?> style="margin-top: 0;" />
									<strong style="font-size: 14px;"><?php esc_html_e( 'Allow both (advanced users only)', 'fatlabschema' ); ?></strong>
									<p class="description" style="margin: 8px 0 0 25px;">
										<?php esc_html_e( 'No warnings, no suppression. Both plugins will output Organization schema. Only use this if you have a specific reason and understand the SEO implications of duplicate schema.', 'fatlabschema' ); ?>
									</p>
								</label>
							</fieldset>
						</td>
					</tr>
				</table>
			</div>

		<?php endif; ?>

		<table class="form-table" role="presentation">

			<tr>
				<th scope="row"><?php esc_html_e( 'AI Search Badges', 'fatlabschema' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fatlabschema_settings[show_ai_badges]" value="1" <?php checked( $settings['show_ai_badges'] ?? true, true ); ?> />
						<?php esc_html_e( 'Show "Optimized for AI Search" badges in wizard', 'fatlabschema' ); ?>
					</label>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Debug Mode', 'fatlabschema' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fatlabschema_settings[debug_mode]" value="1" <?php checked( $settings['debug_mode'] ?? false, true ); ?> />
						<?php esc_html_e( 'Enable advanced debug mode', 'fatlabschema' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'Shows additional information for troubleshooting. Only enable if requested by support.', 'fatlabschema' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php esc_html_e( 'Preserve Data', 'fatlabschema' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="fatlabschema_settings[preserve_data_on_uninstall]" value="1" <?php checked( $settings['preserve_data_on_uninstall'] ?? false, true ); ?> />
						<?php esc_html_e( 'Keep all schema data when uninstalling plugin', 'fatlabschema' ); ?>
					</label>
					<p class="description"><?php esc_html_e( 'By default, all data is deleted on uninstall. Check this to preserve your schema data.', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>
