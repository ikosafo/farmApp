<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getinc = $mysqli->query("select * from `transactions` where transId = '$i_id'");
$resInc = $getinc->fetch_assoc();
?>
<form autocomplete="off" id="farmIncomeForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeNameEdit" class="form-label">Income Name <span class="text-danger">*</span></label>
            <input id="incomeNameEdit" class="form-control border-radius-md"
             type="text" placeholder="Enter income name" value="<?= $resInc['transactionName'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeCategoryEdit" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="incomeCategoryEdit" class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `categories` WHERE `categoryStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    $selected = ($resCat['catId'] == $resInc['transactionCategory']) ? 'selected' : '';
                    echo "<option value='{$resCat['catId']}' $selected>" . htmlspecialchars($resCat['categoryName']) . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeAmountEdit" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="incomeAmountEdit" class="form-control border-radius-md"
             type="number" min="0" step="0.01" placeholder="Enter amount" required value="<?= $resInc['transactionAmount'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeDateEdit" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="incomeDateEdit" class="form-control border-radius-md" type="text"
            placeholder="Select date" required value="<?= $resInc['transactionDate'] ?>">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeReceiptEdit" class="form-label">Receipt Number (if any)</label>
            <input id="incomeReceiptEdit" class="form-control border-radius-md" type="text" 
            placeholder="Enter receipt number" value="<?= $resInc['transactionReceipt'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeDescriptionEdit" class="form-label">Description</label>
            <textarea id="incomeDescriptionEdit" class="form-control border-radius-md"
             rows="4"
             placeholder="Enter description"><?= $resInc['transactionDescription'] ?></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="updateIncome" class="btn bg-gradient-warning">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Income
        </button>
    </div>
</form>



<script>
   /* $("#incomeCategoryEdit").select2({
        placeholder: "Select Category",
        width: '100%' 
    }); */

    $("#incomeDateEdit").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    $("#updateIncome").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            transactionName: $("#incomeNameEdit").val(),
            transactionDescription: $("#incomeDescriptionEdit").val(),
            transactionCategory: $("#incomeCategoryEdit").val(),
            transactionAmount: $("#incomeAmountEdit").val(),
            transactionDate: $("#incomeDateEdit").val(),
            transactionReceipt: $("#incomeReceiptEdit").val(),
            transIndex: '<?php echo $i_id ?>'
        };

        var url = "ajaxscripts/queries/editIncome.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Income saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#editIncomeModal').modal('hide');
                $('#incomeModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                loadPage("ajaxscripts/tables/income.php", function (response) {
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
            if (!formData.transactionName) error += 'Please enter income name\n';
            if (!formData.transactionCategory) error += 'Please select category\n';
            if (!formData.transactionAmount) error += 'Please enter amount\n';
            if (!formData.transactionDate) error += 'Please select date\n';
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>