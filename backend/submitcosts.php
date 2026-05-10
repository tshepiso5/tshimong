<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['costs-btn'])){
    $cleanID = CleanString($con, $_SESSION['auth_user']['id']);
    $cleanCateg = CleanString($con, $_POST['cost-categ']);
    $cleanItem = CleanString($con, $_POST['item']);
    $cleanQty = CleanString($con, $_POST['qty']);
    $cleanPrice = CleanString($con, $_POST['unit-price']);

    CheckEmptyStrings($cleanCateg, 'Cost-Category', '../office.php');
    CheckEmptyStrings($cleanItem, 'Item', '../office.php');
    CheckEmptyStrings($cleanQty, 'Quantity', '../office.php');
    CheckEmptyStrings($cleanPrice, 'Unit Price', '../office.php');

    $checkDups = "SELECT cost_category, item, qty, unit_price FROM farmer_input_costs WHERE farmer_id = ? AND cost_category = ? AND item = ? ";
    $dupsRes = mysqli_execute_query($con, $checkDups, [$cleanID, $cleanCateg, $cleanItem]);

    if(mysqli_num_rows($dupsRes) > 0){
        $_SESSION['messages'] = "You Have Already Submitted This Cost Item";
        header('Location: ../office.php');
        Exit(0);
    }

    $submitCost = "INSERT INTO farmer_input_costs(farmer_id, cost_category, item, qty, unit_price) VALUES(?, ?, ?, ?, ?)";
    $costsRes= mysqli_execute_query($con, $submitCost, [$cleanID, $cleanCateg, $cleanItem, $cleanQty, $cleanPrice]);

    if(!$costsRes){
        $_SESSION['messages'] = "Something Went Wrong";
        header('Location: ../office.php');
        Exit(0);
    }else{
        $_SESSION['messages'] = "You Have Successfully Submitted Cost";
        header('Location: ../office.php');
        Exit(0);
    }
}





?>