<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <?php
                include('message.php');
            ?>
            <div class="card">
            <div class="card-header">
                <h4>
                        Register
                </h4>
                
            </div>
            <div class="card-body">
                

                
                <form action="backend/farmerregistration.php" method="post">
                    <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label>Full Name</label>
                        <input type="text" name="fname" required placeholder="enter full name" class="form-control">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label>Age</label>
                        <input type="number" name="age" required placeholder="enter Age" class="form-control">
                    </div>
                       </div> 
                        <div class="form-group mb-3">
                        <label>Gender</label>
                            <select name="gender" required placeholder="gender" class="form-control mb-3">
                                <option value="">--Select Gender--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Prefer Not To Say">Prefer Not To Say</option>
                            </select>
                        </div>
                    <div class="form-group mb-3">
                        <label>Phone Number</label>
                        <input type="tel" required name="phone" placeholder="enter phone number" class="form-control">
                    </div>
                    
                    
                    
                        
                    <div class="form-group mb-3">
                        <button type="submit" name="register-farmer-btn" class="btn btn-primary">Register </button>
                    </div>
                </form>
                
            </div>
            </div>
        </div>
    </div>
</div>



<?php
include('Includes/scripts.php');
?>