<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/*--- CUSTOM STYLES FOR SETTINGS PAGES ---*/
function eav_admin_styles_init(){
  $styles = array(
    'eav-settings.css'
  );
  foreach($styles as $style){
    wp_enqueue_style($style,plugin_dir_url(__FILE__).$style);
  }
}
add_action('admin_enqueue_scripts','eav_admin_styles_init');

class eavOption{
	public function __construct($ID,$title,$tab,$object = null,$page = null,$prefix='eav_'){
		$this->ID = $prefix.$ID;
		$this->title = $title;
		if($object == null){
			$this->callback = ($callback == null) ? $this->ID.'_callback' : $callback;
		}
		else{
			$this->callback = ($callback == null) ? array($object,$this->ID.'_callback') : array($object,$callback);
		}
		$this->page = ($page == null) ? 'eav-settings-admin' : $page;
		$this->section = $tab->ID;
	}
}

class eavOptionTab{
	public function __construct($ID,$tab_title,$description = null,$settings_field = null,$settings_section = null,$prefix = 'eav_'){
		$this->ID = $prefix.strtolower($ID);
		$this->settingsField = ($settings_field == null) ? $this->ID : $settings_field;
		$this->settingsSection = ($settings_section == null) ? $this->ID : $settings_section;
		$this->tabTitle = $tab_title;
		$this->tab = $this->get_tab();
		$this->description = $description;
	}
	
	public function active_tab(){
		if(isset($_GET['tab'])){
    	$active_tab = $_GET['tab'];
  	}
		else{
			$active_tab = 'options_id';
		}
		return $active_tab;
	}
	
	public function is_active(){
		$active_tab = $this->active_tab();

		if($this->ID == $active_tab){
			$result = true;
		}
		else{
			$result = false;
		}
		return $result;
	}
	
	private function get_tab(){
		$active_tab = $this->active_tab();
		$tab_class = 'nav-tab';

		if($this->is_active()){
			$tab_class .= ' nav-tab-active';
		}
		
		$result = '<a href="?page=easy-age-verifier-settings&tab='.$this->ID.'" class="'.$tab_class.'">'.$this->tabTitle.'</a>';
		return $result;
	}
	
}

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
		$this->options = array(
			new eavOption('minimum_age','Minimum Age',$this),
			new eavOption('form_title','Form Title',$this),
			new eavOption('underage_message','Underage Message',$this),
			new eavOption('wrapper_class','Wrapper Class',$this),
			new eavOption('form_class','Form Class',$this),
			new eavOption('button_value','Button Text',$this),
			new eavOption('form_type','How will visitors will verify their age?',$this),
			new eavOption('over_age_value','Over age button value<br><h5>Only applies to confirm age form.</h5>',$this),
			new eavOption('under_age_value','Under age button value<br><h5>Only applies to confirm age form.</h5>',$this),
			new eavOption('debug','Debug Mode<br><h5>Debug Mode may help support solve your issue.</h5>',$this),
		);
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
		
		foreach($this->options as $option){
			add_settings_field(
				$option->ID,
				$option->title,
				$option->callback,
				$option->page,
				$option->section 
			);      
		}
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
  public function eav_minimum_age_callback(){
    printf(
      '<input type="number" id="eav_minimum_age" name="eav_options[eav_minimum_age]" value="%s" />',
      $this->options['eav_minimum_age'] != '' && $this->options['eav_minimum_age'] != 0 ? esc_attr( $this->options['eav_minimum_age']) : apply_filters('eav_default_age',21)
    );
  }
  
  public function eav_form_type_callback(){?>
      <select id="eav_form_type" name="eav_options[eav_form_type]">
        <option value="eav_enter_age" <?php selected($this->options['eav_form_type'], 'eav_enter_age');?>>Enter Age Form (Visitors Must Enter Their Date of Birth)</option>
        <option value="eav_confirm_age" <?php selected($this->options['eav_form_type'], 'eav_confirm_age');?>>Confirm Age Form (Visitors Must Confirm They're Of Age)</option>
      </select>
  <?php
  }
  
  public function eav_underage_message_callback(){
    printf(
      '<input type="text" id="eav_underage_message" name="eav_options[eav_underage_message]" value="%s" />',
      $this->options['eav_underage_message'] != '' ? esc_attr( $this->options['eav_underage_message']) : apply_filters('eav_default_underage_message','Sorry! You must be '.$this->options['eav_minimum_age'].' To visit this website.')
    );
  }
  
  public function eav_over_age_value_callback(){
    printf(
      '<input type="text" id="eav_over_age_value" name="eav_options[eav_over_age_value]" value="%s" />',
      $this->options['eav_over_age_value'] != '' ? esc_attr( $this->options['eav_over_age_value']) : apply_filters('eav_over_age_value',"I am ".$this->options['eav_minimum_age']." or older.")
    );
  }
  
  public function eav_under_age_value_callback(){
    printf(
      '<input type="text" id="eav_under_age_value" name="eav_options[eav_under_age_value]" value="%s" />',
      $this->options['eav_under_age_value'] != '' ? esc_attr( $this->options['eav_under_age_value']) : apply_filters('under_age_value',"I am under ".$this->options['eav_minimum_age'])
    );
  }

  public function eav_form_title_callback(){
    printf(
      '<input type="text" id="eav_form_title" name="eav_options[eav_form_title]" value="%s" />',
      $this->options['eav_form_title'] != '' ? esc_attr( $this->options['eav_form_title']) : apply_filters('eav_default_form_title','Verify Your Age to Continue')
    );
  }
  
  public function eav_wrapper_class_callback(){
    printf(
      '<input type="text" id="eav_wrapper_class" name="eav_options[eav_wrapper_class]" value="%s" />',
      $this->options['eav_wrapper_class'] != '' ? esc_attr( $this->options['eav_wrapper_class']) : apply_filters('eav_default_wrapper_class','taseav-age-verify')
    );
  }
  
  public function eav_form_class_callback(){
    printf(
      '<input type="text" id="eav_form_class" name="eav_options[eav_form_class]" value="%s" />',
      $this->options['eav_form_class'] != '' ? esc_attr( $this->options['eav_form_class']) : apply_filters('eav_default_wrapper_class','taseav-verify-form')
    );
  }
    
  public function eav_button_value_callback(){
    printf(
      '<input type="text" id="eav_form_class" name="eav_options[eav_button_value]" value="%s" />',
      $this->options['eav_button_value'] != '' ? esc_attr( $this->options['eav_button_value']) : apply_filters('eav_default_button_value','Submit')
    );
  }
  
  public function eav_debug_callback(){
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
				<li><a href="http://www.twitter.com/fillyourtaproom" target="blank">Follow me on Twitter</a></li>
				<li><a href="http://www.fillyourtaproom.com" target="blank">Visit my website</a></li>
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