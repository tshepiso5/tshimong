<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>


<div class="container">
    <?php
        include('message.php');
    ?>
    <div class="row justify-content-center mt-2">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-2">
                    <div id="profile-img">

                    </div>
                </div>
                <div class="col-md-10 d-flex align-items-center" style="text-align: left;">
                    <h5>
                        <?= $_SESSION['auth_user']['name'] ?>
                    </h5>
                </div>
            </div>

            <div class="row">
                <?php
                    $userID = htmlspecialchars($_SESSION['auth_user']['id']);
                    $getFarmData = "SELECT farm_address, land_occupancy_type, latitude, longitude, farmer_id FROM farm_data WHERE farmer_id = ?";
                    $farmDataRes = mysqli_execute_query($con,  $getFarmData, [$userID]);

                    if(!mysqli_num_rows($farmDataRes) > 0){
                        ?>
                            <div class="card">
                                <div class="card-header">
                                    <h4>
                                        Update Farm Data
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form action="backend/farmdata.php" method="post" enctype="multipart/form-data">
                                        <div class="row">

                                        
                                       <div class="col-md-6 mb-3">
                                            <label>Farm Full Address</label>
                                            <input type="text" name="address" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Land Occupancy Type</label>
                                            <select name="tenure" id="" class="form-control">
                                                <option value="">--Select Tenure</option>
                                                <option value="Permission-to-Occupy">Communal/Customary/State Land</option>
                                                <option value="Leasehold">Leased Land</option>
                                                <option value="Title-Deed">Private Title</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label>Land Occupancy Agreement</label>
                                            <input type="file" class="form-control" name="agreement">
                                        </div>
                                        </div>
                                        <div class="col-md-12 mt-5">
                                            <button type="submit" name="update-farm-data" class="btn btn-primary w-100">
                                                Update Farm Data
                                            </button> 
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        <?php

                    }else{

                       foreach($farmDataRes as $data){
                        $santAddress = htmlspecialchars($data['farm_address'], ENT_QUOTES, 'UTF-8');
                        $sanType = htmlspecialchars($data['land_occupancy_type']);
                        $sanLong = htmlspecialchars($data['longitude']);
                        $sanLat = htmlspecialchars($data['latitude']);
                        ?>
                            <div class="col-md-12 mt-3 mb-3">
                                <p>Farm Address: <?= $santAddress ?></p>
                                
                            </div>
                            <div class="col-md-12 mb-3 mt-3">
                                <p>GCS Coordinates: <?= $sanLat.','.$sanLong ?></p>
                                
                            </div>
                            <div class="col-md-12 mt-3 mb-3">
                                <p>Land Tenure: <?= $sanType ?></p>

                            </div>
                            
                        <?php
                       }
                    }
                ?>
            </div>
        </div>
    </div>
</div>


<?php
include('Includes/bottomnav.php');
include('Includes/scripts.php');
?>