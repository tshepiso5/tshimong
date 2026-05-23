<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['customer-register-btn'])){
    $userType=intval($_POST['user-type']);
    $name = CleanString($con, $_POST['full-name']);
    $address = CleanString($con, $_POST['res-address']);
    $age = intval($_POST['age']);
    $gender = CleanString($con, $_POST['gender']);
    $phone = CleanString($con, $_POST['phone']);
    $privacy= CleanString($con, $_POST['privacy-policy-status']) == true ? 1:0; 

    CheckEmptyStrings($name, 'Full Name', '../customersregister.php');
    CheckEmptyStrings($address, 'Residential Address', '../customersregister.php');
    CheckEmptyStrings($age, 'Age', '../customersregister.php');
    CheckEmptyStrings($gender, 'Gender', '../customersregister.php');
    CheckEmptyStrings($phone, 'Phone', '../customersregister.php');

    if($privacy == 0){
        $_SESSION['message'] = "Please Agree to Privacy Policy";
        header("Location: ../customersregister.php");
        exit(0);
    }
    // Call the Python Multi-API Bridge
    $python = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $scriptPath = realpath(__DIR__ . '/../customerregister.py');

    $cmd = "\"$python\" \"$scriptPath\" " . escapeshellarg($phone) . " 2>&1";
    $output = shell_exec($cmd);
    $res = json_decode($output, true);

    if($res && isset($res['result']) && $res['result'] === 'success' && $res['verified'] === true){
        $lat = $res['latitude'];
        $lon = $res['longitude'];

        $verifiedCustomer = 1;

        mysqli_begin_transaction($con);

        try{
            $initialTrans = 0;
            
            $insertQuery = "INSERT INTO buyers (full_name, home_address, latitude, longitude, age, gender, phone_number, roleid, verified_status, privacy_policy_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtResult = mysqli_execute_query($con, $insertQuery, [$name, $address, $lat, $lon, $age, $gender, $phone, $userType, $verifiedCustomer, $privacy]);

            

            $startWallet = "INSERT INTO wallets(user_phone, wallet_transaction) VALUES(?, ?)";
            $walletRes = mysqli_execute_query($con, $startWallet, [$phone, $initialTrans]);

            mysqli_commit($con);

            $_SESSION['message'] = "Registration Successful! Network location mapped.";
            header("Location: ../customerslogin.php");
            exit(0);

        }catch(Exception $e){
            mysqli_rollback($con);

            $_SESSION['message'] = "Something Went Wrong.";
            header("Location: ../customersregister.php");
            exit(0);

        }
        
       

    }else{
        $reason = isset($res['message']) ? $res['message'] : "Network verification failed.";
        $_SESSION['message'] = "Registration Blocked: " . $reason;
        header("Location: ../customersregister.php");
        exit(0);
    }
}



?>