<form autocomplete="off" id="farmExpenditureForm">
    <h5 class="form-section-title">1. Customer Information</h5>
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureName" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input id="expenditureName" class="form-control border-radius-md" type="text" placeholder="Enter expenditure name" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureCategory" class="form-label">Email Address <span class="text-danger">*</span></label>
            <input id="expenditureName" class="form-control border-radius-md" type="text" placeholder="Enter expenditure name" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureAmount" class="form-label">Phone Number <span class="text-danger">*</span></label>
            <input id="expenditureAmount" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDate" class="form-label">Location <span class="text-danger">*</span></label>
            <input id="expenditureDate" class="form-control border-radius-md" type="text" placeholder="Select date" required>
        </div>
    </div>

    <h5 class="form-section-title mt-5">1. Customer Information</h5>
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label class="form-label">Product Name <span class="text-danger">*</span></label>
            <select class="form-select select2" required>
                <option value="">Select a product</option>
                <option value="Organic Apples">Organic Apples</option>
                <option value="Free-Range Eggs">Free-Range Eggs</option>
                <option value="Fresh Milk">Fresh Milk</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
        <label class="form-label">Quantity <span class="text-danger">*</span></label>
        <input type="number" class="form-control" placeholder="e.g., 2" min="1" required>
        </div>
    </div>

   

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveExpenditure" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Expenditure
        </button>
    </div>
</form>




    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .form-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .form-section-title {
            color: #2c6e49;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2c6e49;
            box-shadow: 0 0 0 0.25rem rgba(44, 110, 73, 0.2);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .text-danger {
            font-size: 0.9rem;
        }

        .btn-primary {
            background: linear-gradient(90deg, #2c6e49, #4a9b6a);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #245f3b, #3d8a57);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border-color: #2c6e49;
            color: #2c6e49;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background-color: #2c6e49;
            color: #fff;
        }

        .btn-outline-danger {
            border-radius: 0.5rem;
        }

        .order-item {
            animation: fadeIn 0.3s ease;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .select2-container--default .select2-selection--single {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            height: 38px;
            padding: 0.375rem 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .flatpickr-input {
            background: #fff;
        }

        .form-check-input:checked {
            background-color: #2c6e49;
            border-color: #2c6e49;
        }

        .submit-btn {
            background: linear-gradient(90deg, #28a745, #4cd964);
        }

        .submit-btn:hover {
            background: linear-gradient(90deg, #218838, #42b855);
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 1rem;
            }
            .form-section-title {
                font-size: 1.25rem;
            }
        }
    </style>


    <div class="form-container">
        <form id="farmOrderForm" autocomplete="off">
            <!-- Section: Customer Information -->
            <h5 class="form-section-title">1. Customer Information</h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter full name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" placeholder="Enter phone number" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Street Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="123 Farm Lane" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">State <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ZIP Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" required>
                </div>
            </div>

            <!-- Section: Order Details -->
            <h5 class="form-section-title mt-5">2. Order Details</h5>
            <div id="orderItemsContainer">
                <div class="row g-4 order-item">
                    <div class="col-md-6">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <select class="form-select select2" required>
                            <option value="">Select a product</option>
                            <option value="Organic Apples">Organic Apples</option>
                            <option value="Free-Range Eggs">Free-Range Eggs</option>
                            <option value="Fresh Milk">Fresh Milk</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="e.g., 2" min="1" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger w-100 removeItemBtn d-none">Remove</button>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="button" id="addOrderItem" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Another Product
                </button>
            </div>

            <div class="mt-4">
                <label class="form-label">Special Instructions</label>
                <textarea class="form-control" rows="3" placeholder="e.g., No green tomatoes."></textarea>
            </div>

            <!-- Section: Delivery/Pickup -->
            <h5 class="form-section-title mt-5">3. Delivery / Pickup</h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Fulfillment Method <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Delivery" id="delivery" checked>
                        <label class="form-check-label" for="delivery">Delivery</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fulfillmentMethod" value="Farm Pickup" id="pickup">
                        <label class="form-check-label" for="pickup">Farm Pickup</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Preferred Date <span class="text-danger">*</span></label>
                    <input type="text" class="form-control flatpickr" placeholder="Choose date" required>
                </div>
            </div>

            <!-- Section: Payment -->
            <h5 class="form-section-title mt-5">4. Payment</h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select class="form-select" required>
                        <option value="">Select payment method</option>
                        <option value="Card">Credit/Debit Card</option>
                        <option value="Cash">Cash on Delivery/Pickup</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Amount</label>
                    <input type="text" class="form-control" readonly placeholder="Auto-calculated">
                </div>
            </div>

            <!-- Submit -->
            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-success submit-btn px-4">
                    <i class="bi bi-check-circle me-2"></i>Submit Order
                </button>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select a product',
                width: '100%',
                dropdownCssClass: 'custom-select2-dropdown'
            });

            // Initialize Flatpickr
            $('.flatpickr').flatpickr({
                minDate: 'today',
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y'
            });

            // Add Order Item
            $('#addOrderItem').click(function () {
                const item = $('#orderItemsContainer .order-item:first').clone();
                item.find('select').val('').trigger('change');
                item.find('input[type="number"]').val('');
                item.find('.removeItemBtn').removeClass('d-none');
                $('#orderItemsContainer').append(item);
                item.find('.select2').select2({
                    placeholder: 'Select a product',
                    width: '100%',
                    dropdownCssClass: 'custom-select2-dropdown'
                });
            });

            // Remove Order Item
            $(document).on('click', '.removeItemBtn', function () {
                $(this).closest('.order-item').fadeOut(300, function () {
                    $(this).remove();
                });
            });

            // Form Submission (Placeholder)
            $('#farmOrderForm').on('submit', function (e) {
                e.preventDefault();
                alert('Form submitted successfully! (This is a demo.)');
            });
        });
    </script>
