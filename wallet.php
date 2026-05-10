<?php
include('Includes/header.php');
include('Includes/navbar.php');
?>

    <?php
        include('message.php');
    ?>

<div class="dashboard-container">
    <!-- Tshimong Styled Tab Navigation -->
    <div class="tab-box mb-4">
        <button class="tab-btn active" onclick="openTab(event, 'Wallet')">
            <i class="bi bi-wallet2"></i> My Wallet
        </button>
        <button class="tab-btn" onclick="openTab(event, 'WasteForm')">
            <i class="bi bi-recycle"></i> Waste Collection
        </button>
    </div>

    <!-- Tab 1: Wallet Balance -->
    <div id="Wallet" class="tab-content active">
        <div class="wallet-card">
            <div class="wallet-header">
                <span class="text-white-50 small uppercase fw-bold">Available Balance</span>
                <h2 class="text-white mb-0">R0.00</h2>
            </div>
            <div class="wallet-footer d-flex justify-content-between align-items-center">
                <div class="points">
                    <i class="bi bi-star-fill text-warning"></i>
                    <span class="text-white small"></span>
                </div>
            </div>
        </div>
        
        
    </div>

    <!-- Tab 2: Waste Collection Form -->
    <div id="WasteForm" class="tab-content">
        <div class="p-4 bg-white rounded-4 shadow-sm border">
            
            <h3>Exchange Waste for Currency</h3>
            <p class="text-muted">Verify customer SIM before completing the transfer.</p>
            
            <form action="backend/customerverify.php" method="POST">
                <div class="mb-3">
                    <label>Customer Phone Number</label>
                    <input type="text" name="customer-phone" class="form-control" placeholder="+27..." required>
                </div>
                <div class="mb-3">
                    <label>Waste Category</label>
                    <select name="waste-categ" class="form-control">
                        <option value="">--Choose Category--</option>
                        <option value="food_waste">Food Scraps</option>
                        <option value="other_organic_waste">Other Organic Waste(including animal dung)</option>
                        <option value="pet_bottles">Different-sized PET-bottles</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Waste Mass & Quantity</label>
                    <input type="text" name="qty-weight" placeholder="weight in grams and pet bottles in quantity" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Describe Waste</label>
                    <textarea name="description" class="form-control" rows="3" id=""></textarea>                </div>
                <button type="submit" name="verify-trade-btn" class="btn btn-warning w-100">
                    <i class="bi bi-shield-check"></i> Verify & Authorize Trade
                </button>
            </form>
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
