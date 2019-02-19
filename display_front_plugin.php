<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class Background_Glsl_front{
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array($this,'enqueue_front_script'),50 );
        add_action( 'wp_enqueue_scripts', array($this,'enqueue_front_style'),50 );
    }

    public function enqueue_front_script() 
    {
        wp_enqueue_script( 'enqueue_GlslCanvas', plugins_url('script/GlslCanvas.js', __FILE__) ); 
    }

    public function enqueue_front_style()
    {     
        wp_enqueue_style( 'styleBgGlsl',plugins_url('css/styleBgGlsl.css', __FILE__) );
    }


// =======================New Methode====================

        // Add Js Script enqueue and Hook over the Ajax
    public function ajax_load_scripts() {
            wp_enqueue_script( 'scriptAjax', plugins_url('script/scriptAjax.js', __FILE__), array( 'jquery' ) ); 
            wp_localize_script('scriptAjax', 'ajaxurl', admin_url( 'admin-ajax.php' ));
        }
        
        // Function To send data to Jquery in JSON fornmat
    public function responseAjaxDisplay() {
            // Get Param From Ajax JS (useless)
            $param = $_POST['param'];
            // Request DB 
            $ajax_query = DataWebGl::selectedBG();

            $ajax_query = json_decode(json_encode($ajax_query[0]), True);
            for($i=0;$i<5;$i++){
                if($ajax_query["uploadImg".$i] != ""){
                    $ajax_query["uploadImg".$i] = WP_CONTENT_URL."/uploads".$ajax_query["uploadImg".$i];
                };
            };
            // Array from DB 
            $array = array(
                        'name'    =>  $ajax_query["name"],
                        'textFrag'=>  $ajax_query["textFrag"] ,
                        'script'  =>  $ajax_query["script"],
                        'style'  =>  $ajax_query["style"],
                        'copyrights'  =>  $ajax_query["copyrights"],
                        'uploadImg1'  =>  $ajax_query["uploadImg1"],
                        'uploadImg2'  =>  $ajax_query["uploadImg2"],
                        'uploadImg3'  =>  $ajax_query["uploadImg3"],
                        'uploadImg4'  =>  $ajax_query["uploadImg4"]
             );
            //  Wp function for return a Json formated Array 
            wp_send_json($array);
            die;
        }

        
}
?>