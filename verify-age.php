<?php
/*
Plugin Name: Verify Age
Description: Verify that your visitors are of age.
Version:     1.0
Author:      Alex Standiford
Author URI:  http://www.alexstandiford.com
*/

class taseav{
  
  public function __construct(){
    $this->dob = $_COOKIE['dob'];
    $this->minAge = '21';
  }
  
  public function isOfAge(){
    if($this->age() >= $this->minAge && $this->age() !== false){
      return true;
    }
    else{
      return false;
    }
  }
  
  public function age(){
    if(isset($this->dob)){
    //explode the date to get month, day and year
    $birthDate = explode("-", $this->dob);
    //get age from date or birthdate
    $age = (date("Ymd", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[0], $birthDate[1]))) > date("Ymd")
         ? ((date("Y") - $birthDate[0]) - 1)
         : (date("Y") - $birthDate[0]));
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
    return $result;
  }
  
}

//Enqueues scripts and styles
function taseav_init(){
  //Calls jQuery beforehand as verify-age depends on it
  wp_enqueue_script('jquery');
  //Age Verification Script
  wp_enqueue_script('verify-age.js',plugin_dir_url(__FILE__).'verify-age.js');
  
  //Age Verification Style
  wp_enqueue_style('verify-age.css',plugin_dir_url(__FILE__).'verify-age.css');
}
add_action('wp_enqueue_scripts','taseav_init');

?>