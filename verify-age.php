<?php
/*
Plugin Name: Verify Age
Description: Verify that your visitors are of age.
Version:     1.0
Author:      Alex Standiford
Author URI:  http://www.alexstandiford.com
*/

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