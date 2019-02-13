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
        wp_enqueue_script( 'enqueue_GlslCanvas', plugins_url('script/GlslCanvas.js', __FILE__) ); 
        wp_enqueue_script( 'enqueue_own_js', plugins_url('script/scriptCanvas.js', __FILE__), array( 'jquery' ) ); 
 
    }

    public function add_admin_menu()
    {
        add_menu_page('Glsl_Background_Plugin', 'Glsl_Background_Plugin', 'manage_options', 'glsl_background', array($this, 'menu_html'));
    }

    public function menu_html()
    {
    $plugin = new Lacolley_Glsl_Background_Plugin();

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
                        $list = $plugin->list(); 
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
                        
                        <?php
                        $selectedBG = $plugin->selectedBG();
                        ?>
                        <p>The current Background is : <?php echo $selectedBG[0]->name; ?></p>
                        <!-- Here for Example GLSL need load GlslCanvas  -->
                        <h2>Preview :</h2>
                      
            </div>
        </section>
         <?php
      
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