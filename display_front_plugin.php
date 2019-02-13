<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class Background_Glsl_front{
    public function __construct()
    {
        // add_action('wp_load', array($this, 'displayBackground'));
        // add_action( 'wp_enqueue_scripts', array($this,'enqueue_front_style') );
        add_action( 'wp_enqueue_scripts', array($this,'enqueue_front_script'),50 );
        // add_action( 'wp_enqueue_scripts', array($this,'pw_load_scripts') );

        
        // add_action('wp_head', array($this, 'displayBackground' ));

    }

    public function enqueue_front_script() 
    {
        wp_enqueue_script( 'enqueue_GlslCanvas', plugins_url('script/GlslCanvas.js', __FILE__) ); 
        // wp_enqueue_script( 'enqueue_own_js', plugins_url('script/scriptCanvas.js', __FILE__), array( 'jquery' ) ); 
  
       
        // Get file front Db to display to heade css and footer JS 
    }
    public function enqueue_front_style()
    {     
        // wp_enqueue_style( 'styleBgGlsl',plugins_url('css/styleBgGlsl.css', __FILE__) );
    }
    public function displayBackground($selectedBG)
    {
        
    
    //    <!-- <canvas id="glslCanvas" data-fragment="-->
    //     <?php
    //     // echo  $selectedBG[0]->textFrag ?>  
    <!-- //    "width="100%" height="100%" ></canvas> --> <?php 
        $content = "<h1> Hello Journey </h1>";
        echo($content);
    }

    public function displayJsCanvas($dataBg){ 
        // var_dump("plup");
        // var_dump($dataBg);
        // die;
        $dataBg = json_decode(json_encode($dataBg[0]), True);
        ?>
        <script type="text/javascript" >
            var sandbox;

	        jQuery(document).ready(function($) {
                
                var data = {
                    'action': 'my_action',
                    'whatever': 1234 ,
                    'textFrag': '<?php echo $dataBg["textFrag"]; ?>',
                    'script'  :'<?php echo $dataBg["script"];?>'
                };
                
                // console.log("STOOOOP");
                
                // var canvas = document.getElementById("glslCanvas");
                // sandbox = new GlslCanvas(canvas);

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    alert('Got this from the server: ' + data['script']+" "+response);
                        <?php
                            // var_dump("Stop Here");
                            // die;
                        ?>
                });
    });
    
	</script> 
    <?php
    
    
}
        function my_action2() {
            // var_dump("Ajax Here");
            // die;
            global $wpdb; // this is how you get access to the database
            
            $whatever = intval( $_POST['whatever'] );
            // $whatever += 10;
        
                echo $whatever;
        
            wp_die(); // this is required to terminate immediately and return a proper response
        }

        // add_action( 'wp_enqueue_scripts', 'add_my_script' );




// =======================New Methode====================

        function ajax_load_scripts() {
 
            // wp_enqueue_script('pw-script', plugin_dir_url( __FILE__ ) . 'script/scriptAjax.js');
            wp_enqueue_script( 'scriptAjax', plugins_url('script/scriptAjax.js', __FILE__), array( 'jquery' ) ); 
            
            wp_localize_script('scriptAjax', 'ajaxurl', admin_url( 'admin-ajax.php' ));
        //     wp_localize_script( 'scriptAjax', 'ajaxurl', array(
		// 	'name' => __('Hey! You have clicked the button!', 'pippin'),
        //     'textFrag' => __('You have clicked the other button. Good job!', 'pippin'),
        //     'script' => __('You have clicked the other button. Good job!', 'pippin'),
        //     'css' => __('You have clicked the other button. Good job!', 'pippin'),

            
		// ) ); 
        }
        
        // Function To send data to Jquery in JSON fornmat
        function responseAjaxDisplay() {
                
            $param = $_POST['param'];
               
            $ajax_query = DataWebGl::selectedBG();
            //  var_dump( $ajax_query); 

            $ajax_query = json_decode(json_encode($ajax_query[0]), True);


            $array = array(
                        'name'    =>  $ajax_query["name"],
                        'textFrag'=>  $ajax_query["textFrag"] ,
                        'script'  =>  $ajax_query["script"],
                        'style'  =>  $ajax_query["style"]
             );

            wp_send_json($array);
   


           
        }

}
?>