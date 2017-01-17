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
if(!class_exists('eav')){

  class eav{

    private static $instance = null;
    private function __construct(){}

    public static function getInstance(){
      if(!isset(self::$instance)){
        self::$instance = new self;
        self::$instance->_defineConstants();
        self::$instance->_includeFiles();
      }

      return self::$instance;
    }

    private function _defineConstants(){
      define('EAV_URL',plugin_dir_url(__FILE__));
      define('EAV_PATH',plugin_dir_path(__FILE__));
      define('EAV_ASSETS_URL', EAV_URL.'lib/assets/');
      define('EAV_ASSETS_PATH', EAV_PATH.'lib/assets/');
      define('EAV_TEMPLATES_PATH', EAV_ASSETS_URL.'templates/');
    }

    private function _includeFiles(){
      $includes = array(

        //configuration classes
        'config/cleanup.php',
        'config/customizer.php',
        'config/option.php',

        //App classes
        'app/age.php',
        'app/verification.php',
        'app/verifier.php',

      );

      foreach ($includes as $include){
        require_once(EAV_PATH.'lib/'.$include);
      }
    }
  }
}

eav::getInstance();

function init(){
  if(!is_admin()){
    verifier::doFormActions();
  }
}
add_action('get_header',__NAMESPACE__.'\\init');