=== WP Ultimo: WooCommerce Integration ===
Contributors: aanduque
Requires at least: 5.1
Tested up to: 6.0.1
Requires PHP: 7.1.4
WC requires at least: 5.2
WC tested up to: 6.7.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept payments using any of the hundreds of payment gateways available for WooCommerce and WooCommerce Subscriptions.

== Description ==

WP Ultimo: WooCommerce Integration

Accept payments using any of the hundreds of payment gateways available for WooCommerce and WooCommerce Subscriptions.

== Installation ==

1. Upload 'wp-ultimo-woocommerce' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Follow the step by step Wizard to set the plugin up

== Changelog ==

Version 2.0.0 - Released on 2022-08-05

* Added: Process cancelation method to remove woo subscription when changing the gateway or canceling the membership;
* Added: Handler to downgrade and upgrade memberships;
* Improvement: Load woocommerce dependencies on customer update form in subsites to allow account update;
* Improvement: Correct load Woocommerce cart if not exist;
* Improvement: Ensure we are on main site tables when process a checkout;
* Improvement: Make Ultimo renewal order based on Woocommerce subscription order value and not from last payment;
* Fix: Go to WU Membership button link;
* Fix: Set Ultimo order as paid when Woocommerce subscriptions renewal has paid;
* Build: Add MPB as builder


Version 2.0.0-beta-5 - Released on 2022-01-21

* Internal: Added hooks and filters generator;
* Internal: Added WP Ultimo stubs for developer quality of life;
* Fixed: Prevent the creation of multiple products when not necessary;

Version 2.0.0-beta.4 - 2021-09-23

* Fix: requiring WooCommerce to be network active instead of main site only;
* Improvement: added filter to allow the add-on to be used as a mu-plugin;

Version 2.0.0-beta.3 - 2021-05-28

* Fix: dashboard access control was too aggressive;
* Improvement: Added WooCommerce help links to WP Ultimo top-menu;

Version 2.0.0-beta.2 - 2021-05-04

* Improvement: creates pending payments on Ultimo on WCS renewal order creation;
* Improvement: pre-fills billing fields with WP Ultimo customer data;
* Improvement: adds back billing fields for gateways;

Version 2.0.0-beta.1 - 2021-05-04

* Initial beta release

-- Legacy Versions -- 

Version 1.2.6 - 26/03/2020

* Fixed: Small incompatibility with newer versions of WooCommerce Subscriptions;

Version 1.2.5 - 26/08/2019

* Fixed: Error on previous release;

Version 1.2.4 - 22/08/2019

* Improved: Added option to redirect to WooCommerce checkout screen after integration immediately;

Version 1.2.3 - 26/05/2019

* Fixed: Payment email for WooCommerce disapeared in some edge cases;

Version 1.2.2 - 27/02/2019

* Added: Support to setup fees on the WooCommerce Subscription integration;

Version 1.2.1 - 17/11/2018

* Fixed: Compatibility issues with WP Ultimo version 1.9.0;

Version 1.2.0 - 10/09/2018

* Improved: New updates URL for add-ons;
* Added: Beta support to WooCommerce Subscription;

Version 1.1.2 - 11/02/2018

* Fixed: Link to Pay being generated dynamically to respond to changes to WooCommerce endpoints;
* Improved: We now force completed status for our orders when payment_completed is called to make sure our renewal hooks run when they should;

Version 1.1.1 - 24/01/2018

* Fixed: Now it also checks to see if the WooCommerce is just activated on the main site;
* Fixed: Included over-loadings to allow order creation to include taxes;

Version 1.1.0 - 04/11/2017

* Fixed: Now the label of the integration button actually changes to reflect the settings. Requires WP Ultimo 1.5.0;
* Fixed: WooCommerce Integration now works even if WooCommerce is not network active and activated only in t^he main site;

1.0.0 - Initial Release
