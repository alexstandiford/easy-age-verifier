<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\app;
if(!defined('ABSPATH')) exit;


class age{

  /**
   * Parses the date of birth value into the age
   *
   * @param $dob
   *
   * @return false|string
   */
  private function parseAge($dob){
    //explode the date to parseAge month, day and year
    $birthDate = explode("-", $dob);
    //parseAge age from date or birthdate
    $age = (date("Ymd", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[0], $birthDate[1]))) > date("Ymd")
      ? ((date("Y") - $birthDate[0]) - 1)
      : (date("Y") - $birthDate[0]));

    return $age;
  }

  /**
   * Checks if the confirmation age type is a date of birth, or if it's just a "yes/no" verification
   *
   * @param $dob
   *
   * @return bool
   */
  private function isConfirmAge($dob){
    if(($dob == 'overAge' || $dob == 'underAge')){
      $confirm_age = true;
    }
    else{
      $confirm_age = false;
    }

    return $confirm_age;
  }

  /**
   * Gets the evaluated age based on confirmation age type
   *
   * @param $dob
   *
   * @return false|string
   */
  private function getAgeFrom($dob){
    if(self::isConfirmAge($dob)){
      $age = $dob;
    }
    else{
      $age = self::parseAge($dob);
    }

    return $age;
  }

  /**
   * Parses the age from the date of birth
   *
   * @param null $dob
   *
   * @return bool|false|null|string
   */
  public static function getFromDob($dob = null){

    //Sets default value when a dob isn't specified
    if(!isset($dob)){
      $dob = $_COOKIE['taseavdob'];
    }

    //If the cookie value is set, get the age from the date of birth. Otherwise, return false.
    if(isset($dob)){
      $age = self::getAgeFrom($dob);
    }
    else{
      $age = false;
    }

    return $age;
  }
}