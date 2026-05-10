<?php
include('Includes/header.php');
?>

    <h2 class="mt-4">User Permissions</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard/User Permissions</li>
    </ol>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    include('..\message.php');
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5>
                            User Permissions
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        Role
                                    </th>
                                    <th>
                                        Resource
                                    </th>
                                    <th>
                                        Permission
                                    </th>
                                    <th>
                                        Edit
                                    </th>
                                    <th>
                                        Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getPermissions = "SELECT role_as, resources, permission FROM permissions";
                                    $permsRes = mysqli_execute_query($con, $getPermissions);

                                    if(!mysqli_num_rows($permsRes) > 0){
                                        ?>
                                            <tr>
                                                <td colspan="5">
                                                    <h5>
                                                        Permissions Not Yet Available!
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Add Permissions
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row justify-content-center">    
                                                                        <div class="card-body">
                                                                            <form action="backendfiles/givepermissions.php" method="post">
                                                                                    <div class="row">
                                                                                        
                                                                                        <div class="col-md-12 mb-3">
                                                                                            <?php
                                                                                                $getRoles = "SELECT role_as FROM users";
                                                                                                $rolesRes = mysqli_execute_query($con, $getRoles);

                                                                                                if(!mysqli_num_rows($rolesRes) > 0){
                                                                                                    ?>
                                                                                                        <h5>
                                                                                                            User Roles Not Yet Defined
                                                                                                        </h5>
                                                                                                    <?php
                                                                                                }else{
                                                                                                    ?>
                                                                                                        <label>Choose Role</label>
                                                                                                        <select name="role" id="" class="form-control">
                                                                                                            <option value="">--Choose Role--</option>
                                                                                                            <?php
                                                                                                                foreach($rolesRes as $role){
                                                                                                                    ?>
                                                                                                                        <option value="<?= $role['role_as'] ?>"><?= $role['role_as'] ?></option>
                                                                                                                    <?php
                                                                                                                }
                                                                                                            ?>
                                                                                                        </select>
                                                                                                    <?php
                                                                                                }
                                                                                            ?>
                                                                                            
                                                                            
                                                                                        </div>
                                                                                        <div class="col-md-6 mb-3">
                                                                                            <?php
                                                                                                $getResources ="SELECT resource_name FROM avail_resources";
                                                                                                $resRes = mysqli_execute_query($con, $getResources);

                                                                                                if(!mysqli_num_rows($resRes) > 0){
                                                                                                    ?>
                                                                                                        <h5>
                                                                                                            Resources Not Yet Loaded
                                                                                                        </h5>
                                                                                                    <?php
                                                                                                }else{
                                                                                                    ?>
                                                                                                    <label>Resource</label>
                                                                                                    <select name="resource" id="" class="form-control">
                                                                                                        <option value="">--SELECT RESOURCE</option>
                                                                                                        <?php
                                                                                                            foreach($resRes as $res){
                                                                                                                $safeRes = htmlspecialchars($res['resource_name']);
                                                                                                                ?>
                                                                                                                    <option value="<?=  $safeRes?>"><?= $safeRes ?></option>
                                                                                                                <?php
                                                                                                            }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                    <?php
                                                                                                }
                                                                                            ?>
                                                                                            
                                                                                        </div>
                                                                                        <div class="col-md-6 mb-3">
                                                                                            
                                                                                            <label>Permission</label>
                                                                                            <select name="permission" id="" class="form-control">
                                                                                                <option value="">--Select Permission--</option>
                                                                                                <option value="Allow">Allow</option>
                                                                                                <option value="Deny">Deny</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        

                                                                                        <div class="col-md-12 mb-3">
                                                                                            <button type="submit" name="give-permits-btn" class="btn btn-primary">Add User</button>
                                                                                        </div>
                                                                    
                                                                                    </div>
                                                                                
                                                                                
                                                                    
                                                                            </form>
                                                                        </div>  
                                                                </div>
                                                            </div>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php
                                    }else{
                                        foreach($permsRes as $perm){
                                            $cleanRole = htmlspecialchars($perm['role_as']);
                                            $cleanResource = htmlspecialchars($perm['resources']);
                                            $cleanPermission = htmlspecialchars($perm['permission']);
                                            
                                            ?>
                                                <tr>
                                                    <td>
                                                        <?= $cleanRole ?>
                                                    </td>
                                                    <td>
                                                        <?= $cleanResource ?>
                                                    </td>
                                                    <td>
                                                        <?= $cleanPermission ?>
                                                    </td>
                                                </tr>

                                            <?php
                                        }

                                        ?>
                                            <tr>
                                                <td colspan="5">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    Add Permissions
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row justify-content-center">    
                                                                        <div class="card-body">
                                                                            <form action="backendfiles/givepermissions.php" method="post">
                                                                                    <div class="row">
                                                                                        
                                                                                        <div class="col-md-12 mb-3">
                                                                                            <?php
                                                                                                $getRoles = "SELECT role_as FROM users";
                                                                                                $rolesRes = mysqli_execute_query($con, $getRoles);

                                                                                                if(!mysqli_num_rows($rolesRes) > 0){
                                                                                                    ?>
                                                                                                        <h5>
                                                                                                            User Roles Not Yet Defined
                                                                                                        </h5>
                                                                                                    <?php
                                                                                                }else{
                                                                                                    ?>
                                                                                                        <label>Choose Role</label>
                                                                                                        <select name="role" id="" class="form-control">
                                                                                                            <option value="">--Choose Role--</option>
                                                                                                            <?php
                                                                                                                foreach($rolesRes as $role){
                                                                                                                    ?>
                                                                                                                        <option value="<?= $role['role_as'] ?>"><?= $role['role_as'] ?></option>
                                                                                                                    <?php
                                                                                                                }
                                                                                                            ?>
                                                                                                        </select>
                                                                                                    <?php
                                                                                                }
                                                                                            ?>
                                                                                            
                                                                            
                                                                                        </div>
                                                                                        <div class="col-md-6 mb-3">
                                                                                            <?php
                                                                                                $getResources ="SELECT resource_name FROM avail_resources";
                                                                                                $resRes = mysqli_execute_query($con, $getResources);

                                                                                                if(!mysqli_num_rows($resRes) > 0){
                                                                                                    ?>
                                                                                                        <h5>
                                                                                                            Resources Not Yet Loaded
                                                                                                        </h5>
                                                                                                    <?php
                                                                                                }else{
                                                                                                    ?>
                                                                                                    <label>Resource</label>
                                                                                                    <select name="resource" id="" class="form-control">
                                                                                                        <option value="">--SELECT RESOURCE</option>
                                                                                                        <?php
                                                                                                            foreach($resRes as $res){
                                                                                                                $safeRes = htmlspecialchars($res['resource_name']);
                                                                                                                ?>
                                                                                                                    <option value="<?=  $safeRes?>"><?= $safeRes ?></option>
                                                                                                                <?php
                                                                                                            }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                    <?php
                                                                                                }
                                                                                            ?>
                                                                                            
                                                                                        </div>
                                                                                        <div class="col-md-6 mb-3">
                                                                                            
                                                                                            <label>Permission</label>
                                                                                            <select name="permission" id="" class="form-control">
                                                                                                <option value="">--Select Permission--</option>
                                                                                                <option value="Allow">Allow</option>
                                                                                                <option value="Deny">Deny</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        

                                                                                        <div class="col-md-12 mb-3">
                                                                                            <button type="submit" name="give-permits-btn" class="btn btn-primary">Add User</button>
                                                                                        </div>
                                                                    
                                                                                    </div>
                                                                                
                                                                                
                                                                    
                                                                            </form>
                                                                        </div>  
                                                                </div>
                                                            </div>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

include('Includes/scripts.php');
include('Includes/footer.php');
?>