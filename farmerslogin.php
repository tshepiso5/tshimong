<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <form action="backend/loginbackend.php" method="POST">
                        <div class="mb-3">
                            <label>Registered Phone Number</label>
                            <input type="text" name="phone" placeholder="+27..." class="form-control" required>
                        </div>
                        <button type="submit" name="login-btn" class="btn btn-primary w-100">
                            Login via Network Identity
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include('Includes/scripts.php');
?>