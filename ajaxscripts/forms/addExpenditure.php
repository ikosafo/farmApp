<?php include('../../config.php'); ?>

<form autocomplete="off" id="farmExpenditureForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureName" class="form-label">Expenditure Name <span class="text-danger">*</span></label>
            <input id="expenditureName" class="form-control border-radius-md" type="text" placeholder="Enter expenditure name" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="expenditureCategory" class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled selected>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `expcategory` WHERE `ecatStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    echo "<option value='{$resCat['ecatId']}'>{$resCat['ecatName']}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureAmount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="expenditureAmount" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDate" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="expenditureDate" class="form-control border-radius-md" type="text" placeholder="Select date" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureReceipt" class="form-label">Receipt Number (if any)</label>
            <input id="expenditureReceipt" class="form-control border-radius-md" type="text" placeholder="Enter receipt number">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDescription" class="form-label">Description</label>
            <textarea id="expenditureDescription" class="form-control border-radius-md" rows="4" placeholder="Enter description"></textarea>
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

<script>
    $("#expenditureCategory").select2({
        placeholder: "Select Category",
        dropdownParent: $("#addExpenditureModal"),
        width: '100%' 
    });

    $("#expenditureDate").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    $("#saveExpenditure").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            transactionName: $("#expenditureName").val(),
            transactionDescription: $("#expenditureDescription").val(),
            transactionCategory: $("#expenditureCategory").val(),
            transactionAmount: $("#expenditureAmount").val(),
            transactionDate: $("#expenditureDate").val(),
            transactionReceipt: $("#expenditureReceipt").val()
        };

        var url = "ajaxscripts/queries/addExpenditure.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Expenditure saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#addExpenditureModal').modal('hide');
                loadPage("ajaxscripts/tables/expenditure.php", function (response) {
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
            if (!formData.transactionName) error += 'Please enter expenditure name\n';
            if (!formData.transactionCategory) error += 'Please select category\n';
            if (!formData.transactionAmount) error += 'Please enter amount\n';
            if (!formData.transactionDate) error += 'Please select date\n';
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>
