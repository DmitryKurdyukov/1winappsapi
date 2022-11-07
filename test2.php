<?php

//auth in service
// $headers = [
//     'Content-Type: application/json',
// ];

// $j_data = array (
//     'login_name' => 'encript AES login_name',
//     'password' => 'encript AES password',
//     'client' => 'bot'
// );

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_URL, 'https://api.1-w.app/api/geo/get_list_geo/');
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($j_data));
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// $result = curl_exec($ch);
// echo $result;

// //пример функции с AES шифрованием (у меня она есть ток на клиенте на react js поэтому пример оттуда)
// export function encryptAES(message:any){
//     //первый key, второй iv парамтеры (ключи) для шифрования
//     let key =  CryptoJS.enc.Hex.parse("384e3572577172476973796d65666768");
//     let iv =   CryptoJS.enc.Hex.parse("abcdef9876543210abcdef9876543210");
//     let encrypted = CryptoJS.AES.encrypt(message, key, {iv:iv, padding:CryptoJS.pad.ZeroPadding}).toString();
//     return encrypted;
// }


// //get apps list
// $headers = [
//     'Content-Type: application/json',
//     'Auth-Token: '$token
// ];

// $j_data = array (
//     'select' => ['name', 'package', 'production_type', 'icon'],
//     'where' => [array('platform'=>['android']), array('activ'=>true), array('status'=>['public']), array('production'=>true)],
// );

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_URL, 'https://api.1-w.app/api/apps/get_apps_data/');
// curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($j_data));
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// $result = curl_exec($ch);
// echo $result;

/////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////

// function getPost()
// {
//     if(!empty($_POST))
//     {
//        return $_POST;
//     }
//     $post = json_decode(file_get_contents('php://input'), true);
//     if(json_last_error() == JSON_ERROR_NONE)
//     {
//         return $post;
//     }
//     return false;
// }

// function replace_sub($campaign_params, $link){
//     if(is_array($campaign_params)){
//        for($i=0;$i<count($campaign_params); $i++){
//             $j = $i+1;
//             $link = str_ireplace('{sub_'.$j.'}', $campaign_params[$i], $link);
//         }
//     }
    
//     return $link;
// }

// function replace_macros($url, $app, $hash, $data){
//     $new_url = $url;
//     $new_url = str_ireplace('{hash}', $hash, $new_url);
//     $new_url = str_ireplace('{app}', $app, $new_url);
//     if(is_array($data)){
//        foreach($data as $key => $val) {
//            if(strripos($url, '{'.$key.'}')){
//                $new_url = str_ireplace('{'.$key.'}', $val, $new_url);
//            }
//        }
//     }
    
//     return $new_url;
// }

// function isBot($user_agent){
//     $bots = array(
//       'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
//       'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
//       'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
//       'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
//       'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
//       'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
//       'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
//       'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
//       'Nigma.ru','bing.com','dotnetdotcom','bot', 'bots', 'facebook', 'iPad'
//     );
//     foreach($bots as $bot)
//       if(stripos($user_agent, $bot) !== false){
//         $botname = $bot;
//         return true;
//       }
//     return false;
// }



// function get_hashing_url($app_id, $customer_nick, $connection){
//    $query = "SELECT `id` FROM `customers` WHERE `name` = '$customer_nick'";
//    $get = mysqli_query($connection,$query); 
//    while($row = mysqli_fetch_assoc($get)){
//        $customer_id = $row['id'];
//    }

//    $query = "SELECT `url` FROM `app_hashing` WHERE `type` = 1 AND app = '$app_id' AND customer = '$customer_id'";
//    $get = mysqli_query($connection,$query); 
//    if(mysqli_num_rows($get) != 0){
//       while($row = mysqli_fetch_assoc($get)){
//           $hashing_url = $row['url'];
//       }
//       return array("success"=>true, "url"=>$hashing_url); 
//    }
//    else{
//       return array("success"=>false); 
//    }
// }

