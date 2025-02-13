<?php
/**
 * WooCommerce Gateway.
 *
 * Base Gateway class. Should be extended to add new payment gateways.
 *
 * @package WP_Ultimo
 * @subpackage Managers/Site_Manager
 * @since 2.0.0
 */

namespace WP_Ultimo_WooCommerce\Gateways;

use \WP_Ultimo\Gateways\Base_Gateway;
use \WP_Ultimo\Database\Payments\Payment_Status;
use \WP_Ultimo\Database\Memberships\Membership_Status;
use \WP_Ultimo\Checkout\Cart;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Base Gateway class. Should be extended to add new payment gateways.
 *
 * @since 2.0.0
 */
class WooCommerce_Gateway extends Base_Gateway {

	/**
	 * Holds the ID of a given gateway.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	protected $id = 'woocommerce';

	/**
	 * Initialize the gateway configuration
	 *
	 * This is used to populate the $supports property, setup any API keys, and set the API endpoint.
	 *
	 * @access public
	 * @return void
	 */
	public function init() {} // end init;

	/**
	 * Adds additional hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function hooks() {

		add_action('load-post.php', array($this, 'add_wp_ultimo_link'));

		add_action('woocommerce_order_status_completed', array($this, 'on_order_completed'), 10, 1);

		add_filter('woocommerce_payment_complete_order_status', array($this, 'force_completed_status'), 10, 2);

		add_filter('woocommerce_prevent_admin_access', array($this, 'always_allow_admin_access'), 1);

		add_action('woocommerce_thankyou', array($this, 'maybe_redirect_to_thank_you'));

		/*
		 * Modify the WooCommerce cart
		 * - Removes unnecessary fields, such as billing fields;
		 * - Remove notes;
		 * - Pre-fill other important fields;
		 */
		add_filter('woocommerce_checkout_fields', array($this, 'clean_checkout_fields'));

		add_filter('woocommerce_checkout_get_value', array($this, 'pre_fill_select_fields'), 10, 2);

