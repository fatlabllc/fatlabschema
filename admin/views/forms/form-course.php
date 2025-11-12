<?php
/**
 * Course schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Course Schema', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_course_name"><?php esc_html_e( 'Course Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_course_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="large-text" required />
				<p class="description"><?php esc_html_e( 'The title of the course.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_course_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<textarea id="fls_course_description" name="fatlabschema_data[description]" rows="4" class="large-text fls-char-count-field" data-optimal="300" data-max="500" required><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<div class="fls-char-counter" data-for="fls_course_description">
					<span class="fls-char-count">0</span> <?php esc_html_e( 'characters', 'fatlabschema' ); ?>
					<span class="fls-char-guidance"></span>
				</div>
				<p class="description"><?php esc_html_e( 'What students will learn in this course.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_course_url"><?php esc_html_e( 'Course URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_course_url" name="fatlabschema_data[course_url]" value="<?php echo esc_attr( $data['course_url'] ?? '' ); ?>" class="large-text" />
				<p class="description"><?php esc_html_e( 'Direct link to the course page. Leave blank to use this page URL.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_course_code"><?php esc_html_e( 'Course Code', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_course_code" name="fatlabschema_data[course_code]" value="<?php echo esc_attr( $data['course_code'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Optional course code or identifier (e.g., "CS101", "VOL-001").', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_course_image"><?php esc_html_e( 'Course Image', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_course_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="regular-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'Course image', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Course Provider', 'fatlabschema' ); ?> <span class="required">*</span></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_provider_name"><?php esc_html_e( 'Provider Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_provider_name" name="fatlabschema_data[provider_name]" value="<?php echo esc_attr( $data['provider_name'] ?? get_bloginfo( 'name' ) ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'Organization offering this course.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_provider_url"><?php esc_html_e( 'Provider URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_provider_url" name="fatlabschema_data[provider_url]" value="<?php echo esc_attr( $data['provider_url'] ?? home_url() ); ?>" class="regular-text" />
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Course Details', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_course_mode"><?php esc_html_e( 'Course Format', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_course_mode" name="fatlabschema_data[course_mode]" class="regular-text">
					<option value=""><?php esc_html_e( 'Select format...', 'fatlabschema' ); ?></option>
					<option value="online" <?php selected( $data['course_mode'] ?? '', 'online' ); ?>><?php esc_html_e( 'Online', 'fatlabschema' ); ?></option>
					<option value="onsite" <?php selected( $data['course_mode'] ?? '', 'onsite' ); ?>><?php esc_html_e( 'In-person', 'fatlabschema' ); ?></option>
					<option value="blended" <?php selected( $data['course_mode'] ?? '', 'blended' ); ?>><?php esc_html_e( 'Blended (Online + In-person)', 'fatlabschema' ); ?></option>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_time_required"><?php esc_html_e( 'Time Required', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_time_required" name="fatlabschema_data[time_required]" value="<?php echo esc_attr( $data['time_required'] ?? '' ); ?>" class="regular-text" placeholder="P6W" />
				<p class="description"><?php esc_html_e( 'Duration in ISO 8601 format (e.g., "P6W" = 6 weeks, "P3M" = 3 months, "PT20H" = 20 hours).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_educational_level"><?php esc_html_e( 'Educational Level', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_educational_level" name="fatlabschema_data[educational_level]" class="regular-text">
					<option value=""><?php esc_html_e( 'Select level...', 'fatlabschema' ); ?></option>
					<option value="Beginner" <?php selected( $data['educational_level'] ?? '', 'Beginner' ); ?>><?php esc_html_e( 'Beginner', 'fatlabschema' ); ?></option>
					<option value="Intermediate" <?php selected( $data['educational_level'] ?? '', 'Intermediate' ); ?>><?php esc_html_e( 'Intermediate', 'fatlabschema' ); ?></option>
					<option value="Advanced" <?php selected( $data['educational_level'] ?? '', 'Advanced' ); ?>><?php esc_html_e( 'Advanced', 'fatlabschema' ); ?></option>
					<option value="All levels" <?php selected( $data['educational_level'] ?? '', 'All levels' ); ?>><?php esc_html_e( 'All Levels', 'fatlabschema' ); ?></option>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_course_prerequisites"><?php esc_html_e( 'Prerequisites', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_course_prerequisites" name="fatlabschema_data[course_prerequisites]" rows="2" class="large-text"><?php echo esc_textarea( $data['course_prerequisites'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'What knowledge or skills are required before taking this course.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Instructor (Optional)', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_instructor_name"><?php esc_html_e( 'Instructor Name', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_instructor_name" name="fatlabschema_data[instructor_name]" value="<?php echo esc_attr( $data['instructor_name'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_instructor_url"><?php esc_html_e( 'Instructor Profile URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_instructor_url" name="fatlabschema_data[instructor_url]" value="<?php echo esc_attr( $data['instructor_url'] ?? '' ); ?>" class="regular-text" />
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
				<p class="description"><?php esc_html_e( 'Set to 0 or leave blank for free courses.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_availability"><?php esc_html_e( 'Availability', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_availability" name="fatlabschema_data[availability]" class="regular-text">
					<option value="https://schema.org/InStock" <?php selected( $data['availability'] ?? '', 'https://schema.org/InStock' ); ?>><?php esc_html_e( 'Available', 'fatlabschema' ); ?></option>
					<option value="https://schema.org/SoldOut" <?php selected( $data['availability'] ?? '', 'https://schema.org/SoldOut' ); ?>><?php esc_html_e( 'Sold Out', 'fatlabschema' ); ?></option>
					<option value="https://schema.org/PreOrder" <?php selected( $data['availability'] ?? '', 'https://schema.org/PreOrder' ); ?>><?php esc_html_e( 'Coming Soon', 'fatlabschema' ); ?></option>
				</select>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Course Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>
</div>
