<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class Background_Glsl_front{
    public function __construct()
    {
        // add_action('wp_load', array($this, 'displayBackground'));
        // add_action( 'wp_enqueue_style', array($this,'enqueue_front_style') );
        add_action( 'wp_enqueue_scripts', array($this,'enqueue_front_script') );
    }


    public function displayBackground()
    {
        // Call Function to request Lacolley to get Background

        // $glslSelect = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}glsl_background WHERE used = 1");
        // echo nl2br( $glslSelect[0]->textFrag);
       
        // <!-- Canvas to display !!!!!!!!!! -->

        $content = `<canvas id="glslCanvas" data-fragment="<?php
         echo  $glslSelect[0]->textFrag ?>    
        " width="100%" height="100%" ></canvas> `;
        
        $content = "<h1> Hello Journey </h1>";

        return $content;
    }
    
    public function enqueue_front_script() 
    {
        wp_enqueue_script( 'enqueue_GlslCanvas', plugins_url('script/GlslCanvas.js', __FILE__) ); 
        wp_enqueue_script( 'enqueue_own_js', plugins_url('script/scriptCanvas.js', __FILE__), array( 'jquery' ) ); 

    }
    public function enqueue_front_style()
    {     
        wp_enqueue_style( 'styleBgGlsl',plugins_url('css/styleBgGlsl.css', __FILE__) );

    }
   
}
?>