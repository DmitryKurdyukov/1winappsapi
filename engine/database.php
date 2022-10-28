<?php

$db['db_host'] = "localhost";
$db['db_user'] = "root";
$db['db_pass'] = "";
$db['db_name'] = "1winapp";
foreach($db as $key => $value){ 
    
    define(strtoupper($key), $value);
}

$connection_db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
mysqli_query( $connection_db, "SET NAMES utf8");


function connection_db(){
    $db['db_host'] = "localhost";
    $db['db_user'] = "root";
    $db['db_pass'] = "";
    $db['db_name'] = "1winapp";
    foreach($db as $key => $value){ 
        
        define(strtoupper($key), $value);
    }

    $connection_db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    return $connection_db;
}

// $db['db_host'] = "localhost";
// $db['db_user'] = "p602939";
// $db['db_pass'] = "qknKTScRAwvaQn5kK85H";
// $db['db_name'] = "p602939_1winapps";

// $db['db_host'] = "localhost";
// $db['db_user'] = "root";
// $db['db_pass'] = "";
// $db['db_name'] = "1winapp";