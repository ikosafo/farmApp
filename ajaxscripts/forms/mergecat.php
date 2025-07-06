<?php include('../../config.php'); ?>

<form autocomplete="off" id="mergeCategoryForm">
    <div class="row g-3">
        <div class="col-12">
            <label for="fromCategory" class="form-label">Category to Merge From <span class="text-danger">*</span></label>
            <select id="fromCategory" class="form-control border-radius-md" name="fromCategory" required>
                <option value="">Select category to merge</option>
                <?php
                $sql = "SELECT catId, categoryName FROM categories WHERE categoryStatus = 1 ORDER BY categoryName";
                $result = mysqli_query($mysqli, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['catId']}'>{$row['categoryName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-12">
            <label for="toCategory" class="form-label">Category to Merge Into <span class="text-danger">*</span></label>
            <select id="toCategory" class="form-control border-radius-md" name="toCategory" required>
                <option value="">Select category to keep</option>
                <?php
                $result = mysqli_query($mysqli, $sql);
                mysqli_data_seek($result, 0); // Reset result pointer to reuse
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['catId']}'>{$row['categoryName']}</option>";
                }
                mysqli_free_result($result);
                ?>
            </select>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="mergeCategory" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Merge Categories
        </button>
    </div>
</form>

<script>
    $("#mergeCategory").click(function() {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            fromCategory: $("#fromCategory").val(),
            toCategory: $("#toCategory").val()
        };

        var url = "ajaxscripts/queries/mergeCategory.php";

        var successCallback = function(response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Categories merged successfully!", {
                    className: "success",
                    position: "top right"
                });
                loadPage("ajaxscripts/forms/mergecat.php", function(response) {
                    $('#mergecatForm').html(response);
                });
            } else if (response.startsWith('Error:')) {
                $.notify(response, {
                    className: "error",
                    position: "top right"
                });
            } else {
                $.notify(response, {
                    className: "error",
                    position: "top right"
                });
            }
        };

        var validateForm = function(formData) {
            var error = '';
            if (!formData.fromCategory) {
                error += 'Please select a category to merge from\n';
                $("#fromCategory").focus();
            }
            if (!formData.toCategory) {
                error += 'Please select a category to merge into\n';
                $("#toCategory").focus();
            }
            if (formData.fromCategory === formData.toCategory) {
                error += 'Cannot merge a category into itself\n';
                $("#toCategory").focus();
            }
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
</script>