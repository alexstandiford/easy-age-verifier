<?php
/**
 * Options handler for Easy Age Verifier
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\config;
if(!defined('ABSPATH')) exit;


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

}