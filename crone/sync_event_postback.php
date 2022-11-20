<?php 
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/database.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/engine/functions.php";

corsAllow();

$query = "SELECT `id`, `hash` FROM `event_postback` WHERE `geo` = 0 LIMIT 1";
$get = mysqli_query($connection_db, $query);
if(mysqli_num_rows($get) != 0){
    while($row = mysqli_fetch_assoc($get)){
        $event_id = $row['id'];
        $hash = $row['hash'];

        $query = "SELECT `geo` FROM `installs_log` WHERE `device_id` = '$hash' LIMIT 1";
        $get = mysqli_query($connection_db, $query);
        if(mysqli_num_rows($get) != 0){
            while($row = mysqli_fetch_assoc($get)){
                $geo = $row['geo'];
                if($geo == 0){
                    $geo = 1;
                }

                $query = "UPDATE `event_postback` SET `geo`= '$geo' WHERE `hash` = '$hash'";
                $post = mysqli_query($connection_db, $query);
                if($post){
                    echo json_encode(array('success'=>true, 'info'=>'set geo success', 'postback_id'=>$event_id));
                }
                else{
                    echo json_encode(array('success'=>false, 'info'=>'sql error set geo in success', 'postback_id'=>$event_id));
                }
            }
        }
        else{
            $query = "UPDATE `event_postback` SET `geo`= 1 WHERE `hash` = '$hash'";
            $post = mysqli_query($connection_db, $query);
            if($post){
                echo json_encode(array('success'=>true, 'info'=>'set geo in 1', 'postback_id'=>$event_id));
            }
            else{
                echo json_encode(array('success'=>false, 'info'=>'sql error set geo in 1', 'postback_id'=>$event_id));
            }
        }
    }
}
else{
    echo json_encode(array('success'=>true, 'info'=>'sync dont need'));
}
                               
?>