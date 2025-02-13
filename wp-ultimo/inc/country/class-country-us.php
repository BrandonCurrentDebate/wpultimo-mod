<?php // phpcs:ignore - @generation-checksum US-66-19821
/**
 * Country Class for United States (US).
 *
 * State/province count: 66
 * City count: 19821
 * City count per state/province:
 * - TX: 1277 cities
 * - CA: 1123 cities
 * - PA: 1054 cities
 * - NY: 1054 cities
 * - IL: 855 cities
 * - FL: 845 cities
 * - OH: 756 cities
 * - NC: 554 cities
 * - NJ: 551 cities
 * - GA: 534 cities
 * - MI: 526 cities
 * - MO: 510 cities
 * - VA: 503 cities
 * - WI: 492 cities
 * - MN: 460 cities
 * - IN: 434 cities
 * - WA: 433 cities
 * - MA: 405 cities
 * - KY: 388 cities
 * - TN: 386 cities
 * - IA: 383 cities
 * - AL: 378 cities
 * - MD: 364 cities
 * - LA: 344 cities
 * - OK: 320 cities
 * - KS: 319 cities
 * - MS: 295 cities
 * - CO: 289 cities
 * - AR: 287 cities
 * - SC: 283 cities
 * - ME: 271 cities
 * - PR: 255 cities
 * - OR: 255 cities
 * - NE: 238 cities
 * - AZ: 227 cities
 * - WV: 217 cities
 * - UT: 207 cities
 * - CT: 191 cities
 * - NH: 186 cities
 * - NM: 182 cities
 * - SD: 167 cities
 * - MT: 161 cities
 * - ID: 142 cities
 * - ND: 133 cities
 * - HI: 121 cities
 * - VT: 96 cities
 * - AK: 95 cities
 * - NV: 82 cities
 * - WY: 80 cities
 * - RI: 54 cities
 * - DE: 54 cities
 * - DC: 5 cities
 *
 * @package WP_Ultimo\Country
 * @since 2.0.11
 */

namespace WP_Ultimo\Country;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Country Class for United States (US).
 *
 * IMPORTANT:
 * This file is generated by build scripts, do not
 * change it directly or your changes will be LOST!
 *
 * @since 2.0.11
 *
 * @property-read string $code
 * @property-read string $currency
 * @property-read int $phone_code
 */
class Country_US extends Country {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * General country attributes.
	 *
	 * This might be useful, might be not.
	 * In case of doubt, keep it.
	 *
	 * @since 2.0.11
	 * @var array
	 */
	protected $attributes = array(
		'country_code' => 'US',
		'currency'     => 'USD',
		'phone_code'   => 1,
	);

	/**
	 * The type of nomenclature used to refer to the country sub-divisions.
	 *
	 * @since 2.0.11
	 * @var string
	 */
	protected $state_type = 'state';

	/**
	 * Return the country name.
	 *
	 * @since 2.0.11
	 * @return string
	 */
	public function get_name() {

		return __('United States', 'wp-ultimo-locations');

	} // end get_name;

