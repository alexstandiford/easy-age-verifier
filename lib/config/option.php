<?php
/**
 * Options handler for Easy Age Verifier
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\config;

if(!defined('ABSPATH')) exit;

/**
 * Class option
 * Simple wrapper for get_option WordPress function. Added to a class for scalability purposes
 * @package eav\config
 */
class option{

  /**
   * Gets an option value for Easy Age Verifier
   *
   * @param $value
   *
   * @return mixed
   */
  public static function get($value){
    $option = get_option(EAV_PREFIX.'_'.$value);

    return $option;
  }

  /**
   * Gets the value of a checkbox
   *
   * @param $value
   *
   * @return bool|mixed
   */
  public static function getCheckbox($value){
    $option = option::get($value);
    if(!isset($option) || $option == ""){
      $option = false;
    }
    else{
      $option = true;
    }

    return $option;
  }

  /**
   * RESTful API callback to toggle debug mode
   *
   * @param \WP_REST_Request $req
   *
   * @return bool|\WP_Error
   */
  public static function toggleDebugMode(\WP_REST_Request $req){
    if(check_ajax_referer('wp_rest','nonce',false)){
      $toggle = self::get('debug_mode_enabled') ? false : true;
      update_option(EAV_PREFIX.'_debug_mode_enabled', $toggle);

      return self::get('debug_mode_enabled');
    }
    else{
      return new \WP_Error('INVALID_NONCE', 'Invalid nonce. Reload the page and try again.', ['nonce' => $req->get_param('nonce')]);
    }
  }

  /**
   * Checks to see if the debugger is active
   * @return mixed
   */
  public static function debuggerIsActive(){
    $debug_mode_enabled = option::get('debug_mode_enabled');
    return $debug_mode_enabled ? true : false;
  }
}