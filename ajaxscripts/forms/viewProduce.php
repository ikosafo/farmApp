<?php
include('../../config.php');
include('../../includes/functions.php');

// Sanitize and decrypt the product ID
$i_id = unlock(unlock($_POST['i_index']));
$getProd = $mysqli->query("SELECT * FROM producelist WHERE prodId = '$i_id'");
$resProd = $getProd->fetch_assoc();
?>

<form autocomplete="off" id="farmProduceForm">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="productName" class="form-label">Product Name <span class="text-danger">*</span></label>
            <input id="productName" class="form-control border-radius-md" type="text" value="<?php echo htmlspecialchars($resProd['prodName']); ?>" readonly>
        </div>
        <div class="col-12 col-md-6">
            <label for="productCategory" class="form-label">Category <span class="text-danger">*</span></label>
            <select id="productCategory" class="form-control border-radius-md" name="productCategory" disabled>
                <option value="" disabled>Select a category</option>
                <?php
                $getCat = $mysqli->query("SELECT * FROM `prodcategory` WHERE `pcatStatus` = 1");
                while ($resCat = $getCat->fetch_assoc()) {
                    $selected = ($resCat['pcatId'] == $resProd['prodCategory']) ? 'selected' : '';
                    echo "<option value='{$resCat['pcatId']}' $selected>" . htmlspecialchars($resCat['pcatName']) . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="producePrice" class="form-label">Price <span class="text-danger">*</span></label>
            <input id="producePrice" class="form-control border-radius-md" type="number" min="0" step="0.01" value="<?php echo htmlspecialchars($resProd['prodPrice']); ?>" readonly>
        </div>
        <div class="col-12 col-md-6">
            <label for="expiryDate" class="form-label">Expiry Date <span class="text-danger">*</span></label>
            <input id="expiryDate" class="form-control border-radius-md" type="text" value="<?php echo htmlspecialchars($resProd['expirationDate']); ?>" readonly>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <label for="productQuantity" class="form-label">Quantity <span class="text-danger">*</span></label>
            <div class="input-group">
                <input id="productQuantity" class="form-control border-radius-md" type="number" step="1" min="1" value="<?php echo htmlspecialchars($resProd['prodQuantity']); ?>" readonly>
                <select id="quantityUnit" class="form-control border-radius-md" name="quantityUnit" disabled>
                    <option value="" disabled>Select unit</option>
                    <?php
                    $units = ['kilo' => 'Kilo', 'gram' => 'Gram', 'milligram' => 'Milligram', 'acre' => 'Acre', 'hectare' => 'Hectare', 'bags' => 'Bag(s)'];
                    foreach ($units as $value => $label) {
                        $selected = ($value == $resProd['quantityUnit']) ? 'selected' : '';
                        echo "<option value='$value' $selected>$label</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <label for="productDescription" class="form-label">Description</label>
            <textarea id="productDescription" class="form-control border-radius-md" rows="4" readonly><?php echo htmlspecialchars($resProd['prodDescription']); ?></textarea>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal">Close</button>
    </div>
</form>

<style>
/* Style readonly/disabled inputs to look consistent with enabled inputs */
.form-control:read-only,
.form-control:disabled,
.form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
    cursor: not-allowed;
}
</style>