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

/**
 * Class verification
 * Handles verifications that determine if the verifier will display on this page
 * @package eav\app
 */
class verification{

  public $isOfAge = null;
  public $checks = null;

  public function __construct($dob = null){
    $this->minAge = option::get('minimum_age');
    $this->visitorAge = age::get();
  }

  /**
   * Checks if the visitor is of-age.
   * Stores the result into a value, so it doesn't need to be re-ran.
   * @return bool
   */
  public function isOfAge(){
    if(isset($this->isOfAge)){
      return $this->isOfAge;
    }

    $checks = array(
      $this->visitorAge >= $this->minAge,
      $this->visitorAge != false,
      $this->visitorAge == 'overAge',
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
   * Checks if the WordPress customizer is active
   * @return bool
   */
  public function customizerIsActive(){
    $result = false;
    $active_in_customizer = get_option(EAV_PREFIX."_active_in_customizer");
    if(!isset($active_in_customizer) || $active_in_customizer == ""){
      $active_in_customizer = false;
    }
    if(is_customize_preview() && $active_in_customizer){
      $result = true;
    }

    return $result;
  }

  //TODO: Test the customIsTrue filter
  /**
   * Checks if verification has failed or passed
   * @return bool
   */
  public function failed(){
    $checks = array(
      'of_age_and_logged_in' => $this->isOfAge() == false && !is_user_logged_in(),
      'customizer_is_active' => $this->customizerIsActive(),
    );
    $checks = apply_filters('eav_custom_modal_logic', $checks);
    $this->checks = $checks;
    if(in_array(true, $checks)){
      $failed = true;
    }
    else{
      $failed = false;
    }

    return $failed;
  }
}