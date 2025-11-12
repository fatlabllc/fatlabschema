<?php
/**
 * Wizard meta box view.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get schema types
$schema_types = FLS_Wizard::get_schema_types();
$current_type = $schema_type ?? 'none';
?>

<div class="fls-wizard-container" data-post-id="<?php echo esc_attr( $post->ID ); ?>">

	<?php if ( empty( $wizard_completed ) || empty( $schema_type ) ) : ?>
		<!-- Step 1: Content Type Selection -->
		<div class="fls-wizard-step fls-step-1">
			<h3><?php esc_html_e( 'What type of content is this page?', 'fatlabschema' ); ?></h3>
			<p class="description"><?php esc_html_e( 'Select the option that best describes this content:', 'fatlabschema' ); ?></p>

			<div class="fls-schema-types">
				<?php foreach ( $schema_types as $type => $info ) : ?>
					<label class="fls-schema-type-option">
						<input type="radio" name="fatlabschema_type_selection" value="<?php echo esc_attr( $type ); ?>" />
						<span class="dashicons <?php echo esc_attr( $info['icon'] ); ?>"></span>
						<div class="fls-type-info">
							<strong><?php echo esc_html( $info['label'] ); ?></strong>
							<span class="description"><?php echo esc_html( $info['description'] ); ?></span>
						</div>
					</label>
				<?php endforeach; ?>
			</div>

			<p class="fls-wizard-actions">
				<button type="button" class="button button-primary fls-wizard-continue">
					<?php esc_html_e( 'Continue', 'fatlabschema' ); ?>
				</button>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=fatlabschema-help' ) ); ?>" class="button button-secondary" target="_blank">
					<?php esc_html_e( 'Learn More About Schema', 'fatlabschema' ); ?>
				</a>
			</p>
		</div>

		<!-- Step 2: Recommendation (populated by JavaScript) -->
		<div class="fls-wizard-step fls-step-2" style="display:none;">
			<!-- Content will be loaded via AJAX -->
		</div>

	<?php else : ?>
		<!-- Schema Already Configured -->
		<div class="fls-wizard-summary">
			<div class="fls-status-indicator fls-status-active">
				<span class="dashicons dashicons-yes-alt"></span>
				<strong>
					<?php
					$type_info = isset( $schema_types[ $current_type ] ) ? $schema_types[ $current_type ]['label'] : ucfirst( $current_type );
					printf(
						/* translators: %s: Schema type label */
						esc_html__( '%s schema active', 'fatlabschema' ),
						esc_html( $type_info )
					);
					?>
				</strong>
			</div>

			<p class="description">
				<?php esc_html_e( 'Schema is configured for this page and will appear in search results.', 'fatlabschema' ); ?>
			</p>

			<p>
				<button type="button" class="button fls-edit-schema"><?php esc_html_e( 'Edit Schema', 'fatlabschema' ); ?></button>
				<button type="button" class="button fls-run-wizard-again"><?php esc_html_e( 'Run Wizard Again', 'fatlabschema' ); ?></button>
				<button type="button" class="button fls-remove-schema"><?php esc_html_e( 'Remove Schema', 'fatlabschema' ); ?></button>
			</p>

			<?php
			// Check for conflicts
			if ( 'none' !== $current_type ) {
				$conflict_warning = FLS_Conflict_Detector::show_conflict_warning( $post->ID, $current_type );
				if ( $conflict_warning ) {
					echo $conflict_warning; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Already escaped in method
				}
			}
			?>
		</div>

		<!-- Schema Form (hidden by default, shown when editing) -->
		<div class="fls-schema-form" style="display:none;">
			<h3><?php esc_html_e( 'Schema Details', 'fatlabschema' ); ?></h3>
			<p class="description"><?php esc_html_e( 'Fill in the details for your schema markup:', 'fatlabschema' ); ?></p>

			<!-- Schema fields will be loaded here based on type -->
			<div class="fls-schema-fields">
				<p><?php esc_html_e( 'Schema forms will be available in the next development phase.', 'fatlabschema' ); ?></p>
			</div>

			<p>
				<button type="button" class="button button-primary fls-save-schema"><?php esc_html_e( 'Save Schema', 'fatlabschema' ); ?></button>
				<button type="button" class="button fls-cancel-edit"><?php esc_html_e( 'Cancel', 'fatlabschema' ); ?></button>
			</p>
		</div>

	<?php endif; ?>

	<!-- Hidden fields -->
	<input type="hidden" name="fatlabschema_type" value="<?php echo esc_attr( $schema_type ?? '' ); ?>" />
	<input type="hidden" name="fatlabschema_enabled" value="<?php echo esc_attr( $schema_enabled ? '1' : '0' ); ?>" />
	<input type="hidden" name="fatlabschema_wizard_completed" value="<?php echo esc_attr( $wizard_completed ? '1' : '0' ); ?>" />

</div>

<style>
.fls-wizard-container {
	padding: 10px 0;
}

.fls-schema-types {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
	gap: 10px;
	margin: 15px 0;
}

.fls-schema-type-option {
	display: flex;
	align-items: flex-start;
	padding: 15px;
	border: 2px solid #ddd;
	border-radius: 4px;
	cursor: pointer;
	transition: all 0.2s;
}

.fls-schema-type-option:hover {
	border-color: #2271b1;
	background: #f6f7f7;
}

.fls-schema-type-option input[type="radio"] {
	margin: 3px 10px 0 0;
}

.fls-schema-type-option .dashicons {
	font-size: 24px;
	width: 24px;
	height: 24px;
	margin-right: 10px;
	color: #2271b1;
}

.fls-type-info {
	display: flex;
	flex-direction: column;
	gap: 5px;
}

.fls-type-info strong {
	display: block;
	margin-bottom: 5px;
}

.fls-wizard-actions {
	margin-top: 20px;
}

.fls-status-indicator {
	display: flex;
	align-items: center;
	padding: 10px;
	background: #d4edda;
	border-left: 4px solid #28a745;
	margin-bottom: 15px;
}

.fls-status-indicator .dashicons {
	color: #28a745;
	margin-right: 8px;
}

.fls-conflict-warning {
	display: flex;
	padding: 15px;
	background: #fff3cd;
	border-left: 4px solid #ffc107;
	margin: 15px 0;
}

.fls-warning-icon {
	margin-right: 15px;
}

.fls-warning-icon .dashicons {
	color: #ffc107;
	font-size: 24px;
	width: 24px;
	height: 24px;
}

.fls-warning-content h4 {
	margin-top: 0;
}

.fls-warning-actions {
	margin-top: 10px;
}

.required {
	color: #d63638;
}
</style>
