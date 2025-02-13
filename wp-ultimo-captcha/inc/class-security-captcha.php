<?php
/**
 * Security_Recaptcha
 *
 * @package WP_Ultimo_Captcha
 * @subpackage Security_Recaptcha
 * @since 2.0.0
 */

namespace WP_Ultimo_Captcha;

use \WP_Ultimo_Captcha\Dependencies\ReCaptcha\ReCaptcha;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Handles the basic Recaptcha.
 *
 * @since 2.0.0
 */
class Security_Captcha {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Adds the hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {

		add_action('admin_init', array($this, 'add_settings'), 20);

		add_action('wu_setup_checkout', array($this, 'hooks'));

		add_action('wu_before_handle_order_submission', array($this, 'validate_captcha'));

		add_action('wu_checkout_scripts', array($this, 'register_scripts'), 11);

		add_action('wu_checkout_custom_css', array($this, 'apply_invisible_badge'));

	} // end init;

	/**
	 * Add the necessary hooks when the feature is enabled.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function hooks() {

		add_action('wu_checkout_after_form', array($this, 'maybe_render_recaptcha_no_keys'));

	} // end hooks;

	/**
	 * Apply the option to hide the recaptcha badge.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function apply_invisible_badge() {

		if (wu_get_setting('select_recaptcha') === 'v3_recaptcha' && wu_get_setting('hide_v3_recaptcha', true)) {

			return ' .grecaptcha-badge { visibility: hidden; } ';

		} // end if;

	} // end apply_invisible_badge;

	/**
	 * Adds the scripts.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_scripts() {

		if (is_user_logged_in()) {

			return;

		} // end if;

		wp_register_script('wu-captcha', \WP_Ultimo_Captcha::get_instance()->helper->get_asset('captcha.js', 'js'), array('jquery'), wu_get_version(), true);

		wp_localize_script('wu-captcha', 'wu_captcha', array(
			'recaptcha_version_type' => wu_get_setting('select_recaptcha'),
			'recaptcha_site_key'     => wu_get_setting('site_key_recaptcha'),
			'recaptcha_display'      => wu_get_setting('select_recaptcha_display'),
			'recaptcha_theme'        => wu_get_setting('select_recaptcha_theme'),
			'ajaxurl'                => get_admin_url(wu_get_main_site_id(), 'admin-ajax.php')
		));

		wp_enqueue_script('wu-captcha');

		if (wu_get_setting('select_recaptcha') === 'hcaptcha') {

			// $url_api = '//hcaptcha.com/1/api.js';

			// wp_enqueue_script('wu-recaptcha-api-url', $url_api);

		} else {

			$url_api = wu_get_setting('select_recaptcha') === 'v3_recaptcha' ? 'https://www.google.com/recaptcha/api.js?render=' . wu_get_setting('site_key_recaptcha') : 'https://www.google.com/recaptcha/api.js';

			wp_enqueue_script('wu-recaptcha-api-url', $url_api);

		} // end if;

	} // end register_scripts;

	/**
	 * Callback backend recaptcha validation.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function validate_captcha() {

		if (isset($_POST['g-recaptcha-response'])) {

			if (wu_get_setting('select_recaptcha') === 'v3_recaptcha') {

				$this->verify_request_recaptcha_v3($_POST['g-recaptcha-response']);

			} elseif (wu_get_setting('select_recaptcha') === 'hcaptcha') {

				$this->verify_request_hcaptcha($_POST['g-recaptcha-response']);

			} else {

				$this->verify_request_recaptcha_v2($_POST['g-recaptcha-response']);

			} // end if;

		} // end if;

	}  // end validate_captcha;

	/**
	 * Verify post request hCaptcha in backend.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token The input token recived.
	 *
	 * @return void
	 */
	public function verify_request_hcaptcha($token) {

		$wu_score_threshold = apply_filters('wu_score_threshold', 0.5);

		$resp      = wp_remote_get('https://hcaptcha.com/siteverify?secret=' . wu_get_setting('site_secret_recaptcha') . '&response=' . $_POST['g-recaptcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
		$resp_body = wp_remote_retrieve_body($resp);
		$resp_attr = json_decode($resp_body);

		if (is_wp_error($resp)) {

			$error = new \WP_Error('error-hcaptcha', __('Recaptcha Validation Failed.', 'wp-ultimo-captcha'), $resp);

			wp_send_json_error($error);

		} // end if;

	} // end verify_request_hcaptcha;

	/**
	 * Verify post request Google Recaptcha v3 in backend.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token The input token recived.
	 *
	 * @return void
	 */
	public function verify_request_recaptcha_v3($token) {

		$wu_score_threshold = apply_filters('wu_score_threshold', 0.5);

		$resp      = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret=' . wu_get_setting('site_secret_recaptcha') . '&response=' . $_POST['g-recaptcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
		$resp_body = wp_remote_retrieve_body($resp);
		$resp_attr = json_decode($resp_body);

		if (is_wp_error($resp)) {

			$error = new \WP_Error('error-google-recaptcha-v3', __('Recaptcha Validation Failed.', 'wp-ultimo-captcha'), $resp);

			wp_send_json_error($error);

		} elseif (isset($resp_attr->score) && $resp_attr->score < $wu_score_threshold) {

			$error = new \WP_Error('error-google-recaptcha-score-v3', __('Recaptcha Score Threshold Failed.', 'wp-ultimo-captcha'), $resp);

			wp_send_json_error($error);

		} // end if;

	} // end verify_request_recaptcha_v3;

	/**
	 * Verify post request Google Recaptcha v2 in backend.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token The input token received.
	 *
	 * @return void
	 */
	public function verify_request_recaptcha_v2($token) {

		$recaptcha = new ReCaptcha(wu_get_setting('site_secret_recaptcha'));

		$resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

		if (!$resp->isSuccess()) {

			$error = new \WP_Error('error-google-recaptcha-v2', __('Recaptcha Validation Failed.', 'wp-ultimo-captcha'), $resp);

			wp_send_json_error($error);

		} // end if;

	} // end verify_request_recaptcha_v2;

	/**
	 * Render the error message if no key data is found.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function maybe_render_recaptcha_no_keys() {

		if (!is_user_logged_in() || !current_user_can('manage_network')) {

			return;

		} // end if;

		if (!wu_get_setting('enable_recaptcha', true)) {

			return;

		} // end if;

		if (wu_get_setting('site_secret_recaptcha') && wu_get_setting('site_key_recaptcha')) {

			return;

		} // end if;

		\WP_Ultimo_Captcha::get_instance()->helper->render('captcha/no_keys');

	} // end maybe_render_recaptcha_no_keys;

	/**
	 * Adds the Recaptcha options.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_settings() {

		wu_register_settings_section('captcha', array(
			'title' => __('Captcha', 'wp-ultimo-captcha'),
			'desc'  => __('Captcha', 'wp-ultimo-captcha'),
			'icon'  => 'dashicons-wu-checkbox-checked',
			'order' => 5,
			'addon' => true,
		));

		wu_register_settings_field('captcha', 'captcha_header', array(
			'title' => __('Security', 'wp-ultimo-captcha'),
			'desc'  => __('Protect your network from spam registrations and abuse.', 'wp-ultimo-captcha'),
			'type'  => 'header',
		));

		wu_register_settings_field('captcha', 'enable_recaptcha', array(
			'title'   => __('Enable Captcha', 'wp-ultimo-captcha'),
			'desc'    => __('Toggle this option to enable captcha spam prevent methods on the registration page.', 'wp-ultimo-captcha'),
			'type'    => 'toggle',
			'default' => 0,
		));

		wu_register_settings_field('captcha', 'select_recaptcha', array(
			'title'   => __('Spam Protection Method', 'wp-ultimo-captcha'),
			'desc'    => '',
			'type'    => 'select',
			'default' => 'v3_recaptcha',
			'options' => array(
				'v2_checkbox'        => __('Google ReCaptcha - V2 Checkbox', 'wp-ultimo-captcha'),
				'v2_invisible'       => __('Google ReCaptcha - V2 Invisible', 'wp-ultimo-captcha'),
				'v3_recaptcha'       => __('Google Recaptcha - V3 ReCaptcha', 'wp-ultimo-captcha'),
				'hcaptcha'           => __('hCaptcha', 'wp-ultimo-captcha'),
				'hcaptcha_invisible' => __('hCaptcha Invisible', 'wp-ultimo-captcha'),
			),
			'require' => array(
				'enable_recaptcha' => 1,
			),
		));

		wu_register_settings_field('captcha', 'hide_v3_recaptcha', array(
			'title'   => __('Hide the ReCAPTCHA v3 badge', 'wp-ultimo-captcha'),
			'desc'    => __('Hide the ReCaptcha badge in the bottom right of the screen.', 'wp-ultimo-captcha'),
			'type'    => 'toggle',
			'default' => 0,
			'require' => array(
				'enable_recaptcha' => 1,
				'select_recaptcha' => 'v3_recaptcha',
			),
		));

		wu_register_settings_field('captcha', 'site_key_recaptcha', array(
			'title'           => __('Site Key', 'wp-ultimo-captcha'),
			'placeholder'     => __('2LoD6Z4aAABAAJjZso789dBIHsrEaYrT98pqks45', 'wp-ultimo-captcha'),
			'tooltip'         => __('The site key provided by the captcha service (ReCaptcha or hCaptcha).', 'wp-ultimo-captcha'),
			'type'            => 'text',
			'default'         => '',
			'require'         => array(
				'enable_recaptcha' => 1,
				'select_recaptcha' => array('v2_checkbox', 'v2_invisible', 'v3_recaptcha', 'hcaptcha_invisible', 'hcaptcha'),
			),
			'wrapper_classes' => 'wu-w-1/2',
		));

		wu_register_settings_field('captcha', 'site_secret_recaptcha', array(
			'title'           => __('Site Secret', 'wp-ultimo-captcha'),
			'placeholder'     => __('1VqD2Z9jAAAAAOmEgsb_4SeKcx6pHdvv-L1NL7H3', 'wp-ultimo-captcha'),
			'tooltip'         => __('The secret key authorizes communication between your application backend and the (ReCaptcha or hCaptcha) server.', 'wp-ultimo-captcha'),
			'type'            => 'text',
			'default'         => '',
			'require'         => array(
				'enable_recaptcha' => 1,
				'select_recaptcha' => array('v2_checkbox', 'v2_invisible', 'v3_recaptcha', 'hcaptcha_invisible', 'hcaptcha'),
			),
			'wrapper_classes' => 'wu-w-1/2',
		));

		wu_register_settings_field('captcha', 'select_recaptcha_display', array(
			'title'   => __('Display mode', 'wp-ultimo-captcha'),
			'desc'    => __('The size of the widget.', 'wp-ultimo-captcha'),
			'type'    => 'select',
			'default' => 'normal',
			'options' => array(
				'normal'  => __('Normal', 'wp-ultimo-captcha'),
				'compact' => __('Compact', 'wp-ultimo-captcha'),
			),
			'require' => array(
				'enable_recaptcha' => 1,
				'select_recaptcha' => array('v2_checkbox', 'hcaptcha'),
			),
		));

		wu_register_settings_field('captcha', 'select_recaptcha_theme', array(
			'title'   => __('Theme mode', 'wp-ultimo-captcha'),
			'desc'    => __('The color theme of the widget.', 'wp-ultimo-captcha'),
			'type'    => 'select',
			'default' => 'light',
			'options' => array(
				'light' => __('Light', 'wp-ultimo-captcha'),
				'dark'  => __('Dark', 'wp-ultimo-captcha'),
			),
			'require' => array(
				'enable_recaptcha' => 1,
				'select_recaptcha' => array('v2_checkbox', 'hcaptcha'),
			),
		));

	} // end add_settings;

}  // end class Security_Captcha;