// function get_info_about_ip($ip, $connection){
//     //select geo key
//     $query = "SELECT `key` FROM `api_keys` WHERE id = 1";
//     $get = mysqli_query($connection,$query); 
//     while($row = mysqli_fetch_assoc($get)){
//         $geo_key = $row['key'];
//     }

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_URL, 'http://ipwhois.pro/json/'.$ip.'?key='.$geo_key);
//     $result = curl_exec($ch);
//     curl_close($ch);
//     $obj = json_decode($result);
//     $this_asn = $obj->asn;
//     $this_country = $obj->country;
//     //получение id geo
//     $country_id = 0;
//     $query = "SELECT id FROM `list_geo` WHERE `name` = '$this_country'";
//     $get = mysqli_query($connection,$query); 
//     while($row = mysqli_fetch_assoc($get)){
//         $country_id = $row['id'];
//     }

//     return array("country"=>$this_country, "country_id"=>$country_id, "asn"=>$this_asn); 
// }

// function check_app($app_package, $connection){
//     $success = false;
//     $app_id = null;
//     $active = false;
//     $query = "SELECT id, activ FROM `apps` WHERE package = '$app_package'";
//     $get = mysqli_query($connection,$query); 
//     while($row = mysqli_fetch_assoc($get)){
//         $app_id = $row['id'];
//         if($row['activ'] == 1){
//             $active = true;
//             $success = true;
//         }
//     }
//     return array("success"=>$success, "app_id"=>$app_id, "active"=>$active);
// }

// function check_cloac($ip, $asn, $user_agent, $connection){
//     $success = false;
//     if(isBot($user_agent) == false){
//         $query = "SELECT * FROM `black_list_asn` WHERE asn = '$asn'";
//         $get = mysqli_query($connection,$query); 
//         if(mysqli_num_rows($get) == 0){
//             $query = "SELECT ip FROM `black_list_ip` WHERE ip = '$ip'";
//             $get = mysqli_query($connection,$query); 
//             if(mysqli_num_rows($get) == 0){
//                 $success = true;
//             }
//         }
//     }
//     return array("success"=>$success);

//     // if(isBot($user_agent) == false){
//     //    $headers = [
//     //        'User-Agent: '.$user_agent,
//     //        'REMOTE_ADDR: '.$ip,
//     //        'HTTP_X_FORWARDED_FOR: '.$ip,
//     //        'Content-Type: application/json'
//     //    ];
//     //    $cURLConnection = curl_init('https://apkconversion.ru/cloac_api.php?ip='.$ip.'&asn='.$asn);
//     //    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $json);
//     //    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//     //    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
//     //    $apiResponse = json_decode(curl_exec($cURLConnection), true);
//     //    curl_close($cURLConnection);
//     // }
   
//     // return array("success"=>$apiResponse['status']);
// }


// function get_organic($app_id, $geo, $connection){
//    $success = false;
//    $url = false; 
//    $log = 'one link and organic undefined';
//    $query = "SELECT link FROM `one_link_organic` WHERE app = '$app_id' AND activ = '1'";
//    $get = mysqli_query($connection,$query); 
//    if(mysqli_num_rows($get) != 0){
//        while($row = mysqli_fetch_assoc($get)){
//            $success = true;
//            $url = $row['link'];
//            $log = 'one link organic';
//        }
//    }
//    else{
//        $query = "SELECT link FROM `app_organic` WHERE app = '$app_id' AND geo = '$geo'";
//        $get = mysqli_query($connection,$query); 
//        while($row = mysqli_fetch_assoc($get)){
//            $success = true;
//            $url = $row['link'];
//            $log = 'organic link';
//        }
//    }
   
//    return array("success"=>$success, "url"=>$url, "log"=>$log);
// }


