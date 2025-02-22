<?php
/**
 * Checkout Form Functions
 *
 * @package WP_Ultimo\Functions
 * @since   2.0.0
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

use \WP_Ultimo\Checkout\Checkout;
use \WP_Ultimo\Models\Checkout_Form;

/**
 * Returns a checkout_form.
 *
 * @since 2.0.0
 *
 * @param int $checkout_form_id The ID of the checkout_form.
 * @return \WP_Ultimo\Models\Checkout_Form|false
 */
function wu_get_checkout_form($checkout_form_id) {

	return \WP_Ultimo\Models\Checkout_Form::get_by_id($checkout_form_id);

} // end wu_get_checkout_form;

/**
 * Queries checkout_forms.
 *
 * @since 2.0.0
 *
 * @param array $query Query arguments.
 * @return \WP_Ultimo\Models\Checkout_Form[]
 */
function wu_get_checkout_forms($query = array()) {

	return \WP_Ultimo\Models\Checkout_Form::query($query);

} // end wu_get_checkout_forms;
/**
 * Returns a checkout_form based on slug.
 *
 * @since 2.0.0
 *
 * @param string $checkout_form_slug The slug of the checkout_form.
 * @return \WP_Ultimo\Models\Checkout_Form|false
 */
function wu_get_checkout_form_by_slug($checkout_form_slug) {

	/**
	 * Fixed case: Upgrade/Downgrade forms.
	 *
	 * In this particular case, the fields are fixed,
	 * although they can be modified via a filter in the
	 * Checkout_Form::membership_change_form_fields() method.
	 *
	 * @see wu_checkout_form_membership_change_form_fields filter.
	 */
	if ($checkout_form_slug === 'wu-checkout') {

		$checkout_form = new \WP_Ultimo\Models\Checkout_Form;

		$checkout_fields = Checkout_Form::membership_change_form_fields();

		$checkout_form->set_settings($checkout_fields);

		return $checkout_form;

	} elseif ($checkout_form_slug === 'wu-add-new-site') {

		$checkout_form = new \WP_Ultimo\Models\Checkout_Form;

		$checkout_fields = Checkout_Form::add_new_site_form_fields();

		$checkout_form->set_settings($checkout_fields);

		return $checkout_form;

	} elseif ($checkout_form_slug === 'wu-finish-checkout') {

		$checkout_form = new \WP_Ultimo\Models\Checkout_Form;

		$checkout_fields = Checkout_Form::finish_checkout_form_fields();

		$checkout_form->set_settings($checkout_fields);

		return $checkout_form;

	} // end if;

	return \WP_Ultimo\Models\Checkout_Form::get_by('slug', $checkout_form_slug);

} // end wu_get_checkout_form_by_slug;
/**
 * Creates a new checkout form.
 *
 * @since 2.0.0
 *
 * @param array $checkout_form_data Checkout_Form data.
 * @return \WP_Error|\WP_Ultimo\Models\Checkout_Form
 */
function wu_create_checkout_form($checkout_form_data) {

	$checkout_form_data = wp_parse_args($checkout_form_data, array(
		'name'              => false,
		'slug'              => false,
		'settings'          => array(),
		'allowed_countries' => array(),
		'date_created'      => wu_get_current_time('mysql', true),
		'date_modified'     => wu_get_current_time('mysql', true),
	));

	$checkout_form = new Checkout_Form($checkout_form_data);

	$saved = $checkout_form->save();

	return is_wp_error($saved) ? $saved : $checkout_form;

} // end wu_create_checkout_form;

/**
 * Returns a list of all the available domain options in all registered forms.
 *
 * @since 2.0.11
 * @return array
 */
function wu_get_available_domain_options() {

	$fields = array();

	$main_form = wu_get_checkout_form_by_slug('main-form');

	if ($main_form) {

		$fields = $main_form->get_all_fields_by_type('site_url');

	} else {

		$forms = wu_get_checkout_forms(array(
			'number' => 1,
		));

		if ($forms) {

			$fields = $forms[0]->get_all_fields_by_type('site_url');

		} // end if;

	} // end if;

	$domain_options = array();

	if ($fields) {

		foreach ($fields as $field) {

			$available_domains = isset($field['available_domains']) ? $field['available_domains'] : '';

			$field_domain_options = explode(PHP_EOL, (string) $available_domains);

			if (isset($field['enable_domain_selection']) && $field['enable_domain_selection'] && $field_domain_options) {

				$domain_options = array_merge($domain_options, $field_domain_options);

			} // end if;

		} // end foreach;

	} // end if;

	return $domain_options;

} // end wu_get_available_domain_options;

/**
 * Check if the field is preselected in request.
 *
 * @since 2.0.19
 * @param string $field_slug Path to attach to the end of the URL.
 * @return bool
 */
function wu_is_form_field_pre_selected($field_slug) {

	$checkout = Checkout::get_instance();

	$pre_selected = $checkout->request_or_session('pre_selected', array());

	$from_request = stripslashes_deep(wu_get_isset($_GET, $field_slug)) || isset($pre_selected[$field_slug]);

	return wu_request('wu_preselected') === $field_slug || $from_request;

} // end wu_is_form_field_pre_selected;

/**
 * Get the request arg slug from a field.
 *
 * @since 2.0.19
 * @param array $field The field from form step.
 * @return string
 */
function wu_form_field_request_arg($field) {

	if ($field['type'] === 'template_selection') {

		return 'template_id';

	} // end if;

	if ($field['type'] === 'pricing_table') {

		return 'products';

	} // end if;

	return $field['id'];

} // end wu_form_field_request_arg;

/**
 * Check if the field should be hidden in form
 *
 * @since 2.0.19
 * @param array $field The field from form step.
 * @return bool
 */
function wu_should_hide_form_field($field) {

	$hide_preselect = (bool) (int) wu_get_isset($field, "hide_{$field['type']}_when_pre_selected");

	return $hide_preselect && wu_is_form_field_pre_selected(wu_form_field_request_arg($field));

} // end wu_should_hide_form_field;
