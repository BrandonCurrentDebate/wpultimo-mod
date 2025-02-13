<?php
/**
 * Plugin Name: WP Ultimo: Captcha
 * Description: Prevent bots from creating spam sites on your network using Google reCaptcha or hCaptcha.
 * Plugin URI: https://wpultimo.com/addons
 * Text Domain: wp-ultimo-captcha
 * Version: 1.0.0-beta.2
 * Author: NextPress
 * Author URI: http://nextpress.co/
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 *
 * WP Ultimo Captcha is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Ultimo Captcha is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Ultimo Captcha. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque and NextPress
 * @category Core
 * @package  WP_Ultimo_Captcha
 * @version  1.0.0-beta.2
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!defined('WP_ULTIMO_CAPTCHA_PLUGIN_FILE')) {

	define('WP_ULTIMO_CAPTCHA_PLUGIN_FILE', __FILE__);

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
WP_Ultimo_Captcha\Autoloader::init();

/**
 * Setup activation/deactivation hooks
 */
WP_Ultimo_Captcha\Hooks::init();

/**
 * Initializes the WP Ultimo Captcha class
 *
 * This function returns the WP_Ultimo_Captcha class singleton, and
 * should be used to avoid declaring globals.
 *
 * @since 1.0.0
 * @return WP_Ultimo_Captcha
 */
function WP_Ultimo_Captcha() { // phpcs:ignore

	return WP_Ultimo_Captcha::get_instance();

} // end WP_Ultimo_Captcha;

// Initialize and set to global for back-compat
add_action('plugins_loaded', 'wp_ultimo_captcha_init');

/**
 * Wait before we have WP Ultimo available before hooking into it.
 *
 * @since 1.0.0
 * @return void
 */
function wp_ultimo_captcha_init() {

	$GLOBALS['WP_Ultimo_Captcha'] = WP_Ultimo_Captcha();

} // end wp_ultimo_captcha_init;
