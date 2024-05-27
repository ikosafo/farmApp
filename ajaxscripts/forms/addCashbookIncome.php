<?php include('../../config.php'); ?>


   
		
		
<form autocomplete="off" id="cashBookForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom:30px">
     <h4>Add Cashbook Income </h4>		
		 <div class="row mt-3">
		 <input type="hidden" id="cbUId" name="cbUId">
		 <input type="hidden" id="cbUserId" name="cbUserId">
		 
            <div class="col-12 col-sm-2">
                <label for="cashBookEntryType">Cash Receivings</label>
                <select id="cashBookEntryType" class="form-select" name="cashBookEntryType" required>
                    <option value="Income" selected>Income</option>
                    </select> 
            </div>
            <div class="col-12 col-sm-2 mt-3 mt-sm-0">
               <label for="cashBookDate">Date</label>
                <input id="cashBookDate" class="form-control" type="date" name="cashBookDate" placeholder="Enter transaction date" required>

            </div>
			
			
			<div class="col-12 col-sm-2">
                 <label for="cbNominalAccountType">Nominal Acct</label>
                <select id="cbNominalAccountType" class="form-select" name="cbNominalAccountType" required>
                    <option value="">Select Category</option>
                    <?php
                    $getCat = $mysqli->query("select * from expense_category where ecatCode='INC' AND ecatActive = 1");
                    while ($resCat = $getCat->fetch_assoc()) { ?>
                        <option value="<?php echo $resCat['ecatName'] ?>"><?php echo $resCat['ecatName'] ?></option>
                    <?php } ?>
                </select>
				 
				 
				
            </div>
			
			<div class="col-12 col-sm-2">
                <label for="cbAmount">Amount Received</label>
                <input id="cbAmount" class="form-control" type="text" onkeypress="return isAmount(event)" name="cbAmount" min="0" step="0.01" placeholder="Enter amount" required>
            </div>
			
			<div class="col-12 col-sm-2">
                
			   <label for="cbReferenceNumber">Invoice Number</label>
         <input id="cbReferenceNumber" class="form-control" type="text" name="cbReferenceNumber" placeholder="Enter reference number"> 
            </div>
			
			<div class="col-12 col-sm-2">
                  <label for="cbPaymentMode">Mode </label>			
				<select id="cbPaymentMode" class="form-select" name="cbPaymentMode" required>
                    <option value="Cash">Cash</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Momo">Momo</option>
                </select>
				
            </div>
        </div>
		
		
		
		
		
		 <div class="row mt-3">
            <div class="col-12 col-sm-4">
                <label for="cbSalesProduceType">Sales Type</label>
                 <select id="cbSalesProduceType" class="form-select" name="cbSalesProduceType" required>
                    <option value=""> Select an item </option>
                    <option>General</option>
                    <option disabled>--------</option>
                    <?php
                    $getProd = $mysqli->query("SELECT prodName FROM producelist WHERE prodActive = 1 ORDER BY prodName ASC");
                    while ($resProd = $getProd->fetch_assoc()) { ?>
                        <option value="<?php echo $resProd['prodName'] ." Sales" ?>"><?php echo $resProd['prodName'] ." Sales" ?></option>
                    <?php } ?> 
					</select>
            </div>
            <div class="col-12 col-sm-4 mt-3 mt-sm-0">
               <label for="cbRecipientPayeeName">Buyer or Payee Name</label>
         <input id="cbRecipientPayeeName" class="form-control" type="text" name="cbRecipientPayeeName" placeholder="Enter buyer name">      

            </div>
			
			
			
			
			<div class="col-12 col-sm-4">
                <label for="cbDescription">Description</label>
                <input id="cbDescription" class="form-control" type="text" name="cbDescription" placeholder="Enter transaction description"> 
            </div>
			
			<input type="hidden" id="cbField1" name="cbField1">
			<input type="hidden" id="cbField2" name="cbField2">
			<input type="hidden" id="cbField3" name="cbField3">
			<input type="hidden" id="cbField4" name="cbField4">
			<input type="hidden" id="cbActive" name="cbActive">
			<input type="hidden" id="cbLog" name="cbLog">
        </div>
		
		
		
		
		
		
        <div class="row mt-3">
         <div class="col-12 col-sm-4">
          
         </div>
		 
         <div class="col-12 col-sm-4 mt-3 mt-sm-0">
          
         </div>
		 
		<div class="col-12 col-sm-4 mt-3 mt-sm-0">
        <button  style="width: 80% !important; float: left; margin-top: 30px;" id="saveCashBookEntry" class="btn bg-gradient-dark mb-0 js-btn-next" type="button" title="Save Record">SAVE RECORD >> </button>    
         </div>
		 
        </div>

		
		
        

       
    </div>
</form>

<script>
    $("#saveCashBookEntry").click(function() {
        var formData = {
            cbUId: $("#cbUId").val(),
            cbUserId: $("#cbUserId").val(),
            cashBookEntryType: $("#cashBookEntryType").val(),
			cashBookDate: $("#cashBookDate").val(),
            cbNominalAccountType: $("#cbNominalAccountType").val(),
            cbAmount: $("#cbAmount").val(),
			cbReferenceNumber: $("#cbReferenceNumber").val(),
			cbPaymentMode: $("#cbPaymentMode").val(),
			cbSalesProduceType: $("#cbSalesProduceType").val(),
            cbRecipientPayeeName: $("#cbRecipientPayeeName").val(),
            cbDescription: $("#cbDescription").val(),
            cbField1: $("#cbField1").val(),
            cbField2: $("#cbField2").val(),
            cbField2: $("#cbField2").val(),
            cbField3: $("#cbField3").val(),
            cbField4: $("#cbField4").val(),
            cbActive: $("#cbActive").val(),
            cbLog: $("#cbLog").val()
        };

        var url = "ajaxscripts/queries/addCashbookIncome.php";

        var successCallback = function(response) {
            console.log(response);
            if (response === 'Success') {
                $.notify("Transaction saved successfully", "success");
                loadPage("ajaxscripts/forms/cashbookIncome.php", function(response) {
                    $('#pageForm').html(response);
                });

                loadPage("ajaxscripts/tables/cashbookIncome.php", function(response) {
                    $('#pageTable').html(response);
                });
            } else {
                alert(response);
            }
        };

        var validateForm = function(formData) {
            var error = '';

            if (!formData.cashBookDate) {
                error += 'Please select a transaction date\n';
                $("#cashBookDate").focus();
            }
            if (!formData.cbNominalAccountType) {
                error += 'Please enter a nominal account type\n';
                $("#cbNominalAccountType").focus();
            }
            if (!formData.cbSalesProduceType) {
                error += 'Please select a Sales Type \n';
                $("#cbSalesProduceType").focus();
            }
            if (!formData.cbAmount) {
                error += 'Please enter a transaction amount\n';
                $("#cbAmount").focus();
            }
			if (!formData.cbRecipientPayeeName) {
                error += 'Please enter Payee Name \n';
                $("#cbRecipientPayeeName").focus();
            }
            // You can add more validation rules as needed

            return error;
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback, validateForm);
    });
</script>

<script>
     
    loadPage("ajaxscripts/tables/cashbookIncome.php", function(response) {
        $('#pageTable').html(response);
    }); 
</script>
