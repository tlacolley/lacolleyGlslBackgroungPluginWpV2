

var sandbox ;


function loadGlsl(){

   jQuery.post(
        ajaxurl,
        {
            'action': 'mon_action',
            'param': 1
        },
        function(response){
            var linkImgs = "";
            
            for(var i=1; i<5; i++)
            {
                if(response["uploadImg"+i] != ""){
                    linkImgs += response["uploadImg"+i]+","
                } 
            };
            if(linkImgs.slice(-1)==","){
                linkImgs = linkImgs.slice(0,-1);  
            }
            // console.log(linkImgs);

            
            var canvasHtml = '<canvas id="glslCanvas" style="\
            '+response["style"]+'"\
            width="1920"height="1080" data-textures="'+linkImgs+'"></canvas>'
            $("body").prepend(canvasHtml);
            // console.log(canvasHtml);
            
            
            var canvas = document.getElementById("glslCanvas");
            sandbox = new GlslCanvas(canvas);

            eval(response["script"]);

        
            var string_frag_code = response["textFrag"];
            sandbox.load(string_frag_code);
            
            $("footer").append(response["copyrights"]);

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

