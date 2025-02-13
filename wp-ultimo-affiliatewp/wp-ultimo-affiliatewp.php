<?php
/**
 * Plugin Name: WP Ultimo: AffiliateWP Integration
 * Description: Use the powerful AffiliateWP to grow the client base of your Ultimo Network!
 * Plugin URI: https://wpultimo.com/addons
 * Text Domain: wp-ultimo-affiliatewp
 * Version: 2.0.0
 * Author: NextPress
 * Author URI: http://nextpress.co/
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 *
 * WP Ultimo: AffiliateWP Integration is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Ultimo: AffiliateWP Integration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Ultimo: AffiliateWP Integration. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque and NextPress
 * @category Core
 * @package  WP_Ultimo_AffiliateWP
 * @version  2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!defined('WP_ULTIMO_AFFILIATEWP_PLUGIN_FILE')) {

	define('WP_ULTIMO_AFFILIATEWP_PLUGIN_FILE', __FILE__);

} // end if;

/**
 * Require core file dependencies
 */
require_once __DIR__ . '/constants.php';

require_once __DIR__ . '/dependencies/autoload.php';

require_once __DIR__ . '/inc/class-autoloader.php';

require_once __DIR__ . '/inc/traits/trait-singleton.php';

/**
 * Setup autoloader
 */
WP_Ultimo_AffiliateWP\Autoloader::init();

/**
 * Setup activation/deactivation hooks
 */
WP_Ultimo_AffiliateWP\Hooks::init();

/**
 * Initializes the WP Ultimo: AffiliateWP Integration class
 *
 * This function returns the WP_Ultimo_AffiliateWP class singleton, and
 * should be used to avoid declaring globals.
 *
 * @since 1.0.0
 * @return WP_Ultimo_AffiliateWP
 */
function WP_Ultimo_AffiliateWP() { // phpcs:ignore

	if (!class_exists('WP_Ultimo')) {

		return;

	} // end if;

	if (class_exists('Affiliate_WP')) {

		return WP_Ultimo_AffiliateWP::get_instance();

	} else {

		WP_Ultimo()->notices->add(__('WP Ultimo: AffiliateWP Integration requires <a target="_blank" href="https://affiliatewp.com">AffiliateWP</a> to be activated at least on your main site.', 'wp-ultimo-affiliatewp'), 'warning', 'network-admin');

	} // end if;

} // end WP_Ultimo_AffiliateWP;

// Initialize and set to global for back-compat
add_filter('affwp_extended_integrations', 'wu_add_wpultimo_to_integrations', 10);
add_action('plugins_loaded', 'wp_ultimo_affiliatewp_init');

/**
 * Wait before we have WP Ultimo available before hooking into it.
 *
 * @since 1.0.0
 * @return void
 */
function wp_ultimo_affiliatewp_init() { // phpcs:ignore;

	$GLOBALS['WP_Ultimo_AffiliateWP'] = WP_Ultimo_AffiliateWP();

} // end wp_ultimo_affiliatewp_init;

/**
 * Add the integration option of WP Ultimo to AffiliateWP List.
 *
 * @param array $integrations Integration parameters from AffiliateWP.
 */
function wu_add_wpultimo_to_integrations($integrations) {

	/**
	 * Integration parameters from AffiliateWP.
	 *
	 * @type string $name    Required. The integration display name.
	 * @type string $class   Required. The integration class name.
	 * @type string $file    Required. The path to the file that contains this integration class.
	 * @type bool   $enabled Optional. True forces this integration to always be enabled.
	 *                           False forces it to always be disabled. Defaults to user settings.
	 * @type array $supports Optional. List of features this integration supports. Default empty array.
	*/
	$integrations['wp-ultimo'] = array(
		'name'   => 'WP Ultimo',
		'class'  => plugin_dir_path(__FILE__) . '/inc/class-wp-ultimo-affiliatewp.php',
		'status' => array('enabled', 'disabled'),
		'fields' => 'WP_Ultimo_AffiliateWP'
	);

	return $integrations;

} // end wu_add_wpultimo_to_integrations;
