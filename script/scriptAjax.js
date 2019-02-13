var sandbox ;
jQuery(document).ready(function($) {

        console.log("Hellllll");

            
            jQuery.post(
                ajaxurl,
                {
                    'action': 'mon_action',
                    'param': 1
                },
                function(response){
                    
                    var canvasHtml = '<canvas id="glslCanvas" style="\
                    '+response["style"]+'"\
                    width="100%" height="100%" ></canvas>'
                    $("#page").prepend(canvasHtml);
                    
                    var canvas = document.getElementById("glslCanvas");
                    sandbox = new GlslCanvas(canvas);
                    var string_frag_code = response["textFrag"]
                    sandbox.load(string_frag_code);
                }
                );


});