	/**
	 * Returns the list of states for US.
	 *
	 * @since 2.0.11
	 * @return array The list of state/provinces for the country.
	 */
	protected function states() {

		return array(
			'AL'    => __('Alabama', 'wp-ultimo-locations'),
			'AK'    => __('Alaska', 'wp-ultimo-locations'),
			'AS'    => __('American Samoa', 'wp-ultimo-locations'),
			'AZ'    => __('Arizona', 'wp-ultimo-locations'),
			'AR'    => __('Arkansas', 'wp-ultimo-locations'),
			'UM-81' => __('Baker Island', 'wp-ultimo-locations'),
			'CA'    => __('California', 'wp-ultimo-locations'),
			'CO'    => __('Colorado', 'wp-ultimo-locations'),
			'CT'    => __('Connecticut', 'wp-ultimo-locations'),
			'DE'    => __('Delaware', 'wp-ultimo-locations'),
			'DC'    => __('District of Columbia', 'wp-ultimo-locations'),
			'FL'    => __('Florida', 'wp-ultimo-locations'),
			'GA'    => __('Georgia', 'wp-ultimo-locations'),
			'GU'    => __('Guam', 'wp-ultimo-locations'),
			'HI'    => __('Hawaii', 'wp-ultimo-locations'),
			'UM-84' => __('Howland Island', 'wp-ultimo-locations'),
			'ID'    => __('Idaho', 'wp-ultimo-locations'),
			'IL'    => __('Illinois', 'wp-ultimo-locations'),
			'IN'    => __('Indiana', 'wp-ultimo-locations'),
			'IA'    => __('Iowa', 'wp-ultimo-locations'),
			'UM-86' => __('Jarvis Island', 'wp-ultimo-locations'),
			'UM-67' => __('Johnston Atoll', 'wp-ultimo-locations'),
			'KS'    => __('Kansas', 'wp-ultimo-locations'),
			'KY'    => __('Kentucky', 'wp-ultimo-locations'),
			'UM-89' => __('Kingman Reef', 'wp-ultimo-locations'),
			'LA'    => __('Louisiana', 'wp-ultimo-locations'),
			'ME'    => __('Maine', 'wp-ultimo-locations'),
			'MD'    => __('Maryland', 'wp-ultimo-locations'),
			'MA'    => __('Massachusetts', 'wp-ultimo-locations'),
			'MI'    => __('Michigan', 'wp-ultimo-locations'),
			'UM-71' => __('Midway Atoll', 'wp-ultimo-locations'),
			'MN'    => __('Minnesota', 'wp-ultimo-locations'),
			'MS'    => __('Mississippi', 'wp-ultimo-locations'),
			'MO'    => __('Missouri', 'wp-ultimo-locations'),
			'MT'    => __('Montana', 'wp-ultimo-locations'),
			'UM-76' => __('Navassa Island', 'wp-ultimo-locations'),
			'NE'    => __('Nebraska', 'wp-ultimo-locations'),
			'NV'    => __('Nevada', 'wp-ultimo-locations'),
			'NH'    => __('New Hampshire', 'wp-ultimo-locations'),
			'NJ'    => __('New Jersey', 'wp-ultimo-locations'),
			'NM'    => __('New Mexico', 'wp-ultimo-locations'),
			'NY'    => __('New York', 'wp-ultimo-locations'),
			'NC'    => __('North Carolina', 'wp-ultimo-locations'),
			'ND'    => __('North Dakota', 'wp-ultimo-locations'),
			'MP'    => __('Northern Mariana Islands', 'wp-ultimo-locations'),
			'OH'    => __('Ohio', 'wp-ultimo-locations'),
			'OK'    => __('Oklahoma', 'wp-ultimo-locations'),
			'OR'    => __('Oregon', 'wp-ultimo-locations'),
			'UM-95' => __('Palmyra Atoll', 'wp-ultimo-locations'),
			'PA'    => __('Pennsylvania', 'wp-ultimo-locations'),
			'PR'    => __('Puerto Rico', 'wp-ultimo-locations'),
			'RI'    => __('Rhode Island', 'wp-ultimo-locations'),
			'SC'    => __('South Carolina', 'wp-ultimo-locations'),
			'SD'    => __('South Dakota', 'wp-ultimo-locations'),
			'TN'    => __('Tennessee', 'wp-ultimo-locations'),
			'TX'    => __('Texas', 'wp-ultimo-locations'),
			'UM'    => __('United States Minor Outlying Islands', 'wp-ultimo-locations'),
			'VI'    => __('United States Virgin Islands', 'wp-ultimo-locations'),
			'UT'    => __('Utah', 'wp-ultimo-locations'),
			'VT'    => __('Vermont', 'wp-ultimo-locations'),
			'VA'    => __('Virginia', 'wp-ultimo-locations'),
			'UM-79' => __('Wake Island', 'wp-ultimo-locations'),
			'WA'    => __('Washington', 'wp-ultimo-locations'),
			'WV'    => __('West Virginia', 'wp-ultimo-locations'),
			'WI'    => __('Wisconsin', 'wp-ultimo-locations'),
			'WY'    => __('Wyoming', 'wp-ultimo-locations'),
		);

	} // end states;

} // end class Country_US;