		/*
		 * There are hooks that are mutually exclusive,
		 * meaning, we only load then for vanilla woo
		 * or woo subscriptions.
     *
		 * For better readability, separate them into
		 * two different methods.
		 */
		if ($this->should_use_subscriptions()) {

			$this->woo_subscription_hooks();

		} else {

			$this->woo_hooks();

		} // end if;

	} // end hooks;

	/**
	 * Adds vanilla Woo-specific hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	protected function woo_hooks() {

		add_action('woocommerce_checkout_order_created', array($this, 'process_order_created'), 100);

	} // end woo_hooks;

	/**
	 * Adds WooSubscriptions-specific hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	protected function woo_subscription_hooks() {

		add_action('woocommerce_checkout_subscription_created', array($this, 'process_subscription_created'), 100, 3);

		add_filter('wcs_renewal_order_created', array($this, 'create_wu_payment_on_renew'), 10, 2);

	} // end woo_subscription_hooks;

	/**
	 * Remove unnecessary fields when dealing with a Ultimo cart.
	 *
	 * @since 2.0.0
	 *
	 * @param array $fields The cart fields.
	 * @return array
	 */
	public function clean_checkout_fields($fields) {

		$is_wp_ultimo_cart = WC()->session->get('is_wp_ultimo_cart');

		if (!$is_wp_ultimo_cart) {

			return $fields;

		} // end if;

		$only_virtual = true;

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

			if (!$cart_item['data']->is_virtual()) {

				$only_virtual = false;

			} // end if;

		} // end foreach;

		if ($only_virtual) {

			$customer = wu_get_current_customer();

			if (!$customer) {

				return $fields;

			} // end if;

			$billing_address = $customer->get_billing_address();

			/*
			 * Maps keys between WooCommerce and WP Ultimo
			 */
			$key_values = array(
				'billing_first_name' => $customer->get_user()->first_name,
				'billing_last_name'  => $customer->get_user()->last_name,
				'billing_company'    => $billing_address->company_name,
				'billing_country'    => $billing_address->billing_country,
				'billing_address_1'  => $billing_address->billing_address_line_1,
				'billing_address_2'  => $billing_address->billing_address_line_2,
				'billing_city'       => $billing_address->billing_city,
				'billing_state'      => $billing_address->billing_state,
				'billing_postcode'   => $billing_address->billing_zip_code,
				'billing_email'      => $billing_address->billing_email,
				'billing_phone'      => '',
			);

			/**
			 * Allows developers to filter default WooCommerce billing address fields.
			 *
			 * Since WP Ultimo has billing address fields, it might make sense to
			 * remove the default fields displayed by WooCommerce on the checkout
			 * form. By default, no value is passed to this list, so all fields
			 * configured on WooCommerce to be displayed are kept.
			 *
			 * @since 2.0.0
			 * @return array The list of fields to remove. Available values include billing_first_name,
			 * billing_last_name, billing_company, billing_country, billing_address_1,
			 * billing_address_2, billing_city, billing_state, billing_postcode,
			 * billing_email, and billing_phone.
			 */
			$fields_to_clean = apply_filters('wp_ultimo_woocommerce_checkout_fields_to_clean', array());

			foreach ($fields_to_clean as $field_to_clean) {

				if (!wu_get_isset($key_values, $field_to_clean)) {

					unset($fields['billing'][$field_to_clean]);

				} // end if;

			} // end foreach;

			foreach ($fields['billing'] as $field_index => &$field) {

				if (wu_get_isset($key_values, $field_index)) {

					$field['default'] = $key_values[$field_index];

				} // end if;

			} // end foreach;

			add_filter('woocommerce_enable_order_notes_field', '__return_false');

		} // end if;

		return $fields;

	} // end clean_checkout_fields;

	/**
	 * Pre-fill the select fields based on the customer data.
	 *
	 * @since 2.0.0
	 *
	 * @param string $input Default value being passed.
	 * @param string $key The field key.
	 * @return string
	 */
	public function pre_fill_select_fields($input, $key) {

		if ($input) {

			return $input;

		} // end if;

		$fields_to_pre_fill = array(
			'billing_country',
			'billing_state',
		);

		if (!in_array($key, $fields_to_pre_fill, true)) {

			return $input;

		} // end if;

		$customer = wu_get_current_customer();

		if (!$customer) {

			return $input;

		} // end if;

		$billing_address = $customer->get_billing_address();

		return $billing_address->{$key};

	} // end pre_fill_select_fields;

	/**
	 * Decides if we should use WooCommerce subscriptions or not.
	 *
	 * @since 2.0.0
	 * @return boolean
	 */
	public function should_use_subscriptions() {

		$is_woo_subs_available = wuc_is_woocommerce_subscriptions_active();

		$is_woo_subs_enabled = wu_get_setting('enable_woocommerce_subscription_integration', false);

		/**
		 * Allow developers to force the use of WooCommerce Subscriptions.
		 *
		 * By default the add-on checks if (1) WooCommerce Subscriptions is present,
		 * then (2) checks the setting enabling WooCommerce Subscriptions usage.
		 *
		 * @see "inc/functions/woo.php" wuc_is_woocommerce_subscriptions_active()
		 *
		 * @param bool $enable_woocommerce_subscription_integration If we currently need to use Woo Subs.
		 * @param \WP_Ultimo_WooCommerce\Gateways\WooCommerce_Gateway $woo_gateway The current object.
		 *
		 * @return bool true to use WooCommerce Subs, false to not use it.
		 */
		return apply_filters('wp_ultimo_woocommerce_should_use_subscriptions', $is_woo_subs_available && $is_woo_subs_enabled, $this);

	} // end should_use_subscriptions;

	/**
	 * Declares support to recurring payments.
	 *
	 * Always returns false to make sure we delegate this kind of
	 * decision to WooCommerce.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function supports_recurring() {

		return false;

	} // end supports_recurring;

	/**
	 * Allows Gateways to override the gateway title.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_public_title() {

		$gateway_id = wu_replace_dashes($this->id);

		return wu_get_setting("{$gateway_id}_public_title", __('Other Payment Methods', 'wp-ultimo'));

	} // end get_public_title;

	/**
	 * Adds the Stripe Gateway settings to the settings screen.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function settings() {

		wu_register_settings_field('payment-gateways', 'woocommerce_header', array(
			'title'           => __('WooCommerce', 'wp-ultimo'),
			'desc'            => __('Use the settings section below to configure WooCommerce as a payment method.', 'wp-ultimo'),
			'type'            => 'header',
			'show_as_submenu' => true,
			'require'         => array(
				'active_gateways' => 'woocommerce',
			),
		));

		wu_register_settings_field('payment-gateways', 'woocommerce_public_title', array(
			'title'   => __('WooCommerce Public Name', 'wp-ultimo'),
			'tooltip' => __('The name to display on the payment method selection field. By default, "Other Payment Methods" is used.', 'wp-ultimo'),
			'type'    => 'text',
			'default' => __('Other Payment Methods', 'wp-ultimo'),
			'require' => array(
				'active_gateways' => 'woocommerce',
			),
		));

		wu_register_settings_field('payment-gateways', 'woocommerce_message', array(
			'title'   => __('WooCommerce Message', 'wp-ultimo'),
			'tooltip' => __('This will be displayed to the customer on the checkout form, above the submit button.', 'wp-ultimo'),
			'type'    => 'wp_editor',
			'default' => __('You\'ll be able to select a payment method and enter your payment details on the next page.', 'wp-ultimo'),
			'require' => array(
				'active_gateways' => 'woocommerce',
			),
		));

		/*
		 * If WooSubs is available
		 * add the option to use it.
		 */
		if (wuc_is_woocommerce_subscriptions_active()) {

			wu_register_settings_field('payment-gateways', 'enable_woocommerce_subscription_integration', array(
				'title'   => __('Enable WooCommerce Subscription Integration', 'wp-ultimo-woocommerce'),
				'desc'    => __('If you enable this option, WP Ultimo will use WooCommerce Subscriptions to handle billing instead of using plain WooCommerce Orders.', 'wp-ultimo-woocommerce'),
				'type'    => 'toggle',
				'default' => true,
				'require' => array(
					'active_gateways' => 'woocommerce',
				),
			));

		} // end if;

	} // end settings;

	/**
	 * May create a Woo Product, if needed.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Checkout\Line_Item $line_item The line item from WP Ultimo.
	 * @return \WC_Product
	 */
	protected function maybe_create_product($line_item) {

		switch_to_blog(wu_get_main_site_id());

		$product_slug = $this->get_plan_product_slug($line_item->get_title(), $line_item->get_total(), $this->order->get_duration(), $this->order->get_duration_unit());

		/*
		 * Fetches the WP Ultimo product.
		 */
		$wu_product = $line_item->get_product();

		$product = new \WC_Product();

		/*
		 * Account for Woo Subscriptions support
		 *
		 * Check if WooSubs is active, and if this product should be
		 * recurring.
		 */
		if ($this->should_use_subscriptions() && $line_item->is_recurring()) {

			$product = new \WC_Product_Subscription();

		} // end if;

		$existing = get_page_by_path($product_slug, OBJECT, 'product');

		if ($existing) {

			$product = wc_get_product($existing->ID);

		} // end if;

		/**
		 * Filter the parameters used to create the line items on the
		 * WooCommerce Cart, before redirecting the customer
		 * to the WooCommerce checkout.
		 *
		 * @since 2.0.0
		 *
		 * @param array $line_item_params The cart line item parameters.
		 * @param \WP_Ultimo\Checkout\Line_Item $wu_line_item The WP Ultimo line item instance.
		 * @param \WC_Product $wc_product The WooCommerce product.
		 *
		 * @return array The modified line item parameters.
		 */
		$line_item_params = apply_filters('wu_woocommerce_line_item_params', array(
			'name'               => $line_item->get_title(),
			'description'        => $line_item->get_description(),
			'price'              => $line_item->get_total(),
			'regular_price'      => $line_item->get_total(),
			'catalog_visibility' => 'hidden',
			'virtual'            => 'yes',
			'downloadable'       => 'no',
			'taxable'            => $line_item->is_taxable(),
			'slug'               => $product_slug,
		), $line_item, $product);

		$product->set_props($line_item_params);

		if (!$product->exists()) {

			$id = $product->save();

		} else {

			$id = $product->get_id();

		} // end if;

		if ($id) {

			if ($wu_product) {

				$thumbnail_id = $wu_product->get_featured_image_id();

				update_post_meta($id, '_thumbnail_id', $thumbnail_id);

			} // end if;

			/*
			 * Adds recurring related stuff.
			 */
			if ($this->should_use_subscriptions()) {

				// Subscription Data
				update_post_meta($id, '_subscription_period_interval', $line_item->get_duration(), true);
				update_post_meta($id, '_subscription_period', $line_item->get_duration_unit());

			} // end if;

		} // end if;

		restore_current_blog();

		return $product;

	} // end maybe_create_product;

	/**
	 * Creates a WooCommerce Cart based on a WP Ultimo cart.
	 *
	 * @since 2.0.0
	 * @param \WP_Ultimo\Checkout\Cart $cart The cart object.
	 * @return bool
	 */
	protected function create_woocommerce_cart($cart) {

		if (!WC()->cart) {

			wc_load_cart();

		} // end if;

		WC()->cart->empty_cart();

		foreach ($cart->get_line_items() as $line_item) {

			$product = $this->maybe_create_product($line_item);

			$cart_item_key = WC()->cart->add_to_cart($product->get_id(), $line_item->get_quantity());

		} // end foreach;

		return (bool) $cart_item_key;

	}  // end create_woocommerce_cart;

	/**
	 * Process a checkout.
	 *
	 * It takes the data concerning
	 * a new checkout and process it.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Payment    $payment The payment associated with the checkout.
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @param \WP_Ultimo\Checkout\Cart     $cart The cart object.
	 * @param string                       $type The checkout type. Can be 'new', 'retry', 'upgrade', 'downgrade', 'addon'.
	 * @return void
	 */
	public function process_checkout($payment, $membership, $customer, $cart, $type) {

		switch_to_blog(wu_get_main_site_id());

		wuc_load_dependencies();

		wuc_load_wc_subscriptions_dependencies();

		$cart_key = $this->create_woocommerce_cart($cart);

		if (!$cart_key) {

			wc_add_notice(__('There was an error while processing your subscription purchase. Please, contact the administrator.', 'wp-ultimo-woocommerce'), 'error');

		} // end if;

		$checkout_url = wc_get_checkout_url();

		WC()->session->set('is_wp_ultimo_cart', true);
		WC()->session->set('payment_id', $payment->get_id());
		WC()->session->set('membership_id', $membership->get_id());

		if ($type === 'downgrade') {
			/*
			 * When downgrading, we need to schedule a swap for the end of the
			 * current expiration date.
			 */
			$membership->schedule_swap($cart);

			/*
			 * Mark the membership as pending, as we need to
			 * wait for the payment confirmation.
			 */
			$membership->set_status(Membership_Status::ON_HOLD);

			/*
			 * Saves the membership with the changes.
			 */
			$status = $membership->save();

		} elseif ($type === 'upgrade' || $type === 'addon') {
			/*
			* After everything is said and done,
			* we need to swap the membership to the new products
			* (plans and addons), and save it.
			*
			* The membership swap method takes in a Cart object
			* and handled all the changes we need to make to the
			* membership.
			*
			* It updates the products, the recurring status,
			* the initial and recurring amounts, etc.
			*
			* It doesn't save the membership, though, so
			* you'll have to do that manually (example below).
			*/
			$membership->swap($cart);

			/*
			 * Mark the membership as pending, as we need to
			 * wait for the payment confirmation.
			 */
			$membership->set_status(Membership_Status::ON_HOLD);

			/*
			 * Saves the membership with the changes.
			 */
			$status = $membership->save();

		} // end if;

		wp_redirect($checkout_url);

		exit;

	} // end process_checkout;

	/**
	 * Process a cancellation.
	 *
	 * It takes the data concerning
	 * a membership cancellation and process it.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @return void|bool
	 */
	public function process_cancellation($membership, $customer) {

		if (!$this->should_use_subscriptions()) {

			return;

		} // end if;

		$subscription_id = $membership->get_gateway_subscription_id();

		if (!empty($subscription_id)) {

			$subscription = wcs_get_subscription( $subscription_id );

			// Run all cancellation related functions on the subscription
			if (!$subscription->has_status(array('cancelled', 'expired', 'trash'))) {

				$subscription->update_status( 'cancelled' );

			} // end if;

			wp_delete_post( $subscription_id, true );

		} // end if;

	} // end process_cancellation;

	/**
	 * Process a checkout.
	 *
	 * It takes the data concerning
	 * a refund and process it.
	 *
	 * @since 2.0.0
	 *
	 * @throws \Exception                  When something goes wrong.
	 *
	 * @param float                        $amount The amount to refund.
	 * @param \WP_Ultimo\Models\Payment    $payment The payment associated with the checkout.
	 * @param \WP_Ultimo\Models\Membership $membership The membership.
	 * @param \WP_Ultimo\Models\Customer   $customer The customer checking out.
	 * @return void|bool
	 */
	public function process_refund($amount, $payment, $membership, $customer) {

		$order_id = $payment->get_gateway_payment_id();

		$order = wc_get_order($order_id);

    	// If it's something else such as a WC_Order_Refund, we don't want that.
		if (!is_a($order, 'WC_Order')) {

			return new \WP_Error('wc-order', __( 'Provided ID is not a WC Order', 'wp-ultimo-woocommerce'));

		} // end if;

		if ('refunded' === $order->get_status()) {

			return new \WP_Error('wc-order', __( 'Order has been already refunded', 'wp-ultimo-woocommerce'));

		} // end if;

    	// Get Items
		$order_items = $order->get_items();

    	// Refund Amount
		$refund_amount = 0;

    	// Prepare line items which we are refunding
		$line_items = array();

		if ($order_items) {

			foreach ($order_items as $item_id => $item) {

				$tax_data = $item_meta['_line_tax_data'];

				$refund_tax = 0;

				if (is_array($tax_data[0])) {

					$refund_tax = array_map('wc_format_decimal', $tax_data[0]);

				} // end if;

				$refund_amount = wc_format_decimal($refund_amount) + wc_format_decimal($item_meta['_line_total'][0]);

				$line_items[$item_id] = array(
					'qty'          => $item_meta['_qty'][0],
					'refund_total' => wc_format_decimal($item_meta['_line_total'][0]),
					'refund_tax'   => $refund_tax,
				);

			} // end foreach;

		} // end if;

    	// Check for partial Refund
		if ($amount === $refund_amount) {

			$final_refund_amount = $refund_amount;
			$line_items          = $line_items;

		} else {

			$final_refund_amount = $amount;
			$line_items          = array();

		} // end if;

		$refund = wc_create_refund(array(
			'amount'         => $final_refund_amount,
			'reason'         => $refund_reason,
			'order_id'       => $order_id,
			'line_items'     => $line_items,
			'refund_payment' => true
		));

		$status = $payment->refund($amount);

		if (is_wp_error($status)) {

			throw new \Exception($status->get_error_code(), $status->get_error_message());

		} // end if;

		return $refund;

	} // end process_refund;

	/**
	 * Adds additional fields to the checkout form for a particular gateway.
	 *
	 * In this method, you can either return an array of fields (that we will display
	 * using our form display methods) or you can return plain HTML in a string,
	 * which will get outputted to the gateway section of the checkout.
	 *
	 * @since 2.0.0
	 * @return array|string
	 */
	public function fields() {

		$message = wu_get_setting('woocommerce_message', __('You\'ll be able to select a payment method and enter your payment details on the next page.', 'wp-ultimo-woocommerce'));

		return sprintf('<p class="wu-p-4 wu-bg-yellow-200">%s</p>', $message);

	} // end fields;

	/**
	 * Process PayFast IPN
	 *
	 * Listen for webhooks and take appropriate action to insert payments, renew the member's
	 * account, or cancel the membership.
	 *
	 * @access public
	 * @return void
	 */
	public function process_webhooks() {} // end process_webhooks;

	/**
	 * Returns the plan slug, based on the plan attributes.
	 *
	 * @since 2.0.0
	 *
	 * @param string    $plan_name The plan name.
	 * @param string    $plan_amount The plan price.
	 * @param string    $plan_duration The plan duration.
	 * @param string    $plan_duration_unit The plan duration unit.
	 * @param null|bool $is_sub If the product is a subscription.
	 * @return string
	 */
	public function get_plan_product_slug($plan_name, $plan_amount, $plan_duration, $plan_duration_unit, $is_sub = null) {

		if ($is_sub === null) {

			$is_sub = $this->should_use_subscriptions();

		} // end if;

		$sub = $is_sub ? 'sub' : 'simple';

		return sprintf('%s-%s-%s-%s-%s', $plan_name, $plan_amount, $plan_duration, $plan_duration_unit, $sub);

	} // end get_plan_product_slug;

	/**
	 * Adds the WP Ultimo link to the WooCommerce edit order page.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function add_wp_ultimo_link() {

		add_action('admin_head', function() {

			$post_id = wu_request('post');

			if ($post_id && get_post_meta($post_id, '_is_wp_ultimo', true)) {

				printf('<a id="wp-ultimo-link" href="%s" style="display: none;" class="page-title-action" target="_blank">%s</a>', network_admin_url('admin.php?page=wp-ultimo-edit-membership&id=' . get_post_meta($post_id, '_membership_id', true)), __('Go to the Membership on WP Ultimo', 'wp-ultimo-woocommerce'));

				echo "<script>(function($) {
          $(document).ready(function() {
            $('#wp-ultimo-link').insertAfter('.wp-heading-inline').show();
          });
        })(jQuery);</script>";

			} // end if;

		});

	} // end add_wp_ultimo_link;

	/**
	 * Runs when a WooCommerce order is marked as completed.
	 *
	 * WP Ultimo listens to this event to make changes to the
	 * correspondent WP Ultimo payment, membership and etc.
	 *
	 * @since 2.0.0
	 *
	 * @param int $order_id The order id.
	 * @return void
	 */
	public function on_order_completed($order_id) {

		global $wpdb;

		switch_to_blog(wu_get_main_site_id());

    	// Only for WP Ultimo orders
		if (get_post_meta($order_id, '_is_wp_ultimo', true)) {

			$payment_id = get_post_meta($order_id, '_payment_id', true);

			$payment = wu_get_payment($payment_id);

			$membership = false;

			if ($payment) {

				$membership = $payment->get_membership();

			} // end if;

			if ($payment && $membership) {

				$payment->set_status(Payment_Status::COMPLETED);

				$payment->save();

				$membership->add_to_times_billed(1);

				$membership->renew($membership->should_auto_renew());

			} // end if;

		} // end if;

		restore_current_blog();

	} // end on_order_completed;

	/**
	 * Forces the completed status for WP Ultimo orders.
	 *
	 * @since 2.0.0
	 *
	 * @param string $status Default status for completed.
	 * @param int    $order_id The order id.
	 * @return string
	 */
	public function force_completed_status($status, $order_id) {

		$force_completed = get_post_meta($order_id, '_is_wp_ultimo', true);

		if ($force_completed === 'yes') {

			return 'completed';

		} // end if;

		return $status;

	} // end force_completed_status;

	/**
	 * Returns the external link to view the membership on the membership gateway.
	 *
	 * Return an empty string to hide the link element.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_subscription_id The gateway subscription id.
	 * @return void|string.
	 */
	public function get_subscription_url_on_gateway($gateway_subscription_id) {} // end get_subscription_url_on_gateway;

	/**
	 * Returns the external link to view the membership on the membership gateway.
	 *
	 * Return an empty string to hide the link element.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_customer_id The gateway customer id.
	 * @return void|string.
	 */
	public function get_customer_url_on_gateway($gateway_customer_id) {} // end get_customer_url_on_gateway;

	/**
	 * Returns the external link to view the payment on the membership gateway.
	 *
	 * Return an empty string to hide the link element.
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_payment_id The gateway payment id.
	 * @return void|string.
	 */
	public function get_payment_url_on_gateway($gateway_payment_id) {

		$site_url = get_admin_url(wu_get_main_site_id(), 'post.php');

		return add_query_arg(array(
			'post'   => $gateway_payment_id,
			'action' => 'edit',
		), $site_url);

	} // end get_payment_url_on_gateway;

	/**
	 * Allow access to the admin access.
	 *
	 * @since 1.2.0
	 * @param bool $access Default access.
	 * @return bool
	 */
	public function always_allow_admin_access($access) {

		if (wp_doing_ajax() || wp_doing_cron() || current_user_can('manage_network')) {

			return $access;

		} // end if;

		if (wu_get_current_site()->get_type() !== 'customer_owned') {

			return $access;

		} // end if;

		$has_subscription = wu_get_current_site()->get_membership();

		return empty($has_subscription);

	} // end always_allow_admin_access;

	/**
	 * Maybe redirects to the WP Ultimo thank you page.
	 *
	 * @since 2.0.0
	 *
	 * @param int $order_id The WooCommerce order ID.
	 * @return void
	 */
	public function maybe_redirect_to_thank_you($order_id) {

		$is_wp_ultimo = get_post_meta($order_id, '_is_wp_ultimo', true);

		if (!$is_wp_ultimo) {

			return;

		} // end if;

		$payment_id = get_post_meta($order_id, '_payment_id', true);

		$payment = wu_get_payment($payment_id);

		$order = wc_get_order($order_id);

		if (!$order->has_status('failed') && $payment) {
			/*
			 * Otherwise, we redirect
			 * to the final step.
			 */
			$redirect_url = wu_get_registration_url();

			$redirect_url = add_query_arg(array(
				'payment' => $payment->get_hash(),
				'status'  => 'done',
			), $redirect_url);

			wp_safe_redirect($redirect_url);

			exit;

		} // end if;

	} // end maybe_redirect_to_thank_you;

	/**
	 * Process the Subscription from WooCommerce after creation.
	 *
	 * @todo add new payment on Ultimo in case of renewals.
	 *
	 * @since 1.2.0
	 * @param WC_Subscription $subscription The woocommerce subscription.
	 * @param WC_Order        $order The woocommerce order.
	 * @param array           $recurring_cart The recurring cart.
	 * @return void
 	 */
	public function process_subscription_created($subscription, $order, $recurring_cart) {

		$is_wp_ultimo_cart = WC()->session->get('is_wp_ultimo_cart');
		$payment_id        = WC()->session->get('payment_id');
		$membership_id     = WC()->session->get('membership_id');

		if (!$is_wp_ultimo_cart) {

			return;

		} // end if;

		$payment    = wu_get_payment($payment_id);
		$membership = wu_get_membership($membership_id);

		if (empty($membership)) {

			return;

		} // end if;

		// Add a simple note to let the admin know that this is a automatic note generate by WP Ultimo
		$subscription->add_order_note(__('Subscription created by WP Ultimo', 'wp-ultimo-woocommerce'));

		$order->add_order_note(__('Subscription created by WP Ultimo', 'wp-ultimo-woocommerce'));

		/*
		 * Update the relevant metadata.
		 */
		update_post_meta($subscription->get_id(), '_is_wp_ultimo', 'yes');
		update_post_meta($subscription->get_id(), '_membership_id', $membership->get_id());

		update_post_meta($order->get_id(), '_is_wp_ultimo', 'yes');
		update_post_meta($order->get_id(), '_payment_id', $payment->get_id());

		// Add the WC Subscription to the WU Subscription
		$membership->set_gateway_subscription_id($subscription->get_id());
		$membership->set_auto_renew(true);
		$membership->save();

		$payment->set_gateway_payment_id($order->get_id());
		$payment->save();

	} // end process_subscription_created;

	/**
	 * Create a payment on WP Ultimo on renew.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Order        $order The WooCommerce order.
	 * @param \WC_Subscription $subscription The subscription object.
	 * @return \WC_Order
	 */
	public function create_wu_payment_on_renew($order, $subscription) {

		$is_wp_ultimo = get_post_meta($order->get_id(), '_is_wp_ultimo', true);

		if (!$is_wp_ultimo) {

			return $order;

		} // end if;

		$membership_id = get_post_meta($subscription->get_id(), '_membership_id', true);

		$membership = wu_get_membership($membership_id);

		if (empty($membership)) {

			return $order;

		} // end if;

		$pending_payment = $membership->get_last_pending_payment();

		/*
		 * Create the new payment
		 */
		$previous_payments = wu_get_payments(array(
			'number'        => 1,
			'membership_id' => $membership->get_id(),
			'status'        => 'completed',
			'orderby'       => 'id',
			'order'         => 'DESC',
		));

		if (empty($previous_payments)) {

			return $order;

		} // end if;

		/*
		 * Change pending payment to cancelled.
		 */
		if ($pending_payment) {

			$pending_payment->set_status(Payment_Status::CANCELLED);
			$pending_payment->save();

		} // end if;

		$previous_payment = $previous_payments[0];

		$new_payment_data = array(
			'status'             => Payment_Status::PENDING,
			'gateway'            => $this->get_id(),
			'total'              => $order->get_total(),
			'gateway_payment_id' => $order->get_id(),
			'membership_id'      => $membership->get_id(),
			'customer_id'        => $membership->get_customer_id(),
		);

		$new_payment = wu_create_payment($new_payment_data);

		update_post_meta($order->get_id(), '_payment_id', $new_payment->get_id());

		/*
		 * Update the membership status.
		 */
		$membership->set_status(Membership_Status::ON_HOLD);
		$membership->save();

		/*
		 * Why save first as pending to later
		 * mark it as completed?
		 *
		 * Well, this way we make sure we trigger
		 * the transition that automatically renews
		 * memberships.
		 */
		if ($order->get_status() === 'completed') {

			$new_payment->set_status(Payment_Status::COMPLETED);
			$new_payment->save();

		} // end if;

		return $order;

	} // end create_wu_payment_on_renew;

	/**
	 * Process the Subscription from WooCommerce after creation.
	 *
	 * @todo add new payment on Ultimo in case of renewals.
	 *
	 * @since 1.2.0
	 * @param WC_Order $order The order object.
	 * @return void
 	 */
	public function process_order_created($order) {

		$is_wp_ultimo_cart = WC()->session->get('is_wp_ultimo_cart');
		$payment_id        = WC()->session->get('payment_id');
		$membership_id     = WC()->session->get('membership_id');

		if (!$is_wp_ultimo_cart) {

			return;

		} // end if;

		$payment    = wu_get_payment($payment_id);
		$membership = wu_get_membership($membership_id);

		if (empty($membership)) {

			return;

		} // end if;

		$order->add_order_note(__('Order created by WP Ultimo', 'wp-ultimo-woocommerce'));

		update_post_meta($order->get_id(), '_is_wp_ultimo', 'yes');
		update_post_meta($order->get_id(), '_payment_id', $payment->get_id());

		$payment->set_gateway_payment_id($order->get_id());
		$payment->save();

	} // end process_order_created;

} // end class WooCommerce_Gateway;
