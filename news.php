<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>


<?php
    $getNews ="SELECT * FROM farmers_content";
    $newsRes = mysqli_execute_query($con, $getNews);


    if(!mysqli_num_rows($newsRes) > 0){
        ?>
            <div class="card-card-body">
                <div class="row text-center">
                <h4>
                    Articles Not Yet Available
                </h4>
                </div>
            </div>
        <?php
    }

?>

<?php
include('Includes/bottomnav.php');
include('Includes/scripts.php');
?>