<?php
include('../../config.php');
include('../../includes/functions.php');

// Helper function to display "-" for null values
function displayValue($value) {
    return $value !== null ? htmlspecialchars($value) : '-';
}

// Fetch receipt data
$i_id = unlock(unlock($_POST['i_index']));
$stmt = $mysqli->prepare("SELECT * FROM `cashbook_transactions` WHERE transactionId = ?");
$stmt->bind_param('s', $i_id);
$stmt->execute();
$resInc = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$resInc) {
    echo "<div class='alert alert-danger'>No transaction found with ID: $i_id</div>";
    exit;
}

// Fetch current exchange rates
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
    padding-right: 2.5rem;
    background-color: transparent;
    border: 1px solid #ced4da;
}

.custom-select-wrapper::after {
    content: '\25BC';
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    pointer-events: none;
    color: #495057;
    font-size: 0.875rem;
}

.custom-select-wrapper .form-control.dropdown-toggle:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    outline: none;
    background-color: transparent;
}

.custom-select-wrapper .form-control.dropdown-toggle:hover {
    border-color: #80bdff;
}

.custom-select-wrapper .dropdown-list {
    position: absolute;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050; /* Higher z-index to appear above other elements */
    display: none;
    margin-bottom: 0.25rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.custom-select-wrapper .dropdown-list.above {
    bottom: 100%;
    top: auto;
    margin-bottom: 0.25rem;
    margin-top: 0;
}

.custom-select-wrapper .dropdown-list.below {
    top: 100%;
    bottom: auto;
    margin-top: 0.25rem;
    margin-bottom: 0;
}

.custom-select-wrapper .dropdown-search {
    width: 100%;
    padding: 0.5rem;
    border: none;
    border-bottom: 1px solid #ced4da;
    border-radius: 0;
    outline: none;
    font-size: 0.875rem;
}

.custom-select-wrapper .dropdown-list li {
    padding: 0.5rem 1rem;
    cursor: pointer;
    font-size: 0.875rem;
    color: #495057;
}

.custom-select-wrapper .dropdown-list li:hover,
.custom-select-wrapper .dropdown-list li:focus,
.custom-select-wrapper .dropdown-list li.focused {
    background: #f8f9fa;
}

.custom-select-wrapper .dropdown-list li.disabled {
    color: #6c757d;
    cursor: not-allowed;
    background: none;
}

.custom-select-wrapper .dropdown-list.hidden {
    display: none;
}

.custom-select-wrapper .dropdown-list.active {
    display: block;
}

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
    white-space: pre-wrap;
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
    white-space: pre-wrap;
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
    <h5 class="font-weight-bolder mb-0">Edit Receipt</h5>
</div>

<form autocomplete="off" id="farmIncomeForm">
    <input type="hidden" id="transactionId" name="transactionId" value="<?= htmlspecialchars($resInc['transactionId']) ?>">
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
            <label for="incomeDateEdit" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="incomeDateEdit" class="form-control border-radius-md" type="text" placeholder="Select date" value="<?= displayValue($resInc['transactionDate']) ?>" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="incomePayeeEdit" class="form-label">Payee <span class="text-danger">*</span></label>
            <input id="incomePayeeEdit" class="form-control border-radius-md" type="text" placeholder="Enter Payee" value="<?= displayValue($resInc['payeePayer']) ?>" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeDescriptionEdit" class="form-label">Details</label>
            <textarea id="incomeDescriptionEdit" class="form-control border-radius-md" rows="2" placeholder="Enter description"><?= displayValue($resInc['details']) ?></textarea>
        </div>
        <div class="col-12 col-md-6">
            <label for="farmProduceEdit" class="form-label">Produce</label>
            <div class="custom-select-wrapper">
                <input type="hidden" id="farmProduceEdit" name="farmProduceEdit" value="<?= displayValue($resInc['produce']) ?>">
                <input type="text" class="form-control border-radius-md dropdown-toggle" data-target="farmProduceEditList" placeholder="Select produce" value="<?php
                    if ($resInc['produce']) {
                        $stmt = $mysqli->prepare("SELECT prodName FROM producelist WHERE prodId = ? AND prodStatus = 1");
                        $stmt->bind_param('i', $resInc['produce']);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        echo displayValue($result['prodName']);
                        $stmt->close();
                    } else {
                        echo '-';
                    }
                ?>" readonly>
                <div id="farmProduceEditList" class="dropdown-list hidden">
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
            <label for="invoiceNumberEdit" class="form-label">Invoice No.</label>
            <input id="invoiceNumberEdit" class="form-control border-radius-md" type="text" placeholder="Enter Invoice No." value="<?= displayValue($resInc['invoiceNo']) ?>" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeCategoryEdit" class="form-label">Nominal Account <span class="text-danger">*</span></label>
            <div class="custom-select-wrapper">
                <input type="hidden" id="incomeCategoryEdit" name="incomeCategoryEdit" value="<?= displayValue($resInc['nominalAccount']) ?>">
                <input type="text" class="form-control border-radius-md dropdown-toggle" data-target="incomeCategoryEditList" placeholder="Select account" value="<?php
                    if ($resInc['nominalAccount']) {
                        $stmt = $mysqli->prepare("SELECT categoryName FROM categories WHERE catId = ? AND categoryStatus = 1");
                        $stmt->bind_param('i', $resInc['nominalAccount']);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        echo displayValue($result['categoryName']);
                        $stmt->close();
                    } else {
                        echo '-';
                    }
                ?>" readonly>
                <div id="incomeCategoryEditList" class="dropdown-list hidden">
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
            <label for="currencyEdit" class="form-label">Currency <span class="text-danger">*</span></label>
            <div class="custom-select-wrapper">
                <input type="hidden" id="currencyEdit" name="currencyEdit" value="<?= displayValue($resInc['currency']) ?>">
                <input type="text" class="form-control border-radius-md dropdown-toggle" data-target="currencyListEdit" placeholder="Select currency" value="<?= displayValue($resInc['currency']) ?>" readonly>
                <div id="currencyListEdit" class="dropdown-list hidden">
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
            <label for="incomeAmountEdit" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="incomeAmountEdit" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" value="<?= displayValue($resInc['amount']) ?>" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="exchangeRateEdit" class="form-label">Exchange Rate</label>
            <input id="exchangeRateEdit" class="form-control border-radius-md" type="number" step="0.01" value="<?= displayValue($resInc['exchangeRate']) ?>" readonly required>
        </div>
        <div class="col-12 col-md-6">
            <label for="ghsEquivalentEdit" class="form-label">GHS Equivalent</label>
            <input id="ghsEquivalentEdit" class="form-control border-radius-md" type="text" value="<?= displayValue($resInc['ghsEquivalent']) ?>" readonly>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="reset" class="btn btn-outline-secondary me-3 cancelEdit">Cancel</button>
        <button type="button" id="editReceiptBtn" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Receipt Record
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
    $("#currencyEdit, #incomeAmountEdit").on("input change", function () {
        const currency = $("#currencyEdit").val();
        const amount = parseFloat($("#incomeAmountEdit").val()) || 0;
        const rate = currency ? (exchangeRates[currency] || 1) : 1;
        const ghsEquivalent = (currency === "GHS") ? amount : (amount * rate);
       
        $("#exchangeRateEdit").val(rate.toFixed(2));
        $("#ghsEquivalentEdit").val(ghsEquivalent.toFixed(2));
    });

    // Date picker
    $("#incomeDateEdit").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    // Initialize custom searchable dropdowns
    function initializeDropdowns() {
        // Use event delegation to handle dynamically added dropdowns
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.custom-select-wrapper .dropdown-toggle');
            if (toggle) {
                e.stopPropagation();
                const wrapper = toggle.closest('.custom-select-wrapper');
                const dropdownList = wrapper.querySelector('.dropdown-list');
                const searchInput = wrapper.querySelector('.dropdown-search');

                // Close other open dropdowns
                document.querySelectorAll('.custom-select-wrapper .dropdown-list.active').forEach(list => {
                    if (list !== dropdownList) {
                        list.classList.remove('active');
                        const otherSearch = list.querySelector('.dropdown-search');
                        if (otherSearch) otherSearch.value = '';
                        list.querySelectorAll('li').forEach(opt => opt.style.display = 'block');
                    }
                });

                // Toggle current dropdown
                dropdownList.classList.toggle('active');
                if (dropdownList.classList.contains('active')) {
                    // Calculate position
                    const rect = toggle.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    const spaceBelow = viewportHeight - rect.bottom;
                    const isLongList = wrapper.querySelector('input[type="hidden"]').id === 'farmProduceEdit' || 
                                      wrapper.querySelector('input[type="hidden"]').id === 'incomeCategoryEdit';
                    const dropdownHeight = Math.min(200, dropdownList.scrollHeight);

                    if (isLongList || spaceBelow < dropdownHeight + 20) {
                        dropdownList.classList.add('above');
                        dropdownList.classList.remove('below');
                    } else {
                        dropdownList.classList.add('below');
                        dropdownList.classList.remove('above');
                    }

                    // Focus search input
                    setTimeout(() => searchInput.focus(), 0);
                }
            } else {
                // Close all dropdowns if clicking outside
                document.querySelectorAll('.custom-select-wrapper .dropdown-list.active').forEach(list => {
                    list.classList.remove('active');
                    const searchInput = list.querySelector('.dropdown-search');
                    if (searchInput) searchInput.value = '';
                    list.querySelectorAll('li').forEach(opt => opt.style.display = 'block');
                });
            }
        });

        // Handle search input (with debouncing)
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('dropdown-search')) {
                const searchInput = e.target;
                const dropdownList = searchInput.closest('.dropdown-list');
                const optionsList = dropdownList.querySelectorAll('li');

                // Debounce search to prevent lag
                clearTimeout(searchInput.dataset.timeout);
                searchInput.dataset.timeout = setTimeout(() => {
                    const filter = searchInput.value.toLowerCase();
                    optionsList.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        option.style.display = text.includes(filter) ? 'block' : 'none';
                    });
                }, 200);
            }
        });

        // Handle option selection
        document.addEventListener('click', function(e) {
            const option = e.target.closest('.custom-select-wrapper .dropdown-list li');
            if (option) {
                e.stopPropagation();
                const wrapper = option.closest('.custom-select-wrapper');
                const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                const toggleInput = wrapper.querySelector('.dropdown-toggle');
                const dropdownList = wrapper.querySelector('.dropdown-list');
                const searchInput = wrapper.querySelector('.dropdown-search');

                const value = option.getAttribute('data-value');
                const text = option.textContent;
                hiddenInput.value = value;
                toggleInput.value = text;
                dropdownList.classList.remove('active');
                searchInput.value = '';
                dropdownList.querySelectorAll('li').forEach(opt => opt.style.display = 'block');

                // Trigger change event for exchange rate update
                if (hiddenInput.id === 'currencyEdit') {
                    $(hiddenInput).trigger('change');
                }
            }
        });

        // Handle keyboard navigation
        document.addEventListener('keydown', function(e) {
            const searchInput = e.target.closest('.dropdown-search');
            if (!searchInput) return;

            const dropdownList = searchInput.closest('.dropdown-list');
            const visibleOptions = Array.from(dropdownList.querySelectorAll('li')).filter(opt => opt.style.display !== 'none');
            if (visibleOptions.length === 0) return;

            let selectedIndex = visibleOptions.findIndex(opt => opt.classList.contains('focused')) || 0;

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
                const wrapper = dropdownList.closest('.custom-select-wrapper');
                const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                const toggleInput = wrapper.querySelector('.dropdown-toggle');

                hiddenInput.value = option.getAttribute('data-value');
                toggleInput.value = option.textContent;
                dropdownList.classList.remove('active');
                searchInput.value = '';
                dropdownList.querySelectorAll('li').forEach(opt => opt.style.display = 'block');

                if (hiddenInput.id === 'currencyEdit') {
                    $(hiddenInput).trigger('change');
                }
            } else if (e.key === 'Escape') {
                dropdownList.classList.remove('active');
                searchInput.value = '';
                dropdownList.querySelectorAll('li').forEach(opt => opt.style.display = 'block');
            }
        });
    }

    // Initialize dropdowns
    initializeDropdowns();
    // Re-initialize dropdowns after AJAX content load
    $(document).ajaxComplete(function() {
        initializeDropdowns();
    });

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
    $("#editReceiptBtn").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            transactionDate: $("#incomeDateEdit").val(),
            payeePayer: $("#incomePayeeEdit").val(),
            details: $("#incomeDescriptionEdit").val(),
            produce: $("#farmProduceEdit").val(),
            invoiceNo: $("#invoiceNumberEdit").val(),
            currency: $("#currencyEdit").val(),
            amount: $("#incomeAmountEdit").val(),
            exchangeRate: $("#exchangeRateEdit").val(),
            ghsEquivalent: $("#ghsEquivalentEdit").val(),
            transactionType: "Payment",
            nominalAccount: $("#incomeCategoryEdit").val(),
            transactionId: '<?php echo $i_id ?>'
        };

        var url = "ajaxscripts/queries/editPayment.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                showSuccessAlert("Receipt updated successfully!");
                setTimeout(function() {
                    $('#receipts-tab').tab('show');
                }, 0);
                loadPage("ajaxscripts/tables/receipts.php", function(response) {
                    $('#pageTable').html(response);
                });
                loadPage("ajaxscripts/forms/addReceipt.php", function(response) {
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
                firstEmptyField = firstEmptyField || '#incomeDateEdit';
            }
            if (!formData.payeePayer) {
                error += 'Please enter payee\n';
                firstEmptyField = firstEmptyField || '#incomePayeeEdit';
            }
            if (!formData.currency) {
                error += 'Please select currency\n';
                firstEmptyField = firstEmptyField || '#currencyEdit + .dropdown-toggle';
            }
            if (!formData.amount) {
                error += 'Please enter amount\n';
                firstEmptyField = firstEmptyField || '#incomeAmountEdit';
            }
            if (!formData.nominalAccount) {
                error += 'Please select nominal account\n';
                firstEmptyField = firstEmptyField || '#incomeCategoryEdit + .dropdown-toggle';
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
});
</script>