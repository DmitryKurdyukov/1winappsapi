<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

if($_GET['hash'] != '' && $_GET['app'] != ''){
    $hash = $_GET['hash'];
    $app = $_GET['app'];
    $app_package = $_GET['app'];

    $query = "SELECT id, platform, onesignal_app_id FROM apps WHERE `package` = '$app' LIMIT 1";
        $get = mysqli_query($connection_db,$query); 
        if(mysqli_num_rows($get) != 0){
            while($row = mysqli_fetch_assoc($get)){
                $id = $row['id'];
                $onesignal_app_id = $row['onesignal_app_id'];
                if($row['platform'] == 'ios'){
                    $app = 'id'.$app;
                }
            }

            $query = "SELECT appsflyer_key FROM apps WHERE `id` = '$id' LIMIT 1";
            $get = mysqli_query($connection_db,$query); 
            while($row = mysqli_fetch_assoc($get)){
                $appsflyer_key = $row['appsflyer_key'];
            }
            if($appsflyer_key != ''){
                if($_GET['ip'] != ''){
                    $ip = $_GET['ip'];
                }
                else{
                   $query = "SELECT ip FROM installs_log WHERE `device_id` = '$hash' LIMIT 1";
                   $get = mysqli_query($connection_db,$query); 
                   while($row = mysqli_fetch_assoc($get)){
                       $ip = $row['ip'];
                   }
                }
                

                 if($_GET['reg'] != '{reg}' && $_GET['reg'] != '' && $_GET['reg'] != '0'){
                    $query_reg = "SELECT id FROM event_postback WHERE `hash` = '$hash' AND `app` = '$app_package'";
                    $get_reg = mysqli_query($connection_db,$query_reg); 
                    if(mysqli_num_rows($get_reg) == 0){
                        $query_reg = "INSERT INTO event_postback (`reg`, `dep`, `app`, `hash`) VALUES('1', '0', '$app_package', '$hash')";
                        $post_reg = mysqli_query($connection_db,$query_reg);

                        $purchase_event = array(
                            'appsflyer_id' => $hash,
                            'ip' => $ip,
                            'eventTime' => date("Y-m-d H:i:s.000", time())
                            );
                        $purchase_event['eventName'] = 'RG';
                        $purchase_event['eventValue'] = json_encode(
                            array( 
                                    'af_content_id' => 1,
                                    'af_content_type' => 'rg'
                                )
                            );
                        $data_string = json_encode($purchase_event);     
                        $ch = curl_init('https://api2.appsflyer.com/inappevent/'.$app);                                   
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
                        $curl = curl_init();
                        echo ' reg success <br><br>'.$result1;
                    }
                    else{
                        echo ' reg has already been sent ';
                    }
                }

                if($_GET['dep'] != '{dep}' && $_GET['dep'] != '' && $_GET['dep'] != '0'){
                    $query_dep = "SELECT * FROM event_postback WHERE `hash` = '$hash' AND `app` = '$app_package'";
                    $get_dep = mysqli_query($connection_db,$query_dep); 
                    if(mysqli_num_rows($get_dep) != 0){
                        while($row_dep = mysqli_fetch_assoc($get_dep)){
                            if($row_dep['reg'] == '1' && $row_dep['dep'] == '0'){
                                $purchase_event = array(
                                    'appsflyer_id' => $hash,
                                    'ip' => $ip,
                                    'eventTime' => date("Y-m-d H:i:s.000", time())
                                    );
                                $purchase_event['eventName'] = 'FD';
                                $purchase_event['eventValue'] = json_encode(
                                    array( 
                                            'af_content_id' => 2,
                                            'af_content_type' => 'fd'
                                        )
                                    );
                                $data_string = json_encode($purchase_event);     
                                $ch = curl_init('https://api2.appsflyer.com/inappevent/'.$app);                                   
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
                                curl_setopt($ch, CURLOPT_HEADER, true);                                 
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                 
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                   
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                     
                                'Content-Type: application/json',
                                'authentication: '.$appsflyer_key,                                    
                                'Content-Length: ' . strlen($data_string))
                                );                                   
                                $result2 = curl_exec($ch);
                                $curl = curl_init();
                                echo '<br><br> dep success <br><br>'.$result2;

                                $query = "UPDATE event_postback SET `dep` = '1' WHERE `hash` = '$hash' AND `app` = '$app_package'";
                                $post = mysqli_query($connection_db,$query);
                            }
                            else{
                                echo 'dep has already been sent';
                            }
                        }
                    }
                }

                //for onesignal tags
                if($onesignal_app_id != ''){
                    if($_GET['reg'] != '{reg}' && $_GET['reg'] != '' && $_GET['reg'] != '0'){
                        $data = json_encode(
                            array( 
                                    'reg' => 1,
                                )
                            );
                        $ch = curl_init('https://onesignal.com/api/v1/apps/'.$onesignal_app_id.'/users/'.$hash);                                   
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");  
                        curl_setopt($ch, CURLOPT_HEADER, true);                                 
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                 
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                   
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                     
                            'Content-Type: application/json',
                        )
                        );                                   
                        $signal_res = curl_exec($ch);
                    }

                    if($_GET['dep'] != '{dep}' && $_GET['dep'] != '' && $_GET['dep'] != '0'){
                        $data = json_encode(
                            array( 
                                    'dep' => 1,
                                )
                            );
                        $ch = curl_init('https://onesignal.com/api/v1/apps/'.$onesignal_app_id.'/users/'.$hash);                                   
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");  
                        curl_setopt($ch, CURLOPT_HEADER, true);                                 
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                 
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                   
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                     
                            'Content-Type: application/json',
                        )
                        );                                   
                        $signal_res = curl_exec($ch);
                    }
                    
                }
                //end onesignal tags

            }
            else{
                echo 'error: dev key undefined';
            }
        }
        else{
            echo 'error: this app undefined in database';
        }
}
else{
    echo 'error: undefined hash or app in variables';
}