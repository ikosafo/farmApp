<?php
include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getProd = $mysqli->query("SELECT * FROM `orders` WHERE `orderId` = '$i_id'");
$resProd = $getProd->fetch_assoc();

// Parse orderDetails JSON
$orderDetails = json_decode($resProd['orderDetails'], true);
if (!is_array($orderDetails)) {
    $orderDetails = [];
}
?>

<style>
    .form-section-title {
        color: #2c3e50;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .order-item {
        padding: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
</style>

<div class="form-container">
    <form id="viewOrderForm" autocomplete="off">
        <!-- Section: Customer Information -->
        <h5 class="form-section-title">1. Customer Information</h5>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="fullName" class="form-label">Full Name</label>
                <input id="fullName" class="form-control" type="text" value="<?php echo htmlspecialchars($resProd['customerName']); ?>" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-control" type="email" value="<?php echo htmlspecialchars($resProd['customerEmail']); ?>" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input id="phoneNumber" class="form-control" type="tel" value="<?php echo htmlspecialchars($resProd['customerPhone']); ?>" disabled>
            </div>
            <div class="col-12 col-md-6">
                <label for="address" class="form-label">Home Address</label>
                <input id="address" class="form-control" type="text" value="<?php echo htmlspecialchars($resProd['customerAddress'] ?? ''); ?>" disabled>
            </div>
        </div>

        <!-- Section: Delivery Details -->
        <h5 class="form-section-title mt-5">2. Delivery Details</h5>
        <div id="orderItemsContainer">
            <?php
            if (empty($orderDetails)) {
                echo '<p>No order items available.</p>';
            } else {
                foreach ($orderDetails as $productName => $quantity) {
                    // Fetch product price from producelist
                    $productQuery = $mysqli->query("SELECT `prodPrice` FROM `producelist` WHERE `prodName` = '" . mysqli_real_escape_string($mysqli, $productName) . "' AND `prodStatus` = 1");
                    $product = $productQuery->fetch_assoc();
                    $price = $product ? floatval($product['prodPrice']) : 0;
                    $subtotal = $price * $quantity;
                    ?>
                    <div class="row g-4 order-item align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($productName); ?>" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price (GHC)</label>
                            <input type="text" class="form-control price-input" value="<?php echo number_format($price, 2); ?>" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control quantity-input" value="<?php echo $quantity; ?>" disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal (GHC)</label>
                            <input type="text" class="form-control subtotal-input" value="<?php echo number_format($subtotal, 2); ?>" disabled>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Section: Delivery/Pickup -->
        <h5 class="form-section-title mt-5">3. Delivery / Pickup</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Fulfillment Method</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Delivery" id="delivery" <?php echo $resProd['deliveryMethod'] == 'Delivery' ? 'checked' : ''; ?> disabled>
                    <label class="form-check-label" for="delivery">Delivery</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Farm Pickup" id="pickup" <?php echo $resProd['deliveryMethod'] == 'Farm Pickup' ? 'checked' : ''; ?> disabled>
                    <label class="form-check-label" for="pickup">Farm Pickup</label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Delivery Date</label>
                <input type="text" id="preferredDate" class="form-control" value="<?php echo htmlspecialchars($resProd['deliveryDate']); ?>" disabled>
            </div>
        </div>

        <!-- Section: Payment -->
        <h5 class="form-section-title mt-5">4. Payment</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Payment Method</label>
                <select id="paymentStatus" class="form-select" name="paymentStatus" required disabled>
                    <option value="" disabled selected>Select Payment Status</option>
                    <option value="Part Payment" <?php echo $resProd['paymentStatus'] == 'Part Payment' ? 'selected' : ''; ?>>Part Payment</option>
                    <option value="Full Payment" <?php echo $resProd['paymentStatus'] == 'Full Payment' ? 'selected' : ''; ?>>Full Payment</option>
                    <option value="Overpaid" <?php echo $resProd['paymentStatus'] == 'Overpaid' ? 'selected' : ''; ?>>Overpaid</option>
                    <option value="Pending" <?php echo $resProd['paymentStatus'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Refunded" <?php echo $resProd['paymentStatus'] == 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
                    <option value="On Hold" <?php echo $resProd['paymentStatus'] == 'On Hold' ? 'selected' : ''; ?>>On Hold</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Total Amount (GHC)</label>
                <input type="text" id="totalAmount" class="form-control" value="<?php echo number_format($resProd['totalAmount'], 2); ?>" disabled>
            </div>
        </div>

        <!-- Submit -->
        <div class="d-flex justify-content-center mt-5">
            <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Close</button>
        </div>
    </form>
</div>