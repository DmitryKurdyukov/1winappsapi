<?php 
$headers = [
    'Content-Type: application/json',
    'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
    'Auth-Token: E0m6A9FoksQwQ3xmW2X2pZaYk'
];

// $j_data = array (
//     'select' => ['name', 'package', 'public_time', 'icon', 'platform', 'production_type'],
//     'where' => [array('platform'=>['android']), array('activ'=>true), array('status'=>['public']), array('production'=>true)],
// );
// $j_data = array (
//     'select' => ['name', 'package', 'status', 'platform', 'comment', 'host', 'appsflyer_key', 'developer', 'activ', 'create_time', 'public_time', 'ban_time', 'onesignal_app_id', 'onesignal_api_key', 'production', 'production_type', 'count_instals', 'icon'],
//     'where' => [array('platform'=>['android']), array('activ'=>true), array('status'=>['public']), array('production'=>true)],
//     'limit' => 3,
// );

// $j_data = array (
//     'login_name' => 'EijUaqQ7JB2Ktn85snlDRw==',
//     'password' => 'i3EPZ29hISKNGpt2kgrHIg==',
//     'client' => 'bot'
// );
//alarm_bot_secret
//s9bs1pyzasvgt4w8pxnhvkywlmfeopfa
//cWCTe1aTAgRmJbzJxLG2DhwcR


$j_data = array (
    'app' => 'com.ninja.moneyninja',
    // 'geo' => 'RU',
    // 'naming' => 'asd',
    // 'push_title' => 'asd',
    // 'push_body' => 'asd',
    // 'event' => 'inst'
);

// api.1-w.app
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'http://1winappsapi.local/api/transfer_users/appsflyer_transfer/');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($j_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
echo $result;
// $select_sql = "";
// $select = ['name', 'icon', 'package'];
// $select_icon = false;

// for($i=0; $i<count($select); $i++){
//     if($select[$i] == 'icon'){
//         $select_icon = true;
//     }
//     else{
//         if($i<count($select)-1){
//             $select_sql=$select_sql." "
//         }
//         else{

//         }
//     }
// }

// foreach($select as $key=>$value){
//     if($key == 'multilogin'){
//         $arr['octo'] = $arr['multilogin'];
//         unset($arr['multilogin']);
//     }
//     $params[] = $key;
// }




//возвращать что данный пользователь уже авторизован в случае если по одним и тем же данным хочет авторизоваться
// сделать count_installs полем и сделать crone для синхронизации (сделать поле проверки синхронизации и добавить инкремент в инстале)
// добавить апишку для onelink и reserve (нейминг и органика)
// сделать мини тз к апи

?>

