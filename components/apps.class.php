<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

class Apps  {

    private $connection_db;

    public function __construct() {
        $this->$connection_db = connection_db();
    }

    function get_apps_data($array){
        if($array != false){
            if(isset($array['select']) && is_array($array['select']) && count($array['select']) > 0){
                $count_installs = false;
                $icon = false;
                // if(is_int(array_search('count_installs', $array['select']))){
                //     $count_installs = true;
                //     unset($array['select'][array_search('count_installs', $array['select'])]);
                // }
                if(is_int(array_search('icon', $array['select']))){
                    $icon = true;
                    $new_arr_select = [];
                    for($i=0; $i<count($array['select']); $i++){
                        if($array['select'][$i] != 'icon'){
                            $new_arr_select[] = $array['select'][$i]; 
                        }
                    }
                    $array['select'] = $new_arr_select;
                    // unset($array['select'][array_search('icon', $array['select'])]);
                    // $array['select'][array_search('icon', $array['select'])] = '';
                }

                //generate select string in database
                $select_query = "";
                for($i=0; $i<count($array['select']); $i++){
                    if($array['select'][$i] != ''){
                        if($i < count($array['select'])-1){
                            $select_query = $select_query."`".$array['select'][$i]."`, ";
                        }
                        else{
                            $select_query = $select_query."`".$array['select'][count($array['select'])-1]."`";
                        }
                    }
                }
                
                //generate where string in database
                $where = "";
                $where_query = [];
                if(isset($array['where'])){
                    $where = "WHERE";
                    $where_package = check_associative_key_in_nums_array('package', $array['where']);
                    if($where_package['success'] == true){
                        for($i=0; $i<count($where_package['data']); $i++){
                            $where_query[] = "`package`="."'".$where_package['data'][$i]."'";
                        }
                    }

                    $where_status = check_associative_key_in_nums_array('status', $array['where']);
                    if($where_status['success'] == true){
                        $status_replace_arr_for_db = array('develop'=>0, 'moderation'=>1, 'public'=>2, 'ban'=>3);
                        $status_replace_arr_for_db_keys = array_keys($status_replace_arr_for_db);
                        for($i=0; $i<count($where_status['data']); $i++){
                            if(is_int(array_search($where_status['data'][$i],$status_replace_arr_for_db_keys))){
                                $where_query[] = "`status`="."'".$status_replace_arr_for_db[$where_status['data'][$i]]."'";
                            }
                        }
                    }

                    $where_activ = check_associative_key_in_nums_array('activ', $array['where']);
                    if($where_activ['success'] == true  && is_bool($where_activ['data'])){
                        if($where_activ['data'] == true){
                            $where_query[] = "`activ`="."'1'";
                        }
                        else{
                            $where_query[] = "`activ`="."'0'";
                        }
                    }

                    $where_team = check_associative_key_in_nums_array('team', $array['where']);
                    if($where_team['success'] == true){
                        if($where_team['data'] == 'team2'){
                            $where_query[] = "`team`="."'team2'";
                        }
                        else{
                            $where_query[] = "`team`="."''";
                        }
                    }
                    else{
                        $where_query[] = "`team`="." '' ";
                    }

                    $where_platform = check_associative_key_in_nums_array('platform', $array['where']);
                    if($where_platform['success'] == true){
                        for($i=0; $i<count($where_platform['data']); $i++){
                            $where_query[] = "`platform`="."'".$where_platform['data'][$i]."'";
                        }
                    }

                    $where_production = check_associative_key_in_nums_array('production', $array['where']);
                    if($where_production['success'] == true && is_bool($where_production['data'])){
                        if($where_production['data'] == true){
                            $where_query[] = "`production`="."'1'";
                        }
                        else{
                            $where_query[] = "`production`="."'0'";
                        }
                    }

                    $where_production_type = check_associative_key_in_nums_array('production_type', $array['where']);
                    if($where_production_type['success'] == true && is_array($where_production_type['data'])){
                        if(is_int(array_search('sale',$where_production_type['data']))){
                            $where_query[] = "`production_type`="."'0'";
                        }
                        if(is_int(array_search('rent',$where_production_type['data']))){
                            $where_query[] = "`production_type`="."'1'";
                        }
                    }

                    $where_query_temp = "";
                    for($i=0; $i<count($where_query); $i++){
                        if($i==0){
                            $where_query_temp=$where_query_temp.$where_query[$i];
                        }
                        else{
                            $where_query_temp=$where_query_temp." AND ".$where_query[$i];
                        }
                    }
                    $where_query = $where_query_temp;
                    
                    //generate limit string for databse
                    $limit_query = "";
                    if(isset($array['limit'])){
                        if(is_array($array['limit'])){
                            $limit_query = "LIMIT ".$array['limit'][0].", ".$array['limit'][1];
                        }
                        if(is_int($array['limit'])){
                            $limit_query = "LIMIT ".$array['limit'];
                        }
                    }
                    
                    //generate query
                    $answer = [];
                    $query = "SELECT `id`, ".$select_query." FROM `apps` ".$where." ".$where_query." ORDER BY -id ".$limit_query;
                    // echo $query;
                    $get = mysqli_query($this->$connection_db, $query);
                    if($get){
                        if(mysqli_num_rows($get) != 0){
                            while($row = mysqli_fetch_assoc($get)){
                                $app_id = $row['id'];
                                if(isset($row['developer'])){
                                    $row['developer'] = intval($row['developer']);
                                }
                                if(isset($row['status'])){
                                    switch ($row['status']) {
                                        case 0:
                                            $row['status'] = 'develop';
                                            break;
                                        case 1:
                                            $row['status'] = 'moderation';
                                            break;
                                        case 2:
                                            $row['status'] = 'public';
                                            break;
                                        case 3:
                                            $row['status'] = 'ban';
                                            break;
                                    }
                                }
                                
                                if(isset($row['activ'])){
                                    switch ($row['activ']) {
                                        case 0:
                                            $row['activ'] = false;
                                            break;
                                        case 1:
                                            $row['activ'] = true;
                                            break;
                                    }
                                }
                                
                                if(isset($row['production'])){
                                    switch ($row['production']) {
                                        case 0:
                                            $row['production'] = false;
                                            break;
                                        case 1:
                                            $row['production'] = true;
                                            break;
                                    }
                                }
                                
                                if(isset($row['production_type'])){
                                    switch ($row['production_type']) {
                                        case 0:
                                            $row['production_type'] = 'rent';
                                            break;
                                        case 1:
                                            $row['production_type'] = 'sale';
                                            break;
                                        case 2:
                                            $row['production_type'] = 'rent and sale';
                                            break;
                                    }
                                }
                                

                                if($count_installs == true){
                                    $query_2 = "SELECT `id` FROM `installs_log` WHERE `app` = '$app_id'";
                                    $get_2 = mysqli_query($this->$connection_db, $query_2);
                                    $row['count_installs'] = mysqli_num_rows($get_2);
                                }
                                if($icon == true){
                                    $query_2 = "SELECT `icon` FROM `app_icon` WHERE `app` = '$app_id'";
                                    $get_2 = mysqli_query($this->$connection_db, $query_2);
                                    while($row_2 = mysqli_fetch_assoc($get_2)){
                                        $row['icon'] = $row_2['icon'];
                                    }
                                }
                                $answer[] = $row;
                            }
                            return array('success'=>true, 'data'=> $answer);
                        }
                        else{
                            return array('success'=>true, 'data'=> []);
                        }
                    }
                    else{
                        return array('success'=>false, 'error'=> 'error database query', 'error_num'=>10);
                    }
                }
            }
            else{
                return array('success'=>false, 'error'=> 'selecct param incorrect or undefined', 'error_num'=>9);
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function add($array){
        if($array != false){
            if(isset($array['name']) && $array['name'] != '' && isset($array['package']) && $array['package'] != '' && isset($array['platform']) && $array['platform'] != ''){
                $name = $array['name'];
                $package = $array['package'];
                $platform = $array['platform'];
                $query = "SELECT `id` FROM `apps` WHERE `package` = '$package'";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) == 0){
                    $create_time = date('Y-m-d H:i:s');
                    

                    if(isset($array['developer']) &&  is_int($array['developer'])){
                        $developer = $array['developer'];
                    }
                    else{
                        $developer = 0;
                    }
                    if(isset($array['team']) && $array['team'] != ''){
                        $package = base64_encode($package);
                        $team = $array['team'];

                        $query = "INSERT INTO apps (`name`, `package`, `developer`, `platform`, `create_time`, `team`) VALUES ('$name', '$package', '$developer', '$platform', '$create_time', $team)";
                    }
                    else{
                        $query = "INSERT INTO apps (`name`, `package`, `developer`, `platform`, `create_time`) VALUES ('$name', '$package', '$developer', '$platform', '$create_time')";
                    }
                    
                    $post = mysqli_query($this->$connection_db,$query); 
                    if($post){
                        $app_id = $this->$connection_db->insert_id; 
                        //доп создание записи в таблице с иконками для приложения
                        $query = "INSERT INTO app_icon (`app`) VALUES ('$app_id')";
                        $post = mysqli_query($this->$connection_db,$query); 
                        //доп создание записи в таблице c onelink organic
                        $query = "INSERT INTO one_link_naming (`app`) VALUES ('$app_id')";
                        $post = mysqli_query($this->$connection_db,$query);
                        //доп создание записи в таблице c onelink naming
                        $query = "INSERT INTO one_link_organic (`app`) VALUES ('$app_id')";
                        $post = mysqli_query($this->$connection_db,$query);
        
                        return array('success'=>true, 'package'=>$package);
                    }
                    else{
                        return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                    } 
                }
                else{
                    return array('success'=>false, 'error'=> 'app '.$package.' already exists');
                }
            }
            else{
                return array('success'=>false, 'error'=> 'params name or package or platform undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }

    function update($array){
        if($array != false){
            if(isset($array['app']) && $array['app'] != '' && isset($array['update']) && is_array($array['update'])){
                $package = $array['app'];
                $query = "SELECT `id` FROM `apps` WHERE `package` = '$package'";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $app_id = $row['id'];
                    }
                    
                    //параметры для обработки
                    $rewrite_params = ['status', 'activ', 'production', 'production_type', 'package'];
                    $query_data = "";
                    $success_params = true;
                    $error_success_params = '';
                    

                    //проверка и преобразование данных под стиль базы данных
                    foreach($array['update'] as $key=>$value){
                        if(is_int(array_search($key, $rewrite_params))){
                            if($key == 'status'){
                                switch ($value) {
                                    case 'develop':
                                        $array['update'][$key] = 0;
                                        break;
                                    case 'moderation':
                                        $array['update'][$key] = 1;
                                        break;
                                    case 'public':
                                        $array['update'][$key] = 2;
                                        break;
                                    case 'ban':
                                        $array['update'][$key] = 3;
                                        break;
                                    default:
                                        $success_params = false; $error_success_params = $error_success_params.' status must be develop or moderation or public or ban! ';
                                        break;
                                }
                            }
                            if($key == 'activ'){
                                switch ($value) {
                                    case false:
                                        $array['update'][$key] = 0;
                                        break;
                                    case true:
                                        $array['update'][$key] = 1;
                                        break;
                                    default:
                                        $success_params = false; $error_success_params = $error_success_params.' activ must be true or false! ';
                                        break;
                                }
                            }
                            if($key == 'production'){
                                switch ($value) {
                                    case false:
                                        $array['update'][$key] = 0;
                                        break;
                                    case true:
                                        $array['update'][$key] = 1;
                                        break;
                                    default:
                                        $success_params = false; $error_success_params = $error_success_params.' production must be true or false! ';
                                        break;
                                }
                            }
                            if($key == 'production_type'){
                                switch ($value) {
                                    case 'rent':
                                        $array['update'][$key] = 0;
                                        break;
                                    case 'sale':
                                        $array['update'][$key] = 1;
                                        break;
                                    case 'rent and sale':
                                        $array['update'][$key] = 2;
                                        break;
                                    default:
                                        $success_params = false; $error_success_params = $error_success_params.' production_type must be rent or sale! ';
                                        break;
                                }
                            }
                            if($key == 'package'){
                                if($package != $value){
                                    $new_package = $value;
                                    $query = "SELECT `id` FROM `apps` WHERE `package` = '$new_package'";
                                    $get = mysqli_query($this->$connection_db,$query); 
                                    if(mysqli_num_rows($get) != 0){
                                        $success_params = false; $error_success_params = $error_success_params.' '.$array['update'][$key].' app already exists! ';
                                    }
                                }
                            }
                        }
                    }
                    

                    //проверка параметра после преобразований и сбор пременной для запроса
                    if($success_params == true){
                        $i = 0;
                        foreach($array['update'] as $key=>$value){
                            if($i<count($array['update'])-1){
                                $query_data = $query_data." `$key`='$value', ";
                            }
                            else{
                                $query_data = $query_data." `$key`='$value' ";
                            }
                            $i++;
                        }

                        //генерация запроса и его выполнение
                        $query = "UPDATE `apps` SET ".$query_data." WHERE `id` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query); 
                        if($post){
                            if(isset($array['update']['package'])){
                                return array('success'=>true, 'app'=>$array['update']['package']);
                            }
                            else{
                                return array('success'=>true);
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'sql update error', 'error_num'=>6);
                        }
                    }
                    else{
                        return array('success'=>false, 'error'=> $error_success_params);
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'app '.$package.' undefined');
                }
            }
            else{
                return array('success'=>false, 'error'=> 'params app or update undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }


    function delete($array){
        if($array != false){
            if(isset($array['app']) && isset($array['secret_password'])){
                if($array['secret_password'] == 'asdwsx'){
                    $app_package = $array['app'];
                    $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                    $get = mysqli_query($this->$connection_db,$query); 
                    if(mysqli_num_rows($get) != 0){
                        while($row = mysqli_fetch_assoc($get)){
                            $app_id = $row['id'];
                        }

                        $query = "DELETE FROM `app_accounts` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query); 

                        $query = "DELETE FROM `app_events` WHERE `app_package` = '$app_package'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `app_icon` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `app_naming` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `app_organic` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `event_postback` WHERE `app` = '$app_package'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `installs_log` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `one_link_naming` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `one_link_organic` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `reserv_onelink_naming` WHERE `app` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);

                        $query = "DELETE FROM `apps` WHERE `id` = '$app_id'";
                        $post = mysqli_query($this->$connection_db,$query);
                        if($post){
                            return array('success'=>true);
                        }
                        else{
                            return array('success'=>false, 'error'=> 'sql error');
                        }
                    }
                    else{
                        return array('success'=>false, 'error'=> 'this app undefined');
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'param secret_password incorrect');
                }
                
            }
            else{
                return array('success'=>false, 'error'=> 'params app and secret_password undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }


    function namings($array){
        if($array != false){
            $array_evets = ['add', 'update', 'delete', 'get'];
            if(isset($array['event'])){
                if(is_int(array_search($array['event'], $array_evets))){
                    $event = $array_evets[array_search($array['event'], $array_evets)];
                    
                    //for event delete, add and update
                    if($event == 'delete' OR $event == 'add' OR $event == 'update'){
                        if(isset($array['naming']) && $array['naming'] != '' && isset($array['app_package']) && $array['app_package'] != ''){
                            $check_params = false;
                            $naming = $array['naming'];
                            $app_package = $array['app_package'];
                            if($event == 'add'){
                                if(isset($array['url'])){
                                    $url = $array['url'];
                                    $check_params = true;
                                }
                            }
                            if($event == 'update'){
                                if(isset($array['new_url'])){
                                    $url = $array['new_url'];
                                    $check_params = true;
                                }
                            }
                            if($event == 'delete'){
                                $check_params = true;
                            }
                            if($check_params == true){
                                $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                                $get = mysqli_query($this->$connection_db,$query); 
                                if(mysqli_num_rows($get) != 0){
                                    while($row = mysqli_fetch_assoc($get)){
                                        $app_id = $row['id'];
                                    }
                                    if($event == 'add'){
                                        $query = "SELECT `id` FROM `app_naming` WHERE `app` = '$app_id' AND `name` = '$naming'";
                                        $get = mysqli_query($this->$connection_db,$query); 
                                        if(mysqli_num_rows($get) == 0){
                                            if(isset($array['customer'])){
                                                $customer = $array['customer'];
                                                $query = "INSERT INTO app_naming (`app`, `name`, `link`, `customer`) VALUES ('$app_id', '$naming', '$url', '$customer')";
                                            }
                                            else{
                                                $query = "INSERT INTO app_naming (`app`, `name`, `link`) VALUES ('$app_id', '$naming', '$url')";
                                            }
                                            
                                            $post = mysqli_query($this->$connection_db,$query); 
                                            if($post){
                                                return array('success'=>true);
                                            }
                                            else{
                                                return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                            }
                                        }
                                        else{
                                            return array('success'=>false, 'error'=> 'this naming already exists in the app '.$app_package);
                                        }
                                    }
                                    if($event == 'update' OR $event == 'delete'){
                                        $query = "SELECT `id` FROM `app_naming` WHERE `app` = '$app_id' AND `name` = '$naming'";
                                        $get = mysqli_query($this->$connection_db,$query); 
                                        if(mysqli_num_rows($get) != 0){
                                            if($event == 'update'){
                                                $query = "UPDATE app_naming SET `link`='$url' WHERE `name`='$naming' AND `app` = '$app_id'";
                                            }
                                            if($event == 'delete'){
                                                $query = "DELETE FROM app_naming WHERE `name`='$naming' AND `app` = '$app_id'";
                                            }
                                            
                                            $post = mysqli_query($this->$connection_db,$query); 
                                            if($post){
                                                return array('success'=>true);
                                            }
                                            else{
                                                return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                            }
                                        }
                                        else{
                                            return array('success'=>false, 'error'=> 'this naming undefined in the app '.$app_package);
                                        }
                                    }
                                    
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'url in add or old_url or new_url in update param incorrect or undefined', 'error_num'=>7);
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'naming or app_package or url param incorrect or undefined', 'error_num'=>7);
                        }
                    }

                    //for get naming data
                    if($event == 'get'){
                        if(isset($array['app_package']) && $array['app_package'] != ''){
                            $app_package = $array['app_package'];
                            $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    $app_id = $row['id'];
                                }

                                //for is isset customer param
                                if(isset($array['customer'])){
                                    $customer = $array['customer'];
                                    $query = "SELECT `name` AS `naming`, `link` AS `url`, `customer` FROM `app_naming` WHERE `app` = '$app_id' AND `customer`='$customer'";
                                }
                                else{
                                    $query = "SELECT `name` AS `naming`, `link` AS `url`, `customer` FROM `app_naming` WHERE `app` = '$app_id'";
                                }

                                $get = mysqli_query($this->$connection_db,$query);
                                if($get){
                                    $answer_data = [];
                                    while($row = mysqli_fetch_assoc($get)){
                                        $answer_data[] = $row;
                                    }
                                    return array('success'=>true, 'data'=> $answer_data);
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'error database data', 'error_num'=>6);
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'app_package param incorrect or undefined', 'error_num'=>7);
                        }
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'event param incorrect', 'error_num'=>7);
                }
            }
            else{
                return array('success'=>false, 'error'=> 'event param undefined', 'error_num'=>7);
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }
    

    function naming_one_link($array){
        if($array != false){
            $events = ['get', 'update'];
            if(isset($array['event']) && isset($array['app_package']) && is_int(array_search($array['event'], $events))){
                $event = $array['event'];
                $app_package = $array['app_package'];

                $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $app_id = $row['id'];
                    }

                    if($event == 'get'){
                        $answer = null;
                        $query = "SELECT `activ`, `link`, `customer` FROM `one_link_naming` WHERE `app` = '$app_id'";
                        $get = mysqli_query($this->$connection_db,$query);
                        while($row = mysqli_fetch_assoc($get)){
                            switch ($row['activ']) {
                                case 0:
                                    $row['activ'] = false;
                                    break;
                                case 1:
                                    $row['activ'] = true;
                                    break;
                            }
                            $row['url'] = $row['link'];
                            unset($row['link']);
                            $answer = $row;
                        }
                        return array('success'=>true, 'data'=> $answer);
                    }
                    if($event == 'update'){
                        if(isset($array['app_package']) && $array['app_package'] != '' && isset($array['url']) && isset($array['activ'])){
                            $url = $array['url'];
                            if($array['activ'] == true OR $array['activ'] == false){
                                if($array['activ'] == true){
                                    $activ = 1;
                                }
                                else{
                                    $activ = 0;
                                }

                                if(isset($array['customer'])){
                                    $customer = $array['customer'];
                                    $query = "UPDATE `one_link_naming` SET `link`='$url', `activ`='$activ', `customer`='$customer' WHERE `app` = '$app_id'";
                                }
                                else{
                                    $query = "UPDATE `one_link_naming` SET `link`='$url', `activ`='$activ' WHERE `app` = '$app_id'";
                                }
                                
                                $post = mysqli_query($this->$connection_db,$query); 
                                if($post){
                                    return array('success'=>true);
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                }

                            }
                            else{
                                return array('success'=>false, 'error'=> 'param activ must be true or false');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'params url or app_package or activ undefined or incorrect');
                        }
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                }
                
            }
            else{
                return array('success'=>false, 'error'=> 'params event and app_package undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }


    function reserv_naming_one_link($array){
        if($array != false){
            $events = ['get', 'update'];
            if(isset($array['event']) && isset($array['app_package']) && is_int(array_search($array['event'], $events))){
                $event = $array['event'];
                $app_package = $array['app_package'];

                $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $app_id = $row['id'];
                    }

                    if($event == 'get'){
                        $answer = null;
                        $query = "SELECT `activ`, `link` FROM `reserv_onelink_naming` WHERE `app` = '$app_id'";
                        $get = mysqli_query($this->$connection_db,$query);
                        while($row = mysqli_fetch_assoc($get)){
                            switch ($row['activ']) {
                                case 0:
                                    $row['activ'] = false;
                                    break;
                                case 1:
                                    $row['activ'] = true;
                                    break;
                            }
                            $row['url'] = $row['link'];
                            unset($row['link']);
                            $answer = $row;
                        }
                        return array('success'=>true, 'data'=> $answer);
                    }
                    if($event == 'update'){
                        if(isset($array['app_package']) && $array['app_package'] != '' && isset($array['url']) && isset($array['activ'])){
                            $url = $array['url'];
                            if($array['activ'] == true OR $array['activ'] == false){
                                if($array['activ'] == true){
                                    $activ = 1;
                                }
                                else{
                                    $activ = 0;
                                }

                                $query = "SELECT `id` FROM `reserv_onelink_naming` WHERE `app` = '$app_id'";
                                $get = mysqli_query($this->$connection_db,$query);
                                if(mysqli_num_rows($get) != 0){
                                    $query = "UPDATE `reserv_onelink_naming` SET `link`='$url', `activ`='$activ' WHERE `app` = '$app_id'";
                                    $post = mysqli_query($this->$connection_db,$query); 
                                    if($post){
                                        return array('success'=>true);
                                    }
                                    else{
                                        return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                    }
                                }
                                else{
                                    $query = "INSERT INTO  `reserv_onelink_naming` (`app`, `activ`, `link`) VALUES ('$app_id', '$activ', '$url')";
                                    $post = mysqli_query($this->$connection_db,$query); 
                                    if($post){
                                        return array('success'=>true);
                                    }
                                    else{
                                        return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                    }
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'param activ must be true or false');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'params url or app_package or activ undefined or incorrect');
                        }
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                }
                
            }
            else{
                return array('success'=>false, 'error'=> 'params event and app_package undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }


    function organic_one_link($array){
        if($array != false){
            $events = ['get', 'update'];
            if(isset($array['event']) && isset($array['app_package']) && is_int(array_search($array['event'], $events))){
                $event = $array['event'];
                $app_package = $array['app_package'];

                $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                $get = mysqli_query($this->$connection_db,$query); 
                if(mysqli_num_rows($get) != 0){
                    while($row = mysqli_fetch_assoc($get)){
                        $app_id = $row['id'];
                    }

                    if($event == 'get'){
                        $answer = null;
                        $query = "SELECT `activ`, `link`, `customer` FROM `one_link_organic` WHERE `app` = '$app_id'";
                        $get = mysqli_query($this->$connection_db,$query);
                        while($row = mysqli_fetch_assoc($get)){
                            switch ($row['activ']) {
                                case 0:
                                    $row['activ'] = false;
                                    break;
                                case 1:
                                    $row['activ'] = true;
                                    break;
                            }
                            $row['url'] = $row['link'];
                            unset($row['link']);
                            $answer = $row;
                        }
                        return array('success'=>true, 'data'=> $answer);
                    }
                    if($event == 'update'){
                        if(isset($array['app_package']) && $array['app_package'] != '' && isset($array['url']) && isset($array['activ'])){
                            $url = $array['url'];
                            if($array['activ'] == true OR $array['activ'] == false){
                                if($array['activ'] == true){
                                    $activ = 1;
                                }
                                else{
                                    $activ = 0;
                                }

                                if(isset($array['customer'])){
                                    $customer = $array['customer'];
                                    $query = "UPDATE `one_link_organic` SET `link`='$url', `activ`='$activ', `customer`='$customer' WHERE `app` = '$app_id'";
                                }
                                else{
                                    $query = "UPDATE `one_link_organic` SET `link`='$url', `activ`='$activ' WHERE `app` = '$app_id'";
                                }
                                
                                $post = mysqli_query($this->$connection_db,$query); 
                                if($post){
                                    return array('success'=>true);
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                }

                            }
                            else{
                                return array('success'=>false, 'error'=> 'param activ must be true or false');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'params url or app_package or activ undefined or incorrect');
                        }
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                }
                
            }
            else{
                return array('success'=>false, 'error'=> 'params event and app_package undefined or incorrect');
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }


    function organics($array){
        if($array != false){
            $array_evets = ['add', 'update', 'delete', 'get'];
            if(isset($array['event'])){
                if(is_int(array_search($array['event'], $array_evets))){
                    $event = $array_evets[array_search($array['event'], $array_evets)];

                    //for event delete, add and update
                    if($event == 'delete' OR $event == 'add' OR $event == 'update'){
                        if(isset($array['geo']) && $array['geo'] != '' && isset($array['app_package']) && $array['app_package'] != ''){
                            $check_params = false;
                            $geo = $array['geo'];
                            $app_package = $array['app_package'];
                            if($event == 'add'){
                                if(isset($array['url'])){
                                    $url = $array['url'];
                                    $check_params = true;
                                }
                            }
                            if($event == 'update'){
                                if(isset($array['new_url'])){
                                    $url = $array['new_url'];
                                    $check_params = true;
                                }
                            }
                            if($event == 'delete'){
                                $check_params = true;
                            }
                            if($check_params == true){
                                //check app and get app_id
                                $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                                $get = mysqli_query($this->$connection_db,$query); 
                                if(mysqli_num_rows($get) != 0){
                                    while($row = mysqli_fetch_assoc($get)){
                                        $app_id = $row['id'];
                                    }
                                    //check geo and get geo_id
                                    $query = "SELECT `id` FROM `list_geo` WHERE `c_code` = '$geo'";
                                    $get = mysqli_query($this->$connection_db,$query); 
                                    if(mysqli_num_rows($get) != 0){
                                        while($row = mysqli_fetch_assoc($get)){
                                            $geo_id = $row['id'];
                                        }

                                        if($event == 'add'){
                                            $query = "SELECT `id` FROM `app_organic` WHERE `app` = '$app_id' AND `geo` = '$geo_id'";
                                            $get = mysqli_query($this->$connection_db,$query); 
                                            if(mysqli_num_rows($get) == 0){
                                                if(isset($array['customer'])){
                                                    $customer = $array['customer'];
                                                    $query = "INSERT INTO app_organic (`app`, `geo`, `link`, `customer`) VALUES ('$app_id', '$geo_id', '$url', '$customer')";
                                                }
                                                else{
                                                    $query = "INSERT INTO app_organic (`app`, `geo`, `link`) VALUES ('$app_id', '$geo_id', '$url')";
                                                }
                                                $post = mysqli_query($this->$connection_db,$query); 
                                                if($post){
                                                    return array('success'=>true);
                                                }
                                                else{
                                                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                                }
                                            }
                                            else{
                                                return array('success'=>false, 'error'=> 'this geo already exists in the app '.$app_package);
                                            }
                                        }

                                        if($event == 'update' OR $event == 'delete'){
                                            $query = "SELECT `id` FROM `app_organic` WHERE `app` = '$app_id' AND `geo` = '$geo_id'";
                                            $get = mysqli_query($this->$connection_db,$query); 
                                            if(mysqli_num_rows($get) != 0){
                                                if($event == 'update'){
                                                    $query = "UPDATE app_organic SET `link`='$url' WHERE `geo`='$geo_id' AND `app` = '$app_id'";
                                                }
                                                if($event == 'delete'){
                                                    $query = "DELETE FROM app_organic WHERE `geo`='$geo_id' AND `app` = '$app_id'";
                                                }
                                                
                                                $post = mysqli_query($this->$connection_db,$query); 
                                                if($post){
                                                    return array('success'=>true);
                                                }
                                                else{
                                                    return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                                }
                                            }
                                            else{
                                                return array('success'=>false, 'error'=> 'this geo undefined in the app '.$app_package);
                                            }
                                        }
                                    }
                                    else{
                                        return array('success'=>false, 'error'=> 'this geo undefined');
                                    }
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'url in add or old_url or new_url in update param incorrect or undefined', 'error_num'=>7);
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'geo or app_package or url param incorrect or undefined', 'error_num'=>7);
                        }
                    }
                    
                    //for event get
                    if($event == 'get'){
                        if(isset($array['app_package']) && $array['app_package'] != ''){
                            $app_package = $array['app_package'];
                            $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    $app_id = $row['id'];
                                }

                                //for is isset customer param
                                if(isset($array['customer'])){
                                    $customer = $array['customer'];
                                    $query = "SELECT list_geo.name AS geo_name, `link` AS `url`, list_geo.c_code AS `geo`, `customer` FROM `app_organic` INNER JOIN list_geo ON app_organic.geo = list_geo.id WHERE `app` = '$app_id' AND `customer`='$customer'";
                                }
                                else{
                                    $query = "SELECT list_geo.name AS geo_name, `link` AS `url`, list_geo.c_code AS `geo`, `customer` FROM `app_organic` INNER JOIN list_geo ON app_organic.geo = list_geo.id WHERE `app` = '$app_id'";
                                }
                                
                                $get = mysqli_query($this->$connection_db,$query);
                                if($get){
                                    $answer_data = [];
                                    while($row = mysqli_fetch_assoc($get)){
                                        $row['img'] = get_protocol().$_SERVER['SERVER_NAME']."/uploads/geo/".str_ireplace(' ', '-', $row['geo_name']).".png";
                                        $answer_data[] = $row;
                                    }
                                    return array('success'=>true, 'data'=> $answer_data);
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'error database data', 'error_num'=>6);
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'app_package param incorrect or undefined', 'error_num'=>7);
                        }
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'event param incorrect', 'error_num'=>7);
                }
            }
            else{
                return array('success'=>false, 'error'=> 'event param undefined', 'error_num'=>7);
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }


    function accounts($array){
        if($array != false){
            $array_evets = ['update', 'get'];
            if(isset($array['event'])){
                if(is_int(array_search($array['event'], $array_evets))){
                    $event = $array_evets[array_search($array['event'], $array_evets)];

                    //for event update
                    if($event == 'update'){
                        if(isset($array['app_package']) && $array['app_package'] != ''){
                            $app_package = $array['app_package'];
                            $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    $app_id = $row['id'];
                                }

                                //check update or insert 
                                $query = "SELECT `id` FROM `app_accounts` WHERE `app` = '$app_id'";
                                $get = mysqli_query($this->$connection_db,$query); 
                                if(mysqli_num_rows($get) != 0){
                                    //generate sql query
                                    $sql_update = "";
                                    $num = 0;
                                    foreach($array as $key=>$value){
                                        if($key != 'event' && $key != 'app_package'){
                                            if($num < count($array)-3){
                                                if($key == 'octo_profile_id'){
                                                    $sql_update = $sql_update." `multilogin_profile_id`='$value', ";
                                                }
                                                else{
                                                    $sql_update = $sql_update." `".$key."`='$value', ";
                                                }
                                            }
                                            else{
                                                if($key == 'octo_profile_id'){
                                                    $sql_update = $sql_update." `multilogin_profile_id`='$value' ";
                                                }
                                                else{
                                                    $sql_update = $sql_update." `".$key."`='$value' ";
                                                }
                                            }
                                            $num++;
                                        }
                                    }

                                    $query = "UPDATE app_accounts SET ".$sql_update." WHERE `app` = '$app_id'";
                                    $post = mysqli_query($this->$connection_db,$query); 
                                    if($post){
                                        return array('success'=>true);
                                    }
                                    else{
                                        return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                    }
                                }
                                else{
                                    $sql_insert_values = " `app`, ";
                                    $sql_insert_data = " '".$app_id."', ";
                                    $num = 0;
                                    foreach($array as $key=>$value){
                                        if($key != 'event' && $key != 'app_package'){
                                            if($num < count($array)-3){
                                                if($key == 'octo_profile_id'){
                                                    $sql_insert_values = $sql_insert_values." `multilogin_profile_id`, ";
                                                    $sql_insert_data = $sql_insert_data." '$value', ";
                                                }
                                                else{
                                                    $sql_insert_values = $sql_insert_values." `".$key."`, ";
                                                    $sql_insert_data = $sql_insert_data." '$value', ";
                                                }
                                            }
                                            else{
                                                if($key == 'octo_profile_id'){
                                                    $sql_insert_values = $sql_insert_values." `multilogin_profile_id` ";
                                                    $sql_insert_data = $sql_insert_data." '$value' ";
                                                }
                                                else{
                                                    $sql_insert_values = $sql_insert_values." `".$key."` ";
                                                    $sql_insert_data = $sql_insert_data." '$value' ";
                                                }
                                            }
                                            $num++;
                                        }
                                    }

                                    $query = "INSERT INTO app_accounts (".$sql_insert_values.") VALUES (".$sql_insert_data.")";
                                    $post = mysqli_query($this->$connection_db,$query); 
                                    if($post){
                                        return array('success'=>true);
                                    }
                                    else{
                                        return array('success'=>false, 'error'=> 'error insert data', 'error_num'=>6);
                                    }
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'geo or app_package or url param incorrect or undefined', 'error_num'=>7);
                        }
                    }
                    
                    //for event get
                    if($event == 'get'){
                        if(isset($array['app_package']) && $array['app_package'] != ''){
                            $app_package = $array['app_package'];
                            $query = "SELECT `id` FROM `apps` WHERE `package` = '$app_package'";
                            $get = mysqli_query($this->$connection_db,$query); 
                            if(mysqli_num_rows($get) != 0){
                                while($row = mysqli_fetch_assoc($get)){
                                    $app_id = $row['id'];
                                }

                                $query = "SELECT * FROM `app_accounts` WHERE `app` = '$app_id'";
                                $get = mysqli_query($this->$connection_db,$query);
                                if($get){
                                    $answer_data = null;
                                    while($row = mysqli_fetch_assoc($get)){
                                        $row['octo_profile_id'] = $row['multilogin_profile_id'];
                                        unset($row['id']);
                                        unset($row['app']);
                                        unset($row['multilogin_profile_id']);
                                        $answer_data=$row;
                                    }
                                    return array('success'=>true, 'data'=> $answer_data);
                                }
                                else{
                                    return array('success'=>false, 'error'=> 'error database data', 'error_num'=>6);
                                }
                            }
                            else{
                                return array('success'=>false, 'error'=> 'app '.$app_package.' undefined');
                            }
                        }
                        else{
                            return array('success'=>false, 'error'=> 'app_package param incorrect or undefined', 'error_num'=>7);
                        }
                    }
                }
                else{
                    return array('success'=>false, 'error'=> 'event param incorrect', 'error_num'=>7);
                }
            }
            else{
                return array('success'=>false, 'error'=> 'event param undefined', 'error_num'=>7);
            }
        }
        else{
            return array('success'=>false, 'error'=> 'post json data undefined', 'error_num'=>7);
        }
    }
}


// array for get apps data
// {
//     'select':['name', 'package', 'status', 'platform', 'comment', 'host', 'appsflyer_key', 'developer', 'activ', 'create_time', 'public_time', 'ban_time', 'onesignal_app_id', 'onesignal_api_key', 'production', 'production_type', 'count_installs', 'icon'],
//     'where':[{'package':['com.exemple']}, {'status':['develop', 'moderation', 'public', 'ban']}, {'active':true}, {'team':'team2'}, {'platform':['android', 'ios']}, {'production':true}, {'production_type':['sale', 'rent']}],
//     'limit':1
// }

?>