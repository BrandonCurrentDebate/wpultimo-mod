<?php
/**
 * This file adds a class to manage the addition of WooCommerce's shortcuts menu to
 * WP Ultimo's top admin navigation menu shortcuts.
 *
 * @category Core
 * @package WP_Ultimo_WooCommerce
 * @author Gustavo Modesto <gustavo@wpultimo.com>
 * @since 2.0.0
 */

namespace WP_Ultimo_WooCommerce\Managers;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * This class adds WooCommerce's shortcuts to WP Ultimo's top admin navigation menu.
 *
 * @since 2.0.0
 */
class Top_Menu_Manager {

	/**
	 * Adds the hooks and actions
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct() {

		add_action('admin_bar_menu', array($this, 'add_top_bar_menus'), 50);

	}  // end __construct;

	/**
	 * Adds WooCommerce's shortcuts to WP Ultimo's top admin navigation shortcuts menu.
	 *
	 * @since 2.0.0
	 * @param \WP_Admin_Bar $wp_admin_bar Admin bar object from WordPress/ToolBar.
	 * @return void
	 */
	public function add_top_bar_menus($wp_admin_bar) {

    // Only for super admins
		if (!current_user_can('manage_network')) {

			return;

		} // end if;

    // Settings
		$woocommerce = array(
			'id'     => 'wp-ultimo-woocommerce',
			'parent' => 'wp-ultimo-settings-group',
			'title'  => __('WooCommerce', 'wp-ultimo-woocommerce'),
			'href'   => '#',
			'meta'   => array(
				'class' => 'wp-ultimo-top-menu ab-sub-secondary',
				'title' => __('WooCommerce settings', 'wp-ultimo-woocommerce'),
			)
		);

		$wp_admin_bar->add_node($woocommerce);

		$submenu_tabs = array (
			'wc-admin'          => __('Home', 'wp-ultimo-woocommerce'),
			'shop_order'        => __('Orders', 'wp-ultimo-woocommerce'),
			'shop_subscription' => __('Subscriptions', 'wp-ultimo-woocommerce'),
			'product'           => __('Products', 'wp-ultimo-woocommerce'),
			'wc-settings'       => __('Settings', 'wp-ultimo-woocommerce')
		);

		foreach ($submenu_tabs as $submenu => $submenu_info) {

			if ($submenu === 'shop_subscription' && !wuc_is_woocommerce_subscriptions_active()) {

				continue;

			} // end if;

			$submenu_tabs = array(
				'id'     => 'wp-ultimo-settings-' . $submenu,
				'parent' => 'wp-ultimo-woocommerce',
				'title'  => $submenu_info,
				'href'   => admin_url('edit.php?post_type=') . $submenu,
				'meta'   => array(
					// translators: %s is the page name.
					'title' => sprintf(__('Go to the %s page', 'wp-ultimo-woocommerce'), strtolower($submenu_info)),
					'class' => 'wp-ultimo-top-menu',
				)
			);

			if ($submenu === 'wc-admin' || $submenu === 'wc-settings') {

				$submenu_tabs['href'] = admin_url('admin.php?page=') . $submenu;

			} // end if;

			$wp_admin_bar->add_node($submenu_tabs);

		} // end foreach;

	} // end add_top_bar_menus;

} // end class Top_Menu_Manager;
