<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\app;


class age{

  /**
   * Parses the age from the date of birth
   * @param null $dob
   *
   * @return bool|false|null|string
   */
  //TODO: Make getFromDob more readable by breaking the function down into more simple parts
  public static function getFromDob($dob = null){
    if(!isset($dob)){
      $dob = $_COOKIE['taseavdob'];
    }
    if(isset($dob)){
      if(($dob == 'overAge' || $dob == 'underAge')){
        $age = $dob;
      }
      else{
        //explode the date to get month, day and year
        $birthDate = explode("-", $dob);
        //get age from date or birthdate
        $age = (date("Ymd", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[0], $birthDate[1]))) > date("Ymd")
          ? ((date("Y") - $birthDate[0]) - 1)
          : (date("Y") - $birthDate[0]));
      }
    }
    else{
      $age = false;
    }
    return $age;
  }
}