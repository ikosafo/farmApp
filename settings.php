<?php include('./includes/sidebar.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <!-- Header -->
    <?php include('./includes/header.php'); ?>
    <!-- End Header -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-radius-xl p-4">
                    <div class="card-header bg-transparent border-0">
                        <ul class="nav nav-tabs premium-tabs" id="produceTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="produce-tab" data-bs-toggle="tab" data-bs-target="#produce" type="button" role="tab" aria-controls="produce" aria-selected="true">
                                    <i class="fas fa-money-bill-wave me-2"></i>Produce
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="prodcategories-tab" data-bs-toggle="tab" data-bs-target="#prodcategories" type="button" role="tab" aria-controls="prodcategories" aria-selected="false">
                                    <i class="fas fa-layer-group me-2"></i>Product Categories
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                                    <i class="fas fa-tags me-2"></i>General Categories
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">
                                    <i class="fas fa-users me-2"></i>Users
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">
                                    <i class="fas fa-key me-2"></i>Password
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab" aria-controls="logs" aria-selected="false">
                                    <i class="fas fa-history me-2"></i>Logs
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
                                    <h5 class="font-weight-bolder mb-0">Categories</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus me-2"></i>Add Category
                                    </button>
                                </div>
                                <div id="categoryTable"></div>
                            </div>
                            <!-- Product Categories Tab -->
                            <div class="tab-pane fade" id="prodcategories" role="tabpanel" aria-labelledby="prodcategories-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Product Categories</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProdCategoryModal">
                                        <i class="fas fa-plus me-2"></i>Add Product Category
                                    </button>
                                </div>
                                <div id="prodcategoryTable"></div>
                            </div>
                            <!-- Users Tab -->
                            <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Users</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        <i class="fas fa-plus me-2"></i>Add User
                                    </button>
                                </div>
                                <div id="userTable"></div>
                            </div>
                            <!-- Change Password Tab -->
                            <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Change Password</h5>
                                </div>
                                <div id="changePasswordForm"></div>
                            </div>
                            <!-- Logs Tab -->
                            <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">System Logs</h5>
                                </div>
                                <div id="logsTable"></div>
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
                    <h5 class="modal-title font-weight-bolder" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="categoryForm"></div>
            </div>
        </div>
    </div>


     <!-- Add Product Category Modal -->
     <div class="modal fade" id="addProdCategoryModal" tabindex="-1" aria-labelledby="addProdCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addProdCategoryModalLabel">Add Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="prodcategoryForm"></div>
            </div>
        </div>
    </div>

    <!-- View Category Modal -->
    <div class="modal fade" id="viewCategoryModal" tabindex="-1" aria-labelledby="viewCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="viewCategoryModalLabel">View Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewCategoryFormDiv"></div>
            </div>
        </div>
    </div>

     <!-- View Product Category Modal -->
     <div class="modal fade" id="viewProdCategoryModal" tabindex="-1" aria-labelledby="viewProdCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="viewProdCategoryModalLabel">View Product Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewProdCategoryFormDiv"></div>
            </div>
        </div>
    </div>

    <!-- View Produce Modal -->
    <div class="modal fade" id="viewProduceModal" tabindex="-1" aria-labelledby="viewProduceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="viewProduceModalLabel">View Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewProduceFormDiv"></div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editCategoryFormDiv"></div>
            </div>
        </div>
    </div>

    <!-- Edit Product Category Modal -->
    <div class="modal fade" id="editProdCategoryModal" tabindex="-1" aria-labelledby="editProdCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editProdCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editProdCategoryFormDiv"></div>
            </div>
        </div>
    </div>

    <!-- Edit Produce Modal -->
    <div class="modal fade" id="editProduceModal" tabindex="-1" aria-labelledby="editProduceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editProduceModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editProduceFormDiv"></div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="userForm"></div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="viewUserModalLabel">View User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewUserFormDiv"></div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editUserFormDiv"></div>
            </div>
        </div>
    </div>

</main>

<?php include('./includes/footer.php'); ?>

<style>
    .premium-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        background: #ffffff;
        padding: 10px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .premium-tabs .nav-item {
        flex: 0 1 auto;
    }

    .premium-tabs .nav-link {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        color: #343a40;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-transform: capitalize;
        letter-spacing: 0.3px;
    }

    .premium-tabs .nav-link:hover {
        background: #e9ecef;
        color: #1a2a44;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
    }

    .premium-tabs .nav-link.active {
        background: #1a2a44;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .premium-tabs .nav-link i {
        font-size: 1rem;
        margin-right: 6px;
    }

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .premium-tabs {
            gap: 8px;
            padding: 8px;
        }

        .premium-tabs .nav-link {
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        .premium-tabs .nav-link i {
            font-size: 0.9rem;
            margin-right: 4px;
        }
    }
</style>

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
        loadPage("ajaxscripts/tables/category.php", function(response) {
            $('#categoryTable').html(response);
        });
    });

    // Load Product Categories table when prod Categories tab is shown
    $('#prodcategories-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/productCategory.php", function(response) {
            $('#prodcategoryTable').html(response);
        });
    });

    // Load Category form into modal
    $('#addCategoryModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addCategory.php", function(response) {
            $('#categoryForm').html(response);
        });
    });

    // Load product Category form into modal
    $('#addProdCategoryModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addProductCategory.php", function(response) {
            $('#prodcategoryForm').html(response);
        });
    });

    // Load Users table when Users tab is shown
    $('#users-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/users.php", function(response) {
            $('#userTable').html(response);
        });
    });

    // Load User form into modal
    $('#addUserModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addUser.php", function(response) {
            $('#userForm').html(response);
        });
    });

    // Load Change Password form when Change Password tab is shown
    $('#change-password-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/forms/changePassword.php", function(response) {
            $('#changePasswordForm').html(response);
        });
    });

    // Load Logs table when Logs tab is shown
    $('#logs-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/logs.php", function(response) {
            $('#logsTable').html(response);
        });
    });

    // View Category
    $(document).on('click', '.viewCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewCategory.php";
        var successCallback = function(response) {
            $('#viewCategoryFormDiv').html(response);
            $('#viewCategoryModal').modal('show').find('.modal-title').text('View Category');
        };
        saveForm(formData, url, successCallback);
    });

    // View Product Category
    $(document).on('click', '.viewProdCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewProdCategory.php";
        var successCallback = function(response) {
            $('#viewProdCategoryFormDiv').html(response);
            $('#viewProdCategoryModal').modal('show').find('.modal-title').text('View Category');
        };
        saveForm(formData, url, successCallback);
    });

    // View Produce
    $(document).on('click', '.viewProduction_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewProduce.php";
        var successCallback = function(response) {
            $('#viewProduceFormDiv').html(response);
            $('#viewProduceModal').modal('show').find('.modal-title').text('View Product');
        };
        saveForm(formData, url, successCallback);
    });

    // Edit Category
    $(document).on('click', '.editCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editCategory.php";
        var successCallback = function(response) {
            $('#editCategoryFormDiv').html(response);
            $('#editCategoryModal').modal('show').find('.modal-title').text('Edit Category');
        };
        saveForm(formData, url, successCallback);
    });


    // Edit Product Category
    $(document).on('click', '.editProdCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editProdCategory.php";
        var successCallback = function(response) {
            $('#editProdCategoryFormDiv').html(response);
            $('#editProdCategoryModal').modal('show').find('.modal-title').text('Edit Category');
        };
        saveForm(formData, url, successCallback);
    });


    // Edit Produce
    $(document).on('click', '.editProduction_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editProduce.php";
        var successCallback = function(response) {
            $('#editProduceFormDiv').html(response);
            $('#editProduceModal').modal('show').find('.modal-title').text('Edit Product');
        };
        saveForm(formData, url, successCallback);
    });

    // View User
    $(document).on('click', '.viewUser_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewUser.php";
        var successCallback = function(response) {
            $('#viewUserFormDiv').html(response);
            $('#viewUserModal').modal('show').find('.modal-title').text('View User');
        };
        saveForm(formData, url, successCallback);
    });

    // Edit User
    $(document).on('click', '.editUser_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editUser.php";
        var successCallback = function(response) {
            $('#editUserFormDiv').html(response);
            $('#editUserModal').modal('show').find('.modal-title').text('Edit User');
        };
        saveForm(formData, url, successCallback);
    });
</script>