<?php
/*
Plugin Name: Verify Age
Description: Verify that your visitors are of age.
Version:     1.0
Author:      Alex Standiford
Author URI:  http://www.alexstandiford.com
*/
$tasva_script = plugin_dir_url(__FILE__).'verify-age.js';
$tasva_css = plugin_dir_url(__FILE__).'verify-age.css';

function tasva_add_script(){
  global $tasva_script;
  echo '<!---Age Verification Script--->';
  echo '<script src="'.$tasva_script.'"></script>';
}

function tasva_add_css(){
  global $tasva_css;
  echo '<!---Age Verification CSS--->';
  echo '<link rel="stylesheet" type="text/css" href="'.$tasva_css.'">';
}

if(function_exists('tas_add_tag')){
  tas_add_tag($tasva_script,'script','Age Verification Script','footer');
  tas_add_tag($tasva_css,'stylesheet','Age Verification Stylesheet');
}
else{
  add_action('wp_footer','tasva_add_script');
  add_action('wp_head','tasva_add_css');
}

?>