<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['login-btn'])){
    $cleanNumber = CleanString($con, $_POST['phone']);

    CheckEmptyStrings($cleanNumber, 'Phone Number', 'farmerslogin.php');

    $checkFarmer = "SELECT user_id, full_name, phone_number, is_verified, role_as FROM farmers WHERE phone_number = ?";
    $farmerRes = mysqli_execute_query($con, $checkFarmer, [$cleanNumber]);

    if(mysqli_num_rows($farmerRes) > 0){
        $farmerData = mysqli_fetch_assoc($farmerRes);

        $script_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'verifynumber.py';
        $python_path = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
        $cmd = "\"$python_path\" \"$script_path\" " . escapeshellarg($cleanNumber) . " 2>&1";

        $output = shell_exec($cmd);
        $res = json_decode($output, true);

        if($res && $res['status'] === 'success' && $res['verified'] === true){
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'id' => $farmerData['user_id'],
                'name' => $farmerData['full_name'],
                'phone' => $farmerData['phone_number'], 
                'role' => $farmerData['role_as'],
            ];

            $_SESSION['message'] = "Welcome, " . $farmerData['full_name'];
            header("Location: ../farmersprofile.php"); // Redirect to the farmers profile
            exit(0);
        }else{
            $_SESSION['message'] = "Verification failed. Please use the device with your registered SIM.";
            header("Location: ../login.php");
            exit(0);
        }

    }else{
        $_SESSION['message'] = "Phone number not recognized. Please register first.";
        header("Location: ../farmersregister.php");
        exit(0);
    }

}



?>