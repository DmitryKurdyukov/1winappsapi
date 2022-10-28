<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";

$query = "SELECT `id`, `count_installs` FROM `apps` WHERE `sync_installs` = '0' LIMIT 1";
$get = mysqli_query($connection_db, $query);
if(mysqli_num_rows($get) != 0){
    while($row = mysqli_fetch_assoc($get)){
        $app_id = $row['id'];
        $app_count_installs = $row['count_installs'];
        
        $query_2 = "SELECT `id` FROM `installs_log` WHERE `app` = '$app_id'";
        $get_2 = mysqli_query($connection_db, $query_2);
        $count_logs_app_count_installs = mysqli_num_rows($get_2);

        $query = "UPDATE `apps` SET `count_installs`='$count_logs_app_count_installs', `sync_installs`='1' WHERE `id` = '$app_id'";
        $post = mysqli_query($connection_db, $query);
        if($post){
            echo 'sync true';
        }
        else{
            echo 'error sync (sql error)';
        }
        // if($count_logs_app_count_installs != $app_count_installs){
        //     $query = "UPDATE `apps` SET `count_installs`='$count_logs_app_count_installs', `sync_installs`='1' WHERE `id` = '$app_id'";
        //     $post = mysqli_query($connection_db, $query);
        //     if($post){
        //         echo 'sync true';
        //     }
        //     else{
        //         echo 'error sync (sql error)';
        //     }
        // }
        // else{
        //     echo 'sync dont need';
        // }
    }
}
else{
    echo 'sync dont need';
    // $query = "UPDATE `apps` SET `sync_installs`='0'";
    // $post = mysqli_query($connection_db, $query);
    // echo 'reset sync param';
}
                               
?>