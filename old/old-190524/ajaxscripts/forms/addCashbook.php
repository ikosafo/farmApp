<?php include('../../config.php'); ?>

<form autocomplete="off" id="cashBookForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Cash Book</h5>
        <p class="mb-0 text-sm">Record new transaction</p>

        <div class="row mt-3">
            <div class="col-12">
                <label for="transactionDate">Date</label>
                <input id="transactionDate" class="form-control" type="date" name="transactionDate" placeholder="Enter transaction date" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="transactionDescription">Description</label>
                <textarea id="transactionDescription" class="form-control" name="transactionDescription" rows="3" placeholder="Enter transaction description" required></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="transactionCategory">Category</label>
                <select id="transactionCategory" class="form-select" name="transactionCategory" required>
                    <option value="">Select Category</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="transactionAmount">Amount</label>
                <input id="transactionAmount" class="form-control" type="text" onkeypress="return isAmount(event)" name="transactionAmount" min="0" step="0.01" placeholder="Enter amount" required>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="transactionType">Type</label>
                <select id="transactionType" class="form-select" name="transactionType" required>
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="paymentMethod">Payment Method</label>
                <input id="paymentMethod" class="form-control" type="text" name="paymentMethod" placeholder="Enter payment method">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="referenceNumber">Reference Number</label>
                <input id="referenceNumber" class="form-control" type="text" name="referenceNumber" placeholder="Enter reference number">
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="recipientPayer">Recipient/Payer</label>
                <input id="recipientPayer" class="form-control" type="text" name="recipientPayer" placeholder="Enter recipient/payer">
            </div>
        </div>

        <div class="button-row d-flex justify-content-center mt-4">
            <button id="saveTransaction" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Submit">Submit</button>
        </div>
    </div>
</form>

<script>
    $("#saveTransaction").click(function() {
        var formData = {
            transactionDate: $("#transactionDate").val(),
            transactionDescription: $("#transactionDescription").val(),
            transactionCategory: $("#transactionCategory").val(),
            transactionAmount: $("#transactionAmount").val(),
            transactionType: $("#transactionType").val(),
            paymentMethod: $("#paymentMethod").val(),
            referenceNumber: $("#referenceNumber").val(),
            recipientPayer: $("#recipientPayer").val()
        };

        var url = "ajaxscripts/queries/addTransaction.php";

        var successCallback = function(response) {
            console.log(response);
            if (response === 'Success') {
                $.notify("Transaction saved successfully", "success");
                loadPage("ajaxscripts/forms/addTransaction.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/transactions.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }
        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.transactionDate) {
                error += 'Please select a transaction date\n';
                $("#transactionDate").focus();
            }
            if (!formData.transactionDescription) {
                error += 'Please enter a transaction description\n';
                $("#transactionDescription").focus();
            }
            if (!formData.transactionCategory) {
                error += 'Please select a transaction category\n';
                $("#transactionCategory").focus();
            }
            if (!formData.transactionAmount) {
                error += 'Please enter a transaction amount\n';
                $("#transactionAmount").focus();
            }
            // You can add more validation rules as needed

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });
</script>