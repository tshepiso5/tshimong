<?php
include('Includes/header.php');
?>

    <h2 class="mt-4">Available Resources</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard/Resources</li>
    </ol>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    include('..\message.php');
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>
                            Available Resources
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        Resource ID
                                    </th>
                                    <th>
                                        Resource
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
                                    $getResources = "SELECT id, resource_name FROM avail_resources";
                                    $resourceRes = mysqli_execute_query($con, $getResources);

                                    if(!mysqli_num_rows($resourceRes) > 0){
                                        ?>
                                            <tr>
                                                <td colspan="4">
                                                    <h5>
                                                        No Resources Defined Yet
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
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
                                                                                <form action="backendfiles/processresources.php" method="post">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 mb-3">
                                                                                                <label>Resource Name</label>
                                                                                                <input type="text" name="resource" required class="form-control">
                                                                                        </div>

                                                                                            <div class="col-md-12 mb-3">
                                                                                                <button type="submit" name="add-resource-btn" class="btn btn-primary">Add Resource</button>
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
                                        foreach($resourceRes as $res){
                                            $cleanResource = htmlspecialchars($res['resource_name']);
                                            $safeID = htmlspecialchars($res['id']);

                                            ?>
                                                <tr>
                                                    <td>
                                                        <?= $safeID ?>
                                                    </td>
                                                    <td>
                                                        <?=  $cleanResource ?>
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                        ?>
                                            <tr>
                                                <td colspan="4">
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
                                                                                <form action="backendfiles/processresources.php" method="post">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 mb-3">
                                                                                                <label>Resource Name</label>
                                                                                                <input type="text" name="resource" required class="form-control">
                                                                                        </div>

                                                                                            <div class="col-md-12 mb-3">
                                                                                                <button type="submit" name="add-resource-btn" class="btn btn-primary">Add Resource</button>
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