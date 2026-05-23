<?php
include('Includes/header.php');
include('Includes/navbar.php');
$farmerID = htmlspecialchars($_SESSION['auth_user']['id']);
?>

<?php
    include('message.php');
?>

<div class="dashboard-container">
    
    <div class="tab-box">
        <button class="tab-btn active" onclick="openTab(event, 'HarvestLog')">
            <i class="bi bi-basket"></i> Financials
        </button>
        <button class="tab-btn" onclick="openTab(event, 'FieldNotes')">
            <i class="bi bi-journal-text"></i> Fundamentals
        </button>
    </div>

    <!-- START HARVEST LOG TAB -->
    <div id="HarvestLog" class="tab-content active">
        <?php
            $getCosts = "SELECT cost_category, item, qty, unit_price FROM farmer_input_costs WHERE farmer_id = ?";
            $costRes = mysqli_execute_query($con, $getCosts, [$farmerID]);

            // If no costs are found, show the initial input form
            if (mysqli_num_rows($costRes) == 0) {
                ?>
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
                    <div class="col-md-12 mb-3">
                        <label>Quantity</label>
                        <input type="number" name="qty" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Unit Price</label>
                        <input type="text" name="unit-price" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="submit" name="costs-btn" class="btn btn-primary w-100">Submit Costs</button>
                    </div>   
                </form>
                <?php
            } else {
                // Costs exist, render the clean data table layout
                ?>
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
                                <?php 
                                foreach ($costRes as $cost) {
                                    $sanCat   = htmlspecialchars($cost['cost_category']);
                                    $sanItem  = htmlspecialchars($cost['item']);
                                    $sanQty   = (float)$cost['qty'];
                                    $sanPrice = (float)$cost['unit_price'];
                                    $total    = $sanQty * $sanPrice;
                                    ?>
                                    <tr>
                                        <td><?= $sanCat ?></td>
                                        <td class="fw-bold"><?= $sanItem ?></td>
                                        <td><?= $sanQty ?></td>
                                        <td>R <?= number_format($sanPrice, 2) ?></td>
                                        <td class="text-primary fw-bold">R <?= number_format($total, 2) ?></td>
                                    </tr>
                                    <?php 
                                } 
                                ?>
                                <tr>
                                    <td colspan="5">
                                        <!-- Trigger Button -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#costModal">
                                            <i class="bi bi-dash-circle-fill me-2"></i> Record Cost
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            }
        ?>

        <!-- INVENTORY SECTION -->
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

                if (mysqli_num_rows($inventoriesRes) == 0) {
                    ?>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#inventoryModal">
                            <i class="bi bi-plus-circle-fill me-2"></i> Add Inventory
                        </button>
                    </div>
                    <?php
                } else {
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
                                <?php 
                                foreach ($inventoriesRes as $res) {
                                    $sanCrop  = htmlspecialchars($res['crop']);
                                    $sanCateg = htmlspecialchars($res['crop_growth_stage']);
                                    $sanQtyy  = (int)$res['qty'];
                                    $sanDate  = htmlspecialchars($res['date_planted']);
                                    $sanStat  = (int)$res['avail_status'];
                                    ?>
                                    <tr>
                                        <td><?= $sanCrop ?></td>
                                        <td class="fw-bold"><?= $sanCateg ?></td>
                                        <td><?= $sanQtyy ?></td>
                                        <td><?= $sanDate ?></td>
                                        <td class="text-primary fw-bold"><?= $sanStat == 1 ? 'Ready for Market' : 'In Progress' ?></td>
                                    </tr>
                                    <?php 
                                } 
                                ?>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#inventoryModal">
                                            <i class="bi bi-plus-circle-fill me-2"></i> Add Inventory
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
    <!-- END HARVEST LOG TAB -->

    <!-- =========================================================
        GLOBAL FIELD NOTES TAB (Unified wrapper targeting openTab JS logic)
    ========================================================= -->
    <div id="FieldNotes" class="tab-content">
        <?php
            $getSoilData = "SELECT ph_balance, soil_texture, bio_activity_count FROM land_data WHERE farmer_id = ?";
            $soilDataRes = mysqli_execute_query($con, $getSoilData, [$farmerID]);

            if (mysqli_num_rows($soilDataRes) == 0) {
                // STATE A: No data found -> Show the registration form
                ?>
                <h3>What Type of Land Do You Farm On?</h3>
                <p class="text-muted">If You Cannot Test Soil By Yourself, Please Contact the Dirty Farm Team</p>

                <form action="backend/soilqual.php" method="POST" enctype="multipart/form-data">
                    <div class="col-md-12 mb-3">
                        <label>pH Levels of the Soil</label>
                        <select name="ph-levels" class="form-control">
                            <option value="">--Choose pH Level of Soil--</option>
                            <option value="acidic">Acidic</option>
                            <option value="slight_acid">Slightly Acidic</option>
                            <option value="neutral">Neutral</option>
                            <option value="alkaline">Alkaline</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Soil Colour</label>
                        <input type="text" name="soil-colour" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Soil Texture</label>
                        <select name="texture" class="form-control">
                            <option value="">--Choose Structure</option>
                            <option value="sandy">Sandy</option>
                            <option value="clay">Clay</option>
                            <option value="loamy">Loamy</option>
                        </select>
                    </div>
                     <div class="col-md-12 mb-3">
                        <label>Earth Worm Count Per Sqr-ft</label>
                        <input type="number" name="worms" class="form-control">
                    </div>
                     <div class="col-md-12 mb-3">
                        <label>Soil Sample Image</label>
                        <input type="file" name="soil-image" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type="submit" name="soil-data-btn" class="btn btn-success w-100">Submit Soil Details</button>
                    </div>
                </form>
                <?php
            } else {
                // STATE B: Data exists -> Show the registered details display
                $data = mysqli_fetch_assoc($soilDataRes);
                ?>
                
                <!-- CARD A: SOIL QUALITY -->
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4" style="background: #ffffff;">
                    <div class="p-3 border-bottom d-flex align-items-center gap-2" style="background-color: #f8f9fa;">
                        <i class="bi bi-moisture text-success"></i>
                        <span class="small fw-bold text-muted text-uppercase tracking-wider">Soil Quality & Substrate Health</span>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-4 text-center border-end">
                                <div class="avatar-circle mb-2 mx-auto d-flex align-items-center justify-content-center rounded-circle" 
                                    style="width: 48px; height: 48px; background-color: #fff3e0; color: #e65100;">
                                    <i class="bi bi-droplet-half fs-5"></i>
                                </div>
                                <span class="text-muted d-block font-monospace small" style="font-size: 0.7rem;">pH Balance</span>
                                <strong class="text-dark d-block fs-6"><?= htmlspecialchars($data['ph_balance'] ?? 'N/A'); ?></strong>
                            </div>

                            <div class="col-4 text-center border-end">
                                <div class="avatar-circle mb-2 mx-auto d-flex align-items-center justify-content-center rounded-circle" 
                                    style="width: 48px; height: 48px; background-color: #efebe9; color: #4e342e;">
                                    <i class="bi bi-layers-half fs-5"></i>
                                </div>
                                <span class="text-muted d-block font-monospace small" style="font-size: 0.7rem;">Texture Profile</span>
                                <strong class="text-dark d-block fs-6" style="margin-top: 2px;"><?= htmlspecialchars($data['soil_texture'] ?? 'N/A'); ?></strong>
                            </div>

                            <div class="col-4 text-center">
                                <div class="avatar-circle mb-2 mx-auto d-flex align-items-center justify-content-center rounded-circle" 
                                    style="width: 48px; height: 48px; background-color: #e8f5e9; color: #2e7d32;">
                                    <i class="bi bi-bug fs-5"></i>
                                </div>
                                <span class="text-muted d-block font-monospace small" style="font-size: 0.7rem;">Biological Index</span>
                                <strong class="text-dark d-block fs-5"><?= intval($data['bio_activity_count'] ?? 0); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD B: OPERATIONAL CONTROLS -->
                <div class="card shadow-sm border-0 rounded-4 p-4 mb-5" style="background: #ffffff;">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-sliders2 me-2 text-success"></i>Operational Controls</h5>
                    <p class="text-muted small mb-4">Manage your marketplace distribution channel and view overall agricultural yield configurations instantly.</p>
                    
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-success btn-lg w-100 h-100 py-3 rounded-3 shadow-sm fw-bold d-flex flex-column align-items-center justify-content-center gap-2" 
                                    data-bs-toggle="modal" data-bs-target="#listProduceModal">
                                <i class="bi bi-plus-circle-fill fs-3 text-warning"></i>
                                <span>List Fresh Produce</span>
                            </button>
                        </div>
                        
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-outline-dark btn-lg w-100 h-100 py-3 rounded-3 shadow-sm fw-bold d-flex flex-column align-items-center justify-content-center gap-2" 
                                    data-bs-toggle="modal" data-bs-target="#farmPerformanceModal" style="border-width: 2px;">
                                <i class="bi bi-graph-up-arrow fs-3 text-success"></i>
                                <span>Sales & Performance</span>
                            </button>
                        </div> 
                    </div>
                </div>
                <?php
            }
        ?>
    </div> 
    <!-- END FIELD NOTES TAB -->

</div> <!-- CLOSES GLOBAL DASHBOARD CONTAINER -->


<!-- =========================================================
    MODAL LAYERS (Placed cleanly at document root level)
========================================================= -->

<!-- Farm Performance Insights Modal -->
<div class="modal fade" id="farmPerformanceModal" tabindex="-1" aria-labelledby="farmPerformanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> 
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white p-3">
                <h5 class="modal-title fw-bold" id="farmPerformanceModalLabel"><i class="bi bi-graph-up-arrow me-2"></i>Financial Performance Insights</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 rounded-4 mb-4" style="background: linear-gradient(135deg, #1b5e20, #2e7d32); color: #ffffff;">
                    <span class="small font-monospace uppercase d-block text-white-50">Aggregate Valuation Revenue</span>
                    <h2 class="fw-bold tracking-tight mb-1">R <?= number_format($performance['total_revenue'] ?? 0.00, 2); ?></h2>
                </div>
            </div>
            <div class="modal-footer bg-light border-top p-2">
                <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Close Dashboard View</button>
            </div>
        </div>
    </div>
</div>

<!-- Cost Input Modal -->
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
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Item / Description</label>
                        <input type="text" name="item" class="form-control tshimong-input-red" placeholder="e.g. Organic Compost" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Quantity</label>
                            <input type="number" name="qty" class="form-control tshimong-input-red" placeholder="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Unit Price (R)</label>
                            <input type="text" name="unit-price" class="form-control tshimong-input-red" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="costs-btn" class="btn btn-tshimong-submit-red w-100 py-2 fw-bold">Submit Costs</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                            <label class="form-label small fw-semibold text-muted">Category / Growth Stage</label>
                            <select name="growth-stage" class="form-select tshimong-input">
                                <option value="">--Select Growth Stage--</option>
                                <option value="germination">Germination</option>
                                <option value="seedling">Seedlings </option>
                                <option value="vegetative">Vegetative</option>
                                <option value="flowering">Production</option>
                                <option value="senescene">Maturity or Bolting</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Crop Quantity</label>
                            <input type="number" name="quantity" class="form-control tshimong-input" placeholder="0">
                        </div> 
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Date Planted</label>
                            <input type="date" name="planting-date" class="form-control tshimong-input">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Inventory Image</label>
                            <input type="file" name="inventory-img" class="form-control tshimong-input">
                        </div>
                        <div class="col-12 mt-3">
                            <div class="form-check custom-checkbox">
                                <input class="form-check-input" type="checkbox" id="notifyStock" name="avail-status">
                                <label class="form-check-label text-muted small" for="notifyStock">Crops Are Ready For Market</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" name="inventory-btn" class="btn btn-tshimong-primary w-100 py-2 fw-bold">Save to Inventory</button>
                        <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- List Produce Modal -->
<div class="modal fade" id="listProduceModal" tabindex="-1" aria-labelledby="listProduceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header text-white p-3" style="background: linear-gradient(135deg, #1b5e20, #2e7d32);">
                <h5 class="modal-title fw-bold" id="listProduceModalLabel">List Fresh Produce</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Drop produce listing content here safely -->
                <form action="backend/listproducts.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="farmer-id" value="<?= htmlspecialchars($_SESSION['auth_user']['id']); ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Crop / Product Name</label>
                        <select name="product-name" id="" class="form-control mb-3">
                            <option value="">--Select Available Products--</option>
                             <?php
                            $availProds = 1;
                            $getAvailableProds = "SELECT crop FROM inventory WHERE farmer_id = ? AND avail_status = ?";
                            $availProdsRes = mysqli_execute_query($con, $getAvailableProds, [$farmerID, $availProds]);

                            if(!mysqli_num_rows($availProdsRes) > 0){
                                ?>
                                <option value="">--No Available Products In Inventory--</option>
                                <?php
                            }else{
                                foreach($availProdsRes as $prod){
                                    
                                    ?>
                                    <option value="<?= $prod['crop'] ?>"><?= $prod['crop'] ?></option>
                                    <?php
                                }
                            }
                        ?>

                        </select>
                       
                       
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Product/Crop Details</label>
                        <input type="text" name="product-details" class="form-control form-control-lg fs-6" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6 mb-3">
                        <label class="form-label small fw-semibold text-muted">Product URL</label>
                        <input type="text" name="product-slug" class="form-control form-control-lg fs-6" placeholder="e.g. mustard-spinach" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-semibold text-muted">Product Category</label>
                        <select name="product-category" id="" class="form-control">
                            <option value="">Select Category</option>
                            <option value="fresh-produce">Fresh Produce</option>
                            <option value="dried">Dried Produce</option>
                            <option value="processed">Processed Food</option>
                        </select>
                    </div>

                    </div>
                    

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Price per Unit (ZAR)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">R</span>
                                <input type="number" step="0.01" name="selling-price" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">Quantity Available</label>
                            <input type="number" step="0.1" name="selling_qty" class="form-control" placeholder="e.g., 50" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-muted">Harvest Visual Image</label>
                        <input type="file" name="product-img" class="form-control" accept="image/*" required>
                    </div>

                    <button type="submit" name="submit-listing-btn" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm fs-6 fw-bold">
                        <i class="bi bi-cloud-upload me-2"></i>List to Marketplace
                    </button>
                </form>
            </div>
        </div>
    </div>
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