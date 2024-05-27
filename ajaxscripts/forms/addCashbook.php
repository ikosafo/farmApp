<?php include('../../config.php');
?>

<form autocomplete="off" id="cashBookForm">
    <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
        <h5 class="font-weight-bolder mb-0">Cash Book</h5>
        <p class="mb-0 text-sm">Record new transaction</p> 
		
		<div class="row mt-3">
            <div class="col-12 col-sm-2">
               
            </div>
            
			 <div class="col-12 col-sm-4">
                <button id="cashBookIncome" style="width: 95% !important; float: left; margin-top: 30px;" id="xxsaveTransaction" class="btn bg-gradient-primary mt-3  js-btn-next" type="button" title="Income or Receivings">INCOME / RECEIVINGS >> </button>
            </div>
			
			 <div class="col-12 col-sm-4">
                <button  id="cashBookExpenditure" href="cashbook.php?link=expense" style="width: 100% !important; float: left; margin-top: 30px;" id="xxsaveTransaction" class="btn bg-gradient-primary mt-3  js-btn-next" type="button" title="Expenditures or Payments ">PAYMENTS / EXPENDITURE >> </button>
            </div>
			
			 <div class="col-12 col-sm-2">
               
            </div>
			
			</div>
		
		

		
		
		
<script>
    $("#cashBookIncome").click(function() {
        // Remove 'active' class from all nav links
        $(".settings-link").removeClass("active");

        // Add 'active' class to the clicked nav link
        $(this).addClass("active");

        // Load form content
        loadPage("ajaxscripts/forms/addCashbookIncome.php", function(response) {
            $('#pageForm').html(response);
        });

        /* / Load table content
        loadPage("ajaxscripts/tables/products.php", function(response) {
            $('#pageTable').html(response);
        }); 
		*/
    });


    $("#cashBookExpenditure").click(function() {
        // Remove 'active' class from all nav links
        $(".settings-link").removeClass("active");

        // Add 'active' class to the clicked nav link
        $(this).addClass("active");

        // Load form content
        loadPage("ajaxscripts/forms/addCashbookExpenditure.php", function(response) {
            $('#pageForm').html(response);
        });

        // Load table content
        loadPage("ajaxscripts/tables/cashbookExpenditure.php", function(response) {
            $('#pageTable').html(response);
        });
		
    });


   
</script>		
		