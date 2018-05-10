<?php
/**
 * Tests the Debugger Class
 * @author: Alex Standiford
 * @date  : 5/9/18
 */


use eav\app\debugger;

require_once(EAV_PATH.'lib/app/debugger.php');

if(!defined('ABSPATH')) exit;

class DebuggerTest extends WP_UnitTestCase{
  /**
   * Confirm debug mode status works when debug mode option isn't set
   */
  function test_toggle_debug_mode_status_when_value_is_not_set(){
    delete_option('eav_debug_mode_enabled');
    $debugger = new debugger();
    $this->assertEquals($debugger->debugModeStatus(), "Disabled");
  }

  /**
   * Confirm debug mode status works when debug mode option is true
   */
  function test_toggle_debug_mode_status_when_value_is_true(){
    update_option('eav_debug_mode_enabled', true);
    $debugger = new debugger();
    $this->assertEquals($debugger->debugModeStatus(), "Enabled");
  }

  /**
   * Confirm debug mode status works when debug mode option is false
   */
  function test_toggle_debug_mode_status_when_value_is_false(){
    update_option('eav_debug_mode_enabled', false);
    $debugger = new debugger();
    $this->assertEquals($debugger->debugModeStatus(), "Disabled");
  }

  /**
   * Confirm debug mode status works when debug mode option is a string
   */
  function test_toggle_debug_mode_status_when_value_is_string(){
    update_option('eav_debug_mode_enabled', "this is a string");
    $debugger = new debugger();
    $this->assertEquals($debugger->debugModeStatus(), "Disabled");
  }

  /**
   * Confirm debug mode status works when debug mode option is an int
   */
  function test_toggle_debug_mode_status_when_value_is_int(){
    update_option('eav_debug_mode_enabled', 1);
    $debugger = new debugger();
    $this->assertEquals($debugger->debugModeStatus(), "Disabled");
  }

  /**
   * Confirm debug mode status works when debug mode option is a negative int
   */
  function test_toggle_debug_mode_status_when_value_is_negative_int(){
    update_option('eav_debug_mode_enabled', - 1);
    $debugger = new debugger();
    $this->assertEquals($debugger->debugModeStatus(), "Disabled");
  }

  /**
   * Confirm toggle button text works when debug mode option isn't set
   */
  function test_toggle_button_text_when_value_is_not_set(){
    delete_option('eav_debug_mode_enabled');
    $debugger = new debugger();
    $this->assertEquals($debugger->toggleButtonText(), "Enable");
  }

  /**
   * Confirm toggle button text works when debug mode option is true
   */
  function test_toggle_button_text_when_value_is_true(){
    update_option('eav_debug_mode_enabled', true);
    $debugger = new debugger();
    $this->assertEquals($debugger->toggleButtonText(), "Disable");
  }

  /**
   * Confirm toggle button text works when debug mode option is false
   */
  function test_toggle_button_text_when_value_is_false(){
    update_option('eav_debug_mode_enabled', false);
    $debugger = new debugger();
    $this->assertEquals($debugger->toggleButtonText(), "Enable");
  }

  /**
   * Confirm toggle button text works when debug mode option is a string
   */
  function test_toggle_button_text_when_value_is_string(){
    update_option('eav_debug_mode_enabled', "this is a string");
    $debugger = new debugger();
    $this->assertEquals($debugger->toggleButtonText(), "Enable");
  }

  /**
   * Confirm toggle button text works when debug mode option is an int
   */
  function test_toggle_button_text_when_value_is_int(){
    update_option('eav_debug_mode_enabled', 1);
    $debugger = new debugger();
    $this->assertEquals($debugger->toggleButtonText(), "Enable");
  }

  /**
   * Confirm toggle button text works when debug mode option is a negative int
   */
  function test_toggle_button_text_when_value_is_negative_int(){
    update_option('eav_debug_mode_enabled', - 1);
    $debugger = new debugger();
    $this->assertEquals($debugger->toggleButtonText(), "Enable");
  }

}