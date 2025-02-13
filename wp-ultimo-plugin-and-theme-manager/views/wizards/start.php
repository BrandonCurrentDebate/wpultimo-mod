<?php
/**
 * Start view.
 *
 * @since 2.0.0
 */
?>

<h1>

	<?php echo $title; ?>

</h1>

<p class="wp_ultimo_ptm_text-lg wp_ultimo_ptm_text-gray-600 wp_ultimo_ptm_my-4">

	<?php echo $description; ?>

</p>

<div class="wp_ultimo_ptm_bg-white wp_ultimo_ptm_p-4 wp_ultimo_ptm_-mx-6">

    <div>

	    <span class="wp_ultimo_ptm_text-sm wp_ultimo_ptm_text-gray-800 wp_ultimo_ptm_inline-block wp_ultimo_ptm_py-4">

      		<?php _e('This plugin will:', 'wp-ultimo-plugin-and-theme-manager'); ?>

    	</span>

    <ul class="wp_ultimo_ptm_-mx-5 wp_ultimo_ptm_my-0 wp_ultimo_ptm_border-t wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b-0 wp_ultimo_ptm_border-gray-300">

      <?php foreach ($will as $line) : ?>

        <li class="wp_ultimo_ptm_flex wp_ultimo_ptm_content-center wp_ultimo_ptm_py-2 wp_ultimo_ptm_px-4 wp_ultimo_ptm_bg-gray-100 wp_ultimo_ptm_border-t-0 wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b wp_ultimo_ptm_border-gray-300 wp_ultimo_ptm_m-0">

          <span class="dashicons dashicons-yes-alt wp_ultimo_ptm_text-green-400 wp_ultimo_ptm_self-center wp_ultimo_ptm_mr-2"></span>

          <span><?php echo $line; ?></span>

        </li>

      <?php endforeach; ?>

    </ul>

  </div>

  <?php if (!empty($will_not)) : ?>

    <div>

      <span class="wp_ultimo_ptm_text-sm wp_ultimo_ptm_text-gray-800 wp_ultimo_ptm_inline-block wp_ultimo_ptm_py-4">

        <?php _e('This plugin will <strong>not</strong>:', 'wp-ultimo-plugin-and-theme-manager'); ?>

      </span>

      <ul class="wp_ultimo_ptm_-mx-5 wp_ultimo_ptm_my-0 wp_ultimo_ptm_border-t wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b-0 wp_ultimo_ptm_border-gray-300">

        <?php foreach ($will_not as $line) : ?>

          <li class="wp_ultimo_ptm_flex wp_ultimo_ptm_content-center wp_ultimo_ptm_py-2 wp_ultimo_ptm_px-4 wp_ultimo_ptm_bg-gray-100 wp_ultimo_ptm_border-t-0 wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b wp_ultimo_ptm_border-gray-300 wp_ultimo_ptm_m-0">

            <span class="dashicons dashicons-dismiss wp_ultimo_ptm_text-red-400 wp_ultimo_ptm_self-center wp_ultimo_ptm_mr-2"></span>

            <span><?php echo $line; ?></span>

          </li>

        <?php endforeach; ?>

      </ul>

    </div>

  <?php endif; ?>

</div>

<!-- Submit Box -->
<div class="wp_ultimo_ptm_flex wp_ultimo_ptm_justify-between wp_ultimo_ptm_bg-gray-100 wp_ultimo_ptm_-m-in wp_ultimo_ptm_mt-4 wp_ultimo_ptm_p-4 wp_ultimo_ptm_overflow-hidden wp_ultimo_ptm_border-t wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b-0 wp_ultimo_ptm_border-gray-300">

  <a href="<?php echo esc_url(admin_url('index.php')); ?>" class="wp_ultimo_ptm_self-center button button-large wp_ultimo_ptm_float-left"><?php _e('&larr; Cancel', 'wp-ultimo-plugin-and-theme-manager'); ?></a>

  <span class="wp_ultimo_ptm_self-center wp_ultimo_ptm_content-center wp_ultimo_ptm_flex">

    <button name="submit" value="1" class="button button-primary button-large">

      <?php _e('Let\'s Start', 'wp-ultimo-plugin-and-theme-manager'); ?>

    </button>

  </span>

</div>
<!-- End Submit Box -->
