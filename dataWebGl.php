<?php
// =====================================
//  TO DO 
// Expression reguliere 
//  Refaire la Function Save de la db et Cree la function Update field;
// ================================
//  Object Data for all DB query 
  class DataWebGl{
    // Array for create db col name
    //  In construct create table with the col values
    public static function createTable(){
        // If function in private out of CreateTable Generate an error during activation
        static $arrayCol= ["name VARCHAR(255)NOT NULL","textFrag TEXT","script TEXT","style TEXT","copyrights VARCHAR(255)","uploadImg1 VARCHAR(255)","uploadImg2 VARCHAR(255)","uploadImg3 VARCHAR(255)","uploadImg4 VARCHAR(255)","used BOOL NOT NULL DEFAULT false"];
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
        // Query for get the row from the id 
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE id = '{$array["inputId"]}'");
        if($row){
            $query =  "UPDATE {$wpdb->prefix}glsl_background SET name = '{$array["nameFrag"]}',
            textFrag = '{$array["textFrag"]}', script = '{$array["scriptInput"]}',
            style = '{$array["styleInput"]}', copyrights = '{$array["copyInput"]}',
            uploadImg1 = '{$array["uploadImg1"]}', uploadImg2 = '{$array["uploadImg2"]}',
            uploadImg3 = '{$array["uploadImg3"]}', uploadImg4 = '{$array["uploadImg4"]}' WHERE id = '{$row->id}'";
            $wpdb->query($query);
    }


    }

    public static function create($array){
        global $wpdb;
        $showCol = "SHOW COLUMNS FROM ".$wpdb->prefix."glsl_background"."";
        $resultColName= $wpdb->get_results($showCol);
        $colName=[];
        foreach($resultColName as $col){
            array_push($colName,$col->Field );
        };
        array_pop($colName);
        array_shift($colName);
        array_shift($array);

        $arrayToinsert = array_combine($colName,$array);   
        $wpdb->insert("{$wpdb->prefix}glsl_background", $arrayToinsert);    
        $message = "Code uploaded successfully";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    // Function for select an active Bg or change 
    public static function updateSelectBg($idOptionselect){
        global $wpdb;
        // Update the row and set used True, It's use only for select the BG
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE used = 1");
        if($row){
        //    Change Boolean True to False for old Background 
            $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = 0 WHERE id = '{$row->id}'";
            $wpdb->query($query);
    
        // Set Boolean true for new background selected
        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE id = '{$idOptionselect}'");
        $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = '1' WHERE id = '{$row->id}'";
        $wpdb->query($query);
        }
        // If it's the first select set True the bool used
        else{    
            $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE id = '{$idOptionselect}'");
            $query =  "UPDATE {$wpdb->prefix}glsl_background SET used = '1' WHERE id = '{$row->id}'";
            $wpdb->query($query);

        }
    }

    // Function for delete the row with the ID( It's not use yet)
    public static function delete($idSelect){
        global $wpdb;
        $query = "DELETE  FROM {$wpdb->prefix}glsl_background WHERE id = '{$idSelect}'";
        $element = $wpdb->query($query);
        
    }

    // Function read for the name of the row, The output it's an array.
    public static function read($idSelect){
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}glsl_background WHERE id = '{$idSelect}'";
        $object =  $wpdb->get_results($query);
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