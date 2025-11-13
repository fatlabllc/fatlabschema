<?php
/**
 * Help page view.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap fls-admin-page">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div class="fls-help-section">
		<h2><?php esc_html_e( 'Quick Start Guide', 'fatlabschema' ); ?></h2>
		<ol>
			<li><strong><?php esc_html_e( 'Set up your Organization schema', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'This is required and appears on every page.', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Edit a page or post', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Find the Schema Wizard meta box below the editor.', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Answer the wizard questions', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Get recommendations based on your content.', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Fill in schema details', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Many fields auto-fill from your page content.', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Publish and validate', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Test with Google Rich Results Test.', 'fatlabschema' ); ?></li>
		</ol>
	</div>

	<div class="fls-help-section">
		<h2><?php esc_html_e( 'Common Questions', 'fatlabschema' ); ?></h2>

		<h3><?php esc_html_e( 'When should I use schema?', 'fatlabschema' ); ?></h3>
		<p><?php esc_html_e( 'Use schema for pages with specific, structured content: events, FAQs, how-to guides, business locations, services, people profiles, and articles. Not every page needs schema.', 'fatlabschema' ); ?></p>

		<h3><?php esc_html_e( 'What if I have Yoast/Rank Math installed?', 'fatlabschema' ); ?></h3>
		<p><?php esc_html_e( 'Our conflict detection will warn you if another plugin is adding the same schema type. For Article schema, let your SEO plugin handle it. Use FatLab Schema Wizard for Event, Service, HowTo, and other specialized types.', 'fatlabschema' ); ?></p>

		<h3><?php esc_html_e( 'How do I know if my schema is working?', 'fatlabschema' ); ?></h3>
		<p><?php esc_html_e( 'Use Google\'s Rich Results Test:', 'fatlabschema' ); ?> <a href="https://search.google.com/test/rich-results" target="_blank">https://search.google.com/test/rich-results</a></p>

		<h3><?php esc_html_e( 'Does this help with ChatGPT and AI search?', 'fatlabschema' ); ?></h3>
		<p><?php esc_html_e( 'Yes! Properly structured schema helps AI assistants understand and cite your content more accurately.', 'fatlabschema' ); ?></p>
	</div>

	<div class="fls-help-section">
		<h2><?php esc_html_e( 'Supported Schema Types', 'fatlabschema' ); ?></h2>
		<ul>
			<li><strong><?php esc_html_e( 'Organization/NGO', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Site-wide organization info', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'LocalBusiness', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Physical locations with address', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Event', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Events with date, time, location', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'FAQPage', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Questions and answers', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Article', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Blog posts and articles', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Service', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Services offered', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'HowTo', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Step-by-step guides', 'fatlabschema' ); ?></li>
			<li><strong><?php esc_html_e( 'Person', 'fatlabschema' ); ?></strong> - <?php esc_html_e( 'Individual profiles', 'fatlabschema' ); ?></li>
		</ul>
	</div>

	<div class="fls-help-section">
		<h2><?php esc_html_e( 'Need More Help?', 'fatlabschema' ); ?></h2>
		<p>
			<a href="https://fatlabwebsupport.com/support" class="button button-primary" target="_blank"><?php esc_html_e( 'Contact Support', 'fatlabschema' ); ?></a>
			<a href="https://fatlabwebsupport.com/schema-wizard/docs" class="button button-secondary" target="_blank"><?php esc_html_e( 'View Full Documentation', 'fatlabschema' ); ?></a>
		</p>
	</div>
</div>
