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
    // Function to send the create request to the Db
    public static function create($formInputs){
        $formArray = []; 
        foreach($formInputs as $key => $value) {
            $formArray[$key] = $value;
            if($value==Null){
                $formArray[$key]=Null;
            }
        }
        DataWebGl::create($formArray);
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

    public static function read($id){ 
        $result = DataWebGl::read($id);
        return $result;
    }

    public static function list(){
        $arraylist = DataWebGl::listAll();
        return $arraylist;
    }


    public function selectedBG(){   
        $result = DataWebGl::selectedBG();
        return $result;
    }
}


$plugin = new Lacolley_Glsl_Background_Plugin();
$plugin->admin = new Background_Glsl_admin();
$plugin->front = new Background_Glsl_front();




// Create the Background Glsl, check if name not empty
// echo `<script language="javascript">\
//   if (confirm("Are you sure to update ?")) {\

//   };\
//   return false;\
//   </script>`;
  
if (isset($_POST['inputId']) && !empty($_POST['inputId'])){

    // echo "onclick='return confirm(\'Are you sure you want to submit this form?\');'";
    $plugin->save($_POST);

  }
else if(isset($_POST['nameFrag']) && !empty($_POST['nameFrag'])){
    $plugin->create($_POST);
}





    // ======================Hooks Ajax=================

// Hooks Ajax Front
add_action('wp_enqueue_scripts',function() use (&$plugin) {$plugin->front->ajax_load_scripts();},50);
add_action( 'wp_ajax_mon_action', function() use (&$plugin) {$plugin->front->responseAjaxDisplay();} );
add_action( 'wp_ajax_nopriv_mon_action', function() use (&$plugin) {$plugin->front->responseAjaxDisplay();} );

// Hooks Ajax CRUD Admin
add_action('wp_enqueue_scripts',function() use (&$plugin) {$plugin->admin->ajax_load_scripts_addmin();},50);


// Hook for function selected Background Ajax who will fild the form 
add_action( 'wp_ajax_selected_bg', function() use (&$plugin) {$plugin->admin->selected_bg();} );
add_action( 'wp_ajax_nopriv_selected_bg', function() use (&$plugin) {$plugin->admin->selected_bg();} );

// Hook for function load text in form to edit Ajax
add_action( 'wp_ajax_edit_form', function() use (&$plugin) {$plugin->admin->edit_form();} );
add_action( 'wp_ajax_nopriv_edit_form', function() use (&$plugin) {$plugin->admin->edit_form();} );

// Hook for function select Background Ajax
add_action( 'wp_ajax_select_bg', function() use (&$plugin) {$plugin->admin->select_bg();} );
add_action( 'wp_ajax_nopriv_select_bg', function() use (&$plugin) {$plugin->admin->select_bg();} );

// Hook for function delete Ajax
add_action( 'wp_ajax_delete_bg', function() use (&$plugin) {$plugin->admin->delete_bg();} );
add_action( 'wp_ajax_nopriv_delete_bg', function() use (&$plugin) {$plugin->admin->delete_bg();});

?>