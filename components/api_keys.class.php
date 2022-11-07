<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class api_keys  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get(){
        $rows = array();
        $query = "SELECT `name`, `key` FROM `api_keys` WHERE id = 1";
        $get = mysqli_query($this->$connection_db,$query); 
        while($row = mysqli_fetch_assoc($get)){
            $rows[] = $row;
        }
        return array('success'=>true, 'data'=> $rows);
    }

    function update($array){
        if($array != false){
            if(isset($array['key'])){
                $key = $array['key'];
                $query = "UPDATE `api_keys` SET `key`='$key' WHERE id = '1'";
                $post = mysqli_query($this->$connection_db,$query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param key in post json data undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function add($array){
        if($array != false){
            if(isset($array['ip'])){
                $ip = $array['ip'];
                $query = "INSERT INTO black_list_ip (`ip`) VALUES ('$ip')";
                $post = mysqli_query($this->$connection_db, $query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param ip in post json data undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function delete($array){
        if($array != false){
            if(isset($array['id'])){
                $id = $array['id'];
                $query = "DELETE FROM `black_list_ip` WHERE id = '$id'";
                $post = mysqli_query($this->$connection_db, $query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param id in post json data undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    
}


?>