<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class test_curl_app  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function curl($array){
        if($array != false){
            if(isset($array['data']) && isset($array['host'])){
                if(isset($array['ip']) && $array['ip'] != ''){
                    $ip = $array['ip'];
                }
                else{
                    $ip = get_ip();
                }

                $headers = [
                    'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
                    'REMOTE_ADDR: '.$ip,
                    'HTTP_X_FORWARDED_FOR: '.$ip,
                    'Content-Type: application/json'
                ];
                
                $cURLConnection = curl_init($array['host'].'?ip='.$ip);
                curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($array['data']));
                curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
                $curl_answer = curl_exec($cURLConnection);
                $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
                $curl_info = curl_getinfo($cURLConnection);
                curl_close($cURLConnection);

                return array('success'=>true, 'data'=> array('curl_status'=>$httpcode, 'curl_time'=>$curl_info['total_time'] * 1000 . ' ms', 'curl_answer'=>json_decode($curl_answer)));
            }
            else{
                return array('success'=>false, 'error'=> 'data or host params undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    

    
}


?>