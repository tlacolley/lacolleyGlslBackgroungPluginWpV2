<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// The Admin Object, It's used for all admin display, function.
class Background_Glsl_admin{

    public function __construct()
    {
        // add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action( 'admin_enqueue_scripts', array($this,'enqueue_admin_style'),50 );
        
        
    } 

    public function enqueue_admin_style()
    {     
        wp_enqueue_style( 'styleBgGlslAdmin',plugins_url('css/adminBgGlsl.css', __FILE__) ); 
        wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.7.2/css/all.css'); 
        
        wp_enqueue_script( 'scriptAdmin',plugins_url('script/scriptAdmin.js', __FILE__), array( 'jquery' ),true );
        wp_enqueue_script( 'enqueue_GlslCanvas', plugins_url('script/GlslCanvas.js', __FILE__) ); 

        // Canvas for Glsl from scriptAjax.js is Create in page with #page
        // wp_enqueue_script( 'enqueue_own_js', plugins_url('script/scriptAjax.js', __FILE__), array( 'jquery' ),true ); 
    }

    public function add_admin_menu()
    {
        add_menu_page('Glsl Background Plugin', 'Glsl_Background_Plugin', 'manage_options', 'glsl_background', array($this, 'menu_html'));
    }
    

    public function menu_html()
    {
    $plugin = new Lacolley_Glsl_Background_Plugin();
    ?>

    <!-- Display admin form for save code Glsl  -->
    <section class="adminPluginBgGlslCanvas">
        <h1><?php echo get_admin_page_title()?></h1>
        <div class="contain">
            
            <?php $this->formCreateGlsl() ?>
            <?php $this->listCanvas() ?>
        </div>
    </section>
    <?php

    }

    public function formCreateGlsl(){?>
            <div id="containCreateForm"class="">
            <h2> Save your Glsl Here  </h2>
            <button id="addBg">+</button> <p>Click on buttom + for Create a new Glsl</p>
                <form name= "formBgGlslPlugin" id="formBgGlslPlugin" enctype="multipart/form-data" action="" method="post">
                    <input type="hidden" id="idBgObject" name="inputId">
                    <label for="nameFrag">Name of file</label>
                    <input type="text" name="nameFrag" value="">
                    <label for="textFrag">Your Frag Code Here</label>
                    <textarea name="textFrag" id="textFragInput" cols="100%" rows="8" ></textarea>
                    <label for="textFrag">Your Script Code Here</label>
                    <textarea name="scriptInput" id="scriptInput" cols="100%" rows="8" ></textarea>
                    <label for="textFrag">Your Style Code Here</label>
                    <textarea name="styleInput" id="styleInput" cols="100%" rows="8" ></textarea>
                    <label for="uploadImg1">Yours Images</label>
                    <input type="file" name="uploadImg1" />
                    <label for="uploadImg2">Yours Images</label>
                    <input type="file" name="uploadImg2" />
                    <label for="uploadImg3">Yours Images</label>
                    <input type="file" name="uploadImg3" />
                    <label for="uploadImg4">Yours Images</label>
                    <input type="file" name="uploadImg4" />
                    <label for="copyInput">Copyrights (to display in footer)</label>
                    <input type="text" name="copyInput" value="">
                    <button type="submit">Submit</button>
                </form>
            </div>
    <?php
    }


    public function listCanvas(){
        $plugin = new Lacolley_Glsl_Background_Plugin();
        $list = $plugin->list(); 
      ?>
        <!-- Section display list in admin plugin section  -->
        <div id="containList">
            <h2>List Glsl File : </h2>
            <p>Click on the name for update the code or double-clik for used the background</p>
            <ul class="listGlsl">
                <?php foreach ($list as $row):?>
                <li class="listbtnBg" id="listBg_<?php echo $row->id ?>" ><?php echo $row->name ?> 
                <button class="btnDelete" id="supr_<?php echo $row->id ?>">
                        <i class="far fa-times-circle"></i>
                    </button>
                </li>   
                <?php endforeach;?>
            </ul>
        </div>
    <?php
    }



    public function ajax_load_scripts_addmin() {
        wp_enqueue_script( 'scriptAjaxAdmin', plugins_url('script/scriptAdminjs', __FILE__), array( 'jquery' ) ); 
        wp_localize_script('scriptAjaxAdmin', 'ajaxurl', admin_url( 'admin-ajax.php' ));
    }

    public function edit_form() {
        // Get Param From Ajax JS (useless)
        $idBg = $_POST['idBg'];
        // Request DB 
        $ajax_query = DataWebGl::read($idBg);

        // $ajax_query = json_decode(json_encode($ajax_query[0]), True);
        
        // Array from DB 
        $array = array(
                    'name'    =>  $ajax_query["name"],
                    'textFrag'=>  $ajax_query["textFrag"] ,
                    'script'  =>  $ajax_query["script"],
                    'style'  =>  $ajax_query["style"],
                    'copyrights'=>$ajax_query["copyrights"],
                    'uploadImg1'  =>  $ajax_query["uploadImg1"],
                    'uploadImg2'  =>  $ajax_query["uploadImg2"],
                    'uploadImg3'  =>  $ajax_query["uploadImg3"],
                    'uploadImg4'  =>  $ajax_query["uploadImg4"]
       
         );
        //  Wp function for return a Json formated Array 
        wp_send_json($array);
        die;
    }
    public function selected_bg() {
        // Request DB          
        $ajax_query = DataWebGl::selectedBG();
        $ajax_query =  json_decode(json_encode($ajax_query[0]), True);
        // Array from DB 
        $array = array(
            'id'      => $ajax_query["id"],
            'name'    =>  $ajax_query["name"],
            'textFrag'=>  $ajax_query["textFrag"] ,
            'script'  =>  $ajax_query["script"],
            'style'  =>  $ajax_query["style"],
            'copyrights'=>$ajax_query["copyrights"],
            'uploadImg1'  =>  $ajax_query["uploadImg1"],
            'uploadImg2'  =>  $ajax_query["uploadImg2"],
            'uploadImg3'  =>  $ajax_query["uploadImg3"],
            'uploadImg4'  =>  $ajax_query["uploadImg4"]

         );

        //  Wp function for return a Json formated Array 
        wp_send_json($array);
        die();        
    }

    public function select_bg() {
        // Get Param From Ajax JS (useless)
        $idSelectBg = $_POST['idBgSelect'];
        // Request DB          
        DataWebGl::updateSelectBg($idSelectBg);
        die();        
    }

    public function delete_bg() {
        // Get Param From Ajax JS (useless)
        $idSelectBg = $_POST['idBgDelete'];
        // Request DB          
        DataWebGl::delete($idSelectBg);
        die();        
    }
}
?>