<?php
session_start();
include('..\config/dbcon.php');
include('..\functions.php');


if(isset($_POST['add-resource-btn'])){

    $resourcemap = [
        'Home Page' => 'Index',
        'Dashboard' => 'Dashboard', 
        'About' => 'About Us',
        'Farmers Login' => 'Farmers Login', 
        'Farmers Register' => 'Farmers Register',
        'Customer Login' => 'Customers Login',
        'Customer Register' => 'Customers Register', 
        'Farmer News Feed' => 'Farmers Feed',
        'Farmer Wallet' => 'Farmers Wallet',
        'Farmer Office' => 'Farmers Office',
        'Farmer Work-log' => 'Farmers Log',
        'Customer Feed' => 'Customer Feed', 
        'Customer Wallet' => 'Customer Wallet',
        'Customer Waste' => 'Customer Waste',
        'Customer Profile' => 'Customer Profile',
        'Customer Orders' => 'Orders',

    ];

    $inputResource = $_POST['resource'] ?? '';
    if(!array_key_exists($inputResource, $resourcemap)){
        $_SESSION['message'] = 'Invalid Resource Submitted';
        header("Location: ..\availableresources.php");
        exit(0);
    }

    $cleanResource = $resourcemap[$inputResource];

    CheckEmptyStrings($cleanResource, 'Resource', 'availableresources.php');

    CheckforOneDuplicate($con, 'resource_name', 'avail_resources', $cleanResource, '..\availableresources.php');

    $insertResource = "INSERT INTO avail_resources(resource_name) VALUES(?)";
    $resourceRes = mysqli_execute_query($con, $insertResource, [$cleanResource]);

    if(!$resourceRes){
        $_SESSION['message'] = 'Something Went Wrong';
        header("Location: ..\availableresources.php");
        exit(0);
    }else{
        $_SESSION['message'] = 'Successfully Submitted Resource';
        header("Location: ..\availableresources.php");
        exit(0);
    }



}


?>