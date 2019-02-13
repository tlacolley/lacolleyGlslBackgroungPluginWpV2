var sandbox ;
jQuery(document).ready(function($) {


	

        // alert('Hey! You have clicked the button!');
        
        console.log("Hellllll");

        // $("#btnTest").click(function(){

            
            // alert( pw_script_vars.textFrag );
            
            jQuery.post(
                ajaxurl,
                {
                    'action': 'mon_action',
                    'param': 1
                },
                function(response){

                    // console.log(response["style"]);
                    // var canvasHtml = '<canvas id="glslCanvas" style="\
                    // '+response["style"]+'"\
                    // data-fragment=""width="100%" height="100%" ></canvas>'
                    
                    var canvasHtml = '<canvas id="glslCanvas" style="\
                    '+response["style"]+'"\
                    width="100%" height="100%" ></canvas>'
                    $("#page").prepend(canvasHtml);
                    
                    var canvas = document.getElementById("glslCanvas");
                    sandbox = new GlslCanvas(canvas);
                    var string_frag_code = response["textFrag"]
                    sandbox.load(string_frag_code);
                    // data-textures="'+$.get('../img/05.jpg')+'"\

                }
                );
                
                    
                    
                    // console.log(ajaxurl.name);
                // })
                
                // console.log("Plop");


});