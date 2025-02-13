<?php
/**
 * Plugin Name: WP Ultimo: Ad Injector
 * Description: Inject Ads on some of your plans directly from your plan's edit page! Feel free to provide feedback on the forums at https://docs.wpultimo.com/community/
 * Plugin URI: http://wpultimo.com
 * Text Domain: wu-ads
 * Version: 1.0.2
 * Author: Arindo Duque - NextPress
 * Author URI: http://nextpress.co/
 * Copyright: Arindo Duque, NextPress
 * Network: true
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

if (!class_exists('WP_Ultimo_Ads')) :

/**
 * Here starts our plugin.
 */
class WP_Ultimo_Ads {
  
  /**
   * Version of the Plugin
   * 
   * @var string
   */
  public $version = '1.0.2';

  /**
   * Keeps track of how many times we printed the ads
   */
  public $ad_print_count = 0;
  
  /**
   * Makes sure we are only using one instance of the plugin
   * @var object WP_Ultimo_Ads
   */
  public static $instance;

  /**
   * Returns the instance of WP_Ultimo_Ads
   * @return object A WP_Ultimo_Ads instance
   */
  public static function get_instance() {

    if (null === self::$instance) self::$instance = new self();

    return self::$instance;
    
  } // end get_instance;

  /**
   * Initializes the plugin
   */
  public function __construct() {

    // Set the plugins_path
    $this->plugins_path = plugin_dir_path(__DIR__);

    // Load the text domain
    load_plugin_textdomain('wu-ads', false, dirname(plugin_basename(__FILE__)) . '/lang');

    // Updater
    require_once $this->path('inc/class-wu-addon-updater-free.php');

    /**
     * @since 1.2.0 Creates the updater
     * @var WU_Addon_Updater
     */
    $updater = new WU_Addon_Updater_Free('wp-ultimo-ads', __('WP Ultimo: Ads Injector', 'wp-ads'), __FILE__);

    // Run Rorest, run!
    $this->hooks();

  } // end construct;

  /**
   * Return url to some plugin subdirectory
   * @return string Url to passed path
   */
  public function path($dir) {
    return plugin_dir_path(__FILE__).'/'.$dir;
  }

  /**
   * Return url to some plugin subdirectory
   * @return string Url to passed path
   */
  public function url($dir) {
    return plugin_dir_url(__FILE__).'/'.$dir;
  }
  
  /**
   * Return full URL relative to some file in assets
   * @return string Full URL to path
   */
  public function get_asset($asset, $assetsDir = 'img') {
    return $this->url("assets/$assetsDir/$asset");
  }

  /**
   * Render Views
   * @param string $view View to be rendered.
   * @param Array $vars Variables to be made available on the view escope, via extract().
   */
  public function render($view, $vars = false) {

    // Make passed variables available
    if (is_array($vars)) extract($vars);

    // Load our view
    include $this->path("views/$view.php");

  }

  /**
   * Install our default settings after activation
   * @return
   */
  public function on_activation() { } // end on_activation;

  /** 
   * Add the hooks we need to make this work
   */
  public function hooks() {

    register_activation_hook(__FILE__, array($this, 'on_activation'));

    add_action('wu_settings_sections', array($this, 'add_settings'));

    /**
     * Plan - Advanced options to add the custom code
     */
    add_filter('wu_plans_advanced_options_tabs', array($this, 'add_plan_tab'));

    add_action('wu_plans_advanced_options_after_panels', array($this, 'add_plan_tab_content'));

    add_action('wu_save_plan', array($this, 'save_plan_ad_options'));

    /**
     * Injects the Ads on the appropriate places
     */
    add_action('admin_notices', array($this, 'inject_admin_head_ads'), 20);

    add_filter('the_content', array($this, 'inject_ads_on_the_content'), 10000);

  } // end hooks;

  /**
   * Add the MailChimp tab to the advanced options on the edit plan page
   * @param array $tabs Tabs of the advanced options
   */
  public function add_plan_tab($tabs) {

    $tabs['ads'] = __('Ads Settings', 'wu-ads');

    return $tabs;

  } // end add_plan_tab;

