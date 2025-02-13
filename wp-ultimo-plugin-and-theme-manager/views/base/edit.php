<?php
/**
 * Edit view.
 *
 * @since 2.0.0
 */
?>

<div id="wp-ultimo-wrap" class="wrap">

  <h1 class="wp-heading-inline">

    <?php echo $page->edit ? $labels['edit_label'] : $labels['add_new_label']; ?>

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
     * Allow plugin developers to add additional buttons to edit pages
     *
     * @since 1.8.2
     * @param object  Object holding the information
     * @param WU_Page WP Ultimo Page instance
     */
    do_action('wp_ultimo_ptm_page_edit_after_title', $object, $page);
    ?>

  </h1>

  <?php if (isset($_GET['updated'])) : ?>

    <div id="message" class="updated notice notice-success is-dismissible below-h2">
      <p><?php echo $labels['updated_message']; ?></p>
    </div>

  <?php endif; ?>

  <hr class="wp-header-end">

  <form id="form-<?php echo esc_attr($page->get_id()); ?>" name="post" method="post" autocomplete="off">

    <div id="poststuff">

      <div id="post-body" class="metabox-holder columns-2">

        <?php if ($page->has_title()) : ?>

          <div id="post-body-content">

            <div id="titlediv">

              <div id="titlewrap">

                <input placeholder="<?php echo $labels['title_placeholder'] ?>" type="text" name="name" size="30" value="<?php echo method_exists($object, 'get_name') ? esc_attr($object->get_name()) : ''; ?>" id="title" spellcheck="true" autocomplete="off">

                <?php if (!empty($labels['title_description'])) : ?>

                  <span class="description" style="margin-top: 6px; display: block;">
                    <?php echo $labels['title_description']; ?>
                  </span>

                <?php endif; ?>

                <?php
                /**
                 * Allow plugin developers to add additional information below the text input
                 *
                 * @since 1.8.2
                 * @param object  Object holding the information
                 * @param WU_Page WP Ultimo Page instance
                 */
                do_action('wp_ultimo_ptm_edit_page_after_title_input', $object, $page);
                ?>

              </div>

            </div>
            <!-- /titlediv -->

            <?php if ($page->has_editor()) : ?>

            <div class="wp_ultimo_ptm_mt-5">
              <?php wp_editor('lol', 'lol', array(
                'height' => 500,
              )); ?>
            </div>

            <?php endif; ?>

          </div>
          <!-- /post-body-content -->

        <?php endif; ?>

        <div id="postbox-container-1" class="postbox-container">

          <div id="side-sortables" class="meta-box-sortables ui-sortable">

            <?php
            /**
             * Print Side Metaboxes
             *
             * Allow plugin developers to add new metaboxes
             *
             * @since 1.8.2
             * @param object Object being edited right now
             */
            do_meta_boxes($screen->id, 'side', $object);
            ?>

            <?php
            /**
             * Print Side Metaboxes
             *
             * Allow plugin developers to add new metaboxes
             *
             * @since 1.8.2
             * @param object Object being edited right now
             */
            do_meta_boxes($screen->id, 'side-bottom', $object);
            ?>

          </div>
          <!-- /side-sortables -->

        </div>

        <div id="postbox-container-2" class="postbox-container">

          <div id="normal-sortables" class="meta-box-sortables ui-sortable">

            <?php
            /**
             * Print Normal Metaboxes
             *
             * Allow plugin developers to add new metaboxes
             *
             * @since 1.8.2
             * @param object Object being edited right now
             */
            do_meta_boxes($screen->id, 'normal', $object);
            ?>

          </div>
          <!-- /normal-sortables -->

          <div id="advanced-sortables" class="meta-box-sortables ui-sortable">

            <?php
            /**
             * Print Advanced Metaboxes
             *
             * Allow plugin developers to add new metaboxes
             *
             * @since 1.8.2
             * @param object Object being edited right now
             */
            do_meta_boxes($screen->id, 'advanced', $object);
            ?>

          </div>
          <!-- /advanced-sortables -->

        </div>
        <!-- /normal-sortables -->

      </div>
      <!-- /post-body -->

      <br class="clear">

      <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false); ?>

      <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>

      <?php wp_nonce_field(sprintf('saving_%s', $page->object_id), sprintf('saving_%s', $page->object_id), false) ?>

      <?php wp_nonce_field(sprintf('saving_%s', $page->object_id), '_wpultimo_nonce') ?>

      <?php if ($page->edit) : ?>

        <input type="hidden" name="id" value="<?php echo $object->get_id(); ?>">

      <?php endif; ?>

    </div>
    <!-- /poststuff -->

  </form>

  <?php
  /**
   * Allow plugin developers to add scripts to the bottom of the page
   *
   * @since 1.8.2
   * @param object  Object holding the information
   * @param WU_Page WP Ultimo Page instance
   */
  do_action('wp_ultimo_ptm_page_edit_footer', $object, $page);
  ?>

</div>
