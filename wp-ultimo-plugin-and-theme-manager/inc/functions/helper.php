<?php
/**
 * General helper functions for WP Ultimo: Plugin and Theme Manager.
 *
 * @author      Arindo Duque
 * @category    Admin
 * @package     WP_Ultimo_Plugin_And_Theme_Manager/Helper
 * @version     2.0.0
 */

/**
 * Returns the WP Ultimo: Plugin and Theme Manager version.
 *
 * @since 2.0.0
 * @return string
 */
function wp_ultimo_ptm_get_version() { // phpcs:ignore

	return WP_Ultimo_Plugin_And_Theme_Manager()->version;

} // end wp_ultimo_ptm_get_version;

/**
 * Turn a string into slug.
 *
 * @since 2.0.0
 *
 * @param string $string Asset Name or other string to turn into slug.
 * @return string Return the slug version of your string.
 */
function wp_ultimo_ptm_string_to_slug($string) { // phpcs:ignore

	return strtolower(str_replace('-', '', str_replace(' ', '', preg_replace('/[^a-zA-Z0-9_ -]/s', '', $string))));

} // end wp_ultimo_ptm_string_to_slug;

/**
 * Get the slug for the plugin from the filename
 *
 * @param  string $file Full Path.
 * @return string       Slug used by WordPress.
 */
function wp_ultimo_ptm_get_slug_from_file($file) {

	$pos = strpos($file, '/');

	if ($pos !== false) {

		$slug = strtolower(substr($file, 0, $pos));

	} else {

		$slug = $file;

	} // end if;

	return str_replace('.php', '', $slug);

} // end wp_ultimo_ptm_get_slug_from_file;

/**
 * Returns the list of legacy settings on 1.X.
 *
 * @since 2.0.0
 * @return array
 */
function wp_ultimo_ptm_get_old_settings() {

	global $wpdb;

	$settings = $wpdb->get_var(
		"
			SELECT meta_value
			FROM
				{$wpdb->base_prefix}sitemeta
			WHERE
				meta_key = 'wp-ultimo_settings' OR meta_key = 'wp-ultimo_v2_settings'
			LIMIT 1
		"
	);

	$settings = maybe_unserialize($settings);

	return $settings;

} // end wp_ultimo_ptm_get_old_settings;

/**
 * Returns the value of a particular legacy setting.
 *
 * @since 2.0.0
 *
 * @param string  $setting The setting key.
 * @param boolean $default Default value.
 * @return mixed
 */
function wp_ultimo_ptm_get_old_setting($setting, $default = false) {

	static $settings;

	if ($settings === null) {

		$settings = wp_ultimo_ptm_get_old_settings();

	} // end if;

	return wu_get_isset($settings, $setting, $default);

} // end wp_ultimo_ptm_get_old_setting;
