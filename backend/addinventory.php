<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');


if(isset($_POST['iventory-btn'])){
    $cleanID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanCrop = CleanString($con, $_POST['crop']);
    $cleanStage= CleanString($con, $_POST['growth-stage']);
    $cleanQty = CleanString($con, $_POST['quantity']);
    $cleanDate= CleanString($con, $_POST['planting-date']);
    $cleanStatus = mysqli_real_escape_string($con, $_POST['avail-status']) == true ? 1 : 0;
    $img= $_FILES['inventory-img']['name'];
    $imgTmp = $_FILES['inventory-img']['tmp_name'];


    CheckEmptyStrings($cleanCrop, 'Crop', '../office.php');
    CheckEmptyStrings($cleanStage, 'Growth Stage', '../office.php');
    CheckEmptyStrings($cleanQty, 'Quantity', '../office.php');
    CheckEmptyStrings($cleanDate, 'Planting Date', '../office.php');
   
    $maxSize = 2 * 1024 * 1024;
    if($_FILES['inventory-img']['size'] > $maxSize){
        $_SESSION['messages'] = "Image Size Too Big";
        header('Location: ../office.php');
        Exit(0);
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $fileExt = strtolower(pathinfo($img, PATHINFO_EXTENSION));


    if(!in_array($fileExt, $allowedExts)){
        die("Forbiden File Type");
    }

    $finalImgName = time()."_".bin2hex(random_bytes(4)).".".$fileExt;

    $checkDups = "SELECT crop, crop_growth_stage, qty FROM inventory WHERE farmer_id = ? AND crop = ?";
    $dupsRes = mysqli_execute_query($con, $checkDups, [$cleanID, $cleanCrop]);

    if(mysqli_num_rows($dupsRes) > 0){
        $_SESSION['messages'] = "Crop Record Already Exists";
        header('Location: ../office.php');
        Exit(0);
    }

    $submitInventory = "INSERT INTO inventory(farmer_id, crop, crop_growth_stage, qty, inventory_img, date_planted, avail_status) VALUES(?, ?, ?, ?, ?, ?, ?)";
    $invRes = mysqli_execute_query($con, $submitInventory, [$cleanID, $cleanCrop, $cleanStage, $cleanQty, $finalImgName, $cleanDate, $cleanStatus]);

    if(!$invRes){
        $_SESSION['messages'] = "Something Went Wrong";
        header('Location: ../office.php');
        Exit(0);

    }else{
        move_uploaded_file($_FILES['inventory-img']['tmp_name'], '../uploads/inventory/'.$finalImgName);
        $_SESSION['messages'] = "Successfully Added Inventory";
        header('Location: ../office.php');
        Exit(0);
    }

}

?>