<?php 


define ( 'ROOT_DIR', dirname ( __FILE__ ) );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );
define ( 'COMPONENTS_DIR', ROOT_DIR . '/components' );


$url = explode('/', $_GET['url']); 


include_once ENGINE_DIR."/database.php";
include_once ENGINE_DIR."/functions.php";
include_once COMPONENTS_DIR."/auth.class.php";
include_once COMPONENTS_DIR."/apps.class.php";
include_once COMPONENTS_DIR."/geo.class.php";
include_once COMPONENTS_DIR."/black_list_ip.class.php";
include_once COMPONENTS_DIR."/black_list_asn.class.php";
include_once COMPONENTS_DIR."/installs_log.class.php";
include_once COMPONENTS_DIR."/test_curl_app.class.php";
include_once COMPONENTS_DIR."/cloac_script.class.php";
include_once COMPONENTS_DIR."/service_connect.class.php";
include_once COMPONENTS_DIR."/push_sender.class.php";
include_once COMPONENTS_DIR."/transfer_users.class.php";

// if($check_auth = $auth->check_auth(getAuthHeader())){
//     if($check_auth['success'] == true){
        
//     }
//     else{
//         $isset_answer = true;
//         echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
//     }
// }

corsAllow();
$answer = array('success'=>false, 'error'=>'no rout');

$isset_answer = false;

$auth = new Auth ;
if($url[0] == 'api'){
    if($url[1] == 'auth'){
        if($url[2] == 'login'){
            $login = $auth->login(getPost());
            if(isset($login)){
                $isset_answer = true;
                echo json_encode($login, JSON_UNESCAPED_UNICODE);
            }
        }
        if($url[2] == 'logout'){
            $logout = $auth->logout(getAuthHeader());
            if(isset($logout)){
                $isset_answer = true;
                echo json_encode($logout, JSON_UNESCAPED_UNICODE);
            }
        }
        if($url[2] == 'check_auth'){
            $check_auth = $auth->check_auth(getAuthHeader());
            if(isset($check_auth)){
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'get_user_data_from_shop'){
        $isset_answer = true;
        echo json_encode(get_user_data_from_shop(), JSON_UNESCAPED_UNICODE);
    }
    if($url[1] == 'apps'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $apps = new Apps ;
                if($url[2] == 'get_apps_data'){
                    $apps_data = $apps->get_apps_data(getPost());
                    if(isset($apps_data)){
                        $isset_answer = true;
                        echo json_encode($apps_data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'add'){
                    $data = $apps->add(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'update'){
                    $data = $apps->update(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'namings'){
                    $data = $apps->namings(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'organics'){
                    $data = $apps->organics(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'organic_one_link'){
                    $data = $apps->organic_one_link(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'naming_one_link'){
                    $data = $apps->naming_one_link(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'reserv_naming_one_link'){
                    $data = $apps->reserv_naming_one_link(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'accounts'){
                    $data = $apps->accounts(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'geo'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $geo = new Geo ;
                if($url[2] == 'get_list_geo'){
                    $geo_data = $geo->get_list_geo();
                    if(isset($geo_data)){
                        $isset_answer = true;
                        echo json_encode($geo_data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'add_geo'){
                    $geo_data = $geo->add_geo(getPost());
                    if(isset($geo_data)){
                        $isset_answer = true;
                        echo json_encode($geo_data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'update_geo'){
                    $geo_data = $geo->update_geo(getPost());
                    if(isset($geo_data)){
                        $isset_answer = true;
                        echo json_encode($geo_data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'delete_geo'){
                    $geo_data = $geo->delete_geo(getPost());
                    if(isset($geo_data)){
                        $isset_answer = true;
                        echo json_encode($geo_data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'black_list_ip'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $black_list_ip = new blackListIp ;
                if($url[2] == 'get_list'){
                    $data = $black_list_ip->get_list();
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'add'){
                    $data = $black_list_ip->add(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'update'){
                    $data = $black_list_ip->update(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'delete'){
                    $data = $black_list_ip->delete(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'black_list_asn'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $black_list_asn = new blackListAsn ;
                if($url[2] == 'get_list'){
                    $data = $black_list_asn->get_list();
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'add'){
                    $data = $black_list_asn->add(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'update'){
                    $data = $black_list_asn->update(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'delete'){
                    $data = $black_list_asn->delete(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'installs_log'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $installs_log = new installs_log ;
                if($url[2] == 'get_data'){
                    $data = $installs_log->get_data(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'test_curl_app'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $test_curl_app = new test_curl_app ;
                if($url[2] == 'curl'){
                    $data = $test_curl_app->curl(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'cloac_script'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $cloac_script = new cloac_script ;
                if($url[2] == 'get'){
                    $data = $cloac_script->get();
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'update'){
                    $data = $cloac_script->update(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'service_connect'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $service_connect = new service_connect ;
                if($url[2] == 'get'){
                    $data = $service_connect->get(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
                if($url[2] == 'add_user_bot'){
                    $data = $service_connect->add_user_bot(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'push_sender'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $push_sender = new push_sender ;
                if($url[2] == 'send_naming_push'){
                    $data = $push_sender->send_naming_push(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    if($url[1] == 'transfer_users'){
        if($check_auth = $auth->check_auth(getAuthHeader())){
            if($check_auth['success'] == true){
                $transfer_users = new transfer_users ;
                if($url[2] == 'appsflyer_transfer'){
                    $data = $transfer_users->appsflyer_transfer(getPost());
                    if(isset($data)){
                        $isset_answer = true;
                        echo json_encode($data, JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            else{
                $isset_answer = true;
                echo json_encode($check_auth, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}

if($isset_answer == false){
    echo json_encode($answer, JSON_UNESCAPED_UNICODE);
}


//id, auth_time, last_connect_time, ip, user_agent, login_name, login_name_crypt, password, user_roles, client, token
?>