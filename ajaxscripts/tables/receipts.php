<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Date</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Payee</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Produce</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount (In GHS)</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nominal Account</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<script>
    var oTable = $('#siteTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/receipts.php'
        },
        columns: [
            { data: 'date', className: 'text-sm' },
            { data: 'payee', className: 'text-sm' },
            { data: 'produce', className: 'text-sm' },
            { data: 'amount', className: 'text-sm' },
            { data: 'account', className: 'text-sm' },
            { data: 'actions', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No receipts found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });

    
    // Handle View Income
    $(document).on('click', '.viewReceipt_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewReceipt.php";
        var successCallback = function(response) {
            $('#viewReceiptContent').html(response);
            $('#viewReceiptModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });


     // Handle Edit Receipt
        $(document).on('click', '.editReceipt_btn', function() {
            var theindex = $(this).attr('i_index');
            var formData = { i_index: theindex };
            var url = "ajaxscripts/forms/editReceipt.php";
            var successCallback = function(response) {
                $('#addreceipt-tab').tab('show');
                $('#addreceipt-tab').html('<i class="fas fa-edit me-2"></i>Edit Receipt');
                $('#pageForm').html(response);
            };
            saveForm(formData, url, successCallback);
        });




    $(document).off('click', '.deleteIncome_btn').on('click', '.deleteIncome_btn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Income',
            content: 'Are you sure you want to delete this income?',
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
                        var url = "ajaxscripts/queries/deleteIncome.php";
                        var successCallback = function(response) {
                            $.notify("Income deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/income.php", function(response) {
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