// function get_naming($app_id, $campaign, $connection){
//    $success = false;
//    $url = false; 
//    $log = 'one link and naming undefined';
//    $query = "SELECT link FROM `one_link_naming` WHERE app = '$app_id' AND activ = '1'";
//    $get = mysqli_query($connection,$query); 
//    if(mysqli_num_rows($get) != 0){
//        while($row = mysqli_fetch_assoc($get)){
//            $success = true;
//            $url = $row['link'];
//            $log = 'one link naming';
//        }
//    }
//    else{
//        $query = "SELECT link FROM `app_naming` WHERE app = '$app_id' AND `name` = '$campaign[0]'";
//        $get = mysqli_query($connection,$query); 
//        if(mysqli_num_rows($get) != 0){
//            while($row = mysqli_fetch_assoc($get)){
//                $success = true;
//                $url = $row['link'];
//                $log = 'naming link';
//            }
//        }
//        else{
//            $query = "SELECT link FROM `reserv_onelink_naming` WHERE app = '$app_id'";
//            $get = mysqli_query($connection,$query); 
//            if(mysqli_num_rows($get) != 0){
//                while($row = mysqli_fetch_assoc($get)){
//                    $success = true;
//                    $url = $row['link'];
//                    $log = 'reserv one link naming link';
//                }
//           }
//       }
//    }
   
//    return array("success"=>$success, "url"=>$url, "log"=>$log);
// }

// function add_install(){

// }

// function edit_html_chars($string){
//     $string = str_ireplace('%7B', '{', $string);
//     $string = str_ireplace('%7D', '}', $string);

//     return $string;
// }



// $ansver = new stdClass();
// $json = getPost();

// $app = $json['app'];
// $hash = $json['hash'];
// //for adjust
// if($json['dataAdjust'] != null){
//     $json['data'] = $json['dataAdjust'];
//     $json['dataAdjust'] = null;
// }
// //for deeplink
// if($json['deeplink'] != null && $json['deeplink'] != 'null'){
//     $deeplink = $json['deeplink'];
//     $deeplink = str_ireplace('app://', '', $deeplink);
//     if(!isset($json['data']['campaign'])){
//        $json['data']['campaign'] = $deeplink;
//     }
// }

// if(isset($json['data']['campaign'])){
//     //echo $json['data']['campaign'];
//     $campaign = $json['data']['campaign'];
//     $campaign = str_ireplace(" ", "_", $campaign);
//     $campaign = explode('_', $campaign);
// }

// $user_agent = $_SERVER['HTTP_USER_AGENT'];



// if($json != false && $app != '' && $hash != ''){
    
//     if($_GET['ip'] != ''){
//         $ip = $_GET['ip'];
//     }
//     else{
//         $client  = @$_SERVER['HTTP_CLIENT_IP'];
//         $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
//         $remote  = @$_SERVER['REMOTE_ADDR'];
//         if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
//         elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
//         else $ip = $remote;
//     }

//     //for testing app send data
//     //$dt_t = date("y-m-d H:i:s");
//    //  if($app == '1564556081' OR $app == '1564556081' OR $app == 'com.photoeditorandre.oksma'){
//    //     //$send_info = implode(",", $data);
//    //     $send_info = json_encode($data);
//    //  	$send_dop_info = 'hash : '.$hash.', ip : '.$ip.', user_agent : '.$user_agent;
//    //  	$query = "INSERT INTO `app_send_data` (`app`, `info`, `dop_info`, `dt_t`) VALUES('$app', '$send_info', '$send_dop_info', '$dt_t')";
//    //  	$post = mysqli_query($connection,$query); 
//    //  }
    

//     $check_app = check_app($app, $connection);
//     if($check_app['success'] == true){
//         $info_about_ip = get_info_about_ip($ip, $connection);
//         $check_cloac = check_cloac($ip, $info_about_ip['asn'], $user_agent, $connection);
//         if($check_cloac['success'] == true){
//            if(!isset($json['data']['campaign'])){
//                $redirect_data = get_organic($check_app['app_id'], $info_about_ip['country_id'], $connection);
//            }
//            else{
//                $redirect_data = get_naming($check_app['app_id'], $campaign, $connection);
//            }

