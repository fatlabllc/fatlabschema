<?php
/**
 * HowTo schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure steps array exists
if ( ! isset( $data['steps'] ) || ! is_array( $data['steps'] ) ) {
	$data['steps'] = array(
		array( 'name' => '', 'text' => '', 'image' => '' ),
		array( 'name' => '', 'text' => '', 'image' => '' ),
	);
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'HowTo Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Use this schema for step-by-step guides and tutorials. Great for "How to volunteer," "How to donate," and instructional content.', 'fatlabschema' ); ?></p>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_howto_name"><?php esc_html_e( 'HowTo Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_howto_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="large-text" required placeholder="<?php esc_attr_e( 'How to...', 'fatlabschema' ); ?>" />
				<p class="description"><?php esc_html_e( 'The title of your how-to guide.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_howto_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_howto_description" name="fatlabschema_data[description]" rows="3" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Brief description of what this guide covers.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_howto_image"><?php esc_html_e( 'Main Image', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_howto_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="large-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'HowTo image', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_total_time"><?php esc_html_e( 'Total Time', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_total_time" name="fatlabschema_data[total_time]" value="<?php echo esc_attr( $data['total_time'] ?? '' ); ?>" class="regular-text" placeholder="PT30M" />
				<p class="description"><?php esc_html_e( 'ISO 8601 duration format (e.g., PT30M = 30 minutes, PT2H = 2 hours, P1D = 1 day).', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Steps', 'fatlabschema' ); ?> <span class="required">*</span></h4>

	<p><?php esc_html_e( 'Add the steps for your how-to guide:', 'fatlabschema' ); ?></p>

	<div class="fls-repeater-container">
		<div class="fls-repeater-items">
			<?php foreach ( $data['steps'] as $index => $step ) : ?>
				<div class="fls-repeater-item">
					<a href="#" class="fls-remove-repeater-item dashicons dashicons-no-alt" title="<?php esc_attr_e( 'Remove this step', 'fatlabschema' ); ?>"></a>

					<table class="form-table">
						<tr>
							<th scope="row" style="width: 150px;">
								<strong><?php echo esc_html( sprintf( __( 'Step %d', 'fatlabschema' ), $index + 1 ) ); ?></strong>
							</th>
							<td></td>
						</tr>

						<tr>
							<th scope="row">
								<label for="fls_step_name_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Step Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
							</th>
							<td>
								<input type="text" id="fls_step_name_<?php echo esc_attr( $index ); ?>" name="fatlabschema_data[steps][<?php echo esc_attr( $index ); ?>][name]" value="<?php echo esc_attr( $step['name'] ?? '' ); ?>" class="large-text" required placeholder="<?php esc_attr_e( 'Short step title', 'fatlabschema' ); ?>" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="fls_step_text_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Step Instructions', 'fatlabschema' ); ?> <span class="required">*</span></label>
							</th>
							<td>
								<textarea id="fls_step_text_<?php echo esc_attr( $index ); ?>" name="fatlabschema_data[steps][<?php echo esc_attr( $index ); ?>][text]" rows="3" class="large-text" required placeholder="<?php esc_attr_e( 'Detailed instructions for this step...', 'fatlabschema' ); ?>"><?php echo esc_textarea( $step['text'] ?? '' ); ?></textarea>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="fls_step_image_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Step Image', 'fatlabschema' ); ?></label>
							</th>
							<td>
								<div class="fls-media-upload">
									<input type="url" id="fls_step_image_<?php echo esc_attr( $index ); ?>" name="fatlabschema_data[steps][<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $step['image'] ?? '' ); ?>" class="large-text fls-media-url" />
									<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
									<?php if ( ! empty( $step['image'] ) ) : ?>
										<div class="fls-media-preview">
											<img src="<?php echo esc_url( $step['image'] ); ?>" alt="<?php esc_attr_e( 'Step image', 'fatlabschema' ); ?>" style="max-width: 150px; margin-top: 10px;" />
										</div>
									<?php endif; ?>
								</div>
							</td>
						</tr>
					</table>
				</div>
			<?php endforeach; ?>
		</div>

		<button type="button" class="button fls-add-repeater-item">
			<span class="dashicons dashicons-plus-alt"></span>
			<?php esc_html_e( 'Add Another Step', 'fatlabschema' ); ?>
		</button>

		<!-- Template for new steps -->
		<script type="text/html" class="fls-repeater-template">
			<div class="fls-repeater-item">
				<a href="#" class="fls-remove-repeater-item dashicons dashicons-no-alt" title="<?php esc_attr_e( 'Remove this step', 'fatlabschema' ); ?>"></a>

				<table class="form-table">
					<tr>
						<th scope="row" style="width: 150px;">
							<strong><?php esc_html_e( 'New Step', 'fatlabschema' ); ?></strong>
						</th>
						<td></td>
					</tr>

					<tr>
						<th scope="row">
							<label for="fls_step_name_[INDEX]"><?php esc_html_e( 'Step Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
						</th>
						<td>
							<input type="text" id="fls_step_name_[INDEX]" name="fatlabschema_data[steps][[INDEX]][name]" value="" class="large-text" required placeholder="<?php esc_attr_e( 'Short step title', 'fatlabschema' ); ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="fls_step_text_[INDEX]"><?php esc_html_e( 'Step Instructions', 'fatlabschema' ); ?> <span class="required">*</span></label>
						</th>
						<td>
							<textarea id="fls_step_text_[INDEX]" name="fatlabschema_data[steps][[INDEX]][text]" rows="3" class="large-text" required placeholder="<?php esc_attr_e( 'Detailed instructions for this step...', 'fatlabschema' ); ?>"></textarea>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="fls_step_image_[INDEX]"><?php esc_html_e( 'Step Image', 'fatlabschema' ); ?></label>
						</th>
						<td>
							<div class="fls-media-upload">
								<input type="url" id="fls_step_image_[INDEX]" name="fatlabschema_data[steps][[INDEX]][image]" value="" class="large-text fls-media-url" />
								<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</script>
	</div>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save HowTo Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>

	<div class="fls-help-box" style="margin-top: 20px;">
		<h4><?php esc_html_e( 'Tips for HowTo Schema', 'fatlabschema' ); ?></h4>
		<ul>
			<li><?php esc_html_e( 'Each step should be clear and actionable', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Adding images to steps improves visibility in search results', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Steps will appear numbered in search results', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Minimum 2-3 steps recommended', 'fatlabschema' ); ?></li>
		</ul>
	</div>
</div>
