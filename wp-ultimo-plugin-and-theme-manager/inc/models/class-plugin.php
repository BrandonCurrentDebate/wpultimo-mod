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
class Plugin extends Extension {

	/**
	 * Query Class to the static query methods.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $query_class = '\\WP_Ultimo_Plugin_And_Theme_Manager\Models\\Plugin_Query';

	/**
	 * The extension type. Can be plugin or theme.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $extension_type = 'plugin';

	/**
	 * Loads the original data.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	protected function load_original_data() {

		if (!$this->get_slug()) {

			return array();

		} // end if;

		$plugin_path = WP_PLUGIN_DIR . '/' . $this->get_slug();

		if (!file_exists($plugin_path)) {

			return array();

		} // end if;

		return get_plugin_data($plugin_path);

	} // end load_original_data;

} // end class Plugin;
