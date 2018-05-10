<?php
/**
 * Test the Verifier Class
 *
 * @package Easy_Age_Verifier
 */

use eav\app\verifier;

/**
 * Test The Verification Class
 */
class VerifierTest extends WP_UnitTestCase{

  /**
   * Confirms set body class returns the input when the input is not an array
   */
  function test_set_body_class_when_input_is_not_array(){
    $classes = "this should be an array, but it isn't";
    $verifier = new verifier();
    $this->assertEquals($verifier->setBodyClass($classes), $classes);
  }

  /**
   * Confirms default template routes to an existing file
   */
  function test_template_path_default(){
    $verifier = new verifier();
    $this->assertFileExists($verifier->templatePath());
  }

  /**
   * Confirms default template sets to false when there is a legacy override
   */
  function test_template_path_with_legacy_override(){
    add_filter('eav_modal_template', function(){
      return 'this is a legacy modal template';
    });
    $verifier = new verifier();
    $this->assertThat($verifier->templatePath(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms legacy override returns true when there is an override filter in-place
   */
  function test_legacy_override_returns_true_when_filter_exists(){
    add_filter('eav_modal_template', function(){
      return 'this is a legacy modal template';
    });
    $verifier = new verifier();
    $this->assertThat($verifier->hasLegacyOverride(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirms legacy override returns false when there is no override filter in-place
   */
  function test_legacy_override_returns_false_when_filter_does_not_exist(){
    $verifier = new verifier();
    $this->assertThat($verifier->hasLegacyOverride(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirms legacy template returns when filter is used
   */
  function test_get_template_returns_filter_when_filter_is_set(){
    add_filter('eav_modal_template', function(){
      return true;
    });
    $verifier = new verifier();
    $this->assertThat($verifier->getTemplate(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirm custom form actions run when logic is false
   */
  function test_do_form_actions_custom_actions_run_on_false(){
    global $eav_test_check_ran;
    $eav_test_check_ran = false;
    add_filter('eav_custom_modal_logic', function(){
      return [
        'test_check' => true,
      ];
    });
    add_action('test_check_custom_is_true', function(){
      global $eav_test_check_ran;
      $eav_test_check_ran = true;
    });
    add_action('test_check_custom_is_false', function(){
      global $eav_test_check_ran;
      $eav_test_check_ran = false;
    });
    verifier::doFormActions();
    $this->assertThat($eav_test_check_ran, $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Confirm custom form actions run when logic is true
   */
  function test_do_form_actions_custom_actions_run_on_true(){
    global $eav_test_check_ran;
    $eav_test_check_ran = false;
    add_filter('eav_custom_modal_logic', function(){
      return [
        'test_check' => false,
      ];
    });
    add_action('test_check_custom_is_true', function(){
      global $eav_test_check_ran;
      $eav_test_check_ran = true;
    });
    add_action('test_check_custom_is_false', function(){
      global $eav_test_check_ran;
      $eav_test_check_ran = false;
    });
    verifier::doFormActions();
    $this->assertThat($eav_test_check_ran, $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Confirm pass data filter runs
   */
  function test_pass_data_filter(){
    add_filter('eav_pass_data', function($data){
      $data['test_data'] = true;

      return $data;
    });
    $verifier = new verifier();
    $data = $verifier->passData();
    $this->assertThat($data['test_data'], $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }
}