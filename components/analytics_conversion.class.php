<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class analytics_conversion  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get($array){
        if(isset($array['app']) && $array['app'] != ''){
            $app_package = $array['app'];
            $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
            $get = mysqli_query($this->$connection_db,$query); 
            if(mysqli_num_rows($get) != 0){
                while($row = mysqli_fetch_assoc($get)){
                    $app_id = $row['id'];
                }

                $data = [];
                //поиск постбэков
                $query = "SELECT `hash`, `reg`, `dep`, `geo` FROM `event_postback` WHERE `app` = '$app_package' AND `geo` != 0";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $device_id = $row['hash'];
                        $geo_id = $row['geo'];
                                //поиск названи гео
                                $query3 = "SELECT `name` FROM `list_geo` WHERE `id` = '$geo_id'";
                                $get3 = mysqli_query($this->$connection_db,$query3); 
                                if(mysqli_num_rows($get3) != 0){
                                    while($row3 = mysqli_fetch_assoc($get3)){
                                        $geo_name = $row3['name'];
    
                                        //проверка и создание массива 
                                        if($data[$geo_name] == null){
                                            $data[$geo_name] = array("reg"=>1, "dep"=>0);
                                            if($row['dep'] == 1){
                                                $data[$geo_name]['dep'] = $data[$geo_name]['dep'] + 1; 
                                            }
                                        }
                                        else{
                                            $data[$geo_name]['reg'] = $data[$geo_name]['reg'] + 1; 
                                            if($row['dep'] == 1){
                                                $data[$geo_name]['dep'] = $data[$geo_name]['dep'] + 1; 
                                            }
                                        }
    
                                        //преобразование массива в надлежащий вид
                                        $answer = [];
                                        if(count($data) > 0){
                                            foreach($data as $key=>$value){
                                                $answer[] = array('geo'=>$key, 'reg'=>$data[$key]['reg'], 'dep'=>$data[$key]['dep']);
                                            }
                                        }
                                        
                                    }
                                }
                    }
                    return array('success'=>true, 'data'=> $answer);
                }
                else{
                    return array('success'=>true, 'data'=> []);
                }

                
            }
            else{
                return array('success'=>false, 'error'=> 'this app undefined');
            }
            
        }
        else{
            return array('success'=>false, 'error'=> 'param app in post json data undefined');
        }
        
    }

    
    
}


?>