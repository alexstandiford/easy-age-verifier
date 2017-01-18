<?php
if(!defined('WP_UNINSTALL_PLUGIN')){
  die;
}
$options = array(
  'eav_options',
  "minimum_age",
  "form_type",
  "form_title",
  "underage_message",
  "button_value",
  "over_age_value",
  "under_age_value",
  'active_in_customizer',
);
$is_multisite = is_multisite();
foreach($options as $option){
  delete_option($option);
  if($is_multisite){
    delete_site_option($option);
  }
}
