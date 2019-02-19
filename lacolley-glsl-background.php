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
        DataWebGl::deleteTable();
    }

    public function saveFile(){
        for ($i = 1; $i <= 4; $i++) {
            $i = strval($i);
            if(isset($_FILES['uploadImg'.$i])){
                $errors= array();
                $file_name = $_FILES['uploadImg'.$i]['name'];
                $file_size =$_FILES['uploadImg'.$i]['size'];
                $file_tmp =$_FILES['uploadImg'.$i]['tmp_name'];
                $file_type=$_FILES['uploadImg'.$i]['type'];
                $tmp = explode('.', $_FILES['uploadImg'.$i]['name']);
                $file_ext=strtolower(end($tmp));
                
                $extensions= array("jpeg","jpg","png");
                
                if(in_array($file_ext,$extensions)=== false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                
                if($file_size > 2097152){
                $errors[]='File size must be excately 2 MB';
                }
                
                if(empty($errors)==true){

                if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
                if(!function_exists('wp_get_current_user')) {
                    include(ABSPATH . "wp-includes/pluggable.php"); 
                }
                $uploadedfile = $_FILES['uploadImg'.$i];
                $upload_overrides = array( 'test_form' => false );
                add_filter('upload_dir',  array($this,'my_upload_dir') );
                $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
                remove_filter('upload_dir', array($this,'my_upload_dir'));

                if ( $movefile ) {
                    echo "File is valid, and was successfully uploaded.\n";
                    // var_dump( $movefile);
                } else {
                    echo "Possible file upload attack!\n";
                }
                
                echo "Success";
            }else{
                print_r($errors);
            }
        }
    }
}
    public function my_upload_dir($upload) {

    $upload['subdir'] = '/img_Glsl' . $upload['subdir'];

    $upload['path']   = $upload['basedir'] . $upload['subdir'];

    $upload['url']    = $upload['baseurl'] . $upload['subdir'];

    // var_dump($upload);
    // array(6) { ["path"]=> string(77) "/home/ratewar/Codes/FabienLacan/portfolio/wp-content/uploads/img_Glsl/2019/02" 
    // ["url"]=> string(58) "http://lacan-portfolio/wp-content/uploads/img_Glsl/2019/02" 
    // ["subdir"]=> string(17) "/img_Glsl/2019/02" 
    // ["basedir"]=> string(60) "/home/ratewar/Codes/FabienLacan/portfolio/wp-content/uploads"
    //  ["baseurl"]=> string(41) "http://lacan-portfolio/wp-content/uploads" 
    // ["error"]=> bool(false) } 
    // die;
    return $upload;
    }




    // Function to send the create request to the Db
    public static function create($formInputs,$fileInputs){
        $formArray = []; 
        // var_dump($formInputs);
        // die;
        foreach($formInputs as $key => $value) {
            $formArray[$key] = $value;
            if($value==Null){
                $formArray[$key]=Null;
            }
        }
        foreach($fileInputs as $key => $value) {
           
            if($value["name"] != ""){
                $formArray[$key] =  wp_upload_dir()["subdir"] ."/". $value["name"];
            }
            else{
                $formArray[$key] = Null;
            }

        }
        


        DataWebGl::create($formArray);
    }

    public static function save($saveGlsl,$saveFiles){
        // Put the condition out save() and use variables 
        $formArray = []; 
        foreach($saveGlsl as $key => $value) {
            $formArray[$key] = $value;
            if($value==Null){
                $formArray[$key]=Null;
            }
        }
        foreach($saveFiles as $key => $value) {
            if($value["name"] != ""){
                
                $formArray[$key] =  wp_upload_dir()["subdir"]."/". $value["name"];
            }
            else{
                $formArray[$key] = Null;
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




// echo `<script language="javascript">\
//   if (confirm("Are you sure to update ?")) {\
    
    //   };\
    //   return false;\
    //   </script>`;
    
// Create the Background Glsl, check if name not empty
if (isset($_POST['inputId']) && !empty($_POST['inputId'])){

    // echo "onclick='return confirm(\'Are you sure you want to submit this form?\');'";
    if(isset($_FILES['uploadImg1']) && !empty($_FILES['uploadImg1'])){
        $plugin->save($_POST,$_FILES);  
    }
    else{
        $plugin->save($_POST);
    }

  }
else if(isset($_POST['nameFrag']) && !empty($_POST['nameFrag'])){
    if(isset($_FILES['uploadImg1']) && !empty($_FILES['uploadImg1'])){
        $plugin->create($_POST,$_FILES);
    }
    else{
        $plugin->create($_POST);
    }

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