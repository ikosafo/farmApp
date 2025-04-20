<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Customer Details</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Order Details</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Delivery</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Payment Details</th>
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
            url: 'ajaxscripts/paginations/orders.php'
        },
        columns: [
            { data: 'customerDetails', className: 'text-sm' },
            { data: 'orderDetails', className: 'text-sm' },
            { data: 'deliveryDetails', className: 'text-sm' },
            { data: 'paymentDetails', className: 'text-sm' },
            { data: 'orderActions', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No orders found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });


    $(document).off('click', '.deleteOrder_btn').on('click', '.deleteOrder_btn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Expenditure',
            content: 'Are you sure you want to delete this order?',
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
                        var url = "ajaxscripts/queries/deleteOrder.php";
                        var successCallback = function(response) {
                            $.notify("Order deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/orders.php", function(response) {
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