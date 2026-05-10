<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['submit-work-btn'])){
    $cleanID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanCateg = CleanString($con, $_POST['work-categ']);
    $cleanDescribe = CleanString($con, $_POST['describe']);
    $taskImg = $_FILES['task-img']['name'];
    $imgtmp = $_FILES['task-img']['tmp_name'];

    $maxSize = 2 * 1024 * 1024;
    if($_FILES['task-img']['size'] > $maxSize){
        $_SESSION['messages'] = "Image Size Too Big";
        header('Location: ../work.php');
        Exit(0);
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $fileExt = strtolower(pathinfo($taskImg, PATHINFO_EXTENSION));


    if(!in_array($fileExt, $allowedExts)){
        die("Forbiden File Type");
    }

    $finalImgName = time()."_".bin2hex(random_bytes(4)).".".$fileExt;

    $checkDuplicates = "SELECT farmer_id, work_description FROM work_logs WHERE farmer_id = ? AND work_description = ?";
    $dupRes = mysqli_execute_query($con, $checkDuplicates, [$cleanID, $cleanDescribe]);

    if(!mysqli_num_rows($dupRes) > 0){

        $logTask = "INSERT INTO work_logs(farmer_id, work_category, work_description, work_img) VALUES(?, ?, ?, ?)";
        $taskRes = mysqli_execute_query($con, $logTask, [$cleanID, $cleanCateg, $cleanDescribe, $finalImgName]);

        if(!$taskRes){
            $_SESSION['messages'] = "Something Went Wrong";
            header('Location: ../work.php');
            Exit(0);
        }else{
            move_uploaded_file($_FILES['task-img']['tmp_name'], '../uploads/farmwork/'.$finalImgName);
            $_SESSION['messages'] = "Successfully Logged Task";
            header('Location: ../work.php');
            Exit(0);
        }


    }else{
        $_SESSION['messages'] = "You May have Logged this Task For Today";
        header('Location: ../work.php');
        Exit(0);
    }
}





?>