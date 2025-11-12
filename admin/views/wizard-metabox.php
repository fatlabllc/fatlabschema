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
?>

<div class="fls-wizard-container" data-post-id="<?php echo esc_attr( $post->ID ); ?>">

	<?php
	// Check if post has any schemas using new system
	$has_schemas = FLS_Schema_Manager::has_schemas( $post->ID );
	$schemas = FLS_Schema_Manager::get_schemas( $post->ID );
	$schema_count = count( $schemas );
	?>

	<!-- Schemas Manager View -->
	<div class="fls-schemas-manager" style="<?php echo $has_schemas ? '' : 'display:none;'; ?>">
		<?php if ( $has_schemas ) : ?>
			<div class="fls-schemas-header">
				<div class="fls-status-indicator fls-status-active">
					<span class="dashicons dashicons-yes-alt"></span>
					<strong>
						<?php
						printf(
							/* translators: %d: Number of schemas */
							esc_html( _n( '%d schema configured', '%d schemas configured', $schema_count, 'fatlabschema' ) ),
							$schema_count
						);
						?>
					</strong>
				</div>
				<p class="description">
					<?php esc_html_e( 'Schemas will appear in search results and AI engines.', 'fatlabschema' ); ?>
				</p>

				<?php
				// Show Google Rich Results test button for published posts
				$post_status = get_post_status( $post->ID );
				$permalink = get_permalink( $post->ID );
				if ( 'publish' === $post_status && $permalink ) :
					$test_url = 'https://search.google.com/test/rich-results?url=' . urlencode( $permalink );
					?>
					<p class="fls-test-schema">
						<a href="<?php echo esc_url( $test_url ); ?>" target="_blank" class="button button-primary">
							<span class="dashicons dashicons-external" style="margin-top: 3px;"></span>
							<?php esc_html_e( 'Test with Google Rich Results', 'fatlabschema' ); ?>
						</a>
						<span class="description" style="display: block; margin-top: 8px;">
							<?php esc_html_e( 'Validate your schema markup with Google\'s testing tool.', 'fatlabschema' ); ?>
						</span>
					</p>
				<?php elseif ( 'publish' !== $post_status ) : ?>
					<p class="fls-test-schema">
						<button type="button" class="button button-primary" disabled>
							<span class="dashicons dashicons-external" style="margin-top: 3px;"></span>
							<?php esc_html_e( 'Test with Google Rich Results', 'fatlabschema' ); ?>
						</button>
						<span class="description" style="display: block; margin-top: 8px; color: #646970;">
							<?php esc_html_e( 'Publish this post to test with Google Rich Results.', 'fatlabschema' ); ?>
						</span>
					</p>
				<?php endif; ?>
			</div>

			<!-- Schemas List -->
			<div class="fls-schemas-list-wrapper">
				<?php
				foreach ( $schemas as $schema_id => $schema ) :
					$type = $schema['type'];
					$enabled = isset( $schema['enabled'] ) ? $schema['enabled'] : true;
					$type_info = isset( $schema_types[ $type ] ) ? $schema_types[ $type ] : array(
						'label' => ucfirst( $type ),
						'icon'  => 'dashicons-admin-generic',
					);
					?>
					<div class="fls-schema-item" data-schema-id="<?php echo esc_attr( $schema_id ); ?>">
						<div class="fls-schema-item-header">
							<span class="dashicons <?php echo esc_attr( $type_info['icon'] ); ?>"></span>
							<span class="fls-schema-type-label"><?php echo esc_html( $type_info['label'] ); ?></span>
						</div>
						<div class="fls-schema-item-actions">
							<button type="button" class="button fls-edit-single-schema" data-schema-id="<?php echo esc_attr( $schema_id ); ?>" data-schema-type="<?php echo esc_attr( $type ); ?>">
								<?php esc_html_e( 'Edit', 'fatlabschema' ); ?>
							</button>
							<button type="button" class="button fls-delete-single-schema" data-schema-id="<?php echo esc_attr( $schema_id ); ?>">
								<?php esc_html_e( 'Delete', 'fatlabschema' ); ?>
							</button>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Action Buttons -->
			<p class="fls-manager-actions">
				<button type="button" class="button button-primary fls-add-another-schema">
					<span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span>
					<?php esc_html_e( 'Add Another Schema', 'fatlabschema' ); ?>
				</button>
				<button type="button" class="button fls-remove-all-schemas">
					<?php esc_html_e( 'Remove All Schemas', 'fatlabschema' ); ?>
				</button>
			</p>
		<?php endif; ?>
	</div>

	<!-- Wizard Step 1: Schema Type Selection (always present, hidden when schemas exist) -->
	<div class="fls-wizard-step fls-step-1" style="<?php echo $has_schemas ? 'display:none;' : ''; ?>">
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
			<?php if ( $has_schemas ) : ?>
				<button type="button" class="button fls-cancel-add-schema">
					<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
				</button>
			<?php endif; ?>
		</p>
	</div>

	<!-- Step 2: Recommendation/Form (populated by JavaScript) -->
	<div class="fls-wizard-step fls-step-2" style="display:none;">
		<!-- Content will be loaded via AJAX -->
	</div>

</div>

<style>
.fls-wizard-container {
	padding: 10px 0;
}

/* Schema Types Grid */
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

/* Status Indicator */
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

/* Schemas Manager */
.fls-schemas-manager {
	margin-bottom: 20px;
}

.fls-schemas-header {
	margin-bottom: 20px;
}

.fls-schemas-list-wrapper {
	margin: 15px 0;
}

.fls-schema-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 12px 15px;
	margin-bottom: 10px;
	border: 1px solid #ddd;
	border-radius: 4px;
	background: #fff;
	transition: all 0.2s;
}

.fls-schema-item:hover {
	border-color: #2271b1;
	background: #f6f7f7;
}

.fls-schema-item-header {
	display: flex;
	align-items: center;
	gap: 10px;
	flex: 1;
}

.fls-schema-item-header .dashicons {
	color: #2271b1;
	font-size: 20px;
	width: 20px;
	height: 20px;
}

.fls-schema-type-label {
	font-weight: 600;
	font-size: 14px;
}

.fls-schema-item-actions {
	display: flex;
	gap: 5px;
}

.fls-schema-item-actions .button {
	padding: 4px 10px;
	height: auto;
	line-height: 1.4;
}

.fls-manager-actions {
	margin-top: 15px;
	padding-top: 15px;
	border-top: 1px solid #ddd;
}

.fls-manager-actions .dashicons {
	font-size: 16px;
	width: 16px;
	height: 16px;
	vertical-align: middle;
}

/* Conflict Warning */
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

.fls-warning-content .fls-recommendation {
	margin-bottom: 5px;
	color: #856404;
	background: none;
	border: none;
	padding: 0;
}

.fls-warning-actions {
	margin-top: 10px;
}

.required {
	color: #d63638;
}
</style>
