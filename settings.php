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
	public function __construct($ID,$title,$tab,$object = null,$page = null,$prefix='eav_',$hidden = false){
		$this->ID = $prefix.$ID;
		$this->title = __($title,'easyageverifier');
		if($object == null){
			$this->callback = $this->ID.'_callback';
		}
		else{
			$this->callback = array($object,$this->ID.'_callback');
		}
		$this->page = ($page == null) ? 'eav-settings-admin' : $page;
		$this->section = $tab->ID;
		$this->hidden = $hidden;
	}
}

class eavOptionTab{
	public function __construct($ID,$tab_title,$description = null,$settings_field = null,$settings_section = null,$prefix = 'eav_'){
		$this->ID = $prefix.strtolower($ID);
		$this->settingsField = ($settings_field == null) ? $this->ID : $settings_field;
		$this->settingsSection = ($settings_section == null) ? $this->ID : $settings_section;
		$this->tabTitle = __($tab_title,'easyageverifier');
		$this->tab = $this->get_tab();
		$this->description = __($description,'easyageverifier');
	}
	
	public function active_tab(){
		if(isset($_GET['tab'])){
    	$active_tab = $_GET['tab'];
  	}
		else{
			$active_tab = 'eav_options_id';
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
  protected $options;

  /**
   * Start up
   */
  public function __construct(){

		$this->tabs = apply_filters('eav_tabs',array(
			
			//General Settings
			'options_id' => new eavOptionTab('options_id',__('General Settings','easyageverifier'),__('General Age Verifier Options','easyageverifier'),'eav_options_group','eav-settings-admin'),
			
			//Customize Verifier
			'customize_verifier' => new eavOptionTab('customize_verifier',__('Verifier Display Settings','easyageverifier'),__('<h2>Warning! If you change these classes, your form may not work properly. This is intended for advanced users only.</h2>','easyageverifier'),'eav_customize_verifier'),
		));
		$this->settings = apply_filters('eav_options',array(
			
			//General Settings
			'minimum_age' => new eavOption('minimum_age',__('Minimum Age','easyageverifier'),$this->tabs['options_id'],$this),
			'form_type' => new eavOption('form_type',__('How will visitors will verify their age?','easyageverifier'),$this->tabs['options_id'],$this),
			'form_title' => new eavOption('form_title',__('Form Title','easyageverifier'),$this->tabs['options_id'],$this),
			'underage_message' => new eavOption('underage_message',__('Underage Message','easyageverifier'),$this->tabs['options_id'],$this),
			'button_value' => new eavOption('button_value',__('Button Text','easyageverifier'),$this->tabs['options_id'],$this),
			'over_age_value' => new eavOption('over_age_value',__('Over age button value<br><h5>Only applies to confirm age form.</h5>','easyageverifier'),$this->tabs['options_id'],$this),
			'under_age_value' => new eavOption('under_age_value',__('Under age button value<br><h5>Only applies to confirm age form.</h5>','easyageverifier'),$this->tabs['options_id'],$this),
			'debug' => new eavOption('debug',__('Debug Mode<br><h5>Debug Mode may help support solve your issue.</h5>','easyageverifier'),$this->tabs['options_id'],$this),
			
			//Customize Verifier
			'wrapper_class' => new eavOption('wrapper_class',__('Wrapper Class','easyageverifier'),$this->tabs['customize_verifier'],$this,'eav_customize_verifier'),
			'form_class' => new eavOption('form_class',__('Form Class','easyageverifier'),$this->tabs['customize_verifier'],$this,'eav_customize_verifier'),
		));
    // Set class property
    $this->options = get_option('eav_options');
  }
	
	public function init(){
		do_action('eav_modify_settings');
    add_action('admin_menu', array($this, 'add_plugin_page'));
    add_action('admin_init', array($this, 'page_init'));
	}

  /**
   * Add options page
   */
  public function add_plugin_page(){
    // This page will be under "Settings"
    add_options_page(
      __('Settings Admin','easyageverifier'), 
      __('Easy Age Verifier','easyageverifier'), 
      'manage_options', 
      'easy-age-verifier-settings', 
      array( $this, 'create_admin_page' )
    );
  }

  /**
   * Options page callback
   */
  public function create_admin_page(){
    ?>
	<div class="wrap">
    <h2><?php echo __('Easy Age Verifer','easyageverifier'); ?></h2>
      <h2 class="nav-tab-wrapper">
				<?php
					foreach($this->tabs as $tab){
						echo $tab->tab;
					}
				?>
			</h2>
     <h1>Easy Age Verifier Settings</h1>        
      <div class="eav-wrapper">
        <form method="post" action="options.php">
        <?php
          // This prints out all hidden setting fields
					settings_fields('eav_options_group');   
					foreach($this->tabs as $tab){
						if($tab->is_active()){
          		do_settings_sections($tab->settingsSection);
						}
					}
          submit_button();
        ?>
        </form>
      <div class="eav-admin-sidebar">
        <?php do_action('eav_settings_sidebar');?>
      </div>
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
			foreach($this->tabs as $tab){
				add_settings_section(
					$tab->ID, // ID
					'', // Title
					array( $this, 'print_section_info' ), // Callback
					$tab->settingsSection // Page
				);
			}
		
		//Loop through and build the options
		foreach($this->settings as $option){
			if($option->hidden != true){
				add_settings_field(
					$option->ID,
					$option->title,
					$option->callback,
					$option->page,
					$option->section 
				);
			}
		}
  }

  /** 
   * Print the Section text
   */
  public function print_section_info(){
    foreach($this->tabs as $tab){
			if($tab->is_active()){
				echo $tab->description;
			}
		}
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

function eav_settings_init(){
  $eav_settings = new eavSettings();
	$eav_settings->init();
}
add_action('init','eav_settings_init');

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