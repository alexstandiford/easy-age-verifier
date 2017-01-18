<?php
/**
 * Easy Age Verifier Customizer Class
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\config;

if(!defined('ABSPATH')) exit;

class customizer{

  private static $instance = null;

  private function __construct(){
  }

  public static function register(){
    if(!isset(self::$instance)){
      self::$instance = new self;
      self::$instance->getSections();
      self::$instance->getSimpleFields();
    }
  }

  private static function prefix($value){
    return EAV_PREFIX.'_'.$value;
  }

  /**
   * Gets the customizer sections
   * return void
   */
  private function getSections(){
    global $wp_customize;
    $wp_customize->add_section(self::prefix('section'), array(
      'title'       => __('Age Verifier', EAV_TEXT_DOMAIN),
      'description' => __('Easy Age Verifier Settings', EAV_TEXT_DOMAIN),
      'priority'    => 90,
      'capability'  => 'edit_theme_options',
    ));
  }

  /**
   * Gets the basic fields for the customizer
   * return void
   */
  private function getSimpleFields(){
    global $wp_customize;
    $minimum_age = option::get('minimum_age');
    $settings = array(
      'active_in_customizer' => array(
        'default'     => false,
        'label'       => __('Display the age verifier in the customizer', EAV_TEXT_DOMAIN),
        'description' => __('activates the age verifier in the customizer', EAV_TEXT_DOMAIN),
        'type'        => 'checkbox',
      ),
      'minimum_age'          => array(
        'default'     => 21,
        'label'       => __('Minimum Age', EAV_TEXT_DOMAIN),
        'description' => __('What is the minimum age a visitor can be?', EAV_TEXT_DOMAIN),
        'type'        => 'number',
      ),
      'underage_message'     => array(
        'default'     => __('Sorry! You must be '.$minimum_age.' to visit this website.', EAV_TEXT_DOMAIN),
        'label'       => __('Underage Message', EAV_TEXT_DOMAIN),
        'description' => __('What displays when the visitor is not of age.', EAV_TEXT_DOMAIN),
        'type'        => 'textarea',
      ),
      'form_title'           => array(
        'default'     => __('Verify Your Age to Continue.', EAV_TEXT_DOMAIN),
        'label'       => __('Form Title', EAV_TEXT_DOMAIN),
        'description' => __('The title of your verifier.', EAV_TEXT_DOMAIN),
        'type'        => 'textarea',
      ),
      'form_type'            => array(
        'default'     => __('eav_enter_age', EAV_TEXT_DOMAIN),
        'label'       => __('Form Type', EAV_TEXT_DOMAIN),
        'description' => __('Specify the form type here', EAV_TEXT_DOMAIN),
        'type'        => 'select',
        'choices'     => array(
          'eav_enter_age'   => "Ask for visitor's date of birth",
          'eav_confirm_age' => "Don't ask for visitor's date of birth",
        ),
      ),    //If the form is set to confirm age instead of verify age, show these options
      'over_age_value'       => array(
        'default'     => __('I am '.$minimum_age.' or older.', EAV_TEXT_DOMAIN),
        'label'       => __('Over Age Button Value', EAV_TEXT_DOMAIN),
        'description' => __('Specify what the over age button says here. This only applies to verifiers with over/under age buttons', EAV_TEXT_DOMAIN),
        'type'        => 'text',
      ),
      'under_age_value'      => array(
        'default'     => __('I am under '.$minimum_age, EAV_TEXT_DOMAIN),
        'label'       => __('Under Age Button Value', EAV_TEXT_DOMAIN),
        'description' => __('Specify what the under age button says here. This only applies to verifiers with over/under age buttons', EAV_TEXT_DOMAIN),
        'type'        => 'text',
      ),
      'button_value'         => array(
        'default'     => __('Submit', EAV_TEXT_DOMAIN),
        'label'       => __('Submit Button Value', EAV_TEXT_DOMAIN),
        'description' => __("Specify what the submit button says here. This only applies to verifiers that ask for the visitor's date of birth", EAV_TEXT_DOMAIN),
        'type'        => 'text',
      ),
    );

    foreach($settings as $setting => $value){
      $wp_customize->add_setting(self::prefix($setting), array(
        'type'    => 'option',
        'default' => $value['default'],
      ));

      $control_args = array(
        'label'       => $value['label'],
        'type'        => $value['type'],
        'description' => $value['description'],
        'section'     => self::prefix('section'),
        'settings'    => self::prefix($setting),
      );

      if($value['type'] == 'select'){
        $control_args['choices'] = $value['choices'];
      }

      $wp_customize->add_control(self::prefix($setting), $control_args);
    }
  }
}