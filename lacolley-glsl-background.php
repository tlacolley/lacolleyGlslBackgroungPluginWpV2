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

// The Main object/Plugin.
class Lacolley_Glsl_Background_Plugin{
    public $front;
    public $admin;
    
    public function __construct()
    {
        register_activation_hook(__FILE__, array($this,'install'));
        $admin  = new Background_Glsl_admin();
        add_action('admin_menu', array($admin, 'add_admin_menu')); 
        // $front = new Background_Glsl_front();
       
        register_uninstall_hook(__FILE__, array('Lacolley_Glsl_Background_Plugin','uninstall'));
    }
    //Funtion Install/ Creation DB
    public static function install()
    {
        // 
        DataWebGl::createTable();
        // $front = new Background_Glsl_front();
        // add_action( 'wp_enqueue_script', array($admin,'enqueue_admin_style') );
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
    public function hello(){
      echo "Hello";
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

    // echo("1_____________________________________________________ Aucun Nom n est entree");

}



// add_action("wp_head", array($plugin, 'hello'));


// add_action('wp_head',  function(){$plugin=new Lacolley_Glsl_Background_Plugin();
    //     $front = new Background_Glsl_front();
    //     $front->displayBackground($plugin->selectedBG());} );
    


// add_action('count_em_dude', function() use (&$total) { $total[] = count($total); } );

    // add_action('get_footer',  function() use (&$plugin) {$plugin->front->displayBackground($plugin->selectedBG());} );
    add_action('the_post',  function() {echo"Hello World";} );

// utiliser ces hook pour ajouter les fichier js et css ou l on aura concaterner les ficher de la db 
// cree une fonction dans le display pour le js et le css passer par ajax. 
//  Regarder a la creation du js les request ajax pour le front. 
    // add_action('wp_footer',  function() {echo"<button id='btnTest'> test  </button>";} );

    add_action( 'admin_footer', function() use (&$plugin) {$plugin->front->displayJsCanvas($plugin->selectedBG());} ); // Write our JS below here

    // add_action( 'wp_enqueue_scripts', function() use (&$plugin) {$plugin->front->displayJsCanvas($plugin->selectedBG());} ); // Write our JS below here
    // add_action( 'wp_footer', function() use (&$plugin) {$plugin->front->displayJsCanvas($plugin->selectedBG());} ); // Write our JS below here

    // add_action('wp_footer',  function() use (&$plugin) {$plugin->front->my_action();});
// wp_enqueue_script( 'enqueue_own_js', plugins_url('script/scriptCanvas.js', __FILE__), array( 'jquery' ) ); 
    

    // add_action( 'wp_ajax_my_action', function() use (&$plugin) {$plugin->front->my_action();} );
    // add_action( 'wp_ajax_nopriv_my_action', function() use (&$plugin) {$plugin->front->my_action();} );


    // add_action( 'wp_footer', function() use (&$plugin) {$plugin->front->displayJsCanvas($plugin->selectedBG());} ); // Write our JS below here
    // add_action( 'wp_footer', function() use (&$plugin) {$plugin->front->my_action();} );

    

    
    
    // ======================New Methode=================


    add_action('wp_enqueue_scripts',function() use (&$plugin) {$plugin->front->ajax_load_scripts();},50);

    add_action( 'wp_ajax_mon_action', function() use (&$plugin) {$plugin->front->responseAjaxDisplay();} );
    add_action( 'wp_ajax_nopriv_mon_action', function() use (&$plugin) {$plugin->front->responseAjaxDisplay();} );
    



?>