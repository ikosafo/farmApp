<?php include('../../config.php'); ?>
<form autocomplete="off" id="farmExpenditureForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom: 30px;">
        <h5 class="font-weight-bolder mb-0">Add Farm Expenditure</h5>
        
		  <div class="row mt-3">
            <div class="col-12 col-sm-6">
                <label for="expenditureName">Expenditure Name</label>
                <input id="expenditureName" class="form-control" type="text" name="expenditureName" placeholder="Enter expenditure name" required>
            </div>
            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                <label for="expenditureDescription">Expenditure Description</label>
                <textarea id="expenditureDescription" class="form-control" name="expenditureDescription" rows="1" placeholder="Enter expenditure description"></textarea>
            </div>
			
			
			
        </div>
		
		
		 <div class="row mt-3">
		 <div class="col-12 col-sm-3 mt-3 mt-sm-0">
                <label for="expenditureCategory">Expenditure Category</label>
                <select id="expenditureCategory" class="form-select" name="expenditureCategory" required>
                    <option value="">Select Category</option>
                    <?php
                    $getCat = $mysqli->query("select * from expense_category where ecatActive = 1");
                    while ($resCat = $getCat->fetch_assoc()) { ?>
                        <option value="<?php echo $resCat['ecatName'] ?>"><?php echo $resCat['ecatName'] ?></option>
                    <?php } ?>
                </select>
            </div>
		 
            <div class="col-12 col-sm-2">
                <label for="expenditureAmount">Amount</label>
                <input id="expenditureAmount" class="form-control" type="text" onkeypress="return isAmount(event)" name="expenditureAmount" min="0" step="0.01" placeholder="Enter amount" required>
            </div>
			
			
            <div class="col-12 col-sm-2 mt-3 mt-sm-0">
                <label for="expenditureDate">Date</label>
                <input id="expenditureDate" class="form-control" type="date" name="expenditureDate" placeholder="Enter date" required>
            </div>
			
			<div class="col-12 col-sm-2 mt-3 mt-sm-0">
                <label for="expenditureReceipt">Receipt Number</label>
                <input id="expenditureReceipt" class="form-control" type="text" name="expenditureReceipt" placeholder="Enter receipt number">
            </div>
			
			
			<div class="col-12 col-sm-3 mt-3 mt-sm-0">
                
			<button  style="width: 80% !important; float: left; margin-top: 30px;" id="saveExpenditure" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Save Record">SAVE RECORD >> </button>
        </div>
            
			
			
        </div>
	
        
		<!--
        <div class="button-row d-flex justify-content-center mt-4">
            <button id="saveExpenditure" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Submit">Submit</button>
        </div> -->
		
    </div>
</form>

<script>
    $("#saveExpenditure").click(function() {
        var formData = {
            expenditureName: $("#expenditureName").val(),
            expenditureDescription: $("#expenditureDescription").val(),
            expenditureCategory: $("#expenditureCategory").val(),
            expenditureAmount: $("#expenditureAmount").val(),
            expenditureDate: $("#expenditureDate").val(),
            expenditureReceipt: $("#expenditureReceipt").val()
        };

        var url = "ajaxscripts/queries/addExpenditure.php";

        var successCallback = function(response) {
            //alert(response);
            console.log(response);
            if (response === 'Success') {
                $.notify("Form saved successfully", "success");
                loadPage("ajaxscripts/forms/addExpenditure.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/expenditure.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }
        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.expenditureName) {
                error += 'Please enter expenditure name\n';
                $("#expenditureName").focus();
            }
            if (!formData.expenditureCategory) {
                error += 'Please select category\n';
                $("#expenditureCategory").focus();
            }
            if (!formData.expenditureAmount) {
                error += 'Please enter amount\n';
                $("#expenditureAmount").focus();
            }
            if (!formData.expenditureDate) {
                error += 'Please select date\n';
                $("#expenditureDate").focus();
            }
            // You can add more validation rules as needed

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });
</script>