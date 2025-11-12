<?php
/**
 * LocalBusiness schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'LocalBusiness Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Use this schema for physical business locations with an address. Great for offices, retail stores, campaign headquarters, and service locations.', 'fatlabschema' ); ?></p>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_business_type"><?php esc_html_e( 'Business Type', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_business_type" name="fatlabschema_data[business_type]" class="regular-text">
					<option value="LocalBusiness" <?php selected( $data['business_type'] ?? 'LocalBusiness', 'LocalBusiness' ); ?>><?php esc_html_e( 'General Business', 'fatlabschema' ); ?></option>
					<option value="ProfessionalService" <?php selected( $data['business_type'] ?? '', 'ProfessionalService' ); ?>><?php esc_html_e( 'Professional Service', 'fatlabschema' ); ?></option>
					<option value="Store" <?php selected( $data['business_type'] ?? '', 'Store' ); ?>><?php esc_html_e( 'Retail Store', 'fatlabschema' ); ?></option>
					<option value="Restaurant" <?php selected( $data['business_type'] ?? '', 'Restaurant' ); ?>><?php esc_html_e( 'Restaurant', 'fatlabschema' ); ?></option>
					<option value="HealthAndBeautyBusiness" <?php selected( $data['business_type'] ?? '', 'HealthAndBeautyBusiness' ); ?>><?php esc_html_e( 'Health & Beauty', 'fatlabschema' ); ?></option>
					<option value="LegalService" <?php selected( $data['business_type'] ?? '', 'LegalService' ); ?>><?php esc_html_e( 'Legal Service', 'fatlabschema' ); ?></option>
					<option value="FinancialService" <?php selected( $data['business_type'] ?? '', 'FinancialService' ); ?>><?php esc_html_e( 'Financial Service', 'fatlabschema' ); ?></option>
					<option value="RealEstateAgent" <?php selected( $data['business_type'] ?? '', 'RealEstateAgent' ); ?>><?php esc_html_e( 'Real Estate Agent', 'fatlabschema' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'Choose the category that best describes this location.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_business_name"><?php esc_html_e( 'Business Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_business_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'The official name of the business or location.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_business_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_business_description" name="fatlabschema_data[description]" rows="3" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'A brief description of the business and what it offers.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_business_url"><?php esc_html_e( 'Website URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_business_url" name="fatlabschema_data[url]" value="<?php echo esc_attr( $data['url'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Address', 'fatlabschema' ); ?> <span class="required">*</span></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_street_address"><?php esc_html_e( 'Street Address', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_street_address" name="fatlabschema_data[street_address]" value="<?php echo esc_attr( $data['street_address'] ?? '' ); ?>" class="regular-text" required />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_address_locality"><?php esc_html_e( 'City', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_address_locality" name="fatlabschema_data[address_locality]" value="<?php echo esc_attr( $data['address_locality'] ?? '' ); ?>" class="regular-text" required />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_address_region"><?php esc_html_e( 'State/Region', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_address_region" name="fatlabschema_data[address_region]" value="<?php echo esc_attr( $data['address_region'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_postal_code"><?php esc_html_e( 'Postal Code', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_postal_code" name="fatlabschema_data[postal_code]" value="<?php echo esc_attr( $data['postal_code'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_address_country"><?php esc_html_e( 'Country', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_address_country" name="fatlabschema_data[address_country]" value="<?php echo esc_attr( $data['address_country'] ?? 'US' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Two-letter country code (e.g., US, GB, CA).', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Contact Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_telephone"><?php esc_html_e( 'Phone Number', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="tel" id="fls_telephone" name="fatlabschema_data[telephone]" value="<?php echo esc_attr( $data['telephone'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Include country code (e.g., +1-555-123-4567).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_business_image"><?php esc_html_e( 'Business Image', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_business_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="regular-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'Business image', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Photo of the business location or storefront.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Business Details (Optional)', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_opening_hours"><?php esc_html_e( 'Opening Hours', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_opening_hours" name="fatlabschema_data[opening_hours]" rows="3" class="regular-text" placeholder="Mo-Fr 09:00-17:00&#10;Sa 10:00-14:00"><?php echo esc_textarea( $data['opening_hours'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Format: Mo-Fr 09:00-17:00 (one per line). Days: Mo, Tu, We, Th, Fr, Sa, Su.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_price_range"><?php esc_html_e( 'Price Range', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_price_range" name="fatlabschema_data[price_range]">
					<option value="">-</option>
					<option value="$" <?php selected( $data['price_range'] ?? '', '$' ); ?>>$ (Inexpensive)</option>
					<option value="$$" <?php selected( $data['price_range'] ?? '', '$$' ); ?>>$$ (Moderate)</option>
					<option value="$$$" <?php selected( $data['price_range'] ?? '', '$$$' ); ?>>$$$ (Expensive)</option>
					<option value="$$$$" <?php selected( $data['price_range'] ?? '', '$$$$' ); ?>>$$$$ (Very Expensive)</option>
				</select>
				<p class="description"><?php esc_html_e( 'Approximate price range for products/services.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label><?php esc_html_e( 'Geo Coordinates', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" name="fatlabschema_data[latitude]" value="<?php echo esc_attr( $data['latitude'] ?? '' ); ?>" placeholder="37.7749" class="small-text" />
				<input type="text" name="fatlabschema_data[longitude]" value="<?php echo esc_attr( $data['longitude'] ?? '' ); ?>" placeholder="-122.4194" class="small-text" />
				<p class="description"><?php esc_html_e( 'Latitude and longitude for precise location (optional, helps with maps).', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save LocalBusiness Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>
</div>
