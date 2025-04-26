<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getInc = $mysqli->query("select * from `prodcategory` where `pcatId` = '$i_id'");
$resInc = $getInc->fetch_assoc();

?>
<form autocomplete="off" id="categoryForm">
    <div class="row g-3">
        <div class="col-12">
            <label for="categoryNameEdit" class="form-label">Category Name <span class="text-danger">*</span></label>
            <input id="categoryNameEdit" class="form-control border-radius-md" type="text" 
            placeholder="Enter category name" value="<?= $resInc['pcatName']?>">
        </div>
        <div class="col-12">
            <label for="categoryDescriptionEdit" class="form-label">Description</label>
            <textarea id="categoryDescriptionEdit" class="form-control border-radius-md" 
            rows="3" placeholder="Enter description"><?= $resInc['pcatDesc']?></textarea>
        </div>
        <?php
            $statusChecked = $resInc['pcatStatus'] == 1 ? 'checked' : '';
            $statusLabel = $resInc['pcatStatus'] == 1 ? 'Active' : 'Inactive';
            ?>

        <div class="col-12">
            <label for="categoryStatusEdit" class="form-label">Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="categoryStatusEdit" name="categoryStatusEdit" 
                    <?= $statusChecked ?>>
                <label class="form-check-label" for="categoryStatusEdit"><?= $statusLabel ?></label>
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
            categoryName: $("#categoryNameEdit").val(),
            categoryDescription: $("#categoryDescriptionEdit").val(),
            categoryStatus: $("#categoryStatusEdit").is(':checked') ? 1 : 0,
            catIndex: '<?php echo $i_id ?>'
        };

        var url = "ajaxscripts/queries/editprodcategory.php";

        var successCallback = function(response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Product Category updated successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#editProdCategoryModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                loadPage("ajaxscripts/tables/productCategory.php", function(response) {
                    $('#prodcategoryTable').html(response);
                });

                if ($('#addCategoryModal').is(':visible')) {
                    loadPage("ajaxscripts/forms/addProductCategory.php", function(response) {
                        $('#categoryForm').html(response);
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
                $("#categoryNameEdit").focus();
            }
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>