  /**
   * Adds the HTMl markep of the Tab we added
   * @param WU_Plan $plan Plan Object
   */
  public function add_plan_tab_content($plan) {

    $this->render('options-panel', array(
      'plan' => $plan
    ));

  } // end add_plan_tab_content;

  /**
   * Save the inputs to the plan
   * @param  WU_Plan $plan Plan Object
   * @return void
   */
  public function save_plan_ad_options($plan) {

    if (is_a($plan, 'WU_Plan') && isset($_POST['has_wu_ads'])) {

      // Check boxes to save
      $checkboxes = array(
        'enable_front_end_ads',
        'enable_back_end_ads',
        'enable_before_content_ads',
        'enable_after_content_ads',
      );

      foreach($checkboxes as $checkbox) {

        update_post_meta($plan->id, "wpu_$checkbox", isset($_POST[ $checkbox ]) );

      } // end foreach;

      // Textareas and Inputs
      $inputs = array(
        'max_ads',
        'before_ad_code',
        'after_ad_code',
        'back_end_ad_code',
      );

      foreach($inputs as $input) {

        if (isset( $_POST[ $input ] ))
          update_post_meta($plan->id, "wpu_$input", $_POST[ $input ] );

      } // end foreach;

    } // end if;

  } // end save_plan_ad_options;

  /**
   * Adds the custom settings to the add-on section of our settings page on WP Ultimo
   * @param array $sections Sections;
   */
  function add_settings($sections) {

    return $sections;

  } // end add_settings;

  /**
   * Injects the ads on the admin panel
   *
   * @return void
   */
  public function inject_admin_head_ads() {

    $site = wu_get_current_site();

    $plan = $site->get_plan();

    if (!$plan || !$plan->enable_back_end_ads) return;

    echo $this->wrap_ad_in_container($plan->back_end_ad_code);

  } // end inject_admin_head_ads;

  /**
   * Checks if we are above the limit
   *
   * @return bool
   */
  public function should_print_ad($plan) {

    $limit = $plan->max_ads;

    if (!$limit) return true;

    return $this->ad_print_count < $limit;

  } // end should_print_ad;

  /**
   * Undocumented function
   *
   * @param string $the_content
   * @return string
   */
  public function inject_ads_on_the_content($the_content) {

    $site = wu_get_current_site();

    $plan = $site->get_plan();

    if (!$plan) return $the_content;

    /**
     * Before
     */
    if ($plan->enable_before_content_ads && $plan->before_ad_code && $this->should_print_ad($plan)) {

      $the_content = $this->wrap_ad_in_container($plan->before_ad_code) . $the_content;

      $this->ad_print_count++;

    } // end if;

    /**
     * After
     */
    if ($plan->enable_after_content_ads && $plan->after_ad_code && $this->should_print_ad($plan)) {

      $the_content .= $this->wrap_ad_in_container($plan->after_ad_code);

      $this->ad_print_count++;

    } // end if;

    return $the_content;

  } // end inject_ads_on_the_content;

  /**
   * Adds the container div around the code
   *
   * @param string $ad_code
   * @return string
   */
  public function wrap_ad_in_container($ad_code) {

    $ad_code = do_shortcode($ad_code);

    return "<div class='wu_ad_container'style='margin: 20px auto; overflow: hidden; with: 100%; max-width: 700px'>$ad_code</div>";

  } // end wrap_ad_in_container;

} // end WP_Ultimo_Ads;

/**
 * Initialize the Plugin
 */
add_action('plugins_loaded', 'wu_ads_init', 1);

/**
 * Returns the active instance of the plugin
 *
 * @return void
 */
function WP_Ultimo_Ads() {

  return WP_Ultimo_Ads::get_instance();

} // end WP_Ultimo_Ads;

/**
 * Initializes the plugin
 *
 * @return void
 */
function wu_ads_init() {

  if (!class_exists('WP_Ultimo')) return; // We require WP Ultimo, baby

  if (!version_compare(WP_Ultimo()->version, '1.5.0', '>=')) {

    WP_Ultimo()->add_message(__('WP Ultimo: Ads Injector requires WP Ultimo version 1.5.0. ', 'wu-ads'), 'warning', true);

    return;

  } // end if;

  // Set global
  $GLOBALS['WP_Ultimo_Ads'] = WP_Ultimo_Ads();

} // end wu_ads_init;

endif;