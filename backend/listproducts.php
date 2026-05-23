<?php
session_start();
include('../admin/config/dbcon.php');
include('../admin/functions.php');

if(isset($_POST['submit-listing-btn'])){
    $farmerID = CleanString($con, $_POST['farmer-id']);
    $productName = CleanString($con, $_POST['product-name']);
    $dtls = CleanString($con, $_POST['product-details']);
    $categ = CleanString($con, $_POST['product-category']);
    $slug = CleanString($con, $_POST['product-slug']);
    $price = CleanString($con, $_POST['selling-price']);
    $qty = CleanString($con, $_POST['selling_qty']);
    $prodImg = $_FILES['product-img']['name'];
    $imgTmp = $_FILES['product-img']['tmp_name'];
    CheckEmptyStrings($productName, 'Product Name', '../office.php');
    CheckEmptyStrings($prodImg, 'Product Image', '../office.php');
    CheckEmptyStrings($dtls, 'Details', '../office.php');
    CheckEmptyStrings($slug, 'Product Url', '../office.php');
    CheckEmptyStrings($categ, 'Product Category', '../office.php');
    CheckEmptyStrings($price, 'Selling Price', '../office.php');
    CheckEmptyStrings($qty, 'Product Available Quantity', '../office.php');

    $maxSize = 2 * 1024 * 1024;
    if($_FILES['task-img']['size'] > $maxSize){
        $_SESSION['messages'] = "Image Size Too Big";
        header('Location: ../office.php');
        Exit(0);
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $fileExt = strtolower(pathinfo($prodImg, PATHINFO_EXTENSION));


    if(!in_array($fileExt, $allowedExts)){
        die("Forbiden File Type");
    }
    
    $finalImgName = "product-".time()."_".bin2hex(random_bytes(4)).".".$fileExt;

    CheckforTwoDuplicates($con, 'farmer_id', 'product_slug', 'listed_products', $farmerID, $slug, '../office.php');
    $finalSlug = CreateSlug($slug);

    $listProduct = "INSERT INTO listed_products(farmer_id, product_name, details, product_category, product_slug, selling_price, qty, product_img) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
    $listRes = mysqli_execute_query($con, $listProduct, [$farmerID, $productName, $dtls, $categ, $finalSlug, $price, $qty, $finalImgName]);

    if(!$listRes){
        $_SESSION['messages'] = "Something Went Wrong";
        header('Location: ../office.php');
        Exit(0);

    }else{
        move_uploaded_file($_FILES['task-img']['tmp_name'], '../uploads/listed_products/'.$finalImgName);
        $_SESSION['messages'] = "Successfully Listed Product.";
        header('Location: ../office.php');
        Exit(0);
    }



}

?>