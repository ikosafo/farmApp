<?php include('../../config.php'); ?>

<form autocomplete="off" id="cashbookTransactionForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="transactionDate" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="transactionDate" class="form-control border-radius-md" type="text" placeholder="Select date" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="payeePayer" class="form-label">Payee/Payer <span class="text-danger">*</span></label>
            <input id="payeePayer" class="form-control border-radius-md" type="text" placeholder="Enter payee or payer" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="details" class="form-label">Details <span class="text-danger">*</span></label>
            <textarea id="details" class="form-control border-radius-md" rows="4" placeholder="Enter transaction details" required></textarea>
        </div>
        <div class="col-12 col-md-6">
            <label for="produce" class="form-label">Produce</label>
            <input id="produce" class="form-control border-radius-md" type="text" placeholder="Enter produce (if any)">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="invoiceNo" class="form-label">Invoice Number</label>
            <input id="invoiceNo" class="form-control border-radius-md" type="text" placeholder="Enter invoice number">
        </div>
        <div class="col-12 col-md-6">
            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
            <select id="currency" class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled selected>Select currency</option>
                <option value="GHS">GHS</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="amount" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="exchangeRate" class="form-label">Exchange Rate</label>
            <input id="exchangeRate" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter exchange rate">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="ghsEquivalent" class="form-label">GHS Equivalent</label>
            <input id="ghsEquivalent" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter GHS equivalent">
        </div>
        <div class="col-12 col-md-6">
            <label for="transactionType" class="form-label">Transaction Type <span class="text-danger">*</span></label>
            <select id="transactionType" class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled selected>Select transaction type</option>
                <option value="Receipt">Receipt</option>
                <option value="Payment">Payment</option>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="nominalAccount" class="form-label">Nominal Account</label>
            <input id="nominalAccount" class="form-control border-radius-md" type="text" placeholder="Enter nominal account">
        </div>
        <div class="col-12 col-md-6">
            <label for="receipt" class="form-label">Receipt</label>
            <input id="receipt" class="form-control border-radius-md" type="text" placeholder="Enter receipt details">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="payment" class="form-label">Payment</label>
            <input id="payment" class="form-control border-radius-md" type="text" placeholder="Enter payment details">
        </div>
        <div class="col-12 col-md-6">
            <label for="balance" class="form-label">Balance</label>
            <input id="balance" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter balance">
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveTransaction" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Transaction
        </button>
    </div>
</form>

<script>
    $("#currency").select2({
        placeholder: "Select Currency",
        dropdownParent: $("#addTransactionModal"),
        width: '100%' 
    });

    $("#transactionType").select2({
        placeholder: "Select Transaction Type",
        dropdownParent: $("#addTransactionModal"),
        width: '100%' 
    });

    $("#transactionDate").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    $("#saveTransaction").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            transactionDate: $("#transactionDate").val(),
            payeePayer: $("#payeePayer").val(),
            details: $("#details").val(),
            produce: $("#produce").val(),
            invoiceNo: $("#invoiceNo").val(),
            currency: $("#currency").val(),
            amount: $("#amount").val(),
            exchangeRate: $("#exchangeRate").val(),
            ghsEquivalent: $("#ghsEquivalent").val(),
            transactionType: $("#transactionType").val(),
            nominalAccount: $("#nominalAccount").val(),
            receipt: $("#receipt").val(),
            payment: $("#payment").val(),
            balance: $("#balance").val()
        };

        var url = "ajaxscripts/queries/addTransaction.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Transaction saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#addTransactionModal').modal('hide');
                loadPage("ajaxscripts/tables/cashbook.php", function (response) {
                    $('#pageTable').html(response);
                });
            } else {
                $.notify(response, {
                    className: "error",
                    position: "top right"
                });
            }
        };

        var validateForm = function (formData) {
            var error = '';
            if (!formData.transactionDate) error += 'Please select date\n';
            if (!formData.payeePayer) error += 'Please enter payee/payer\n';
            if (!formData.details) error += 'Please enter details\n';
            if (!formData.currency) error += 'Please select currency\n';
            if (!formData.amount) error += 'Please enter amount\n';
            if (!formData.transactionType) error += 'Please select transaction type\n';
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>