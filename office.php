<?php
include('Includes/header.php');
include('Includes/navbar.php');
$farmerID = htmlspecialchars($_SESSION['auth_user']['id']);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php
            include('message.php');
            ?>
        </div>
    </div>
</div>

<div class="dashboard-container">
    <div class="tab-box">
        <button class="tab-btn active" onclick="openTab(event, 'HarvestLog')">
            <i class="bi bi-basket"></i> Financials
        </button>
        <button class="tab-btn" onclick="openTab(event, 'FieldNotes')">
            <i class="bi bi-journal-text"></i> Soil Quality
        </button>
    </div>
    <?php
        $getCosts = "SELECT cost_category, item, qty, unit_price FROM farmer_input_costs WHERE farmer_id = ?";
        $costRes = mysqli_execute_query($con, $getCosts, [$farmerID]);

        if(!mysqli_num_rows($costRes) > 0){
            ?>
                <div id="HarvestLog" class="tab-content active">
                    <h3>Record Cost Items For the Farm</h3>
                    <p class="text-muted">Record the costs incurred by the farm</p>
                    <form action="backend/submitcosts.php" method="POST">
                        <div class="col-md-12 mb-3">
                            <label>Cost Category</label>
                            <select name="cost-categ" class="form-control">
                                <option value="">--Select Cost Category</option>
                                <option value="seeds">Seeds</option>
                                <option value="fertilizer">Fertilizer</option>
                                <option value="water">Water</option>
                                <option value="pest_management">Pest Management</option>
                                <option value="equipment">Equipment</option>
                                <option value="labour">Labour</option>
                                <option value="rent">Rent</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Item</label>
                            <input type="text" name="item" class="form-control">
                        </div>
                        <div class="col-md-12-mb-3">
                            <label >Quantity</label>
                            <input type="number" name="qty" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label >Unit Price</label>
                            <input type="text" name="unit-price" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                        <button type="submit" name="costs-btn" class="btn btn-primary w-100">Submit Costs</button>
                        </div>  
                        
                    </form>
                </div>
            <?php
        }else{
            foreach($costRes as $cost){
                $sanCat = htmlspecialchars($cost['cost_category']);
                $sanQty = htmlspecialchars($cost['qty']);
                $sanItem = htmlspecialchars($cost['item']);
                $sanPrice = htmlspecialchars($cost['unit_price']);

                 ?>
                <div id="HarvestLog" class="tab-content active">
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h3 class="mb-0">Financial Overview</h3>
                                <p class="text-muted small">A summary of all farm input costs and expenditures.</p>
                            </div>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Export</button>
                        </div>

                        <div class="table-responsive custom-table-wrapper mb-4">
                            <table class="table custom-tshimong-table">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Cost Item</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($costRes as $cost): 
                                        $sanCat = htmlspecialchars($cost['cost_category']);
                                        $sanQty = (float)$cost['qty'];
                                        $sanItem = htmlspecialchars($cost['item']);
                                        $sanPrice = (float)$cost['unit_price'];
                                        $total = $sanQty * $sanPrice;
                                    ?>
                                        <tr>
                                            <td>
                                                <?= $sanCat ?>    
                                            </td>
                                            <td class="fw-bold"><?= $sanItem ?></td>
                                            <td><?= $sanQty ?></td>
                                            <td>R <?= number_format($sanPrice, 2) ?></td>
                                            <td class="text-primary fw-bold">R <?= number_format($total, 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                        <tr>
                                            <td colspan="5">
                                                <!-- Trigger Button -->
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#costModal" focus="false">                                                    <i class="bi bi-dash-circle-fill me-2"></i> Record Cost
                                                </button>
                                                <!-- Cost Modal -->
                                               
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                   </div>
                    <div class="row">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="mt-2">
                                <h3 class="mb-0">Inventory Management</h3>
                                <p class="text-muted small">A summary of inventories the farm has</p>
                            </div>
                        </div>
                        <?php
                            $getInventories = "SELECT crop, crop_growth_stage, qty, date_planted, avail_status FROM inventory WHERE farmer_id = ?";
                            $inventoriesRes = mysqli_execute_query($con, $getInventories, [$farmerID]);

                            if(!mysqli_num_rows($inventoriesRes) > 0){
                                ?>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#inventoryModal">
                                            <i class="bi bi-plus-circle-fill me-2"></i> Add Inventory
                                        </button>

                                        <!-- Inventory Modal -->
                                        <div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold" id="inventoryModalLabel" style="color: #2d3436;">New Inventory Item</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <form action="backend/addinventory.php" method="POST" enctype="multipart/form-data">
                                                            <div class="row g-3">
                                                                
                                                                <div class="col-md-6">
                                                                    <label class="form-label small fw-semibold text-muted">Crop</label>
                                                                    <input type="text" name="crop" class="form-control tshimong-input" required>
                                                                </div>
                                                                
                                                                
                                                                <div class="col-md-6">
                                                                    <label class="form-label small fw-semibold text-muted">Category</label>
                                                                    <select name="growth-stage" class="form-select tshimong-input">
                                                                        <option value="">--Select Growth Stage--</option>
                                                                        <option value="germination">Germination</option>
                                                                        <option value="seedling">Seedlings </option>
                                                                        <option value="vegetative">Vegetative</option>
                                                                        <option value="flowering">Production</option>
                                                                        <option value="senescene">Maturity or Bolting</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Field 3: Quantity -->
                                                                <div class="col-md-6">
                                                                    <label class="form-label small fw-semibold text-muted">Crop Quantity</label>
                                                                    <input type="number" name="quantity" class="form-control tshimong-input" placeholder="0">
                                                                </div> 

                                                                <!-- Field 6: Expiry Date -->
                                                                <div class="col-md-6">
                                                                    <label class="form-label small fw-semibold text-muted">Date Planted</label>
                                                                    <input type="date" name="planting-date" class="form-control tshimong-input">
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <label class="form-label small fw-semibold text-muted">Inventory Image</label>
                                                                    <input type="file" name="inventory-img" class="form-control tshimong-input">
                                                                </div>

                                                                <!-- Checkbox -->
                                                                <div class="col-12 mt-3">
                                                                    <div class="form-check custom-checkbox">
                                                                        <input class="form-check-input" type="checkbox" id="notifyStock" name="avail-status">
                                                                        <label class="form-check-label text-muted small" for="notifyStock">
                                                                            Crops Are Ready For Market
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mt-4 pt-3 border-top d-flex gap-2">
                                                                <button type="submit" name="iventory-btn" class="btn btn-tshimong-primary w-100 py-2 fw-bold">Save to Inventory</button>
                                                                <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php
                            }else{

                                ?>
                                    <div class="table-responsive custom-table-wrapper">
                                        <table class="table custom-tshimong-table">
                                            <thead>
                                                <tr>
                                                    <th>Crop</th>
                                                    <th>Growth Stage</th>
                                                    <th>Qty</th>
                                                    <th>Date Planted</th>
                                                    <th>Availability Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($inventoriesRes as $res): 
                                                    $sanCrop = htmlspecialchars($res['crop']);
                                                    $sanCateg = htmlspecialchars($res['crop_growth_stage']);
                                                    $sanQtyy = (int)$res['qty'];
                                                    $sanDate = htmlspecialchars($res['date_planted']);
                                                    $sanStat = (int)$res['avail_status'];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <?= $sanCrop ?>    
                                                        </td>
                                                        <td class="fw-bold"><?= $sanCateg ?></td>
                                                        <td><?= $sanQtyy ?></td>
                                                        <td <?= $sanDate ?></td>
                                                        <td class="text-primary fw-bold"><?= $sanStat ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php

                            }
 
                        ?>
                    
                    
                    
                    </div>
                </div>
                <?php


            }
           
        }
    ?>

    
 <div class="modal fade" id="costModal" tabindex="-1" aria-labelledby="costModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                                            <div class="modal-header border-0 pb-0 pt-4 px-4">
                                                                <h5 class="modal-title fw-bold" id="costModalLabel" style="color: #444;">Record Input Cost</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            
                                                            <div class="modal-body p-4">
                                                                <p class="text-muted small mb-4">Log your farm expenditures to keep your financials accurate.</p>
                                                                
                                                                <form action="backend/submitcosts.php" method="POST">
                                                                    <!-- Cost Category -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold text-secondary">Cost Category</label>
                                                                        <select name="cost-categ" class="form-select tshimong-input-red" required>
                                                                            <option value="">--Select Cost Category</option>
                                                                            <option value="seeds">Seeds</option>
                                                                            <option value="fertilizer">Fertilizer</option>
                                                                            <option value="water">Water</option>
                                                                            <option value="pest_management">Pest Management</option>
                                                                            <option value="equipment">Equipment</option>
                                                                            <option value="labour">Labour</option>
                                                                            <option value="rent">Rent</option>
                                                                        </select>
                                                                    </div>

                                                                    <!-- Item Name -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label small fw-bold text-secondary">Item / Description</label>
                                                                        <input type="text" name="item" class="form-control tshimong-input-red" placeholder="e.g. Organic Compost" required>
                                                                    </div>

                                                                    <div class="row">
                                                                        <!-- Quantity -->
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label small fw-bold text-secondary">Quantity</label>
                                                                            <input type="number" name="qty" class="form-control tshimong-input-red" placeholder="0" required>
                                                                        </div>

                                                                        <!-- Unit Price -->
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label small fw-bold text-secondary">Unit Price (R)</label>
                                                                            <input type="text" name="unit-price" class="form-control tshimong-input-red" placeholder="0.00" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Submit Button -->
                                                                    <div class="mt-4">
                                                                        <button type="submit" name="costs-btn" class="btn btn-tshimong-submit-red w-100 py-2 fw-bold">
                                                                            Submit Costs
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    <?php
        
        $getSoilData = "SELECT ph_balance, soil_texture, bio_activity_count FROM land_data WHERE farmer_id = ?";
        $soilDataRes = mysqli_execute_query($con, $getSoilData, [$farmerID]);

        if(!mysqli_num_rows($soilDataRes) > 0){
            ?>
            <div id="FieldNotes" class="tab-content">
                <h3>What Type of Land Do You Farm On ?</h3>
                <p class="text-muted">If You Cannot Test Soil By Yourself, Please Contact the Dirty Farm Team</p>

                <form action="backend/soilqual.php" method="post" enctype="multipart/form-data">
                    <div class="col-md-12 mb-3">
                        <label>pH Levels of the Soil</label>
                        <select name="ph-levels" id="" class="form-control">
                            <option value="">--Choose pH Level of Soil--</option>
                            <option value="acidic">Acidic</option>
                            <option value="slight_acid">Slightly Acidic</option>
                            <option value="neutral">Neutral</option>
                            <option value="alkaline">Alkaline</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label >Soil Colour</label>
                        <input type="text" name="soil-colour" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Soil Texture</label>
                        <select name="texture" id="" class="form-control">
                            <option value="">--Choose Structure</option>
                            <option value="sandy">Sandy</option>
                            <option value="clay">Clay</option>
                            <option value="loamy">Loamy</option>
                            
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label >Earthworms per Sqr-foot</label>
                        <input type="number" name="worms" class="form-control">

                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Sample Image</label>
                        <input type="file" name="soil-image" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="submit" name="soil-data-btn" class="btn btn-secondary w-100">Save Notes</button>
                    </div>
                </form>
                
            </div>
            <?php
        }else{
           foreach($soilDataRes as $data){
            $sanPh= htmlspecialchars($data['ph_balance'], ENT_QUOTES, 'UTF-8');
            $sanTextr = htmlspecialchars($data['soil_texture'], ENT_QUOTES, 'utf-8');
            $sanWorms = htmlspecialchars($data['bio_activity_count'], ENT_QUOTES, 'UTF-8');

            ?>
                <div id="FieldNotes" class="tab-content">
                    
                <h3>This is the Type of Soil You Have at Your Farm</h3>
                
                <div class="col-md-12 mb-3">
                <p class="text-muted">The pH levels at your farm are: <?= $sanPh ?></p>

                </div>
                <div class="col-md-12 mb-3">
                <p class="text-muted">The soil at your farm has a texture that is : <?= $sanTextr ?></p>

                </div>
                <div class="col-md-12 mb-3">
                <p class="text-muted">You can expect to find around : <?= $sanWorms ?> earthworms per square-foot, living in the soil, on your farm</p>

                </div>

                
                
            </div>
            <?php
           }
        }
    ?>
    
</div>

<script>
    function openTab(evt, tabName) {
    // 1. Hide all tab content
    let contents = document.getElementsByClassName("tab-content");
    for (let i = 0; i < contents.length; i++) {
        contents[i].classList.remove("active");
    }

    // 2. Remove "active" class from all buttons
    let buttons = document.getElementsByClassName("tab-btn");
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("active");
    }

    // 3. Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}
</script>

<?php
include('Includes/bottomnav.php');
include('Includes/scripts.php');
?>