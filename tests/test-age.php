<?php
/**
 * Test the Age Class
 *
 * @package Easy_Age_Verifier
 */

use eav\app\age;

/**
 * Test The Age Class
 */
class AgeTest extends WP_UnitTestCase{

  /**
   * Confirm age returns an int when cookie is not set
   */
  function test_confirm_age_is_an_int_when_a_cookie_is_not_set(){
    $_COOKIE['eav_age'] = null;
    $this->assertThat(age::get(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(0)
    ));
  }

  /**
   * Confirm cookie returns zero when cookie is set to something other than an int
   */
  function test_confirm_age_is_an_int_when_a_cookie_is_not_an_int(){
    $_COOKIE['eav_age'] = 'this is not an integer';
    $this->assertThat(age::get(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(0)
    ));
  }

  /**
   * Confirm cookie is a positive int when cookie is set to a negative int
   */
  function test_confirm_age_is_positive_when_cookie_is_negative(){
    $_COOKIE['eav_age'] = -25;
    $this->assertThat(age::get(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(0)
    ));
  }

}
