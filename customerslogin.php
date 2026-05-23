<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>

<div class="login-container" style="max-width: 400px; margin: 50px auto; padding: 20px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); border-radius: 8px;">
    <h2 class="text-center">Welcome</h2>
    <p class="text-muted text-center">Use your registered mobile number to securely access your profile and wallet</p>
    
    <?php
        include('message.php');
    ?>

    <form action="backend/processlogin.php" method="POST">
        <div class="mb-4">
            <label class="form-label">Phone Number</label>
            <input type="text" name="customer-phone" class="form-control form-control-lg" placeholder="+27XXXXXXXXX" required>
            <small class="text-muted">Ensure your mobile data is turned on for network auto-verification.</small>
        </div>
        
        <button type="submit" name="customer-login-btn" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-shield-lock"></i> Secure Login
        </button>
    </form>
</div>

<?php
include('Includes/scripts.php');
?>