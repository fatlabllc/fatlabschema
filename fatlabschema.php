<?php
/**
 * Plugin Name: FatLab Schema Wizard
 * Plugin URI: https://fatlabwebsupport.com/projects/schema-wizard/
 * Description: Schema markup that knows when to say no. Intelligent wizard guides you to correct schema implementation optimized for AI search.
 * Version: 1.0.5
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: FatLab Web Support
 * Author URI: https://fatlabwebsupport.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: fatlabschema
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'FATLABSCHEMA_VERSION', '1.0.5' );

/**
 * Plugin basename.
 */
define( 'FATLABSCHEMA_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Plugin directory path.
 */
define( 'FATLABSCHEMA_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'FATLABSCHEMA_URL', plugin_dir_url( __FILE__ ) );

/**
 * Minimum required PHP version.
 */
define( 'FATLABSCHEMA_MIN_PHP_VERSION', '7.4' );

/**
 * Minimum required WordPress version.
 */
define( 'FATLABSCHEMA_MIN_WP_VERSION', '5.8' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
class FatLab_Schema_Wizard {

	/**
	 * The single instance of the class.
	 *
	 * @var FatLab_Schema_Wizard
	 */
	private static $instance = null;

	/**
	 * Main FatLab_Schema_Wizard Instance.
	 *
	 * Ensures only one instance of FatLab_Schema_Wizard is loaded or can be loaded.
	 *
	 * @return FatLab_Schema_Wizard - Main instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		// Register autoloader for schema classes (lazy loading)
		spl_autoload_register( array( $this, 'autoload_schema_classes' ) );

		// Core classes (always needed)
		require_once FATLABSCHEMA_PATH . 'includes/class-fls-schema-manager.php';
		require_once FATLABSCHEMA_PATH . 'includes/class-fls-schema-generator.php';
		require_once FATLABSCHEMA_PATH . 'includes/class-fls-validator.php';
		require_once FATLABSCHEMA_PATH . 'includes/class-fls-conflict-detector.php';
		require_once FATLABSCHEMA_PATH . 'includes/class-fls-schema-suppressor.php';
		require_once FATLABSCHEMA_PATH . 'includes/class-fls-output.php';

		// Admin classes (only if in admin)
		if ( is_admin() ) {
			require_once FATLABSCHEMA_PATH . 'includes/class-fls-admin.php';
			require_once FATLABSCHEMA_PATH . 'includes/class-fls-admin-notices.php';
			require_once FATLABSCHEMA_PATH . 'includes/class-fls-wizard.php';
			require_once FATLABSCHEMA_PATH . 'includes/class-fls-ajax.php';
		}

		// Note: Schema type classes are now autoloaded on-demand
	}

	/**
	 * Autoload schema classes on demand.
	 *
	 * @param string $class_name Class name to load.
	 */
	public function autoload_schema_classes( $class_name ) {
		// Only handle our schema classes
		if ( strpos( $class_name, 'FLS_Schema_' ) !== 0 ) {
			return;
		}

		// Convert class name to file name
		// FLS_Schema_Organization -> class-fls-schema-organization.php
		$file_name = 'class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
		$file_path = FATLABSCHEMA_PATH . 'schemas/' . $file_name;

		if ( file_exists( $file_path ) ) {
			require_once $file_path;
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'fatlabschema',
			false,
			dirname( FATLABSCHEMA_BASENAME ) . '/languages/'
		);
	}

	/**
	 * Register all of the hooks related to the admin area functionality.
	 */
	private function define_admin_hooks() {
		if ( is_admin() ) {
			$admin = new FLS_Admin();
			$notices = new FLS_Admin_Notices();
			$ajax = new FLS_Ajax();
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 */
	private function define_public_hooks() {
		$output = new FLS_Output();

		// Initialize schema suppression for other plugins
		FLS_Schema_Suppressor::init();
	}
}

/**
 * Begins execution of the plugin.
 *
 * @return FatLab_Schema_Wizard
 */
function fatlabschema() {
	return FatLab_Schema_Wizard::get_instance();
}

// Activation hook
register_activation_hook( __FILE__, 'fatlabschema_activate' );

/**
 * The code that runs during plugin activation.
 */
function fatlabschema_activate() {
	// Check PHP version
	if ( version_compare( PHP_VERSION, FATLABSCHEMA_MIN_PHP_VERSION, '<' ) ) {
		deactivate_plugins( FATLABSCHEMA_BASENAME );
		wp_die(
			sprintf(
				/* translators: %s: Required PHP version */
				esc_html__( 'FatLab Schema Wizard requires PHP version %s or higher. Please upgrade PHP or deactivate the plugin.', 'fatlabschema' ),
				FATLABSCHEMA_MIN_PHP_VERSION
			),
			esc_html__( 'Plugin Activation Error', 'fatlabschema' ),
			array( 'back_link' => true )
		);
	}

	// Check WordPress version
	if ( version_compare( get_bloginfo( 'version' ), FATLABSCHEMA_MIN_WP_VERSION, '<' ) ) {
		deactivate_plugins( FATLABSCHEMA_BASENAME );
		wp_die(
			sprintf(
				/* translators: %s: Required WordPress version */
				esc_html__( 'FatLab Schema Wizard requires WordPress version %s or higher. Please upgrade WordPress or deactivate the plugin.', 'fatlabschema' ),
				FATLABSCHEMA_MIN_WP_VERSION
			),
			esc_html__( 'Plugin Activation Error', 'fatlabschema' ),
			array( 'back_link' => true )
		);
	}

	// Set default options
	$default_settings = array(
		'enabled'            => true,
		'conflict_detection' => true,
		'show_ai_badges'     => true,
		'auto_suggest'       => false,
		'debug_mode'         => false,
	);

	add_option( 'fatlabschema_settings', $default_settings );
	add_option( 'fatlabschema_version', FATLABSCHEMA_VERSION );
	add_option( 'fatlabschema_activated', time() );

	// Set transient for welcome notice
	set_transient( 'fatlabschema_activation_notice', true, 30 );
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'fatlabschema_deactivate' );

/**
 * The code that runs during plugin deactivation.
 */
function fatlabschema_deactivate() {
	// Clear any transients
	delete_transient( 'fatlabschema_activation_notice' );

	// Note: We don't delete options here - that's done in uninstall.php
}

// Initialize the plugin
fatlabschema();
