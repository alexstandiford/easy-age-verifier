<?php
/**
 * Debug logger
 * @author: Alex Standiford
 * @date  : 2/20/2017
 */

namespace eav\app;

use eav\config\option;

class debugger{

  public function __construct(){
    $this->verification = new verification(true);
    $this->verifier = new verifier($this->verification);
    $this->verifier->templatePath = $this->verifier->templatePath();
    $this->verifier->hasLegacyOverride = $this->verifier->hasLegacyOverride();
    $this->eavVersion = get_plugin_data(EAV_PATH.'easy-age-verifier.php', false)['Version'];
    $this->wpInfo = $this->getWpInfo();
    $this->plugins = get_plugins();
    $this->scriptIsLoadable = is_readable(EAV_ASSETS_PATH.'js/verifier.js');
    $this->cssIsLoadable = is_readable(EAV_ASSETS_PATH.'css/verifier.css');
  }

  public function getWpInfo(){
    $info_to_get = array('url', 'version', 'stylesheet_url');
    $blog_info = array();

    foreach($info_to_get as $info){
      $blog_info[$info] = get_bloginfo($info);
    }

    return $blog_info;
  }

  public function generateInfo(){
    ob_start();
    include(EAV_TEMPLATES_PATH.'admin/debugInfo.php');
    $debug_info = ob_get_clean();

    return htmlspecialchars($debug_info);
  }

  public function toggleButtonText(){
    return option::debuggerIsActive() ? "Disable" : "Enable";
  }

  public function debugModeStatus(){
    return option::debuggerIsActive() ? "Enabled" : "Disabled";
  }
}