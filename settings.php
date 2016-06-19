<?php
class eavSettings{
  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;

  /**
   * Start up
   */
  public function __construct(){
    add_action('admin_menu', array($this, 'add_plugin_page'));
    add_action('admin_init', array($this, 'page_init'));
  }

  /**
   * Add options page
   */
  public function add_plugin_page(){
    // This page will be under "Settings"
    add_options_page(
      'Settings Admin', 
      'Easy Age Verifier', 
      'manage_options', 
      'easy-age-verifier-settings', 
      array( $this, 'create_admin_page' )
    );
  }

  /**
   * Options page callback
   */
  public function create_admin_page(){
    // Set class property
    $this->options = get_option( 'eav_options' );
    ?>
    <div class="wrap">
      <h2>Easy Age Verifer</h2>           
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields( 'eav_options_group' );   
        do_settings_sections( 'eav-settings-admin' );
        submit_button(); 
      ?>
      </form>
    </div>
    <?php
  }

  /**
   * Register and add settings
   */
  public function page_init(){        
    register_setting(
      'eav_options_group', // Option group
      'eav_options' // Option name
    );

    add_settings_section(
      'eav_options_id', // ID
      'Easy Age Verifier Settings', // Title
      array( $this, 'print_section_info' ), // Callback
      'eav-settings-admin' // Page
    );  

    add_settings_field(
      'eav_minimum_age', // ID
      'Minimum Age', // Title 
      array( $this, 'minimum_age_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
 
    add_settings_field(
      'eav_form_title', // ID
      'Form Title', // Title 
      array( $this, 'form_title_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
  
    add_settings_field(
      'eav_underage_message', // ID
      'Underage Message', // Title 
      array( $this, 'underage_message_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
  
    add_settings_field(
      'eav_wrapper_class', // ID
      'Wrapper Class', // Title 
      array( $this, 'wrapper_class_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
  
    add_settings_field(
      'eav_form_class', // ID
      'Form Class', // Title 
      array( $this, 'form_class_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
   
    add_settings_field(
      'eav_debug', // ID
      'Debug Mode', // Title 
      array( $this, 'debug_mode_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
 
  }

  /** 
   * Print the Section text
   */
  public function print_section_info(){
    print 'Enter your settings below:';
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function minimum_age_callback(){
    printf(
      '<input type="number" id="eav_minimum_age" name="eav_options[eav_minimum_age]" value="%s" />',
      $this->options['eav_minimum_age'] = '' && $this->options['eav_minimum_age'] != 0 ? esc_attr( $this->options['eav_minimum_age']) : apply_filters('eav_default_age',21)
    );
  }
  
  public function underage_message_callback(){
    printf(
      '<input type="text" id="eav_underage_message" name="eav_options[eav_underage_message]" value="%s" />',
      $this->options['eav_underage_message'] = '' ? esc_attr( $this->options['eav_underage_message']) : apply_filters('eav_default_underage_message','Sorry! You must be '.$this->options['eav_minimum_age'].' To visit this website.')
    );
  }
  
  public function form_title_callback(){
    printf(
      '<input type="text" id="eav_form_title" name="eav_options[eav_form_title]" value="%s" />',
      $this->options['eav_form_title'] = '' ? esc_attr( $this->options['eav_form_title']) : apply_filters('eav_default_form_title','Verify Your Age to Continue')
    );
  }
  
  public function wrapper_class_callback(){
    printf(
      '<input type="text" id="eav_wrapper_class" name="eav_options[eav_wrapper_class]" value="%s" />',
      $this->options['eav_wrapper_class'] = '' ? esc_attr( $this->options['eav_wrapper_class']) : apply_filters('eav_default_wrapper_class','taseav-age-verify')
    );
  }
  
  public function form_class_callback(){
    printf(
      '<input type="text" id="eav_form_class" name="eav_options[eav_form_class]" value="%s" />',
      $this->options['eav_form_class'] = '' ? esc_attr( $this->options['eav_form_class']) : apply_filters('eav_default_wrapper_class','taseav-verify-form')
    );
  }
  
  public function debug_mode_callback(){
    printf(
      '<input type="checkbox" id="eav_debug" name="eav_options[eav_debug]" value="1" %s/>',
      checked(1, $this->options['eav_debug'], false)
    );
  }

}

if(is_admin()){
  $eav_settings = new eavSettings();
}