jQuery(document).ready(function(){
    // ---------Variables--------
    var btnAdd = jQuery("#addBg");
    var btnDelete = jQuery(".btnDelete");
    var listGlsl =jQuery(".listGlsl");
    var itemsList = jQuery(".listbtnBg");
    
    var createForm = jQuery("#containCreateForm");
    var contain = jQuery(".adminPluginBgGlslCanvas");
    
    
    // Check if a Bg is used and fild the form 

  jQuery.post(
     ajaxurl,
     {
         'action': 'selected_bg',
     },
     function(response){
        jQuery('#idBgObject').val(response["id"]);        
        jQuery('input[name="nameFrag"]').val(response["name"]);                
        jQuery('#textFragInput').val(response["textFrag"]);
        jQuery('#scriptInput').val(response["script"]);
        jQuery('#styleInput').val(response["style"]);
        jQuery('input[name="copyInput"]').val(response["copyrights"]);      
        
        jQuery("#listBg_"+response["id"]).addClass("selectedList");
        
     }
     )
    
// Function on Click button Add
    btnAdd.click(function(){
        createForm.find("input[type=text],input[type=hidden], textarea").val("");
        
    });

// Function on Click button Edit
    itemsList.click(function(){
        var idList = this.id; 
        var id =  idList.replace('listBg_', '');;
        id = parseInt(id)
        
        jQuery.post(
            ajaxurl,
            {
                'action': 'edit_form',
                'idBg': id
            },
            function(response){
                jQuery('#idBgObject').val(id);
                jQuery('input[name="nameFrag"]').val(response["name"]);                
                jQuery('#textFragInput').val(response["textFrag"]);
                jQuery('#scriptInput').val(response["script"]);
                jQuery('#styleInput').val(response["style"]);
                jQuery('input[name="copyInput"]').val(response["copyrights"]);
            }
            )



    });
    // Function doubleClik Select Bg
    itemsList.dblclick(function(){
        // RemoveClass selected for all element and assigne it to the dblclick selected
        itemsList.removeClass("selectedList");
        jQuery(this).addClass("selectedList");

        var idList = this.id; 

        var id =  idList.replace('listBg_', '');;
        console.log(id);
    
        jQuery.post(
            ajaxurl,
            {
                'action': 'select_bg',
                'idBgSelect': id
            },
            function(response){
            }
            )
    

    })
// Function on Click button Delete
    btnDelete.click(function(){
        var idBg = this.id; 
        var id =  idBg.replace('supr_', '');;
        id = parseInt(id)

        if (confirm("Are you sure to delete ?")) {
            jQuery.post(
                ajaxurl,
                {
                    'action': 'delete_bg',
                    'idBgDelete': id
                },
                function(response){
                    id = id.toString();
                    jQuery("#listBg_"+id).remove();
                }
                )
                
        };
        return false;


    });










});