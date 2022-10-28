<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class Geo  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get_list_geo(){
        $rows = [];
        $query = "SELECT `name`, `c_code` FROM `list_geo`";
        $get = mysqli_query($this->$connection_db,$query); 
        while($row = mysqli_fetch_assoc($get)){
            $row['img'] = get_protocol().$_SERVER['SERVER_NAME']."/uploads/geo/".str_ireplace(' ', '-', $row['name']).".png";
            $row['code'] = $row['c_code'];
            unset($row['c_code']);
            $rows[] = $row;
        }
        return array('success'=>true, 'data'=> $rows);
    }

    function update_geo($array){
        if($array != false){
            if(isset($array['name']) && isset($array['code']) && $array['name'] != '' && $array['code'] != ''){
                $name = $array['name'];
                $c_code = $array['code'];
                $query = "UPDATE `list_geo` SET `name`='$name' WHERE `c_code` = '$c_code'";
                $post = mysqli_query($this->$connection_db,$query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param name or code in post json data undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function add_geo($array){
        if($array != false){
            if(isset($array['name']) && isset($array['code']) && $array['name'] != '' && $array['code'] != ''){
                $name = $array['name'];
                $c_code = $array['code'];
                $query = "INSERT INTO list_geo (`name`, `c_code`) VALUES ('$name', '$c_code')";
                $post = mysqli_query($this->$connection_db, $query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param name or code in post json data undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function delete_geo($array){
        if($array != false){
            if(isset($array['code'])){
                $c_code = $array['code'];
                $query = "DELETE FROM `list_geo` WHERE `c_code` = '$c_code'";
                $post = mysqli_query($this->$connection_db, $query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param code in post json data undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    
}


?>