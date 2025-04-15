<?php include('includes/sidebar.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">

    <!-- Header -->
    <?php include('includes/header.php') ?>
    <!-- End Header -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-5">
                <div id="pageForm"></div>
            </div>
            <div class="col-md-7">
                <div id="pageTable"></div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php') ?>

<script>
    loadPage("ajaxscripts/forms/addExpenditure.php", function(response) {
        $('#pageForm').html(response);
    });

    loadPage("ajaxscripts/tables/expenditure.php", function(response) {
        $('#pageTable').html(response);
    });
</script>