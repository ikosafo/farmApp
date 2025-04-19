<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="siteTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Produce Name</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Category</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Price</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Quantity</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<div class="modal fade" id="produceModal" tabindex="-1" aria-labelledby="produceModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-radius-xl">
            <div class="modal-header border-0">
                <h5 class="modal-title font-weight-bolder" id="produceModal">View Produce</h5>
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
            url: 'ajaxscripts/paginations/produce.php'
        },
        columns: [
            { data: 'produceName', className: 'text-sm' },
            { data: 'produceCategory', className: 'text-sm' },
            { data: 'producePrice', className: 'text-sm' },
            { data: 'produceQuantity', className: 'text-sm' },
            { data: 'produceActions', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No produce found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });


    $(document).off('click', '.deleteProduce_btn').on('click', '.deleteProduce_btn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Produce',
            content: 'Are you sure you want to delete this produce?',
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
                        var url = "ajaxscripts/queries/deleteProduce.php";
                        var successCallback = function(response) {
                            $.notify("Produce deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/produce.php", function(response) {
                                $('#pageTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });


    $(document).on('click', '.viewProduce_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewProduce.php";
        var successCallback = function(response) {
            $('#pageForm3').html(response);
            $('#produceModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });

    $(document).on('click', '.editProduce_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editProduce.php";
        var successCallback = function(response) {
            $('#pageForm3').html(response);
            $('#produceModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });
</script>