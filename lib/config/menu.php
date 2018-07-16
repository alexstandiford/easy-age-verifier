<?php
/**
 * Singleton Class for the admin menu
 * @author: Alex Standiford
 * @date  : 1/15/2017
 */

namespace eav\config;

if(!defined('ABSPATH')) exit;
//TODO: add doc blocks to this file
class menu{

  private static $instance;

  private function __construct(){
  }

  public static function register(){
    if(!isset(self::$instance)){
      self::$instance = new self;
      self::$instance->buildMenuPage();
      self::$instance->buildSubMenu();
      add_action('admin_enqueue_scripts',[self::$instance,'enqueueScripts']);
    }
  }

  public function enqueueScripts(){
    wp_enqueue_style('eav-admin',EAV_ASSETS_URL.'css/admin.css',EAV_VERSION);

    wp_register_script('eav-admin',EAV_ASSETS_URL.'js/dist/admin.js',['jquery'],EAV_VERSION);
    wp_localize_script('eav-admin','eavAdmin',['nonce' => wp_create_nonce('wp_rest'),'debugModeUrl' => get_rest_url(null,'/easy-age-verifier/v1/toggle-debug-mode')]);
    wp_enqueue_script('eav-admin');
  }

  private function buildMenuPage(){
    add_menu_page(
      'Customize Easy Age Verifier', //Page title
      'Age Verifier', //Menu title
      'manage_options', //Capability
      EAV_PREFIX.'-options', //Menu Slug
      array(self::$instance, 'redirectToCustomizer'), //Function
      'dashicons-lock' //icon
    );
  }

  public function redirectToCustomizer(){
    echo '<h1>Sorry, an error occured. Try editing your form by going to <a href="http://localhost/16002/wp-admin/customize.php?autofocus[section]=eav_section">appearance>>>customizer</a></h1>';
  }

  private function buildSubMenu(){
    $menu_items = array(
      'debug' => array(
        'menu_title' => 'Debug Verifier',
        'page_title' => '',
        'menu_slug' => 'eav-debugger',
        'callback' => array(self::$instance, 'getDebugger')
      ),
      'cta' => array(
        'menu_title' => '<hr>Free Resources for Breweries',
        'page_title' => '',
        'menu_slug'  => 'eav-resource',
        'callback'   => array(self::$instance, 'getSidebar'),
      ),
    );
    $menu_items = apply_filters(EAV_PREFIX.'_sub_menu_items', $menu_items);

    foreach($menu_items as $menu_item){
      add_submenu_page(
        EAV_PREFIX.'-options',
        $menu_item['page_title'],
        $menu_item['menu_title'],
        'manage_options',
        $menu_item['menu_slug'],
        $menu_item['callback']
      );
    }
  }

  public function getSidebar(){
    require_once(EAV_PATH.'lib/assets/templates/admin/sidebar.php');
  }

  public function getDebugger(){
    require_once(EAV_PATH.'lib/app/debugger.php');
    require_once(EAV_PATH.'lib/assets/templates/admin/debug.php');
  }

}