<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getExp = $mysqli->query("select * from `expcategory` where `ecatId` = '$i_id'");
$resExp = $getExp->fetch_assoc();

?>
<form autocomplete="off" id="categoryForm">
    <div class="row g-3">
        <div class="col-12">
            <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
            <input id="categoryName" class="form-control border-radius-md" type="text" 
            placeholder="Enter category name" value="<?= $resExp['ecatName']?>">
        </div>
        <div class="col-12">
            <label for="categoryDescription" class="form-label">Description</label>
            <textarea id="categoryDescription" class="form-control border-radius-md" 
            rows="3" placeholder="Enter description"><?= $resExp['ecatDesc']?></textarea>
        </div>
        <?php
            $statusChecked = $resExp['ecatStatus'] == 1 ? 'checked' : '';
            $statusLabel = $resExp['ecatStatus'] == 1 ? 'Active' : 'Inactive';
            ?>

        <div class="col-12">
            <label for="categoryStatus" class="form-label">Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="categoryStatus" name="categoryStatus" 
                    <?= $statusChecked ?>>
                <label class="form-check-label" for="categoryStatus"><?= $statusLabel ?></label>
            </div>
        </div>

    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="editCategory" class="btn bg-gradient-warning">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Category
        </button>
    </div>
</form>


<script>
    $("#editCategory").click(function() {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            categoryName: $("#categoryName").val(),
            categoryDescription: $("#categoryDescription").val(),
            categoryStatus: $("#categoryStatus").is(':checked') ? 1 : 0,
            catIndex: '<?php echo $i_id ?>'
        };

        var url = "ajaxscripts/queries/editExpCategory.php";

        var successCallback = function(response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Category updated successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#addCategoryModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                loadPage("ajaxscripts/tables/expCategory.php", function(response) {
                    $('#categoryTable').html(response);
                });

                if ($('#addExpenditureModal').is(':visible')) {
                    loadPage("ajaxscripts/forms/addExpenditure.php", function(response) {
                        $('#pageForm').html(response);
                    });
                }
            }

            else {
                $.notify(response, {
                    className: "error",
                    position: "top right"
                });
            }
        };

        var validateForm = function(formData) {
            var error = '';
            if (!formData.categoryName) {
                error += 'Please enter category name\n';
                $("#categoryName").focus();
            }
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>
