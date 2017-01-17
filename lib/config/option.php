<?php
/**
 * Options handler for Easy Age Verifier
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\config;
if(!defined('ABSPATH')) exit;


class option{

  private static $options = null;

  /**
   * Gets the options value for Easy Age Verifier
   *
   * @param $value
   *
   * @return mixed
   */
  public static function get($value, $default = null){

    if(!isset(self::$options)){
      self::$options = get_option('eav_options');
    }
    $db_value = self::$options['eav_'.$value];
    if(isset($default) && !isset($db_value)){
      $option = $default;
    }
    else{
      $option = $db_value;
    }

    return $option;
  }

  /**
   * Gets an option with a built-in default value as a filter
   *
   * @param      $value
   * @param      $default
   * @param null $filter
   *
   * @return mixed
   */
  public static function getWithFilter($value, $default, $filter = null){
    if(self::get($value) !== null){
      $result = self::get($value);
    }
    else{
      if(isset($filter)){
        $result = apply_filters($filter, $default);
      }
      else{
        $result = apply_filters('eav_default_'.$value.'_value', $default);
      }
    }

    return $result;
  }

}