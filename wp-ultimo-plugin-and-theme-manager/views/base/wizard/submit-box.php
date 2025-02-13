<?php
/**
 * Submit Box.
 *
 * @since 2.0.0
 */
?>

<!-- Submit Box -->
<div class="wp_ultimo_ptm_flex wp_ultimo_ptm_justify-between wp_ultimo_ptm_bg-gray-100 wp_ultimo_ptm_-m-in wp_ultimo_ptm_mt-4 wp_ultimo_ptm_p-4 wp_ultimo_ptm_overflow-hidden wp_ultimo_ptm_border-t wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b-0 wp_ultimo_ptm_border-gray-300">

  <a href="<?php echo esc_url($page->get_prev_section_link()); ?>" class="wp_ultimo_ptm_self-center button button-large wp_ultimo_ptm_float-left">
    <?php _e('&larr; Go Back', 'wp-ultimo-plugin-and-theme-manager'); ?>
  </a>

  <span class="wp_ultimo_ptm_self-center wp_ultimo_ptm_content-center wp_ultimo_ptm_flex">

    <button name="submit" value="1" class="button button-primary button-large">
      <?php _e('Continue', 'wp-ultimo-plugin-and-theme-manager'); ?>
    </button>

  </span>

</div>
<!-- End Submit Box -->
