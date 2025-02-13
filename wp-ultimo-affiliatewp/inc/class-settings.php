<?php
/**
 * WP Ultimo: AffiliateWP Integration settings class.
 *
 * @package WP_Ultimo_AffiliateWP
 * @since 2.0.0
 */

namespace WP_Ultimo_AffiliateWP;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * This class merges our settings to AffiliateWP settings page
 *
 * @since 2.0.0
 */
class Settings {

	/**
	 * Filter settings affiliate integrations adding new option
	 *
	 * @param array $array Settings.
	 *
	 * @return array
	 */
	static function add_wp_ultimo_on_settings($array) {

		$settings_wp = array(
			'wp_ultimo_setup_fee_affwp' => array(
				'name'        => __('WP Ultimo Integration Options', 'wp-ultimo'),
				'title'       => __('Allow commissions to be applied to the Setup Fees', 'wp-ultimo'),
				'desc'        => __('Allow commissions to be applied to the Setup Fees', 'wp-ultimo'),
				'tooltip'     => __('If you enable this option, WP Ultimo commissions will include the Setup Fees as well.', 'wp-ultimo'),
				'type'        => 'checkbox',
				'placeholder' => '',
				'default'     => 0,
			)
		);

		return array_merge($array, $settings_wp);

	} // end add_wp_ultimo_on_settings;

} // end class Settings;
