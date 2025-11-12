<?php
/**
 * Organization settings page view.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$organization = get_option( 'fatlabschema_organization', array() );
?>

<div class="wrap fls-admin-page">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div class="fls-intro">
		<p>
			<?php esc_html_e( 'Organization schema appears on every page of your site and tells search engines about your organization. This is the foundation for all other schema types.', 'fatlabschema' ); ?>
		</p>
	</div>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'fatlabschema_organization_settings' );
		?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">
					<label for="fatlabschema_org_type"><?php esc_html_e( 'Organization Type', 'fatlabschema' ); ?> <span class="required">*</span></label>
				</th>
				<td>
					<select name="fatlabschema_organization[type]" id="fatlabschema_org_type" class="regular-text">
						<option value="Organization" <?php selected( $organization['type'] ?? '', 'Organization' ); ?>>
							<?php esc_html_e( 'Business', 'fatlabschema' ); ?>
						</option>
						<option value="NGO" <?php selected( $organization['type'] ?? '', 'NGO' ); ?>>
							<?php esc_html_e( 'Nonprofit/NGO', 'fatlabschema' ); ?>
						</option>
						<option value="PoliticalOrganization" <?php selected( $organization['type'] ?? '', 'PoliticalOrganization' ); ?>>
							<?php esc_html_e( 'Political Organization', 'fatlabschema' ); ?>
						</option>
						<option value="EducationalOrganization" <?php selected( $organization['type'] ?? '', 'EducationalOrganization' ); ?>>
							<?php esc_html_e( 'Educational Organization', 'fatlabschema' ); ?>
						</option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_name"><?php esc_html_e( 'Organization Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
				</th>
				<td>
					<input type="text" name="fatlabschema_organization[name]" id="fatlabschema_org_name" value="<?php echo esc_attr( $organization['name'] ?? get_bloginfo( 'name' ) ); ?>" class="regular-text" required />
					<p class="description"><?php esc_html_e( 'The official name of your organization.', 'fatlabschema' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_url"><?php esc_html_e( 'Website URL', 'fatlabschema' ); ?> <span class="required">*</span></label>
				</th>
				<td>
					<input type="url" name="fatlabschema_organization[url]" id="fatlabschema_org_url" value="<?php echo esc_attr( $organization['url'] ?? home_url() ); ?>" class="regular-text" required />
					<p class="description"><?php esc_html_e( 'Your organization\'s main website URL.', 'fatlabschema' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_logo"><?php esc_html_e( 'Logo', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<div class="fls-media-upload">
						<input type="url" name="fatlabschema_organization[logo]" id="fatlabschema_org_logo" value="<?php echo esc_attr( $organization['logo'] ?? '' ); ?>" class="regular-text fls-media-url" />
						<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Logo', 'fatlabschema' ); ?></button>
						<div class="fls-media-preview">
							<?php if ( ! empty( $organization['logo'] ) ) : ?>
								<img src="<?php echo esc_url( $organization['logo'] ); ?>" alt="<?php esc_attr_e( 'Organization logo', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
							<?php endif; ?>
						</div>
					</div>
					<p class="description"><?php esc_html_e( 'Recommended: Square image, at least 112x112 pixels. Used in search results.', 'fatlabschema' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<textarea name="fatlabschema_organization[description]" id="fatlabschema_org_description" rows="4" class="large-text fls-char-count-field" data-optimal="300" data-max="500"><?php echo esc_textarea( $organization['description'] ?? get_bloginfo( 'description' ) ); ?></textarea>
					<div class="fls-char-counter" data-for="fatlabschema_org_description">
						<span class="fls-char-count">0</span> <?php esc_html_e( 'characters', 'fatlabschema' ); ?>
						<span class="fls-char-guidance"></span>
					</div>
					<p class="description"><?php esc_html_e( 'A brief description of your organization and what it does. Optimal: 150-300 characters.', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Contact Information', 'fatlabschema' ); ?></h2>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">
					<label for="fatlabschema_org_email"><?php esc_html_e( 'Email', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="email" name="fatlabschema_organization[email]" id="fatlabschema_org_email" value="<?php echo esc_attr( $organization['email'] ?? get_option( 'admin_email' ) ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_telephone"><?php esc_html_e( 'Phone Number', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="tel" name="fatlabschema_organization[telephone]" id="fatlabschema_org_telephone" value="<?php echo esc_attr( $organization['telephone'] ?? '' ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Include country code (e.g., +1-555-123-4567).', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Address (Optional)', 'fatlabschema' ); ?></h2>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">
					<label for="fatlabschema_org_street"><?php esc_html_e( 'Street Address', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" name="fatlabschema_organization[street_address]" id="fatlabschema_org_street" value="<?php echo esc_attr( $organization['street_address'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_city"><?php esc_html_e( 'City', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" name="fatlabschema_organization[address_locality]" id="fatlabschema_org_city" value="<?php echo esc_attr( $organization['address_locality'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_region"><?php esc_html_e( 'State/Region', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" name="fatlabschema_organization[address_region]" id="fatlabschema_org_region" value="<?php echo esc_attr( $organization['address_region'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_postal"><?php esc_html_e( 'Postal Code', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" name="fatlabschema_organization[postal_code]" id="fatlabschema_org_postal" value="<?php echo esc_attr( $organization['postal_code'] ?? '' ); ?>" class="regular-text" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_country"><?php esc_html_e( 'Country', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="text" name="fatlabschema_organization[address_country]" id="fatlabschema_org_country" value="<?php echo esc_attr( $organization['address_country'] ?? 'US' ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Two-letter country code (e.g., US, GB, CA).', 'fatlabschema' ); ?></p>
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Social Profiles', 'fatlabschema' ); ?></h2>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row">
					<label for="fatlabschema_org_facebook"><?php esc_html_e( 'Facebook', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="url" name="fatlabschema_organization[facebook]" id="fatlabschema_org_facebook" value="<?php echo esc_attr( $organization['facebook'] ?? '' ); ?>" class="regular-text" placeholder="https://facebook.com/yourpage" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_twitter"><?php esc_html_e( 'Twitter/X', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="url" name="fatlabschema_organization[twitter]" id="fatlabschema_org_twitter" value="<?php echo esc_attr( $organization['twitter'] ?? '' ); ?>" class="regular-text" placeholder="https://twitter.com/yourhandle" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_linkedin"><?php esc_html_e( 'LinkedIn', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="url" name="fatlabschema_organization[linkedin]" id="fatlabschema_org_linkedin" value="<?php echo esc_attr( $organization['linkedin'] ?? '' ); ?>" class="regular-text" placeholder="https://linkedin.com/company/yourcompany" />
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="fatlabschema_org_instagram"><?php esc_html_e( 'Instagram', 'fatlabschema' ); ?></label>
				</th>
				<td>
					<input type="url" name="fatlabschema_organization[instagram]" id="fatlabschema_org_instagram" value="<?php echo esc_attr( $organization['instagram'] ?? '' ); ?>" class="regular-text" placeholder="https://instagram.com/yourhandle" />
				</td>
			</tr>
		</table>

		<div id="fls-ngo-fields" style="<?php echo ( isset( $organization['type'] ) && ( 'NGO' === $organization['type'] || 'PoliticalOrganization' === $organization['type'] ) ) ? '' : 'display:none;'; ?>">
			<h2><?php esc_html_e( 'For Nonprofits & Political Organizations', 'fatlabschema' ); ?></h2>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="fatlabschema_org_mission"><?php esc_html_e( 'Mission Statement', 'fatlabschema' ); ?></label>
					</th>
					<td>
						<textarea name="fatlabschema_organization[mission_statement]" id="fatlabschema_org_mission" rows="4" class="large-text fls-char-count-field" data-optimal="400" data-max="750"><?php echo esc_textarea( $organization['mission_statement'] ?? '' ); ?></textarea>
						<div class="fls-char-counter" data-for="fatlabschema_org_mission">
							<span class="fls-char-count">0</span> <?php esc_html_e( 'characters', 'fatlabschema' ); ?>
							<span class="fls-char-guidance"></span>
						</div>
						<p class="description"><?php esc_html_e( 'Your organization\'s mission or purpose. Optimal: 200-400 characters.', 'fatlabschema' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="fatlabschema_org_founding"><?php esc_html_e( 'Founding Date', 'fatlabschema' ); ?></label>
					</th>
					<td>
						<input type="date" name="fatlabschema_organization[founding_date]" id="fatlabschema_org_founding" value="<?php echo esc_attr( $organization['founding_date'] ?? '' ); ?>" class="regular-text" />
					</td>
				</tr>
			</table>
		</div>

		<?php submit_button( __( 'Save Organization Schema', 'fatlabschema' ) ); ?>
	</form>

	<div class="fls-help-box">
		<h3><?php esc_html_e( 'Need Help?', 'fatlabschema' ); ?></h3>
		<p><?php esc_html_e( 'Once you save your Organization schema, it will appear on every page of your site. You can then add page-specific schema using the wizard in the post editor.', 'fatlabschema' ); ?></p>
		<p>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=fatlabschema-help' ) ); ?>" class="button button-secondary">
				<?php esc_html_e( 'View Documentation', 'fatlabschema' ); ?>
			</a>
		</p>
	</div>
</div>

<style>
.fls-char-counter {
	margin-top: 8px;
	font-size: 13px;
	font-weight: 500;
}

.fls-char-counter.fls-optimal {
	color: #2e7d32;
}

.fls-char-counter.fls-warning {
	color: #f57c00;
}

.fls-char-counter.fls-exceeded {
	color: #d32f2f;
}

.fls-char-guidance {
	margin-left: 10px;
	font-weight: normal;
	font-style: italic;
}
</style>

<script>
jQuery(document).ready(function($) {
	// Show/hide NGO fields based on organization type
	$('#fatlabschema_org_type').on('change', function() {
		var type = $(this).val();
		if (type === 'NGO' || type === 'PoliticalOrganization') {
			$('#fls-ngo-fields').slideDown();
		} else {
			$('#fls-ngo-fields').slideUp();
		}
	});

	// Character counter functionality
	function updateCharCounter($field) {
		var fieldId = $field.attr('id');
		var $counter = $('.fls-char-counter[data-for="' + fieldId + '"]');
		var $countSpan = $counter.find('.fls-char-count');
		var $guidance = $counter.find('.fls-char-guidance');

		var length = $field.val().length;
		var optimal = parseInt($field.data('optimal'));
		var max = parseInt($field.data('max'));

		$countSpan.text(length);

		// Remove all state classes
		$counter.removeClass('fls-optimal fls-warning fls-exceeded');

		// Add appropriate class and guidance text
		if (length === 0) {
			$guidance.text('');
		} else if (length <= optimal) {
			$counter.addClass('fls-optimal');
			$guidance.text('Good length');
		} else if (length <= max) {
			$counter.addClass('fls-warning');
			$guidance.text('Getting long - consider shortening');
		} else {
			$counter.addClass('fls-exceeded');
			$guidance.text('Very long - may be truncated in search results');
		}
	}

	// Initialize counters on page load
	$('.fls-char-count-field').each(function() {
		updateCharCounter($(this));
	});

	// Update counter on input
	$('.fls-char-count-field').on('input', function() {
		updateCharCounter($(this));
	});
});
</script>
