<?php
/**
 * Class used for querying posts.
 *
 * @package WP_Ultimo
 * @subpackage Database\Posts
 * @since 2.0.0
 */

namespace WP_Ultimo_Plugin_And_Theme_Manager\Models; // phpcs:ignore

use \WP_Ultimo\Database\Posts\Post_Query;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Class used for querying posts.
 *
 * @since 2.0.0
 */
class Plugin_Query extends Post_Query {

	/**
	 * Callback function for turning IDs into objects.
	 *
	 * @since  2.0.0
	 * @access public
	 * @var mixed
	 */
	protected $item_shape = '\\WP_Ultimo_Plugin_And_Theme_Manager\\Models\\Plugin';

} // end class Plugin_Query;
