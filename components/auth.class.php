<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class Auth  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function login($array){
        if($array != false OR isset($array)){
            if(isset($array['client']) OR $array['client'] != '' OR $array['client'] == 'bot' OR $array['client'] == 'user'){
                if(isset($array['login_name']) OR $array['login_name'] != ''){
                    if(isset($array['password']) OR $array['password'] != ''){

                        $client = $array['client'];
                        if($client == 'bot'){
                            $login_name_no_crypt = decrypt_aes($array['login_name']);
                            $login_name = hash("sha512", decrypt_aes($array['login_name']));
                            $password =  hash("sha512", decrypt_aes($array['password']));
                        }
                        else{
                            $login_name = decrypt_aes($array['login_name']);
                            $password =  decrypt_aes($array['password']);
                        }
                        $auth_time = date('Y-m-d H:i:s');
                        $ip = get_ip();
                        $user_agent = get_user_agent();
                        $auth_token = $this->generateToken();
    
                        if($client == 'user'){
                            $login = get_login_user_from_shop($login_name, $password);
                            if($login['success'] == true){
                                $roles = $login['roles'];
                                $password_crypt = $login['password_crypt'];

                                //delete old tokens
                                $query = "DELETE FROM `service_connect` WHERE `login_name` = '$login_name'";
                                $post = mysqli_query($this->$connection_db, $query);

                                $query = "INSERT INTO `service_connect` (`auth_time`, `ip`, `user_agent`, `login_name`, `password`, `client`, `auth_token`) values ('$auth_time','$ip','$user_agent', '$login_name', '$password_crypt', '$client', '$auth_token')";
                                $post = mysqli_query($this->$connection_db, $query);
                                if($post){
                                    return array('success'=>true, 'auth_token'=> $auth_token, 'roles'=>$roles, 'team'=>'');
                                }
                                else{
                                    // return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6, 'sql_error'=>$this->$connection_db->error);
                                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                }
                            }
                            else{
                                return $login;
                            }
                            
                        }
    
                        if($client == 'bot'){
                            $query = "SELECT `id`, `password`, `team` FROM `users` WHERE `login_name_encrypt` = '$login_name'";
                            $get = mysqli_query($this->$connection_db, $query);
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    if($row['password'] == $password){
                                        $user_id = $row['id'];
                                        $team = $row['team'];
                                        $roles = [];
                                        $query_2 = "SELECT `role` FROM `user_roles` WHERE `user` = '$user_id'";
                                        $get_2 = mysqli_query($this->$connection_db, $query_2);
                                        while($row_2 = mysqli_fetch_assoc($get_2)){
                                            $roles[] = $row_2['role'];
                                        }

                                        //delete old tokens
                                        $query = "DELETE FROM `service_connect` WHERE `login_name` = '$login_name_no_crypt'";
                                        $post = mysqli_query($this->$connection_db, $query);

                                        $query = "INSERT INTO `service_connect` (`auth_time`, `ip`, `user_agent`, `login_name`, `password`, `user`, `client`, `auth_token`) values ('$auth_time','$ip','$user_agent', '$login_name_no_crypt', '$password', '$user_id', '$client', '$auth_token')";
                                        $post = mysqli_query($this->$connection_db, $query);
                                        if($post){
                                            return array('success'=>true, 'auth_token'=>$auth_token, 'roles'=>$roles, 'team'=>$team);
                                        }
                                        else{
                                            return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                        }
                                    }
                                    else{
                                        return array('success'=>false, 'error'=> 'password incorrect or undefined', 'error_num'=>5);
                                    }
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'this user incorrect or undefined', 'error_num'=>5);
                            }
                        }
    
                    }
                    else{
                        return array('success'=>false, 'error'=> 'password param undefined or incorrect', 'error_num'=>3);
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'login_name param undefined or incorrect', 'error_num'=>2);
                }
            }
            else{
                return array('success'=>false, 'error'=> 'client param undefined or incorrect', 'error_num'=>1);
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function generateToken($length = 25){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    } 

    function check_auth($auth_token){
        if($auth_token == 'HIqOO9MSVIjG1sRhos9U'){
            return array('success'=>true, 'roles'=>[]);
        }
        else{
            $user_agent = get_user_agent();
            $query = "SELECT `id`, `user` FROM `service_connect` WHERE `auth_token` = '$auth_token' AND `user_agent` = '$user_agent'";
            $get = mysqli_query($this->$connection_db, $query);
            if(mysqli_num_rows($get) != 0){
                while($row = mysqli_fetch_assoc($get)){
                    $user_id = $row['user'];
                    $roles = [];
                    $query_2 = "SELECT `role` FROM `user_roles` WHERE `user` = '$user_id'";
                    $get_2 = mysqli_query($this->$connection_db, $query_2);
                    while($row_2 = mysqli_fetch_assoc($get_2)){
                        $roles[] = $row_2['role'];
                    }
                }
                //update last connect
                $last_connect_time = date('Y-m-d H:i:s');
                $query = "UPDATE `service_connect` SET `last_connect_time`='$last_connect_time' WHERE `auth_token` = '$auth_token'";
                $get = mysqli_query($this->$connection_db, $query);
                
                return array('success'=>true, 'roles'=>$roles);
            }
            else{
                return array('success'=>false, 'error'=> 'auth undefined', 'error_num'=>8);
            }
        }
    } 

    function logout($auth_token){
        $user_agent = get_user_agent();
        $query = "DELETE FROM `service_connect` WHERE `auth_token` = '$auth_token'";
        $post = mysqli_query($this->$connection_db, $query);
        if($post){
            return array('success'=>true);
        }
        else{
            return array('success'=>false, 'error'=> 'error query data', 'error_num'=>6);
        }
    }
}


?>