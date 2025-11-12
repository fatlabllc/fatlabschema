<?php
/**
 * Event schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Event Schema', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_event_name"><?php esc_html_e( 'Event Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_event_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'The name of your event.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_event_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_event_description" name="fatlabschema_data[description]" rows="3" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'A brief description of the event.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_event_start_date"><?php esc_html_e( 'Start Date & Time', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="date" id="fls_event_start_date" name="fatlabschema_data[start_date]" value="<?php echo esc_attr( $data['start_date'] ?? '' ); ?>" class="fls-datepicker" required />
				<input type="time" name="fatlabschema_data[start_time]" value="<?php echo esc_attr( $data['start_time'] ?? '' ); ?>" class="fls-timepicker" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_event_end_date"><?php esc_html_e( 'End Date & Time', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="date" id="fls_event_end_date" name="fatlabschema_data[end_date]" value="<?php echo esc_attr( $data['end_date'] ?? '' ); ?>" class="fls-datepicker" />
				<input type="time" name="fatlabschema_data[end_time]" value="<?php echo esc_attr( $data['end_time'] ?? '' ); ?>" class="fls-timepicker" />
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Event Location', 'fatlabschema' ); ?> <span class="required">*</span></h4>

	<div class="fls-location-toggle">
		<label>
			<input type="radio" name="fatlabschema_data[location_type]" value="physical" <?php checked( ( $data['location_type'] ?? 'physical' ), 'physical' ); ?> />
			<?php esc_html_e( 'Physical Location', 'fatlabschema' ); ?>
		</label>
		&nbsp;&nbsp;
		<label>
			<input type="radio" name="fatlabschema_data[location_type]" value="virtual" <?php checked( ( $data['location_type'] ?? '' ), 'virtual' ); ?> />
			<?php esc_html_e( 'Virtual Event (Online)', 'fatlabschema' ); ?>
		</label>
	</div>

	<div class="fls-physical-location" style="<?php echo ( isset( $data['location_type'] ) && 'virtual' === $data['location_type'] ) ? 'display:none;' : ''; ?>">
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="fls_location_name"><?php esc_html_e( 'Venue Name', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" id="fls_location_name" name="fatlabschema_data[location_name]" value="<?php echo esc_attr( $data['location_name'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fls_street_address"><?php esc_html_e( 'Street Address', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" id="fls_street_address" name="fatlabschema_data[street_address]" value="<?php echo esc_attr( $data['street_address'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fls_address_locality"><?php esc_html_e( 'City', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" id="fls_address_locality" name="fatlabschema_data[address_locality]" value="<?php echo esc_attr( $data['address_locality'] ?? '' ); ?>" class="regular-text" />
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
	</div>

	<div class="fls-virtual-location" style="<?php echo ( isset( $data['location_type'] ) && 'virtual' === $data['location_type'] ) ? '' : 'display:none;'; ?>">
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="fls_virtual_url"><?php esc_html_e( 'Event URL', 'fatlabschema' ); ?> <span class="required">*</span></label>
				</th>
				<td>
					<input type="url" id="fls_virtual_url" name="fatlabschema_data[virtual_url]" value="<?php echo esc_attr( $data['virtual_url'] ?? '' ); ?>" class="regular-text" placeholder="https://zoom.us/j/..." />
					<p class="description"><?php esc_html_e( 'The URL where the online event will take place (Zoom, Teams, YouTube, etc.).', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>
	</div>

	<h4><?php esc_html_e( 'Additional Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_event_image"><?php esc_html_e( 'Event Image', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_event_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="regular-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'Event image', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Recommended for better visibility in search results.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_organizer_name"><?php esc_html_e( 'Organizer', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_organizer_name" name="fatlabschema_data[organizer_name]" value="<?php echo esc_attr( $data['organizer_name'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Organization or person organizing the event.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_organizer_url"><?php esc_html_e( 'Organizer URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_organizer_url" name="fatlabschema_data[organizer_url]" value="<?php echo esc_attr( $data['organizer_url'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Tickets/Registration (Optional)', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_offers_url"><?php esc_html_e( 'Ticket/Registration URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_offers_url" name="fatlabschema_data[offers_url]" value="<?php echo esc_attr( $data['offers_url'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Link to purchase tickets or register for the event.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_offers_price"><?php esc_html_e( 'Price', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="number" id="fls_offers_price" name="fatlabschema_data[offers_price]" value="<?php echo esc_attr( $data['offers_price'] ?? '' ); ?>" class="small-text" step="0.01" min="0" />
				<select name="fatlabschema_data[offers_currency]" class="small-text">
					<option value="USD" <?php selected( $data['offers_currency'] ?? 'USD', 'USD' ); ?>>USD</option>
					<option value="EUR" <?php selected( $data['offers_currency'] ?? 'USD', 'EUR' ); ?>>EUR</option>
					<option value="GBP" <?php selected( $data['offers_currency'] ?? 'USD', 'GBP' ); ?>>GBP</option>
					<option value="CAD" <?php selected( $data['offers_currency'] ?? 'USD', 'CAD' ); ?>>CAD</option>
				</select>
				<p class="description"><?php esc_html_e( 'Leave blank or set to 0 for free events.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Event Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>
</div>
