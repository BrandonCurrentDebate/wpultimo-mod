<?php
/**
 * List view.
 *
 * @since 2.0.0
 */
?>

<div id="wp-ultimo-wrap" class="wrap wp_ultimo_ptm_wrap <?php echo esc_attr($classes); ?>">

  <h1 class="wp-heading-inline">

    <?php echo $page->get_title(); ?>

    <?php
    /**
     * You can filter the get_title_link using wp_ultimo_ptm_page_list_get_title_link, see class-wp_ultimo_ptm_page-list.php
     *
     * @since 1.8.2
     */
    foreach ($page->get_title_links() as $action_link) : ?>

      <a href="<?php echo esc_url($action_link['url']); ?>" class="page-title-action">

        <?php if ($action_link['icon']) : ?>

          <span class="dashicons dashicons-<?php echo esc_attr($action_link['icon']); ?> wp_ultimo_ptm_text-sm wp_ultimo_ptm_align-middle wp_ultimo_ptm_h-4 wp_ultimo_ptm_w-4">
            &nbsp;
          </span>

        <?php endif; ?>

        <?php echo $action_link['label']; ?>

      </a>

    <?php endforeach; ?>

    <?php
    /**
     * Allow plugin developers to add aditional buttons to list pages
     *
     * @since 1.8.2
     * @param WU_Page WP Ultimo Page instance
     */
    do_action('wp_ultimo_ptm_page_list_after_title', $page);
    ?>

  </h1>

  <?php if (isset($_GET['deleted'])) : ?>
    <div id="message" class="updated notice notice-success is-dismissible below-h2">
      <p><?php echo $page->labels['deleted_message']; ?></p>
    </div>
  <?php endif; ?>

  <hr class="wp-header-end">

  <div id="poststuff">

    <div id="post-body" class="">

      <div id="post-body-content">

        <div class="">

          <?php $table->prepare_items(); ?>

          <?php $table->filters(); ?>

          <form id="posts-filter" method="post">

            <input type="hidden" name="page" value="<?php echo $page->get_id(); ?>">

            <?php $table->display(); ?>

          </form>

        </div>
        <!-- /ui-sortable -->

      </div>
      <!-- /post-body-content -->

    </div>
    <!-- /post-body -->

    <br class="clear">

  </div>
  <!-- /poststuff -->

  <?php
  /**
   * Allow plugin developers to add scripts to the bottom of the page
   *
   * @since 1.8.2
   * @param WU_Page WP Ultimo Page instance
   */
  do_action('wp_ultimo_ptm_page_list_footer', $page);
  ?>

</div>
