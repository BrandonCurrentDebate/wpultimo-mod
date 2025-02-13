<?php
/**
 * WP Ultimo: AffiliateWP Integration manager class.
 *
 * @package WP_Ultimo_AffiliateWP
 * @since 2.0.0
 */

namespace WP_Ultimo_AffiliateWP\Managers;

use AffWP\Referral;

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * This class manages our plugin integration's logic.
 *
 * @since 2.0.0
 */
class AffiliateWP_Manager extends \Affiliate_WP_Base {

	use \WP_Ultimo_AffiliateWP\Traits\Singleton;

	/**
	 * The context for referrals. This refers to the integration that is being used.
	 *
	 * @access  public
	 * @since   1.2
	 * @var string
	 */
	public $context = 'wp-ultimo';

	/**
	 * Setup actions and filters
	 *
	 * @access  public
	 * @since   1.0
	 */
	public function init() {

		// Set the referral on payment update
		add_action('wu_payment_post_save', array($this, 'set_referral'), 10, 2);

		/**
		 * Adds coupon code support to AffiliateWP.
		 *
		 * @since 1.0.1
		 */
		add_filter('wu_discount_code_options_sections', array($this, 'coupon_affiliate'), 10, 2);

		add_action('wu_discount_code_post_save', array($this, 'coupon_affiliate_save'), 10, 2);

		add_filter('affwp_referral_reference_column', array( $this, 'reference_link' ), 10, 2);

	} // end init;

	/**
	 * Runs the check necessary to confirm this plugin is active.
	 *
	 * @since 2.5
	 *
	 * @return bool True if the plugin is active, false otherwise.
	 */
	public function plugin_is_active() {

		return class_exists( 'WP_Ultimo' );

	} // end plugin_is_active;

	/**
	 * Builds the reference link for the referrals table
	 *
	 * @param   string $link Requested URL.
	 * @param   Object $referral Referral parameters.
	 * @access  public
	 * @since   1.0
	 */
	public function reference_link($link = '', $referral) {

		if (empty( $referral->context ) || 'wp-ultimo' !== $referral->context) {

			return $link;

		} // end if;

		if (!empty( $referral->custom)) {

			$url  = network_admin_url('admin.php?page=wu-edit-subscription&user_id=' . $referral->custom);
			$link = '<a href="' . esc_url($url) . '">View WP Ultimo Subscription</a>';

		} // end if;

		if (!empty($referral->reference) && is_numeric($referral->reference)) {

			$url  = network_admin_url('admin.php?page=wu-edit-subscription&user_id=' . $referral->reference);
			$link = '<a href="' . esc_url($url) . '">View WP Ultimo Subscription</a>';

		} // end if;

		return $link;

	} // end reference_link;

	/**
	 * Injects the limitations panels when necessary.
	 *
	 * @since 2.0.0
	 *
	 * @param array                                   $sections List of tabbed widget sections.
	 * @param \WP_Ultimo\Models\Trait\Trait_Limitable $object The model being edited.
	 * @return array
	 */
	public function coupon_affiliate($sections, $object) {

		$sections['affiliatewp'] = array(
			'title'  => __('AffiliateWP', 'wp-ultimo-affiliatewp'),
			'desc'   => __('Only customer-owned sites have limitations.', 'wp-ultimo-affiliatewp'),
			'icon'   => 'dashicons-wu-browser',
			'fields' => array(
				'affiliate' => array(
					'type'    => 'select',
					'title'   => __('AffiliateWP Affiliate', 'wp-ultimo-affiliatewp'),
					'tooltip' => __('Link this coupon to one of your affiliates.', 'wp-ultimo-affiliatewp'),
					'value'   => (int) $object->get_meta('affiliate', 0),
					'options' => function() {

						$affiliates = array(
							'0' => __('Select an Affiliate', 'wp-ultimo-affiliatewp'),
						);

						foreach (affiliate_wp()->affiliates->get_affiliates() as $aff) {

							$user = get_user_by('id', $aff->user_id);

							if (!$user) {

								continue;

							} // end if;

							$affiliates[$aff->affiliate_id] = sprintf('%s (User ID: %s)', $user->user_login, $user->ID);

						} // end foreach;

						return $affiliates;

					}
				),
			),
		);

		return $sections;

	} // end coupon_affiliate;

	/**
	 * Save the discount code affiliate code.
	 *
	 * @since 2.0.0
	 *
	 * @param array                           $data The discount code data.
	 * @param \WP_Ultimo\Models\Discount_Code $discount_code The discount code object.
	 * @return void
	 */
	public function coupon_affiliate_save($data, $discount_code) {

		$discount_code->update_meta('affiliate', wu_request('affiliate', 0));

	} // end coupon_affiliate_save;

