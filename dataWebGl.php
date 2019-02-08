<?php
class DataWebGl{

    public $arrayCol= ["name VARCHAR(255)NOT NULL","textFrag TEXT","script TEXT","style TEXT","used BOOL NOT NULL DEFAULT false"];
   
    public function __construct()
    {
        global $wpdb;
       
        $column = "";
        foreach($this->arrayCol as $col=>$val){ 
                    $column = $column.",".$val;
                };

        $query = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."glsl_background (id INT AUTO_INCREMENT PRIMARY KEY$column)";
        
        $wpdb->query($query);


 }
 


    // public function save(){

    //     if (isset($_POST['textFrag']) && !empty($_POST['textFrag'])&& !empty($_POST['nameFrag'])) {
    //         global $wpdb;
    //         $name = $_POST['nameFrag'];
    //         $textFrag = $_POST['textFrag'];
    //         $script = $_POST['scriptInput'];
    //         $style = $_POST['styleInput'];

    //         $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '$name'");
    //         if (is_null($row)) {     
    //             $wpdb->insert("{$wpdb->prefix}glsl_background", array('name' => $name, 'textFrag'=>$textFrag,'script'=>$script,'style'=>$style));

    //             echo 'Code uploaded successfully';
    //         }
    //         else {
    //             echo 'This name is use. Choose another';
    //         } 
    //     }
    //     else{
    //         echo 'You should enter a  name !!';
    //     } 


    // }

    

    public function  saveDB($array){

        global $wpdb;
// =======Get the Name from DB table With out Id and last column=======
        $colNameDb = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '.$wpdb->prefix.glsl_background'";
        $showCol = "SHOW COLUMNS FROM ".$wpdb->prefix."glsl_background"."";

        $resultColName= $wpdb->get_results($showCol);
        $colName=[];
        foreach($resultColName as $col){
            array_push($colName,$col->Field );
        };
        array_pop($colName);
        array_shift($colName);
        


        $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$array["nameFrag"]}'");

        if (is_null($row)) {     

            
            // array(4) { ["nameFrag"]=> string(6) "Hello " ["textFrag"]=> string(15) "0inuipr bnpetr " ["scriptInput"]=> string(20) "g grtgtr g rt tr rt " ["styleInput"]=> string(0) "" } 

            $arrayToinsert = [];
            foreach($colName as $newkey){

                $arrayToinsert[$newkey]=0;

            }
            // var_dump($arrayToinsert);
            // die;
            foreach( $arrayToinsert as  $value){
                // var_dump($key);
                // var_dump($value);
                foreach($colName as $newkey){

                $arrayToinsert[$newkey]= $value;

                }
            }

            var_dump($arrayToinsert);
            die;

            $wpdb->insert("{$wpdb->prefix}glsl_background", array('name' => $array["name"],));


            echo 'Code uploaded successfully';
        }
        
        // else {
        //     $query =  "UPDATE {$wpdb->prefix}glsl_background SET name = '{$array["name"]}'WHERE id = '{$array["id"]}'";
        //     echo 'Update Name ';

        //     // $query =" UPDATE {$wpdb->prefix}glsl_background SET $arra"
        // } 

        }

    public function delete($idSelect){
        global $wpdb;
        $query = "DELETE * FROM {$wpdb->prefix}glsl_background WHERE id = '{$idSelect}'";
        $element = $wpdb->query($query);
        return "Element deleted";
    }

    public function read($name){
        global $wpdb;
        $name;
        $queryname = "SELECT * FROM {$wpdb->prefix}glsl_background WHERE name = '{$name}'";
        $result =  $wpdb->get_results($queryname);
        if(count($result)>1){
            // echo"Toomuch ";
            return $result;
        }
        else{
            return $result[0];
        }
    }

    public function listAll(){
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