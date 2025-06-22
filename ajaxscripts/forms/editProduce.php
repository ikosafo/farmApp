<?php
include('../../config.php');
include('../../includes/functions.php');

// Sanitize and decrypt the product ID
$i_id = unlock(unlock($_POST['i_index']));
$getProd = $mysqli->query("SELECT * FROM producelist WHERE prodId = '$i_id'");
$resProd = $getProd->fetch_assoc();

// Check if product exists
if (!$resProd) {
    echo "Error: Product not found";
    exit;
}
?>

<form autocomplete="off" id="farmProduceFormEdit">
    <input type="hidden" id="prodIdEdit" name="prodIdEdit" value="<?php echo htmlspecialchars($resProd['prodId'] ?? ''); ?>">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="productNameEdit" class="form-label">Product Name <span class="text-danger">*</span></label>
            <input id="productNameEdit" name="productNameEdit" class="form-control border-radius-md" type="text" placeholder="Enter product name" value="<?php echo htmlspecialchars($resProd['prodName'] ?? ''); ?>" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="productCategoryEdit" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="productCategoryEdit" name="productCategoryEdit" class="form-control border-radius-md" required>
                <option value="" disabled selected>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `prodcategory` WHERE `pcatStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    $selected = ($resCat['pcatId'] == ($resProd['prodCategory'] ?? '')) ? 'selected' : '';
                    echo "<option value='{$resCat['pcatId']}' $selected>" . htmlspecialchars($resCat['pcatName'] ?? '') . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="producePriceEdit" class="form-label">Price <span class="text-danger">*</span></label>
            <input id="producePriceEdit" name="producePriceEdit" class="form-control border-radius-md" type="number" min="0" step="0.01" placeholder="Enter amount" value="<?php echo htmlspecialchars($resProd['prodPrice'] ?? ''); ?>" required>
        </div>
        <div class="col-12 col-md-6">
            <label for="expiryDateEdit" class="form-label">Expiry Date <span class="text-danger">*</span></label>
            <input id="expiryDateEdit" name="expiryDateEdit" class="form-control border-radius-md" type="text" placeholder="Select date" value="<?php echo htmlspecialchars($resProd['expirationDate'] ?? ''); ?>" required>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="productQuantityEdit" class="form-label">Quantity <span class="text-danger">*</span></label>
            <div class="input-group">
                <input id="productQuantityEdit" name="productQuantityEdit" class="form-control border-radius-md" type="number" step="1" min="1" placeholder="Enter quantity" value="<?php echo htmlspecialchars($resProd['prodQuantity'] ?? ''); ?>" required>
                <select id="quantityUnitEdit" name="quantityUnitEdit" class="form-control border-radius-md" required>
                    <option value="" disabled selected>Select unit</option>
                    <?php
                    $units = ['kilo' => 'Kilo', 'gram' => 'Gram', 'milligram' => 'Milligram', 'acre' => 'Acre', 'hectare' => 'Hectare', 'bags' => 'Bag(s)'];
                    $selectedUnit = $resProd['quantityUnit'] ?? '';
                    foreach ($units as $value => $label) {
                        $selected = ($value === $selectedUnit) ? 'selected' : '';
                        echo "<option value='$value' $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <label for="productDescriptionEdit" class="form-label">Description</label>
            <textarea id="productDescriptionEdit" name="productDescriptionEdit" class="form-control border-radius-md" rows="4" placeholder="Enter description"><?php echo htmlspecialchars($resProd['prodDescription'] ?? ''); ?></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveProduceEdit" class="btn bg-gradient-primary">
            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
            Update Produce
        </button>
    </div>
</form>

<script>
// Ensure the DOM is fully loaded before initializing plugins
$(document).ready(function() {
    // Initialize flatpickr for expiry date
    $("#expiryDateEdit").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });

    // Form submission
    $("#saveProduceEdit").click(function () {
        var $button = $(this);
        var $spinner = $button.find('.spinner-border');
        $spinner.removeClass('d-none');

        var formData = {
            prodId: $("#prodIdEdit").val(),
            productName: $("#productNameEdit").val(),
            productDescription: $("#productDescriptionEdit").val(),
            produceCategory: $("#productCategoryEdit").val(),
            productPrice: $("#producePriceEdit").val(),
            productExpiration: $("#expiryDateEdit").val(),
            productQuantity: $("#productQuantityEdit").val(),
            quantityUnit: $("#quantityUnitEdit").val()
        };

        var url = "ajaxscripts/queries/editProduce.php";

        var successCallback = function (response) {
            $spinner.addClass('d-none');
            if (response === 'Success') {
                $.notify("Product updated successfully!", {
                    className: "success",
                    position: "top right"
                });
                $('#editProduceModal').modal('hide');
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