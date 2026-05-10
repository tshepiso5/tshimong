<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>


<div class="container">
        
    <div class="row justify-content-center">
        <?php
            include('message.php');
        ?>
        <div class="col-md-8">
            <?php
                $clockedInFarmer = 1;
                $id = $_SESSION['auth_user']['id'];
                $getClockInStatus = "SELECT farmer_id FROM clock_in_clock_out WHERE farmer_id = ? AND check_out_time IS NULL";
                $statRes = mysqli_execute_query($con, $getClockInStatus, [$id]);
                
                if(!mysqli_num_rows($statRes) > 0){

                    ?>
                    

                        <h2>Welcome to Tshimong, <?= $_SESSION['auth_user']['name']; ?></h2>
                        <p class="text-muted">You are currently logged in, but not yet active on the farm.</p>
                        
                        <div class="card shadow-sm p-4 my-4">
                            <h5>Verify Your Location</h5>
                            <p>Click below to verify your SIM identity at your registered farm plot.</p>
                            
                            <form action="backend/clockinclockouthandler.php" method="POST">
                                <button type="submit" name="clock-in-btn" class="btn btn-success btn-lg px-5">
                                    <i class="bi bi-geo-alt"></i> Clock-In Now
                                </button>
                            </form>
                        </div>
                    <?php
                }else{
                    ?>
                        <div class="container">
                            <div class="row justify-content-end">
                                <form action="backend/clockinclockouthandler.php" method="post">
                                <div class="col-md-3">
                                    <button type="submit" name="clock-out-btn" class="btn btn-danger">
                                        Clock-out
                                    </button>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="card shadow-sm p-4 my-4">
                            <form action="backend/logwork.php" method="POST" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <h2>Welcome to Your Workspace, <?= $_SESSION['auth_user']['name']; ?></h2>
                                    <p class="text-muted">Which tasks have you completed, since clocking-in to work?</p>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label>Work Completed</label>
                                    <select name="work-categ" id="" class="form-control">
                                        <option value="">--Which Task Completed?--</option>
                                        <option value="till">Tilled Land</option>
                                        <option value="beds_rows">Made Beds or Rows</option>
                                        <option value="plant">Planted Crops</option>
                                        <option value="transplant">Transplanted Crops</option>
                                        <option value="water">Watered Crops</option>
                                        <option value="weed_pest_ctrl">Weed or Pest Control</option>
                                        <option value="innovations_optimizations">Innovations or Optimizations</option>
                                        <option value="harvest">Harvest</option>
                                    </select>

                                </div>
                                <div class="col-md-12 mb-3">
                                    <label >Work Description</label>
                                    <textarea name="describe" id="" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Proof of Task Completed Image</label>
                                    <input type="file" name="task-img" class="form-control">
                                </div>
                                
                                <button type="submit" name="submit-work-btn" class="btn btn-success btn-lg px-5">
                                    <i class="bi bi-geo-alt"></i> Submit Task
                                </button>
                            </form>
                        </div>
                    <?php
                }
            
            ?>
            
    </div>
</div>


<?php
include('Includes/bottomnav.php');
include('Includes/scripts.php');
?>
