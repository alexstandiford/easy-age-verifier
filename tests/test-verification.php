<?php
/**
 * Test the Verification Class
 *
 * @package Easy_Age_Verifier
 */

use eav\app\verification;

/**
 * Test The Verification Class
 */
class VerificationTest extends WP_UnitTestCase{

  /**
   * Verifies true when user is of age
   */
  function test_verify_is_of_age_returns_true_when_age_is_older_than_minimum_age(){
    $verification = new verification();
    $verification->minAge = 21;
    $verification->visitorAge = 22;
    $this->assertThat($verification->isOfAge(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies true when user is the same age as the minimum age
   */
  function test_verify_is_of_age_returns_true_when_age_is_same_as_minimum_age(){
    $verification = new verification();
    $verification->minAge = 21;
    $verification->visitorAge = 21;
    $this->assertThat($verification->isOfAge(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies false when user is younger than the minimum age
   */
  function test_verify_is_of_age_returns_true_when_age_is_younger_than_minimum_age(){
    $verification = new verification();
    $verification->minAge = 21;
    $verification->visitorAge = 20;
    $this->assertThat($verification->isOfAge(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies false when min age is a string
   */
  function test_verify_is_of_age_returns_true_when_min_age_is_not_an_int(){
    $verification = new verification();
    $verification->minAge = 'this is not an int';
    $verification->visitorAge = 20;
    $this->assertThat($verification->isOfAge(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }


  /**
   * Verifies false when visitor age is a string
   */
  function test_verify_is_of_age_returns_true_when_visitor_age_is_not_an_int(){
    $verification = new verification();
    $verification->minAge = 21;
    $verification->visitorAge = 'this is not an int';
    $this->assertThat($verification->isOfAge(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies true when visitor age is equal to overAge
   */
  function test_verify_is_of_age_returns_true_when_visitor_age_is_over_age_string(){
    $verification = new verification();
    $verification->minAge = 5000;
    $verification->visitorAge = 'overAge';
    $this->assertThat($verification->isOfAge(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies customizer is active returns false when the current page is not a customizer instance
   */
  function test_verify_checks_return_false_when_custom_check_returns_false(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_false'  => false,
        'this_returned_true'   => true,
        'this_returned_truthy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $this->assertThat($verification->verifyChecks($verification->customChecks), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies verify checks returns true when custom check all return true
   */
  function test_verify_checks_return_true_when_custom_check_returns_true(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_true'    => true,
        'this_returned_truethy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $this->assertThat($verification->verifyChecks($verification->customChecks), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_verify_return_true_when_custom_check_returns_true(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_true'    => true,
        'this_returned_truethy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $this->assertThat($verification->verify(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_verify_return_false_when_custom_check_returns_false(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_false'   => false,
        'this_returned_true'    => true,
        'this_returned_truethy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $this->assertThat($verification->verify(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_verify_return_true_when_visitor_is_not_of_age_and_custom_is_true(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_true'    => true,
        'this_returned_truethy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $verification->visitorAge = 20;
    $verification->minAge = 21;
    $this->assertThat($verification->verify(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_verify_return_false_when_visitor_is_not_of_age_and_custom_is_false(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_true'    => false,
        'this_returned_truethy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $verification->visitorAge = 20;
    $verification->minAge = 21;
    $this->assertThat($verification->verify(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_verify_return_true_when_visitor_is_of_age_and_custom_is_true(){
    add_filter('eav_custom_modal_logic', function(){
      $checks = [
        'this_returned_true'    => true,
        'this_returned_truethy' => "true",
      ];

      return $checks;
    });
    $verification = new verification();
    $verification->visitorAge = 20;
    $verification->minAge = 20;
    $this->assertThat($verification->verify(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies passed passes when user is logged in and the display verifier when logged in is set to false
   */
  function test_passed_return_true_when_logged_in(){
    $verification = new verification();
    $verification->visitorAge = 20;
    $verification->minAge = 21;
    update_option('eav_show_verifier_to_logged_in_users', false);
    $user_id = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_id);

    $this->assertThat($verification->passed(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

  /**
   * Verifies passed passes when user is logged in and the display verifier when logged in is set to true
   */
  function test_passed_return_true_when_logged_in_and_verifier_display_when_logged_in_is_true(){
    $verification = new verification();
    $verification->visitorAge = 20;
    $verification->minAge = 21;
    update_option('eav_show_verifier_to_logged_in_users', true);
    $user_id = $this->factory->user->create(array('role' => 'administrator'));
    wp_set_current_user($user_id);

    $this->assertThat($verification->passed(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_passed_return_false_when_not_of_age_and_not_logged_in(){
    $verification = new verification();
    $verification->visitorAge = 20;
    $verification->minAge = 21;

    $this->assertThat($verification->passed(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(false)
    ));
  }

  /**
   * Verifies verify returns true when custom check all return true
   */
  function test_passed_return_true_when_of_age_and_not_logged_in(){
    $verification = new verification();
    $verification->visitorAge = 21;
    $verification->minAge = 21;

    $this->assertThat($verification->passed(), $this->logicalAnd(
      $this->isType('bool'),
      $this->equalTo(true)
    ));
  }

}