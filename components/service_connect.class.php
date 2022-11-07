<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class service_connect  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get($array){
        if($array != false){
            if(isset($array['type'])){
                if($array['type'] == 'connect_now' OR $array['type'] == 'users_bot'){
                    //вывод таблички тех кто подключен к апи в данный момент
                    if($array['type'] == 'connect_now'){
                        if(isset($array['client'])){
                            if($array['client'] == 'bot' OR $array['client'] == 'user' OR $array['client'] == 'all'){
                                $data = [];
                                if($array['client'] == 'all'){
                                    $client = $array['client'];
                                    $query = "SELECT `auth_time`, `login_name`, `ip`, `last_connect_time`, `client` FROM service_connect ";
                                }
                                else{
                                    $client = $array['client'];
                                    $query = "SELECT `auth_time`, `login_name`, `ip`, `last_connect_time` FROM service_connect WHERE `client` = '$client'";
                                }
                                
                                $get = mysqli_query($this->$connection_db,$query); 
                                while($row = mysqli_fetch_assoc($get)){
                                    $data[] = $row;
                                }
                        
                                return array('success'=>true, 'data'=>$data);
                            }
                            else{
                                return array('success'=>false, 'error'=> 'param client must be a bot or user');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'param client undefined post json data');
                        }
                    }
                    //вывод таблички с пользователями апи (боты, сервисы у которых client bot)
                    if($array['type'] == 'users_bot'){
                        $query = "SELECT `login_name` FROM users ";
                        $get = mysqli_query($this->$connection_db,$query); 
                        while($row = mysqli_fetch_assoc($get)){
                            $login_name = $row['login_name'];
                            $query2 = "SELECT `auth_time`, `login_name`, `ip`, `last_connect_time`, `client` FROM `service_connect` WHERE `login_name` = '$login_name'";
                            $get2 = mysqli_query($this->$connection_db,$query2); 
                            if(mysqli_num_rows($get2) != 0){
                                while($row2 = mysqli_fetch_assoc($get2)){
                                    $row['auth_time'] = $row2['auth_time'];
                                    $row['ip'] = $row2['ip'];
                                    $row['last_connect_time'] = $row2['last_connect_time'];
                                    $row['client'] = $row2['client'];
                                    
                                }
                            }
                            else{
                                $row['auth_time'] = null;
                                $row['ip'] = null;
                                $row['last_connect_time'] = null;
                                $row['client'] = null;
                            }
                            
                            $data[] = $row;
                        }
                
                        return array('success'=>true, 'data'=>$data);
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'param type must be a connect_now or users_bot');
                }
            }
            else{
                return array('success'=>false, 'error'=> 'param type undefined post json data');
            }

            
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
        
    }

    function add_user_bot($array){
        if($array != false){
            if(isset($array['login_name']) && $array['login_name'] != '' && isset($array['password']) && $array['password'] != ''){
                $login_name = decrypt_aes($array['login_name']);
                $login_name_encrypt = hash("sha512", decrypt_aes($array['login_name']));
                $password =  hash("sha512", decrypt_aes($array['password']));
                $query = "INSERT INTO `users` (`login_name`, `login_name_encrypt`, `password`) VALUES ('$login_name', '$login_name_encrypt', '$password')";
                $post = mysqli_query($this->$connection_db,$query); 
                if($post){
                    $user_id = $this->$connection_db->insert_id;
                    //add roles to user
                    $query = "INSERT INTO `user_roles` (`user`, `role`) VALUES ('$user_id', 'admin')";
                    $post = mysqli_query($this->$connection_db,$query);
                    $query = "INSERT INTO `user_roles` (`user`, `role`) VALUES ('$user_id', 'developer')";
                    $post = mysqli_query($this->$connection_db,$query);

                    return array('success'=>true);
                }
                else{
                    return array('success'=>false, 'error'=> 'sql query error');
                } 
            }
            else{
                return array('success'=>false, 'error'=> 'param login_name or password incrorrect or undefined');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
        
    }

    
}


?>