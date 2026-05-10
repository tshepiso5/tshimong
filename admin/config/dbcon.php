<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "dirty_farm_db";

try{
    $con = mysqli_connect($host, $username, $password, $database);

    if(!$con){
        throw new Exception("DB Connection Failed!");
    }



}catch(Exception $e){
    header('location: ..\errors/dberrors.php');
    exit(0);
}


?>