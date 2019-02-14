<?php
/*
Plugin Name: Lacolley Glsl background V4
Description: Plugin for save Glsl code in DB and display this code in Background
Version: 0.4
Author: Tlacolley
*/
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include_once plugin_dir_path(__FILE__).'display_admin_plugin.php';
include_once plugin_dir_path(__FILE__).'display_front_plugin.php';
include_once plugin_dir_path(__FILE__).'dataWebGl.php';

// The Main object/Plugin.
class Lacolley_Glsl_Background_Plugin{
    public $front;
    public $admin;
    
    public function __construct()
    {
        register_activation_hook(__FILE__, array($this,'install'));
        $admin  = new Background_Glsl_admin();
        add_action('admin_menu', array($admin, 'add_admin_menu')); 
        register_uninstall_hook(__FILE__, array('Lacolley_Glsl_Background_Plugin','uninstall'));
    }
    //Funtion Install/ Creation DB
    // Soucis durant l activation du plugin avec la function CreateTable
    public static function install()
    {
        DataWebGl::createTable();
    }

    public static function uninstall()
    {
        DataWebGl::createTadeleteTableble();
    }

    public static function save($saveGlsl){
        // Put the condition out save() and use variables 
        $formArray = []; 
        foreach($saveGlsl as $key => $value) {
            $formArray[$key] = $value;
            if($value==Null){
                $formArray[$key]=Null;
            }
        }
        DataWebGl::saveDB($formArray);
    }

    public static function read($name){ 
        $result = DataWebGl::read($name);
        return $result;
    }

    public static function list(){
        $arraylist = DataWebGl::listAll();
        return $arraylist;
    }

    public function selectBG($selectBgStr){
        global $wpdb;  
        $selectBg = DataWebGl::read($selectBgStr);           
        DataWebGl::saveDB($selectBg); 
    }

    public function selectedBG(){   
        $result = DataWebGl::selectedBG();
        return $result;
    }
}


$plugin = new Lacolley_Glsl_Background_Plugin();
$plugin->admin = new Background_Glsl_admin();
$plugin->front = new Background_Glsl_front();


if (isset($_POST['selectBG']) && !empty($_POST['selectBG'])) {
    $plugin->selectBG($_POST['selectBG']);
}

if (isset($_POST['nameFrag']) && !empty($_POST['nameFrag'])){
    $plugin->save($_POST);

}else{


}




    // ======================New Methode=================

    add_action('wp_enqueue_scripts',function() use (&$plugin) {$plugin->front->ajax_load_scripts();},50);

    add_action( 'wp_ajax_mon_action', function() use (&$plugin) {$plugin->front->responseAjaxDisplay();} );
    add_action( 'wp_ajax_nopriv_mon_action', function() use (&$plugin) {$plugin->front->responseAjaxDisplay();} );
?>