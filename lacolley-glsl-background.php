<?php
/*
Plugin Name: Lacolley Glsl background V2 
Description: Plugin for save Glsl code in DB and display this code in Background
Version: 0.3
Author: Tlacolley
*/
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
include_once plugin_dir_path(__FILE__).'display_admin_plugin.php';
include_once plugin_dir_path(__FILE__).'display_front_plugin.php';
include_once plugin_dir_path(__FILE__).'dataWebGl.php';

class Lacolley_Glsl_Background_Plugin{
    public function __construct()
    {
        register_activation_hook(__FILE__, array($this,'install'));
        $admin = new Background_Glsl_admin();
        add_action('admin_menu', array($admin, 'add_admin_menu'));
        
        $front = new Background_Glsl_front();
        register_uninstall_hook(__FILE__, array('Lacolley_Glsl_Background_Plugin','uninstall'));
    }
    //Funtion Install/ Creation DB
    public static function install()
    {
        $data = new DataWebGl();
        // $front = new Background_Glsl_front();
        

  

        // add_action( 'wp_enqueue_script', array($admin,'enqueue_admin_style') );
    }
    public static function uninstall()
    {
        global $wpdb;
        $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."glsl_background";
        $wpdb->query($query);
    }

    public static function save(){
        // formBgGlslPlugin

        $formArray = []; 

        foreach($_POST as $key => $value) {
            // echo "POST parameter '$key' has '$value'";
            $formArray[$key] = $value;

        }
        $data = new DataWebGl();
        $data->saveDB($formArray);

    }
    public static function read($name){
        $data = new DataWebGl();
        $result = $data->read($name);
        var_dump($result);
        // echo("<h1>".$result."</h1>");
        return $result;

    }
    public static function list(){
        $data = new DataWebGl();
        $arraylist = $data->listAll();
        return $arraylist;

    }
    public function selectBG(){
        
        if (isset($_POST['selectBG']) && !empty($_POST['selectBG'])) {

            global $wpdb;
        
            $optionSelect = $_POST['selectBG'];
        
            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE used = 1");
        
            if($row){
                
            //    Change Boolean True to False for old Background 
                $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = 0 WHERE id = '{$row->id}'";
                $wpdb->query($query);
        
            // Set Boolean true for new background selected
                $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$optionSelect}'");
                $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = '1' WHERE id = '{$row->id}'";
                $wpdb->query($query);
        
                //  Finir le choix dans la db Du backGround 
            }
            else{
            
                $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$optionSelect}'");
        
                $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = '1' WHERE id = '{$row->id}'";
        
                $wpdb->query($query);
            
            // var_dump($row->name);
            }
        
        
        }



    }


}
$plugin=new Lacolley_Glsl_Background_Plugin();
$admin = new Background_Glsl_admin();
$data = new DataWebGl();



 
$plugin->save();


$plugin->selectBG();

// $plugin->read("Froc");

// $list = $plugin->list();

// $admin->listBG($list);

// $data = new DataWebGl();
// $data->listAll();

//  Function to select BG 


// die();  
?>