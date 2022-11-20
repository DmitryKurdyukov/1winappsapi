<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class transfer_users  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    
    function appsflyer_transfer($array){
        if($array != false){
           if(isset($array['app']) && $array['app'] != ''){
               $app_package = $array['app'];
               $query = "SELECT `id`, `appsflyer_key` FROM `apps` WHERE `package` = '$app_package'";
               $get = mysqli_query($this->$connection_db,$query); 
               if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $app_id = $row['id'];
                        $appsflyer_key = $row['appsflyer_key'];
                    }
                    if($appsflyer_key != ''){
                        $geo_array = [1, 8, 20, 68, 86, 87];
                        $count_dep = 0;
                        
                        for($i=0; i<count($geo_array); $i++){
                            $query = "SELECT installs_log.gaid AS gaid, event_postback.hash AS device_id, installs_log.ip AS ip FROM event_postback INNER JOIN installs_log ON event_postback.geo = installs_log.geo AND event_postback.hash = installs_log.device_id AND installs_log.gaid != '' AND installs_log.gaid != 'null' WHERE event_postback.dep = 1 AND event_postback.geo = '$geo_array[$i]' LIMIT 20";
                            $get = mysqli_query($this->$connection_db,$query); 
                            while($row = mysqli_fetch_assoc($get)){
                                $hash = $row['device_id'];
                                $gaid = $row['gaid'];
                                $ip = $row['ip'];
                                
                                if($this->appsflyer_send_dep($app_package, $hash, $ip, $gaid, $appsflyer_key)){
                                    $count_dep++;
                                }
                            }
                        }
                        


                        return array('success'=>true, 'data'=> array('app'=>$app_package, 'count_reg'=>0, 'count_dep'=>$count_dep));
                    }
                    else{
                        return array('success'=>false, 'error'=> 'appsflyer_key undefined or empty');
                    }
               }
               else{
                    return array('success'=>false, 'error'=> $app_package.' app undefined');
               }
           }
           else{
              return array('success'=>false, 'error'=> 'param app incorrect or undefined');
           }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
        
    }

    function appsflyer_send_reg($app_package, $hash, $ip, $gaid, $appsflyer_key){
        $purchase_event = array(
            'appsflyer_id' => $hash,
            'ip' => $ip,
            'eventTime' => date("Y-m-d H:i:s.000", time()),
            'advertising_id' => $gaid
        );
        $purchase_event['eventName'] = 'RG';
        $purchase_event['eventValue'] = json_encode(
            array( 
                    'af_content_id' => 1,
                    'af_content_type' => 'rg'
                )
        );
        $data_string = json_encode($purchase_event);     
        $ch = curl_init('https://api2.appsflyer.com/inappevent/'.$app_package);                                   
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_HEADER, true);                                 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                   
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                     
        'Content-Type: application/json',
        'authentication: '.$appsflyer_key,                                    
        'Content-Length: ' . strlen($data_string))
        );                                   
        $result1 = curl_exec($ch);
        //echo $result1.'<br>';
        if(is_int(strpos($result1, '200 OK'))){
            return true;
        }
        else{
            return false;
        }
    }

    function appsflyer_send_dep($app_package, $hash, $ip, $gaid, $appsflyer_key){
        $purchase_event = array(
            'appsflyer_id' => $hash,
            'ip' => $ip,
            'eventTime' => date("Y-m-d H:i:s.000", time()),
            'advertising_id' => $gaid
        );
        $purchase_event['eventName'] = 'FD';
        $purchase_event['eventValue'] = json_encode(
            array( 
                    'af_content_id' => 1,
                    'af_content_type' => 'fd'
                )
        );
        $data_string = json_encode($purchase_event);     
        $ch = curl_init('https://api2.appsflyer.com/inappevent/'.$app_package);                                   
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_HEADER, true);                                 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                   
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                     
        'Content-Type: application/json',
        'authentication: '.$appsflyer_key,                                    
        'Content-Length: ' . strlen($data_string))
        );                                   
        $result1 = curl_exec($ch);
        //echo $result1.'<br>';
        if(is_int(strpos($result1, '200 OK'))){
            return true;
        }
        else{
            return false;
        }
    }

    
}


?>