<?php include('../../config.php'); ?>

<form autocomplete="off" id="farmIncomeForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeName" class="form-label">Receivable <span class="text-danger">*</span></label>
            <input id="incomeName" class="form-control border-radius-md" type="text" placeholder="Enter income name" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="incomeCategory" class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled selected>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `categories` WHERE `categoryStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    echo "<option value='{$resCat['catId']}'>{$resCat['categoryName']}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeAmount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="incomeAmount" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeDate" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="incomeDate" class="form-control border-radius-md" type="text" placeholder="Select date" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeReceipt" class="form-label">Receipt Number (if any)</label>
            <input id="incomeReceipt" class="form-control border-radius-md" type="text" placeholder="Enter receipt number">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeDescription" class="form-label">Description</label>
            <textarea id="incomeDescription" class="form-control border-radius-md" rows="4" placeholder="Enter description"></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveIncome" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Income
        </button>
    </div>
</form>

<script>
    $("#incomeCategory").select2({
        placeholder: "Select Category",
        dropdownParent: $("#addIncomeModal"),
        width: '100%' 
    });

    $("#incomeDate").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    $("#saveIncome").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            transactionName: $("#incomeName").val(),
            transactionDescription: $("#incomeDescription").val(),
            transactionCategory: $("#incomeCategory").val(),
            transactionAmount: $("#incomeAmount").val(),
            transactionDate: $("#incomeDate").val(),
            transactionReceipt: $("#incomeReceipt").val()
        };

        var url = "ajaxscripts/queries/addIncome.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Income saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                // Reset form and plugins
                $("#farmIncomeForm")[0].reset();
                $("#incomeCategory").val(null).trigger('change');
                $("#incomeDate").flatpickr().clear();
                $('#addIncomeModal').modal('hide');
                $('#addIncomeModal').on('hidden.bs.modal', function () {
                    loadPage("ajaxscripts/tables/income.php", function (response) {
                        $('#pageTable').html(response);
                    });
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
            if (!formData.transactionName) error += 'Please enter income name\n';
            if (!formData.transactionCategory) error += 'Please select category\n';
            if (!formData.transactionAmount) error += 'Please enter amount\n';
            if (!formData.transactionDate) error += 'Please select date\n';
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>
