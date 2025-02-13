<?php
/**
 * WP Ultimo Captcha main class.
 *
 * @package WP_Ultimo_Captcha
 * @since 1.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo Captcha main class
 *
 * This class instantiates our dependencies and load the things
 * our plugin needs to run.
 *
 * @package WP_Ultimo_Captcha
 * @since 1.0.0
 */
final class WP_Ultimo_Captcha {

	use \WP_Ultimo_Captcha\Traits\Singleton;

	/**
	 * Checks if WP Ultimo Captcha was loaded or not.
	 *
	 * This is set to true when all the WP Ultimo Captcha requirements are met.
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
	public $version = '1.0.0-beta.1';

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
		$this->helper = WP_Ultimo_Captcha\Helper::get_instance();

		/*
		 * Set up the text-domain for translations
		 */
		$this->setup_textdomain();

		/*
		 * Check if the WP Ultimo Captcha requirements are present.
		 *
		 * Everything we need to run our setup install needs top be loaded before this
		 * and have no dependencies outside of the classes loaded so far.
		 */
		if (WP_Ultimo_Captcha\Requirements::met() === false) {

			return;

		} // end if;

		$this->loaded = true;

		/**
		 * Run the updater.
		 */
		WP_Ultimo_Captcha\Updater::get_instance();

		/**
		 *  Security Captcha for WP Ultimo
		 */
		\WP_Ultimo_Captcha\Security_Captcha::get_instance();

		/**
		 * Triggers when all the dependencies were loaded
		 *
		 * Allows plugin developers to add new functionality. For example, support to new
		 * Hosting providers, etc.
		 *
		 * @since 1.0.0
		 */
		do_action('wp_ultimo_captcha_load');

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
		load_plugin_textdomain('wp-ultimo-captcha', false, dirname(WP_ULTIMO_CAPTCHA_PLUGIN_BASENAME) . '/lang');

	} // end setup_textdomain;

} // end class WP_Ultimo_Captcha;