//            if($redirect_data['success'] == true){
//                $ansver->success = true;
//                $ansver->url = replace_sub($campaign, replace_macros(edit_html_chars($redirect_data['url']), $app, $hash, $json['data']));
//            }
//            else{
//                $ansver->success = false;
//                $ansver->error = $redirect_data['log'];
//            }
//         }
//         else{
//            $ansver->success = false;
//            $ansver->error = "cloac block";
//         }
        
//     }
//     else{
//         $ansver->success = false;
//         $ansver->error = "app not activ or undefined";
//     }

//     //insert instal info
//     $app_id = $check_app['app_id'];
//     $dt = date('Y-m-d H:i:s');
//     if(isset($json['data']['campaign'])){
//         $type = 'Non-organic';
//     }
//     else{
//         $type = 'Organic';
//     }
    
//     $link = $ansver->url;
//     $geo = $info_about_ip['country_id'];
//     $device_id = $hash;
//     $gaid = $json['gaid'];
//     if(isset($redirect_data['log'])){
//        $dop_log = $redirect_data['log'];
//     }
//     else{
//        $dop_log = $ansver->error;
//     }
//     if($ansver->success == false){
//         $admin_ansver = 'false';
//     }
//     else{
//         $admin_ansver = 'true';
//     }
    
    
//     $install_data = json_encode($json);
//     $query = "INSERT INTO installs_log (`app`, `type`, `ip`, `link`, `geo`, `dt`, `device_id`, `install_data`, `user_agent`, `dop_log`, `gaid`, `ansver`) VALUES ('$app_id', '$type', '$ip', '$link', '$geo', '$dt', '$device_id', '$install_data', '$user_agent', '$dop_log', '$gaid', '$admin_ansver')";
//     $post = mysqli_query($connection,$query); 
//     //$ansver->query = $query;
    
//     //update count installs
//     $query = "UPDATE `apps` SET `count_installs` = `count_installs` + 1 WHERE id = '$app_id'";
//     $post = mysqli_query($connection,$query);
    
    

    
// }
// else{
//     $ansver->success = false;
//     $ansver->error = "invalid json or params undefined";
// }
// //$ansver->ip = $ip;
// $ansver = json_encode($ansver);
// echo $ansver;


///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////




// function getPost()
// {
//     if(!empty($_POST))
//     {
//        return $_POST;
//     }
//     $post = json_decode(file_get_contents('php://input'), true);
//     if(json_last_error() == JSON_ERROR_NONE)
//     {
//         return $post;
//     }
//     return false;
// }

// function sendAlarmNoNaming($app_package, $naming){
//     $headers = [
//         'Content-Type: application/json',
//     ];

//     $j_data = array ('naming'=>$naming, 'package'=>$app_package);

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_URL, 'http://146.59.52.218:25611/sendTrafficLeak');
//     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($j_data));
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     $result = curl_exec($ch);
// }

// function replace_sub($campaign_params, $link){
//     if(is_array($campaign_params)){
//        for($i=0;$i<count($campaign_params); $i++){
//             $j = $i+1;
//             $link = str_ireplace('{sub_'.$j.'}', $campaign_params[$i], $link);
//         }
//     }
    
//     return $link;
// }

// function replace_macros($url, $app, $hash, $data){
//     $new_url = $url;
//     $new_url = str_ireplace('{hash}', $hash, $new_url);
//     $new_url = str_ireplace('{app}', $app, $new_url);
//     if(is_array($data)){
//        foreach($data as $key => $val) {
//            if(strripos($url, '{'.$key.'}')){
//                $new_url = str_ireplace('{'.$key.'}', $val, $new_url);
//            }
//        }
//     }
    
//     return $new_url;
// }

