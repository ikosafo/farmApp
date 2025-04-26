<div class="table-responsive">
    <!-- Filter Section -->
    <div class="mb-3">
        <label for="paymentStatusFilter">Filter by Payment Status:</label>
        <select id="paymentStatusFilter" class="form-control" style="width: 200px;">
            <option value="">All</option>
            <option value="Part Payment">Part Payment</option>
            <option value="Full Payment">Full Payment</option>
            <option value="Overpaid">Overpaid</option>
            <option value="Pending">Pending</option>
            <option value="Refunded">Refunded</option>
            <option value="On Hold">On Hold</option>
        </select>
    </div>

    <!-- Total Amount Summary -->
    <div id="totalAmountSummary" class="mb-3">
        <h5>Total Amount by Payment Status</h5>
        <div id="summaryContent"></div>
    </div>

    <!-- DataTable -->
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Customer Details</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Delivery Details</th>
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
            url: 'ajaxscripts/paginations/orders.php',
            data: function(d) {
                d.paymentStatus = $('#paymentStatusFilter').val();
            }
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

    // Reload table when paymentStatus filter changes
    $('#paymentStatusFilter').on('change', function() {
        oTable.draw(); // Redraw the DataTable with the new filter
        fetchTotalAmountSummary(); // Update the total amount summary
    });

    // Function to fetch and display total amount by payment status
    function fetchTotalAmountSummary() {
        $.ajax({
            url: 'ajaxscripts/queries/totalAmountByStatus.php',
            method: 'POST',
            data: {
                paymentStatus: $('#paymentStatusFilter').val()
            },
            success: function(response) {
                var data = JSON.parse(response);
                var html = '';
                if (data.length > 0) {
                    data.forEach(function(item) {
                        html += `<p><strong>${item.paymentStatus}</strong>: GHC ${parseFloat(item.totalAmount).toFixed(2)}</p>`;
                    });
                } else {
                    html = '<p>No data available.</p>';
                }
                $('#summaryContent').html(html);
            },
            error: function() {
                $('#summaryContent').html('<p>Error fetching data.</p>');
            }
        });
    }

    // Initial fetch of total amount summary
    fetchTotalAmountSummary();

    // Handle delete button click (unchanged)
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
                            fetchTotalAmountSummary(); // Update summary after deletion
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });
</script>