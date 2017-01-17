<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 1/17/2017
 */

namespace eav\config;


class upgrade{

  private static $legacy_options = null;

  /**
   * Gets all of the legacy options for Easy Age Verifier
   * @return mixed|null
   */
  private function getLegacy($legacy_key){
    if(!isset(self::$legacy_options)){
      self::$legacy_options = get_option('eav_options');
    }

    return self::$legacy_options[EAV_PREFIX.'_'.$legacy_key];
  }

  private function addLegacyValues(){
    $result = true;
    $updated_keys = array(
      "minimum_age",
      "form_type",
      "form_title",
      "underage_message",
      "button_value",
      "over_age_value",
      "under_age_value",
    );

    foreach($updated_keys as $key){
      $update = update_option(EAV_PREFIX.'_'.$key, self::getLegacy($key));
      if($update == false){
        $result = false;
      }
    }

    return $result;
  }

  private function removeLegacyValues(){
    return delete_option('eav_options');
  }

  public static function legacyDatabase(){
    $legacy_values_added = self::addLegacyValues();

    if($legacy_values_added){
      self::removeLegacyValues();
    }

  }
}