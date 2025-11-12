<?php
/**
 * FAQPage schema form.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure questions array exists
if ( ! isset( $data['questions'] ) || ! is_array( $data['questions'] ) ) {
	$data['questions'] = array(
		array( 'question' => '', 'answer' => '' ),
	);
}
?>

<div class="fls-schema-form-wrapper">
	<h4><?php esc_html_e( 'FAQPage Schema', 'fatlabschema' ); ?></h4>

	<p><?php esc_html_e( 'Add your questions and answers below. This schema creates expandable Q&A sections in search results.', 'fatlabschema' ); ?></p>

	<div class="fls-repeater-container">
		<div class="fls-repeater-items">
			<?php foreach ( $data['questions'] as $index => $qa ) : ?>
				<div class="fls-repeater-item">
					<a href="#" class="fls-remove-repeater-item dashicons dashicons-no-alt" title="<?php esc_attr_e( 'Remove this question', 'fatlabschema' ); ?>"></a>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="fls_question_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Question', 'fatlabschema' ); ?> <span class="required">*</span></label>
							</th>
							<td>
								<input type="text" id="fls_question_<?php echo esc_attr( $index ); ?>" name="fatlabschema_data[questions][<?php echo esc_attr( $index ); ?>][question]" value="<?php echo esc_attr( $qa['question'] ?? '' ); ?>" class="large-text" required placeholder="<?php esc_attr_e( 'What is...?', 'fatlabschema' ); ?>" />
							</td>
						</tr>

						<tr>
							<th scope="row">
								<label for="fls_answer_<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Answer', 'fatlabschema' ); ?> <span class="required">*</span></label>
							</th>
							<td>
								<textarea id="fls_answer_<?php echo esc_attr( $index ); ?>" name="fatlabschema_data[questions][<?php echo esc_attr( $index ); ?>][answer]" rows="4" class="large-text" required placeholder="<?php esc_attr_e( 'The answer to this question...', 'fatlabschema' ); ?>"><?php echo esc_textarea( $qa['answer'] ?? '' ); ?></textarea>
							</td>
						</tr>
					</table>
				</div>
			<?php endforeach; ?>
		</div>

		<button type="button" class="button fls-add-repeater-item">
			<span class="dashicons dashicons-plus-alt"></span>
			<?php esc_html_e( 'Add Another Question', 'fatlabschema' ); ?>
		</button>

		<!-- Template for new questions -->
		<script type="text/html" class="fls-repeater-template">
			<div class="fls-repeater-item">
				<a href="#" class="fls-remove-repeater-item dashicons dashicons-no-alt" title="<?php esc_attr_e( 'Remove this question', 'fatlabschema' ); ?>"></a>

				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="fls_question_[INDEX]"><?php esc_html_e( 'Question', 'fatlabschema' ); ?> <span class="required">*</span></label>
						</th>
						<td>
							<input type="text" id="fls_question_[INDEX]" name="fatlabschema_data[questions][[INDEX]][question]" value="" class="large-text" required placeholder="<?php esc_attr_e( 'What is...?', 'fatlabschema' ); ?>" />
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="fls_answer_[INDEX]"><?php esc_html_e( 'Answer', 'fatlabschema' ); ?> <span class="required">*</span></label>
						</th>
						<td>
							<textarea id="fls_answer_[INDEX]" name="fatlabschema_data[questions][[INDEX]][answer]" rows="4" class="large-text" required placeholder="<?php esc_attr_e( 'The answer to this question...', 'fatlabschema' ); ?>"></textarea>
						</td>
					</tr>
				</table>
			</div>
		</script>
	</div>

	<div class="fls-form-actions">
		<button type="button" class="button button-primary fls-save-schema-button">
			<?php esc_html_e( 'Save FAQ Schema', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-preview-schema-button">
			<?php esc_html_e( 'Preview JSON-LD', 'fatlabschema' ); ?>
		</button>
		<button type="button" class="button fls-cancel-schema-button">
			<?php esc_html_e( 'Cancel', 'fatlabschema' ); ?>
		</button>
	</div>

	<div class="fls-help-box" style="margin-top: 20px;">
		<h4><?php esc_html_e( 'Tips for FAQPage Schema', 'fatlabschema' ); ?></h4>
		<ul>
			<li><?php esc_html_e( 'Only use FAQPage schema on pages that list questions with answers', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Each question should be distinct and answerable', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Answers should be complete and comprehensive', 'fatlabschema' ); ?></li>
			<li><?php esc_html_e( 'Minimum 2-3 questions recommended for better visibility', 'fatlabschema' ); ?></li>
		</ul>
	</div>
</div>
