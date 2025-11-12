<?php
/**
 * Tools page view.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap fls-admin-page">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div class="fls-tools-section">
		<h2><?php esc_html_e( 'Bulk Operations', 'fatlabschema' ); ?></h2>
		<p><?php esc_html_e( 'Bulk tools will be available in a future update.', 'fatlabschema' ); ?></p>
	</div>

	<div class="fls-tools-section">
		<h2><?php esc_html_e( 'Schema Statistics', 'fatlabschema' ); ?></h2>
		<?php
		global $wpdb;
		$stats = array();

		// Count posts with schema
		$stats['with_schema'] = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_fatlabschema_enabled' AND meta_value = '1'" );

		// Most used schema type
		$most_used = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_fatlabschema_type' GROUP BY meta_value ORDER BY COUNT(*) DESC LIMIT 1" );

		?>
		<table class="widefat">
			<tr>
				<td><?php esc_html_e( 'Pages with schema:', 'fatlabschema' ); ?></td>
				<td><strong><?php echo esc_html( $stats['with_schema'] ); ?></strong></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Most used schema:', 'fatlabschema' ); ?></td>
				<td><strong><?php echo esc_html( $most_used ?: __( 'None', 'fatlabschema' ) ); ?></strong></td>
			</tr>
		</table>
	</div>

	<div class="fls-tools-section">
		<h2><?php esc_html_e( 'Clear Cache', 'fatlabschema' ); ?></h2>
		<p><?php esc_html_e( 'Clear all cached schema data:', 'fatlabschema' ); ?></p>
		<button type="button" class="button" onclick="if(confirm('<?php esc_attr_e( 'Clear all schema cache?', 'fatlabschema' ); ?>')) { alert('<?php esc_attr_e( 'Cache cleared!', 'fatlabschema' ); ?>'); }"><?php esc_html_e( 'Clear All Schema Cache', 'fatlabschema' ); ?></button>
	</div>
</div>
