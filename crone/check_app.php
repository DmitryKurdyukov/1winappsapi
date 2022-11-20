<?php 
include $_SERVER['DOCUMENT_ROOT'].'/engine/database.php';


class gp_app{
    function get_link($package){
        return 'https://play.google.com/store/apps/details?id='.$package;
    }

    function get_icon($html){
        $gp_icon_start = strpos($html, 'class="Mqg6jb Mhrnjf"');
        $gp_icon_finish = strpos($html, 'srcset', $gp_icon_start);
        return substr($html, $gp_icon_start + 32, $gp_icon_finish - $gp_icon_start - 34);
    } 

    function get_apple_icon($html){
        $gp_icon_start = strpos($html, '<source class="we-artwork__source" srcset="');
        $gp_icon_finish = strpos($html, '" media=', $gp_icon_start);
        return substr($html, $gp_icon_start, $gp_icon_finish - $gp_icon_start);
    } 
}


//curl for transfer user
function transfer_user($package){
    $headers = [
        'Content-Type: application/json',
        'User-Agent: '.$_SERVER['HTTP_USER_AGENT'],
        'Auth-Token: HIqOO9MSVIjG1sRhos9U'
    ];

    $j_data = array (
        'app' => $package,
    );
    
    // api.1-w.app
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'https://api.1-w.app/api/transfer_users/appsflyer_transfer/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($j_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
} 


$gp_app = new gp_app;
// вывод списка приложений которые находятя намодерации или опубликованы
$query = "SELECT * FROM apps WHERE  `status` = '1' OR `status` = '2'";
$get = mysqli_query($connection_db,$query); 
while($row = mysqli_fetch_assoc($get)){
    $id = $row['id'];
    //for android
    if($row['package'] != '' && $row['platform'] == 'android'){
        if($row['team'] == 'team2'){
            $row['package'] = base64_decode($row['package']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://play.google.com/store/apps/details?id='.$row['package']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        $gp = strpos($result, 'sorry, the requested URL was not found on this server');
        if($gp == false){
            if($row['status'] == '1'){
                $public_time = date('Y-m-d H:i:s');
                $query = "UPDATE apps SET `status` = '2', `public_time`='$public_time' WHERE `id` = '$id'";
                $post = mysqli_query($connection_db,$query);
    
                $icon = $gp_app->get_icon($result);
                $query = "UPDATE app_icon  SET icon = '$icon' WHERE app = '$id'";
                $post = mysqli_query($connection_db,$query);

                transfer_user();
            }
        }
        else{
            if($row['status'] == '2'){
                $ban_time = date('Y-m-d H:i:s');
                $query = "UPDATE apps SET `status` = '3', `ban_time`='$ban_time' WHERE `id` = '$id'";
                $post = mysqli_query($connection_db,$query);

                $query = "UPDATE app_icon  SET icon = '' WHERE app = '$id'";
                $post = mysqli_query($connection_db,$query);
            }
        }
    }
    
    //for ios
    if($row['package'] != '' && $row['platform'] == 'ios'){
        if($row['team'] == 'team2'){
            $row['package'] = base64_decode($row['package']);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://apps.apple.com/ru/app/id'.$row['package']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        
        if($result != ''){
            $query = "UPDATE apps SET `status` = '2' WHERE `id` = '$id'";
            $post = mysqli_query($connection_db,$query);

            $icon = $gp_app->get_apple_icon($result);
            $query = "UPDATE app_icon  SET icon = '$icon' WHERE app = '$id'";
            $post = mysqli_query($connection_db,$query);
            
        }
        else{
            if($row['status'] == '2'){
                $query = "UPDATE apps SET `status` = '3' WHERE `id` = '$id'";
                $post = mysqli_query($connection_db,$query);
            }
        }
    }
}

echo 'success';
?>