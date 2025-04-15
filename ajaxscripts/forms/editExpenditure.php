<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getExp = $mysqli->query("select * from `transactions` where transId = '$i_id'");
$resExp = $getExp->fetch_assoc();
?>
<form autocomplete="off" id="farmExpenditureForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureName" class="form-label">Expenditure Name <span class="text-danger">*</span></label>
            <input id="expenditureName" class="form-control border-radius-md"
             type="text" placeholder="Enter expenditure name" value="<?= $resExp['transactionName'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureCategoryEdit" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="expenditureCategoryEdit" class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `expcategory` WHERE `ecatStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    $selected = ($resCat['ecatId'] == $resExp['transactionCategory']) ? 'selected' : '';
                    echo "<option value='{$resCat['ecatId']}' $selected>" . htmlspecialchars($resCat['ecatName']) . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureAmount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="expenditureAmount" class="form-control border-radius-md"
             type="number" min="0" step="0.01" placeholder="Enter amount" required value="<?= $resExp['transactionAmount'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDateEdit" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="expenditureDateEdit" class="form-control border-radius-md" type="text"
            placeholder="Select date" required value="<?= $resExp['transactionDate'] ?>">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureReceipt" class="form-label">Receipt Number (if any)</label>
            <input id="expenditureReceipt" class="form-control border-radius-md" type="text" 
            placeholder="Enter receipt number" value="<?= $resExp['transactionReceipt'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDescription" class="form-label">Description</label>
            <textarea id="expenditureDescription" class="form-control border-radius-md"
             rows="4"
             placeholder="Enter description"><?= $resExp['transactionDescription'] ?></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="updateExpenditure" class="btn bg-gradient-warning">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Expenditure
        </button>
    </div>
</form>



<script>
   $("#expenditureCategoryEdit").select2({
        placeholder: "Select Category",
        width: '100%' 
    });

    $("#expenditureDateEdit").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    $("#updateExpenditure").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            transactionName: $("#expenditureName").val(),
            transactionDescription: $("#expenditureDescription").val(),
            transactionCategory: $("#expenditureCategoryEdit").val(),
            transactionAmount: $("#expenditureAmount").val(),
            transactionDate: $("#expenditureDateEdit").val(),
            transactionReceipt: $("#expenditureReceipt").val(),
            transIndex: '<?php echo $i_id ?>'
        };

        var url = "ajaxscripts/queries/editExpenditure.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Expenditure saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#addExpenditureModal').modal('hide');
                $('#expenditureModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
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