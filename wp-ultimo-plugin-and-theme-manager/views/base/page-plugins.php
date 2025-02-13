<?php
/**
 * Page Plugins.
 *
 * @since 2.0.0
 */

wp_reset_vars(array('theme', 'search'));

wp_localize_script('theme', '_wpThemeSettings', array(
		'themes'    => $plugins,
		'settings'  => array(
				'canInstall'    => (!is_multisite() && current_user_can('install_themes')),
				'installURI'    => (!is_multisite() && current_user_can('install_themes')) ? admin_url('theme-install.php') : null,
				'confirmDelete' => __("Are you sure you want to delete this theme?\n\nClick 'Cancel' to go back, 'OK' to confirm the delete."),
				'adminUrl'      => parse_url(admin_url(), PHP_URL_PATH),
		),
		'pluginl10n' => array(
				'addNew'            => __('Add New Plugin'),
				'search'            => __('Search available plugins'),
				'searchPlaceholder' => __('Search available plugins...'),
				'themesFound'       => __('Number of Plugins found: %d'),
				'noThemesFound'     => __('No plugins found. Try a different search.'),
		),
));

add_thickbox();

if ($display_setting == 'theme') {

		wp_enqueue_script('theme');

		wp_enqueue_script('updates');

		wp_enqueue_script('customize-loader');

} // end if;

wp_enqueue_style('wp-ultimo-plugin-and-theme-manager');

$current_theme_actions = array();

?>
<div id="wpbody" role="main">

	<div id="wpbody-content">

		<div class="wrap">

			<h1><?php esc_html_e( 'Plugins' ); ?>

				<span class="title-count theme-count"><?php echo count($plugins); ?></span>

			</h1>

			<div class="wp-filter">

				<ul class="filter-links">

					<li>

						<a href="#" class="current" data-category=""><?php _e('All Plugins'); ?></a>

					</li>

					<li>

						<a href="#" class="" data-category="active"><?php _e('Active'); ?></a>

					</li>

					<li class="selector-inactive">

						<a href="#" data-category="inactive"><?php _e('Inactive'); ?></a>

					</li>

					<?php if ($categories) : ?>

						<?php foreach ($categories as $slug => $category) : ?>

							<li>

								<a href="?s=<?php echo $slug; ?>" class="" data-category="<?php echo $slug; ?>"><?php echo $category; ?></a>

							</li>

						<?php endforeach; ?>

					<?php endif; ?>

				</ul>

			</div>

			<?php if (isset($_GET['activate'])) : ?>

				<div id="message2" class="updated notice is-dismissible">

					<p>

						<?php _e('Plugin activated successfully!', 'wp-ultimo-plugin-and-theme-manager' ); ?>

					</p>

				</div>

			<?php elseif (isset($_GET['deactivate'])) : ?>

				<div id="message2" class="updated notice is-dismissible">

					<p>

						<?php _e('Plugin deactivated successfully!', 'wp-ultimo-plugin-and-theme-manager'); ?>

					</p>

				</div>

			<?php endif; ?>

		<?php
		/**
		 * Display as Plugin
		 */
		if ($display_setting == 'theme') {

			WP_Ultimo_Plugin_And_Theme_Manager()->helper->render('base/templates/theme-template', compact('plugins'));

			WP_Ultimo_Plugin_And_Theme_Manager()->helper->render('base/templates/details-template', compact('current_theme_actions'));

		} else {

			WP_Ultimo_Plugin_And_Theme_Manager()->helper->render('base/templates/plugin-template', compact('plugins'));

		} // end if;

		wp_print_request_filesystem_credentials_modal();

		wp_print_admin_notice_templates();

		wp_print_update_row_templates();

		wp_localize_script('updates', '_wpUpdatesItemCounts', array(
				'totals'  => wp_get_update_data(),
		));

		require(ABSPATH.'wp-admin/admin-footer.php');

	exit;
