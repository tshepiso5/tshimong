<?php
include('Includes/header.php');

?>
    <h2 class="mt-4">Permitted Users</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard/Users</li>
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
                            Users
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        UserID
                                    </th>
                                    <th>
                                        Role
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getUsers = "SELECT user_id, role_as FROM users";
                                    $usersRes = mysqli_execute_query($con, $getUsers);

                                    if(!mysqli_num_rows($usersRes) > 0){
                                       
                                            ?>
                                                <tr>
                                                    <td colspan="2">
                                                        <h5>
                                                            No users Defined Yet
                                                        </h5>

                                                    </td>
                                                    
                                                    
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        Add Users
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
                                                                                <form action="backendfiles/defineusers.php" method="post">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 mb-3">
                                                                                                <label>User Role</label>
                                                                                                <input type="text" name="role" required class="form-control">
                                                                                        </div>

                                                                                            <div class="col-md-12 mb-3">
                                                                                                <button type="submit" name="add-user-btn" class="btn btn-primary">Add User</button>
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
                                        foreach($usersRes as $res){
                                            $safeID = htmlspecialchars($res['user_id']);
                                            $safeRole = htmlspecialchars($res['role_as']);

                                            ?>
                                                <tr>
                                                    <td>
                                                        <?= $safeID ?>
                                                    </td>
                                                    <td>
                                                        <?= $safeRole ?>
                                                    </td>
                                                </tr>
                                                

                                            <?php
                                        }

                                        ?>
                                            <tr>
                                                    <td colspan="2">
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        Add Users
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
                                                                                <form action="backendfiles/defineusers.php" method="post">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 mb-3">
                                                                                                <label>User Role</label>
                                                                                                <input type="text" name="role" required class="form-control">
                                                                                        </div>

                                                                                            <div class="col-md-12 mb-3">
                                                                                                <button type="submit" name="add-user-btn" class="btn btn-primary">Add User</button>
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
                                <tr>

                                </tr>
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