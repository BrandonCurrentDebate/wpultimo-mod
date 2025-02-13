<?php
/**
 * Plugin and Theme Manager
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @subpackage Managers/Plugin_And_Theme_Manager
 * @since 2.0.0
 */

namespace WP_Ultimo_Plugin_And_Theme_Manager\Managers; // phpcs:ignore

use WP_Ultimo_Plugin_And_Theme_Manager\Models\Extension;
use WP_Ultimo_Plugin_And_Theme_Manager\Models\Plugin;
use WP_Ultimo_Plugin_And_Theme_Manager\Models\Theme;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Handles processes related to managing plugin and themes.
 *
 * @since 2.0.0
 */
class Plugin_And_Theme_Manager {

	use \WP_Ultimo\Traits\Singleton;

	/**
	 * Instantiate the necessary hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {
		/*
		 * Register admin scripts.
		 */
		add_action('admin_enqueue_scripts', array($this, 'register_scripts'));

		/*
		 * Add forms
		 */
		add_action('init', array($this, 'register_forms'), 10);

		/*
		 * Replace the core scripts from the plugin or theme page.
		 */
		add_action('current_screen', array($this, 'replace_wp_core_scripts'), 10000);

		/*
		 * Change plugin info.
		 */
		add_filter('all_plugins', array($this, 'change_plugin_info'), 10);

		/*
		 * Change theme info.
		 */
		add_filter('all_themes', array($this, 'change_theme_info'), 10);

		add_filter('wp_prepare_themes_for_js', array($this, 'change_theme_info_js'), 10);

		/*
		 * Add a category column to the plugin page.
		 */
		add_filter('manage_plugins-network_columns', array($this, 'add_categories_column'));

		/*
		 * Add a category column to the theme page.
		 */
		add_filter('manage_themes-network_columns', array($this, 'add_categories_column'));

		/*
		 * Add link to edi information in the plugins page.
		 */
		add_filter('network_admin_plugin_action_links', array($this, 'add_edit_link'), 10, 4);

		/*
		 * Add link to edi information in the plugins page.
		 */
		add_filter('theme_action_links', array($this, 'add_edit_link'), 10, 3);

		/*
		 * Render the category column in the plugins page.
		 */
		add_action('manage_plugins_custom_column', array($this, 'render_plugins_categories_column'), 10, 3);

		/*
		 * Render the category column in the themes page.
		 */
		add_action('manage_themes_custom_column', array($this, 'render_themes_categories_column'), 10, 3);

		/*
		 * Clean meta in the plugins page.
		 */
		add_filter('plugin_row_meta', array($this, 'clean_plugin_meta_row'), 10, 3);

		/*
		 * Clean meta in the themes page.
		 */
		add_filter('theme_row_meta', array($this, 'clean_theme_meta_row'), 10, 3);

		/*
		 * Replace plugins page.
		 */
		add_action('in_admin_header', array($this, 'replace_plugins_page'));

		add_action('in_admin_header', array($this, 'add_filters_to_theme_page'), 10, 2);

