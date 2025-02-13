<?php
/**
 * Wizard page.
 *
 * @since 2.0.0
 */
?>

<div id="wp-ultimo-wrap" class="wrap wp_ultimo_ptm_wrap <?php echo esc_attr($classes); ?>">

  <h1 class="wp-heading-inline">

    <?php echo $page->get_title(); ?>

    <?php
    /**
     * Allow plugin developers to add aditional buttons to list pages
     *
     * @since 1.8.2
     * @param WU_Page WP Ultimo Page instance
     */
    do_action('wp_ultimo_ptm_page_wizard_after_title', $page);
    ?>

  </h1>

	<?php if (isset($_GET['deleted'])) : ?>
    <div id="message" class="updated notice notice-success is-dismissible below-h2">
      <p><?php echo $page->labels['deleted_message']; ?></p>
    </div>
	<?php endif; ?>

  <hr class="wp-header-end">

  <div id="poststuff" class="wp_ultimo_ptm_flex">

    <div class="wp_ultimo_ptm_w-2/12">

      <!-- Navigator -->
      <ul class="wp_ultimo_ptm_mt-8">

        <?php

        /**
         * We need to set a couple of flags in here to control clickable navigation elements.
         * This flag makes sure only steps the user already went through are clickable.
         */
        $is_pre_current_section = true;

        ?>

        <?php foreach ($sections as $section_name => $section) : ?>

			<?php

			/**
			 * Updates the flag after the current section is looped.
			 */
			if ($current_section === $section_name) {

				$is_pre_current_section = false;

			} // end if;

			?>

          <!-- Menu Item -->
          <li class="wp_ultimo_ptm_sticky">

            <!-- Menu Link -->
            <a href="<?php echo esc_url($page->get_section_link($section_name)); ?>" class="wp_ultimo_ptm_block wp_ultimo_ptm_py-2 wp_ultimo_ptm_px-4 wp_ultimo_ptm_no-underline wp_ultimo_ptm_text-sm wp_ultimo_ptm_rounded <?php echo !$clickable_navigation && !$is_pre_current_section ? 'wp_ultimo_ptm_pointer-events-none' : ''; ?> <?php echo $current_section === $section_name ? 'wp_ultimo_ptm_bg-gray-300 wp_ultimo_ptm_text-gray-800' : 'wp_ultimo_ptm_text-gray-600 hover:wp_ultimo_ptm_text-gray-700'; ?>">
              <?php echo $section['title']; ?>
            </a>
            <!-- End Menu Link -->

            <?php if (!empty($section['sub-sections'])) : ?>

              <!-- Sub-menu -->
              <ul class="classes">

                <?php foreach ($section['sub-sections'] as $sub_section_name => $sub_section) : ?>

                  <li class="classes">
                    <a href="#" class="wp_ultimo_ptm_block wp_ultimo_ptm_py-2 wp_ultimo_ptm_px-4 wp_ultimo_ptm_no-underline wp_ultimo_ptm_text-gray-500 hover:wp_ultimo_ptm_text-gray-600 wp_ultimo_ptm_text-sm">
                      &rarr; <?php echo $sub_section['title']; ?>
                    </a>
                  </li>

                <?php endforeach; ?>

              </ul>
              <!-- End Sub-menu -->

            <?php endif; ?>

          </li>
          <!-- End Menu Item -->

        <?php endforeach; ?>

      </ul>
      <!-- End Navigator -->

    </div>

    <div class="wp_ultimo_ptm_w-8/12 wp_ultimo_ptm_px-4 metabox-holder">

      <form method="post">

        <?php
        /**
         * Print Side Metaboxes
         *
         * Allow plugin developers to add new metaboxes
         *
         * @since 1.8.2
         * @param object Object being edited right now
         */
        do_meta_boxes($screen->id, 'normal', false);
        ?>

        <?php wp_nonce_field(sprintf('saving_%s', $current_section), sprintf('saving_%s', $current_section), false); ?>

        <?php wp_nonce_field(sprintf('saving_%s', $current_section), '_wpultimo_nonce'); ?>

      </form>

    </div>

  </div>

	<?php
	/**
	 * Allow plugin developers to add scripts to the bottom of the page
	 *
	 * @since 1.8.2
	 * @param WU_Page WP Ultimo Page instance
	 */
	do_action('wp_ultimo_ptm_page_wizard_footer', $page);
	?>

</div>
