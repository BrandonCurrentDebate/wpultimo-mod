<?php
/**
 * Check if all the pre-requisites to run WP Ultimo: AffiliateWP Integration are in place.
 *
 * @package WP_Ultimo_AffiliateWP
 * @subpackage Requirements
 * @since 1.0.0
 */

namespace WP_Ultimo_AffiliateWP;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Check if all the pre-requisites to run WP Ultimo: AffiliateWP Integration are in place.
 *
 * @since 1.0.0
 */
class Requirements {

	/**
	 * Caches the result of the requirement check.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public static $met;

	/**
	 * Minimum PHP version required to run WP Ultimo: AffiliateWP Integration.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public static $php_version = '7.1.3';

	/**
	 * Recommended PHP Version
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public static $php_recommended_version = '7.1.4';

	/**
	 * Minimum WordPress version required to run WP Ultimo: AffiliateWP Integration.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public static $wp_version = '5.1.2';

	/**
	 * Recommended WP Version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public static $wp_recommended_version = '5.4.2';

	/**
	 * The required WP Ultimo version.
	 *
	 * @var string
	 */
	public static $wp_ultimo_version = '2.0.9';

	/**
	 * The recommended WP Ultimo version.
	 *
	 * @var string
	 */
	public static $wp_ultimo_recommended_version = '2.0.9';

	/**
	 * Static-only class.
	 */
	private function __construct() {} // end __construct;

	/**
	 * Check if the minimum pre-requisites to run WP Ultimo: AffiliateWP Integration are present.
	 *
	 * - Check if the PHP version requirements are met;
	 * - Check if the WordPress version requirements are met;
	 * - Check if the install is a Multisite install;
	 * - Check if WP Ultimo: AffiliateWP Integration is network active.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function met() {

		if (self::$met === null) {

			self::$met = (
				self::check_php_version()
				&& self::check_wp_version()
				&& self::is_multisite()
				&& self::is_network_active()
				&& self::check_wp_ultimo_version()
			);

		} // end if;

		return self::$met;

	} // end met;

	/**
	 * Checks if we have ran through the setup already.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function run_setup() {

		if (self::is_unit_test()) {

			return true;

		} // end if;

		return get_network_option(null, 'wp_ultimo_affiliatewp_setup_finished', false);

	} // end run_setup;

	/**
	 * Checks for a test environment.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_unit_test() {

		return defined('WP_TESTS_MULTISITE') && WP_TESTS_MULTISITE;

	} // end is_unit_test;

	/**
	 * Check if the PHP version requirements are met
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function check_php_version() {

		if (version_compare(phpversion(), self::$php_version, '<')) {

			add_action('network_admin_notices', array('WP_Ultimo_AffiliateWP\Requirements', 'notice_unsupported_php_version'));

			return false;

		} // end if;

		return true;

	} // end check_php_version;

	/**
	 * Check if the WordPress version requirements are met
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function check_wp_version() {

		global $wp_version;

		if (version_compare($wp_version, self::$wp_version, '<')) {

			add_action('network_admin_notices', array('WP_Ultimo_AffiliateWP\Requirements', 'notice_unsupported_wp_version'));

			return false;

		} // end if;

		return true;

	} // end check_wp_version;

	/**
	 * Check if the WordPress version requirements are met
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function check_wp_ultimo_version() {

		if (!function_exists('\WP_Ultimo')) {

			add_action('network_admin_notices', array('WP_Ultimo_AffiliateWP\Requirements', 'notice_unsupported_wp_ultimo_version'));

			return false;

		} // end if;

		if (version_compare(WP_Ultimo()->version, self::$wp_ultimo_version, '<')) {

			add_action('network_admin_notices', array('WP_Ultimo_AffiliateWP\Requirements', 'notice_unsupported_wp_ultimo_version'));

			return false;

		} // end if;

		return true;

	} // end check_wp_ultimo_version;

	/**
	 * Check if the install is a Multisite install
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_multisite() {

		if (!is_multisite()) {

			add_action('admin_notices', array('WP_Ultimo_AffiliateWP\Requirements', 'notice_not_multisite'));

			return false;

		} // end if;

		return true;

	} // end is_multisite;

	/**
	 * Check if WP Ultimo: AffiliateWP Integration is network active
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public static function is_network_active() {

		if (!function_exists('is_plugin_active_for_network')) {

			require_once ABSPATH . '/wp-admin/includes/plugin.php';

		} // end if;

		if (!is_plugin_active_for_network(WP_ULTIMO_AFFILIATEWP_PLUGIN_BASENAME) && !self::is_unit_test()) {

			add_action('admin_notices', array('WP_Ultimo_AffiliateWP\Requirements', 'notice_not_network_active'));

			return false;

		} // end if;

		return true;

	} // end is_network_active;

	/**
	 * Adds a network admin notice about the PHP requirements not being met
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function notice_unsupported_php_version() {

		// translators: the %1$s placeholder is the required PHP version, while the %2$s is the current PHP version.
		$message = sprintf(__('WP Ultimo: AffiliateWP Integration requires at least PHP version %1$s to run. Your current PHP version is <strong>%2$s</strong>. Please, contact your hosting company support to upgrade your PHP version. If you want maximum performance consider upgrading your PHP to version 7.0 or later.', 'wp-ultimo-affiliatewp'), self::$php_version, phpversion());

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);

	} // end notice_unsupported_php_version;

	/**
	 * Adds a network admin notice about the WordPress requirements not being met
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function notice_unsupported_wp_version() {

		global $wp_version;

		// translators: the %1$s placeholder is the required WP version, while the %2$s is the current WP version.
		$message = sprintf(__('WP Ultimo: AffiliateWP Integration requires at least WordPress version %1$s to run. Your current WordPress version is <strong>%2$s</strong>.', 'wp-ultimo-affiliatewp'), self::$wp_version, $wp_version);

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);

	} // end notice_unsupported_wp_version;

	/**
	 * Adds a network admin notice about the install not being a multisite install
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function notice_not_multisite() {

		$message = __('WP Ultimo: AffiliateWP Integration requires a multisite install to run properly. To know more about WordPress Networks, visit this link: <a href="https://wordpress.org/support/article/create-a-network/">Create a Network &rarr;</a>', 'wp-ultimo-affiliatewp');

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);

	} // end notice_not_multisite;

	/**
	 * Adds a network admin notice about the WP Ultimo: AffiliateWP Integration not being network-active
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function notice_not_network_active() {

		// translators: %s is a placeholder for the Network Admin plugins page URL.
		$message = sprintf(__('WP Ultimo: AffiliateWP Integration needs to be network active to run properly. You can "Network Activate" it <a href="%s">here</a>', 'wp-ultimo-affiliatewp'), network_admin_url('plugins.php'));

		printf('<div class="notice notice-error"><p>%s</p></div>', $message);

	} // end notice_not_network_active;

	/**
	 * Adds a network admin notice about the WP Ultimo: AffiliateWP Integration not being network-active
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function notice_unsupported_wp_ultimo_version() {

		// translators: the %1$s placeholder is the required WP Ultimo version.
		$message = sprintf(__('WP Ultimo: AffiliateWP Integration requires WP Ultimo version %1$s or later to be installed and activated to run.', 'wp-ultimo-affiliatewp'), self::$wp_ultimo_version);
		printf('<div class="notice notice-error"><p>%s</p></div>', $message);

	} // end notice_unsupported_wp_ultimo_version;

} // end class Requirements;
