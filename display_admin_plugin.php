<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class Background_Glsl_admin{
    public function __construct()
    {
        // add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action( 'admin_enqueue_scripts', array($this,'enqueue_admin_style') );
        
    }
    public function enqueue_admin_style()
    {     
        wp_enqueue_style( 'styleBgGlslAdmin',plugins_url('css/adminBgGlsl.css', __FILE__) );    
    }

    public function add_admin_menu()
    {
        add_menu_page('Glsl_Background_Plugin', 'Glsl_Background_Plugin', 'manage_options', 'glsl_background', array($this, 'menu_html'));
    }

    public function menu_html()
    {
    ?>
          <!-- Display admin form for save code Glsl  -->
        <section class="adminPluginBgGlslCanvas">
            <h1><?php echo get_admin_page_title()?></h1>
            <div>
                <h2> Save your Glsl Here  </h2>
                <form name= "formBgGlslPlugin" id="formBgGlslPlugin" enctype="multipart/form-data" action="" method="post">
                    <label for="nameFrag">Name of file</label>
                    <input type="text" name="nameFrag">
                    <label for="textFrag">Your Frag Code Here</label>
                    <textarea name="textFrag" id="textFragInput" cols="100%" rows="8" ></textarea>
                    <label for="textFrag">Your Script Code Here</label>
                    <textarea name="scriptInput" id="scriptInput" cols="100%" rows="8" ></textarea>
                    <label for="textFrag">Your Style Code Here</label>
                    <textarea name="styleInput" id="styleInput" cols="100%" rows="8" ></textarea>
                    <button type="submit">Submit</button>
                </form>

          
            </div>
            <div>
                    <?php
                    //  Query Glsl saved and display List of them 
                    // Request to get all File in DB  
                    // $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}glsl_background");
                    // if (!empty($result)) {
                        ?>
                     <?php 
                        $plugin = new Lacolley_Glsl_Background_Plugin();
                        $list = $plugin->list();
                        // $this->listBG($list);  
                        ?>
                        <!-- Section display list in admin plugin section  -->
                        <h3>List Glsl File :</h3>
                        <form id="formSelectBg" action="" method="post">
                            <select name="selectBG"> <?php
                                foreach ($list as $row):?>
                                    <option value="<?php echo $row->name ?>"><?php echo $row->name ?></option>
                                <?php endforeach;?>
                            </select>
                            <button id="btnSelectBG" type="submit">Select BG</button>
                        </form>


                        <p>The current Background is :</p>
                        <?php
 
                        ?>
                        <!-- Here for Example GLSL need load GlslCanvas  -->
                        <h2>Preview :</h2>
                        <!-- <canvas id="glslCanvas" data-fragment="<?php
                        // echo  $glslSelect[0]->textFrag;
                        ?>" width="200%" height="200%" style="background-color: rgba(1, 1, 1, 0);border: red 1px solid;"></canvas> -->
                        <!-- var_dump($glslSelect[0]);
                        die; -->
                       


            
            </div>
        </section>
         <?php
        //   }
    }


    public function listBG($array){
        ?> <ul>
        <?php 
        foreach($array as $row):?>

        <li><?php echo $row->name ?> bu  </li>
        

    <?php endforeach;?>
        </ul>
        <?php 
    }
}
?>