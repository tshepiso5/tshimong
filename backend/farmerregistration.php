<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['register-farmer-btn'])){
    $cleanName = CleanString($con, $_POST['fname']);
    $cleanAge = CleanString($con, $_POST['age']);
    $cleanGender = CleanString($con, $_POST['gender']);
    $cleanphone = CleanString($con, $_POST['phone']);
    $cleanRole = CleanString($con, $_POST['role']);

    CheckEmptyStrings($cleanName, 'Full Name', '../farmersregister.php');
    CheckEmptyStrings($cleanAge, 'Age', '../farmersregister.php');
    CheckEmptyStrings($cleanGender, 'Gender', '../farmersregister.php');
    CheckEmptyStrings($cleanphone, 'Phone', '../farmersregister.php');


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

        mysqli_begin_transaction($con);

        try{

            $verifiedNum = 1;
            $initialTrans = 0;
            $storeFarmer = "INSERT INTO farmers(full_name, age,	gender, phone_number, is_verified, role_as) VALUES(?, ?, ?, ?, ?, ?)";
            $farmerRes = mysqli_execute_query($con, $storeFarmer, [$cleanName, $cleanAge, $cleanGender, $cleanphone, $verifiedNum, $cleanRole]);

            $newID = mysqli_insert_id($con);
        

            $startWallet = "INSERT INTO wallets(user_id,user_phone, wallet_transaction) VALUES(?, ?, ?)";
            $walletRes = mysqli_execute_query($con, $startWallet, [$newID, $cleanphone, $initialTrans]);

            mysqli_commit($con);

            $_SESSION['message'] = "Registration Successful! Network location mapped.";
            header("Location: ../farmerslogin.php");
            exit(0);
        }catch(Exception $e){
            mysqli_rollback($con);

            $_SESSION['message'] = "Something Went Wrong";
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