<?php
session_start();
include('..\config/dbcon.php');
include('..\functions.php');

if(isset($_POST['add-user-btn'])){

    $rolemap = [
        'Super Admin' => 'Super Admin',
        'Admin' => 'Admin', 
        'Farmer' => 'Farmer', 
        'Buyer' => 'Customer',
        'Customer' => 'Customer',
        'Funder' => 'funder',
        'Investor' => 'funder',

    ];

    $userInput = $_POST['role'] ?? '';
    if(!array_key_exists($userInput, $rolemap)){
        $_SESSION['message'] = 'Invalid Role Submitted';
        header("Location: ..\permittedusers.php");
        exit(0);
    }
   
    $cleanRole = $rolemap[$userInput];
    

    CheckEmptyStrings($cleanRole, 'Role', '..\permittedusers.php');

    if(ContainsNumbers($cleanRole)){
        $_SESSION['message'] = 'User Cannot Contain Numbers';
        header("Location: ..\permittedusers.php");
        exit(0);
    }

    CheckforOneDuplicate($con, 'role_as', 'users', $cleanRole, '..\permittedusers.php');

    $insertRole = "INSERT INTO users(role_as) VALUES(?)";
    $insertRes = mysqli_execute_query($con, $insertRole, [$cleanRole]);

    if(!$insertRes){
        $_SESSION['message'] = 'Something Went Wrong';
        header("Location: ..\permittedusers.php");
        exit(0);
    }else{
        $_SESSION['message'] = 'Successfully Entered User Role!';
        header("Location: ..\permittedusers.php");
        exit(0);
    }

    


}


?>