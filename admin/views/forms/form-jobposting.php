<?php
/**
 * JobPosting schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Job Posting Schema', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_job_title"><?php esc_html_e( 'Job Title', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_job_title" name="fatlabschema_data[title]" value="<?php echo esc_attr( $data['title'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'The title of the job position (e.g., "Program Director", "Community Organizer").', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_job_description"><?php esc_html_e( 'Job Description', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<textarea id="fls_job_description" name="fatlabschema_data[description]" rows="5" class="large-text fls-char-count-field" data-optimal="300" data-max="1000" required><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<div class="fls-char-counter" data-for="fls_job_description">
					<span class="fls-char-count">0</span> <?php esc_html_e( 'characters', 'fatlabschema' ); ?>
					<span class="fls-char-guidance"></span>
				</div>
				<p class="description"><?php esc_html_e( 'Detailed description of the job responsibilities and requirements.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_date_posted"><?php esc_html_e( 'Date Posted', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="date" id="fls_date_posted" name="fatlabschema_data[date_posted]" value="<?php echo esc_attr( $data['date_posted'] ?? date( 'Y-m-d' ) ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'When this job was posted.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_valid_through"><?php esc_html_e( 'Valid Through', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="date" id="fls_valid_through" name="fatlabschema_data[valid_through]" value="<?php echo esc_attr( $data['valid_through'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'When this job posting expires (optional but recommended).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_employment_type"><?php esc_html_e( 'Employment Type', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_employment_type" name="fatlabschema_data[employment_type]" class="regular-text">
					<option value=""><?php esc_html_e( 'Select type...', 'fatlabschema' ); ?></option>
					<option value="FULL_TIME" <?php selected( $data['employment_type'] ?? '', 'FULL_TIME' ); ?>><?php esc_html_e( 'Full-time', 'fatlabschema' ); ?></option>
					<option value="PART_TIME" <?php selected( $data['employment_type'] ?? '', 'PART_TIME' ); ?>><?php esc_html_e( 'Part-time', 'fatlabschema' ); ?></option>
					<option value="CONTRACT" <?php selected( $data['employment_type'] ?? '', 'CONTRACT' ); ?>><?php esc_html_e( 'Contract', 'fatlabschema' ); ?></option>
					<option value="TEMPORARY" <?php selected( $data['employment_type'] ?? '', 'TEMPORARY' ); ?>><?php esc_html_e( 'Temporary', 'fatlabschema' ); ?></option>
					<option value="INTERN" <?php selected( $data['employment_type'] ?? '', 'INTERN' ); ?>><?php esc_html_e( 'Internship', 'fatlabschema' ); ?></option>
					<option value="VOLUNTEER" <?php selected( $data['employment_type'] ?? '', 'VOLUNTEER' ); ?>><?php esc_html_e( 'Volunteer', 'fatlabschema' ); ?></option>
				</select>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Hiring Organization', 'fatlabschema' ); ?> <span class="required">*</span></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_hiring_organization"><?php esc_html_e( 'Organization Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_hiring_organization" name="fatlabschema_data[hiring_organization]" value="<?php echo esc_attr( $data['hiring_organization'] ?? get_bloginfo( 'name' ) ); ?>" class="regular-text" required />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_organization_url"><?php esc_html_e( 'Organization URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_organization_url" name="fatlabschema_data[organization_url]" value="<?php echo esc_attr( $data['organization_url'] ?? home_url() ); ?>" class="regular-text" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_organization_logo"><?php esc_html_e( 'Organization Logo', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_organization_logo" name="fatlabschema_data[organization_logo]" value="<?php echo esc_attr( $data['organization_logo'] ?? '' ); ?>" class="regular-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Logo', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['organization_logo'] ) ) : ?>
							<img src="<?php echo esc_url( $data['organization_logo'] ); ?>" alt="<?php esc_attr_e( 'Organization logo', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Job Location', 'fatlabschema' ); ?> <span class="required">*</span></h4>

	<div class="fls-location-toggle">
		<label>
			<input type="radio" name="fatlabschema_data[location_type]" value="physical" <?php checked( ( $data['location_type'] ?? 'physical' ), 'physical' ); ?> />
			<?php esc_html_e( 'Physical Location', 'fatlabschema' ); ?>
		</label>
		&nbsp;&nbsp;
		<label>
			<input type="radio" name="fatlabschema_data[location_type]" value="remote" <?php checked( ( $data['location_type'] ?? '' ), 'remote' ); ?> />
			<?php esc_html_e( 'Remote (Work from Home)', 'fatlabschema' ); ?>
		</label>
	</div>

	<div class="fls-physical-location" style="<?php echo ( isset( $data['location_type'] ) && 'remote' === $data['location_type'] ) ? 'display:none;' : ''; ?>">
		<table class="form-table">
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
					<label for="fls_address_locality"><?php esc_html_e( 'City', 'fatlabschema' ); ?> <span class="required">*</span></label>
				</th>
				<td>
					<input type="text" id="fls_address_locality" name="fatlabschema_data[address_locality]" value="<?php echo esc_attr( $data['address_locality'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fls_address_region"><?php esc_html_e( 'State/Region', 'fatlabschema' ); ?> <span class="required">*</span></label>
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
					<label for="fls_address_country"><?php esc_html_e( 'Country', 'fatlabschema' ); ?> <span class="required">*</span></label>
				</th>
				<td>
					<input type="text" id="fls_address_country" name="fatlabschema_data[address_country]" value="<?php echo esc_attr( $data['address_country'] ?? 'US' ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Two-letter country code (e.g., US, GB, CA).', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>
	</div>

	<h4><?php esc_html_e( 'Compensation (Optional)', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_salary_value"><?php esc_html_e( 'Salary/Wage', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="number" id="fls_salary_value" name="fatlabschema_data[salary_value]" value="<?php echo esc_attr( $data['salary_value'] ?? '' ); ?>" class="regular-text" step="0.01" min="0" />
				<select name="fatlabschema_data[salary_currency]" class="small-text">
					<option value="USD" <?php selected( $data['salary_currency'] ?? 'USD', 'USD' ); ?>>USD</option>
					<option value="EUR" <?php selected( $data['salary_currency'] ?? 'USD', 'EUR' ); ?>>EUR</option>
					<option value="GBP" <?php selected( $data['salary_currency'] ?? 'USD', 'GBP' ); ?>>GBP</option>
					<option value="CAD" <?php selected( $data['salary_currency'] ?? 'USD', 'CAD' ); ?>>CAD</option>
				</select>
				<select name="fatlabschema_data[salary_unit]" class="regular-text">
					<option value="YEAR" <?php selected( $data['salary_unit'] ?? 'YEAR', 'YEAR' ); ?>><?php esc_html_e( 'per year', 'fatlabschema' ); ?></option>
					<option value="MONTH" <?php selected( $data['salary_unit'] ?? 'YEAR', 'MONTH' ); ?>><?php esc_html_e( 'per month', 'fatlabschema' ); ?></option>
					<option value="WEEK" <?php selected( $data['salary_unit'] ?? 'YEAR', 'WEEK' ); ?>><?php esc_html_e( 'per week', 'fatlabschema' ); ?></option>
					<option value="HOUR" <?php selected( $data['salary_unit'] ?? 'YEAR', 'HOUR' ); ?>><?php esc_html_e( 'per hour', 'fatlabschema' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'Recommended: Helps attract qualified candidates.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_application_url"><?php esc_html_e( 'Application URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_application_url" name="fatlabschema_data[application_url]" value="<?php echo esc_attr( $data['application_url'] ?? '' ); ?>" class="large-text" />
				<p class="description"><?php esc_html_e( 'Direct link to apply for this job. Leave blank to use this page URL.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Job Posting Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>
</div>
