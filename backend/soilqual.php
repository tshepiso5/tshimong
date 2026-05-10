<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');


if(isset($_POST['soil-data-btn'])){
    $cleanID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanPh = CleanString($con, $_POST['ph-levels']);
    $cleanColour = CleanString($con, $_POST['soil-colour']);
    $cleanTexture = CleanString($con, $_POST['texture']);
    $cleanWorms = CleanString($con, $_POST['worms']);
    $cleanImg = CleanString($con, $_FILES['soil-image']['name']);
    $cleanTmp = CleanString($con, $_FILES['soil-image']['tmp_name']);

    CheckEmptyStrings($cleanPh, 'PH', '../office.php');
    CheckEmptyStrings($cleanColour, 'Colour', '../office.php');
    CheckEmptyStrings($cleanTexture, 'Texture', '../office.php');
    CheckEmptyStrings($cleanWorms, 'Worms', '../office.php');
    
    $maxSize = 2 * 1024 * 1024;
    if($_FILES['soil-image']['size'] > $maxSize){
        $_SESSION['messages'] = "Image Size Too Big";
        header('Location: ../office.php');
        Exit(0);
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $fileExt = strtolower(pathinfo($cleanImg, PATHINFO_EXTENSION));


    if(!in_array($fileExt, $allowedExts)){
        die("Forbiden File Type");
    }

    $finalImgName = time()."_".bin2hex(random_bytes(4)).".".$fileExt;

   

    $captureSoilDetls = "INSERT INTO land_data(farmer_id, ph_balance, soil_colour, soil_texture, bio_activity_count, soil_sample_img) VALUES(?, ?, ?, ?, ?, ?)";
    $soilRes = mysqli_execute_query($con, $captureSoilDetls, [$cleanID, $cleanPh, $cleanColour, $cleanTexture, $cleanWorms, $finalImgName]);

    if(!$soilRes){
        $_SESSION['messages'] = "Something Went Wrong";
        header('Location: ../office.php');
        Exit(0);
    }else{
        move_uploaded_file($_FILES['soil-image']['tmp_name'], '../uploads/soil_tests/'.$finalImgName);
        $_SESSION['messages'] = "Successfully Updated Data";
        header('Location: ../office.php');
        Exit(0);
    }

}


?>