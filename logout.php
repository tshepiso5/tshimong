<?php
session_start();
if(isset($_POST['logout-btn'])){
    session_destroy();

    $_SESSION['message'] = "Logged Out";
    header("Location: farmerslogin.php");
    exit(0);
}

?>