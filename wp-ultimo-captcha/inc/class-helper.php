<?php
/**
 * WP Ultimo: Captcha helper methods for including and rendering files, assets, etc
 *
 * @package WP_Ultimo_Captcha
 * @subpackage Helper
 * @since 2.0.0
 */

namespace WP_Ultimo_Captcha; // phpcs:ignore

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo: Captcha helper methods for including and rendering files, assets, etc
 *
 * @since 2.0.0
 */
class Helper {

	use \WP_Ultimo_Captcha\Traits\Singleton;

	/**
	 * Returns the full path to the plugin folder
	 *
	 * @since 0.0.1
	 * @param string $dir Path relative to the plugin root you want to access.
	 * @return string
	 */
	public function path($dir) {

		return WP_ULTIMO_CAPTCHA_PLUGIN_DIR . $dir;

	} // end path;

	/**
	 * Returns the URL to the plugin folder.
	 *
	 * @since 0.0.1
	 * @param string $dir Path relative to the plugin root you want to access.
	 * @return string
	 */
	public function url($dir) {

		return apply_filters('wp_ultimo_captcha_manager_url', WP_ULTIMO_CAPTCHA_PLUGIN_URL . $dir);

	} // end url;

	/**
	 * Shorthand for url('assets/img'). Returns the URL for assets inside the assets folder.
	 *
	 * @since 0.0.1
	 * @param string $asset Asset file name with the extention.
	 * @param string $assets_dir Assets sub-directory. Defaults to 'img'.
	 * @return string
	 */
	public function get_asset($asset, $assets_dir = 'img') {

		if (!defined('SCRIPT_DEBUG') || !SCRIPT_DEBUG) {

			$asset = preg_replace('/(?<!\.min)(\.js|\.css)/', '.min$1', $asset);

		} // end if;

		return $this->url("assets/$assets_dir/$asset");

	} // end get_asset;

	/**
	 * Renders a view file from the view folder.
	 *
	 * @since 0.0.1
	 * @param string  $view View file to render. Do not include the .php extension.
	 * @param boolean $vars Key => Value pairs to be made available as local variables inside the view scope.
	 * @return void
	 */
	public function render($view, $vars = false) {

		$template = $this->path("views/$view.php");

		// Make passed variables available
		if (is_array($vars)) {

			extract($vars); // phpcs:ignore

		} // end if;

		// Load our view
		include $template;

	} // end render;

} // end class Helper;