// function isBot($user_agent){
//     $bots = array(
//       'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
//       'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
//       'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
//       'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
//       'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
//       'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
//       'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
//       'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
//       'Nigma.ru','bing.com','dotnetdotcom','bot', 'bots', 'facebook', 'iPad'
//     );
//     foreach($bots as $bot)
//       if(stripos($user_agent, $bot) !== false){
//         $botname = $bot;
//         return true;
//       }
//     return false;
// }



// function get_hashing_url($app_id, $customer_nick, $connection){
//    $query = "SELECT `id` FROM `customers` WHERE `name` = '$customer_nick'";
//    $get = mysqli_query($connection,$query); 
//    while($row = mysqli_fetch_assoc($get)){
//        $customer_id = $row['id'];
//    }

//    $query = "SELECT `url` FROM `app_hashing` WHERE `type` = 1 AND app = '$app_id' AND customer = '$customer_id'";
//    $get = mysqli_query($connection,$query); 
//    if(mysqli_num_rows($get) != 0){
//       while($row = mysqli_fetch_assoc($get)){
//           $hashing_url = $row['url'];
//       }
//       return array("success"=>true, "url"=>$hashing_url); 
//    }
//    else{
//       return array("success"=>false); 
//    }
// }

// function get_info_about_ip($ip, $connection){
//     //select geo key
//     $query = "SELECT `key` FROM `api_keys` WHERE id = 1";
//     $get = mysqli_query($connection,$query); 
//     while($row = mysqli_fetch_assoc($get)){
//         $geo_key = $row['key'];
//     }

//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_URL, 'http://ipwhois.pro/json/'.$ip.'?key='.$geo_key);
//     $result = curl_exec($ch);
//     curl_close($ch);
//     $obj = json_decode($result);
//     $this_asn = $obj->asn;
//     $this_country = $obj->country;
//     //получение id geo
//     $country_id = 0;
//     $query = "SELECT id FROM `list_geo` WHERE `name` = '$this_country'";
//     $get = mysqli_query($connection,$query); 
//     while($row = mysqli_fetch_assoc($get)){
//         $country_id = $row['id'];
//     }

//     return array("country"=>$this_country, "country_id"=>$country_id, "asn"=>$this_asn); 
// }

// function check_app($app_package, $connection){
//     $success = false;
//     $app_id = null;
//     $active = false;
//     $query = "SELECT id, activ FROM `apps` WHERE package = '$app_package'";
//     $get = mysqli_query($connection,$query); 
//     while($row = mysqli_fetch_assoc($get)){
//         $app_id = $row['id'];
//         if($row['activ'] == 1){
//             $active = true;
//             $success = true;
//         }
//     }
//     return array("success"=>$success, "app_id"=>$app_id, "active"=>$active);
// }

// function check_cloac($ip, $asn, $user_agent, $connection){
//     $success = false;
//     if(isBot($user_agent) == false){
//         $query = "SELECT * FROM `black_list_asn` WHERE asn = '$asn'";
//         $get = mysqli_query($connection,$query); 
//         if(mysqli_num_rows($get) == 0){
//             $query = "SELECT ip FROM `black_list_ip` WHERE ip = '$ip'";
//             $get = mysqli_query($connection,$query); 
//             if(mysqli_num_rows($get) == 0){
//                 $success = true;
//             }
//         }
//     }
//     return array("success"=>$success);

//     // if(isBot($user_agent) == false){
//     //    $headers = [
//     //        'User-Agent: '.$user_agent,
//     //        'REMOTE_ADDR: '.$ip,
//     //        'HTTP_X_FORWARDED_FOR: '.$ip,
//     //        'Content-Type: application/json'
//     //    ];
//     //    $cURLConnection = curl_init('https://apkconversion.ru/cloac_api.php?ip='.$ip.'&asn='.$asn);
//     //    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $json);
//     //    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//     //    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
//     //    $apiResponse = json_decode(curl_exec($cURLConnection), true);
//     //    curl_close($cURLConnection);
//     // }
   
