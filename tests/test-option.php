<?php
/**
 * Test the Verification Class
 *
 * @package Easy_Age_Verifier
 */

use eav\config\option;

/**
 * Test The Verification Class
 */
class OptionTest extends WP_UnitTestCase{

  /**
   * Tests to see if value comes out correctly when minimum age is set as a negative number
   */
  function test_get_minimum_age_is_valid_when_given_a_negative_int(){
    update_option('eav_minimum_age',-1);
    $this->assertThat(option::getMinimumAge(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(21)
    ));
  }

  /**
   * Tests to see if value comes out correctly when minimum age is set as a string
   */
  function test_get_minimum_age_is_valid_when_given_a_string(){
    update_option('eav_minimum_age','200');
    $this->assertThat(option::getMinimumAge(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(21)
    ));
  }

  /**
   * Tests to see if value comes out correctly when minimum age is not set
   */
  function test_get_minimum_age_is_valid_when_the_option_is_not_set(){
    delete_option('eav_minimum_age');
    $this->assertThat(option::getMinimumAge(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(21)
    ));
  }

  /**
   * Tests to see if value comes out correctly when minimum age is set as a positive int
   */
  function test_get_minimum_age_is_valid_when_the_option_is_int(){
    update_option('eav_minimum_age',200);
    $this->assertThat(option::getMinimumAge(), $this->logicalAnd(
      $this->isType('int'),
      $this->equalTo(200)
    ));
  }

  /**
   * Confirms get option runs correctly when the option does not exist
   */
  function test_get_option_when_option_does_not_exist(){
    $this->assertThat(option::get('this_option_should_not_ever_exist_no_matter_what'), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option does not exist
   */
  function test_get_option_checkbox_when_option_does_not_exist(){
    $this->assertThat(option::getCheckbox('this_option_should_not_ever_exist_no_matter_what'), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is true
   */
  function test_get_option_checkbox_when_option_is_true(){
    update_option('eav_test_option',true);
    $this->assertThat(option::getCheckbox('test_option'), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is string
   */
  function test_get_option_checkbox_when_option_is_string(){
    update_option('eav_test_option',"1");
    $this->assertThat(option::getCheckbox('test_option'), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is false
   */
  function test_get_option_checkbox_when_option_is_false(){
    update_option('eav_test_option',false);
    $this->assertThat(option::getCheckbox('test_option'), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is empty
   */
  function test_get_option_checkbox_when_option_is_empty(){
    update_option('eav_test_option',"");
    $this->assertThat(option::getCheckbox('test_option'), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is empty
   */
  function test_debugger_is_active_when_option_is_empty(){
    update_option('eav_debug_mode_enabled',"");
    $this->assertThat(option::debuggerIsActive(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is empty
   */
  function test_debugger_is_active_when_option_is_true(){
    update_option('eav_debug_mode_enabled',true);
    $this->assertThat(option::debuggerIsActive(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is an int
   */
  function test_debugger_is_active_when_option_is_int(){
    update_option('eav_debug_mode_enabled',1);
    $this->assertThat(option::debuggerIsActive(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is negative
   */
  function test_debugger_is_active_when_option_is_negative(){
    update_option('eav_debug_mode_enabled',-1);
    $this->assertThat(option::debuggerIsActive(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirms get checkbox runs correctly when the option is negative
   */
  function test_debugger_is_active_when_option_is_not_set(){
    delete_option('eav_debug_mode_enabled');
    $this->assertThat(option::debuggerIsActive(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

}