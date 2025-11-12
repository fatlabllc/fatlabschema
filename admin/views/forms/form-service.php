<?php
/**
 * Service schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Service Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Use this schema to describe services your organization offers, such as counseling, consulting, legal aid, hosting, or any professional service.', 'fatlabschema' ); ?></p>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_service_name"><?php esc_html_e( 'Service Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_service_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="large-text" required />
				<p class="description"><?php esc_html_e( 'The name of the service you provide.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_service_type"><?php esc_html_e( 'Service Type', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_service_type" name="fatlabschema_data[service_type]" value="<?php echo esc_attr( $data['service_type'] ?? '' ); ?>" class="large-text" required placeholder="<?php esc_attr_e( 'e.g., Web Hosting, Legal Consulting, Counseling', 'fatlabschema' ); ?>" />
				<p class="description"><?php esc_html_e( 'Category or type of service.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_service_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_service_description" name="fatlabschema_data[description]" rows="4" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Detailed description of what the service includes.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_service_image"><?php esc_html_e( 'Service Image', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_service_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="large-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'Service image', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Provider Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_provider_name"><?php esc_html_e( 'Provider Name', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_provider_name" name="fatlabschema_data[provider_name]" value="<?php echo esc_attr( $data['provider_name'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Organization or person providing the service (auto-filled from Organization schema).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_area_served"><?php esc_html_e( 'Area Served', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_area_served" name="fatlabschema_data[area_served]" value="<?php echo esc_attr( $data['area_served'] ?? '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g., United States, California, San Francisco', 'fatlabschema' ); ?>" />
				<p class="description"><?php esc_html_e( 'Geographic area where the service is available.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Pricing (Optional)', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_price"><?php esc_html_e( 'Price', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="number" id="fls_price" name="fatlabschema_data[price]" value="<?php echo esc_attr( $data['price'] ?? '' ); ?>" class="small-text" step="0.01" min="0" />
				<select name="fatlabschema_data[price_currency]" class="small-text">
					<option value="USD" <?php selected( $data['price_currency'] ?? 'USD', 'USD' ); ?>>USD</option>
					<option value="EUR" <?php selected( $data['price_currency'] ?? 'USD', 'EUR' ); ?>>EUR</option>
					<option value="GBP" <?php selected( $data['price_currency'] ?? 'USD', 'GBP' ); ?>>GBP</option>
					<option value="CAD" <?php selected( $data['price_currency'] ?? 'USD', 'CAD' ); ?>>CAD</option>
				</select>
				<p class="description"><?php esc_html_e( 'Service price (leave blank if pricing varies or is by quote).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_service_url"><?php esc_html_e( 'Service URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_service_url" name="fatlabschema_data[url]" value="<?php echo esc_attr( $data['url'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Link to more information or booking page for this service.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Service Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>
</div>
