<?php
/*
Plugin Name: Easy Age Verifier
Description: Easy Age Verifier makes it easy for websites to confirm their website visitors are of legal age.
Version:     1.31
Author:      Alex Standiford
Author URI:  http://www.fillyourtaproom.com
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: easyageverifier
*/

namespace eav;

use eav\app\verifier;
use eav\config\customizer;

if(!defined('ABSPATH')) exit;

if(!class_exists('eav')){

//TODO: Improve the doc blocks across the board
//TODO: Ensure no WP_DEBUG issues occur with the plugin

  class eav{

    private static $instance = null;

    private function __construct(){
    }

    public static function getInstance(){
      if(!isset(self::$instance)){
        self::$instance = new self;
        self::$instance->_defineConstants();
        self::$instance->_includeFiles();
      }

      return self::$instance;
    }

    private function _defineConstants(){
      define('EAV_URL', plugin_dir_url(__FILE__));
      define('EAV_PATH', plugin_dir_path(__FILE__));
      define('EAV_ASSETS_URL', EAV_URL.'lib/assets/');
      define('EAV_ASSETS_PATH', EAV_PATH.'lib/assets/');
      define('EAV_TEMPLATES_PATH', EAV_ASSETS_URL.'templates/');
      define('EAV_TEXT_DOMAIN','easyageverifier');
      define('EAV_PREFIX','eav');
    }

    private function _includeFiles(){
      $includes = array(

        //configuration classes
        'config/customizer.php',
        'config/option.php',

        //App classes
        'app/age.php',
        'app/verification.php',
        'app/verifier.php',

      );

      foreach($includes as $include){
        require_once(EAV_PATH.'lib/'.$include);
      }
    }
  }
}

eav::getInstance();

/**
 * Initializes the Form
 *
 * @return void
 */
function init(){
  if(!is_admin()){
    verifier::doFormActions();
  }
}
add_action('get_header', __NAMESPACE__.'\\init');

/**
 * Initializes the customizer on the admin
 *
 * @return void
 *
 */
function admin_init(){
    customizer::register();
}
add_action('customize_register',__NAMESPACE__.'\\admin_init');