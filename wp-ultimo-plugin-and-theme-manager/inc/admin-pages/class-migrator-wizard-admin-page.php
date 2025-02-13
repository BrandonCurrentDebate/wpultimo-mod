<?php
/**
 * Plugin and Theme Manager Wizard Admin Page.
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @subpackage Wizard_Admin_Page
 * @since 2.0.0
 */

namespace WP_Ultimo_Plugin_And_Theme_Manager\Admin_Pages; // phpcs:ignore

use WP_Ultimo_Plugin_And_Theme_Manager\Models\Plugin_And_Theme; // phpcs:ignore

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Plugin and Theme Manager Dashboard Admin Page.
 */
class Migrator_Wizard_Admin_Page extends \WP_Ultimo\Admin_Pages\Wizard_Admin_Page {

	/**
	 * Holds the ID for this page, this is also used as the page slug.
	 *
	 * @var string
	 */
	protected $id = 'ptm-migrator-wizard';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $type = 'submenu';

	/**
	 * Is this a top-level menu or a submenu?
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $parent = 'index.php';

	/**
	 * If this number is greater than 0, a badge with the number will be displayed alongside the menu title
	 *
	 * @since 2.0.0
	 * @var integer
	 */
	protected $badge_count = 0;

	/**
	 * Holds the admin panels where this page should be displayed, as well as which capability to require.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $supported_panels = array(
		'network_admin_menu' => 'manage_network',
	);

	/**
	 * Allow child classes to add further initializations.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function page_loaded() {

		parent::page_loaded();

	} // end page_loaded;

	/**
	 * Returns the title of the page.
	 *
	 * @since 2.0.0
	 * @return string Title of the page.
	 */
	public function get_title() {

		return sprintf(__('Migrator', 'wp-ultimo-plugin-and-theme-manager'));

	} // end get_title;

	/**
	 * Returns the title of menu for this page.
	 *
	 * @since 2.0.0
	 * @return string Menu label of the page.
	 */
	public function get_menu_title() {

		return __('PTM Migrator', 'wp-ultimo-plugin-and-theme-manager');

	} // end get_menu_title;

	/**
	 * Returns the sections for this Wizard.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_sections() {

		if (get_network_option(null, 'wu_ptm_migration')) {

			$sections = array(
				'migrated' => array(
					'title' => __('Migrated', 'wp-ultimo-plugin-and-theme-manager'),
					'view'  => array($this, 'section_migrated'),
				),
			);

		} else {

			$sections = array(
				'start' => array(
					'title'   => __('Start', 'wp-ultimo-plugin-and-theme-manager'),
					'view'    => array($this, 'section_start'),
					'handler' => array($this, 'handle_start'),
				),
				'done' => array(
					'title' => __('Done', 'wp-ultimo-plugin-and-theme-manager'),
					'view'  => array($this, 'section_done'),
				),
			);

		} // end if;

		return $sections;

	} // end get_sections;

	/**
	 * Displays the content of the start section.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function section_start() {

		$explainer_lines = array(
			'will'     => array(
				'transfer' => sprintf(__('Transfer or your data to the new saving scheme.', 'wp-ultimo-plugin-and-theme-manager'), $this->get_title()),
				'keep'     => sprintf(__('Keep all your previously configuration', 'wp-ultimo-plugin-and-theme-manager'), $this->get_title()),
				'remove'   => sprintf(__('Remove the old data.', 'wp-ultimo-plugin-and-theme-manager'), $this->get_title()),
			),
		);

		wp_ultimo_ptm_get_template('wizards/start', array(
			'screen'      => get_current_screen(),
			'page'        => $this,
			'title'       => __('Plugin and Theme Manager', 'wp-ultimo-plugin-and-theme-manager'),
			'description' => __('This plugin will help you migrate the content from the old Plugin and Theme Manager version to the new version', 'wp-ultimo-plugin-and-theme-manager'),
			'will'        => $explainer_lines['will'],
		));

	} // end section_start;

	/**
	 * Displays the content of the end section.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function section_done() {

		update_network_option(null, 'wu_ptm_migration', true);

		wp_ultimo_ptm_get_template('wizards/done', array(
			'screen'      => get_current_screen(),
			'page'        => $this,
			'title'       => __('Migration Completed!', 'wp-ultimo-plugin-and-theme-manager'),
			'description' => __('You can now disable this migrator and the Plugin and Theme Manager.', 'wp-ultimo-plugin-and-theme-manager'),
		));

	} // end section_done;

	/**
	 * Handles the all the migration process.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle_start() {
		/**
		 * Get old data.
		 */
		$args = array(
			'post_type'    => 'wu_extension',
			'post_status'  => 'publish',
		);

		$old_data = new \WP_Query( $args );

		if (!$old_data) {

			wp_redirect(admin_url('?page=ptm-migrator-wizard&step=done&notfound=true'));

		} // end if;

		$all_categories = $this->get_all_old_categories();

		if (isset($all_categories['plugin'])) {

			update_network_option(null, 'ptm_categories_plugin', $all_categories['plugin']);

		} // end if;

