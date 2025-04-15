<?php include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getProd = $mysqli->query("select * from producelist where prodId = '$i_id'");
$resProd = $getProd->fetch_assoc();

?>
<form autocomplete="off" id="addProductForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Edit Product</h5>
        <p class="mb-0 text-sm">Edit farm product</p>

        <div class="row mt-3">
            <div class="col-12">
                <label for="productName">Product Name</label>
                <input id="productName" class="form-control" type="text" name="productName" placeholder="Enter product name" required value="<?php echo $resProd['prodName']; ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <label for="productDescription">Product Description</label>
                <textarea id="productDescription" class="form-control" name="productDescription" rows="3" placeholder="Enter product description"><?php echo $resProd['prodDescription']; ?></textarea>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="produceCategory">Product Category</label>
                <select id="produceCategory" class="form-select" name="productCategory" required>
                    <option value="">Select Category</option>
                    <?php
                    $prodCat = $resProd['prodCategory'];
                    $getCat = $mysqli->query("select * from produce_category where pcatActive = 1");
                    while ($resCat = $getCat->fetch_assoc()) { ?>
                        <option <?php if (@$prodCat == $resCat['pcatName']) echo "Selected" ?>><?php echo $resCat['pcatName'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="productPrice">Price</label>
                <input id="productPrice" class="form-control" type="text" onkeypress="return isAmount(event)" name="productPrice" min="0" step="0.01" placeholder="Enter price" required value="<?php echo $resProd['prodPrice']; ?>">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="productQuantity">Quantity</label>
                <input id="productQuantity" class="form-control" type="text" onkeypress="return isNumber(event)" name="productQuantity" min="0" placeholder="Enter quantity" required value="<?php echo $resProd['prodQuantity'] ?>">
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="productExpiration">Expiration Date</label>
                <input id="productExpiration" class="form-control" type="date" name="productExpiration" placeholder="Enter expiration date" value="<?php echo $resProd['expirationDate'] ?>">
            </div>
        </div>


        <div class="button-row d-flex justify-content-center mt-4">
            <button id="editProduct" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Submit">Update Product</button>
        </div>

    </div>
</form>

<script>
    /*  $("#produceCategory").select2({
        placeholder: "Select Category"
    }); */

    $("#editProduct").click(function() {
        var formData = {
            productName: $("#productName").val(),
            productDescription: $("#productDescription").val(),
            produceCategory: $("#produceCategory").val(),
            productPrice: $("#productPrice").val(),
            productQuantity: $("#productQuantity").val(),
            productExpiration: $("#productExpiration").val(),
            prodId: '<?php echo $i_id; ?>'
        };

        var url = "ajaxscripts/queries/updateProduct.php";

        var successCallback = function(response) {
            console.log(response);
            //alert(response);
            if (response == 'Success') {
                $.notify("Form updated successfully", "success");
                loadPage("ajaxscripts/forms/addProduct.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/products.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }

        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.productName) {
                error += 'Please enter product name\n';
                $("#productName").focus();
            }
            if (!formData.produceCategory) {
                error += 'Please select category\n';
                $("#produceCategory").focus();
            }
            if (!formData.productPrice) {
                error += 'Please enter price\n';
                $("#productPrice").focus();
            }
            if (!formData.productQuantity) {
                error += 'Please enter quantity\n';
                $("#productQuantity").focus();
            }
            // You can add more validation rules as needed

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });
</script>