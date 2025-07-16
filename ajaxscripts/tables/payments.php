<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Date</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Payer</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Produce</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nominal Account</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<style>
    .table-responsive {
        border-radius: 0.75rem;
        overflow: hidden;
        width: 100%;
        font-family: 'Poppins', sans-serif;
    }

    .table {
        margin-bottom: 0;
        background: #FFFFFF;
        width: 100%;
        table-layout: auto;
        font-family: 'Poppins', sans-serif;
    }

    .table thead th {
        background: #2d6a4f; /* Dark green from FarmApp */
        color: #FFFFFF !important;
        font-weight: 500;
        border: none;
        padding: 0.6rem;
        font-size: 0.85rem;
        font-family: 'Poppins', sans-serif;
    }

    /* Remove DataTables sort icons */
    .table thead th.sorting,
    .table thead th.sorting_asc,
    .table thead th.sorting_desc {
        background-image: none !important;
        padding-right: 0.6rem !important; /* Remove extra padding for sort icons */
    }

    .table tbody tr {
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: rgba(45, 106, 79, 0.05); /* Light green hover */
    }

    .table td,
    .table th {
        padding: 0.6rem;
        vertical-align: middle;
        color: #1f2937; /* Dark text for consistency */
        border-color: rgba(0, 0, 0, 0.05);
        font-size: 0.85rem;
        font-family: 'Poppins', sans-serif;
        text-align: left; /* Ensure all columns are left-aligned */
    }

    .table td.text-sm {
        font-size: 0.85rem;
        font-family: 'Poppins', sans-serif;
    }

    /* Explicitly align Amount column to the left */
    .table td:nth-child(4) {
        text-align: left;
    }

    /* DataTable processing spinner */
    .dataTables_wrapper .dataTables_processing {
        font-family: 'Poppins', sans-serif;
        color: #2d6a4f; /* Green for spinner text */
    }

    /* DataTable empty table message */
    .dataTables_wrapper .dataTables_empty {
        font-family: 'Poppins', sans-serif;
        color: #4a7043; /* Muted green */
    }

    /* jQuery Confirm dialog */
    .jconfirm .jconfirm-box {
        font-family: 'Poppins', sans-serif;
    }

    .jconfirm .jconfirm-title {
        color: #2d6a4f !important; /* Green title */
        font-family: 'Poppins', sans-serif;
    }

    .jconfirm .jconfirm-content {
        color: #1f2937; /* Dark text */
        font-family: 'Poppins', sans-serif;
    }

    .jconfirm .btn-outline-secondary {
        border-color: #d4e4c3 !important; /* Green border */
        color: #2d6a4f !important; /* Green text */
        font-family: 'Poppins', sans-serif;
    }

    .jconfirm .btn-outline-secondary:hover {
        background: rgba(45, 106, 79, 0.1); /* Light green hover */
    }

    .jconfirm .btn-danger {
        background: #2d6a4f !important; /* Green button */
        border-color: #2d6a4f !important;
        color: #FFFFFF !important;
        font-family: 'Poppins', sans-serif;
    }

    .jconfirm .btn-danger:hover {
        background: #40916c !important; /* Lighter green on hover */
    }

    .dt-paging-button.current, .dt-paging-button:hover, .dt-paging-button.current:hover {
        border-radius: 50% !important;
        background-color: #40916c !important;
        color: white !important;
        font-size: 0.8em;
    }

    /* jQuery Notify */
    .notifyjs-bootstrap-success {
        background: #2d6a4f !important; /* Green for success notification */
        font-family: 'Poppins', sans-serif;
        color: #FFFFFF !important;
    }

    @media (max-width: 767px) {
        .table td,
        .table th {
            font-size: 0.75rem;
            padding: 0.5rem;
        }
    }
</style>

<script>
    var oTable = $('#siteTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/payments.php'
        },
        columns: [
            { data: 'date', className: 'text-sm' },
            { data: 'payer', className: 'text-sm' },
            { data: 'produce', className: 'text-sm' },
            { data: 'amount', className: 'text-sm' },
            { data: 'account', className: 'text-sm' },
            { data: 'actions', className: 'text-sm' },
        ],
        language: {
            emptyTable: "No payments found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        },
        ordering: false, /* Disable sorting to prevent sort icons */
        columnDefs: [
            { targets: 3, className: 'text-sm text-left' } /* Ensure Amount column is left-aligned */
        ]
    });

    // Handle View Payment
    $(document).on('click', '.viewPayment_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewPayment.php";
        var successCallback = function(response) {
            $('#viewPaymentContent').html(response);
            $('#viewPaymentModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });

    // Handle Edit Payment
    $(document).on('click', '.editPayment_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editPayment.php";
        var successCallback = function(response) {
            $('#addpayment-tab').tab('show');
            $('#addpayment-tab').html('<i class="fas fa-edit me-2"></i>Edit Payment');
            $('#pageForm').html(response);
        };
        saveForm(formData, url, successCallback);
    });

    // Handle Delete Payment
    $(document).off('click', '.deletePayment_btn').on('click', '.deletePayment_btn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Payment',
            content: 'Are you sure you want to delete this payment?',
            theme: 'modern',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-outline-secondary'
                },
                confirm: {
                    text: 'Delete',
                    btnClass: 'btn-danger',
                    action: function() {
                        var formData = { i_index: theindex };
                        var url = "ajaxscripts/queries/deletePayment.php";
                        var successCallback = function(response) {
                            $.notify("Payment deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/payments.php", function(response) {
                                $('#pageTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });
</script>