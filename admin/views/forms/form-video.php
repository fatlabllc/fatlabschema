<?php
/**
 * Video schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'Video Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Use this schema for video content including YouTube videos, Vimeo, self-hosted, and embedded videos. This helps your videos appear in video search results with rich snippets.', 'fatlabschema' ); ?></p>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_video_name"><?php esc_html_e( 'Video Title', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="text" id="fls_video_name" name="fatlabschema_data[name]" value="<?php echo esc_attr( $data['name'] ?? '' ); ?>" class="large-text" required />
				<p class="description"><?php esc_html_e( 'The title of your video (auto-filled from page title).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_description"><?php esc_html_e( 'Description', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<textarea id="fls_video_description" name="fatlabschema_data[description]" rows="3" class="large-text" required><?php echo esc_textarea( $data['description'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Brief description of the video content (auto-filled from excerpt).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_thumbnail"><?php esc_html_e( 'Thumbnail Image', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_video_thumbnail" name="fatlabschema_data[thumbnail_url]" value="<?php echo esc_attr( $data['thumbnail_url'] ?? '' ); ?>" class="large-text fls-media-url" required />
					<button type="button" class="button fls-media-upload-button"><?php esc_html_e( 'Upload Thumbnail', 'fatlabschema' ); ?></button>
					<div class="fls-media-preview">
						<?php if ( ! empty( $data['thumbnail_url'] ) ) : ?>
							<img src="<?php echo esc_url( $data['thumbnail_url'] ); ?>" alt="<?php esc_attr_e( 'Video thumbnail', 'fatlabschema' ); ?>" style="max-width: 200px; margin-top: 10px;" />
						<?php endif; ?>
					</div>
				</div>
				<p class="description"><?php esc_html_e( 'Video thumbnail image URL (auto-filled from featured image).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_content_url"><?php esc_html_e( 'Video URL', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="url" id="fls_video_content_url" name="fatlabschema_data[content_url]" value="<?php echo esc_attr( $data['content_url'] ?? '' ); ?>" class="large-text" required />
				<p class="description"><?php esc_html_e( 'Direct URL to the video file (e.g., .mp4, .webm) or streaming URL.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_embed_url"><?php esc_html_e( 'Embed URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_video_embed_url" name="fatlabschema_data[embed_url]" value="<?php echo esc_attr( $data['embed_url'] ?? '' ); ?>" class="large-text" />
				<p class="description"><?php esc_html_e( 'URL used in the embed player (e.g., YouTube embed URL: https://www.youtube.com/embed/VIDEO_ID).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_duration"><?php esc_html_e( 'Duration', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_video_duration" name="fatlabschema_data[duration]" value="<?php echo esc_attr( $data['duration'] ?? '' ); ?>" class="regular-text" placeholder="5:30 or 330" />
				<p class="description"><?php esc_html_e( 'Video length. Formats: MM:SS (5:30), HH:MM:SS (1:05:30), or seconds (330).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_upload_date"><?php esc_html_e( 'Upload Date', 'fatlabschema' ); ?> <span class="required">*</span></label>
			</th>
			<td>
				<input type="datetime-local" id="fls_video_upload_date" name="fatlabschema_data[upload_date]" value="<?php echo esc_attr( $data['upload_date'] ?? '' ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'When the video was published (auto-filled from post publish date).', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Creator Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_video_author_name"><?php esc_html_e( 'Author/Creator Name', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_video_author_name" name="fatlabschema_data[author_name]" value="<?php echo esc_attr( $data['author_name'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Person who created the video (auto-filled from post author).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_author_url"><?php esc_html_e( 'Author URL', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="url" id="fls_video_author_url" name="fatlabschema_data[author_url]" value="<?php echo esc_attr( $data['author_url'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Link to author profile or website.', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Publisher Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_video_publisher_name"><?php esc_html_e( 'Publisher Name', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="text" id="fls_video_publisher_name" name="fatlabschema_data[publisher_name]" value="<?php echo esc_attr( $data['publisher_name'] ?? '' ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Organization that published the video (auto-filled from Organization schema).', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_publisher_logo"><?php esc_html_e( 'Publisher Logo', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<div class="fls-media-upload">
					<input type="url" id="fls_video_publisher_logo" name="fatlabschema_data[publisher_logo]" value="<?php echo esc_attr( $data['publisher_logo'] ?? '' ); ?>" class="regular-text fls-media-url" />
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

	<h4><?php esc_html_e( 'Optional Information', 'fatlabschema' ); ?></h4>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="fls_video_transcript"><?php esc_html_e( 'Video Transcript', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<textarea id="fls_video_transcript" name="fatlabschema_data[transcript]" rows="5" class="large-text"><?php echo esc_textarea( $data['transcript'] ?? '' ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Full text transcript of the video. Improves SEO and accessibility.', 'fatlabschema' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="fls_video_interaction_count"><?php esc_html_e( 'View Count', 'fatlabschema' ); ?></label>
			</th>
			<td>
				<input type="number" id="fls_video_interaction_count" name="fatlabschema_data[interaction_count]" value="<?php echo esc_attr( $data['interaction_count'] ?? '' ); ?>" class="regular-text" min="0" />
				<p class="description"><?php esc_html_e( 'Number of times the video has been watched (optional).', 'fatlabschema' ); ?></p>
			</td>
		</tr>
	</table>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save Video Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>

	<div class="fls-help-box" style="margin-top: 20px;">
		<h4><?php esc_html_e( 'Video Schema Tips', 'fatlabschema' ); ?></h4>
		<ul>
			<li><?php esc_html_e( 'Works great for: YouTube videos, Vimeo, self-hosted videos, video tutorials, webinars, promotional videos', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'High-quality thumbnail images (minimum 160x90px, recommended 1280x720px or higher)', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Adding a transcript significantly improves SEO and makes your content accessible', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Video schema helps your content appear in Google Video Search and video carousels', 'fatlabschema' ); ?></li>
		</ul>
	</div>
</div>
