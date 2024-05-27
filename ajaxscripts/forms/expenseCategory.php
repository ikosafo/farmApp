<form autocomplete="off">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Add Expenditure Category</h5>
        
        <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label>Indicate Expenditure Category Name</label>
                <input id="categoryName" class="form-control" type="text" placeholder="eg. Equipment and Machinery">
            </div>
            <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                <label>Category Code</label>
                <select class="form-select" id="categoryCode" name="categoryCode">
				<option value="EXP" selected>Expenditure</option>
				<option value="INC">Income</option>
				</select>
            </div>
			
			
			
			 <div class="col-12 col-sm-3 mt-3 mt-sm-0">
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

        var url = "ajaxscripts/queries/addExpenseCategory.php";

        var successCallback = function(response) {
            console.log(response);
            //alert(response);
            if (response == 'Success') {
                $.notify("Form saved successfully", "success");
                loadPage("ajaxscripts/forms/expenseCategory.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/expenseCategories.php", function(response) {
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