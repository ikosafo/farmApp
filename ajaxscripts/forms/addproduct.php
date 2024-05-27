<?php include('../../config.php'); ?>

<form autocomplete="off" id="addProductForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom: 20px; padding-bottom: 40px !important;">
        <h5 class="font-weight-bolder mb-0">Add Farm Produce</h5>
        

		
		  <div class="row mt-3">
            <div class="col-12 col-sm-6">
                 <label for="productName">Produce Name</label>
                <input id="productName" class="form-control" type="text" name="productName" placeholder="Enter produce name" required>
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
               <label for="productDescription">Produce Description</label>
                <textarea id="productDescription" class="form-control" name="productDescription" rows="1" placeholder="Enter product description"></textarea>
            </div>
			
			
			
        </div>
		
		
		 <div class="row mt-3">
		 <div class="col-12 col-sm-2 mt-3 mt-sm-0">
                <label for="produceCategory">Produce Category</label>
                <select id="produceCategory" class="form-select" name="productCategory" required>
                    <option value="">Select Category</option>
                    <?php
                    $getCat = $mysqli->query("select * from produce_category where pcatActive = 1");
                    while ($resCat = $getCat->fetch_assoc()) { ?>
                        <option value="<?php echo $resCat['pcatName'] ?>"><?php echo $resCat['pcatName'] ?></option>
                    <?php } ?>
                </select>
            </div>
		 
            <div class="col-12 col-sm-2">
               <label for="productPrice">Price</label>
                <input id="productPrice" class="form-control" type="text" onkeypress="return isAmount(event)" name="productPrice" min="0" step="0.01" placeholder="Enter price" required>
            </div>
			
			<div class="col-12 col-sm-1 mt-3 mt-sm-0">
                 <label for="productQuantity">Per(kg)</label>
                <select class="form-select" name="" >
				<option>kilo</option>
				<option>gram</option>
				<option>milligram</option>
				<option>acre </option>
				<option>hectare  </option>
				<option>bag(s)  </option>
				</select>
				
            </div>
			
            <div class="col-12 col-sm-1 mt-3 mt-sm-0">
                 <label for="productQuantity">Quantity</label>
                <input id="productQuantity" class="form-control" type="text" onkeypress="return isNumber(event)" name="productQuantity" min="0" placeholder="Enter quantity" required>
            </div>
			
			<div class="col-12 col-sm-2 mt-3 mt-sm-0">
                <label for="productExpiration">Expiration Date</label>
                <input id="productExpiration" class="form-control" type="date" name="productExpiration" placeholder="Enter expiration date">
            </div>
			
			
			<div class="col-12 col-sm-3 mt-3 mt-sm-0">
                
			<button  style="width: 80% !important; float: left; margin-top: 30px;" id="saveProduct" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Save Record">SAVE RECORD >> </button>
      
		</div>
            
			
			
        </div>
	
        
		
		 
    </div>
</form>










<script>
    /*  $("#produceCategory").select2({
        placeholder: "Select Category"
    }); */

    $("#saveProduct").click(function() {
        var formData = {
            productName: $("#productName").val(),
            productDescription: $("#productDescription").val(),
            produceCategory: $("#produceCategory").val(),
            productPrice: $("#productPrice").val(),
            productQuantity: $("#productQuantity").val(),
            productExpiration: $("#productExpiration").val(),
        };

        var url = "ajaxscripts/queries/addProduct.php";

        var successCallback = function(response) {
            console.log(response);
            //alert(response);
            if (response == 'Success') {
                $.notify("Form saved successfully", "success");
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