<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class blackListAsn  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get_list(){
        $rows = array();
        $query = "SELECT `id`, `asn` FROM `black_list_asn`";
        $get = mysqli_query($this->$connection_db,$query); 
        while($row = mysqli_fetch_assoc($get)){
            $rows[] = $row;
        }
        return array('success'=>true, 'data'=> $rows);
    }

    function update($array){
        if($array != false){
            if(isset($array['id'])){
                if(isset($array['asn'])){
                    $id = $array['id'];
                    $asn = $array['asn'];
                    $query = "UPDATE `black_list_asn` SET `asn`='$asn' WHERE id = '$id'";
                    $post = mysqli_query($this->$connection_db,$query); 
                    if($post){
                        return array('success'=>true);
                    }
                    else{
                        return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                    } 
                }
                else{
                    return array('success'=>false, 'error'=> 'param asn in post json data undefined');
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

    function add($array){
        if($array != false){
            if(isset($array['asn'])){
                $asn = $array['asn'];
                $query = "INSERT INTO black_list_asn (`asn`) VALUES ('$asn')";
                $post = mysqli_query($this->$connection_db, $query); 
                if($post){
                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param asn in post json data undefined');
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
                $query = "DELETE FROM `black_list_asn` WHERE id = '$id'";
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