	/**
	 * Run on payment save hook to add or change the affiliate referrer.
	 *
	 * @since 2.0.0
	 *
	 * @param array                     $data The object data that will be stored.
	 * @param \WP_Ultimo\Models\Payment $payment The payment instance.
	 */
	public function set_referral($data, $payment) {

		$customer   = $payment->get_customer();
		$membership = $payment->get_membership();

		if (!$membership) {

			return;

		} // end if;

		$user_id = $customer->get_user_id();

		$referral = affwp_get_referral_by('custom', $user_id);

		$user = get_user_by('id', $user_id);

		if (!$referral || is_wp_error($referral)) {
			// Create a new referral
			$visit = affiliate_wp()->tracking->get_visit_id();

			if (!$visit) {

				return;

			} // end if;

			/* translators: name of the user */
			$desc = sprintf(__('Referred user %s.', 'wp-ultimo-affiliatewp'), $customer->get_hash());

			$affiliate_id = affiliate_wp()->tracking->get_affiliate_id();

			$discount_code = $payment->get_discount_code();
			if ($discount_code) {
				$discount              = wu_get_discount_code_by_code($discount_code);
				$discount_affiliate_id = $discount->get_meta('affiliate');

				if ($discount_affiliate_id) {
					/* translators: first: customer hash id; second: coupon's code */
					$desc         = sprintf(__('Referred user %1$s via coupon code %2$s.', 'wp-ultimo-affiliatewp'), $customer->get_hash(), $payment->get_discount_code());
					$affiliate_id = intval($discount_affiliate_id);

				} // end if;

			} // end if;

			if (!$affiliate_id) {

				return;

			} // end if;

			$this->create_referral(false, $affiliate_id, $visit, $desc, $membership, $payment);

		} else {

			$affiliate_id     = $referral->affiliate_id;
			$payment_referral = affiliate_wp()->referrals->get_by('reference', $payment->get_hash());

			if ($payment_referral && !is_wp_error($payment_referral)) {
				// Update the referral status
				$new_status = $this->get_affiliate_payment_status($payment->get_status());

				if ($new_status !== $payment_referral->status && $payment_referral->status !== 'paid') {

					$success = affwp_set_referral_status($payment_referral->referral_id, $new_status);

					if ($success) {
						/* translators: first: referral id (number); second: new status name */
						affiliate_wp()->utils->log(sprintf(__('Referral status of %1$ss changed to "%2$ss".', 'wp-ultimo-affiliatewp'), $payment_referral->referral_id, $new_status));

					} // end if;

				} // end if;

			} // end if;

		} // end if;

	} // end set_referral;

	/**
	 * Adds the tracking to the AffiliateWP tracking
	 *
	 * @since  1.0.1
	 * @param object|false                 $referral     The user referral object.
	 * @param string                       $affiliate_id ID of the affiliate.
	 * @param string                       $visit        Visit ID.
	 * @param string                       $description  Description.
	 * @param \WP_Ultimo\Models\Membership $membership   Subscription object containing the parameters.
	 * @param \WP_Ultimo\Models\Payment    $payment      Payment object containing the parameters.
	 * @return array|\WP_Error
	 */
	protected function create_referral($referral, $affiliate_id, $visit, $description, $membership, $payment) {

		/**
		 * Set Variables
		 */
		$reference = (string) $payment->get_hash();
		$status    = $this->get_affiliate_payment_status($payment->get_status());
		$context   = $this->context;
		$campaign  = affiliate_wp()->tracking->get_campaign();
		$amount    = $payment->get_total();
		$user_id   = $membership->get_customer()->get_user_id();

		if (!affiliate_wp()->settings->get('wp_ultimo_setup_fee_affwp') && !$referral) {
			// Remove the setup fee from the amount
			$amount = $amount - $membership->get_plan()->get_setup_fee();

		} // end if;

		$referral_amount = $amount > 0 ? affwp_calc_referral_amount( $amount, $affiliate_id ) : 0;

		$referral_amount = (string) apply_filters('affwp_calc_referral_amount', $referral_amount, $affiliate_id, $amount, $reference, 0, $context); // phpcs:ignore

		$args = array(
			'reference'    => $reference,
			'affiliate_id' => $affiliate_id,
			'description'  => $description,
			'custom'       => $user_id,
			'status'       => $status,
			'parent_id'    => $membership->get_hash(),
			'amount'       => $referral_amount,
		);

		if (!$referral) {
			$args = array_merge($args, array(
				'visit_id' => $visit,
				'context'  => $context,
				'campaign' => $campaign,
				'type'     => 'sale',
			));

			// Create a new referral
			$referral_id = affiliate_wp()->referrals->add( apply_filters( 'affwp_insert_pending_referral', $args, $referral_amount, $reference, $description, $affiliate_id, $visit, array(), $context ) ); //phpcs:ignore

			return $referral_id ? true : false;

		} // end if;

		$referral_id = affiliate_wp()->referrals->add( apply_filters( 'affwp_insert_pending_referral', $args, $referral_amount, $reference, $description, $affiliate_id, $visit, array(), $context ) ); //phpcs:ignore

		if ($status === 'unpaid') {

			return $this->complete_referral($referral_id);

		} // end if;

		return affwp_set_referral_status( $referral_id, $status ) ? true : false;

	} // end create_referral;

	/**
	 * Get the payment status of a affiliate from wu payment
	 *
	 * @param  string $payment_status The statue of payment.
	 * @return string
	 */
	protected function get_affiliate_payment_status($payment_status) {

		switch ($payment_status) {
			case 'refunded':
				return 'rejected';
			case 'failed':
				return 'rejected';
			case 'cancelled':
				return 'rejected';
			case 'completed':
				return 'unpaid';
			default:
				return 'pending';
		} // end switch;

	} // end get_affiliate_payment_status;

}  // end class AffiliateWP_Manager;
