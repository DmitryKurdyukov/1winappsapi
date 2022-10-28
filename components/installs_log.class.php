<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class installs_log  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get_data($array){
        if($array != false){
            $request_params = array();
            //for select
            if(isset($array['select']) && is_array($array['select']) && isset($array['where']) && is_array($array['where'])){
                $sql_select = "";
                if(is_int(array_search('campaign', $array['select']))){
                    $request_params['campaign'] = true;
                    $array['select'][array_search('campaign', $array['select'])] = null;
                    if(!is_int(array_search('install_data', $array['select']))){
                        $array['select'][] = 'install_data';
                        $request_params['not_install_data'] = true;
                    }
                }
                
                for($i=0; $i<count($array['select']);$i++){
                    if($array['select'][$i] != '' && $array['select'][$i] != null){
                        if($i<count($array['select'])-1){
                            $sql_select = $sql_select." `".$array['select'][$i]."`, ";
                        }
                        else{
                            $sql_select = $sql_select." `".$array['select'][$i]."` ";
                        }
                    }
                }

                //for where
                if(count($array['where']) != 0){
                    $sql_where = " WHERE ";
                    foreach ($array['where'] as $key => $val) {
                        if($key == 'app'){
                            $app_package = $val;
                            $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    $app_id = $row['id'];
                                }
                            }
                            else{
                                $app_id = 'undefined';
                            }
                            $array['where'][$key] = $app_id;
                        }
                        if($key == 'geo'){
                            $request_params['one_geo'] = true;
                            $geo_c_code = $val;
                            $query = "SELECT `id`, `name` FROM `list_geo` WHERE `c_code` = '$geo_c_code'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    $geo_id = $row['id'];
                                    $geo_name = $row['name'];
                                    $geo_img = get_protocol().$_SERVER['SERVER_NAME']."/uploads/geo/".str_ireplace(' ', '-', $row['name']).".png";
                                }
                            }
                            else{
                                $geo_id = 'undefined';
                            }
                            $array['where'][$key] = $geo_id;
                        }
                    }
                }
                else{
                    $sql_where = "";
                }

                $i = 0;
                foreach ($array['where'] as $key => $val) {
                    if($i<count($array['where'])-1){
                        if($key == 'dt_start' OR $key == 'dt_finish'){
                            if($key == 'dt_start'){
                                $sql_where = $sql_where." `dt`>='$val' AND ";
                            }
                            if($key == 'dt_finish'){
                                $sql_where = $sql_where." `dt`<='$val' AND ";
                            }
                        }
                        else{
                            $sql_where = $sql_where." `$key`='$val' AND ";
                        }
                    }
                    else{
                        if($key == 'dt_start' OR $key == 'dt_finish'){
                            if($key == 'dt_start'){
                                $sql_where = $sql_where." `dt`<='$val' ";
                            }
                            if($key == 'dt_finish'){
                                $sql_where = $sql_where." `dt`>='$val' ";
                            }
                        }
                        else{
                            $sql_where = $sql_where." `$key`='$val' ";
                        }
                    }
                    $i++;
                }

                //for limit
                $sql_limit = '';
                if(isset($array['limit'])){
                    if(is_int($array['limit'])){
                        $sql_limit = " LIMIT ".$array['limit'];
                    }
                    if(is_array($array['limit'])){
                        $sql_limit = " LIMIT ".$array['limit'][0].", ".$array['limit'][0];
                    }
                    if(!is_int($array['limit']) && !is_array($array['limit'])){
                        $sql_limit = " LIMIT 100";
                    }
                }
                else{
                    $sql_limit = " LIMIT 100";
                }

                $answer = [];
                $query = "SELECT ".$sql_select." FROM installs_log ".$sql_where." ORDER BY -id ".$sql_limit;
                $get = mysqli_query($this->$connection_db,$query); 
                while($row = mysqli_fetch_assoc($get)){
                    if($request_params['campaign'] == true){
                        $sel_data = json_decode($row['install_data'], true);
                        if(isset($sel_data['data'])){
                            if(isset($sel_data['data']['campaign'])){
                                if($sel_data['data']['campaign'] != null && $sel_data['data']['campaign'] != null){
                                    $row['campaign'] = $sel_data['data']['campaign'];
                                }
                                else{
                                    $row['campaign'] = '(not set)';
                                }
                            }
                            else{
                                $row['campaign'] = '(not set)';
                            }
                        }
                    }
                    if($request_params['not_install_data'] == true){
                        unset($row['install_data']);
                    }
                    
                    if(isset($row['geo'])){
                        if($request_params['one_geo'] == true){
                            $row['geo_name'] = $geo_name;
                            $row['geo_img'] = $geo_img;
                        }
                        else{
                            $sel_geo_id = $row['geo'];
                            $query_2 = "SELECT `name` FROM `list_geo` WHERE `id` = '$sel_geo_id'";
                            $get_2 = mysqli_query($this->$connection_db,$query_2); 
                            if(mysqli_num_rows($get_2) != 0){
                                while($row_2 = mysqli_fetch_assoc($get_2)){
                                    $row['geo_name'] = $row_2['name'];
                                    $row['geo_img'] = get_protocol().$_SERVER['SERVER_NAME']."/uploads/geo/".str_ireplace(' ', '-', $row_2['name']).".png";
                                }
                            }
                            else{
                                $row['geo_name'] = 'undefined';
                                $row['geo_img'] = 'undefined';
                            }
                        }
                    }
                    
                    $answer[] = $row;
                }

                return array('success'=>true, 'data'=> $answer);

            }
            else{
                return array('success'=>false, 'error'=> 'param select or where undefined and they must be an array');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    

    
}


?>