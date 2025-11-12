<?php
/**
 * Article/ScholarlyArticle schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Article Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Use this schema for blog posts, news articles, and publications. Most fields are auto-filled from your post data.', 'fatlabschema' ); ?></p>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_article_type"><?php esc_html_e( 'Article Type', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<select id="fls_article_type" name="fatlabschema_data[article_type]" class="regular-text">
					<option value="Article" <?php selected( $data['article_type'] ?? 'Article', 'Article' ); ?>><?php esc_html_e( 'Article (Blog posts, news)', 'fatlabschema' ); ?></option>
					<option value="BlogPosting" <?php selected( $data['article_type'] ?? '', 'BlogPosting' ); ?>><?php esc_html_e( 'Blog Posting', 'fatlabschema' ); ?></option>
					<option value="NewsArticle" <?php selected( $data['article_type'] ?? '', 'NewsArticle' ); ?>><?php esc_html_e( 'News Article', 'fatlabschema' ); ?></option>
					<option value="ScholarlyArticle" <?php selected( $data['article_type'] ?? '', 'ScholarlyArticle' ); ?>><?php esc_html_e( 'Scholarly Article (Research, academic)', 'fatlabschema' ); ?></option>
					<option value="TechArticle" <?php selected( $data['article_type'] ?? '', 'TechArticle' ); ?>><?php esc_html_e( 'Technical Article', 'fatlabschema' ); ?></option>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_headline"><?php esc_html_e( 'Headline', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_headline" name="fatlabschema_data[headline]" value="<?php echo esc_attr( $data['headline'] ?? '' ); ?>" class="large-text" required />
				<p class="description"><?php esc_html_e( 'Article title (auto-filled from post title).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_description" name="fatlabschema_data[description]" rows="3" class="large-text"><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Brief description or excerpt (auto-filled).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_article_image"><?php esc_html_e( 'Article Image', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_article_image" name="fatlabschema_data[image]" value="<?php echo esc_attr( $data['image'] ?? '' ); ?>" class="large-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Image', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['image'] ) ) : ?>
							<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php esc_attr_e( 'Article image', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Featured image (auto-filled from post featured image).', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Author Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_author_name"><?php esc_html_e( 'Author Name', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_author_name" name="fatlabschema_data[author_name]" value="<?php echo esc_attr( $data['author_name'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'Auto-filled from post author.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_date_published"><?php esc_html_e( 'Published Date', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="datetime-local" id="fls_date_published" name="fatlabschema_data[datePublished]" value="<?php echo esc_attr( $data['datePublished'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'Auto-filled from post publish date.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_date_modified"><?php esc_html_e( 'Modified Date', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="datetime-local" id="fls_date_modified" name="fatlabschema_data[dateModified]" value="<?php echo esc_attr( $data['dateModified'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Auto-filled from post modified date.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Publisher Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_publisher_name"><?php esc_html_e( 'Publisher Name', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_publisher_name" name="fatlabschema_data[publisher_name]" value="<?php echo esc_attr( $data['publisher_name'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Auto-filled from Organization schema. Leave blank to use site name.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_publisher_logo"><?php esc_html_e( 'Publisher Logo', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_publisher_logo" name="fatlabschema_data[publisher_logo]" value="<?php echo esc_attr( $data['publisher_logo'] ?? '' ); ?>" class="regular-text fls-media-url" />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Logo', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['publisher_logo'] ) ) : ?>
							<img src="<?php echo esc_url( $data['publisher_logo'] ); ?>" alt="<?php esc_attr_e( 'Publisher logo', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Auto-filled from Organization schema logo.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Article Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>

	<div class="fls-help-box" style="margin-top: 20px;">
		<h4><?php esc_html_e( 'Important Note', 'fatlabschema' ); ?></h4>
		<p><?php esc_html_e( 'If you have Yoast SEO, Rank Math, or All in One SEO installed, they may already be adding Article schema to your posts. Check for conflicts to avoid duplicate schema markup.', 'fatlabschema' ); ?></p>
	</div>
</div>
