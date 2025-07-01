<?php
include('../../config.php');

// Fetch the current exchange rates
$query = $mysqli->query("SELECT currencyghs, currencyusd, currencyeur FROM currencies LIMIT 1");
$rates = $query->fetch_assoc();
?>

<style>
/* Custom Searchable Dropdown Styling */
.custom-select-wrapper {
    position: relative;
    width: 100%;
}

.custom-select-wrapper .form-control.dropdown-toggle {
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    padding-right: 2.5rem; /* Space for arrow */
    background-color: transparent; /* Transparent background */
    border: 1px solid #ced4da; /* Ensure border is visible */
}

/* Custom dropdown arrow */
.custom-select-wrapper::after {
    content: '\25BC'; /* Unicode for down arrow */
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    pointer-events: none;
    color: #495057;
    font-size: 0.875rem;
}

/* Focus state for dropdown toggle */
.custom-select-wrapper .form-control.dropdown-toggle:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
    background-color: transparent; /* Keep transparent on focus */
}

/* Hover state for dropdown toggle */
.custom-select-wrapper .form-control.dropdown-toggle:hover {
    border-color: #80bdff;
}

/* Dropdown list */
.custom-select-wrapper .dropdown-list {
    position: absolute;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
    margin-bottom: 0.25rem; /* Space when above */
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Dropdown list positioned above */
.custom-select-wrapper .dropdown-list.above {
    bottom: 100%;
    top: auto;
    margin-bottom: 0.25rem;
    margin-top: 0;
}

/* Dropdown list positioned below */
.custom-select-wrapper .dropdown-list.below {
    top: 100%;
    bottom: auto;
    margin-top: 0.25rem;
    margin-bottom: 0;
}

/* Search input inside dropdown */
.custom-select-wrapper .dropdown-search {
    width: 100%;
    padding: 0.5rem;
    border: none;
    border-bottom: 1px solid #ced4da;
    border-radius: 0;
    outline: none;
    font-size: 0.875rem;
}

/* Dropdown options */
.custom-select-wrapper .dropdown-list li {
    padding: 0.5rem 1rem;
    cursor: pointer;
    font-size: 0.875rem;
    color: #495057;
}

.custom-select-wrapper .dropdown-list li:hover,
.custom-select-wrapper .dropdown-list li:focus {
    background: #f8f9fa;
}

.custom-select-wrapper .dropdown-list li.disabled {
    color: #6c757d;
    cursor: not-allowed;
    background: none;
}

/* Hide dropdown when not active */
.custom-select-wrapper .dropdown-list.hidden {
    display: none;
}

/* Show dropdown when active */
.custom-select-wrapper .dropdown-list.active {
    display: block;
}

/* Scrollbar styling */
.custom-select-wrapper .dropdown-list::-webkit-scrollbar {
    width: 6px;
}

.custom-select-wrapper .dropdown-list::-webkit-scrollbar-thumb {
    background: #ced4da;
    border-radius: 3px;
}

/* Custom Error Alert Styling */
.custom-error-alert {
    display: none;
    position: relative;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    border-radius: 0.25rem;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    font-size: 0.875rem;
    animation: fadeIn 0.3s ease-in-out;
}

.custom-error-alert.show {
    display: block;
}

.custom-error-alert .error-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.custom-error-alert .error-icon {
    font-size: 1.25rem;
    line-height: 1.2;
}

.custom-error-alert .error-text {
    flex: 1;
    white-space: pre-wrap; /* Preserve newlines */
}

.custom-error-alert .btn-close {
    position: absolute;
    top: 0.75rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 0.875rem;
    color: #721c24;
    cursor: pointer;
    padding: 0;
}

.custom-error-alert .btn-close:hover {
    color: #491217;
}

/* Custom Success Alert Styling */
.custom-success-alert {
    display: none;
    position: relative;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    border-radius: 0.25rem;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    font-size: 0.875rem;
    animation: fadeIn 0.3s ease-in-out;
}

.custom-success-alert.show {
    display: block;
}

.custom-success-alert .success-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.custom-success-alert .success-icon {
    font-size: 1.25rem;
    line-height: 1.2;
}

.custom-success-alert .success-text {
    flex: 1;
    white-space: pre-wrap; /* Preserve newlines */
}

.custom-success-alert .btn-close {
    position: absolute;
    top: 0.75rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 0.875rem;
    color: #155724;
    cursor: pointer;
    padding: 0;
}

.custom-success-alert .btn-close:hover {
    color: #0c3c1a;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Transparent backgrounds for readonly and Flatpickr inputs */
.form-control[readonly],
.flatpickr-input[readonly],
.flatpickr-input.form-control {
    background-color: transparent;
    border: 1px solid #ced4da;
}

.form-control[readonly]:focus,
.flatpickr-input[readonly]:focus,
.flatpickr-input.form-control:focus {
    background-color: transparent;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
}

.form-control[readonly]:hover,
.flatpickr-input[readonly]:hover,
.flatpickr-input.form-control:hover {
    border-color: #80bdff;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="font-weight-bolder mb-0">Add New Payment</h5>
</div>

<form autocomplete="off" id="farmIncomeForm">
    <!-- Custom Error Alert -->
    <div id="customErrorAlert" class="custom-error-alert">
        <div class="error-content">
            <span class="error-icon">❗</span>
            <div class="error-text"></div>
        </div>
        <button type="button" class="btn-close">×</button>
    </div>

    <!-- Custom Success Alert -->
    <div id="customSuccessAlert" class="custom-success-alert">
        <div class="success-content">
            <span class="success-icon">✅</span>
            <div class="success-text"></div>
        </div>
        <button type="button" class="btn-close">×</button>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureDate" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="expenditureDate" class="form-control border-radius-md flatpickrDate" type="text" placeholder="Select date" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditurePayer" class="form-label">Payer <span class="text-danger">*</span></label>
            <input id="expenditurePayer" class="form-control border-radius-md" type="text" placeholder="Enter Payee" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureDescription" class="form-label">Details</label>
            <textarea id="expenditureDescription" class="form-control border-radius-md" rows="2" placeholder="Enter description"></textarea>
        </div>
        <div class="col-12 col-md-6">
            <label for="farmProduce" class="form-label">Produce </label>
            <div class="custom-select-wrapper">
                <input type="hidden" id="farmProduce" name="farmProduce" required>
                <input type="text" class="form-control border-radius-md dropdown-toggle" data-target="farmProduceList" placeholder="Select produce" readonly>
                <div id="farmProduceList" class="dropdown-list hidden">
                    <input type="text" class="dropdown-search" placeholder="Search produce...">
                    <ul class="list-unstyled m-0">
                        <?php
                        $getProd = $mysqli->query("SELECT * FROM producelist WHERE prodStatus = 1");
                        while ($resProd = $getProd->fetch_assoc()) {
                            echo "<li data-value='{$resProd['prodId']}'>{$resProd['prodName']}</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="invoiceNumber" class="form-label">Invoice No. </label>
            <input id="invoiceNumber" class="form-control border-radius-md" type="text" placeholder="Enter Invoice No." required>
        </div>
         <div class="col-12 col-md-6">
            <label for="expenditureCategory" class="form-label">Nominal Account <span class="text-danger">*</span></label>
            <div class="custom-select-wrapper">
                <input type="hidden" id="expenditureCategory" name="expenditureCategory" required>
                <input type="text" class="form-control border-radius-md dropdown-toggle" data-target="expenditureCategoryList" placeholder="Select account" readonly>
                <div id="expenditureCategoryList" class="dropdown-list hidden">
                    <input type="text" class="dropdown-search" placeholder="Search account...">
                    <ul class="list-unstyled m-0">
                        <?php
                        $getCat = $mysqli->query("SELECT * FROM categories WHERE categoryStatus = 1");
                        while ($resCat = $getCat->fetch_assoc()) {
                            echo "<li data-value='{$resCat['catId']}'>{$resCat['categoryName']}</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
         <div class="col-12 col-md-6">
            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
            <div class="custom-select-wrapper">
                <input type="hidden" id="currency" name="currency" required>
                <input type="text" class="form-control border-radius-md dropdown-toggle" data-target="currencyList" placeholder="Select currency" readonly>
                <div id="currencyList" class="dropdown-list hidden">
                    <input type="text" class="dropdown-search" placeholder="Search currency...">
                    <ul class="list-unstyled m-0">
                        <li data-value="GHS">GHS</li>
                        <li data-value="USD">USD</li>
                        <li data-value="EUR">EUR</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureAmount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="expenditureAmount" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" required>
        </div>
    </div>

    <div class="row g-4">
         <div class="col-12 col-md-6">
            <label for="exchangeRate" class="form-label">Exchange Rate </label>
            <input id="exchangeRate" class="form-control border-radius-md" type="number" step="0.01" readonly required>
        </div>
        <div class="col-12 col-md-6">
            <label for="ghsEquivalent" class="form-label">GHS Equivalent </label>
            <input id="ghsEquivalent" class="form-control border-radius-md" type="text" readonly>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="reset" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Reset</button>
        <button type="button" id="saveIncome" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Payment Record
        </button>
    </div>
</form>

<script> 
    $(document).ready(function() {
        const exchangeRates = {
            GHS: 1,
            USD: <?= floatval($rates['currencyusd']) ?>,
            EUR: <?= floatval($rates['currencyeur']) ?>
        };

        // Exchange rate calculation
        $("#currency, #expenditureAmount").on("input change", function () {
            const currency = $("#currency").val();
            const amount = parseFloat($("#expenditureAmount").val()) || 0;
            const rate = currency ? (exchangeRates[currency] || 1) : 1;
            const ghsEquivalent = (currency === "GHS") ? amount : (amount * rate);

            $("#exchangeRate").val(rate.toFixed(2));
            $("#ghsEquivalent").val(ghsEquivalent.toFixed(2));
        });

        // Date picker
        $("#expenditureDate").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        });




        // Initialize custom searchable dropdowns
            function initializeDropdowns() {
                document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
                    const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                    const toggleInput = wrapper.querySelector('.dropdown-toggle');
                    const dropdownList = wrapper.querySelector('.dropdown-list');
                    const searchInput = wrapper.querySelector('.dropdown-search');
                    const optionsList = wrapper.querySelectorAll('.dropdown-list li');

                    // Determine dropdown position (above or below)
                    const setDropdownPosition = () => {
                        const rect = toggleInput.getBoundingClientRect();
                        const viewportHeight = window.innerHeight;
                        const spaceBelow = viewportHeight - rect.bottom;
                        const isLongList = hiddenInput.id === 'farmProduce' || hiddenInput.id === 'expenditureCategory';
                        const dropdownHeight = Math.min(200, dropdownList.scrollHeight); 

                        if (isLongList || spaceBelow < dropdownHeight + 20) {
                            dropdownList.classList.add('above');
                            dropdownList.classList.remove('below');
                        } else {
                            dropdownList.classList.add('below');
                            dropdownList.classList.remove('above');
                        }
                    };

                    // Toggle dropdown on click
                    toggleInput.addEventListener('click', () => {
                        setDropdownPosition();
                        dropdownList.classList.toggle('active');
                        if (dropdownList.classList.contains('active')) {
                            searchInput.focus();
                        }
                    });

                    // Filter options based on search
                    searchInput.addEventListener('input', () => {
                        const filter = searchInput.value.toLowerCase();
                        optionsList.forEach(option => {
                            const text = option.textContent.toLowerCase();
                            option.style.display = text.includes(filter) ? 'block' : 'none';
                        });
                    });

                    // Select option
                    optionsList.forEach(option => {
                        option.addEventListener('click', () => {
                            const value = option.getAttribute('data-value');
                            const text = option.textContent;
                            hiddenInput.value = value;
                            toggleInput.value = text;
                            dropdownList.classList.remove('active');
                            searchInput.value = '';
                            optionsList.forEach(opt => opt.style.display = 'block');
                            // Trigger change event for exchange rate update
                            if (hiddenInput.id === 'currency') {
                                $(hiddenInput).trigger('change');
                            }
                        });
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', e => {
                        if (!wrapper.contains(e.target)) {
                            dropdownList.classList.remove('active');
                            searchInput.value = '';
                            optionsList.forEach(opt => opt.style.display = 'block');
                        }
                    });

                    // Keyboard navigation
                    searchInput.addEventListener('keydown', e => {
                        const visibleOptions = Array.from(optionsList).filter(opt => opt.style.display !== 'none');
                        if (visibleOptions.length === 0) return;
                        let selectedIndex = visibleOptions.findIndex(opt => opt.classList.contains('focused'));
                        if (selectedIndex === -1) selectedIndex = 0;

                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            selectedIndex = (selectedIndex + 1) % visibleOptions.length;
                            visibleOptions.forEach(opt => opt.classList.remove('focused'));
                            visibleOptions[selectedIndex].classList.add('focused');
                            visibleOptions[selectedIndex].scrollIntoView({ block: 'nearest' });
                        } else if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            selectedIndex = (selectedIndex - 1 + visibleOptions.length) % visibleOptions.length;
                            visibleOptions.forEach(opt => opt.classList.remove('focused'));
                            visibleOptions[selectedIndex].classList.add('focused');
                            visibleOptions[selectedIndex].scrollIntoView({ block: 'nearest' });
                        } else if (e.key === 'Enter' && selectedIndex >= 0) {
                            e.preventDefault();
                            const option = visibleOptions[selectedIndex];
                            hiddenInput.value = option.getAttribute('data-value');
                            toggleInput.value = option.textContent;
                            dropdownList.classList.remove('active');
                            searchInput.value = '';
                            optionsList.forEach(opt => opt.style.display = 'block');
                            if (hiddenInput.id === 'currency') {
                                $(hiddenInput).trigger('change');
                            }
                        } else if (e.key === 'Escape') {
                            dropdownList.classList.remove('active');
                            searchInput.value = '';
                            optionsList.forEach(opt => opt.style.display = 'block');
                        }
                    });
                });
            }

            // Call initialization
            initializeDropdowns();



        // Custom error alert handling
            function showErrorAlert(message) {
                const errorAlert = document.getElementById('customErrorAlert');
                const errorText = errorAlert.querySelector('.error-text');
                errorText.textContent = message;
                errorAlert.classList.add('show');
            }

            function hideErrorAlert() {
                const errorAlert = document.getElementById('customErrorAlert');
                errorAlert.classList.remove('show');
            }

            // Custom success alert handling
            function showSuccessAlert(message) {
                const successAlert = document.getElementById('customSuccessAlert');
                const successText = successAlert.querySelector('.success-text');
                successText.textContent = message;
                successAlert.classList.add('show');
            }

            function hideSuccessAlert() {
                const successAlert = document.getElementById('customSuccessAlert');
                successAlert.classList.remove('show');
            }

            // Initialize close buttons for alerts
            document.getElementById('customErrorAlert').querySelector('.btn-close').addEventListener('click', hideErrorAlert);
            document.getElementById('customSuccessAlert').querySelector('.btn-close').addEventListener('click', hideSuccessAlert);


    
        // Form submission
        $("#saveIncome").click(function () {
            var $button = $(this);
            var $spinner = $button.find('.spinner-border');
            $spinner.removeClass('d-none');

            var formData = {
                transactionDate: $("#expenditureDate").val(),
                payeePayer: $("#expenditurePayer").val(),
                details: $("#expenditureDescription").val(),
                produce: $("#farmProduce").val(),
                invoiceNo: $("#invoiceNumber").val(),
                currency: $("#currency").val(),
                amount: $("#expenditureAmount").val(),
                exchangeRate: $("#exchangeRate").val(),
                ghsEquivalent: $("#ghsEquivalent").val(),
                transactionType: "Payment",
                nominalAccount: $("#expenditureCategory").val()
            };

            var url = "ajaxscripts/queries/addPayment.php";

            var successCallback = function (response) {
                $spinner.addClass('d-none');
                if (response === 'Success') {
                    showSuccessAlert("Payment updated successfully!");
                    setTimeout(function() {
                        $('#payments-tab').tab('show');
                    }, 0);
                    loadPage("ajaxscripts/tables/payments.php", function(response) {
                        $('#pageTable').html(response);
                    });
                    loadPage("ajaxscripts/forms/addPayment.php", function(response) {
                        $('#pageForm').html(response);
                    });
                    location.reload();
                } else {
                    $spinner.addClass('d-none');
                    showErrorAlert(response);
                }
            };

            var validateForm = function (formData) {
                var error = '';
                var firstEmptyField = null;

                if (!formData.transactionDate) {
                    error += 'Please select a date\n';
                    firstEmptyField = firstEmptyField || '#expenditureDate';
                }
                if (!formData.payeePayer) {
                    error += 'Please enter payer\n';
                    firstEmptyField = firstEmptyField || '#expenditurePayer';
                }
                if (!formData.currency) {
                    error += 'Please select currency\n';
                    firstEmptyField = firstEmptyField || '#currency + .dropdown-toggle';
                }
                if (!formData.amount) {
                    error += 'Please enter amount\n';
                    firstEmptyField = firstEmptyField || '#expenditureAmount';
                }
                if (!formData.nominalAccount) {
                    error += 'Please select nominal account\n';
                    firstEmptyField = firstEmptyField || '#expenditureCategory + .dropdown-toggle';
                }

                return { error: error, firstEmptyField: firstEmptyField };
            };

            var validationResult = validateForm(formData); 
            if (validationResult.error) {
                $spinner.addClass('d-none');
                showErrorAlert(validationResult.error); 
                if (validationResult.firstEmptyField) {
                    $(validationResult.firstEmptyField).focus();
                }
                return; 
            }

            saveForm(formData, url, successCallback); 
        });
    })
</script>