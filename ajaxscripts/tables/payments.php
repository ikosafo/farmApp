<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Date</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Payer</th>
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
        }
    });

    
    // Handle View Income
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