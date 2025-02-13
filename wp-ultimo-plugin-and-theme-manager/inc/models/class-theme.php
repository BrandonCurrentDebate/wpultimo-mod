<?php
/**
 * The post model for Plugins.
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @subpackage Models
 * @since 2.0.0
 */

namespace WP_Ultimo_Plugin_And_Theme_Manager\Models; // phpcs:ignore

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Extension model class. Implements the Base Model.
 *
 * @since 2.0.0
 */
class Theme extends Extension {

	/**
	 * Query Class to the static query methods.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $query_class = '\\WP_Ultimo_Plugin_And_Theme_Manager\Models\\Theme_Query';

	/**
	 * The extension type. Can be plugin or theme.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $extension_type = 'theme';

	/**
	 * The original theme instance.
	 *
	 * @since 2.0.0
	 * @var \WP_Theme
	 */
	public $wp_theme;

	/**
	 * Loads the original data.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	protected function load_original_data() {

		$this->wp_theme = wp_get_theme($this->get_slug());

		if (!is_object($this->wp_theme)) {

			return array();

		} // end if;

		$reflector = new \ReflectionClass($this->wp_theme);

		$headers = $reflector->getProperty('headers');

		$headers->setAccessible(true);

		return $headers->getValue($this->wp_theme);

	} // end load_original_data;

} // end class Theme;
