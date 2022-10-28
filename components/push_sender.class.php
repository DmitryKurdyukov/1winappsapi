<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class push_sender  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function send_naming_push($array){
        if($array != false){
            if(isset($array['app']) && isset($array['naming']) && isset($array['event']) && isset($array['push_title']) && isset($array['push_body'])){
                if($array['app'] != '' && $array['naming'] != '' && $array['event'] != '' && $array['push_title'] != '' && $array['push_body'] != ''){
                   $app_package = $array['app'];
                   $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                   $get = mysqli_query($this->$connection_db,$query); 
                   if(mysqli_num_rows($get) != 0){
                       while($row = mysqli_fetch_assoc($get)){
                           $app_id = $row['id'];
                       }
                       $naming = $array['naming'];
                       $push_title = $array['push_title'];
                       $push_body = $array['push_body'];
                       $event = $array['event'];
                       $push_big_icon = $array['push_big_icon'];
                       $arr_external_user_id = [];

                       switch ($event) {
                        case 'installation':
                            $event = $array['event'];
                            break;
                        case 'registration':
                            $event = $array['event'];
                            break;
                        case 'deposit':
                            $event = $array['event'];
                            break;
                        default:
                            $event = 'installation';
                            break;
                        }

                        //получение ключей для onesignal и проверка на пустоту
                        $query = "SELECT `onesignal_app_id`, `onesignal_api_key` FROM apps WHERE id = '$app_id'";
                        $get = mysqli_query($this->$connection_db,$query); 
                        while($row = mysqli_fetch_assoc($get)){
                            $onesignal_app_id = $row['onesignal_app_id'];
                            $onesignal_api_key = $row['onesignal_api_key'];
                        }

                        if($onesignal_app_id != '' && $onesignal_api_key != ''){
                            //выборка id юзеров по заданным параметрам
                            $query = "SELECT `install_data`, `device_id` FROM installs_log WHERE app = '$app_id'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            while($row = mysqli_fetch_assoc($get)){
                                $sel_data = json_decode($row['install_data'], true);
                                if(isset($sel_data['data'])){
                                    if(isset($sel_data['data']['campaign'])){
                                        if($sel_data['data']['campaign'] == $naming){
                                            $device_id = $row['device_id'];
                                            if($event == 'installation'){
                                                $arr_external_user_id[] = $device_id;
                                            }
                                            if($event == 'registration'){
                                                $query_e = "SELECT `id` FROM event_postback WHERE `hash` = '$device_id' AND `reg` = '1'";
                                                $get_e = mysqli_query($this->$connection_db,$query_e); 
                                                if(mysqli_num_rows($get_e) != 0){
                                                    $arr_external_user_id[] = $row['device_id'];
                                                }
                                            }
                                            if($event == 'deposit'){
                                                $query_e = "SELECT `id` FROM event_postback WHERE `hash` = '$device_id' AND `dep` = '1'";
                                                $get_e = mysqli_query($this->$connection_db,$query_e); 
                                                if(mysqli_num_rows($get_e) != 0){
                                                    $arr_external_user_id[] = $row['device_id'];
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if(count($arr_external_user_id) > 0){
                                $data = $this->send_push_to_onesignal($push_title, $push_body, $onesignal_app_id, $arr_external_user_id, $push_big_icon, $onesignal_api_key);
                                if($data['success'] == true){
                                    return array('success'=>true, 'data'=> array('app'=>$app_package, 'naming'=>$naming, 'count_devices'=>count($arr_external_user_id)));
                                }
                                else{
                                    return array('success'=>false, 'error'=> $data['error']);
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'No devices were found for the given parameters');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'params onesignal_app_id or onesignal_api_key not configured in the app');
                        }
                    }
                    else{
                        return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'params app, naming, event, push_title, push_body must not be empty');
                }
            }
            else{
                return array('success'=>false, 'error'=> 'params app, naming, event, push_title, push_body must not be empty');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
        
    }

    function send_push_to_onesignal($push_title, $push_body, $onesignal_app_id, $arr_external_user_id, $push_big_icon, $onesignal_api_key){
        $heading = array(
            "en" => $push_title,
        );
        $contents = array(
            "en" => $push_body,
        );
        
        $json = array(
            "app_id" => $onesignal_app_id,
            "channel_for_external_user_ids" => 'push',
            "include_external_user_ids" => $arr_external_user_id,
            "headings" => $heading,
            "contents" => $contents,
            "big_picture" => $push_big_icon
        );
        
        
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '.$onesignal_api_key
        ];
        $json = json_encode($json);
        //echo $json.'<br>';
        
        $cURLConnection = curl_init('https://onesignal.com/api/v1/notifications');
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $json);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        if(is_int(strpos($apiResponse, 'All included players are not subscribed'))){
            return array('success'=>false, 'error'=>$apiResponse);
        }
        else{
            return array('success'=>true);
        }
    }

    
}


?>