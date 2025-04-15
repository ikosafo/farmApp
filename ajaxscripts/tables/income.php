<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Receivable</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Category</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
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
            url: 'ajaxscripts/paginations/incomes.php'
        },
        columns: [
            { data: 'incomeName', className: 'text-sm' },
            { data: 'incomeDate', className: 'text-sm' },
            { data: 'incomeCategory', className: 'text-sm' },
            { data: 'incomeAmount', className: 'text-sm' },
            { data: 'incomeActions', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No incomes found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
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