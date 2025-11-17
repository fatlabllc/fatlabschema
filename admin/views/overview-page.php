<?php
/**
 * Schema Overview page view.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get all public post types
$post_types = get_post_types( array( 'public' => true ), 'names' );

// Query all posts with schemas
$posts_with_schemas = get_posts(
	array(
		'posts_per_page' => -1,
		'post_type'      => $post_types,
		'post_status'    => 'any',
		'meta_key'       => '_fatlabschema_schemas',
		'meta_compare'   => 'EXISTS',
		'orderby'        => 'modified',
		'order'          => 'DESC',
	)
);

// Get schema type definitions
$schema_types = class_exists( 'FLS_Wizard' ) ? FLS_Wizard::get_schema_types() : array();

// Define badge colors for each schema type
$schema_colors = array(
	'service'       => '#2271b1',
	'faqpage'       => '#00a32a',
	'article'       => '#8c8f94',
	'event'         => '#d63638',
	'video'         => '#9b51e0',
	'jobposting'    => '#f0b429',
	'course'        => '#4ab866',
	'howto'         => '#00a0d2',
	'scholarly'     => '#826eb4',
	'localbusiness' => '#c9356e',
	'person'        => '#e65054',
	'none'          => '#dcdcde',
);

?>

<div class="wrap fls-admin-page">
	<h1>
		<span class="dashicons dashicons-admin-site-alt" style="font-size: 32px; margin-right: 8px;"></span>
		<?php esc_html_e( 'Schema Overview', 'fatlabschema' ); ?>
	</h1>

	<p class="description" style="margin-bottom: 20px;">
		<?php esc_html_e( 'This page shows all posts and pages that have schema markup applied. Use this to quickly find and manage your schema-enabled content.', 'fatlabschema' ); ?>
	</p>

	<?php if ( empty( $posts_with_schemas ) ) : ?>
		<div class="notice notice-info inline">
			<p>
				<strong><?php esc_html_e( 'No schemas found.', 'fatlabschema' ); ?></strong>
			</p>
			<p>
				<?php esc_html_e( 'You haven\'t added any schema markup to your posts or pages yet. Edit a post or page and use the FatLab Schema Wizard meta box to add structured data.', 'fatlabschema' ); ?>
			</p>
		</div>
	<?php else : ?>
		<div class="fls-overview-stats" style="background: #f0f0f1; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
			<strong><?php esc_html_e( 'Total:', 'fatlabschema' ); ?></strong>
			<?php
			printf(
				/* translators: %d: number of posts with schemas */
				esc_html( _n( '%d post/page with schema markup', '%d posts/pages with schema markup', count( $posts_with_schemas ), 'fatlabschema' ) ),
				count( $posts_with_schemas )
			);
			?>
		</div>

		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col" style="width: 30%;"><?php esc_html_e( 'Title', 'fatlabschema' ); ?></th>
					<th scope="col" style="width: 10%;"><?php esc_html_e( 'Type', 'fatlabschema' ); ?></th>
					<th scope="col" style="width: 10%;"><?php esc_html_e( 'Status', 'fatlabschema' ); ?></th>
					<th scope="col" style="width: 35%;"><?php esc_html_e( 'Applied Schemas', 'fatlabschema' ); ?></th>
					<th scope="col" style="width: 10%;" class="num"><?php esc_html_e( 'Enabled / Total', 'fatlabschema' ); ?></th>
					<th scope="col" style="width: 5%;"><?php esc_html_e( 'Actions', 'fatlabschema' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $posts_with_schemas as $post ) : ?>
					<?php
					$schemas = FLS_Schema_Manager::get_schemas( $post->ID );
					$enabled_count = FLS_Schema_Manager::get_enabled_count( $post->ID );
					$total_count = count( $schemas );
					$post_type_obj = get_post_type_object( $post->post_type );
					$post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : $post->post_type;

					// Get status with proper label
					$status_obj = get_post_status_object( $post->post_status );
					$status_label = $status_obj ? $status_obj->label : $post->post_status;
					?>
					<tr>
						<td>
							<strong>
								<a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>">
									<?php echo esc_html( $post->post_title ? $post->post_title : __( '(no title)', 'fatlabschema' ) ); ?>
								</a>
							</strong>
						</td>
						<td>
							<?php echo esc_html( $post_type_label ); ?>
						</td>
						<td>
							<?php
							// Status indicator with color
							$status_colors = array(
								'publish' => '#00a32a',
								'draft'   => '#8c8f94',
								'pending' => '#f0b429',
								'private' => '#2271b1',
								'future'  => '#9b51e0',
							);
							$status_color = isset( $status_colors[ $post->post_status ] ) ? $status_colors[ $post->post_status ] : '#8c8f94';
							?>
							<span class="fls-status-badge" style="display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; color: #fff; background-color: <?php echo esc_attr( $status_color ); ?>;">
								<?php echo esc_html( $status_label ); ?>
							</span>
						</td>
						<td>
							<div class="fls-schema-badges">
								<?php
								if ( ! empty( $schemas ) ) {
									foreach ( $schemas as $schema_id => $schema ) {
										$schema_type = $schema['type'];
										$is_enabled = $schema['enabled'];
										$type_label = isset( $schema_types[ $schema_type ] ) ? $schema_types[ $schema_type ]['label'] : ucfirst( $schema_type );
										$type_icon = isset( $schema_types[ $schema_type ] ) ? $schema_types[ $schema_type ]['icon'] : 'dashicons-admin-generic';
										$badge_color = isset( $schema_colors[ $schema_type ] ) ? $schema_colors[ $schema_type ] : '#8c8f94';

										// Dim badge if not enabled
										$badge_style = $is_enabled ? 'opacity: 1;' : 'opacity: 0.4;';
										?>
										<span class="fls-schema-badge" style="display: inline-block; margin: 2px 4px 2px 0; padding: 4px 10px; border-radius: 3px; font-size: 12px; color: #fff; background-color: <?php echo esc_attr( $badge_color ); ?>; <?php echo esc_attr( $badge_style ); ?>" title="<?php echo $is_enabled ? esc_attr__( 'Enabled', 'fatlabschema' ) : esc_attr__( 'Disabled', 'fatlabschema' ); ?>">
											<span class="dashicons <?php echo esc_attr( $type_icon ); ?>" style="font-size: 14px; width: 14px; height: 14px; margin-right: 4px; vertical-align: text-bottom;"></span>
											<?php echo esc_html( $type_label ); ?>
											<?php if ( ! $is_enabled ) : ?>
												<span style="opacity: 0.7;"> (<?php esc_html_e( 'disabled', 'fatlabschema' ); ?>)</span>
											<?php endif; ?>
										</span>
										<?php
									}
								} else {
									echo '<span style="color: #8c8f94; font-style: italic;">' . esc_html__( 'No schemas', 'fatlabschema' ) . '</span>';
								}
								?>
							</div>
						</td>
						<td class="num">
							<strong><?php echo esc_html( $enabled_count ); ?></strong>
							<span style="color: #8c8f94;"> / <?php echo esc_html( $total_count ); ?></span>
						</td>
						<td>
							<a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>" class="button button-small" title="<?php esc_attr_e( 'Edit Post', 'fatlabschema' ); ?>">
								<?php esc_html_e( 'Edit', 'fatlabschema' ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

<style>
.fls-overview-stats {
	font-size: 14px;
}

.fls-schema-badge {
	white-space: nowrap;
}

.fls-schema-badges {
	line-height: 1.8;
}

table.wp-list-table th.num,
table.wp-list-table td.num {
	text-align: center;
}

/* Responsive adjustments */
@media screen and (max-width: 782px) {
	.fls-schema-badge {
		font-size: 11px;
		padding: 3px 8px;
	}

	.fls-schema-badge .dashicons {
		font-size: 12px !important;
		width: 12px !important;
		height: 12px !important;
	}
}
</style>
