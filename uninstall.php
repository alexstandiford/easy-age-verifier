<?php
/**
 * Uninstallation script file. Runs when Easy Age Verifier is deleted
 */

if(!defined('WP_UNINSTALL_PLUGIN')){
  die;
}

$options = array(
  'eav_minimum_age',
  'eav_form_type',
  'eav_form_title',
  'eav_underage_message',
  'eav_button_value',
  'eav_over_age_value',
  'eav_under_age_value',
  'eav_show_verifier_to_logged_in_users',
  'eav_active_in_customizer',
);
$is_multisite = is_multisite();
foreach($options as $option){
  delete_option($option);
  if($is_multisite){
    delete_site_option($option);
  }
}
