<?php
/**
 * Verification class for Easy Age Verifier
 * Everything related to the verification process of the visitor
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\app;
if(!defined('ABSPATH')) exit;

use eav\config\option;

class verification{

  public $isOfAge = null;

  public function __construct($dob = null){
    $this->minimum_age = option::get('minimum_age');
    $this->visitor_age = age::getFromDob($dob);
  }

  /**
   * Checks if the visitor is of-age. Stores the result into a value, so it doesn't need to be re-ran
   * @return bool
   */
  public function isOfAge(){
    if(isset($this->isOfAge)){
      return $this->isOfAge;
    }

    $checks = array(
      $this->visitor_age >= $this->minimum_age,
      $this->visitor_age != false,
      $this->visitor_age == 'overAge',
    );

    if(in_array(true, $checks)){
      $this->isOfAge = true;

      return true;
    }
    else{
      $this->isOfAge = false;

      return false;
    }
  }

  /**
   * Allows developers to add custom logic for the modal popup
   * @return array|mixed
   */
  public function customIsTrue(){
    $checks = array('result' => true);
    $checks = apply_filters('eav_custom_modal_logic', $checks);
    if(is_array($checks)){
      foreach($checks as $check){
        if($check == false){
          $checks['result'] = false;
          break;
        }
      }
    }

    return $checks;
  }

  /**
   * Checks if verification has failed or passed
   * @return bool
   */
  public function failed(){
    $checks = array(
      $this->isOfAge() == false,
      !is_user_logged_in(),
      is_customize_preview(),
    );

    if(in_array(true,$checks)){
      $failed = true;
    }
    else{
      if($this->customIsTrue()['result']){
        $failed = false;
      }
      else{
        $failed = true;
      }
    }
    return $failed;
  }
}