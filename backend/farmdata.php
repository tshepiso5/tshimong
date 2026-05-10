<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');


if(isset($_POST['update-farm-data'])){
    $cleanPhone = CleanString($con, $_SESSION['auth_user']['phone']);
    $cleanFarmerID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanAddress = CleanString($con, $_POST['address']);
    $cleanTenure = CleanString($con, $_POST['tenure']);
    $agreement = $_FILES['agreement']['name'];
    $agreementTmp = $_FILES['agreement']['tmp_name'];

    $python_path = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $script_path = dirname(__DIR__) . '/getlocation.py';
    $cmd = "\"$python_path\" \"$script_path\" " . escapeshellarg($cleanPhone);

    $output = shell_exec($cmd);
    $res = json_decode($output, true);



    

    $maxSize = 2 * 1024 * 1024;

    if($_FILES['agreement']['size'] > $maxSize){
        $_SESSION['messages'] = "Document Size Too Big";
            header('Location: ../farmersprofile.php');
            Exit(0);
    }
    $allowedExts = ['pdf', 'docx', 'jpg', 'jpeg', 'png', 'webp', 'svg', ''];
    $fileExt = strtolower(pathinfo($agreement, PATHINFO_EXTENSION));


    
    $finalAgreementName = time()."_".bin2hex(random_bytes(4).".".$fileExt);

    

    if(!in_array($fileExt, $allowedExts)){
        die("Forbiden File Type");
    }

    if($res && $res['status'] === 'success'){
        $lat = $res['latitude'];
        $lng = $res['longitude'];

        $CheckforDuplicates = "SELECT farmer_id, latitude, longitude FROM farm_data WHERE farmer_id = ? AND latitude = ? AND longitude = ?";
        $dupsRes = mysqli_execute_query($con, $CheckforDuplicates, [$cleanFarmerID, $lat, $lng]);

        if(mysqli_num_rows($dupsRes) > 0){
            $_SESSION['message'] = "This Farm Has Already Been Captured";
            header("Location: ../farmersprofile.php");
            exit(0);
        }

        if(!empty($agreement) && $fileExt !=''){
            $captureFarmData = "INSERT INTO farm_data(farmer_id, land_occupancy_type, occupancy_agreement, farm_address, latitude, longitude) VALUES(?, ?, ?, ?, ?, ?)";
            $captureRes = mysqli_execute_query($con, $captureFarmData, [$cleanFarmerID, $cleanTenure, $finalAgreementName, $cleanAddress, $lat, $lng]);

            if($captureRes){
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/agreements/'.$finalAgreementName);
                $_SESSION['message'] = "Farm Data Updated Successfully.";
                header("Location: ../farmersprofile.php");
                exit(0);
            }else{
                $_SESSION['message'] = "Error capturing location: " . ($res['message'] ?? 'Unknown error');
                header("Location: ../farmersprofile.php");
                exit(0);
            }
        }else{
            $nullAgrmnt = null;
            $captureFarmData = "INSERT INTO farm_data(farmer_id, land_occupancy_type, occupancy_agreement, farm_address, latitude, longitude) VALUES(?, ?, ?, ?, ?, ?)";
            $captureRes = mysqli_execute_query($con, $captureFarmData, [$cleanFarmerID, $cleanTenure, $nullAgrmnt, $cleanAddress, $lat, $lng]);

            if($captureRes){
                $_SESSION['message'] = "Farm Data Updated Successfully.";
                header("Location: ../farmersprofile.php");
                exit(0);
            }else{
                $_SESSION['message'] = "Error capturing location: " . ($res['message'] ?? 'Unknown error');
                header("Location: ../farmersprofile.php");
                exit(0);
            }
        }

    }

   



   



}



?>