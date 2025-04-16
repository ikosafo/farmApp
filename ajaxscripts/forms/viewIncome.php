<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getInc = $mysqli->query("select * from `transactions` where transId = '$i_id'");
$resInc = $getInc->fetch_assoc();
?>
<form autocomplete="off" id="farmIncomeForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeName" class="form-label">Receivable <span class="text-danger">*</span></label>
            <input id="incomeName" class="form-control border-radius-md" disabled
             type="text" placeholder="Enter income name" value="<?= $resInc['transactionName'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="incomeCategory" disabled class="form-control border-radius-md" required style="width: 100%;">
                <option value="" disabled>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `inccategory` WHERE `icatStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    $selected = ($resCat['icatId'] == $resInc['transactionCategory']) ? 'selected' : '';
                    echo "<option value='{$resCat['icatId']}' $selected>" . htmlspecialchars($resCat['icatName']) . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeAmount" class="form-label">Amount <span class="text-danger">*</span></label>
            <input id="incomeAmount" class="form-control border-radius-md" disabled
             type="number" min="0" step="0.01" placeholder="Enter amount" required value="<?= $resInc['transactionAmount'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeDate" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="incomeDate" class="form-control border-radius-md" type="text" disabled
            placeholder="Select date" required value="<?= $resInc['transactionDate'] ?>">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="incomeReceipt" class="form-label">Receipt Number (if any)</label>
            <input id="incomeReceipt" class="form-control border-radius-md" type="text" disabled 
            placeholder="Enter receipt number" value="<?= $resInc['transactionReceipt'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="incomeDescription" class="form-label">Description</label>
            <textarea id="incomeDescription" class="form-control border-radius-md"
             rows="4" disabled
             placeholder="Enter description"><?= $resInc['transactionDescription'] ?></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>



<script>
    $("#addExpBtn").click(function() {
        loadPage("ajaxscripts/forms/addExpenditure.php", function(response) {
            $('#pageForm').html(response);
        });
    });
</script>