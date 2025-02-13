<?php
/**
 * The post model for Extension posts.
 *
 * @package WP_Ultimo_Plugin_And_Theme_Manager
 * @subpackage Models
 * @since 2.0.0
 */

namespace WP_Ultimo_Plugin_And_Theme_Manager\Models; // phpcs:ignore

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Extension model class. Implements the Base Model.
 *
 * @since 2.0.0
 */
abstract class Extension extends \WP_Ultimo\Models\Post_Base_Model {

	/**
	 * Post type.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $type = 'extension';

	/**
	 * Set the allowed types to prevent saving wrong types.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $allowed_types = array('extension');

	/**
	 * Email slug.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $slug = '';

	/**
	 * The extension's author.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $author = '';

	/**
	 * The extension type. Can be plugin or theme.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $extension_type;

	/**
	 * If we should display the author.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $display_author;

	/**
	 * If we should display the version.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $display_version;

	/**
	 * If we should display the details.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $display_details;

	/**
	 * If we should display other info.
	 *
	 * @since 2.0.0
	 * @var boolean
	 */
	protected $display_other;

	/**
	 * Featured Image.
	 *
	 * @since 2.0.0
	 * @var int
	 */
	protected $featured_image_id;

	/**
	 * The extension original data.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $original_data;

	/**
	 * Same as the original data, but with keys normalized.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $_original_data;

	/**
	 * The extension category list.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	protected $categories;

	/**
	 * Constructs the object via the constructor arguments.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $object Std object with model parameters.
	 */
	public function __construct($object = null) {

		parent::__construct($object);

		/*
		 * Load the original data of the extension. Also converts the keys to lowercase.
		 */
		$this->original_data = $this->load_original_data();

		$this->_original_data = array_change_key_case($this->original_data);

	} // end __construct;

	/**
	 * Loads the original data.
	 *
	 * Each extension type should implement this method on their own.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	abstract protected function load_original_data();

	/**
	 * Get the extension original data..
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_original_data() {

		return $this->original_data;

	} // end get_original_data;

	/**
	 * Gets a piece of original data.
	 *
	 * @since 2.0.0
	 *
	 * @param string  $param The original param name.
	 * @param boolean $default The default value to return.
	 * @return mixed
	 */
	public function get_original($param, $default = false) {

		return wu_get_isset($this->_original_data, $param, $default);

	} // end get_original;

	/**
	 * Aliases
	 */

	/**
	 * Get alias for title.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_name() {

		return $this->get_title();

	} // end get_name;

	/**
	 * Set alias for title.
	 *
	 * @since 2.0.0
	 * @param string $name Alias for title.
	 * @return void
	 */
	public function set_name($name) {

		$this->set_title($name);

	} // end set_name;

	/**
	 * Get alias for content.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_description() {

		return $this->get_content();

	} // end get_description;

	/**
	 * Set alias for content.
	 *
	 * @since 2.0.0
	 * @param string $description Alias for content.
	 * @return void
	 */
	public function set_description($description) {

		$this->set_content($description);

	} // end set_description;

	/**
	 * Get ptm slug.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_slug() {

		return $this->slug;

	} // end get_slug;

	/**
	 * Set the slug.
	 *
	 * @since 2.0.0
	 *
	 * @param string $slug The slug being set.
	 * @return void
	 */
	public function set_slug($slug) {

		$this->slug = $slug;

	} // end set_slug;

	/**
	 * Adds checks to prevent saving the model with the wrong type.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type The type being set.
	 * @return void
	 */
	public function set_type($type) {

		if (!in_array($type, $this->allowed_types, true)) {

			$type = 'extension';

		} // end if;

		$this->type = $type;

	} // end set_type;

	/**
	 * Get the extension's author.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_author() {

		if (!$this->author) {

			$this->author = $this->get_meta('wu_asset_author');

		} // end if;

		return $this->author;

	} // end get_author;

	/**
	 * Set the extension's author.
	 *
	 * @since 2.0.0
	 * @param string $author The extension's author.
	 * @return void
	 */
	public function set_author($author) {

		$this->author = $author;

		$this->meta['wu_asset_author'] = $this->author;

	} // end set_author;

	/**
	 * Get the extension type. Can be plugin or theme.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function get_extension_type() {

		return $this->extension_type;

	} // end get_extension_type;

	/**
	 * Set the extension type. Can be plugin or theme.
	 *
	 * @since 2.0.0
	 * @param string $extension_type The extension type. Can be plugin or theme.
	 * @return void
	 */
	public function set_extension_type($extension_type) {

		$this->extension_type = $extension_type;

	} // end set_extension_type;

