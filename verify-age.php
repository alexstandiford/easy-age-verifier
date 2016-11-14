<?php
/*
Plugin Name: Verify Age
Description: Verify that your visitors are of age.
Version:     1.30
Author:      Alex Standiford
Author URI:  http://www.alexstandiford.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;

require_once(plugin_dir_path(__FILE__).'settings.php');

class taseav{
  
  public function __construct(){
    $options = get_option('eav_options');
    $this->dob = $_COOKIE['taseavdob'];
    $this->minAge = $options['eav_minimum_age'] != '' ? $options['eav_minimum_age'] : apply_filters('eav_default_age',21);
    $this->underageMessage = $options['eav_underage_message'] != '' ? $options['eav_underage_message'] : apply_filters('eav_default_underage_message','Sorry! You must be '.$this->options['eav_minimum_age'].' To visit this website.');
    $this->formTitle = $options['eav_form_title'] != '' ? $options['eav_form_title'] : apply_filters('eav_default_form_title','Verify Your Age to Continue');
    $this->wrapperClass = $options['eav_wrapper_class'] != '' ? $options['eav_wrapper_class'] : apply_filters('eav_default_wrapper_class','taseav-age-verify');
    $this->formClass = $options['eav_form_class'] != '' ? $options['eav_form_class'] : apply_filters('eav_default_wrapper_class','taseav-verify-form');
    $this->buttonValue = $options['eav_button_value'] != '' ? $options['eav_button_value'] : apply_filters('eav_default_button_value','Submit');
    $this->overAge = $options['eav_over_age_value'] != '' ? $options['eav_over_age_value'] : apply_filters('eav_over_age_value',"I am ".$this->minAge." or older.");
    $this->underAge = $options['eav_under_age_value'] != '' ? $options['eav_under_age_value'] : apply_filters('eav_under_age_value',"I am under ".$this->minAge);
    $this->debug = $options['eav_debug'];
    $this->formType = $options['eav_form_type'] == null ? 'eav_enter_age' : $options['eav_form_type'];
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
    $this->loggedIn = is_user_logged_in();
  }
  
  public function isOfAge(){
    if($this->age() >= $this->minAge && $this->age() != false && $this->age() != 'underAge'){
      $this->isOfAge = true;
      return true;
    }
    else{
      $this->isOfAge = false;
      return false;
    }
  }

  public function custom_is_true(){
    $result = apply_filters('eav_custom_modal_logic', true);
    return $result;
  }

  public function age(){
    if(isset($this->dob)){
      if(($this->dob == 'overAge' || $this->dob == 'underAge')){
        $age = $this->dob;
      }
      else{
        //explode the date to get month, day and year
        $birthDate = explode("-", $this->dob);
        //get age from date or birthdate
        $age = (date("Ymd", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[0], $birthDate[1]))) > date("Ymd")
             ? ((date("Y") - $birthDate[0]) - 1)
             : (date("Y") - $birthDate[0]));
      }
    return $age;
    }
    else{
      return false;
    }
  }
  
  public function get(){
    $result = [];
    foreach($this as $var => $value){
      $result = array_merge($result,[$var => $value]);
    }
    $result['isOfAge'] = $this->isOfAge();
    return $result;
  }
  
}

//Enqueues scripts and styles
function taseav_init(){
  //Calls the data to pass to the JS file
  $pass_data = new taseav();
  //Checks to see if the date of birth is above the desired age
  //Also checks to see if the user is logged in.
  if($pass_data->isOfAge() == false && !is_user_logged_in()){
    //Checks to see if there are any custom overrides to the behavior of the modal
    if($pass_data->custom_is_true()){
      //Calls jQuery beforehand as verify-age depends on it
      wp_enqueue_script('jquery');

      //Registers the Age Verification Script
      wp_register_script('verify-age.js',plugin_dir_url(__FILE__).'verify-age.js',[],time());

      //Adds PHP Variables to the script as an object
      wp_localize_script('verify-age.js','taseavData',$pass_data->get());

      //Calls Age Verification Script
      wp_enqueue_script('verify-age.js',[],time());

      //Age Verification Style
      wp_enqueue_style('verify-age.css',plugin_dir_url(__FILE__).'verify-age.css',[],'1.30');
    }
  }
}
add_action('wp_enqueue_scripts','taseav_init');

?>