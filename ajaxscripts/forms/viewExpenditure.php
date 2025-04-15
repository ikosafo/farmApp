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
            <input id="expenditureName" class="form-control border-radius-md" disabled
             type="text" placeholder="Enter expenditure name" value="<?= $resExp['transactionName'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="expenditureCategory" disabled class="form-control border-radius-md" required style="width: 100%;">
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
            <input id="expenditureAmount" class="form-control border-radius-md" disabled
             type="number" min="0" step="0.01" placeholder="Enter amount" required value="<?= $resExp['transactionAmount'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDate" class="form-label">Date <span class="text-danger">*</span></label>
            <input id="expenditureDate" class="form-control border-radius-md" type="text" disabled
            placeholder="Select date" required value="<?= $resExp['transactionDate'] ?>">
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="expenditureReceipt" class="form-label">Receipt Number (if any)</label>
            <input id="expenditureReceipt" class="form-control border-radius-md" type="text" disabled 
            placeholder="Enter receipt number" value="<?= $resExp['transactionReceipt'] ?>">
        </div>
        <div class="col-12 col-md-6">
            <label for="expenditureDescription" class="form-label">Description</label>
            <textarea id="expenditureDescription" class="form-control border-radius-md"
             rows="4" disabled
             placeholder="Enter description"><?= $resExp['transactionDescription'] ?></textarea>
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