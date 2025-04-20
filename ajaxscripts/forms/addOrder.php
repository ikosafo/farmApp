<?php include('../../config.php'); ?>

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
    <form id="addOrderForm" autocomplete="off">
        <!-- Section: Customer Information -->
        <h5 class="form-section-title">1. Customer Information</h5>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label for="fullName" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input id="fullName" class="form-control" type="text" placeholder="Enter full name" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email Address </label>
                <input id="email" class="form-control" type="email" placeholder="Enter email address" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="phoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input id="phoneNumber" class="form-control" maxlength="10" type="tel" onkeypress="return isNumber(event)" placeholder="Enter phone number" required>
            </div>
            <div class="col-12 col-md-6">
                <label for="address" class="form-label">Home Address <span class="text-danger">*</span></label>
                <input id="address" class="form-control" type="text" placeholder="Enter address" required>
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
                    <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Delivery" id="delivery" checked required>
                    <label class="form-check-label" for="delivery">Delivery</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Farm Pickup" id="pickup">
                    <label class="form-check-label" for="pickup">Farm Pickup</label>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Preferred Date <span class="text-danger">*</span></label>
                <input type="text" id="preferredDate" class="form-control flatpickr" placeholder="Choose date" required>
            </div>
        </div>

        <!-- Section: Payment -->
        <h5 class="form-section-title mt-5">4. Payment</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                <select id="paymentMethod" class="form-select" name="paymentMethod" required>
                    <option value="" disabled selected>Select payment method</option>
                    <option value="Card">Credit or Debit Card/Mobile Money/Bank Transfer</option>
                    <option value="Cash">Cash on Delivery/Pickup</option>
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
        $('.flatpickr').flatpickr({
            minDate: 'today',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'F j, Y'
        });

        // Function to update disabled options in all product dropdowns
        function updateProductOptions() {
            // Reset all options to enabled
            $('.product-select option').prop('disabled', false);

            // Collect all selected products
            const selectedProducts = [];
            $('.product-select').each(function() {
                const selectedValue = $(this).val();
                if (selectedValue) {
                    selectedProducts.push(selectedValue);
                }
            });

            // Disable selected products in all dropdowns except the one where they are selected
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
            updateProductOptions(); // Update disabled options
        });

        // Quantity change
        $(document).on('input', '.quantity-input', function() {
            const $item = $(this).closest('.order-item');
            updateItemCalculations($item);
        });

        // Add Order Item
        $('#addOrderItem').click(function () {
            // Clone the first order item
            const $item = $('#orderItemsContainer .order-item:first').clone(true);
            
            // Clear inputs
            $item.find('.product-select').val('');
            $item.find('.quantity-input').val('');
            $item.find('.price-input').val('');
            $item.find('.subtotal-input').val('');

            // Show remove button
            $item.find('.removeItemBtn').removeClass('d-none');

            // Append new item
            $('#orderItemsContainer').append($item);
            updateProductOptions(); // Update disabled options after adding new item
        });

        // Remove Order Item
        $(document).on('click', '.removeItemBtn', function () {
            const $item = $(this).closest('.order-item');
            $item.fadeOut(300, function () {
                $item.remove();
                updateTotal();
                updateProductOptions(); // Update disabled options after removing item
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
                paymentMethod: $('#paymentMethod').val(),
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
                if (!data.fullName) error += 'Please enter full name\n';
                else if (data.email && !validateEmail(data.email)) error += 'Please enter a valid email address\n';
                if (!data.phoneNumber) error += 'Please enter phone number\n';
                if (!data.address) error += 'Please enter address\n';
                if (!data.fulfillmentMethod) error += 'Please select fulfillment method\n';
                if (!data.preferredDate) error += 'Please select preferred date\n';
                if (!data.paymentMethod) error += 'Please select payment method\n';
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
                    $.notify('Order placed successfully', { className: 'success', position: 'top right' });
                    $('#addOrderForm')[0].reset();
                    // Reset order items to a single empty item
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
                    updateProductOptions(); // Reset disabled options after form reset
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