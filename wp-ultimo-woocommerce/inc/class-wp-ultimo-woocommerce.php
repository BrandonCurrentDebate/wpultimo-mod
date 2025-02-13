<?php
/**
 * WP Ultimo - WooCommerce Gateways main class.
 *
 * @package WP_Ultimo_WooCommerce
 * @since 2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo - WooCommerce Gateways main class
 *
 * This class instantiates our dependencies and load the things
 * our plugin needs to run.
 *
 * @package WP_Ultimo_WooCommerce
 * @since 2.0.0
 */
final class WP_Ultimo_WooCommerce {

	use \WP_Ultimo_WooCommerce\Traits\Singleton;

	/**
	 * Checks if WP Ultimo - WooCommerce Gateways was loaded or not.
	 *
	 * This is set to true when all the WP Ultimo - WooCommerce Gateways requirements are met.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $loaded = false;

	/**
	 * Version of the Plugin
	 *
	 * @var string
	 */
	public $version = '2.0.0';

	/**
	 * Loads the necessary components into the main class
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {
		/*
		 * Set up the text-domain for translations
		 */
		$this->setup_textdomain();

		/*
		 * Check if the WP Ultimo - WooCommerce Gateways requirements are present.
		 *
		 * Everything we need to run our setup install needs top be loaded before this
		 * and have no dependencies outside of the classes loaded so far.
		 */
		if (WP_Ultimo_WooCommerce\Requirements::met() === false) {

			return;

		} // end if;

		$this->loaded = true;

		/**
		 * Run the updater.
		 */
		WP_Ultimo_WooCommerce\Updater::get_instance();

		/*
		 * Loads the APIs from WooCommerce
		 */
		require_once WP_ULTIMO_WOOCOMMERCE_PLUGIN_DIR . 'inc/functions/woo.php';

		/**
		 * Loads the main gateway class.
		 */
		add_action('wu_register_gateways', array($this, 'register_gateways'), 20);

		/**
		 * Triggers after all the add-on dependencies were loaded.
		 *
		 * Allows plugin developers to add new functionality.
		 *
		 * @since 2.0.0
		 */
		do_action('wp_ultimo_woocommerce_load');

		/**
		 * Loads WP Ultimo's WooCommerce top menu shortcut
		 */
		new WP_Ultimo_WooCommerce\Managers\Top_Menu_Manager;

	} // end init;

	/**
	 * Returns true if all the requirements are met.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function is_loaded() {

		return $this->loaded;

	} // end is_loaded;

	/**
	 * Runs when WP Ultimo is fully loaded.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_gateways() {

		wu_register_gateway('woocommerce', __('WooCommerce', 'wp-ultimo'), __('Accept payments using any of the hundreds of payment gateways available for WooCommerce and WooCommerce Subscriptions.', 'wp-ultimo'), '\\WP_Ultimo_WooCommerce\\Gateways\\WooCommerce_Gateway');

	} // end register_gateways;

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
		load_plugin_textdomain('wp-ultimo-woocommerce', false, dirname(WP_ULTIMO_WOOCOMMERCE_PLUGIN_BASENAME) . '/lang');

	} // end setup_textdomain;

} // end class WP_Ultimo_WooCommerce;
