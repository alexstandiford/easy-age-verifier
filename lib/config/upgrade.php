<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 1/17/2017
 */

namespace eav\config;

/**
 * Class upgrade
 * Upgrades the database format from 1.0 to the newest version
 * @package eav\config
 */
class upgrade{

  private static $legacy_options = null;

  /**
   * Gets the value of a single legacy option
   * The former version of Easy Age Verifier put all of the options into a single Database entry. This function can extract a single option from that database entry
   *
   * @param $legacy_key
   *
   * @return mixed
   */
  private function getLegacy($legacy_key){
    if(!isset(self::$legacy_options)){
      self::$legacy_options = get_option('eav_options');
    }

    return self::$legacy_options[EAV_PREFIX.'_'.$legacy_key];
  }

  /**
   * Converts legacy database values
   * Converts the legacy database values to the modern database values. Returns false if it failed to complete
   * @return bool
   */
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

  /**
   * Removes the legacy values from the database
   * @return bool
   */
  private function removeLegacyValues(){
    return delete_option('eav_options');
  }

  /**
   * Upgrades the database
   * Converts all old database values to the new version, and removes the old database value. Returns `true` when the conversion is successful.
   * @return bool
   */
  public static function legacyDatabase(){
    if(isset(self::$legacy_options)){
      $legacy_values_added = self::addLegacyValues();

      if($legacy_values_added){
        self::removeLegacyValues();
      }

      return $legacy_values_added;
    }
  }
}