		if (isset($all_categories['theme'])) {

			update_network_option(null, 'ptm_categories_theme', $all_categories['theme']);

		} // end if;

		foreach ($old_data->posts as $key => $data) {

			$type = get_post_meta($data->ID, 'wpu_type')[0];

			if ($type) {

				if ($type == 'plugin') {

					$slug = wp_ultimo_ptm_string_to_slug($data->post_name);

					$object = new Plugin_And_Theme();

				} // end if;

				if ($type == 'theme') {

					$original_theme = wp_get_theme($data->post_name);

					$theme_headers = $this->get_headers($original_theme);

					$slug = wp_ultimo_ptm_string_to_slug($theme_headers['Name']);

					$object = new Plugin_And_Theme();

				} // end if;

			} // end if;

			$object->set_title($data->post_title);

			$object->set_content($data->post_content);

			$object->set_status('publish');

			$object->set_slug($slug);

			if ($object->save()) {

				$display_author     = get_post_meta($data->ID, 'wpu_display_author')[0];
				$display_version    = get_post_meta($data->ID, 'wpu_display_version')[0];
				$display_details    = get_post_meta($data->ID, 'wpu_display_details')[0];
				$wpu_display_other  = get_post_meta($data->ID, 'wpu_display_other')[0];

				$settings = array(
					'display_author'    => isset($display_author) ? 'on' : '',
					'display_version'   => isset($display_version) ? 'on' : '',
					'display_details'   => isset($display_details) ? 'on' : '',
					'display_other'     => isset($wpu_display_other) ? 'on' : '',
				);

				$object->update_meta('display_settings', $settings);

				$object->update_meta('asset_slug', $data->post_name);

				$object->update_meta('extension_type', $type);

				switch_to_blog(1);

				$taxonomy = array('wu_extension_category_' . $type);

				$object_categories = wp_get_post_terms($data->ID, $taxonomy, array('fields' => 'names'));

				restore_current_blog();

				if (isset($object_categories)) {

					$object->update_meta('category', $object_categories);

				} // end if;

				if ($type == 'plugin') {

					$asset_file = get_post_meta($data->ID, 'wpu_plugin_file');

					if ($asset_file) {

						$object->update_meta('asset_file', $asset_file[0]);

					} // end if;

				} // end if;

				$author = get_post_meta($data->ID, 'wpu_author');

				if ($author) {

					$object->update_meta('author', $author[0]);

				} // end if;

				$thumbnail = get_post_meta($data->ID, 'wpu_thumbnail');

				if ($thumbnail) {

					$object->update_meta('thumbnail', $thumbnail[0]);

				} // end if;

			} // end if;

		} // end foreach;

		wp_redirect(network_admin_url('?page=ptm-migrator-wizard&step=done'));

		exit;

	} // end handle_start;

	/**
	 * Get all PTM categories in a array based on the asset type.
	 *
	 * @since 2.0.0
	 *
	 * @return array.
	 */
	public function get_all_old_categories() {

		$types = array('plugin', 'theme');

		$all_categories = array();

		foreach ($types as $type) {

			$taxonomy = 'wu_extension_category_' . $type;

			switch_to_blog(1);

			$taxonomies = array($taxonomy);

			$check_later = array();

			global $wp_taxonomies;

			foreach ($taxonomies as $taxonomy) {

				if (isset($wp_taxonomies[$taxonomy])) {

					$check_later[$taxonomy] = false;

				} else {

					$wp_taxonomies[$taxonomy] = (object) array(
						'hierarchical' => false
					);

					$check_later[$taxonomy] = true;

				} // end if;

			} // end foreach;

			$categories = get_terms($taxonomies, array('hide_empty' => 0));

			if ($categories) {

				foreach ($categories as $category_key => $category) {

					if ($category) {

						$all_categories[$type][$category->slug] = array(
							'name'  => $category->name,
							'count' => $category->count
						);

					} // end if;

				} // end foreach;

			} // end if;

		} // end foreach;

		return $all_categories;

	} // end get_all_old_categories;

	/**
	 * Get the headers so we can change its value
	 *
	 * @since 2.0.0
	 *
	 * @param object $asset Theme Object.
	 * @return array With the asset headers.
	 */
	public function get_headers($asset) {

		if (!is_object($asset)) {

			return $asset;

		} // end if;

		$reflector = new \ReflectionClass($asset);

		$headers = $reflector->getProperty('headers');

		$headers->setAccessible(true);

		return $headers->getValue($asset);

	} // end get_headers;

	/**
	 * Removes the data form the plugin's older version.
	 *
	 * @since 2.0.0
	 * @return void.
	 */
	public function wp_ultimo_ptm_remove_old_data() {
		/**
		 * Get old data.
		 */
		$args = array(
			'post_type'     => 'wu_extension',
			'post_status'   => 'publish',
		);

		$old_data = new \WP_Query( $args );

		foreach ($old_data->posts as $key => $post) {

			wp_delete_post($post->ID);

		} // end foreach;

	} // end wp_ultimo_ptm_remove_old_data;

} // end class Migrator_Wizard_Admin_Page;