//     // return array("success"=>$apiResponse['status']);
// }


// function get_organic($app_id, $geo, $connection){
//    $success = false;
//    $url = false; 
//    $log = 'one link and organic undefined';
//    $query = "SELECT link FROM `one_link_organic` WHERE app = '$app_id' AND activ = '1'";
//    $get = mysqli_query($connection,$query); 
//    if(mysqli_num_rows($get) != 0){
//        while($row = mysqli_fetch_assoc($get)){
//            $success = true;
//            $url = $row['link'];
//            $log = 'one link organic';
//        }
//    }
//    else{
//        $query = "SELECT link FROM `app_organic` WHERE app = '$app_id' AND geo = '$geo'";
//        $get = mysqli_query($connection,$query); 
//        while($row = mysqli_fetch_assoc($get)){
//            $success = true;
//            $url = $row['link'];
//            $log = 'organic link';
//        }
//    }
   
//    return array("success"=>$success, "url"=>$url, "log"=>$log);
// }


// function get_naming($app_id, $campaign, $app_package, $campaign_original, $connection){
//    $success = false;
//    $url = false; 
//    $log = 'one link and naming undefined';
//    $query = "SELECT link FROM `one_link_naming` WHERE app = '$app_id' AND activ = '1'";
//    $get = mysqli_query($connection,$query); 
//    if(mysqli_num_rows($get) != 0){
//        while($row = mysqli_fetch_assoc($get)){
//            $success = true;
//            $url = $row['link'];
//            $log = 'one link naming';
//        }
//    }
//    else{
//        $query = "SELECT link FROM `app_naming` WHERE app = '$app_id' AND `name` = '$campaign[0]'";
//        $get = mysqli_query($connection,$query); 
//        if(mysqli_num_rows($get) != 0){
//            while($row = mysqli_fetch_assoc($get)){
//                $success = true;
//                $url = $row['link'];
//                $log = 'naming link';
//            }
//        }
//        else{
//            $query = "SELECT link FROM `reserv_onelink_naming` WHERE app = '$app_id'";
//            $get = mysqli_query($connection,$query); 
//            if(mysqli_num_rows($get) != 0){
//                while($row = mysqli_fetch_assoc($get)){
//                    $success = true;
//                    $url = $row['link'];
//                    $log = 'reserv one link naming link';
//                }
//           }
//       }
//    }
   
//    if($log == "one link and naming undefined"){
//     sendAlarmNoNaming($app_package, $campaign_original);
//    }

//    return array("success"=>$success, "url"=>$url, "log"=>$log);
// }

// function add_install(){

// }

// function edit_html_chars($string){
//     $string = str_ireplace('%7B', '{', $string);
//     $string = str_ireplace('%7D', '}', $string);

//     return $string;
// }



// $ansver = new stdClass();
// $json = getPost();

// $app = $json['app'];
// $hash = $json['hash'];
// //for adjust
// if($json['dataAdjust'] != null){
//     $json['data'] = $json['dataAdjust'];
//     $json['dataAdjust'] = null;
// }
// //for deeplink
// if($json['deeplink'] != null && $json['deeplink'] != 'null'){
//     $deeplink = $json['deeplink'];
//     $deeplink = str_ireplace('app://', '', $deeplink);
//     if(!isset($json['data']['campaign'])){
//        $json['data']['campaign'] = $deeplink;
//     }
// }

// if(isset($json['data']['campaign'])){
//     //echo $json['data']['campaign'];
//     $campaign_original = $json['data']['campaign'];
//     $campaign = $json['data']['campaign'];
//     $campaign = str_ireplace(" ", "_", $campaign);
//     $campaign = explode('_', $campaign);
// }

// $user_agent = $_SERVER['HTTP_USER_AGENT'];



