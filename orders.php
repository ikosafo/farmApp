<?php include('./includes/sidebar.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <!-- Header -->
    <?php include('./includes/header.php'); ?>
    <!-- End Header -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-radius-xl p-4">
                    <div class="card-body">
                        <div class="tab-content" id="orderTabsContent">
                            <!-- Orders Tab -->
                            <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Farm Orders</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                                        <i class="fas fa-plus me-2"></i>Add New Order
                                    </button>
                                </div>
                                <div id="pageTable"></div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Order Modal -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addOrderModalLabel">Add Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="pageForm"></div>
            </div>
        </div>
    </div>

   
</main>

<?php include('./includes/footer.php'); ?>

<script>
    // Load Orders table on page load
    loadPage("ajaxscripts/tables/orders.php", function(response) {
        $('#pageTable').html(response);
    });

    // Load Order form into modal
    $('#addOrderModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addOrder.php", function(response) {
            $('#pageForm').html(response);
        });
    });


</script>