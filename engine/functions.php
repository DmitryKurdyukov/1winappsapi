<?php 
function page_refresh(){
    echo "<meta http-equiv='refresh' content='1'>" ;
}

function page_navigate($url){
    echo "<scritp>location.replace(".$url.")</scritp>" ;
}

function get_protocol() { 
    if($_SERVER['HTTPS'] !== 'off'){
        return 'http://';
    }
    else{
        return 'https://';
    }
}

function getRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

function getAuthHeader() {
    $headers = getRequestHeaders();
    if(isset($headers['Auth-Token'])){
        return $headers['Auth-Token'];
    }
    else{
        return 'false';
    }
}

function edit_html_chars($string){
    $string = str_ireplace('%7B', '{', $string);
    $string = str_ireplace('%7D', '}', $string);

    return $string;
}

function get_ip(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
    else $ip = $remote;

    return $ip;
}

function get_user_agent(){
    return $_SERVER['HTTP_USER_AGENT'];
}

function check_ip_access(){
    if(get_ip() == '146.59.52.218'){
        return true;
    }
    else{
        return false;
    }
    
}

function getPost()
{
    if(!empty($_POST))
    {
       return $_POST;
    }
    $post = json_decode(file_get_contents('php://input'), true);
    if(json_last_error() == JSON_ERROR_NONE)
    {
        return $post;
    }
    return false;
}

function corsAllow(){
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
        // whitelist of safe domains
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 99999');
        // header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
    }
}

function check_associative_key_in_nums_array($key, $array){
    $res = false;
    for($i=0; $i<count($array); $i++){
        $arr = array_keys($array[$i]);
        if(is_int(array_search($key, $arr))){
            $res = true;
            return array('success'=>true, 'data'=>$array[$i][$key]);
        }
    }
    if($res == false){
        return array('success'=>false);
    }
}

function decrypt_aes($data){
    $key = hex2bin("384e3572577172476973796d65666768");
    $iv =  hex2bin("abcdef9876543210abcdef9876543210");
    $encrypted = $data;
    $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
    $decrypted = trim($decrypted);
    return $decrypted;
    
    //original key - 8N5rWqrGisymefgh
    // echo bin2hex("8N5rWqrGisymefgh");
}

function get_user_data_from_shop_api(){
    $headers = [
        'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
        'Content-Type: application/json'
    ];
    $cURLConnection = curl_init('https://1win.shop/api/users');
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
    $apiResponse = json_decode(curl_exec($cURLConnection), true);
    curl_close($cURLConnection);
    if(isset($apiResponse['data']) && is_array($apiResponse['data'])){
        return array('success'=>true, 'data'=>$apiResponse);
    }
    else{
        return array('success'=>false, 'data'=>'error api shop');
    }
}

function get_user_data_from_shop(){
    if(file_exists($_SERVER["DOCUMENT_ROOT"].'/modules/api_users_shop_data.txt')){
        $users_shop_data_file = fopen($_SERVER["DOCUMENT_ROOT"].'/modules/api_users_shop_data.txt', "r");
        $users_shop_data = json_decode(fgets($users_shop_data_file), true);
        fclose($users_shop_data_file);
        $answer = array('success'=>false);

        if(isset($users_shop_data['data']) && is_array($users_shop_data['data'])){
            return array('success'=>true, 'data'=>$users_shop_data['data']);
        }
        else{
            $users_shop_data = get_user_data_from_shop_api();
            if($users_shop_data['success'] == true){
                $answer['success'] = true;
                $answer['data'] = $users_shop_data['data'];
            }
            else{
                return array('success'=>false, 'data'=>'error api shop');
            }
        }
        
        
        
        return $answer;
        
    }
    else{
        return array('success'=>false, 'error'=>'users data from shop undefined');
    }
}

function get_login_user_from_shop($login_name, $password){
    if(file_exists($_SERVER["DOCUMENT_ROOT"].'/modules/api_users_shop_data.txt')){
        $users_shop_data_file = fopen($_SERVER["DOCUMENT_ROOT"].'/modules/api_users_shop_data.txt', "r");
        $users_shop_data = json_decode(fgets($users_shop_data_file), true);
        fclose($users_shop_data_file);
        $answer = array('success'=>false);

        $check = false;
        for($i=0; $i<count($users_shop_data['data']); $i++){
            if($users_shop_data['data'][$i]['email'] == $login_name){
                $check = true;
                if(password_verify($password, $users_shop_data['data'][$i]['password'])){
                    $answer['success'] = true;
                    $answer['roles'] = $users_shop_data['data'][$i]['roles'];
                    $answer['password_crypt'] = $users_shop_data['data'][$i]['password'];
                }
                else{
                    $answer['error'] = 'incorrect password';
                }
                break;
            }
        }

        if($check == false){
            $answer['error'] = 'this user undefined';
        }
        
        return $answer;
        
    }
    else{
        return array('success'=>false, 'error'=>'users data from shop undefined');
    }
}
?>