// if($json != false && $app != '' && $hash != ''){
    
//     if($_GET['ip'] != ''){
//         $ip = $_GET['ip'];
//     }
//     else{
//         $client  = @$_SERVER['HTTP_CLIENT_IP'];
//         $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
//         $remote  = @$_SERVER['REMOTE_ADDR'];
//         if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
//         elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
//         else $ip = $remote;
//     }

//     //for testing app send data
//     //$dt_t = date("y-m-d H:i:s");
//    //  if($app == '1564556081' OR $app == '1564556081' OR $app == 'com.photoeditorandre.oksma'){
//    //     //$send_info = implode(",", $data);
//    //     $send_info = json_encode($data);
//    //  	$send_dop_info = 'hash : '.$hash.', ip : '.$ip.', user_agent : '.$user_agent;
//    //  	$query = "INSERT INTO `app_send_data` (`app`, `info`, `dop_info`, `dt_t`) VALUES('$app', '$send_info', '$send_dop_info', '$dt_t')";
//    //  	$post = mysqli_query($connection,$query); 
//    //  }
    

//     $check_app = check_app($app, $connection);
//     if($check_app['success'] == true){
//         $info_about_ip = get_info_about_ip($ip, $connection);
//         $check_cloac = check_cloac($ip, $info_about_ip['asn'], $user_agent, $connection);
//         if($check_cloac['success'] == true){
//            if(!isset($json['data']['campaign'])){
//                $redirect_data = get_organic($check_app['app_id'], $info_about_ip['country_id'], $connection);
//            }
//            else{
//                $redirect_data = get_naming($check_app['app_id'], $campaign, $app, $campaign_original, $connection);
//            }

//            if($redirect_data['success'] == true){
//                $ansver->success = true;
//                $ansver->url = replace_sub($campaign, replace_macros(edit_html_chars($redirect_data['url']), $app, $hash, $json['data']));
//            }
//            else{
//                $ansver->success = false;
//                $ansver->error = $redirect_data['log'];
//            }
//         }
//         else{
//            $ansver->success = false;
//            $ansver->error = "cloac block";
//         }
        
//     }
//     else{
//         $ansver->success = false;
//         $ansver->error = "app not activ or undefined";
//     }

//     //insert instal info
//     $app_id = $check_app['app_id'];
//     $dt = date('Y-m-d H:i:s');
//     if(isset($json['data']['campaign'])){
//         $type = 'Non-organic';
//     }
//     else{
//         $type = 'Organic';
//     }
    
//     $link = $ansver->url;
//     $geo = $info_about_ip['country_id'];
//     $device_id = $hash;
//     $gaid = $json['gaid'];
//     if(isset($redirect_data['log'])){
//        $dop_log = $redirect_data['log'];
//     }
//     else{
//        $dop_log = $ansver->error;
//     }
//     if($ansver->success == false){
//         $admin_ansver = 'false';
//     }
//     else{
//         $admin_ansver = 'true';
//     }
    
    
//     $install_data = json_encode($json);
//     $query = "INSERT INTO installs_log (`app`, `type`, `ip`, `link`, `geo`, `dt`, `device_id`, `install_data`, `user_agent`, `dop_log`, `gaid`, `ansver`) VALUES ('$app_id', '$type', '$ip', '$link', '$geo', '$dt', '$device_id', '$install_data', '$user_agent', '$dop_log', '$gaid', '$admin_ansver')";
//     $post = mysqli_query($connection,$query); 
//     //$ansver->query = $query;
    
//     //update count installs
//     $query = "UPDATE `apps` SET `count_installs` = `count_installs` + 1 WHERE id = '$app_id'";
//     $post = mysqli_query($connection,$query);
    
    

    
// }
// else{
//     $ansver->success = false;
//     $ansver->error = "invalid json or params undefined";
// }
// //$ansver->ip = $ip;
// $ansver = json_encode($ansver);
// echo $ansver;

