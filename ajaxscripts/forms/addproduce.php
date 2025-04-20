<?php include('../../config.php'); ?>

<form autocomplete="off" id="farmProduceForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
            <input id="productName" class="form-control border-radius-md" type="text" placeholder="Enter product name" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="productCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="productCategory" class="form-control border-radius-md" name="productCategory" required>
                <option value="" disabled selected>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `prodcategory` WHERE `pcatStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    echo "<option value='{$resCat['pcatId']}'>{$resCat['pcatName']}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="producePrice" class="form-label">Price <span class="text-danger">*</span></label>
            <input id="producePrice" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expiryDate" class="form-label">Expiry Date <span class="text-danger">*</span></label>
            <input id="expiryDate" class="form-control border-radius-md" type="text" placeholder="Select date" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="productQuantity" class="form-label">Quantity <span class="text-danger">*</span></label>
            <div class="input-group">
                <input id="productQuantity" class="form-control border-radius-md" type="number" step="1" min="1" placeholder="Enter quantity" required>
                <select id="quantityUnit" class="form-control border-radius-md" name="quantityUnit" required>
                    <option value="" disabled selected>Select unit</option>
                    <option value="kilo">Kilo</option>
                    <option value="gram">Gram</option>
                    <option value="milligram">Milligram</option>
                    <option value="acre">Acre</option>
                    <option value="hectare">Hectare</option>
                    <option value="bags">Bag(s)</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <label for="productDescription" class="form-label">Description</label>
            <textarea id="productDescription" class="form-control border-radius-md" rows="4" placeholder="Enter description"></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveProduce" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Save Produce
        </button>
    </div>
</form>

<script>
// Ensure the DOM is fully loaded before initializing plugins
$(document).ready(function() {
    // Initialize flatpickr for expiry date
    $("#expiryDate").flatpickr();

    // Form submission
    $("#saveProduce").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            productName: $("#productName").val(),
            productDescription: $("#productDescription").val(),
            produceCategory: $("#productCategory").val(),
            productPrice: $("#producePrice").val(),
            productExpiration: $("#expiryDate").val(),
            productQuantity: $("#productQuantity").val(),
            quantityUnit: $("#quantityUnit").val()
        };

        var url = "ajaxscripts/queries/addProduce.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Product saved successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#addproduceModal').modal('hide');
                loadPage("ajaxscripts/tables/produce.php", function (response) {
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
            if (!formData.productName) error += 'Please enter product name\n';
            if (!formData.produceCategory) error += 'Please select category\n';
            if (!formData.productPrice) error += 'Please enter price\n';
            if (!formData.productExpiration) error += 'Please select expiry date\n';
            if (!formData.productQuantity) error += 'Please enter quantity\n';
            if (!formData.quantityUnit) error += 'Please select quantity unit\n';
            return error;
        };

        saveForm(formData, url, successCallback, validateForm);
    });
});
</script>