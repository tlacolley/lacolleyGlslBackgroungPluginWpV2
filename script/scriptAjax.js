

var sandbox ;


function loadGlsl(){

   jQuery.post(
        ajaxurl,
        {
            'action': 'mon_action',
            'param': 1
        },
        function(response){
            
            var canvasHtml = '<canvas id="glslCanvas" style="\
            '+response["style"]+'"\
            width="1920"height="1080" data-textures="/home/ratewar/Codes/FabienLacan/portfolio/wp-content/plugins/lacolley-glsl-background/img/05.jpg"></canvas>'
            $("body").prepend(canvasHtml);
            
            var canvas = document.getElementById("glslCanvas");
            sandbox = new GlslCanvas(canvas);

            eval(response["script"]);

 
            var string_frag_code = response["textFrag"];
            sandbox.load(string_frag_code);
            console.log("ShaderLoaded");
            

            // function main() {
            //     // Get A WebGL context
            //     var canvas = document.getElementById("glslCanvas");
            //     var gl = canvas.getContext("webgl");

            //     if (!gl) {
            //       return;
            //     }
              
    
            //     // Draw the scene.
            //     function drawScene() {   
            //         var string_frag_code = response["textFrag"];
            //         sandbox.load(string_frag_code);
            //       resize(gl.canvas);
            //       gl.viewport(0, 0, gl.canvas.width, gl.canvas.height);

            //     }
            //     drawScene();

            //     // Function to resize the Canvas
            //     function resize(canvas) {
            //       // Lookup the size the browser is displaying the canvas.
            //       var displayWidth  = canvas.clientWidth;
            //       var displayHeight = canvas.clientHeight;
              
            //       // Check if the canvas is not the same size.
            //       if (canvas.width  !== displayWidth ||
            //           canvas.height !== displayHeight) {
              
            //         // Make the canvas the same size
            //         canvas.width  = displayWidth;
            //         canvas.height = displayHeight;
            //       }
            //     }
                
            //   }


            //     main();

            }
            
            );
            
        }
jQuery(document).ready(function($) {
    
    loadGlsl();
            // $("#btnTest").click(function(){

            // })


});

