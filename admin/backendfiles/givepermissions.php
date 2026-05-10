<?php
session_start();
include('..\config/dbcon.php');
include('..\functions.php');

if(isset($_POST['give-permits-btn'])){
    $cleanRole = CleanString($con, $_POST['role']);
    $cleanResource = CleanString($con, $_POST['resource']);
    $cleanPerm = CleanString($con, $_POST['permission']);

    CheckEmptyStrings($cleanPerm, 'Permission', '..\userpermissions.php');
    
    CheckforTwoDuplicates($con, 'role_as', 'resources', 'permissions', $cleanRole, $cleanResource, '..\userpermissions.php');

    $insertPermission = "INSERT INTO permissions(role_as, resources, permission) VALUES(?, ?, ?)";
    $insertRes = mysqli_execute_query($con, $insertPermission, [$cleanRole, $cleanResource, $cleanPerm]);


    if(!$insertRes){
        $_SESSION['message'] = 'Something Went Wrong';
        header("Location: ..\userpermissions.php");
        exit(0);
    }else{
        $_SESSION['message'] = 'Successfully Added Permission';
        header("Location: ..\userpermissions.php");
        exit(0);
    }
    
}
?>