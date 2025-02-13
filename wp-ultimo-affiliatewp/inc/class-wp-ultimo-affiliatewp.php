<?php
/**
 * WP Ultimo: AffiliateWP Integration main class.
 *
 * @package WP_Ultimo_AffiliateWP
 * @since 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo: AffiliateWP Integration main class
 *
 * This class instantiates our dependencies and load the things
 * our plugin needs to run.
 *
 * @package WP_Ultimo_AffiliateWP
 * @since 1.0.0
 */
final class WP_Ultimo_AffiliateWP {

	use \WP_Ultimo_AffiliateWP\Traits\Singleton;

	/**
	 * Checks if WP Ultimo: AffiliateWP Integration was loaded or not.
	 *
	 * This is set to true when all the WP Ultimo: AffiliateWP Integration requirements are met.
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
	public $version = '2.0.0';

	/**
	 * Makes sure we are only using one instance of the plugin
	 *
	 * @var object WU_Ultimo_AffiliateWP
	 */
	public static $instance;


	/**
	 * Construct.
	 */
	public function __construct() {

    	// Bail if no WP Ultimo
		if (!function_exists('WP_Ultimo')) {

			return;

		} // end if;

		$this->hooks();

	}  // end __construct;

	/**
	 * Loads the necessary components into the main class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function init() {
		/*
		* Set up the text-domain for translations
		*/
		$this->setup_textdomain();

		/*
		* Check if the WP Ultimo: AffiliateWP Integration requirements are present.
		*
		* Everything we need to run our setup install needs top be loaded before this
		* and have no dependencies outside of the classes loaded so far.
		*/
		if (WP_Ultimo_AffiliateWP\Requirements::met() === false) {

			return;

		} // end if;

		$this->loaded = true;

		/**
		 * Run the updater.
		 */
		WP_Ultimo_AffiliateWP\Updater::get_instance();

		/**
		 * Triggers when all the dependencies were loaded
		 *
		 * Allows plugin developers to add new functionality. For example, support to new
		 * Hosting providers, etc.
		 *
		 * @since 1.0.0
		 */
		do_action('wp_ultimo_affiliatewp_load');

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
		load_plugin_textdomain('wp-ultimo-affiliatewp', false, dirname(WP_ULTIMO_AFFILIATEWP_PLUGIN_BASENAME) . '/lang');

	} // end setup_textdomain;

	/**
	 * Add all the hooks we need on WP Ultimo to correctly track referrals
	 */
	public function hooks() {

		add_filter('affwp_settings_integrations', array('WP_Ultimo_AffiliateWP\Settings', 'add_wp_ultimo_on_settings'), 10, 1);

    	// Hooks that only get loaded if the WP Ultimo integration is activated
		if (in_array('wp-ultimo', array_keys(affiliate_wp()->settings->get('integrations', array())), true)) {

			if (class_exists('Affiliate_WP_Recurring_Base')) {

				/**
				 * Loads support to Recurring Referrals
				 *
				 * @since 1.2.0
				 */
				WP_Ultimo_AffiliateWP\Managers\AffiliateWP_Manager_Recurring::get_instance()->init();

			} else {

				WP_Ultimo_AffiliateWP\Managers\AffiliateWP_Manager::get_instance()->init();

			} // end if;

		} // end if;

	} // end hooks;

} // end class WP_Ultimo_AffiliateWP;
