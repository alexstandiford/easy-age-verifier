<?php
/*
Plugin Name: Easy Age Verifier
Description: Easy Age Verifier makes it easy for websites to confirm their website visitors are of legal age.
Version:     2.10
Author:      Alex Standiford (Fill Your Taproom)
Author URI:  http://www.fillyourtaproom.com
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: easyageverifier
*/

namespace eav;

use eav\app\verifier;
use eav\config\customizer;
use eav\config\option;
use eav\config\upgrade;
use eav\config\menu;

if(!defined('ABSPATH')) exit;

if(!class_exists('eav')){

  class eav{

    private static $instance = null;

    private function __construct(){
    }

    /**
     * Fires up the plugin.
     * @return self
     */
    public static function getInstance(){
      if(!isset(self::$instance)){
        self::$instance = new self;
        self::$instance->_defineConstants();
        self::$instance->_includeFiles();
        add_action('rest_api_init', function(){
          register_rest_route('easy-age-verifier/v1', '/toggle-debug-mode', [
            'methods'  => 'POST',
            'callback' => 'eav\\config\\option::toggleDebugMode',
          ]);
        });
      }

      return self::$instance;
    }

    /**
     * Defines the constants related to Easy Age Verifier
     * @return void
     */
    private function _defineConstants(){
      define('EAV_URL', plugin_dir_url(__FILE__));
      define('EAV_PATH', plugin_dir_path(__FILE__));
      define('EAV_ASSETS_URL', EAV_URL.'lib/assets/');
      define('EAV_ASSETS_PATH', EAV_PATH.'lib/assets/');
      define('EAV_TEMPLATES_PATH', EAV_ASSETS_PATH.'templates/');
      define('EAV_TEXT_DOMAIN', 'easyageverifier');
      define('EAV_PREFIX', 'eav');

      $version = get_plugin_data(__FILE__,false,false);
      $version = $version['Version'];
      define('EAV_VERSION', $version);
    }

    /**
     * Grabs the files to include, and requires them
     * @return void
     */
    private function _includeFiles(){
      $includes = array(

        //configuration classes
        'config/customizer.php',
        'config/option.php',
        'config/upgrade.php',
        'config/menu.php',

        //App classes
        'app/age.php',
        'app/verification.php',
        'app/verifier.php',

        //Extra classes
        'extras/wpApi.php',

      );

      foreach($includes as $include){
        require_once(EAV_PATH.'lib/'.$include);
      }
    }
  }
}

//Let's rock 'n roll
eav::getInstance();

/**
 * Initializes the verifier form
 * @return void
 */
function init(){
  if(!is_admin()){
    verifier::doFormActions();
  }
}

add_action('init', __NAMESPACE__.'\\init');
add_action('customize_preview_init', __NAMESPACE__.'\\init');

/**
 * Initializes the customizer on the admin
 * @return void
 */
function customize_init(){
  customizer::register();
}

add_action('customize_register', __NAMESPACE__.'\\customize_init');

/**
 * Initializes the menu item on the admin
 * @return void
 */
function menu_init(){
  menu::register();
}

add_action('admin_menu', __NAMESPACE__.'\\menu_init');


/**
 * Upgrades the legacy database to the new database format on plugin activation
 * @return void
 */
function upgrade_legacy_data(){
  upgrade::legacyDatabase();
}

register_activation_hook(__FILE__, __NAMESPACE__.'\\upgrade_legacy_data');

function eav_admin_styles_init(){
  $styles = array(
    'settings.css' => EAV_ASSETS_URL.'css/settings.css',
  );
  $styles = apply_filters(EAV_PREFIX.'_admin_styles', $styles);
  foreach($styles as $style => $path){
    wp_enqueue_style($style, $path);
  }
  $scripts = array();
  $scripts = apply_filters(EAV_PREFIX.'_admin_scripts', $scripts);
  foreach($scripts as $script => $parameters){
    wp_enqueue_script($script, $parameters['src'], $parameters['dependencies'], $parameters['version'], $parameters['in_footer']);
  }
}

add_action('admin_enqueue_scripts', __NAMESPACE__.'\\eav_admin_styles_init');

/**
 * Redirects to the customizer page when the eav-options page is opened
 * @return void
 */
function redirect_to_customizer(){
  global $_GET;
  if(isset($_GET['page']) && $_GET['page'] == 'eav-options'){
    wp_redirect(admin_url().'customize.php?autofocus[section]=eav_section');
    die;
  }
}

add_action('admin_init', __NAMESPACE__.'\\redirect_to_customizer');

function fyt_content_widget(){
  // Globalize the metaboxes array, this holds all the widgets for wp-admin
  global $wp_meta_boxes;

  // Only load this widget once. This prevents EBL and EAV from unintentionally loading the widget twice.
  if(!isset($wp_meta_boxes['dashboard']['normal']['core']['fyt_content_widget'])){
    wp_add_dashboard_widget('fyt_content_widget', 'Fill Your Taproom Articles', __NAMESPACE__.'\\fyt_content_widget_function');
    // Get the regular dashboard widgets array
    // (which has our new widget already but at the end)
    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    // Backup and delete our new dashboard widget from the end of the array
    $fyt_widget_backup = array('fyt_content_widget' => $normal_dashboard['fyt_content_widget']);
    unset($normal_dashboard['fyt_content_widget']);

    // Merge the two arrays together so our widget is at the beginning
    $sorted_dashboard = array_merge($fyt_widget_backup, $normal_dashboard);

    // Save the sorted array back into the original metaboxes
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
  }
}

add_action('wp_dashboard_setup', __NAMESPACE__.'\\fyt_content_widget');

function fyt_content_widget_function(){
  include_once(EAV_TEMPLATES_PATH.'admin/dashboard-widget.php');
}

/**
 * Sets default values when plugin is loaded for the first time
 */
function set_default_values(){
  if(get_option('eav_is_activated') != true){
    $options = array(
      'eav_minimum_age'                      => 21,
      'eav_form_type'                        => __('eav_enter_age', EAV_TEXT_DOMAIN),
      'eav_form_title'                       => __('Verify Your Age to Continue.', EAV_TEXT_DOMAIN),
      'eav_underage_message'                 => __('Sorry! You must be 21 to visit this website.', EAV_TEXT_DOMAIN),
      'eav_button_value'                     => __('Submit', EAV_TEXT_DOMAIN),
      'eav_over_age_value'                   => __('I am 21 or older.', EAV_TEXT_DOMAIN),
      'eav_under_age_value'                  => __('I am under 21', EAV_TEXT_DOMAIN),
      'eav_show_verifier_to_logged_in_users' => false,
      'eav_active_in_customizer'             => false,
      'eav_is_activated'                     => true,
    );
    foreach($options as $option => $value){
      if(get_option($option) == false) update_option($option, $value);
    }
  }
}

register_activation_hook(__FILE__, __NAMESPACE__.'\\set_default_values');
