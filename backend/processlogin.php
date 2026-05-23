<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');


if(isset($_POST['customer-login-btn'])){
    $cleanNumber = CleanString($con, $_POST['customer-phone']);

    CheckEmptyStrings($cleanNumber, 'Phone Number', '../customerslogin.php');

    $checkUserQuery = "SELECT buyer_id, full_name, phone_number, roleid FROM buyers WHERE phone_number = ? LIMIT 1";
    $userResult = mysqli_execute_query($con, $checkUserQuery, [$cleanNumber]);

    if(mysqli_num_rows($userResult) > 0){
        $userData = mysqli_fetch_assoc($userResult);

        $python = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
        $scriptPath = realpath(__DIR__ . '/../customerlogin.py');

        $cmd = "\"$python\" \"$scriptPath\" " . escapeshellarg($cleanNumber) . " 2>&1";
        $output = shell_exec($cmd);
        $res = json_decode($output, true);

        if($res && isset($res['result']) && $res['result'] == 'success' && $res['authenticated'] == true){
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'id' => $userData['buyer_id'],
                'name' => $userData['full_name'],
                'phone' => $userData['phone_number'], 
                'role' => $userData['roleid'],

            ];

            $_SESSION['message'] = "Welcome, " . $userData['full_name'];
            header("Location: ../customersprofile.php"); // Redirect to the customers profile
            exit(0);

        }else{
            // SIM mismatch or network authentication failure
            $reason = isset($res['message']) ? $res['message'] : "Network verification failed.";
            $_SESSION['message'] = "Login Blocked: " . $reason;
            header("Location: ../customerslogin.php");
            exit(0);
        }
    }else{
        // Customer profile missing from Database
        $_SESSION['message'] = "No registered profile found matching this number.";
        header("Location: ../customerslogin.php");
        exit(0);
    }
}



?> 