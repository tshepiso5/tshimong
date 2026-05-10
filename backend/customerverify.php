<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['verify-trade-btn'])){
    $farmerID = $_SESSION['auth_user']['id'];
    $cleanNum = CleanString($con, $_POST['customer-phone']);
    $cleanCateg = CleanString($con, $_POST['waste-categ']);
    $cleanMass = CleanString($con, $_POST['qty-weight']);
    $cleanDescribe = CleanString($con, $_POST['description']);

    CheckEmptyStrings($cleanNum, 'Number', '../wallet.php');
    CheckEmptyStrings($cleanCateg, 'Waste Category', '../wallet.php');
    CheckEmptyStrings($cleanMass, 'Mass ', '../wallet.php');
    CheckEmptyStrings($cleanDescribe, 'Mass ', '../wallet.php');
    checkNumericOnly($cleanMass, 'Mass ', '../wallet.php');

    $bottleRate = 0.50;
    $foodWasteRate = 1.35;
    $orgncWasteRate = 2;

    // 1. Call Python identity check
    $python = 'C:\Users\Smart Axis\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $scriptPath = realpath(__DIR__ . '/../customercheck.py');

    if (!$scriptPath) {
        die("CRITICAL ERROR: PHP cannot find 'customerverify.py' inside the backend folder.");
    }
    $cmd = "\"$python\" \"$scriptPath\" " . escapeshellarg($cleanNum) . " 2>&1";
    $output = shell_exec($cmd);
    
    
    $res = json_decode($output, true);
    

    if($res && $res['result'] == 'success' && $res['verified'] == true){
        $collectWaste = "INSERT INTO waste_collection(farmer_id, buyer_num, waste_category, description, qty) VALUES(?, ?, ?, ?, ?)";
        $colRes = mysqli_execute_query($con, $collectWaste, [$farmerID, $cleanNum, $cleanCateg, $cleanDescribe, $cleanMass]);

        if(!$colRes){
            $_SESSION['message'] = "Something Went Wrong";
            header("Location: ../wallet.php");
            exit(0); 

        }else{
            $_SESSION['message'] = "Successfully Collected Waste";
            header("Location: ../wallet.php");
            exit(0); 
        }

       


    }else{
        $_SESSION['message'] = "Trade Blocked" .$res['message'] ?? "Security check failed.";
        header("Location: ../wallet.php");
        exit(0); 
    }

}


?>