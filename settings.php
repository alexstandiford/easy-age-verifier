<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/*--- CUSTOM STYLES FOR SETTINGS PAGES ---*/
function eav_admin_styles_init(){
  $styles = [
    'eav-settings.css'
  ];
  foreach($styles as $style){
    wp_enqueue_style($style,plugin_dir_url(__FILE__).$style);
  }
}
add_action('admin_enqueue_scripts','eav_admin_styles_init');

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
    <h2>Easy Age Verifer</h2>           
      <div class="eav-wrapper">
        <form method="post" action="options.php">
        <?php
          // This prints out all hidden setting fields
          settings_fields( 'eav_options_group' );   
          do_settings_sections( 'eav-settings-admin' );
          submit_button(); 
        ?>
        </form>
      <div class="eav-admin-sidebar">
        <?php do_action('eav_settings_sidebar');?>
      </div>
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
      'eav_button_value', // ID
      'Button Text', // Title 
      array( $this, 'button_value_callback' ), // Callback
      'eav-settings-admin', // Page
      'eav_options_id' // Section           
    );      
     
    add_settings_field(
      'eav_form_type', // ID
      'How will visitors will verify their age?', // Title 
      array( $this, 'form_type_callback' ), // Callback
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
      $this->options['eav_minimum_age'] != '' && $this->options['eav_minimum_age'] != 0 ? esc_attr( $this->options['eav_minimum_age']) : apply_filters('eav_default_age',21)
    );
  }
  
  public function form_type_callback(){?>
      <select id="eav_form_type" name="eav_options[eav_form_type]">
        <option value="eav_enter_age" <?php selected($this->options['eav_form_type'], 'eav_enter_age');?>>Visitors Must Enter Their Date of Birth</option>
        <option value="eav_confirm_age" <?php selected($this->options['eav_form_type'], 'eav_confirm_age');?>>Visitors Must Confirm They're Of Age (single button)</option>
      </select>
  <?php
  }
  
  public function underage_message_callback(){
    printf(
      '<input type="text" id="eav_underage_message" name="eav_options[eav_underage_message]" value="%s" />',
      $this->options['eav_underage_message'] != '' ? esc_attr( $this->options['eav_underage_message']) : apply_filters('eav_default_underage_message','Sorry! You must be '.$this->options['eav_minimum_age'].' To visit this website.')
    );
  }
  
  public function form_title_callback(){
    printf(
      '<input type="text" id="eav_form_title" name="eav_options[eav_form_title]" value="%s" />',
      $this->options['eav_form_title'] != '' ? esc_attr( $this->options['eav_form_title']) : apply_filters('eav_default_form_title','Verify Your Age to Continue')
    );
  }
  
  public function wrapper_class_callback(){
    printf(
      '<input type="text" id="eav_wrapper_class" name="eav_options[eav_wrapper_class]" value="%s" />',
      $this->options['eav_wrapper_class'] != '' ? esc_attr( $this->options['eav_wrapper_class']) : apply_filters('eav_default_wrapper_class','taseav-age-verify')
    );
  }
  
  public function form_class_callback(){
    printf(
      '<input type="text" id="eav_form_class" name="eav_options[eav_form_class]" value="%s" />',
      $this->options['eav_form_class'] != '' ? esc_attr( $this->options['eav_form_class']) : apply_filters('eav_default_wrapper_class','taseav-verify-form')
    );
  }
    
  public function button_value_callback(){
    printf(
      '<input type="text" id="eav_form_class" name="eav_options[eav_button_value]" value="%s" />',
      $this->options['eav_button_value'] != '' ? esc_attr( $this->options['eav_button_value']) : apply_filters('eav_default_button_value','Submit')
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

/*------ SETTINGS SIDEBAR ACTIONS ------*/
function eav_settings_sidebar_cta(){?>
		<div class="eav-sidebar-item">
			<h2>Easy Age Verifier was proudly made by Alex Standiford</h2>
			<p>I am here to help breweries manage their online presence faster. I do that by providing breweries with tools, tips, and tricks that make their lives easier.</p>
			<p>If you ever have <em>any</em> questions about WordPress, or need customizations to your website don't hesitate to send me a message.  I'll be happy to help you out in any way I can.</p>
			<ul>
				<li>Email: <a href="mailto:a@alexstandiford.com">a@alexstandiford.com</a></li>
				<li><a href="http://www.twitter.com/alexstandiford" target="blank">Follow me on Twitter</a></li>
				<li><a href="http://www.alexstandiford.com" target="blank">Visit my website</a></li>
			</ul>
		</div>
		<div class="signup-form">	
		<div id="mc_embed_signup">
		<form action="//alexstandiford.us2.list-manage.com/subscribe/post?u=f39d9629a4dd9dd976f09f6e5&amp;id=b6a3d349e7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
			<h2>Spend Less Time Updating Your Website</h2>
			<h3>Fill out the form below, and I'll send you</h3>
					<ul>
						<li>A list of my 3 must-have free plugins for brewers and bars</li>
						<li>Learn about the free tool that I use to spend less time managing social media</li>
						<li>A complete workflow of how to quickly promote events on Facebook, Instagram, and Twitter</li>
						<li>PDF to-do checklists that walk you through the process quickly</li>
						<li>Ongoing WordPress tips and tricks for breweries</li>
					</ul>
		<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
		<div class="mc-field-group">
			<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
		</label>
			<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
		</div>
		<div class="mc-field-group">
			<label for="mce-FNAME">First Name </label>
			<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
		</div>
		<div class="mc-field-group">
			<label for="mce-LNAME">Last Name </label>
			<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
		</div>
		<div class="mc-field-group input-group hidden">
				<li><input checked type="checkbox" value="8" name="group[18977][8]" id="mce-group[18977]-18977-3"><label for="mce-group[18977]-18977-3">Easy Age Verifier User</label></li>
		<li><input checked type="checkbox" value="2" name="group[18977][2]" id="mce-group[18977]-18977-1"><label for="mce-group[18977]-18977-1">Website Efficency Workflow</label></li>
		</ul>
		</div>
			<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" style="display:none"></div>
				<div class="response" id="mce-success-response" style="display:none"></div>
			</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_f39d9629a4dd9dd976f09f6e5_b6a3d349e7" tabindex="-1" value=""></div>
				<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</div>
		</form>
		</div>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
		<!--End mc_embed_signup-->
		</div>
<?php }
add_action('eav_settings_sidebar', 'eav_settings_sidebar_cta');