<?php
/**
 * Done view.
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

<!-- Submit Box -->
<div class="wp_ultimo_ptm_flex wp_ultimo_ptm_justify-between wp_ultimo_ptm_bg-gray-100 wp_ultimo_ptm_-m-in wp_ultimo_ptm_mt-4 wp_ultimo_ptm_p-4 wp_ultimo_ptm_overflow-hidden wp_ultimo_ptm_border-t wp_ultimo_ptm_border-solid wp_ultimo_ptm_border-l-0 wp_ultimo_ptm_border-r-0 wp_ultimo_ptm_border-b-0 wp_ultimo_ptm_border-gray-300">

  <a href="<?php echo esc_url(admin_url('index.php')); ?>" class="wp_ultimo_ptm_self-center button button-large wp_ultimo_ptm_float-left">

		<?php _e('&larr; Back', 'wp-ultimo-plugin-and-theme-manager'); ?>

	</a>

</div>
<!-- End Submit Box -->
