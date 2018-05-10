<?php
/**
 * Age class for the verifier
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\app;

if(!defined('ABSPATH')) exit;

/**
 * Verifier Age Class
 * Handles all things related to the visitor's age. Works from the cookie set by the Verifier form
 * @package eav\app
 */
class age{

  /**
   * Parses the age from the date of birth
   * @see self::getAgeFrom();
   * @return int
   */
  public static function get(){

    //Sets default value when a dob isn't specified
    if(isset($_COOKIE['eav_age']) && is_int($_COOKIE['eav_age']) && $_COOKIE['eav_age'] >= 0){
      $age = $_COOKIE['eav_age'];
    }
    else{
      $age = 0;
    }

    return absint($age);
  }
}