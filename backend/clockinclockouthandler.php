<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');


if(isset($_POST['clock-in-btn'])){

    $cleanID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanPhone = CleanString($con, $_SESSION['auth_user']['phone']);

    $farmCoords = "SELECT latitude, longitude FROM farm_data WHERE farmer_id = ?";
    $coordsRes = mysqli_execute_query($con, $farmCoords, [$cleanID]);

    $coords = mysqli_fetch_assoc($coordsRes);

    if(!$coords['latitude']){
        $_SESSION['message'] = "Please register your farm in the profile, before attempting a clock-in";
        header("Location: ../farmersprofile.php");
        exit();
    }

    $python = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $script = dirname(__DIR__) . '/clockinverify.py';
    $cmd = "\"$python\" \"$script\" " . escapeshellarg($cleanPhone) . " {$coords['latitude']} {$coords['longitude']}";
    
    $res = json_decode(shell_exec($cmd), true);

    if($res && $res['status'] === 'success' && $res['verified'] === true){
        $today= date('Y-m-d');
        $checkout = null;
        $captureClockIn = "INSERT INTO clock_in_clock_out(farmer_id, work_date, check_out_time) VALUES(?, ?, ?)";
        $clockRes = mysqli_execute_query($con, $captureClockIn, [$cleanID, $today, $checkout]);

        if(!$clockRes){
            $_SESSION['message'] = "Clock-in Failed. Something Went Wrong";
            header("Location: ../work.php");
            exit();

        }else{
            $_SESSION['message'] = "Clocked-in Successfully.";
            header("Location: ../work.php");
            exit();

        }
    }else{
        $_SESSION['message'] = "Clock-in Failed. Network Indicates You Are Not at Farm";
        header("Location: ../work.php");
        exit();
    }

}elseif(isset($_POST['clock-out-btn'])){
    $cleanID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanPhone = CleanString($con, $_SESSION['auth_user']['phone']);

    $farmCoords = "SELECT latitude, longitude FROM farm_data WHERE farmer_id = ?";
    $coordsRes = mysqli_execute_query($con, $farmCoords, [$cleanID]);

    $coords = mysqli_fetch_assoc($coordsRes);

    $python = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $script = dirname(__DIR__) . '/clockinverify.py';
    $cmd = "\"$python\" \"$script\" " . escapeshellarg($cleanPhone) . " {$coords['latitude']} {$coords['longitude']}";
    
    $res = json_decode(shell_exec($cmd), true);

    if($res && $res['status'] === 'success' && $res['verified'] === true){
        $clockStatus = 0;
        $captureClockIn = "UPDATE clock_in_clock_out SET check_out_time = NOW() WHERE farmer_id = ? AND check_out_time IS NULL ORDER BY clock_in_time DESC LIMIT 1";
        $clockRes = mysqli_execute_query($con, $captureClockIn, [$cleanID]);

        if(!$clockRes){
            $_SESSION['message'] = "Clock-out Failed. Something Went Wrong";
            header("Location: ../work.php");
            exit();

        }else{
            $_SESSION['message'] = "Clocked-out Successfully.";
            header("Location: ../work.php");
            exit();

        }
    }else{
        $_SESSION['message'] = "Clock-out Failed. Network Indicates You have already left the Farm";
        header("Location: ../work.php");
        exit();
    }



}


?>