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
                        <ul class="nav nav-tabs premium-tabs" id="expenditureTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="expenditures-tab" data-bs-toggle="tab" data-bs-target="#expenditures" type="button" role="tab" aria-controls="expenditures" aria-selected="true">
                                    <i class="fas fa-money-bill-wave me-2"></i>Expenditures
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                                    <i class="fas fa-tags me-2"></i>Categories
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="expenditureTabsContent">
                            <!-- Expenditures Tab -->
                            <div class="tab-pane fade show active" id="expenditures" role="tabpanel" aria-labelledby="expenditures-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Farm Expenditures</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenditureModal">
                                        <i class="fas fa-plus me-2"></i>Add Expenditure
                                    </button>
                                </div>
                                <div id="pageTable"></div>
                            </div>
                            <!-- Categories Tab -->
                            <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Expenditure Categories</h5>
                                    <button class="btn bg-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus me-2"></i>Add Category
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

    <!-- Add Expenditure Modal -->
    <div class="modal fade" id="addExpenditureModal" tabindex="-1" aria-labelledby="addExpenditureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="addExpenditureModalLabel">Add Farm Expenditure</h5>
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
                    <h5 class="modal-title font-weight-bolder" id="addCategoryModalLabel">Add Expenditure Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="categoryForm"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="expenditureModal" tabindex="-1" aria-labelledby="expenditureModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="expenditureModal">View Expenditures</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="pageForm3"></div>
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
    // Load Expenditures table on page load
    loadPage("ajaxscripts/tables/expenditure.php", function(response) {
        $('#pageTable').html(response);
    });

    // Load Expenditure form into modal
    $('#addExpenditureModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addExpenditure.php", function(response) {
            $('#pageForm').html(response);
        });
    });

    // Load Categories table when Categories tab is shown
    $('#categories-tab').on('shown.bs.tab', function () {
        loadPage("ajaxscripts/tables/expCategory.php", function(response) {
            $('#categoryTable').html(response);
        });
    });

    // Load Category form into modal
    $('#addCategoryModal').on('show.bs.modal', function () {
        loadPage("ajaxscripts/forms/addExpCategory.php", function(response) {
            $('#categoryForm').html(response);
        });
    });



    $(document).on('click', '.viewExpenditure_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewExpenditure.php";
        var successCallback = function(response) {
            $('#pageForm3').html(response);
            $('#expenditureModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });

    $(document).on('click', '.editExpenditure_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editExpenditure.php";
        var successCallback = function(response) {
            $('#pageForm3').html(response);
            $('#expenditureModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });
</script>