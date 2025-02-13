<?php
/**
 * Plugin Name: WP Ultimo: WooCommerce Integration
 * Description: Accept payments using any of the hundreds of payment gateways available for WooCommerce and WooCommerce Subscriptions.
 * Plugin URI: https://wpultimo.com
 * Text Domain: wp-ultimo-woocommerce
 * Version: 2.0.0
 * Author: NextPress
 * Author URI: http://nextpress.co/
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 *
 * WP Ultimo - WooCommerce Gateways is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Ultimo - WooCommerce Gateways is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Ultimo - WooCommerce Gateways. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque and NextPress
 * @category Core
 * @package  WP_Ultimo_WooCommerce
 * @version  2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!defined('WP_ULTIMO_WOOCOMMERCE_PLUGIN_FILE')) {

	define('WP_ULTIMO_WOOCOMMERCE_PLUGIN_FILE', __FILE__);

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
WP_Ultimo_WooCommerce\Autoloader::init();

/**
 * Setup activation/deactivation hooks
 */
WP_Ultimo_WooCommerce\Hooks::init();

/**
 * Initializes the WP Ultimo - WooCommerce Gateways class
 *
 * This function returns the WP_Ultimo_WooCommerce class singleton, and
 * should be used to avoid declaring globals.
 *
 * @since 2.0.0
 * @return WP_Ultimo_WooCommerce
 */
function WP_Ultimo_WooCommerce() { // phpcs:ignore

	return WP_Ultimo_WooCommerce::get_instance();

} // end WP_Ultimo_WooCommerce;

// Initialize and set to global for back-compat
add_action('plugins_loaded', 'wp_ultimo_woocommerce_init');

/**
 * Wait before we have WP Ultimo available before hooking into it.
 *
 * @since 2.0.0
 * @return void
 */
function wp_ultimo_woocommerce_init() {

	$GLOBALS['WP_Ultimo_WooCommerce'] = WP_Ultimo_WooCommerce();

} // end wp_ultimo_woocommerce_init;
