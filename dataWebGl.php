<?php
// =====================================
//  TO DO 
// Expression reguliere 

// ================================
//  Object Data for all DB query 
  class DataWebGl{
    // Array for create db col name
    //  In construct create table with the col values
    public static function createTable(){
        // If function in private out of CreateTable Generate an error during activation
        static $arrayCol= ["name VARCHAR(255)NOT NULL","textFrag TEXT","script TEXT","style TEXT","used BOOL NOT NULL DEFAULT false"];
        global $wpdb;
        $column = "";
        // Verifier This or self 
        foreach($arrayCol as $col=>$val){ 
                    $column = $column.",".$val;
                };
        $query = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."glsl_background (id INT AUTO_INCREMENT PRIMARY KEY $column)"; 
        $wpdb->query($query);

 }
    // Function for delete the Table in DB 
    public static function deleteTable(){
        global $wpdb;
        $query = "DROP TABLE IF EXISTS ".$wpdb->prefix."glsl_background";
        $wpdb->query($query);
    }
 
    // Function saveDB for save or update values in DB
    public static function  saveDB($array){

        global $wpdb;
// =======Get the Name from DB table Without Id and last column (bool)=======
        $colNameDb = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '.$wpdb->prefix.glsl_background'";
        $showCol = "SHOW COLUMNS FROM ".$wpdb->prefix."glsl_background"."";

        $resultColName= $wpdb->get_results($showCol);
        $colName=[];
        foreach($resultColName as $col){
            array_push($colName,$col->Field );
        };
        array_pop($colName);
        array_shift($colName);
        //  Condition for select the name between both form( create form or selectBG from) in display_admin_plugin.php
        if($array["nameFrag"]){
            $nameSearch = $array["nameFrag"];
        }
        else if($array["name"]){
            $nameSearch = $array["name"];
        }
        // Query for see if row with the name exist or not
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$nameSearch}'");
        // If row doesn't exist, Create in insert form values in DB 
        if (is_null($row)) {   

            $arrayToinsert = array_combine($colName,$array);   
            $wpdb->insert("{$wpdb->prefix}glsl_background", $arrayToinsert);    
            echo 'Code uploaded successfully';

        }
        // If row exist, Update the row and set used True, It's use only for select the BG
        else {
            $optionSelect = $array["name"];
            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE used = 1");
            if($row){
                
            //    Change Boolean True to False for old Background 
                $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = 0 WHERE id = '{$row->id}'";
                $wpdb->query($query);
        
            // Set Boolean true for new background selected
                $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$optionSelect}'");
                $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = '1' WHERE id = '{$row->id}'";
                $wpdb->query($query);
        
                //  Finir le choix dans la db Du backGround 
            }
            else{    
                $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$optionSelect}'");
                $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = '1' WHERE id = '{$row->id}'";
                $wpdb->query($query);
            }
        } 

    }

    // Function for delete the row with the ID( It's not use yet)
    public static function delete($idSelect){
        global $wpdb;
        $query = "DELETE * FROM {$wpdb->prefix}glsl_background WHERE id = '{$idSelect}'";
        $element = $wpdb->query($query);
        return "Element deleted";
    }

    // Function read for the name of the row, The output it's an array.
    public static function read($name){
        global $wpdb;
        $queryname = "SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$name}'";
        $object =  $wpdb->get_results($queryname);
        $result =  json_decode(json_encode($object[0]), True);
        return $result;
    }
    // Function for get the BG who is selected. Query on the bool used.
    public static function selectedBG(){
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}glsl_background WHERE used = '1'";
        $result = $wpdb->get_results($query);
        return $result;
    }
    // Function for get all the row from the DB
    public static function listAll(){
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}glsl_background";
        $result = $wpdb->get_results($query);
        $listArray=[];
        foreach ($result as $row){   
            $listArray[] = $row;
        };
        return $listArray;
    }

}?>