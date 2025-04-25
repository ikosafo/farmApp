<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getInc = $mysqli->query("select * from `categories` where `catId` = '$i_id'");
$resInc = $getInc->fetch_assoc();

?>
<form autocomplete="off" id="categoryForm">
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Category Name <span class="text-danger">*</span></label>
            <input class="form-control border-radius-md" type="text" 
            placeholder="Enter category name" disabled value="<?= $resInc['categoryName']?>">
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea disabled class="form-control border-radius-md" 
            rows="3" placeholder="Enter description"><?= $resInc['categoryDescription']?></textarea>
        </div>
        <?php
            $statusChecked = $resInc['categoryStatus'] == 1 ? 'checked' : '';
            $statusLabel = $resInc['categoryStatus'] == 1 ? 'Active' : 'Inactive';
            ?>

        <div class="col-12">
            <label for="categoryStatus" class="form-label">Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="categoryStatus" 
                    <?= $statusChecked ?> disabled>
                <label class="form-check-label" for="categoryStatus"><?= $statusLabel ?></label>
            </div>
        </div>

    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>

