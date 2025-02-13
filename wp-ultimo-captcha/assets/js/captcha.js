/* eslint-disable no-unused-vars */
/* eslint-disable max-len */
/* global wu_captcha, grecaptcha */
(function($) {

  if (wu_captcha.recaptcha_version_type === 'v2_checkbox') {

    $('.wu_submit_button').before('<p><div class="g-recaptcha" data-theme="' + wu_captcha.recaptcha_theme + '" data-size="' + wu_captcha.recaptcha_display + '" data-sitekey="' + wu_captcha.recaptcha_site_key + '"></div></p>');

  } else if (wu_captcha.recaptcha_version_type === 'v2_invisible') {

    $('#checkout-btn').attr('data-sitekey', wu_captcha.recaptcha_site_key);

    $('#checkout-btn').attr('data-callback', 'execute_invisible_recaptcha');

    //$('#checkout-btn').attr('data-badge', 'inline');

    $('#checkout-btn').addClass('g-recaptcha');

  } else if (wu_captcha.recaptcha_version_type === 'v3_recaptcha') {

    grecaptcha.ready(function() {

      grecaptcha.execute(wu_captcha.recaptcha_site_key, { action: 'execute_invisible_recaptcha' }).then(function(token) {

        $('#wu_form').prepend('<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="' + token + '">');

      });

    });

  } else if (wu_captcha.recaptcha_version_type === 'hcaptcha_invisible') {

    $('.wu_submit_button').before('<script type="application/javascript" src="https://hcaptcha.com/1/api.js" async defer></script>');

    $('#checkout-btn').attr('data-sitekey', wu_captcha.recaptcha_site_key);

    $('#checkout-btn').attr('data-callback', 'execute_invisible_recaptcha');

    $('#checkout-btn').addClass('h-captcha');

  } else if (wu_captcha.recaptcha_version_type === 'hcaptcha') {

    $('.wu_submit_button').before('<script type="application/javascript" src="https://hcaptcha.com/1/api.js" async defer></script>');

    $('.wu_submit_button').before('<div class="h-captcha" data-theme="' + wu_captcha.recaptcha_theme + '" data-size="' + wu_captcha.recaptcha_display + '" data-sitekey="' + wu_captcha.recaptcha_site_key + '"></div>');

  } // end if;

}(jQuery));

function execute_invisible_recaptcha(token) {

}
