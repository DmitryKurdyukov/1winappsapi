<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class cloac_script  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get(){
        $query = "SELECT * FROM cloac WHERE `id` = '1'";
        $get = mysqli_query($this->$connection_db,$query); 
        while($row = mysqli_fetch_assoc($get)){
            $script = str_ireplace("||", "'", $row['script']);
        }

        return array('success'=>true, 'data'=>array('script'=>$script));
    }

    function update($array){
        if($array != false){
            if(isset($array['script'])){
                $script = str_ireplace("'", "||", $array['script']);
                $query = "UPDATE cloac SET `script`= '$script' WHERE `id` = '1'";
                $post = mysqli_query($this->$connection_db,$query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'sql query error');
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param script undefined post json data');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
        
    }

    
}


?>