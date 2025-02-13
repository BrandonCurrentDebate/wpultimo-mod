<?php
/**
 * Helper functions for the WooCommerce gateways
 *
 * @author      Arindo Duque
 * @category    Admin
 * @package     WP_Ultimo_WooCommerce
 * @version     2.0.0
 */

/**
 * Loads the WooCommerce Dependencies.
 *
 * @since 2.0.0
 * @return void
 */
function wuc_load_dependencies() {

	$plugins_dir = dirname(WP_ULTIMO_WOOCOMMERCE_PLUGIN_DIR);

	if (!class_exists('WooCommerce')) {

		require_once $plugins_dir . '/woocommerce/woocommerce.php';

		WC()->init();

		WC_Post_types::register_taxonomies();
		WC_Post_types::register_post_types();

		WC()->countries = new WC_Countries();

	} // end if;

} // end wuc_load_dependencies;

/**
 * Loads the WooCommerce Subscriptions Dependencies.
 *
 * @since 2.0.0
 * @return void
 */
function wuc_load_wc_subscriptions_dependencies() {

	$plugins_dir = dirname(WP_ULTIMO_WOOCOMMERCE_PLUGIN_DIR);

	$file_name = $plugins_dir . '/woocommerce-subscriptions/woocommerce-subscriptions.php';

	if (file_exists($file_name)) {

		require_once $file_name;

		if (class_exists('WC_Subscriptions')) {

			WC_Subscriptions::load_dependant_classes();

			WC_Subscriptions::attach_dependant_hooks();

		} // end if;

	} // end if;

} // end wuc_load_wc_subscriptions_dependencies;

/**
 * Checks wether or not we have WooCommerce Subscriptions activated on the site
 *
 * @since 1.2.0
 * @return boolean
 */
function wuc_is_woocommerce_subscriptions_active() {

	$active_plugins = apply_filters('active_plugins', get_blog_option(get_current_site()->blog_id, 'active_plugins', array())); // phpcs:ignore;

	return class_exists('\WC_Subscriptions') || in_array('woocommerce-subscriptions/woocommerce-subscriptions.php', $active_plugins, true);

} // end wuc_is_woocommerce_subscriptions_active;
