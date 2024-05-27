<?php include('./includes/sidebar.php'); ?>

<main class="main-content h-100 ps ps--active-y">

    <!-- Header -->
    <?php include('./includes/header.php') ?>
    <!-- End Header -->
    <style>
        #configSection .settings-link.active {
            background-color: #e9ecef;
        }
    </style>



    <div class="container-fluid my-3 py-3" id="configSection">
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card position-sticky top-1" style="margin-bottom:20px;">
     
    <div >
	
	<div class="navBut" style="float: left; border-right:0px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#" id="productManagement">
    <span class="text-sm">PRODUCE</span>
     </a>
	</div>

	<div class="navBut" style="float: left; border-right:0px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#" id="productCategory">
	<span class="text-sm">PRODUCE CATEGORY</span>
	</a>
	</div>						
		
	<div class="navBut" style="float: left; border-right:0px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#" id="expenseCategory">
	<span class="text-sm">EXPENDITURE CATEGORY</span>
	</a>
	</div>
	
	
	<div class="navBut" style="float: left; border-right:0px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#" id="accountManagement">
	<span class="text-sm">ACCOUNTS</span>
	</a>
	</div>
	
	<div class="navBut" style="float: left; border-right:1px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#">
	<span class="text-sm">MISC SETTINGS</span>
	</a>
	</div>
	
	
	<div class="navBut" style="float: left; border-right:1px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#" id="userManagement">
	<span class="text-sm">USERS</span>
	</a>
	</div>
	
	<div class="navBut" style="float: left; border-right:1px dashed #ccc; ">
	<a class="nav-link settings-link text-body" data-scroll="" href="#sessions">
	<span class="text-sm">LOGS</span>
	</a>
	</div>

		
	</div>				
					
					
					
					
                       
                </div>
            </div>
            <div class="col-lg-12 mt-lg-0 mt-4">
                <div id="pageForm"></div>
                <div id="pageTable"></div>
            </div>
        </div>

    </div>
    <div class=" ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; height: 639px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 113px;"></div>
    </div>
</main>

<?php include('includes/footer.php') ?>

<script>
    $("#productManagement").click(function() {
        // Remove 'active' class from all nav links
        $(".settings-link").removeClass("active");

        // Add 'active' class to the clicked nav link
        $(this).addClass("active");

        // Load form content
        loadPage("ajaxscripts/forms/addProduct.php", function(response) {
            $('#pageForm').html(response);
        });

        // Load table content
        loadPage("ajaxscripts/tables/products.php", function(response) {
            $('#pageTable').html(response);
        });
    });


    $("#userManagement").click(function() {
        // Remove 'active' class from all nav links
        $(".settings-link").removeClass("active");

        // Add 'active' class to the clicked nav link
        $(this).addClass("active");

        // Load form content
        loadPage("ajaxscripts/forms/addUser.php", function(response) {
            $('#pageForm').html(response);
        });

        // Load table content
        loadPage("ajaxscripts/tables/users.php", function(response) {
            $('#pageTable').html(response);
        });
    });


    $("#productCategory").click(function() {
        // Remove 'active' class from all nav links
        $(".settings-link").removeClass("active");

        // Add 'active' class to the clicked nav link
        $(this).addClass("active");

        // Load form content
        loadPage("ajaxscripts/forms/productCategory.php", function(response) {
            $('#pageForm').html(response);
        });

        // Load table content
        loadPage("ajaxscripts/tables/productCategories.php", function(response) {
            $('#pageTable').html(response);
        });
    });


    $("#expenseCategory").click(function() {
        // Remove 'active' class from all nav links
        $(".settings-link").removeClass("active");

        // Add 'active' class to the clicked nav link
        $(this).addClass("active");

        // Load form content
        loadPage("ajaxscripts/forms/expenseCategory.php", function(response) {
            $('#pageForm').html(response);
        });

        // Load table content
        loadPage("ajaxscripts/tables/expenseCategories.php", function(response) {
            $('#pageTable').html(response);
        });
    });
</script>