<?php
/*
Plugin Name: Verify Age
Description: Verify that your visitors are of age.
Version:     1.0
Author:      Alex Standiford
Author URI:  http://www.alexstandiford.com
*/
$taseav_script = plugin_dir_url(__FILE__).'verify-age.js';
$taseav_css = plugin_dir_url(__FILE__).'verify-age.css';

function taseav_add_script(){
  global $taseav_script;
  echo '<!---Age Verification Script--->';
  echo '<script src="'.$taseav_script.'"></script>';
}

function taseav_add_css(){
  global $taseav_css;
  echo '<!---Age Verification CSS--->';
  echo '<link rel="stylesheet" type="text/css" href="'.$taseav_css.'">';
}

if(function_exists('tas_add_tag')){
  tas_add_tag($taseav_script,'script','Age Verification Script','footer');
  tas_add_tag($taseav_css,'stylesheet','Age Verification Stylesheet');
}
else{
  add_action('wp_footer','taseav_add_script');
  add_action('wp_head','taseav_add_css');
}

?>