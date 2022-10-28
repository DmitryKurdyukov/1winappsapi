<?php 
$headers = [
    'Content-Type: application/json',
    'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
    'Auth-Token: 76CmZovakXodlqreCAgarhMok'
];

// $j_data = array (
//     'select' => ['name', 'package', 'status', 'platform', 'comment', 'host', 'appsflyer_key', 'developer', 'activ', 'create_time', 'public_time', 'ban_time', 'onesignal_app_id', 'onesignal_api_key', 'production', 'production_type', 'count_instals', 'icon'],
//     'where' => [array('platform'=>['android']), array('activ'=>true), array('status'=>['public'])],
//     'limit' => 3,
// );
// $j_data = array (
//     'select' => ['name', 'public_time', 'icon'],
//     'where' => [array('package'=>['com.rise.egypt.spell'])],
//     // 'limit' => 1,
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
    'app' => 'com.bananas',
    'update' => array('package'=>'com.bananas.andbahamas', 'production_type'=>'rent'),
    // 'naming' => 'asd',
    // 'push_title' => 'asd',
    // 'push_body' => 'asd',
    // 'event' => 'inst'
);


$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'http://1winapi/api/apps/update/');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($j_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
echo $result;

// foreach($arr['update'] as $key=>$value){
//     // if($key == 'multilogin'){
//     //     $arr['octo'] = $arr['multilogin'];
//     //     unset($arr['multilogin']);
//     // }
//     $params[] = $key;
// }




//возвращать что данный пользователь уже авторизован в случае если по одним и тем же данным хочет авторизоваться
// сделать count_installs полем и сделать crone для синхронизации (сделать поле проверки синхронизации и добавить инкремент в инстале)
// добавить апишку для onelink и reserve (нейминг и органика)
// сделать мини тз к апи

?>

