<?php include('../../config.php'); ?>

<form autocomplete="off" id="categoryForm">
    <div class="row g-3">
        <div class="col-12">
            <label for="categoryName" class="form-label">Category Name <span class="text-danger">*</span></label>
            <input id="categoryName" class="form-control border-radius-md" type="text" name="categoryName" placeholder="Enter category name" required>
        </div>
        <div class="col-12">
            <label for="categoryDescription" class="form-label">Description</label>
            <textarea id="categoryDescription" class="form-control border-radius-md" name="categoryDescription" rows="3" placeholder="Enter description"></textarea>
        </div>
        <div class="col-12">
            <label for="categoryStatus" class="form-label">Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="categoryStatus" name="categoryStatus" checked>
                <label class="form-check-label" for="categoryStatus">Active</label>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveCategory" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Category
        </button>
    </div>
</form>

<script>
    $("#saveCategory").click(function() {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            categoryName: $("#categoryName").val(),
            categoryDescription: $("#categoryDescription").val(),
            categoryStatus: $("#categoryStatus").is(':checked') ? 1 : 0
        };

        var url = "ajaxscripts/queries/addProdCategory.php";

        var successCallback = function(response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Product Category saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#addProdCategoryModal').modal('hide');
                loadPage("ajaxscripts/tables/productCategory.php", function(response) {
                    $('#prodcategoryTable').html(response);
                });
                
                if ($('#addProdCategoryModal').is(':visible')) {
                    loadPage("ajaxscripts/forms/addProductCategory.php", function(response) {
                        $('#prodcategoryForm').html(response);
                    });
                }

            } else {
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