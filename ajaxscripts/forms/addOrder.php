<?php include('../../config.php'); ?>

<style>
    .form-section-title {
        color: #2c3e50;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .form-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
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
    <form id="addOrderForm" autocomplete="off">
        <!-- Section: Customer Information -->
        <h5 class="form-section-title">1. Customer Information</h5>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input id="fullName" class="form-control" type="text" placeholder="Enter full name" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-control" type="email" placeholder="Enter email address">
            </div>
            <div class="col-12 col-md-6">
                <label for="phoneNumber" class="form-label">Phone Number</label>
                <input id="phoneNumber" class="form-control" maxlength="10" type="tel" onkeypress="return isNumber(event)" placeholder="Enter phone number" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                <input id="address" class="form-control" type="text" placeholder="Enter delivery or supply address" required>
            </div>
        </div>

        <!-- Section: Order Details -->
        <h5 class="form-section-title mt-5">2. Order Details</h5>
        <div id="orderItemsContainer">
            <div class="row g-4 order-item align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <select class="form-select product-select" name="products[]" required>
                        <option value="" disabled selected>Select a product</option>
                        <?php
                        $getProducts = $mysqli->query("SELECT `prodName`, `prodPrice`, `prodQuantity` FROM `producelist` WHERE `prodStatus` = 1 AND `prodQuantity` > 0");
                        while ($product = $getProducts->fetch_assoc()) {
                            echo "<option value='{$product['prodName']}' data-price='{$product['prodPrice']}' data-quantity='{$product['prodQuantity']}'>{$product['prodName']} (Available: {$product['prodQuantity']})</option>";
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
        </div>

        <div class="text-end mt-3">
            <button type="button" id="addOrderItem" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Another Product
            </button>
        </div>

        <!-- Section: Fulfillment Method -->
        <h5 class="form-section-title mt-5">3. Fulfillment Method</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Fulfillment Type <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Delivery" id="delivery" checked required>
                    <label class="form-check-label" for="delivery">Delivery to Customer</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Supply" id="supply">
                    <label class="form-check-label" for="supply">Supply Pickup at Farm</label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Preferred Date <span class="text-danger">*</span></label>
                <input type="text" id="preferredDate" class="form-control flatpickr" placeholder="Choose delivery or pickup date" required>
            </div>
        </div>

        <!-- Section: Payment -->
        <h5 class="form-section-title mt-5">4. Payment</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                <select id="paymentStatus" class="form-select" name="paymentStatus" required>
                    <option value="" disabled selected>Select payment status</option>
                    <option value="Part Payment">Part Payment</option>
                    <option value="Full Payment">Full Payment</option>
                    <option value="Overpaid">Overpaid</option>
                    <option value="Pending">Pending</option>
                    <option value="Refunded">Refunded</option>
                    <option value="On Hold">On Hold</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Total Amount (GHC)</label>
                <input type="text" id="totalAmount" class="form-control" readonly placeholder="Auto-calculated">
            </div>
        </div>

        <!-- Submit -->
        <div class="d-flex justify-content-center mt-5">
            <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="saveOrder" class="btn btn-primary">
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                Place Order
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function () {
    // Initialize Flatpickr
    $('.flatpickr').flatpickr();

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
        const $productSelect = $item.find('.product-select');
        const $priceInput = $item.find('.price-input');
        const $quantityInput = $item.find('.quantity-input');
        const $subtotalInput = $item.find('.subtotal-input');

        const product = $productSelect.val();
        const quantity = parseInt($quantityInput.val()) || 0;
        const price = parseFloat($productSelect.find('option:selected').data('price')) || 0;
        const availableQuantity = parseInt($productSelect.find('option:selected').data('quantity')) || 0;

        // Validate quantity
        if (quantity > availableQuantity) {
            $quantityInput.val(availableQuantity);
            $.notify(`Only ${availableQuantity} units available for ${product}`, { className: 'warning', position: 'top right' });
        }

        $priceInput.val(price.toFixed(2));
        $subtotalInput.val((price * (parseInt($quantityInput.val()) || 0)).toFixed(2));
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

    // Add Order Item
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
            fullName: $('#fullName').val(),
            email: $('#email').val(),
            phoneNumber: $('#phoneNumber').val(),
            address: $('#address').val(),
            fulfillmentMethod: $('input[name="fulfillmentMethod"]:checked').val(),
            preferredDate: $('#preferredDate').val(),
            paymentStatus: $('#paymentStatus').val(),
            totalAmount: $('#totalAmount').val(),
            products: [],
            quantities: []
        };

        // Validate quantities against available stock
        let quantityError = false;
        $('.order-item').each(function() {
            const $item = $(this);
            const product = $item.find('.product-select').val();
            const quantity = parseInt($item.find('.quantity-input').val()) || 0;
            const availableQuantity = parseInt($item.find('.product-select option:selected').data('quantity')) || 0;
            if (product && quantity) {
                if (quantity > availableQuantity) {
                    $.notify(`Only ${availableQuantity} units available for ${product}`, { className: 'error', position: 'top right' });
                    quantityError = true;
                } else {
                    formData.products.push(product);
                    formData.quantities.push(quantity);
                }
            }
        });

        if (quantityError) {
            $submitBtn.find('.spinner-border').addClass('d-none');
            return;
        }

        // Validation
        const validateForm = function(data) {
            let error = '';
            if (!data.fullName) error += 'Please enter full name\n';
            if (data.email && !validateEmail(data.email)) error += 'Please enter a valid email address\n';
            if (!data.fulfillmentMethod) error += 'Please select fulfillment type\n';
            if (!data.preferredDate) error += 'Please select delivery or pickup date\n';
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
        const url = 'ajaxscripts/queries/addOrder.php';
        const successCallback = function(response) {
            $submitBtn.find('.spinner-border').addClass('d-none');
            if (response === 'Success') {
                $.notify('Order information saved successfully', { className: 'success', position: 'top right' });
                $('#addOrderForm')[0].reset();
                const $firstItem = $('#orderItemsContainer .order-item:first').clone(true);
                $firstItem.find('.product-select').val('');
                $firstItem.find('.quantity-input').val('');
                $firstItem.find('.price-input').val('');
                $firstItem.find('.subtotal-input').val('');
                $firstItem.find('.removeItemBtn').addClass('d-none');
                $('#orderItemsContainer').html($firstItem);
                $('#addOrderModal').modal('hide');
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
});
</script>