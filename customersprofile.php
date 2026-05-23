<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <?php
                $sanPhone = htmlspecialchars($_SESSION['auth_user']['number'], ENT_QUOTES, 'utf-8'); 
                $getCustomerData = "SELECT full_name, home_address, latitude, longitude, gender, age, verified_status, phone_number FROM buyers WHERE phone_number = ?";
                $customerRes = mysqli_execute_query($con, $getCustomerData, [$sanPhone]);

                if(mysqli_num_rows($customerRes) > 0){
                    foreach($customerRes as $res){
                        ?>
                            

                            <div class="col-md-12">
                                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4" style="background: #ffffff;">
    
                                    <div class="p-3 d-flex align-items-center justify-content-between border-bottom" style="background-color: #f8f9fa;">
                                        <span class="small fw-bold text-muted uppercase tracking-wider">Account Matrix</span>
                                        
                                        <?php if($res['verified_status'] == '1'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill d-flex align-items-center gap-1">
                                                <i class="bi bi-shield-check-fill"></i> Verified Member
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning-dominant border border-warning-subtle px-3 py-1.5 rounded-pill d-flex align-items-center gap-1" style="color: #b78103;">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Pending Verification
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" 
                                                style="width: 64px; height: 64px; background: linear-gradient(135deg, #2e7d32, #1b5e20); font-size: 1.5rem;">
                                                <?= strtoupper(substr($res['full_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <h4 class="fw-bold text-dark mb-0"><?= htmlspecialchars($res['full_name']); ?></h4>
                                                <p class="text-muted small mb-0"><i class="bi bi-phone me-1 text-success"></i> <?= htmlspecialchars($res['phone_number']); ?></p>
                                            </div>
                                        </div>

                                        <div class="row g-2 text-center mb-4">
                                            <div class="col-6">
                                                <div class="p-2 rounded-3" style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                                                    <span class="d-block text-muted small uppercase font-monospace" style="font-size: 0.75rem;">Age Profile</span>
                                                    <strong class="text-dark fs-5"><?= htmlspecialchars($res['age']); ?></strong> <span class="small text-muted">yrs</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 rounded-3" style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                                                    <span class="d-block text-muted small uppercase font-monospace" style="font-size: 0.75rem;">Gender Profile</span>
                                                    <strong class="text-dark fs-5"><?= htmlspecialchars($res['gender']); ?></strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 rounded-3" style="background-color: #fcfcfc; border: 1px dashed #e0e0e0;">
                                            <div class="mb-3">
                                                <span class="text-muted small d-block uppercase font-monospace mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Registered Physical Address</span>
                                                <div class="text-dark fw-semibold d-flex align-items-start gap-2">
                                                    <i class="bi bi-geo-alt-fill text-danger mt-0.5"></i>
                                                    <span><?= htmlspecialchars($res['home_address']); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="row g-0 pt-2 border-top border-light">
                                                <div class="col-6">
                                                    <span class="text-muted small d-block mb-0" style="font-size: 0.7rem;">Latitude Sector</span>
                                                    <code class="text-dark fw-bold font-monospace"><?= number_format($res['latitude'], 6); ?></code>
                                                </div>
                                                <div class="col-6 ps-3 border-start">
                                                    <span class="text-muted small d-block mb-0" style="font-size: 0.7rem;">Longitude Sector</span>
                                                    <code class="text-dark fw-bold font-monospace"><?= number_format($res['longitude'], 6); ?></code>
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

<?php
include('Includes/bottomnav.php');
include('Includes/scripts.php');
?>