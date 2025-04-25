<?php
include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getProd = $mysqli->query("SELECT * FROM `orders` WHERE `orderid` = '$i_id'");
if (!$getProd->num_rows) {
    echo '<p>Error: Order not found.</p>';
    exit;
}
$resProd = $getProd->fetch_assoc();

// Parse orderDetails JSON
$orderDetails = json_decode($resProd['orderDetails'], true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($orderDetails)) {
    // Log the error for debugging (optional)
    error_log("Invalid orderDetails JSON for orderid $i_id: " . $resProd['orderDetails']);
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
    <form id="editOrderForm" autocomplete="off">
        <input type="hidden" id="orderid" value="<?php echo htmlspecialchars($resProd['orderid']); ?>">
        
        <!-- Section: Customer Information -->
        <h5 class="form-section-title">1. Customer Information</h5>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="customerName" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input id="customerName" class="form-control" type="text" placeholder="Enter full name" value="<?php echo htmlspecialchars($resProd['customerName']); ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="customerEmail" class="form-label">Email Address</label>
                <input id="customerEmail" class="form-control" type="email" placeholder="Enter email address" value="<?php echo htmlspecialchars($resProd['customerEmail']); ?>">
            </div>
            <div class="col-12 col-md-6">
                <label for="customerPhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input id="customerPhone" class="form-control" maxlength="10" type="tel" onkeypress="return isNumber(event)" placeholder="Enter phone number" value="<?php echo htmlspecialchars($resProd['customerPhone']); ?>" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="customerAddress" class="form-label">Home Address <span class="text-danger">*</span></label>
                <input id="customerAddress" class="form-control" type="text" placeholder="Enter address" value="<?php echo htmlspecialchars($resProd['customerAddress']); ?>" required>
            </div>
        </div>

        <!-- Section: Delivery Details -->
        <h5 class="form-section-title mt-5">2. Delivery Details</h5>
        <div id="orderItemsContainer">
            <?php
            if (empty($orderDetails)) {
                // Display a single empty order item if no valid Delivery details
                ?>
                <div class="row g-4 order-item align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <select class="form-select product-select" name="products[]" required>
                            <option value="" disabled selected>Select a product</option>
                            <?php
                            $getProducts = $mysqli->query("SELECT `prodName`, `prodPrice` FROM `producelist` WHERE `prodStatus` = 1");
                            while ($product = $getProducts->fetch_assoc()) {
                                echo "<option value='{$product['prodName']}' data-price='{$product['prodPrice']}'>{$product['prodName']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price (GHC)</label>
                        <input type="text" class="form-control price-input" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control quantity-input" name="quantities[]" placeholder="e.g., 2" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Subtotal (GHC)</label>
                        <input type="text" class="form-control subtotal-input" readonly>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100 removeItemBtn d-none">Remove</button>
                    </div>
                </div>
                <?php
            } else {
                foreach ($orderDetails as $index => $item) {
                    $productName = isset($item['product']) ? htmlspecialchars($item['product']) : '';
                    $quantity = isset($item['quantity']) ? htmlspecialchars($item['quantity']) : '';
                    $price = isset($item['price']) ? htmlspecialchars($item['price']) : '';
                    $subtotal = floatval($price) * intval($quantity);
                    ?>
                    <div class="row g-4 order-item align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <select class="form-select product-select" name="products[]" required>
                                <option value="" disabled>Select a product</option>
                                <?php
                                $getProducts = $mysqli->query("SELECT `prodName`, `prodPrice` FROM `producelist` WHERE `prodStatus` = 1");
                                while ($product = $getProducts->fetch_assoc()) {
                                    $selected = ($product['prodName'] == $productName) ? 'selected' : '';
                                    echo "<option value='{$product['prodName']}' data-price='{$product['prodPrice']}' $selected>{$product['prodName']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price (GHC)</label>
                            <input type="text" class="form-control price-input" value="<?php echo number_format(floatval($price), 2); ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control quantity-input" name="quantities[]" placeholder="e.g., 2" min="1" value="<?php echo $quantity; ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal (GHC)</label>
                            <input type="text" class="form-control subtotal-input" value="<?php echo number_format($subtotal, 2); ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger w-100 removeItemBtn <?php echo $index == 0 ? 'd-none' : ''; ?>">Remove</button>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <div class="text-end mt-3">
            <button type="button" id="addOrderItem" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Another Product
            </button>
        </div>

        <!-- Section: Delivery/Pickup -->
        <h5 class="form-section-title mt-5">3. Delivery / Pickup</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Fulfillment Method <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="deliveryMethod" value="Delivery" id="delivery" <?php echo $resProd['deliveryMethod'] == 'Delivery' ? 'checked' : ''; ?> required>
                    <label class="form-check-label" for="delivery">Delivery</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="deliveryMethod" value="Farm Pickup" id="pickup" <?php echo $resProd['deliveryMethod'] == 'Farm Pickup' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="pickup">Farm Pickup</label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Delivery Date <span class="text-danger">*</span></label>
                <input type="text" id="deliveryDate" class="form-control flatpickr" placeholder="Choose date" value="<?php echo htmlspecialchars($resProd['deliveryDate']); ?>" required>
            </div>
        </div>

        <!-- Section: Payment -->
        <h5 class="form-section-title mt-5">4. Payment</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                <select id="paymentStatus" class="form-select" name="paymentStatus" required>
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
                <input type="text" id="totalAmount" class="form-control" value="<?php echo number_format($resProd['totalAmount'], 2); ?>" readonly placeholder="Auto-calculated">
            </div>
        </div>

        <!-- Submit -->
        <div class="d-flex justify-content-center mt-5">
            <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="saveOrder" class="btn btn-primary">
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                Update Order
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    // Initialize Flatpickr
    $('.flatpickr').flatpickr({
        minDate: 'today',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'F j, Y'
    });

    // Function to update disabled options in all product dropdowns
    function updateProductOptions() {
        $('.product-select option').prop('disabled', false);
        const selectedProducts = [];
        $('.product-select').each(function() {
            const selectedValue = $(this).val();
            if (selectedValue) {
                selectedProducts.push(selectedValue);
            }
        });
        $('.product-select').each(function() {
            const $currentSelect = $(this);
            const currentValue = $currentSelect.val();
            selectedProducts.forEach(function(product) {
                if (product !== currentValue) {
                    $currentSelect.find(`option[value="${product}"]`).prop('disabled', true);
                }
            });
        });
    }

    // Update price and subtotal when product or quantity changes
    function updateItemCalculations($item) {
        const $productSelect = $item.findbiscuit('.product-select');
        const $priceInput = $item.find('.price-input');
        const $quantityInput = $item.find('.quantity-input');
        const $subtotalInput = $item.find('.subtotal-input');

        const product = $productSelect.val();
        const quantity = parseInt($quantityInput.val()) || 0;
        const price = parseFloat($productSelect.find('option:selected').data('price')) || 0;

        $priceInput.val(price.toFixed(2));
        $subtotalInput.val((price * quantity).toFixed(2));
        updateTotal();
    }

    // Update total amount
    function updateTotal() {
        let total = 0;
        $('.subtotal-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#totalAmount').val(total.toFixed(2));
    }

    // Product selection change
    $(document).on('change', '.product-select', function() {
        const $item = $(this).closest('.order-item');
        updateItemCalculations($item);
        updateProductOptions();
    });

    // Quantity change
    $(document).on('input', '.quantity-input', function() {
        const $item = $(this).closest('.order-item');
        updateItemCalculations($item);
    });

    // Add Delivery Item
    $('#addOrderItem').click(function () {
        const $item = $('#orderItemsContainer .order-item:first').clone(true);
        $item.find('.product-select').val('');
        $item.find('.quantity-input').val('');
        $item.find('.price-input').val('');
        $item.find('.subtotal-input').val('');
        $item.find('.removeItemBtn').removeClass('d-none');
        $('#orderItemsContainer').append($item);
        updateProductOptions();
    });

    // Remove Order Item
    $(document).on('click', '.removeItemBtn', function () {
        const $item = $(this).closest('.order-item');
        $item.fadeOut(300, function () {
            $item.remove();
            updateTotal();
            updateProductOptions();
        });
    });

    // Form Submission
    $('#saveOrder').click(function () {
        const $submitBtn = $(this);
        $submitBtn.find('.spinner-border').removeClass('d-none');

        // Collect form data
        const formData = {
            orderid: $('#orderid').val(),
            customerName: $('#customerName').val(),
            customerEmail: $('#customerEmail').val(),
            customerPhone: $('#customerPhone').val(),
            customerAddress: $('#customerAddress').val(),
            deliveryMethod: $('input[name="deliveryMethod"]:checked').val(),
            deliveryDate: $('#deliveryDate').val(),
            paymentStatus: $('#paymentStatus').val(),
            totalAmount: $('#totalAmount').val(),
            products: [],
            quantities: []
        };

        // Collect order items
        $('.order-item').each(function() {
            const $item = $(this);
            const product = $item.find('.product-select').val();
            const quantity = $item.find('.quantity-input').val();
            if (product && quantity) {
                formData.products.push(product);
                formData.quantities.push(quantity);
            }
        });

        // Validation
        const validateForm = function(data) {
            let error = '';
            if (!data.customerName) error += 'Please enter full name\n';
            else if (data.customerEmail && !validateEmail(data.customerEmail)) error += 'Please enter a valid email address\n';
            if (!data.customerPhone) error += 'Please enter phone number\n';
            if (!data.customerAddress) error += 'Please enter address\n';
            if (!data.deliveryMethod) error += 'Please select fulfillment method\n';
            if (!data.deliveryDate) error += 'Please select delivery date\n';
            if (!data.paymentStatus) error += 'Please select payment status\n';
            if (data.products.length === 0) error += 'Please add at least one product\n';
            return error;
        };

        const error = validateForm(formData);
        if (error) {
            $submitBtn.find('.spinner-border').addClass('d-none');
            $.notify(error, { className: 'error', position: 'top right' });
            return;
        }

        // Submit form
        const url = 'ajaxscripts/queries/editOrder.php';
        const successCallback = function(response) {
            $submitBtn.find('.spinner-border').addClass('d-none');
            if (response === 'Success') {
                $.notify('Order updated successfully', { className: 'success', position: 'top right' });
                $('#editOrderForm')[0].reset();
                const $firstItem = $('#orderItemsContainer .order-item:first').clone(true);
                $firstItem.find('.product-select').val('');
                $firstItem.find('.quantity-input').val('');
                $firstItem.find('.price-input').val('');
                $firstItem.find('.subtotal-input').val('');
                $firstItem.find('.removeItemBtn').addClass('d-none');
                $('#orderItemsContainer').html($firstItem);
                $('#editOrderModal').modal('hide');
                loadPage('ajaxscripts/tables/orders.php', function(response) {
                    $('#pageTable').html(response);
                });
                updateProductOptions();
            } else {
                $.notify(response, { className: 'error', position: 'top right' });
            }
        };

        saveForm(formData, url, successCallback);
    });

    function validateEmail(email) {
        const re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    function isNumber(evt) {
        const charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    updateProductOptions();
});
</script>