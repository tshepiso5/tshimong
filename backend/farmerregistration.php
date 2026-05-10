<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['register-farmer-btn'])){
    $cleanName = CleanString($con, $_POST['fname']);
    $cleanAge = CleanString($con, $_POST['age']);
    $cleanGender = CleanString($con, $_POST['gender']);
    $cleanphone = CleanString($con, $_POST['phone']);


    if(empty($cleanphone)) {
        die("Error: Phone number field was empty.");
    }

    //number verification bridge
    $script_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'verifynumber.py';
    $python_path = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $cmd = "\"$python_path\" \"$script_path\" " . escapeshellarg($cleanphone) . " 2>&1";
    $output = shell_exec($cmd);
    
    $result = json_decode($output, true);

    if($result && $result['status'] === 'success' && $result['verified'] === true){
        $verifiedNum = 1;
        $storeFarmer = "INSERT INTO farmers(full_name, age,	gender, phone_number, is_verified) VALUES(?, ?, ?, ?, ?)";
        $farmerRes = mysqli_execute_query($con, $storeFarmer, [$cleanName, $cleanAge, $cleanGender, $cleanphone, $verifiedNum]);

        if(!$farmerRes){
            $_SESSION['message'] = "Something Went Wrong";
            header("Location: ../farmersregister.php");
            exit(0);
        }else{
            $_SESSION['message'] = "Successfully Registered Farmer's Profile. Please Login";
            header("Location: ../farmersregister.php");
            exit(0);

        }
    }else{
        $_SESSION['message'] = "Identity verification failed. Please ensure your SIM is active.";
        header("Location: ../farmersregister.php");
        exit(0);
    }
}

?>