<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="expCategory">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Category Name</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Description</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<div class="modal fade" id="expCatModal" tabindex="-1" aria-labelledby="expCatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-radius-xl">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder" id="expCatModalLabel">View Expenditure Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="pageForm2"></div>
            </div>
        </div>
</div>

<script>
    var oTable = $('#expCategory').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/expCategories.php'
        },
        columns: [
            { data: 'catName', className: 'text-sm' },
            { data: 'catDescription', className: 'text-sm' },
            { data: 'catAction', className: 'text-sm' },
        ],
        language: {
            emptyTable: "No expenditure categories found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });

    $(document).off('click', '.deleteExpCategory').on('click', '.deleteExpCategory', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Category',
            content: 'Are you sure you want to delete this category?',
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
                        var url = "ajaxscripts/queries/deleteExpCategory.php";
                        var successCallback = function(response) {
                            $.notify("Category deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/expCategory.php", function(response) {
                                $('#categoryTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });

    $(document).on('click', '.viewExpCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/viewExpCategory.php";
        var successCallback = function(response) {
            console.log('Response from viewExpCategory.php:', response); 
            $('#pageForm2').empty().html(response); 
            $('#expCatModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });


    $(document).on('click', '.editExpCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        var formData = { i_index: theindex };
        var url = "ajaxscripts/forms/editExpCategory.php";
        var successCallback = function(response) {
            $('#pageForm2').html(response);
            $('#expCatModal').modal('show');
        };
        saveForm(formData, url, successCallback);
    });
</script>