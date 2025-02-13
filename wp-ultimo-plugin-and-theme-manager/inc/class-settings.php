<?php
/**
 * WP Ultimo: Plugin and Theme Manager settings helper class.
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @subpackage Settings
 * @since 2.0.0
 */

namespace WP_Ultimo_Plugin_And_Theme_Manager;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * WP Ultimo settings helper class.
 *
 * @since 2.0.0
 */
class Settings {

	use \WP_Ultimo_Plugin_And_Theme_Manager\Traits\Singleton;

	/**
	 * Runs on singleton instantiation.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct() {

		add_action('admin_init', array($this, 'add_all_settings'));

	} // end __construct;

	/**
	 * Add all settings.
	 *
	 * @since 2.0.0
	 *
	 * @return void.
	 */
	public function add_all_settings() {

		wu_register_settings_section('plugin-and-theme-manager', array(
			'title' => __('Plugin & Theme Manager', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'  => __('Plugin & Theme Manager', 'wp-ultimo-plugin-and-theme-manager'),
			'icon'  => 'dashicons-wu-edit',
			'order' => 10,
			'addon' => true,
		));

		wu_register_settings_field('plugin-and-theme-manager', 'ptm_header', array(
			'title' => __('Plugin and Theme Manager', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'  => __('Define what type of events will be saved in your database, will be saved in the log, both or will not be saved.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'  => 'header',
		));

		$preview_image = sprintf(' <span class="wu-image-preview wu-text-gray-600 wu-bg-gray-200 wu-p-1 wu-px-2 wu-ml-1 wu-inline-block wu-text-2xs wu-uppercase wu-font-bold wu-rounded wu-cursor-pointer wu-border-gray-300 wu-border wu-border-solid" data-image="%s">%s %s</span>', WP_Ultimo_Plugin_And_Theme_Manager()->helper->get_asset('preview-plugin-page.png'), "<span class='dashicons-wu-image wu-align-middle wu-mr-1'></span>", __('Preview', 'wp-ultimo'));

		wu_register_settings_field('plugin-and-theme-manager', 'replace_plugin_page', array(
			'title'   => __('Replace the Plugin Page', 'wp-ultimo-plugin-and-theme-manager') . $preview_image,
			'desc'    => __('Check this option if you want to replace the default WordPress Plugin Page of your clients’ site with a custom theme-like page, including advanced filtering and display.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'toggle',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-replace-plugins-page', true),
		));

		wu_register_settings_field('plugin-and-theme-manager', 'display_type', array(
			'title'   => __('Display Type', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'    => __('The layout of the display page.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'select',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-display-type', 'plugin-card'),
			'options' => array(
				'plugin-card' => __('Plugin Style', 'wp-ultimo-plugin-and-theme-manager'),
				'theme'       => __('Theme Style', 'wp-ultimo-plugin-and-theme-manager'),
			)
		));

		wu_register_settings_field('plugin-and-theme-manager', 'apply_to_all_sites', array(
			'title'   => __('Apply Changes to All Sites', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'    => __('By default, we only display the changes made to the plugin and theme\'s metadata for Ultimo sites (sites owned by users with an WP Ultimo subscription). Use this option to apply the changes to all sites in the network.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'toggle',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-all-sites', false),
		));

		wu_register_settings_field('plugin-and-theme-manager', 'display_plugin_author', array(
			'title'   => __('Display Plugin\'s Author', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'    => __('Check this box if you want to display the plugin’s author info to your users.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'toggle',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-display-author', true),
		));

		wu_register_settings_field('plugin-and-theme-manager', 'display_plugin_version', array(
			'title'   => __('Display Plugin\'s Version', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'    => __('Check this box if you want to display the plugin’s version to your users.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'toggle',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-display-version', true),
		));

		wu_register_settings_field('plugin-and-theme-manager', 'display_plugin_details', array(
			'title'   => __('Display Plugin\'s Details', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'    => __('Check this box if you want to display the plugin’s details modal to your users. This option is only used if you do not use the custom plugin page.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'toggle',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-display-details', true),
		));

		wu_register_settings_field('plugin-and-theme-manager', 'display_plugin_extra', array(
			'title'   => __('Display Plugin\'s Extra Links', 'wp-ultimo-plugin-and-theme-manager'),
			'desc'    => __('Check this box if you want to display the plugin’s extra links to your users. This option is only used if you do not use the custom plugin page.', 'wp-ultimo-plugin-and-theme-manager'),
			'type'    => 'toggle',
			'default' => wp_ultimo_ptm_get_old_setting('wu-ptm-display-extra-links', true),
		));

	} // end add_all_settings;

} // end class Settings;
