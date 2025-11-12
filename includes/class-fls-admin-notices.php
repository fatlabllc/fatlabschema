<?php
/**
 * Admin notices functionality.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin notices class.
 */
class FLS_Admin_Notices {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
		add_action( 'wp_ajax_fatlabschema_dismiss_notice', array( $this, 'dismiss_notice' ) );
	}

	/**
	 * Display admin notices.
	 */
	public function display_admin_notices() {
		// Welcome notice on activation
		if ( get_transient( 'fatlabschema_activation_notice' ) ) {
			$this->render_notice(
				'welcome',
				'success',
				sprintf(
					/* translators: %s: Settings page URL */
					__( 'Welcome to FatLab Schema Wizard! To get started, please <a href="%s">configure your Organization schema</a>.', 'fatlabschema' ),
					admin_url( 'admin.php?page=fatlabschema' )
				),
				true
			);
		}

		// Check if Organization schema is configured
		if ( $this->should_show_organization_notice() ) {
			$this->render_notice(
				'configure_organization',
				'warning',
				sprintf(
					/* translators: %s: Settings page URL */
					__( 'FatLab Schema Wizard: Please <a href="%s">configure your Organization schema</a> to get started. This is required for proper schema markup.', 'fatlabschema' ),
					admin_url( 'admin.php?page=fatlabschema' )
				),
				true
			);
		}

		// Settings saved notice
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] === 'true' ) {
			$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

			if ( strpos( $page, 'fatlabschema' ) === 0 ) {
				$this->render_notice(
					'settings_saved',
					'success',
					__( 'Settings saved successfully!', 'fatlabschema' ),
					false
				);
			}
		}
	}

	/**
	 * Check if organization notice should be shown.
	 *
	 * @return bool
	 */
	private function should_show_organization_notice() {
		// Don't show if user dismissed it
		if ( get_user_meta( get_current_user_id(), 'fatlabschema_dismissed_configure_organization', true ) ) {
			return false;
		}

		// Don't show on settings pages
		$screen = get_current_screen();
		if ( $screen && strpos( $screen->id, 'fatlabschema' ) !== false ) {
			return false;
		}

		// Check if organization is configured
		$organization = get_option( 'fatlabschema_organization', array() );

		return empty( $organization['name'] ) || empty( $organization['url'] );
	}

	/**
	 * Render a notice.
	 *
	 * @param string $id Notice ID.
	 * @param string $type Notice type (success, error, warning, info).
	 * @param string $message Notice message.
	 * @param bool   $dismissible Whether the notice is dismissible.
	 */
	private function render_notice( $id, $type, $message, $dismissible = true ) {
		$class = 'notice notice-' . $type;

		if ( $dismissible ) {
			$class .= ' is-dismissible';
		}

		?>
		<div class="<?php echo esc_attr( $class ); ?>" data-notice-id="<?php echo esc_attr( $id ); ?>">
			<p><?php echo wp_kses_post( $message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Handle AJAX request to dismiss a notice.
	 */
	public function dismiss_notice() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$notice_id = isset( $_POST['notice_id'] ) ? sanitize_text_field( $_POST['notice_id'] ) : '';

		if ( empty( $notice_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid notice ID.', 'fatlabschema' ) ) );
		}

		// Save dismissal in user meta
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'fatlabschema_dismissed_' . $notice_id, true );

		// Delete transient for activation notice
		if ( 'welcome' === $notice_id ) {
			delete_transient( 'fatlabschema_activation_notice' );
		}

		wp_send_json_success( array( 'message' => __( 'Notice dismissed.', 'fatlabschema' ) ) );
	}

	/**
	 * Display a success notice.
	 *
	 * @param string $message Notice message.
	 */
	public static function success( $message ) {
		add_settings_error(
			'fatlabschema_messages',
			'fatlabschema_message',
			$message,
			'success'
		);
	}

	/**
	 * Display an error notice.
	 *
	 * @param string $message Notice message.
	 */
	public static function error( $message ) {
		add_settings_error(
			'fatlabschema_messages',
			'fatlabschema_message',
			$message,
			'error'
		);
	}

	/**
	 * Display a warning notice.
	 *
	 * @param string $message Notice message.
	 */
	public static function warning( $message ) {
		add_settings_error(
			'fatlabschema_messages',
			'fatlabschema_message',
			$message,
			'warning'
		);
	}

	/**
	 * Display an info notice.
	 *
	 * @param string $message Notice message.
	 */
	public static function info( $message ) {
		add_settings_error(
			'fatlabschema_messages',
			'fatlabschema_message',
			$message,
			'info'
		);
	}
}
