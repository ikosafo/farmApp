<form autocomplete="off">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Add Produce Category</h5>
        

        <div class="row mt-3">
            <div class="col-12 col-sm-4">
                <label>Category Name</label>
                <input id="categoryName" class="form-control" type="text" placeholder="eg. Vegetables">
            </div>
            <div class="col-12 col-sm-4 mt-3 mt-sm-0">
                <label>Category Code</label>
                <input id="categoryCode" class="form-control" type="text" placeholder="eg. VG">
            </div>
			
			<div class="col-12 col-sm-4 mt-3 mt-sm-0">
                 
				 <button  style="width: 80% !important; float: left; margin-top: 30px;" id="saveCategory" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Save Record">SAVE RECORD >> </button>
				 
            </div>
			
        </div>

        

    </div>
</form>

<script>
    $("#saveCategory").click(function() {
        var formData = {
            categoryName: $("#categoryName").val(),
            categoryCode: $("#categoryCode").val(),
        };

        var url = "ajaxscripts/queries/addCategory.php";

        var successCallback = function(response) {
            console.log(response);
            //alert(response);
            if (response == 'Success') {
                $.notify("Form saved successfully", "success");
                loadPage("ajaxscripts/forms/productCategory.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/productCategories.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }

        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.categoryName) {
                error += 'Please enter category name\n';
                $("#categoryName").focus();
            }
            if (!formData.categoryCode) {
                error += 'Please enter category code\n';
                $("#categoryCode").focus();
            }

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });
</script>