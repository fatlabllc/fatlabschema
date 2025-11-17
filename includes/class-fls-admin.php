<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package FatLab_Schema_Wizard
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 */
class FLS_Admin {

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		// Admin menu
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		// Register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Meta box
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_box_data' ) );

		// Enqueue admin scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add settings link on plugins page
		add_filter( 'plugin_action_links_' . FATLABSCHEMA_BASENAME, array( $this, 'add_plugin_action_links' ) );

		// Add contextual help
		add_action( 'load-toplevel_page_fatlabschema', array( $this, 'add_contextual_help' ) );
	}

	/**
	 * Add admin menu.
	 */
	public function add_admin_menu() {
		// Main menu page
		add_menu_page(
			__( 'FatLab Schema Wizard', 'fatlabschema' ),
			__( 'Schema Wizard', 'fatlabschema' ),
			'manage_options',
			'fatlabschema',
			array( $this, 'render_settings_page' ),
			'dashicons-admin-site-alt',
			80
		);

		// Organization submenu (same as main menu)
		add_submenu_page(
			'fatlabschema',
			__( 'Organization Schema', 'fatlabschema' ),
			__( 'Organization', 'fatlabschema' ),
			'manage_options',
			'fatlabschema',
			array( $this, 'render_settings_page' )
		);

		// Settings submenu
		add_submenu_page(
			'fatlabschema',
			__( 'Plugin Settings', 'fatlabschema' ),
			__( 'Settings', 'fatlabschema' ),
			'manage_options',
			'fatlabschema-settings',
			array( $this, 'render_plugin_settings_page' )
		);

		// Schema Overview submenu
		add_submenu_page(
			'fatlabschema',
			__( 'Schema Overview', 'fatlabschema' ),
			__( 'Schema Overview', 'fatlabschema' ),
			'manage_options',
			'fatlabschema-overview',
			array( $this, 'render_overview_page' )
		);

		// Tools submenu
		add_submenu_page(
			'fatlabschema',
			__( 'Schema Tools', 'fatlabschema' ),
			__( 'Tools', 'fatlabschema' ),
			'manage_options',
			'fatlabschema-tools',
			array( $this, 'render_tools_page' )
		);

		// Help submenu
		add_submenu_page(
			'fatlabschema',
			__( 'Help & Documentation', 'fatlabschema' ),
			__( 'Help', 'fatlabschema' ),
			'manage_options',
			'fatlabschema-help',
			array( $this, 'render_help_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		// Register organization settings
		register_setting(
			'fatlabschema_organization_settings',
			'fatlabschema_organization',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_organization_settings' ),
				'default'           => array(),
			)
		);

		// Register plugin settings
		register_setting(
			'fatlabschema_plugin_settings',
			'fatlabschema_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_plugin_settings' ),
				'default'           => array(
					'enabled'            => true,
					'conflict_detection' => true,
					'show_ai_badges'     => true,
					'debug_mode'         => false,
				),
			)
		);
	}

	/**
	 * Sanitize organization settings.
	 *
	 * @param array $input The input array.
	 * @return array Sanitized settings.
	 */
	public function sanitize_organization_settings( $input ) {
		$sanitized = array();

		if ( isset( $input['type'] ) ) {
			$allowed_types = array( 'Organization', 'NGO', 'PoliticalOrganization', 'EducationalOrganization' );
			$sanitized['type'] = in_array( $input['type'], $allowed_types, true ) ? $input['type'] : 'Organization';
		}

		if ( isset( $input['name'] ) ) {
			$sanitized['name'] = sanitize_text_field( $input['name'] );
		}

		if ( isset( $input['url'] ) ) {
			$sanitized['url'] = esc_url_raw( $input['url'] );
		}

		if ( isset( $input['logo'] ) ) {
			$sanitized['logo'] = esc_url_raw( $input['logo'] );
		}

		if ( isset( $input['description'] ) ) {
			$sanitized['description'] = sanitize_textarea_field( $input['description'] );
		}

		if ( isset( $input['email'] ) ) {
			$sanitized['email'] = sanitize_email( $input['email'] );
		}

		if ( isset( $input['telephone'] ) ) {
			$sanitized['telephone'] = sanitize_text_field( $input['telephone'] );
		}

		// Address fields
		if ( isset( $input['street_address'] ) ) {
			$sanitized['street_address'] = sanitize_text_field( $input['street_address'] );
		}

		if ( isset( $input['address_locality'] ) ) {
			$sanitized['address_locality'] = sanitize_text_field( $input['address_locality'] );
		}

		if ( isset( $input['address_region'] ) ) {
			$sanitized['address_region'] = sanitize_text_field( $input['address_region'] );
		}

		if ( isset( $input['postal_code'] ) ) {
			$sanitized['postal_code'] = sanitize_text_field( $input['postal_code'] );
		}

		if ( isset( $input['address_country'] ) ) {
			$sanitized['address_country'] = sanitize_text_field( $input['address_country'] );
		}

		// Social profiles
		if ( isset( $input['facebook'] ) ) {
			$sanitized['facebook'] = esc_url_raw( $input['facebook'] );
		}

		if ( isset( $input['twitter'] ) ) {
			$sanitized['twitter'] = esc_url_raw( $input['twitter'] );
		}

		if ( isset( $input['linkedin'] ) ) {
			$sanitized['linkedin'] = esc_url_raw( $input['linkedin'] );
		}

		if ( isset( $input['instagram'] ) ) {
			$sanitized['instagram'] = esc_url_raw( $input['instagram'] );
		}

		// NGO-specific fields
		if ( isset( $input['mission_statement'] ) ) {
			$sanitized['mission_statement'] = sanitize_textarea_field( $input['mission_statement'] );
		}

		if ( isset( $input['founding_date'] ) ) {
			$sanitized['founding_date'] = sanitize_text_field( $input['founding_date'] );
		}

		return $sanitized;
	}

	/**
	 * Sanitize plugin settings.
	 *
	 * @param array $input The input array.
	 * @return array Sanitized settings.
	 */
	public function sanitize_plugin_settings( $input ) {
		$sanitized = array();

		$sanitized['enabled']                     = isset( $input['enabled'] ) ? (bool) $input['enabled'] : false;
		$sanitized['conflict_detection']          = isset( $input['conflict_detection'] ) ? (bool) $input['conflict_detection'] : false;
		$sanitized['show_ai_badges']              = isset( $input['show_ai_badges'] ) ? (bool) $input['show_ai_badges'] : false;
		$sanitized['debug_mode']                  = isset( $input['debug_mode'] ) ? (bool) $input['debug_mode'] : false;
		$sanitized['preserve_data_on_uninstall']  = isset( $input['preserve_data_on_uninstall'] ) ? (bool) $input['preserve_data_on_uninstall'] : false;

		// Organization schema priority
		$allowed_priorities = array( 'suppress_others', 'warn_only', 'allow_both' );
		$sanitized['organization_schema_priority'] = isset( $input['organization_schema_priority'] ) && in_array( $input['organization_schema_priority'], $allowed_priorities, true )
			? $input['organization_schema_priority']
			: 'suppress_others';

		return $sanitized;
	}

	/**
	 * Add meta box to post/page editor.
	 */
	public function add_meta_box() {
		$post_types = get_post_types( array( 'public' => true ), 'names' );

		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'fatlabschema_wizard',
				'<span class="dashicons dashicons-admin-site-alt"></span> ' . __( 'FatLab Schema Wizard', 'fatlabschema' ),
				array( $this, 'render_meta_box' ),
				$post_type,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Render meta box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box( $post ) {
		// Add nonce for security
		wp_nonce_field( 'fatlabschema_meta_box', 'fatlabschema_meta_box_nonce' );

		// Get existing values
		$schema_type      = get_post_meta( $post->ID, '_fatlabschema_type', true );
		$schema_data      = get_post_meta( $post->ID, '_fatlabschema_data', true );
		$schema_enabled   = get_post_meta( $post->ID, '_fatlabschema_enabled', true );
		$wizard_completed = get_post_meta( $post->ID, '_fatlabschema_wizard_completed', true );

		// Load wizard view
		require_once FATLABSCHEMA_PATH . 'admin/views/wizard-metabox.php';
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_meta_box_data( $post_id ) {
		// Check if nonce is set
		if ( ! isset( $_POST['fatlabschema_meta_box_nonce'] ) ) {
			return;
		}

		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['fatlabschema_meta_box_nonce'], 'fatlabschema_meta_box' ) ) {
			return;
		}

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save schema type
		if ( isset( $_POST['fatlabschema_type'] ) ) {
			update_post_meta( $post_id, '_fatlabschema_type', sanitize_text_field( $_POST['fatlabschema_type'] ) );
		}

		// Save schema enabled status
		if ( isset( $_POST['fatlabschema_enabled'] ) ) {
			update_post_meta( $post_id, '_fatlabschema_enabled', (bool) $_POST['fatlabschema_enabled'] );
		} else {
			update_post_meta( $post_id, '_fatlabschema_enabled', false );
		}

		// Save wizard completed status
		if ( isset( $_POST['fatlabschema_wizard_completed'] ) ) {
			update_post_meta( $post_id, '_fatlabschema_wizard_completed', (bool) $_POST['fatlabschema_wizard_completed'] );
		}

		// Save schema data (will be sanitized by individual schema classes)
		if ( isset( $_POST['fatlabschema_data'] ) ) {
			update_post_meta( $post_id, '_fatlabschema_data', $_POST['fatlabschema_data'] );
		}

		// Save conflict override flag
		if ( isset( $_POST['fatlabschema_override_conflict'] ) && $_POST['fatlabschema_override_conflict'] ) {
			update_post_meta( $post_id, '_fatlabschema_override_conflict', true );
		} else {
			delete_post_meta( $post_id, '_fatlabschema_override_conflict' );
		}

		// Clear any cached schema for this post
		delete_transient( 'fatlabschema_output_' . $post_id );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @param string $hook The current admin page.
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Define our admin pages
		$our_pages = array(
			'toplevel_page_fatlabschema',
			'schema-wizard_page_fatlabschema-settings',
			'schema-wizard_page_fatlabschema-overview',
			'schema-wizard_page_fatlabschema-tools',
			'schema-wizard_page_fatlabschema-help',
		);

		$is_our_page = in_array( $hook, $our_pages, true );
		$is_post_editor = ( 'post.php' === $hook || 'post-new.php' === $hook );

		// Only proceed if on our pages or post editor
		if ( ! $is_our_page && ! $is_post_editor ) {
			return;
		}

		// Enqueue CSS
		wp_enqueue_style(
			'fatlabschema-admin',
			FATLABSCHEMA_URL . 'admin/css/fatlabschema-admin.css',
			array(),
			FATLABSCHEMA_VERSION
		);

		// Enqueue JavaScript with defer attribute for better performance
		wp_enqueue_script(
			'fatlabschema-admin',
			FATLABSCHEMA_URL . 'admin/js/fatlabschema-admin.js',
			array( 'jquery', 'wp-util' ),
			FATLABSCHEMA_VERSION,
			true
		);

		// Add defer attribute to our script
		add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 2 );

		// Localize script
		wp_localize_script(
			'fatlabschema-admin',
			'fatlabschemaAdmin',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'fatlabschema_admin_nonce' ),
				'strings'  => array(
					'confirm_delete' => __( 'Are you sure you want to remove this schema?', 'fatlabschema' ),
					'saving'         => __( 'Saving...', 'fatlabschema' ),
					'saved'          => __( 'Saved!', 'fatlabschema' ),
					'error'          => __( 'Error saving data.', 'fatlabschema' ),
				),
			)
		);

		// Only enqueue media uploader on our settings pages (not post editor)
		// This saves significant JavaScript loading on post editor
		if ( $is_our_page ) {
			wp_enqueue_media();
		}
	}

	/**
	 * Add defer attribute to our admin script.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string
	 */
	public function add_defer_attribute( $tag, $handle ) {
		if ( 'fatlabschema-admin' !== $handle ) {
			return $tag;
		}

		// Add defer attribute if not already present
		if ( strpos( $tag, ' defer' ) === false ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Add settings link on plugins page.
	 *
	 * @param array $links Existing plugin action links.
	 * @return array Modified plugin action links.
	 */
	public function add_plugin_action_links( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=fatlabschema' ) . '">' . __( 'Settings', 'fatlabschema' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Add contextual help tabs.
	 */
	public function add_contextual_help() {
		$screen = get_current_screen();

		$screen->add_help_tab(
			array(
				'id'      => 'fls_overview',
				'title'   => __( 'Overview', 'fatlabschema' ),
				'content' => '<p>' . __( 'FatLab Schema Wizard helps you add structured data (schema markup) to your WordPress site. Start by configuring your Organization schema on this page.', 'fatlabschema' ) . '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'fls_organization',
				'title'   => __( 'Organization Schema', 'fatlabschema' ),
				'content' => '<p>' . __( 'Organization schema appears on every page of your site and tells search engines about your organization. This is required before adding page-level schema.', 'fatlabschema' ) . '</p>',
			)
		);

		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'fatlabschema' ) . '</strong></p>' .
			'<p><a href="https://fatlabwebsupport.com/projects/schema-wizard/docs/" target="_blank">' . __( 'Documentation', 'fatlabschema' ) . '</a></p>' .
			'<p><a href="https://fatlabwebsupport.com/get-support/" target="_blank">' . __( 'Support', 'fatlabschema' ) . '</a></p>'
		);
	}

	/**
	 * Render organization settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once FATLABSCHEMA_PATH . 'admin/views/organization-settings.php';
	}

	/**
	 * Render plugin settings page.
	 */
	public function render_plugin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once FATLABSCHEMA_PATH . 'admin/views/settings-page.php';
	}

	/**
	 * Render tools page.
	 */
	public function render_tools_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once FATLABSCHEMA_PATH . 'admin/views/tools-page.php';
	}

	/**
	 * Render help page.
	 */
	public function render_help_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once FATLABSCHEMA_PATH . 'admin/views/help-page.php';
	}

	/**
	 * Render schema overview page.
	 */
	public function render_overview_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		require_once FATLABSCHEMA_PATH . 'admin/views/overview-page.php';
	}
}
