<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="gencategoryTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Category Name</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Description</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>



<script>
    var oTable = $('#gencategoryTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/categories.php'
        },
        columns: [
            { data: 'catName', className: 'text-sm' },
            { data: 'catDescription', className: 'text-sm' },
            { data: 'catAction', className: 'text-sm' },
        ],
        language: {
            emptyTable: "No product categories found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });

    $(document).off('click', '.deleteCategory').on('click', '.deleteCategory', function() {
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
                        var url = "ajaxscripts/queries/deleteCategory.php";
                        var successCallback = function(response) {
                            $.notify("Category deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/category.php", function(response) {
                                $('#categoryTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });

   
</script>