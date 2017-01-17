<?php
/**
 * Verifier class for Easy Age Verifier.
 * This class contains everything related to the age verifier itself, including template routing and customizations
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\app;

use eav\config\option;

class verifier{


  public function __construct($verification = null, $template_path = null){
    if(!isset($verification)){
      $this->verification = new verification();
    }

    $this->underageMessage = option::getWithFilter(
      'underage_message',
      'Sorry! You must be '.$this->verification->minimum_age.' To visit this website.',
      'eav_default_underage_message'
    );

    $this->formTitle = option::getWithFilter(
      'form_title',
      'Verify Your Age to Continue.',
      'eav_default_form_title'
    );

    $this->wrapperClass = option::getWithFilter(
      'wrapper_class',
      'taseav-age-verify',
      'eav_default_wrapper_class'
    );

    $this->formClass = option::getWithFilter(
      'form_class',
      'taseav-verify-form',
      'eav_default_form_class'
    );

    $this->buttonValue = option::getWithFilter(
      'button_value',
      'Submit'
    );

    $this->overAge = option::getWithFilter(
      'over_age_value',
      'I am'.$this->verification->minimum_age.'or older.',
      'eav_over_age_value'
    );

    $this->underAge = option::getWithFilter(
      'under_age_value',
      'I am under '.$this->verification->minimum_age,
      'eav_under_age_value'
    );

    $this->templatePath = $this->templatePath($template_path);
    $this->template = apply_filters('eav_modal_template','');

    $this->beforeForm = apply_filters('eav_before_form','');
    $this->afterForm = apply_filters('eav_after_form','');
    $this->monthClass = apply_filters('eav_month_class','taseav-month');
    $this->dayClass = apply_filters('eav_day_class','taseav-day');
    $this->yearClass = apply_filters('eav_year_class','taseav-year');
    $this->minYear = apply_filters('eav_min_year','1900');
    $this->beforeYear = apply_filters('eav_before_year','');
    $this->beforeDay = apply_filters('eav_before_day','');
    $this->beforeMonth = apply_filters('eav_before_month','');
    $this->beforeButton = apply_filters('eav_before_button','');

    $this->formType = option::get('enter_age','eav_enter_age');
  }

  /**
   * Gets the template path from the constructor Uses a default value if a path isn't given
   *
   * @param null $template_path
   *
   * @return null|string|bool
   */
  public function templatePath($template_path){
    if(isset($template_path)){
      $path = $template_path;
    }
    else{
      // Support for legacy method of building custom verifier templates
      if($this->hasLegacyOverride()){
        $path = false;
      }
      else{
        $path = apply_filters('eav_modal_template_file', EAV_TEMPLATES_PATH.'default.php');
      }
    }
    return $path;
  }

  /**
   * Legacy support for adding a theme template from a functions.php file
   *
   * @return bool
   */
  public function hasLegacyOverride(){
    if(has_filter('eav_modal_template')){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Gets the form if the verification failed. Does custom actions when the result is false
   *
   * $return void
   */
  public static function doFormActions(){
    $verifier = new self();
    if($verifier->verification->failed()){
      add_action('wp_enqueue_scripts', array($verifier, 'enqueueVerifierScripts'));
    }
    else{
      $custom_is_true = $verifier->verification->customIsTrue();
      if(is_array($custom_is_true)){
        foreach($custom_is_true as $check_id => $boolean){
          do_action($check_id.'_custom_is_false');
        }
      }
    }
  }

  /**
   * Enqueues the verifier scripts
   *
   * @return void
   */
  public function enqueueVerifierScripts(){
    //Calls jQuery beforehand as verify-age depends on it
    wp_enqueue_script('jquery');
    //Registers the Age Verification Script
    wp_register_script('verify-age.js',EAV_ASSETS_URL.'js/verifier.js',array(),time());
    //Adds PHP Variables to the script as an object
    wp_localize_script('verify-age.js','taseavData',$this->passData());
    //Calls Age Verification Script
    wp_enqueue_script('verify-age.js',array(),time());
    //Age Verification Style
    wp_enqueue_style('verify-age.css',EAV_ASSETS_URL.'/css/verifier.css',array(),'1.30');
  }

  /**
   * Grabs all of the object data to pass into the Javascript
   *
   * @return array
   */
  public function passData(){
    $result = array();
    foreach($this as $var => $value){
      $result = array_merge($result, [$var => $value]);
    }
    $result['isOfAge'] = false;
//    $result['isOfAge'] = $this->verification->isOfAge();
    $result['verification_failed'] = $this->verification->failed();
    return $result;
  }
}