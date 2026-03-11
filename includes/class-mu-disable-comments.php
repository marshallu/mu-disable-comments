<?php
/**
 * Main plugin class.
 *
 * @package MU_Disable_Comments
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main MU_Disable_Comments class.
 */
class MU_Disable_Comments {

	/**
	 * Single instance of the class.
	 *
	 * @var MU_Disable_Comments
	 */
	private static $instance = null;

	/**
	 * Returns the single instance of MU_Disable_Comments.
	 *
	 * @return MU_Disable_Comments
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Load required files.
	 */
	private function includes() {
		require_once plugin_dir_path( __FILE__ ) . 'class-mu-disable-comments-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-mu-disable-comments-public.php';
	}

	/**
	 * Register hooks.
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize plugin components.
	 */
	public function init() {
		new MU_Disable_Comments_Admin();
		new MU_Disable_Comments_Public();
	}
}