	/**
	 * Get if we should display the author.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_display_author() {

		if ($this->display_author === null) {

			$this->display_author = $this->get_meta('wu_display_author', true);

		} // end if;

		return apply_filters('wu_extension_display_author', $this->display_author, $this);

	} // end should_display_author;

	/**
	 * Set if we should display the author.
	 *
	 * @since 2.0.0
	 * @param boolean $display_author If we should display the author.
	 * @return void
	 */
	public function set_display_author($display_author) {

		$this->meta['wu_display_author'] = (bool) $display_author;

		$this->display_author = $this->meta['wu_display_author'];

	} // end set_display_author;

	/**
	 * Get if we should display the version.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_display_version() {

		if ($this->display_version === null) {

			$this->display_version = $this->get_meta('wu_display_version', true);

		} // end if;

		return apply_filters('wu_extension_display_version', $this->display_version, $this);

	} // end should_display_version;

	/**
	 * Set if we should display the version.
	 *
	 * @since 2.0.0
	 * @param boolean $display_version If we should display the version.
	 * @return void
	 */
	public function set_display_version($display_version) {

		$this->meta['wu_display_version'] = (bool) $display_version;

		$this->display_version = $this->meta['wu_display_version'];

	} // end set_display_version;

	/**
	 * Get if we should display the details.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_display_details() {

		if ($this->display_details === null) {

			$this->display_details = $this->get_meta('wu_display_details', true);

		} // end if;

		return apply_filters('wu_extension_display_details', $this->display_details, $this);

	} // end should_display_details;

	/**
	 * Set if we should display the details.
	 *
	 * @since 2.0.0
	 * @param boolean $display_details If we should display the details.
	 * @return void
	 */
	public function set_display_details($display_details) {

		$this->meta['wu_display_details'] = (bool) $display_details;

		$this->display_details = $this->meta['wu_display_details'];

	} // end set_display_details;

	/**
	 * Get if we should display other info.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_display_other() {

		if ($this->display_other === null) {

			$this->display_other = $this->get_meta('wu_display_other', true);

		} // end if;

		return apply_filters('wu_extension_display_other', $this->display_other, $this);

	} // end should_display_other;

	/**
	 * Set if we should display other info.
	 *
	 * @since 2.0.0
	 * @param boolean $display_other If we should display other info.
	 * @return void
	 */
	public function set_display_other($display_other) {

		$this->meta['wu_display_other'] = (bool) $display_other;

		$this->display_other = $this->meta['wu_display_other'];

	} // end set_display_other;

	/**
	 * Get featured image ID.
	 *
	 * @since 2.0.0
	 * @return int
	 */
	public function get_featured_image_id() {

		if ($this->featured_image_id === null) {

			return $this->get_meta('wu_featured_image_id');

		} // end if;

		return $this->featured_image_id;

	} // end get_featured_image_id;

	/**
	 * Get featured image url.
	 *
	 * @since 2.0.0
	 * @param string $size The size of the image to retrieve.
	 * @param bool   $use_placeholder If we should get the placeholder.
	 * @return string
	 */
	public function get_featured_image($size = 'medium', $use_placeholder = true) {

		is_multisite() && switch_to_blog(wu_get_main_site_id());

		$image_attributes = wp_get_attachment_image_src($this->get_featured_image_id(), $size);

		is_multisite() && restore_current_blog();

		$default = $use_placeholder ? WP_Ultimo_Plugin_And_Theme_Manager()->helper->get_asset('ptm-placeholder.png') : false;

		return $image_attributes ? $image_attributes[0] : $default;

	} // end get_featured_image;

	/**
	 * Set featured image ID.
	 *
	 * @since 2.0.0
	 * @param int $image_id Holds the ID of the featured image.
	 * @return void
	 */
	public function set_featured_image_id($image_id) {

		$this->meta['wu_featured_image_id'] = $image_id;

		$this->feature_image_id = $image_id;

	} // end set_featured_image_id;

	/**
	 * Get the extension category list.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public function get_categories() {

		if (!isset($this->categories) && !$this->categories) {

			$this->categories = $this->get_meta('wu_categories', array());

		} // end if;

		return $this->categories;

	} // end get_categories;

	/**
	 * Set the extension category list.
	 *
	 * @since 2.0.0
	 *
	 * @param array $categories The extension category list.
	 * @return void
	 */
	public function set_categories($categories) {

		$this->meta['wu_categories'] = array_filter(array_unique($categories));

		$this->categories = $this->meta['wu_categories'];

	} // end set_categories;

