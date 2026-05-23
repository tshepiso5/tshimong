<?php
session_start();
if(isset($_POST['logout-btn'])){
    if($_SESSION['auth_user']['role'] == '3'){
        session_destroy();

        $_SESSION['message'] = "Logged Out";
        header("Location: farmerslogin.php");
        exit(0);
    }elseif($_SESSION['auth_user']['role'] == '4'){
        session_destroy();

        $_SESSION['message'] = "Logged Out";
        header("Location: customerslogin.php");
        exit(0);
    }
    
}

?>