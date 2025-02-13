<?php
/**
 * Google Recaptcha no keys view.
 *
 * @since 2.0.0
 */
?>
<div class="wu-block wu-w-full wu-mt-4">

	<div class="wu-block wu-bg-red-100 wu-p-4 wu-mb-4 wu-round">

		<span class="wu-font-bold wu-uppercase wu-text-xs"><?php _e('Add your Captcha site keys', 'wp-ultimo-captcha'); ?></span>

		<p>
			<?php printf(__('If you do not have keys already, then visit %s.', 'wp-ultimo-captcha'), '<a href="https://www.google.com/recaptcha/admin">Google reCaptcha</a>'); ?> <?php _e('After adding the correct keys, reload this page and this message should go away.', 'wp-ultimo-captcha'); ?>
		</p>

	</div>

</div>
