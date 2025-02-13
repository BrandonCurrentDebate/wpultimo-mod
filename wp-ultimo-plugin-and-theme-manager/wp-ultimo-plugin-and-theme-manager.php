<?php
/**
 * Plugin Name: WP Ultimo: Plugin & Theme Manager
 * Description: Edit or hide the meta information (title, author, thumbnail and description) of installed plugins and themes. Create categories for plugins and themes to allow your users to filter them and add a beautiful custom Plugins page to your users' panel.
 * Plugin URI: http://wpultimo.com/addons
 * Text Domain: wp-ultimo-plugin-and-theme-manager
 * Version: 2.0.0-beta.4
 * Author: NextPress
 * Author URI: http://nextpress.co/
 * Network: true
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang
 *
 * WP Ultimo Plugin And Theme Manager is distributed under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Ultimo Plugin And Theme Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Ultimo Plugin And Theme Manager. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author   Arindo Duque and NextPress
 * @category Core
 * @package  WP_Ultimo_Plugin_And_Theme_Manager
 * @version  2.0.0-beta.4
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!defined('WP_ULTIMO_PLUGIN_AND_THEME_MANAGER_PLUGIN_FILE')) {

	define('WP_ULTIMO_PLUGIN_AND_THEME_MANAGER_PLUGIN_FILE', __FILE__);

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
WP_Ultimo_Plugin_And_Theme_Manager\Autoloader::init();

/**
 * Setup activation/deactivation hooks
 */
WP_Ultimo_Plugin_And_Theme_Manager\Hooks::init();

/**
 * Initializes the WP Ultimo Plugin And Theme Manager class
 *
 * This function returns the WP_Ultimo_Plugin_And_Theme_Manager class singleton, and
 * should be used to avoid declaring globals.
 *
 * @since 1.0.0
 * @return WP_Ultimo_Plugin_And_Theme_Manager
 */
function WP_Ultimo_Plugin_And_Theme_Manager() { // phpcs:ignore

	return WP_Ultimo_Plugin_And_Theme_Manager::get_instance();

} // end WP_Ultimo_Plugin_And_Theme_Manager;

// Initialize and set to global for back-compat
add_action('plugins_loaded', 'wp_ultimo_plugin_and_theme_manager_init');

/**
 * Wait before we have WP Ultimo available before hooking into it.
 *
 * @since 1.0.0
 * @return void
 */
function wp_ultimo_plugin_and_theme_manager_init() {

	$GLOBALS['WP_Ultimo_Plugin_And_Theme_Manager'] = WP_Ultimo_Plugin_And_Theme_Manager();

} // end wp_ultimo_plugin_and_theme_manager_init;
