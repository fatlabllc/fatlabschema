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
