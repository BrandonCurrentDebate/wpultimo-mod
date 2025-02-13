<?php
/**
 * Set additional WP Ultimo - WooCommerce Gateways plugin constants.
 *
 * @package WP_Ultimo_WooCommerce
 * @since 2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

// Plugin Folder Path
if (!defined('WP_ULTIMO_WOOCOMMERCE_PLUGIN_DIR')) {

	define('WP_ULTIMO_WOOCOMMERCE_PLUGIN_DIR', plugin_dir_path(WP_ULTIMO_WOOCOMMERCE_PLUGIN_FILE));

} // end if;

// Plugin Folder URL
if (!defined('WP_ULTIMO_WOOCOMMERCE_PLUGIN_URL')) {

	define('WP_ULTIMO_WOOCOMMERCE_PLUGIN_URL', plugin_dir_url(WP_ULTIMO_WOOCOMMERCE_PLUGIN_FILE));

} // end if;

// Plugin Root File
if (!defined('WP_ULTIMO_WOOCOMMERCE_PLUGIN_BASENAME')) {

	define('WP_ULTIMO_WOOCOMMERCE_PLUGIN_BASENAME', plugin_basename(WP_ULTIMO_WOOCOMMERCE_PLUGIN_FILE));

} // end if;