		add_filter('admin_body_class', array($this, 'add_admin_class'));

	} // end init;

	/**
	 * Registers and enqueue the scripts and styles necessary.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_scripts() {
		/*
		 * Enqueue the necessary scripts form media upload.
		 */
		wp_enqueue_media();

		/*
		 * Register styles
		 */
		wp_register_style('wp-ultimo-plugin-and-theme-manager', WP_Ultimo_Plugin_And_Theme_Manager()->helper->get_asset('ptm.css', 'css'), array());

	} // end register_scripts;

	/**
	 * Register ajax forms that we use to edit plugins or themes information.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register_forms() {
		/*
		 * Edit Asset Information.
		 */
		wu_register_form('edit_asset_information', array(
			'render'  => array($this, 'render_edit_asset_information'),
			'handler' => array($this, 'handle_edit_asset_information'),
		));

	} // end register_forms;

	/**
	 * Add the edit link to one of the options.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $actions Actions of the row.
	 * @param object $slug Plugin or theme object.
	 * @return array $actions.
	 */
	public function add_edit_link($actions, $slug) {

		$current_screen = get_current_screen();

		if (!$current_screen) {

			return $actions;

		} // end if;

		$type = '';

		if ($current_screen->id === 'themes-network') {

			$type = 'theme';

			$slug = $slug->stylesheet;

		} elseif ($current_screen->id === 'plugins-network') {

			$type = 'plugin';

		} else {

			return $actions;

		} // end if;

		$url = wu_get_form_url('edit_asset_information', array(
			'extension_type' => $type,
			'extension_slug' => $slug,
		));

		$actions['wu-styling inline hide-if-no-js'] = sprintf('<a href="%s" class="wubox wu-text-orange-500" title="%s">%s</a>', $url, esc_attr(__('Rebrand Item', 'wp-ultimo-plugin-and-theme-manager')), sprintf(__('Rebrand Item', 'wp-ultimo-plugin-and-theme-manager')));

		return $actions;

	} // end add_edit_link;

	/**
	 * Renders the edit information modal.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function render_edit_asset_information() {

		$object = null;

		$all_categories = array();

		$extension_type = wu_request('extension_type');

		$slug = wu_request('extension_slug');

		if ($extension_type === 'theme') {

			$object = Theme::get_by('slug', $slug);

			if (!$object) {

				$object = new Theme(array(
					'slug' => $slug,
				));

			} // end if;

			$all_categories = Extension::get_all_categories('theme');

		}  // end if;

		if ($extension_type === 'plugin') {

			$object = Plugin::get_by('slug', $slug);

			if (!$object) {

				$object = new Plugin(array(
					'slug' => $slug,
				));

			} // end if;

			$all_categories = Extension::get_all_categories('plugin');

		} // end if;

		$fields = array(
			// Tab
			'tab'               => array(
				'type'              => 'tab-select',
				'value'             => 'info',
				'html_attr'         => array(
					'v-model' => 'tab',
				),
				'options'           => array(
					'info'       => __('Information', 'wp-ultimo'),
					'visibility' => __('Display Info', 'wp-ultimo'),
					'image'      => __('Image', 'wp-ultimo'),
				),
				'wrapper_html_attr' => array(
					'v-cloak' => 1,
				),
			),
			'name'              => array(
				'title'             => __('Name', 'wp-ultimo-plugin-and-theme-manager'),
				'placeholder'       => sprintf(__('e.g.: %s', 'wp-ultimo-plugin-and-theme-manager'), $object->get_original('name', 'None')),
				'type'              => 'text',
				'value'             => $object->get_name(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "info"',
				),
			),
			'description'       => array(
				'title'             => __('Description', 'wp-ultimo-plugin-and-theme-manager'),
				'placeholder'       => sprintf(__('e.g.: %s', 'wp-ultimo-plugin-and-theme-manager'), strip_tags($object->get_original('description', 'None'))),
				'type'              => 'textarea',
				'value'             => $object->get_description(),
				'html_attr'         => array(
					'rows' => 4,
				),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "info"',
				),
			),
			'author'            => array(
				'title'             => __('Author', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'text',
				'placeholder'       => sprintf(__('e.g.: %s', 'wp-ultimo-plugin-and-theme-manager'), $object->get_original('authorname', 'None')),
				'value'             => $object->get_author(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "info"',
				),
			),
			'categories'        => array(
				'title'             => __('Categories', 'wp-ultimo-plugin-and-theme-manager'),
				'placeholder'       => __('Comma-separated list.', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'select',
				'value'             => $object->get_categories(),
				'options'           => $all_categories ? $all_categories : array(),
				'html_attr'         => array(
					'data-selectize-categories' => 999,
					'multiple'                  => true,
				),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "info"',
				),
			),
			'display_author'    => array(
				'title'             => __('Display Author?', 'wp-ultimo-plugin-and-theme-manager'),
				'desc'              => __('Toggle to show/hide the author info from the listing.', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'toggle',
				'value'             => $object->should_display_author(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "visibility"',
				),
			),
			'display_details'   => array(
				'title'             => __('Display Details?', 'wp-ultimo-plugin-and-theme-manager'),
				'desc'              => __('Toggle to show/hide the details from the listing.', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'toggle',
				'value'             => $object->should_display_details(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "visibility"',
				),
			),
			'display_version'   => array(
				'title'             => __('Display Version?', 'wp-ultimo-plugin-and-theme-manager'),
				'desc'              => __('Toggle to show/hide the version from the listing.', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'toggle',
				'value'             => $object->should_display_version(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "visibility"',
				),
			),
			'display_other'     => array(
				'title'             => __('Display Other Information?', 'wp-ultimo-plugin-and-theme-manager'),
				'desc'              => __('Toggle to show/hide the additional info from the listing.', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'toggle',
				'value'             => $object->should_display_other(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "visibility"',
				),
			),
			'featured_image_id' => array(
				'title'             => __('Upload Image', 'wp-ultimo-plugin-and-theme-manager'),
				'desc'              => __('Upload Image', 'wp-ultimo-plugin-and-theme-manager'),
				'type'              => 'image',
				'value'             => $object->get_featured_image_id(),
				'img'               => $object->get_featured_image(),
				'html_attr'         => array(),
				'wrapper_html_attr' => array(
					'v-show' => 'tab === "image"',
				),
			),
			'extension_type'    => array(
				'type'  => 'hidden',
				'value' => wu_request('extension_type'),
			),
			'extension_slug'    => array(
				'type'  => 'hidden',
				'value' => wu_request('extension_slug'),
			),
			'submit_button'     => array(
				'type'            => 'submit',
				'title'           => __('Save Item', 'wp-ultimo-plugin-and-theme-manager'),
				'value'           => 'save',
				'classes'         => 'button button-primary wu-w-full',
				'wrapper_classes' => 'wu-items-end',
				'html_attr'       => array(),
			),
		);

		$form = new \WP_Ultimo\UI\Form('edit_asset_information', $fields, array(
			'views'                 => 'admin-pages/fields',
			'classes'               => 'wu-modal-form wu-widget-list wu-striped wu-m-0 wu-mt-0',
			'field_wrapper_classes' => 'wu-w-full wu-box-border wu-items-center wu-flex wu-justify-between wu-p-4 wu-m-0 wu-border-t wu-border-l-0 wu-border-r-0 wu-border-b-0 wu-border-gray-300 wu-border-solid',
			'html_attr'             => array(
				'data-wu-app' => 'edit_asset_information',
				'data-state'  => wu_convert_to_state(array(
					'tab' => 'info',
				)),
			),
		));

		$form->render();

	} // end render_edit_asset_information;

	/**
	 * Handles the edit information modal.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function handle_edit_asset_information() {

		$extension_type = wu_request('extension_type');

		$extension_slug = wu_request('extension_slug');

		if ($extension_type === 'theme') {

			$object = Theme::get_by('slug', $extension_slug);

			if (!$object) {

				$object = new Theme(array(
					'slug' => $extension_slug,
				));

			} // end if;

			$return = array(
				'redirect_url' => network_admin_url('themes.php'),
			);

		}  // end if;

		if ($extension_type === 'plugin') {

			$object = Plugin::get_by('slug', $extension_slug);

			if (!$object) {

				$object = new Plugin(array(
					'slug' => $extension_slug,
				));

			} // end if;

			$return = array(
				'redirect_url' => network_admin_url('plugins.php'),
			);

		} // end if;

		$attrs = array(
			'name'              => wu_request('name'),
			'description'       => wu_request('description'),
			'author'            => wu_request('author'),
			'categories'        => wu_request('categories', array()),
			'display_author'    => wu_request('display_author'),
			'display_details'   => wu_request('display_details'),
			'display_version'   => wu_request('display_version'),
			'display_other'     => wu_request('display_other'),
			'featured_image_id' => wu_request('featured_image_id'),
		);

		$object->attributes($attrs);

		$saved = $object->save();

		if (!$saved) {

			$error = new \WP_Error('something-wrong', __('We were not able to save this extension.', 'wp-ultimo'));

			wp_send_json_error($error);

		} // end if;

		if (is_wp_error($saved)) {

			wp_send_json_error($saved);

		} // end if;

		wp_send_json_success($return);

	} // end handle_edit_asset_information;

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
	 * Adds the filter bar for the themes page
	 *
	 * @since 2.0.0
	 *
	 * @return void.
	 */
	public function add_filters_to_theme_page() {

		if (get_current_screen()->id !== 'themes') {

			return;

		} // end if;

		wp_enqueue_style('wp-ultimo-plugin-and-theme-manager');

		?>

		<div class="wp-filter" style="display: none;">
			<ul class="filter-links">

				<li class="selector-inactive">
					<a href="#" class="current" data-category=""><?php _e('All Themes'); ?></a>
				</li>

		<?php

		$categories = Extension::get_all_categories('theme');

		if ($categories) {

			foreach ($categories as $slug => $category) {

				?>

					<li>

						<a href="?s=<?php echo $slug; ?>" class="" data-category="<?php echo $slug; ?>"><?php echo $category; ?></a>

					</li>

					<?php } // end foreach; ?>

				<?php } // end if; ?>

				</ul>

			</div>

			<script type="text/javascript">
			(function($){
					$(document).ready(function() {
					$('.wp-filter').insertAfter($( '#wpbody h1:first' )).show();
					});
			})(jQuery);
			</script>

		<?php

	} // end add_filters_to_theme_page;

	/**
	 * Add classes of control to the body class.
	 *
	 * @since 2.0.0
	 *
	 * @param string $classes With the classes to add.
	 * @return string With all classes to be added.
	 */
	public function add_admin_class($classes) {

		$screen = get_current_screen();

		if ($this->should_replace_screen() || $screen->id === 'themes') {

			/**
			 * Hides the info we don't want to show.
			 */
			add_action('admin_print_footer_scripts', function() {

				$classes = array();

				if (!wu_get_setting('display_plugin_version', true)) {

					$classes[] = '.theme-overlay .theme-version';

				} // end if;

				if (!wu_get_setting('display_plugin_author', true)) {

					$classes[] = '.theme-overlay .theme-author, .themes .authors';

				} // end if;

				$selectors = implode(', ', $classes);

				if (empty($selectors)) {

					return;

				} // end if;

				echo "
					<style>
						$selectors {
							display: none;
						}
					</style>
				";

				echo "<script type='text/javascript'>(function($) {
					$(document).ready(function() {
						$(\"$selectors\").remove();
					});
				})(jQuery);</script>";

			});

			return "wu-ptm-page $classes themes-php";

		} // end if;

		return $classes;

	} // end add_admin_class;

	/**
	 * Replace the CORE theme script.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Screen $screen With the current screen.
	 * @return void.
	 */
	public function replace_wp_core_scripts($screen) {

		global $wp_scripts;

		/**
		 * Deregister default WordPress Themes and enqueue our own code, if necessary
		 */
		if (in_array($screen->id, array('themes', 'plugins')) && $this->should_apply_changes()) {

			if (isset($wp_scripts->registered['theme'])) {

				$wp_scripts->registered['theme']->src = WP_Ultimo_Plugin_And_Theme_Manager()->helper->get_asset('ptm-theme.js', 'js');

			} // end if;

			if (isset($wp_scripts->registered['customize-loader'])) {

				unset($wp_scripts->registered['customize-loader']);

			} // end if;

			$display_type = $screen->id === 'themes' ? 'theme' : wu_get_setting('display_type', 'theme');

			wp_localize_script('theme', 'wu_ptm', array(
				'type'         => $screen->id === 'plugins' ? 'plugins' : 'themes',
				'display_type' => $display_type,
			));

		} // end if;

		/*
		 * In the case of the super admin plugins page, enqueue the necessary scripts.
		 */
		if ($screen->id === 'plugins-network' || $screen->id === 'themes-network') {

			wp_enqueue_script('wu-selectizer');

			wp_enqueue_script('wu-fields');

			wp_enqueue_media();

			add_wubox();

		} // end if;

	} // end replace_wp_core_scripts;

	/**
	 * Check if a given user is a ultimo user.
	 *
	 * @since 2.0.0
	 *
	 * @param  int $user_id Wit the user ID.
	 * @return boolean.
	 */
	public function should_apply_changes($user_id = false) {

		if (!function_exists('get_current_screen')) {

			return false;

		} // end if;

		$allowed = array('themes-network', 'plugins-network', 'plugins', 'themes');

		$screen = get_current_screen();

		if ($screen && in_array($screen->id, $allowed)) {

			return true;

		} // end if;

		$user_id = isset($user_id) ? $user_id : wu_get_current_customer()->get_id();

		return wu_get_memberships($user_id);

	} // end should_apply_changes;

	/**
	 * Check if we should replace screen
	 *
	 * @since 2.0.0
	 *
	 * @return boolean
	 */
	public function should_replace_screen() {

		if (wu_get_setting('replace_plugin_page') && $this->should_apply_changes()) {

			$screen = get_current_screen();

			return $screen->id === 'plugins';

		} // end if;

		return false;

	} // end should_replace_screen;

	/**
	 * Modifies the plugin or theme data, based on the context.
	 *
	 * @since 2.0.0
	 *
	 * @param string  $slug The asset slug to find the wu post.
	 * @param array   $asset With the original data.
	 * @param string  $type The type, plugin or theme.
	 * @param boolean $network If the is network admin.
	 * @return array
	 */
	public function change_data($slug, $asset, $type, $network = false) {

		if ($type === 'plugin') {

			$object = Plugin::get_by('slug', $slug);

			if (!$object) {

				return $asset;

			} // end if;

			$items = array('Name', 'Description', 'Author');

			foreach ($items as $key) {

				$lower_key = strtolower($key);

				$new_value = $object->{"get_$lower_key"}();

				if (empty($new_value)) {

					continue;

				} // end if;

				if ($network) {

					$new_value = sprintf('%s <br><code>(%s)</code>', $new_value, $object->get_original($lower_key));

				} // end if;

				$asset[$key] = $new_value;

			} // end foreach;

			return $asset;

		} // end if;

		if ($type === 'theme') {

			$object = Theme::get_by('slug', $slug);

			if (!$object) {

				return $asset;

			} // end if;

			$data = $this->get_headers($asset);

			$items = array('Name', 'Description', 'Author');

			foreach ($items as $key) {

				$lower_key = strtolower($key);

				$new_value = $object->{"get_$lower_key"}();

				if (empty($new_value)) {

					continue;

				} // end if;

				if ($network) {

					$new_value = sprintf('%s <br><code>(%s)</code>', $new_value, $object->get_original($lower_key));

				} // end if;

				$data[$key] = $new_value;

			} // end foreach;

			$data = $this->replace_headers($object->wp_theme, $data);

			return $data;

		} // end if;

	} // end change_data;

	/**
	 * Uses reflection to edit protected elements of WP Theme
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Theme $original_wp_theme Original theme info.
	 * @param  array    $modified_headers Headers to replace the original.
	 * @return WP_Theme With the replaced header.
	 */
	public function replace_headers($original_wp_theme, $modified_headers) {

		if (!is_object($original_wp_theme)) {

			return $original_wp_theme;

		} // end if;

		$reflector = new \ReflectionClass($original_wp_theme);

		$headers = $reflector->getProperty('headers');

		$headers->setAccessible(true);

		$headers->setValue($original_wp_theme, $modified_headers);

		return $original_wp_theme;

	} // end replace_headers;

	/**
	 * Adds the categories columns to the plugins list table
	 *
	 * @since 2.0.0
	 *
	 * @param array $columns All table columns.
	 * @return array With the categories column included.
	 */
	public function add_categories_column($columns) {

		$columns['ptm_categories'] = __('Categories', 'wp-ultimo-plugin-and-theme-manager');

		return $columns;

	} // end add_categories_column;

	/**
	 * Change Theme Infos
	 *
	 * @since 2.0.0
	 *
	 * @param  array $all_themes All available themes.
	 * @return array Themes with the information changed.
	 */
	public function change_theme_info($all_themes) {

		$network = false;

		/*
		 * Only run for Ultimo Users
		 */
		if (!$this->should_apply_changes()) {

			return $all_themes;

		} // end if;

		foreach ($all_themes as $key => $theme) {

			if (is_network_admin()) {

				$network = true;

			} // end if;

			$all_themes[$key] = $this->change_data($key, $theme, 'theme', $network);

		} // end foreach;

		return $all_themes;

	} // end change_theme_info;

	/**
	 * Change the info for the front end display of the users
	 *
	 * @since 2.0.0
	 *
	 * @param  array $all_themes Array containing prepared themes for JS.
	 * @return array with modified data.
	 */
	public function change_theme_info_js($all_themes) {
		/*
		 * Only run for Ultimo Users
		 */
		if (!$this->should_apply_changes()) {

			return $all_themes;

		} // end if;

		foreach ($all_themes as $key => $theme) {

			$asset = Theme::get_by('slug', $key);

			if ($asset) {

				$theme['name'] = $asset->get_name() ? $asset->get_name() : $theme['name'];

				$theme['description'] = $asset->get_description() ? $asset->get_description() : $theme['description'];

				if ($asset->should_display_author()) {

					$theme['author'] = $asset->get_author() ? $asset->get_author() : $theme['author'];

					$theme['authorAndUri'] = $asset->get_author() ? $asset->get_author() : $theme['author'];

				} else {

					$theme['author'] = '';

					$theme['authorAndUri'] = '';

				} // end if;

				if (!$asset->should_display_version()) {

					$theme['version'] = '';

				} // end if;

				if ($asset->get_featured_image('full', false)) {

					$theme['screenshot'] = array($asset->get_featured_image('full'));

				} // end if;

				$all_categories = $asset->get_categories();

				if ($all_categories) {

					$theme['tags'] = trim(implode(', ', $all_categories));

				} // end if;

				/*
				 * Check if is a parent theme
				 */
				if ($theme['parent']) {
					/*
					 * Search for the main theme name based on theme parent name.
					 */
					foreach ($all_themes as $theme_main) {

						if ($theme_main['id'] === strtolower(str_replace(' ', '', $theme['parent']))) {

							$parent = Theme::get_by('slug', $theme_main['id']);

							$theme['parent'] = $parent && $parent->get_name() ? $parent->get_name() : $theme['parent'];

						} // end if;

					} // end foreach;

				} // end if;

				/*
				 * Hide info that we don't want to display
				 */
				add_action('admin_print_footer_scripts', function() use ($asset, $key) {

					$classes = array();

					if (!$asset->should_display_version()) {

						$classes[] = "[data-slug='{$key}'] .theme-version";

					} // end if;

					if (!$asset->should_display_author()) {

						$classes[] = "[data-slug='{$key}'] .theme-author";

					} // end if;

					$selectors = implode(', ', $classes);

					echo "
						<style>
							$selectors {
								display: none;
							}
						</style>
					";

					echo "<script type='text/javascript'>(function($) {
						$(document).ready(function() {
							$(\"$selectors\").remove();
						});
					})(jQuery);</script>";

				});

			} // end if;

			$all_themes[$key] = $theme;

		} // end foreach;

		return $all_themes;

	} // end change_theme_info_js;

	/**
	 * Prepare Plugins for JS on our new plugins page.
	 *
	 * @since 2.0.0
	 *
	 * @return array With the prepared plugins for js.
	 */
	public function prepare_plugins_for_js() {

		$all_plugins = apply_filters('all_plugins', get_plugins()); // phpcs:ignore

		$prepared_plugins = array();

		foreach ($all_plugins as $plugin_file => $plugin) {

			$active = is_plugin_active($plugin_file);

			$object = Plugin::get_by('slug', $plugin_file);

			if ($object) {

				$prepared_plugins[] = array(
					'id'           => $plugin_file,
					'name'         => $plugin['Name'],
					'screenshot'   => array($object->get_featured_image()),
					'description'  => $plugin['Description'],
					'author'       => $object->should_display_author() ? $plugin['Author'] : '',
					'authorAndUri' => $object->should_display_author() ? $plugin['Author'] : '',
					'version'      => $object->should_display_version() ? $plugin['Version'] : '',
					'tags'         => $object->get_categories() ? implode(', ', $object->get_categories()) : __('None', 'wp-ultimo-plugin-and-theme-manager'),
					'parent'       => false,
					'active'       => $active, // $slug === $current_theme,
					'hasUpdate'    => false,
					'hasPackage'   => false,
					'update'       => false,
					'network'      => isset($plugin['Network']) ? $plugin['Network'] : false,
					'actions'      => array(
						'activate'   => wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin_file, 'activate-plugin_' . $plugin_file),
						'deactivate' => wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . $plugin_file, 'deactivate-plugin_' . $plugin_file),
					),
				);

			} else {

				$prepared_plugins[] = array(
					'id'           => $plugin_file,
					'name'         => $plugin['Name'],
					'screenshot'   => array(WP_Ultimo_Plugin_And_Theme_Manager()->helper->get_asset('ptm-placeholder.png')),
					'description'  => $plugin['Description'],
					'author'       => $plugin['Author'],
					'authorAndUri' => $plugin['Author'],
					'version'      => $plugin['Version'],
					'tags'         => __('None', 'wp-ultimo-plugin-and-theme-manager'),
					'parent'       => false,
					'active'       => $active, // $slug === $current_theme,
					'hasUpdate'    => false,
					'hasPackage'   => false,
					'update'       => false,
					'network'      => isset($plugin['Network']) ? $plugin['Network'] : false,
					'actions'      => array(
						'activate'   => wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin_file, 'activate-plugin_' . $plugin_file),
						'deactivate' => wp_nonce_url('plugins.php?action=deactivate&amp;plugin=' . $plugin_file, 'deactivate-plugin_' . $plugin_file),
					),
				);

			} // end if;

		} // end foreach;

		return $prepared_plugins;

	} // end prepare_plugins_for_js;

	/**
	 * Change Plugin Info
	 *
	 * @since 2.0.0
	 *
	 * @param  array $all_plugins The list of all plugins.
	 * @return array With all plugins information changed.
	 */
	public function change_plugin_info($all_plugins) {

		$network = false;

		/*
		 * Only run for Ultimo Users
		 */
		if (!$this->should_apply_changes()) {

			return $all_plugins;

		} // end if;

		foreach ($all_plugins as $key => $plugin) {

			$all_plugins[$key] = $this->change_data($key, $plugin, 'plugin', is_network_admin());

		} // end foreach;

		return $all_plugins;

	} // end change_plugin_info;

	/**
	 * Trash posts related to deleted post.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type  Extension type: `theme` or `plugin`.
	 * @param array  $slugs List of slugs of still existent post.
	 */
	public function clean_deleted_extensions($type, $slugs) {

		// TODO: alterar essa função.
		$ptm_posts = new Plugin_And_Theme_Manager(array(
			'post_type'              => 'wu_extension',
			'posts_per_page'         => -1,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'meta_key'               => 'wpu_type',
			'meta_value'             => $type,
		));

		if (!empty($ptm_posts->posts)) {

			$ptm_posts = wp_list_pluck($ptm_posts->posts, 'post_name', 'ID');

			$deleted_extensions = array_diff( $ptm_posts, $slugs );

			foreach ($deleted_extensions as $deleted_asset_id => $deleted_extension) {

				wp_delete_post($deleted_asset_id);

			} // end foreach;

		} // end if;

	} // end clean_deleted_extensions;

	/**
	 * Displays the network table.
	 *
	 * @since 2.0.0
	 *
	 * @param  object $object Post object.
	 * @return void.
	 */
	public function displays_table_network($object) {

		$network = false;

		/*
		 * Depending on the type, we get a certain type of table
		 */
		if ($object->get_meta('extension_type') === 'theme') {

			$wp_list_table = _get_list_table('WP_MS_Themes_List_Table', array('screen' => 'themes-network'));

			$asset = wp_get_theme($object->get_meta('asset_slug'));

			$slug = $object->get_slug();

			if (is_network_admin()) {

				$network = true;

			} // end if;

			$args = $this->change_data($slug, $asset, 'theme', $network);

		} elseif ($object->get_meta('extension_type') === 'plugin') {

			$asset = wp_get_theme($object->get_meta('asset_slug'));

			$wp_list_table = _get_list_table('WP_Plugins_List_Table', array('screen' => 'plugins-network'));

			$plugin_file = $object->get_slug();

			if (is_network_admin()) {

				$network = true;

			} // end if;

			$args = array($plugin_file, $this->change_data($plugin_file, $asset, 'theme', $network));

		} // end if;

		if ($args) {

			$wp_list_table->single_row($args);

		} // end if;

	} // end displays_table_network;

	/**
	 * Renders the custom column for the categories
	 *
	 * @since 2.0.0
	 *
	 * @param  string $column_name With the colum name to compare with ptm_column.
	 * @param  string $plugin_file The path to the plugin that you be changed.
	 * @param  array  $plugin_data The information to change in the plugins info.
	 * @return void.
	 */
	public function render_plugins_categories_column($column_name, $plugin_file, $plugin_data) {

		if ($column_name !== 'ptm_categories') {

			return;

		} // end if;

		$object = Plugin::get_by('slug', $plugin_file);

		if ($object && $object->get_categories()) {

			echo $this->wrap_in_tags($object->get_categories());

			return;

		} // end if;

		echo __('No categories', 'wp-ultimo-plugin-and-theme-manager');

	} // end render_plugins_categories_column;

	/**
	 * Wrap the categories in tag styling.
	 *
	 * @since 2.0.0
	 *
	 * @param array $categories The array of categories.
	 * @return string
	 */
	protected function wrap_in_tags($categories) {

		$tags = implode('</span><span class="wu-px-2 wu-py-1 wu-bg-gray-300 wu-rounded wu-text-xs wu-mr-1">', $categories);

		return sprintf('<div class="wu-styling"><span class="wu-px-2 wu-py-1 wu-bg-gray-300 wu-rounded wu-text-xs wu-mr-1">%s</span></div>', $tags);

	} // end wrap_in_tags;

	/**
	 * Renders the custom column for the categories in the themes page.
	 *
	 * @since 2.0.0
	 *
	 * @param string   $column_name With the colum name to compare with ptm_column.
	 * @param string   $stylesheet Directory name of the theme.
	 * @param WP_Theme $theme Current WP_Theme object.
	 * @return array With the modified column.
	 */
	public function render_themes_categories_column($column_name, $stylesheet, $theme) {

		if ($column_name !== 'ptm_categories') {

			return;

		} // end if;

		$object = Theme::get_by('slug', $stylesheet);

		if ($object && $object->get_categories()) {

			echo $this->wrap_in_tags($object->get_categories());

			return;

		} // end if;

		echo __('No categories', 'wp-ultimo-plugin-and-theme-manager');

	} // end render_themes_categories_column;

	/**
	 * Remove some elements of the plugin Meta List
	 *
	 * @since 2.0.0
	 *
	 * @param array  $plugin_meta Array containing the plugin meta links.
	 * @param string $plugin_file Plugin file.
	 * @param array  $plugin_data Array containing the plugin data.
	 * @return array Modified plugin meta array.
	 */
	public function clean_plugin_meta_row($plugin_meta, $plugin_file, $plugin_data) {
		/*
		 * Only run for Ultimo Users
		 */
		if (!$this->should_apply_changes()) {

			return $plugin_meta;

		} // end if;

		$object = Plugin::get_by('slug', $plugin_file);

		if ($object) {

			if (!$object->should_display_other() && !is_network_admin()) {

				$asset_meta = array($plugin_meta[0], $plugin_meta[1], $plugin_meta[2]);

			} // end if;

			$options = array('display_version', 'display_author', 'display_details');

			foreach ($options as $key => $option) {

				if (!$object->{"should_$option"}()) {

					unset($plugin_meta[$key]);

				} // end if;

			} // end foreach;

		} // end if;

		return $plugin_meta;

	} // end clean_plugin_meta_row;

	/**
	 * Remove some elements of the theme Meta List
	 *
	 * @since 2.0.0
	 *
	 * @param  array    $theme_meta An array of the theme's metadata, including the version, author, and theme URI.
	 * @param  string   $stylesheet Directory name of the theme.
	 * @param  WP_Theme $theme WP_Theme object.
	 * @return array Modified plugin meta array.
	 */
	public function clean_theme_meta_row($theme_meta, $stylesheet, $theme) {
		/*
		 * Only run for Ultimo Users
		 */
		if (!$this->should_apply_changes()) {

			return $theme_meta;

		} // end if;

		$options = array('display_version', 'display_author', 'display_details');

		$object = Theme::get_by('slug', $stylesheet);

		if ($object) {

			if (!$object->should_display_other() && !is_network_admin()) {

				$theme_meta = array($theme_meta[1], $theme_meta[2]);

			} // end if;

			foreach ($options as $key => $option) {

				$method_name = "should_$option";

				if (!$object->{$method_name}()) {

					unset($theme_meta[$key]);

				} // end if;

			} // end foreach;

		} // end if;

		return $theme_meta;

	} // end clean_theme_meta_row;

	/**
	 * Replace the plugins page, if that option is selected
	 *
	 * @since 2.0.0
	 *
	 * @return void.
	 */
	public function replace_plugins_page() {

		if ($this->should_replace_screen()) {

			$screen = get_current_screen();

			$check_base = array(
				$screen->parent_base,
				$screen->base,
				$screen->id
			);

			wp_enqueue_script('theme');

			WP_Ultimo_Plugin_And_Theme_Manager()->helper->render('base/page-plugins', array(
				'type_slug'       => in_array('plugins', $check_base, true) ? 'plugin' : 'theme',
				'display_type'    => wu_get_setting('display_type', 'theme'),
				'categories'      => Extension::get_all_categories('plugin'),
				'plugins'         => $this->prepare_plugins_for_js(),
				'display_setting' => wu_get_setting('display_type')
			));

			exit;

		} // end if;

	} // end replace_plugins_page;

} // end class Plugin_And_Theme_Manager;
