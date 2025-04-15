<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Expenditure Name</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Category</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
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


<script>
    var oTable = $('#siteTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/expenditures.php'
        },
        columns: [
            { data: 'expenditureName', className: 'text-sm' },
            { data: 'expenditureDate', className: 'text-sm' },
            { data: 'expenditureCategory', className: 'text-sm' },
            { data: 'expenditureAmount', className: 'text-sm' },
            { data: 'expenditureActions', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No expenditures found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });


    $(document).off('click', '.deleteExpenditure_btn').on('click', '.deleteExpenditure_btn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Expenditure',
            content: 'Are you sure you want to delete this expenditure?',
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
                        var url = "ajaxscripts/queries/deleteExpenditure.php";
                        var successCallback = function(response) {
                            $.notify("Expenditure deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/expenditure.php", function(response) {
                                $('#pageTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
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