<?php include('./includes/sidebar.php'); ?>

<main class="main-content h-100 ps ps--active-y">
    <!-- Header -->
    <?php include('./includes/header.php'); ?>
    <!-- End Header -->

    <div class="container-fluid my-3 py-3" id="configSection">
        <div class="row mb-5">
            <div class="col-lg-12">
                <!-- Sticky Navigation Card -->
                <div class="card position-sticky top-1 mb-4 shadow-sm border-0">
                    <div class="card-header text-white border-0">
                        <h5 class="mb-0">System Settings</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs nav-justified flex-column flex-md-row">
                            <li class="nav-item">
                                <a class="nav-link settings-link text-sm font-weight-bold" id="productManagement" href="#productManagementContent" data-bs-toggle="tab">
                                    <i class="fas fa-carrot me-1"></i> Produce
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link settings-link text-sm font-weight-bold" id="productCategory" href="#productCategoryContent" data-bs-toggle="tab">
                                    <i class="fas fa-tags me-1"></i> Produce Category
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link settings-link text-sm font-weight-bold" id="expenseCategory" href="#expenseCategoryContent" data-bs-toggle="tab">
                                    <i class="fas fa-wallet me-1"></i> Expenditure Category
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link settings-link text-sm font-weight-bold" id="accountManagement" href="#accountManagementContent" data-bs-toggle="tab">
                                    <i class="fas fa-piggy-bank me-1"></i> Accounts
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link settings-link text-sm font-weight-bold" id="userManagement" href="#userManagementContent" data-bs-toggle="tab">
                                    <i class="fas fa-users me-1"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link settings-link text-sm font-weight-bold" id="logs" href="#logsContent" data-bs-toggle="tab">
                                    <i class="fas fa-history me-1"></i> Logs
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Produce -->
                    <div class="tab-pane fade" id="productManagementContent">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div id="productManagementForm" class="mb-4"></div>
                                <div id="productManagementTable"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Produce Category -->
                    <div class="tab-pane fade" id="productCategoryContent">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div id="productCategoryForm" class="mb-4"></div>
                                <div id="productCategoryTable"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Expenditure Category -->
                    <div class="tab-pane fade" id="expenseCategoryContent">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div id="expenseCategoryForm" class="mb-4"></div>
                                <div id="expenseCategoryTable"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Accounts -->
                    <!-- <div class="tab-pane fade" id="accountManagementContent">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div id="accountManagementForm" class="mb-4"></div>
                                <div id="accountManagementTable"></div>
                            </div>
                        </div>
                    </div> -->
                   
                    <!-- Users -->
                    <div class="tab-pane fade" id="userManagementContent">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div id="userManagementForm" class="mb-4"></div>
                                <div id="userManagementTable"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Logs -->
                    <div class="tab-pane fade" id="logsContent">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div id="logsTable"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast for Errors -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Perfect Scrollbar Rails -->
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>
    <div class="ps__rail-y" style="top: 0px; height: 639px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 113px;"></div>
    </div>
</main>

<?php include('./includes/footer.php'); ?>

<!-- STYLES -->
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .card {
        border-radius: 12px;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1) !important;
    }

    .nav-tabs .nav-link {
        padding: 12px 16px;
        color: #2c3e50;
        border: none;
        border-radius: 0;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background-color: #f1f3f5;
        color: #3498db;
    }

    .nav-tabs .nav-link.active {
        background-color: #3498db;
        color: #fff;
        font-weight: 600;
    }

    .nav-tabs {
        border-bottom: 2px solid #ecf0f1;
        background-color: #fff;
    }

    .table-container {
        max-height: 400px;
        overflow-y: auto;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th, .table td {
        border: 1px solid #dee2e6;
        padding: 12px;
    }

    .table thead th {
        background-color: #f1f3f5;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:hover {
        background-color: #e9ecef;
    }


    .loading-spinner {
        display: none;
        text-align: center;
        padding: 20px;
    }

    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .nav-tabs {
            flex-direction: column;
        }

        .nav-tabs .nav-link {
            text-align: center;
            padding: 10px;
        }
    }
</style>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
    // Assume loadPage is defined elsewhere (e.g., in a global script)
    function loadPage(url, callback) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                callback(response);
            },
            error: function(xhr, status, error) {
                showErrorToast('Failed to load content: ' + (xhr.statusText || error));
            }
        });
    }

    function showErrorToast(message) {
        const toastEl = document.getElementById('errorToast');
        toastEl.querySelector('.toast-body').textContent = message;
        new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    }

    // Navigation Click Handlers
    $("#productManagement").click(function(e) {
        e.preventDefault();
        loadSection('productManagement', 'ajaxscripts/forms/addProduct.php', 'ajaxscripts/tables/products.php');
    });

    $("#productCategory").click(function(e) {
        e.preventDefault();
        loadSection('productCategory', 'ajaxscripts/forms/productCategory.php', 'ajaxscripts/tables/productCategories.php');
    });

    $("#expenseCategory").click(function(e) {
        e.preventDefault();
        loadSection('expenseCategory', 'ajaxscripts/forms/expenseCategory.php', 'ajaxscripts/tables/expenseCategories.php');
    });

    /* $("#accountManagement").click(function(e) {
        e.preventDefault();
        loadSection('accountManagement', 'ajaxscripts/forms/accountManagement.php', 'ajaxscripts/tables/accounts.php');
    }); */


    $("#userManagement").click(function(e) {
        e.preventDefault();
        loadSection('userManagement', 'ajaxscripts/forms/addUser.php', 'ajaxscripts/tables/users.php');
    });

    $("#logs").click(function(e) {
        e.preventDefault();
        loadSection('logs', null, 'ajaxscripts/tables/logs.php');
    });

    // Load Section Content
    function loadSection(section, formUrl, tableUrl) {
        $('.settings-link').removeClass('active');
        $(`#${section}`).addClass('active');

        const formContainer = $(`#${section}Form`);
        const tableContainer = $(`#${section}Table`);

        // Show loading spinner
        formContainer.html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');
        tableContainer.html('<div class="loading-spinner"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>');

        // Load form if provided
        if (formUrl) {
            loadPage(formUrl, function(response) {
                formContainer.html(response);
            });
        } else {
            formContainer.html('');
        }

        // Load table
        loadPage(tableUrl, function(response) {
            tableContainer.html(response);
        });
    }

    // Initialize Tooltips
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    // Default Load (Optional: Load Produce section on page load)
    $(document).ready(function() {
        $("#productManagement").click();
    });
</script>