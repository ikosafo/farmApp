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
                        <ul class="nav nav-tabs premium-tabs" id="incomeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="incomes-tab" data-bs-toggle="tab" data-bs-target="#incomes" type="button" role="tab" aria-controls="incomes" aria-selected="true">
                                    <i class="fas fa-money-bill-wave me-2"></i>Income
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="statistics-tab" data-bs-toggle="tab" data-bs-target="#statistics" type="button" role="tab" aria-controls="statistics" aria-selected="false">
                                    <i class="fas fa-chart-bar me-2"></i>Statistics
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="incomeTabsContent">
                            <!-- Incomes Tab -->
                            <div class="tab-pane fade show active" id="incomes" role="tabpanel" aria-labelledby="incomes-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Farm Incomes</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                                        <i class="fas fa-plus me-2"></i>Add Income
                                    </button>
                                </div>
                                <div id="pageTable"></div>
                            </div>
                            <!-- Categories Tab -->
                            <!-- <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Income Categories</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus me-2"></i>Add Category
                                    </button>
                                </div>
                                <div id="categoryTable"></div>
                            </div> -->
                            <!-- Statistics Tab -->
                            <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="statistics-tab">
                                <div id="statisticsTable"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Income Modal -->
    <div class="modal fade" id="addIncomeModal" tabindex="-1" aria-labelledby="addIncomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addIncomeModalLabel">Add Farm Income</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="addIncomeForm"></div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <!-- <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addCategoryModalLabel">Add Income Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="addCategoryForm"></div>
            </div>
        </div>
    </div> -->

    <!-- View Income Modal -->
    <div class="modal fade" id="viewIncomeModal" tabindex="-1" aria-labelledby="viewIncomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="viewIncomeModalLabel">View Income Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewIncomeContent"></div>
            </div>
        </div>
    </div>

    <!-- Edit Income Modal -->
    <div class="modal fade" id="editIncomeModal" tabindex="-1" aria-labelledby="editIncomeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editIncomeModalLabel">Edit Income</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editIncomeContent"></div>
            </div>
        </div>
    </div>

    <!-- View/Edit Income Category Modal -->
    <!-- <div class="modal fade" id="incCatModal" tabindex="-1" aria-labelledby="incCatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="incCatModalLabel">Income Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="incomeCatContent"></div>
            </div>
        </div>
    </div> -->
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
    // Load Incomes table on page load
    loadPage("ajaxscripts/tables/income.php", function(response) {
        $('#pageTable').html(response);
    });

    // Load Income form into modal
    $('#addIncomeModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addIncome.php", function(response) {
            $('#addIncomeForm').html(response);
        });
    });

    // Load Categories table when Categories tab is shown
    /* $('#categories-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/incCategory.php", function(response) {
            $('#categoryTable').html(response);
        });
    }); */

    // Load Category form into modal
    /* $('#addCategoryModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addIncCategory.php", function(response) {
            $('#addCategoryForm').html(response);
        });
    }); */

    // Handle View Income
    $(document).on('click', '.viewIncome_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewIncome.php";
        var successCallback = function(response) {
            $('#viewIncomeContent').html(response);
            $('#viewIncomeModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });

    // Handle Edit Income
    $(document).on('click', '.editIncome_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editIncome.php";
        var successCallback = function(response) {
            $('#editIncomeContent').html(response);
            $('#editIncomeModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });

    // Handle View Income Category
    /* $(document).on('click', '.viewIncCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewIncCategory.php";
        var successCallback = function(response) {
            $('#incomeCatContent').html(response);
            $('#incCatModal').modal('show').find('.modal-title').text('View Income Category');
        };
        saveForm(formData, url, successCallback);
    }); */

    // Handle Edit Income Category
    /* $(document).on('click', '.editIncCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editIncCategory.php";
        var successCallback = function(response) {
            $('#incomeCatContent').html(response);
            $('#incCatModal').modal('show').find('.modal-title').text('Edit Income Category');
        };
        saveForm(formData, url, successCallback);
    }); */


    $('#statistics-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/incStatistics.php", function(response) {
            $('#statisticsTable').html(response);
        });
    });
</script>