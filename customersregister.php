<?php
include('Includes/header.php');
include('Includes/navbar.php');
$userType = 4;
?>

<div class="container d-flex justify-content-center align-items-center min-vh-100 my-5">
    <div class="card tshimong-register-card border-0 shadow-lg p-4 p-md-5">
        
        <!-- Form Header -->
        <div class="text-center mb-4">
            <h2 class="tshimong-logo justify-content-center mb-1">
                Tshimong<span class="logo-accent">:</span> <span class="logo-sub">Join Us</span>
            </h2>
            <p class="text-muted small">Create your account to start buying from organic farms around you, and earn our digital currency through exchanges for your waste.</p>
        </div>

        <!-- Registration Form -->
        <form action="backend/registercustomer.php" method="POST">
            <div class="row g-3">
                <input type="number" name="user-type" class="form-control" value="<?= $userType ?>" readonly hidden>
                <!-- Full Name -->
                <div class="col-12">
                    <label class="form-label tshimong-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text tshimong-icon-box"><i class="bi bi-person"></i></span>
                        <input type="text" name="full-name" class="form-control tshimong-input" placeholder="e.g. Thabo Ndlovu" required>
                    </div>
                </div>

                <!-- Age -->
                <div class="col-md-6">
                    <label class="form-label tshimong-label">Age</label>
                    <input type="number" name="age" min="15" max="120" class="form-control tshimong-input" placeholder="e.g. 28" required>
                </div>

                <!-- Gender -->
                <div class="col-md-6">
                    <label class="form-label tshimong-label">Gender</label>
                    <select name="gender" class="form-select tshimong-input" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="non-binary">Non-binary</option>
                        <option value="prefer-not-to-say">Prefer not to say</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="col-12">
                    <label class="form-label tshimong-label">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text tshimong-icon-box"><i class="bi bi-telephone"></i></span>
                        <input type="tel" name="phone" class="form-control tshimong-input" placeholder="e.g.+2782 123 4567" required>
                        <small class="text-muted">Keep your phone turned on; your operator will verify connection status.</small>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-12">
                    <label class="form-label tshimong-label">Residential Address</label>
                    <div class="input-group">
                        <span class="input-group-text tshimong-icon-box"><i class="bi bi-geo-alt"></i></span>
                        <textarea name="res-address" rows="3" class="form-control tshimong-input" placeholder="e.g. 142 Vilakazi St, Orlando West, Soweto" required></textarea>
                    </div>
                </div>

                <!-- Terms Checkbox -->
                <div class="col-12 mt-3">
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" name="privacy-policy-status" type="checkbox" id="terms" required>
                        <label class="form-check-label text-muted small" for="terms">
                            I agree to the Tshimong Privacy Policy & Data Terms.
                        </label>
                    </div>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit" name="customer-register-btn" class="btn btn-tshimong-primary w-100 py-3 fw-bold shadow-sm">
                    Create Customer Account
                </button>
            </div>
            
            <div class="text-center mt-3">
                <p class="small text-muted mb-0">Already registered? <a href="login.php" class="text-success fw-bold text-decoration-none">Sign In</a></p>
            </div>
        </form>
        
    </div>
</div>


<?php
include('Includes/scripts.php');
?>