	/**
	 * Gets a model instance by a column value.
	 *
	 * @since 2.0.0
	 *
	 * @param string $column The name of the column to query for.
	 * @param string $value Value to search for.
	 * @return Base_Model|false
	 */
	public static function get_by($column, $value) {

		$instance = new static();

		$query_class = new $instance->query_class;

		$new_model = $query_class->get_item_by($column, $value);

		if ($new_model) {

			return $new_model;

		} // end if;

		if ($column !== 'slug') {

			return false;

		} // end if;

		// Set the Slug
		$slug = $value;

		/*
		 * Tries to get the old model
		 */
		$modified_slug = wp_ultimo_ptm_get_slug_from_file($slug);

		// Switch to main blog
		is_multisite() && switch_to_blog(wu_get_main_site_id());

		$posts = get_posts(array(
			'name'           => $modified_slug,
			'post_type'      => 'wu_extension',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		));

		is_multisite() && restore_current_blog();

		if ($posts) {

			$post = $posts[0];

			$meta_data = get_post_meta($post->ID);

			$meta_data = array_map(function($item) {

				return current($item);

			}, $meta_data);

			$type = wu_get_isset($meta_data, 'wpu_type');

			if (!$type) {

				return false;

			} // end if;

			$new_data = array(
				'slug'              => $slug,
				'name'              => $post->post_title,
				'description'       => $post->post_content,
				'author'            => wu_get_isset($meta_data, 'wpu_author', ''),
				'featured_image_id' => (int) wu_get_isset($meta_data, 'wpu_thumbnail'),
				'display_author'    => (bool) wu_get_isset($meta_data, 'wpu_display_author'),
				'display_details'   => (bool) wu_get_isset($meta_data, 'wpu_display_details'),
				'display_version'   => (bool) wu_get_isset($meta_data, 'wpu_display_version'),
				'display_other'     => (bool) wu_get_isset($meta_data, 'wpu_display_other'),
				'categories'        => wu_get_isset($meta_data, 'categories', array()),
			);

			register_taxonomy('wu_extension_category_plugin', 'wu_extension', array(
				'label'                 => __('Plugin Category', 'wu-ptm'),
				'hierarchical'          => false,
				'show_ui'               => false,
				'query_var'             => true,
				'show_in_quick_edit'    => true,
				'update_count_callback' => '_update_post_term_count',
				'rewrite'               => array('slug' => 'category-theme'),
			));

			register_taxonomy('wu_extension_category_theme', 'wu_extension', array(
				'label'                 => __('Theme Category', 'wu-ptm'),
				'hierarchical'          => false,
				'show_ui'               => false,
				'query_var'             => true,
				'show_in_quick_edit'    => true,
				'update_count_callback' => '_update_post_term_count',
				'rewrite'               => array('slug' => 'category-plugin'),
			));

			$cats = wp_get_post_terms($post->ID, "wu_extension_category_{$type}", array('fields' => 'names'));

			$cats = array_change_key_case(array_combine($cats, $cats));

			$new_data['categories'] = $cats;

			$object = $type === 'plugin' ? new Plugin($new_data) : new Theme($new_data);

			$object->save();

			return $object;

		} // end if;

		return false;

	} // end get_by;

	/**
	 * Returns the categories.
	 *
	 * @since 2.0.0
	 * @param string $type The type to retrieve.
	 * @return array
	 */
	public static function get_all_categories($type = 'plugin') {

		global $wpdb;

		static $cats = null;

		if ($cats !== null) {

			return $cats;

		} // end if;

		$query = $wpdb->prepare("
			SELECT m.meta_value
			FROM {$wpdb->base_prefix}wu_postmeta as m
			INNER JOIN {$wpdb->base_prefix}wu_posts as p ON p.id=m.wu_post_id
			WHERE m.meta_key = %s
		", 'wu_categories');

		if ($type === 'plugin') {

			$query .= " AND p.slug LIKE '%.php'";

		} else {

			$query .= " AND p.slug NOT LIKE '%.php'";

		} // end if;

		$all_categories = $wpdb->get_results($query); // phpcs:ignore

		$all_categories = array_column($all_categories, 'meta_value');

		$all_categories = array_map('maybe_unserialize', $all_categories);

		$all_categories = array_merge(...$all_categories);

		$all_categories = array_unique($all_categories);

		$all_categories = array_combine($all_categories, $all_categories);

		$all_categories = array_change_key_case($all_categories);

		$cats = apply_filters('wu_extension_get_all_categories', $all_categories);

		return $cats;

	} // end get_all_categories;

} // end class Extension;
