<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class analytics_log  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get_data($array){
        if($array != false){
            if(isset($array['app'])){
                $app_package = $array['app'];
                $answer = [];
                $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $app_id = $row['id'];

                        $dop_query_where = "AND `app_package` = '$app_package'";
                        if(isset($array['dt_start'])){
                            $dop_query_where = $dop_query_where." AND `time` >= ".$array['dt_start'];
                        }
                        if(isset($array['dt_finish'])){
                            $dop_query_where = $dop_query_where." AND `time` <= ".$array['dt_finish'];
                        }

                        $geo_code = null;
                        if(isset($array['geo'])){
                            $geo_code = $array['geo'];
                            $query_geo = "SELECT `id`, `name` FROM `list_geo` WHERE `c_code` = '$geo_code'";
                            $get_geo = mysqli_query($this->$connection_db,$query_geo); 
                            if(mysqli_num_rows($get_geo) != 0){
                                while($row_geo = mysqli_fetch_assoc($get_geo)){
                                    $geo_name = $row_geo['name'];
                                    $geo_id = $row_geo['id'];
                                    $dop_query_where = $dop_query_where." AND `geo` = ".$geo_id;
                                }
                            }
                        }

                        //for limit
                        if(isset($array['limit'])){
                            if(is_int($array['limit'])){
                                $dop_query_where2 = " LIMIT ".$array['limit'];
                            }
                            else{
                                $dop_query_where2 = " LIMIT 100";
                            }
                        }
                        else{
                            $dop_query_where2 = " LIMIT 100";
                        }


    
                        $query = "SELECT `device_id`, `ip`, `time`, `id` FROM app_events WHERE `name`='a_o' ".$dop_query_where." ORDER BY -id ".$dop_query_where2;
                        $get = mysqli_query($this->$connection_db,$query); 
                        while($row = mysqli_fetch_assoc($get)){
                            $answer_item = array();
                            $device_id = $row['device_id'];
                            $answer_item['a_o'] = true;
                            $answer_item['device_id'] = $row['device_id'];
                            $answer_item['ip'] = $row['ip'];
                            $answer_item['a_o_time'] = $row['time'];
                            
                            $answer_item['app'] = $app_package;

                            //get info about event game_open
                            $query_g = "SELECT `id`, `time` FROM app_events WHERE device_id = '$device_id' AND `name`='g_o' ORDER BY -id LIMIT 1";
                            $get_g = mysqli_query($this->$connection_db,$query_g); 
                            if(mysqli_num_rows($get_g) != 0){
                                while($row_g = mysqli_fetch_assoc($get_g)){
                                    $answer_item['g_o'] = true;
                                    $answer_item['g_o_time'] = $row_g['time'];
                                }
                            }
                            else{
                                $answer_item['g_o'] = false;
                            }

                            //get info about event offer_open
                            $query_g = "SELECT `id`, `time` FROM app_events WHERE device_id = '$device_id' AND `name`='o_o' ORDER BY -id LIMIT 1";
                            $get_g = mysqli_query($this->$connection_db,$query_g); 
                            if(mysqli_num_rows($get_g) != 0){
                                while($row_g = mysqli_fetch_assoc($get_g)){
                                    $answer_item['o_o'] = true;
                                    $answer_item['o_o_time'] = $row_g['time'];
                                }
                            }
                            else{
                                $answer_item['o_o'] = false;
                            }

                            

                            //get info about answer install
                            $query_g = "SELECT `type`, `install_data`, `ansver`, `dop_log`, `geo`, `ansver` FROM installs_log WHERE `app`='$app_id' AND device_id = '$device_id' ORDER BY -id LIMIT 1";
                            $get_g = mysqli_query($this->$connection_db,$query_g); 
                            if(mysqli_num_rows($get_g) != 0){
                                while($row_g = mysqli_fetch_assoc($get_g)){
                                    $answer_item['cloac_dop_log'] = $row_g['dop_log'];
                                    if($row_g['ansver'] == 'true'){
                                        $answer_item['answer'] = true;
                                    }
                                    else{
                                        $answer_item['answer'] = false;
                                    }
                                    
                                    if($row_g['dop_log'] == 'cloac block'){
                                        $answer_item['cloac'] = false;
                                    }
                                    else{
                                        $answer_item['cloac'] = true;
                                    }

                                    // if($row_g['ansver'] == 'false'){
                                    //     $grafic_arr_date[$pos_grafic_date][8]++;
                                    //     $num_ansver_false++;
                                    //     $log = $tpl->set('{status_ansver}', 'danger', $log);
                                    // }
                                    // else{
                                    //     $log = $tpl->set('{status_ansver}', 'success', $log);
                                    // }

                                    if($geo_code == null){
                                        $temp_geo_id = $row_g['geo'];
                                        $query_geo = "SELECT  `name`, `c_code` FROM `list_geo` WHERE `id` = '$temp_geo_id'";
                                        $get_geo = mysqli_query($this->$connection_db,$query_geo); 
                                        if(mysqli_num_rows($get_geo) != 0){
                                            while($row_geo = mysqli_fetch_assoc($get_geo)){
                                                $geo_name = $row_geo['name'];
                                                $geo_code = $row_geo['c_code'];

                                                $answer_item['geo_name'] = $geo_name;
                                                $answer_item['geo_code'] = $geo_code;
                                                $answer_item['geo_img'] = get_protocol().$_SERVER['SERVER_NAME']."/uploads/geo/".str_ireplace(' ', '-', $geo_name).".png";
                                            }
                                        }
                                    }
                                    else{
                                        $answer_item['geo_name'] = $geo_name;
                                        $answer_item['geo_code'] = $geo_code;
                                        $answer_item['geo_img'] = get_protocol().$_SERVER['SERVER_NAME']."/uploads/geo/".str_ireplace(' ', '-', $geo_name).".png";
                                    }


                                    //set campaign
                                    if($row_g['install_data'] != ''){
                                        $sel_data = json_decode($row_g['install_data'], true);
                                        if(isset($sel_data['data'])){
                                            if(isset($sel_data['data']['campaign'])){
                                                if($sel_data['data']['campaign'] != null && $sel_data['data']['campaign'] != null){
                                                    $answer_item['campaign'] = $sel_data['data']['campaign'];
                                                }
                                                else{
                                                    $answer_item['campaign'] = '(not set)';
                                                }
                                            }
                                            else{
                                                $answer_item['campaign'] = '(not set)';
                                            }
                                        }
                                        else{
                                            $answer_item['campaign'] = '(not set)';
                                        }
                                    }
                                    else{
                                        $answer_item['campaign'] = '(not set)';
                                    }
                                    
                                }
                            }
                            else{
                                // $answer_item['campaign'] = '(not set)';
                                // $answer_item['cloac_dop_log'] = '';
                                // $log = $tpl->set('{status_cloac}', 'warning', $log);
                            }
                        }

                        
                        if($answer_item != null){
                            $answer[] = $answer_item;
                        }
                        
                    }

                    return array('success'=>true, 'data'=> $answer);
                }
                else{
                    return array('success'=>false, 'error'=> 'app '.$package.' undefined');
                }
            }
            else{
                return array('success'=>false, 'error'=> 'params app undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
        


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

    
    function get_data_by_params($array){
        if($array != false){
            if(isset($array['device_id'])){
                $device_id = $array['device_id'];

                $answer = array(
                    'a_o'=>array('availability'=>false, 'time'=>null),
                    'o_o'=>array('availability'=>false, 'time'=>null),
                    'g_o'=>array('availability'=>false, 'time'=>null),
                    'e_l'=>array('availability'=>false, 'time'=>null),
                );

                $query = "SELECT `time` FROM app_events WHERE `name`='a_o' AND `device_id` = '$device_id' ORDER BY -id LIMIT 1";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $answer['a_o']['availability'] = true;
                        $answer['a_o']['time'] = $row['time'];
                    }
                }

                $query = "SELECT `time` FROM app_events WHERE `name`='o_o' AND `device_id` = '$device_id' ORDER BY -id LIMIT 1";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $answer['o_o']['availability'] = true;
                        $answer['o_o']['time'] = $row['time'];
                    }
                }

                $query = "SELECT `time` FROM app_events WHERE `name`='g_o' AND `device_id` = '$device_id' ORDER BY -id LIMIT 1";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $answer['g_o']['availability'] = true;
                        $answer['g_o']['time'] = $row['time'];
                    }
                }

                $query = "SELECT `time` FROM app_events WHERE `name`='e_l' AND `device_id` = '$device_id' ORDER BY -id LIMIT 1";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $answer['e_l']['availability'] = true;
                        $answer['e_l']['time'] = $row['time'];
                    }
                }

                return array('success'=>true, 'data'=> $answer);
                
            }
            else{
                return array('success'=>false, 'error'=> 'params device_id undefined or incorrect');
            }
        } 
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }
    
}


?>