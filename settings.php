<?php include('./includes/sidebar.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <!-- Header -->
    <?php include('./includes/header.php'); ?>
    <!-- End Header -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-radius-xl p-4">
                    <div class="card-header bg-white border-0">
                        <ul class="nav nav-tabs" id="produceTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="produce-tab" data-bs-toggle="tab" data-bs-target="#produce" type="button" role="tab" aria-controls="produce" aria-selected="true">
                                    <i class="fas fa-money-bill-wave me-2"></i>Produce
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                                    <i class="fas fa-tags me-2"></i>Produce Category
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                                    <i class="fas fa-tags me-2"></i>Users
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="produceTabsContent">
                            <!-- Produce Tab -->
                            <div class="tab-pane fade show active" id="produce" role="tabpanel" aria-labelledby="produce-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Farm Products</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addproduceModal">
                                        <i class="fas fa-plus me-2"></i>Add Produce
                                    </button>
                                </div>
                                <div id="pageTable"></div>
                            </div>
                            <!-- Categories Tab -->
                            <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Products Categories</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus me-2"></i>Add Product Category
                                    </button>
                                </div>
                                <div id="categoryTable"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Produce Modal -->
    <div class="modal fade" id="addproduceModal" tabindex="-1" aria-labelledby="addproduceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addproduceModalLabel">Add Farm Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="pageForm"></div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addCategoryModalLabel">Add Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="categoryForm"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewProductCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addCategoryModalLabel">Add Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewProdCategoryFormDiv"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductCategoryModal" tabindex="-1" aria-labelledby="editProductCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editProductCategoryModalLabel">Add Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editProdCategoryFormDiv"></div>
            </div>
        </div>
    </div>


</main>

<?php include('./includes/footer.php'); ?>

<script>
    // Load Produce table on page load
    loadPage("ajaxscripts/tables/produce.php", function(response) {
        $('#pageTable').html(response);
    });

    // Load Produce form into modal
    $('#addproduceModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addproduce.php", function(response) {
            $('#pageForm').html(response);
        });
    });

    // Load Categories table when Categories tab is shown
    $('#categories-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/productCategory.php", function(response) {
            $('#categoryTable').html(response);
        });
    });

    // Load Category form into modal
    $('#addCategoryModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addProductCategory.php", function(response) {
            $('#categoryForm').html(response);
        });
    });

    $(document).on('click', '.viewProdCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewProdCategory.php";
        var successCallback = function(response) {
            $('#viewProdCategoryFormDiv').html(response);
            $('#viewProductCategoryModal').modal('show').find('.modal-title').text('View Product Category');
        };
        saveForm(formData, url, successCallback);
    });


    $(document).on('click', '.editProdCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editProdCategory.php";
        var successCallback = function(response) {
            $('#editProdCategoryFormDiv').html(response);
            $('#editProductCategoryModal').modal('show').find('.modal-title').text('Edit Product Category');
        };
        saveForm(formData, url, successCallback);
    });
</script>