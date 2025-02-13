<?php
/**
 * WP Ultimo Plugin And Theme Manager main class.
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @since 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo Plugin And Theme Manager main class
 *
 * This class instantiates our dependencies and load the things
 * our plugin needs to run.
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @since 1.0.0
 */
final class WP_Ultimo_Plugin_And_Theme_Manager {

	use \WP_Ultimo_Plugin_And_Theme_Manager\Traits\Singleton;

	/**
	 * Checks if WP Ultimo Plugin And Theme Manager was loaded or not.
	 *
	 * This is set to true when all the WP Ultimo Plugin And Theme Manager requirements are met.
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	protected $loaded = false;

	/**
	 * Version of the Plugin
	 *
	 * @var string
	 */
	public $version = '2.0.0-beta.1';

	/**
	 * Loads the necessary components into the main class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		/*
		 * Loads the WP_Ultimo\Helper class.
		 */
		$this->helper = WP_Ultimo_Plugin_And_Theme_Manager\Helper::get_instance();

		/*
		 * Helper Functions
		 */
		require_once $this->helper->path('inc/functions/helper.php');

		/*
		 * Render Functions
		 */
		require_once $this->helper->path('inc/functions/template.php');

		/*
		 * Loads the WP Ultimo settings helper class.
		 */
		new WP_Ultimo_Plugin_And_Theme_Manager\Settings();

		/*
		 * Set up the text-domain for translations
		 */
		$this->setup_textdomain();

		/*
		 * Check if the WP Ultimo Plugin And Theme Manager requirements are present.
		 *
		 * Everything we need to run our setup install needs top be loaded before this
		 * and have no dependencies outside of the classes loaded so far.
		 */
		if (WP_Ultimo_Plugin_And_Theme_Manager\Requirements::met() === false) {

			return;

		} // end if;

		/*
		 * Loads admin pages
		 */
		$this->load_admin_pages();

		/*
		 * Loads Managers
		 */
		$this->load_managers();

		$this->loaded = true;

		/**
		 * Run the updater.
		 */
		WP_Ultimo_Plugin_And_Theme_Manager\Updater::get_instance();

		/**
		 * Triggers when all the dependencies were loaded
		 *
		 * Allows plugin developers to add new functionality. For example, support to new
		 * Hosting providers, etc.
		 *
		 * @since 1.0.0
		 */
		do_action('wp_ultimo_plugin_and_theme_manager_load');

	} // end init;

	/**
	 * Returns true if all the requirements are met.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function is_loaded() {

		return $this->loaded;

	} // end is_loaded;

	/**
	 * Setup the plugin text domain to be used in translations.
	 *
	 * @since 0.0.1
	 * @return void
	 */
	public function setup_textdomain() {
		/*
		 * Loads the translation files.
		 */
		load_plugin_textdomain('wp-ultimo-plugin-and-theme-manager', false, dirname(WP_ULTIMO_PLUGIN_AND_THEME_MANAGER_PLUGIN_BASENAME) . '/lang');

	} // end setup_textdomain;

	/**
	 * Load the WP_Ultimo_Plugin_And_Theme_Manager Addon Admin Pages.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	protected function load_admin_pages() {} // end load_admin_pages;

	/**
	 * Load extra the WU PTM managers.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	protected function load_managers() {
		/*
		 * Loads the PTM manager.
		 */
		WP_Ultimo_Plugin_And_Theme_Manager\Managers\Plugin_And_Theme_Manager::get_instance();

	} // end load_managers;

} // end class WP_Ultimo_Plugin_And_Theme_Manager;
