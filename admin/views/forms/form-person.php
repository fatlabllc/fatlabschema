<?php
/**
 * Person schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Person Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Use this schema for individual profiles such as political candidates, executive directors, board members, or staff profiles.', 'fatlabschema' ); ?></p>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_person_name"><?php esc_html_e( 'Full Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_person_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'The person\'s full name.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_person_image"><?php esc_html_e( 'Photo', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_person_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="regular-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Photo', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'Person photo', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Professional headshot or photo.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_job_title"><?php esc_html_e( 'Job Title / Role', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_job_title" name="fatlabschema_data[job_title]" value="<?php echo esc_attr( $data['job_title'] ?? '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g., Executive Director, Candidate for Senate, Board Member', 'fatlabschema' ); ?>" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_description"><?php esc_html_e( 'Biography', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_description" name="fatlabschema_data[description]" rows="5" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Brief biography or description of the person and their role.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Affiliation', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_affiliation"><?php esc_html_e( 'Organization/Party', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_affiliation" name="fatlabschema_data[affiliation]" value="<?php echo esc_attr( $data['affiliation'] ?? '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g., Democratic Party, Example Nonprofit', 'fatlabschema' ); ?>" />
				<p class="description"><?php esc_html_e( 'Political party, organization, or company affiliation.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_member_of"><?php esc_html_e( 'Member Of', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_member_of" name="fatlabschema_data[member_of]" value="<?php echo esc_attr( $data['member_of'] ?? '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'e.g., Campaign Committee, Board of Directors', 'fatlabschema' ); ?>" />
				<p class="description"><?php esc_html_e( 'Groups or organizations the person is a member of.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Contact Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_email"><?php esc_html_e( 'Email', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="email" id="fls_email" name="fatlabschema_data[email]" value="<?php echo esc_attr( $data['email'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_telephone"><?php esc_html_e( 'Phone Number', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="tel" id="fls_telephone" name="fatlabschema_data[telephone]" value="<?php echo esc_attr( $data['telephone'] ?? '' ); ?>" class="regular-text" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_url"><?php esc_html_e( 'Website', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_url" name="fatlabschema_data[url]" value="<?php echo esc_attr( $data['url'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Personal or campaign website.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Social Media Profiles', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_facebook"><?php esc_html_e( 'Facebook', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_facebook" name="fatlabschema_data[facebook]" value="<?php echo esc_attr( $data['facebook'] ?? '' ); ?>" class="regular-text" placeholder="https://facebook.com/username" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_twitter"><?php esc_html_e( 'Twitter/X', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_twitter" name="fatlabschema_data[twitter]" value="<?php echo esc_attr( $data['twitter'] ?? '' ); ?>" class="regular-text" placeholder="https://twitter.com/username" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_linkedin"><?php esc_html_e( 'LinkedIn', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_linkedin" name="fatlabschema_data[linkedin]" value="<?php echo esc_attr( $data['linkedin'] ?? '' ); ?>" class="regular-text" placeholder="https://linkedin.com/in/username" />
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_instagram"><?php esc_html_e( 'Instagram', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_instagram" name="fatlabschema_data[instagram]" value="<?php echo esc_attr( $data['instagram'] ?? '' ); ?>" class="regular-text" placeholder="https://instagram.com/username" />
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Person Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>

	<div class="fls-help-box" style="margin-top: 20px;">
		<h4><?php esc_html_e( 'Tips for Person Schema', 'fatlabschema' ); ?></h4>
		<ul>
			<li><?php esc_html_e( 'Best for notable individuals who may have knowledge panels', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Include professional photo and comprehensive biography', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Social media profiles help verify identity', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Perfect for political candidates and organizational leadership', 'fatlabschema' ); ?></li>
		</ul>
	</div>
</div>
