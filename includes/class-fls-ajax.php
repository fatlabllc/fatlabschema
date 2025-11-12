<?php
/**
 * AJAX handler for wizard interactions.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX handler class.
 */
class FLS_Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Wizard interactions
		add_action( 'wp_ajax_fls_get_recommendation', array( $this, 'get_recommendation' ) );
		add_action( 'wp_ajax_fls_save_schema', array( $this, 'save_schema' ) );
		add_action( 'wp_ajax_fls_preview_schema', array( $this, 'preview_schema' ) );
		add_action( 'wp_ajax_fls_get_schema_form', array( $this, 'get_schema_form' ) );
		add_action( 'wp_ajax_fls_remove_schema', array( $this, 'remove_schema' ) );
		add_action( 'wp_ajax_fls_reset_wizard', array( $this, 'reset_wizard' ) );
	}

	/**
	 * Get recommendation for schema type.
	 */
	public function get_recommendation() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$schema_type = isset( $_POST['schema_type'] ) ? sanitize_text_field( $_POST['schema_type'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( empty( $schema_type ) || empty( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request.', 'fatlabschema' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'fatlabschema' ) ) );
		}

		// Get recommendation
		$recommendation = FLS_Wizard::get_recommendation( $schema_type );
		$post = get_post( $post_id );

		// Check for conflicts
		$conflict_warning = '';
		if ( $recommendation['recommended'] && ( 'article' === $schema_type || 'scholarly' === $schema_type ) ) {
			$conflict_warning = FLS_Conflict_Detector::show_conflict_warning( $post_id, $schema_type );
		}

		// Build HTML response
		ob_start();
		$this->render_recommendation( $recommendation, $schema_type, $post, $conflict_warning );
		$html = ob_get_clean();

		wp_send_json_success( array(
			'html' => $html,
			'recommended' => $recommendation['recommended'],
			'schema_type' => $schema_type,
		) );
	}

	/**
	 * Render recommendation HTML.
	 *
	 * @param array   $recommendation Recommendation data.
	 * @param string  $schema_type Schema type.
	 * @param WP_Post $post Post object.
	 * @param string  $conflict_warning Conflict warning HTML.
	 */
	private function render_recommendation( $recommendation, $schema_type, $post, $conflict_warning ) {
		$settings = get_option( 'fatlabschema_settings', array() );
		$show_ai_badge = isset( $settings['show_ai_badges'] ) ? $settings['show_ai_badges'] : true;
		?>

		<div class="fls-recommendation <?php echo $recommendation['recommended'] ? '' : 'fls-no-schema'; ?>">
			<?php if ( $recommendation['recommended'] && $show_ai_badge ) : ?>
				<div class="fls-ai-badge">
					<span class="dashicons dashicons-admin-site-alt"></span>
					<?php esc_html_e( 'Optimized for AI Search', 'fatlabschema' ); ?>
				</div>
			<?php endif; ?>

			<h4><?php echo esc_html( $recommendation['title'] ); ?></h4>
			<p><?php echo esc_html( $recommendation['message'] ); ?></p>

			<?php if ( ! empty( $recommendation['benefits'] ) ) : ?>
				<p><strong><?php echo esc_html( $recommendation['benefits_title'] ); ?></strong></p>
				<ul>
					<?php foreach ( $recommendation['benefits'] as $benefit ) : ?>
						<li><?php echo esc_html( $benefit ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( ! empty( $recommendation['use_cases'] ) ) : ?>
				<p class="fls-use-cases"><?php echo esc_html( $recommendation['use_cases'] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $recommendation['note'] ) ) : ?>
				<p><em><?php echo esc_html( $recommendation['note'] ); ?></em></p>
			<?php endif; ?>

			<?php if ( $conflict_warning ) : ?>
				<?php echo $conflict_warning; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>

			<div class="fls-recommendation-actions">
				<?php if ( $recommendation['recommended'] ) : ?>
					<button type="button" class="button button-primary fls-add-schema" data-schema-type="<?php echo esc_attr( $schema_type ); ?>">
						<?php
						printf(
							/* translators: %s: Schema type label */
							esc_html__( 'Add %s Schema', 'fatlabschema' ),
							esc_html( str_replace( array( 'Recommended: ', ' Schema' ), '', $recommendation['title'] ) )
						);
						?>
					</button>
				<?php endif; ?>
				<button type="button" class="button fls-skip-schema">
					<?php esc_html_e( $recommendation['recommended'] ? 'Skip - No Schema Needed' : 'Close Wizard', 'fatlabschema' ); ?>
				</button>
				<button type="button" class="button fls-wizard-back">
					<?php esc_html_e( 'Go Back', 'fatlabschema' ); ?>
				</button>
			</div>
		</div>

		<?php
	}

	/**
	 * Get schema form for a specific type.
	 */
	public function get_schema_form() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$schema_type = isset( $_POST['schema_type'] ) ? sanitize_text_field( $_POST['schema_type'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( empty( $schema_type ) || empty( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request.', 'fatlabschema' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'fatlabschema' ) ) );
		}

		$post = get_post( $post_id );
		$existing_data = get_post_meta( $post_id, '_fatlabschema_data', true );

		// Auto-fill data if no existing data
		if ( empty( $existing_data ) ) {
			$data = FLS_Wizard::auto_fill_data( $post, $schema_type );
		} else {
			$data = $existing_data;
		}

		// Build form HTML
		ob_start();
		$this->render_schema_form( $schema_type, $data, $post );
		$html = ob_get_clean();

		wp_send_json_success( array(
			'html' => $html,
			'data' => $data,
		) );
	}

	/**
	 * Render schema form.
	 *
	 * @param string  $schema_type Schema type.
	 * @param array   $data Schema data.
	 * @param WP_Post $post Post object.
	 */
	private function render_schema_form( $schema_type, $data, $post ) {
		// Load the appropriate form template
		$form_file = FATLABSCHEMA_PATH . 'admin/views/forms/form-' . $schema_type . '.php';

		if ( file_exists( $form_file ) ) {
			include $form_file;
		} else {
			// Generic form for types without specific templates yet
			$this->render_generic_form( $schema_type, $data, $post );
		}
	}

	/**
	 * Render generic schema form (fallback).
	 *
	 * @param string  $schema_type Schema type.
	 * @param array   $data Schema data.
	 * @param WP_Post $post Post object.
	 */
	private function render_generic_form( $schema_type, $data, $post ) {
		?>
		<div class="fls-schema-form-wrapper">
			<h4><?php echo esc_html( ucfirst( $schema_type ) ); ?> <?php esc_html_e( 'Schema', 'fatlabschema' ); ?></h4>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="fls_name"><?php esc_html_e( 'Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
					</th>
					<td>
						<input type="text" id="fls_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? $post->post_title ); ?>" class="regular-text" required />
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="fls_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
					</th>
					<td>
						<textarea id="fls_description" name="fatlabschema_data[description]" rows="3" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
					</td>
				</tr>
			</table>

			<p class="description">
				<?php
				printf(
					/* translators: %s: Schema type */
					esc_html__( 'The %s schema form will be fully implemented in Phase 3-4.', 'fatlabschema' ),
					esc_html( $schema_type )
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Save schema data.
	 */
	public function save_schema() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$schema_type = isset( $_POST['schema_type'] ) ? sanitize_text_field( $_POST['schema_type'] ) : '';
		$schema_data = isset( $_POST['fatlabschema_data'] ) ? $_POST['fatlabschema_data'] : array();

		if ( empty( $post_id ) || empty( $schema_type ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request.', 'fatlabschema' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'fatlabschema' ) ) );
		}

		// Validate schema data
		$validator = new FLS_Validator();
		$is_valid = $validator->validate_schema_data( $schema_type, $schema_data );

		if ( ! $is_valid ) {
			$errors = $validator->get_validation_errors();
			wp_send_json_error( array(
				'message' => __( 'Validation failed.', 'fatlabschema' ),
				'errors' => $errors,
			) );
		}

		// Save schema data
		update_post_meta( $post_id, '_fatlabschema_type', $schema_type );
		update_post_meta( $post_id, '_fatlabschema_data', $schema_data );
		update_post_meta( $post_id, '_fatlabschema_enabled', true );
		update_post_meta( $post_id, '_fatlabschema_wizard_completed', true );

		// Clear cache
		FLS_Output::clear_cache( $post_id );

		$warnings = $validator->get_validation_warnings();

		wp_send_json_success( array(
			'message' => __( 'Schema saved successfully!', 'fatlabschema' ),
			'warnings' => $warnings,
		) );
	}

	/**
	 * Preview schema JSON-LD.
	 */
	public function preview_schema() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$schema_type = isset( $_POST['schema_type'] ) ? sanitize_text_field( $_POST['schema_type'] ) : '';
		$schema_data = isset( $_POST['fatlabschema_data'] ) ? $_POST['fatlabschema_data'] : array();
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( empty( $schema_type ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request.', 'fatlabschema' ) ) );
		}

		// Generate schema
		$schema = FLS_Schema_Generator::generate_json_ld( $schema_type, $schema_data, $post_id );

		if ( empty( $schema ) ) {
			wp_send_json_error( array( 'message' => __( 'Failed to generate schema.', 'fatlabschema' ) ) );
		}

		// Format JSON
		$json = wp_json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

		wp_send_json_success( array(
			'json'   => $json,
			'schema' => $schema,
		) );
	}

	/**
	 * Remove schema from post.
	 */
	public function remove_schema() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( empty( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request.', 'fatlabschema' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'fatlabschema' ) ) );
		}

		// Remove schema data
		delete_post_meta( $post_id, '_fatlabschema_type' );
		delete_post_meta( $post_id, '_fatlabschema_data' );
		delete_post_meta( $post_id, '_fatlabschema_enabled' );
		delete_post_meta( $post_id, '_fatlabschema_wizard_completed' );

		// Clear cache
		FLS_Output::clear_cache( $post_id );

		wp_send_json_success( array(
			'message' => __( 'Schema removed successfully.', 'fatlabschema' ),
		) );
	}

	/**
	 * Reset wizard for post.
	 */
	public function reset_wizard() {
		check_ajax_referer( 'fatlabschema_admin_nonce', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( empty( $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request.', 'fatlabschema' ) ) );
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'fatlabschema' ) ) );
		}

		// Reset wizard completion flag
		update_post_meta( $post_id, '_fatlabschema_wizard_completed', false );

		wp_send_json_success( array(
			'message' => __( 'Wizard reset successfully.', 'fatlabschema' ),
		) );
	}
}
