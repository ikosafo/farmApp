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
                        <ul class="nav nav-tabs premium-tabs" id="receiptTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="receipts-tab" data-bs-toggle="tab" data-bs-target="#receipts" type="button" role="tab" aria-controls="receipts" aria-selected="true">Receipts
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="addreceipt-tab" data-bs-toggle="tab" data-bs-target="#addreceipt" type="button" role="tab" aria-controls="addreceipt" aria-selected="true">
                                    <i class="fas fa-plus me-2"></i>Add Receipt
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
                        <div class="tab-content" id="receiptTabsContent">
                            <!-- Add Receipt Tab -->
                            <div class="tab-pane fade" id="addreceipt" role="tabpanel" aria-labelledby="addreceipt-tab">
                                <div class="mb-4">
                                    <div id="pageForm"></div>
                                </div>
                            </div>
                            <!-- Receipts Tab -->
                            <div class="tab-pane fade show active" id="receipts" role="tabpanel" aria-labelledby="receipts-tab">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="font-weight-bolder mb-0">Farm Receipts</h5>
                                </div>
                                <div id="pageTable"></div>
                            </div>
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

    <!-- View Receipt Modal -->
    <div class="modal fade" id="viewReceiptModal" tabindex="-1" aria-labelledby="viewReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-body" id="viewReceiptContent"></div>
            </div>
        </div>
    </div>

    <!-- Edit Receipt Modal -->
    <div class="modal fade" id="editReceiptModal" tabindex="-1" aria-labelledby="editReceiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="editReceiptModalLabel">Edit Revenue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editReceiptContent"></div>
            </div>
        </div>
    </div>

</main>

<?php include('./includes/footer.php'); ?>

<style>
    .card .card-body {
        font-family: 'Poppins', sans-serif !important;
    }

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
    $(document).ready(function() {

        // Persist active tab on page reload
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('.nav-tabs .nav-link').removeClass('active').attr('aria-selected', 'false');
            $('.tab-pane').removeClass('show active');
            $('#' + activeTab).addClass('active').attr('aria-selected', 'true');
            $('#' + activeTab.replace('-tab', '')).addClass('show active');
        }

        // Save active tab to localStorage when a tab is shown
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('id'));
        });

        // Load content based on active tab
        if (activeTab === 'receipts-tab') {
           loadPage("ajaxscripts/tables/receipts.php", function(response) {
                $('#pageTable').html(response);
            });
        }
        if (!activeTab || activeTab === 'addreceipt-tab') {
            loadPage("ajaxscripts/forms/addReceipt.php", function(response) {
                $('#pageForm').html(response);
            });
        }
        if (activeTab === 'statistics-tab') {
            loadPage("ajaxscripts/tables/incStatistics.php", function(response) {
                $('#statisticsTable').html(response);
            });
        }

        loadPage("ajaxscripts/tables/receipts.php", function(response) {
            $('#pageTable').html(response);
        });

        loadPage("ajaxscripts/tables/incStatistics.php", function(response) {
                $('#statisticsTable').html(response);
            });

       

    });
</script>