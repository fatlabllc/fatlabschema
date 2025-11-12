<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package FatLab_Schema_Wizard
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete all plugin data.
 */
function fatlabschema_uninstall() {
	global $wpdb;

	// Check if user wants to preserve data
	$settings = get_option( 'fatlabschema_settings', array() );
	if ( isset( $settings['preserve_data_on_uninstall'] ) && $settings['preserve_data_on_uninstall'] ) {
		return; // Don't delete anything
	}

	// Delete all post meta
	$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_fatlabschema_%'" );

	// Delete all options
	delete_option( 'fatlabschema_settings' );
	delete_option( 'fatlabschema_organization' );
	delete_option( 'fatlabschema_version' );
	delete_option( 'fatlabschema_activated' );

	// Delete all transients
	delete_transient( 'fatlabschema_activation_notice' );

	// Delete all transients (including expired ones)
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_fatlabschema_%'" );
	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_fatlabschema_%'" );

	// Delete all user meta (for dismissed notices)
	$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE 'fatlabschema_%'" );

	// For multisite: delete from all sites
	if ( is_multisite() ) {
		$sites = get_sites( array( 'number' => 0 ) );

		foreach ( $sites as $site ) {
			switch_to_blog( $site->blog_id );

			// Delete post meta for this site
			$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_fatlabschema_%'" );

			// Delete options for this site
			delete_option( 'fatlabschema_settings' );
			delete_option( 'fatlabschema_organization' );
			delete_option( 'fatlabschema_version' );
			delete_option( 'fatlabschema_activated' );

			// Delete transients for this site
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_fatlabschema_%'" );
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_fatlabschema_%'" );

			restore_current_blog();
		}
	}

	// Clear any cached data
	wp_cache_flush();
}

fatlabschema_uninstall();
