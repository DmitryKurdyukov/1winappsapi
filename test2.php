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


