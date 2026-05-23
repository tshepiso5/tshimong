<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>


<div class="container my-4">
    <?php include('message.php'); ?>
    
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4" style="background: #ffffff;">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" 
                         style="width: 56px; height: 56px; background: linear-gradient(135deg, #2e7d32, #1b5e20); font-size: 1.35rem;">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div>
                        <span class="text-muted small uppercase tracking-wider font-monospace d-block" style="font-size: 0.75rem;">Authenticated Farmer</span>
                        <h4 class="fw-bold text-dark mb-0"><?= htmlspecialchars($_SESSION['auth_user']['name']); ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                    $userID = htmlspecialchars($_SESSION['auth_user']['id']);
                    $getFarmData = "SELECT farm_address, land_occupancy_type, latitude, longitude, farmer_id FROM farm_data WHERE farmer_id = ?";
                    $farmDataRes = mysqli_execute_query($con, $getFarmData, [$userID]);

                    // CASE A: NO FARM PROFILE SET YET -> SHOW REGISTRATION FORM CARD
                    if(!mysqli_num_rows($farmDataRes) > 0){
                        ?>
                        <div class="col-12">
                            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                                <div class="p-3 text-white" style="background: linear-gradient(135deg, #1b5e20, #2e7d32);">
                                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-plus-circle"></i> Initialize Farm Profiles
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #ffffff;">
                                    <form action="backend/farmdata.php" method="post" enctype="multipart/form-data">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold text-muted">Farm Full Name</label>
                                                <input type="text" name="farm-name" class="form-control form-control-lg fs-6" placeholder="e.g., Tshimong Hub Alpha" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold text-muted">Farm Full Address</label>
                                                <input type="text" name="address" class="form-control form-control-lg fs-6" placeholder="e.g., Soweto, Johannesburg" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold text-muted">Land Occupancy Type</label>
                                                <select name="tenure" class="form-select form-select-lg fs-6" required>
                                                    <option value="">-- Select Tenure --</option>
                                                    <option value="Permission-to-Occupy">Communal / Customary / State Land</option>
                                                    <option value="Leasehold">Leased Land Agreement</option>
                                                    <option value="Title-Deed">Private Title Deed Holder</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold text-muted">Land Occupancy Agreement</label>
                                                <input type="file" class="form-control" name="agreement" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 mt-4 pt-2">
                                            <button type="submit" name="update-farm-data" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm fs-6 fw-bold">
                                                <i class="bi bi-cloud-arrow-up me-2"></i>Securely Commit Farm Registry
                                            </button> 
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        // CASE B: FARM FOUND -> DISPLAY DATA IN A CLEAN PROFILE FOOTPRINT CARD
                        foreach($farmDataRes as $data){
                            $santAddress = htmlspecialchars($data['farm_address'], ENT_QUOTES, 'UTF-8');
                            $sanType = htmlspecialchars($data['land_occupancy_type']);
                            $sanLong = htmlspecialchars($data['longitude']);
                            $sanLat = htmlspecialchars($data['latitude']);
                            ?>
                            <div class="col-12">
                                <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="background: #ffffff;">
                                    
                                    <div class="p-3 d-flex align-items-center justify-content-between border-bottom" style="background-color: #f8f9fa;">
                                        <span class="small fw-bold text-muted uppercase tracking-wider font-monospace" style="font-size: 0.75rem;">Registered Operational Footprint</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill font-monospace" style="font-size: 0.7rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Active Record
                                        </span>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="mb-4">
                                            <span class="text-muted small d-block uppercase font-monospace mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Physical Ground Address</span>
                                            <div class="text-dark fw-semibold d-flex align-items-start gap-2 fs-5">
                                                <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                                                <span><?= $santAddress ?></span>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded-3 mb-4" style="background-color: #fdf6ed; border-left: 4px solid #f57c00;">
                                            <span class="text-muted small d-block uppercase font-monospace mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Triangulated Grid Matrix (GCS)</span>
                                            <div class="row g-0 text-center">
                                                <div class="col-6 border-end border-2">
                                                    <span class="text-muted small d-block mb-0" style="font-size: 0.7rem;">Latitude Zone</span>
                                                    <code class="text-dark fw-bold font-monospace fs-6"><?= number_format(floatval($sanLat), 6); ?></code>
                                                </div>
                                                <div class="col-6 ps-2">
                                                    <span class="text-muted small d-block mb-0" style="font-size: 0.7rem;">Longitude Zone</span>
                                                    <code class="text-dark fw-bold font-monospace fs-6"><?= number_format(floatval($sanLong), 6); ?></code>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded-3" style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <span class="text-muted small d-block uppercase font-monospace" style="font-size: 0.7rem;">Verified Legal Tenure Allocation</span>
                                                    <strong class="text-success-dark fs-5 fw-bold text-dark" style="color: #1b5e20;"><?= $sanType ?></strong>
                                                </div>
                                                <div class="fs-2 text-success opacity-75">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
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