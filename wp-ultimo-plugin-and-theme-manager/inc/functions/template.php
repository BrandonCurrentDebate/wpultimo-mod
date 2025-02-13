<?php
/**
 * Helper Functions
 *
 * We will create some helper functions just to make the whole rendering syntax more similar to
 * existing WordPress Plugins, like WooCommerce and etc.
 *
 * @author      Arindo Duque
 * @category    Admin
 * @package     WP_Ultimo_Plugin_And_Theme_Manager/Helper
 * @version     2.0.0
 */

/**
 * Alias function to be used on the templates
 *
 * @param  string $view Template to be get.
 * @param  array  $args Arguments to be parsed and made available inside the template file.
 * @return void
 */
function wp_ultimo_ptm_get_template($view, $args = array()) { // phpcs:ignore

	WP_Ultimo_Plugin_And_Theme_Manager()->helper->render($view, $args);

} // end wp_ultimo_ptm_get_template;

/**
 * Alias function to be used on the templates;
 * Rather than directly including the template, it returns the contents inside a variable
 *
 * @param  string $view Template to be get.
 * @param  array  $args Arguments to be parsed and made available inside the template file.
 * @return string
 */
function wp_ultimo_ptm_get_template_contents($view, $args = array()) { // phpcs:ignore

	ob_start();

	WP_Ultimo_Plugin_And_Theme_Manager()->helper->render($view, $args);

	return ob_get_clean();

} // end wp_ultimo_ptm_get_template_contents;
