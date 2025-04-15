<div class="card mt-4" data-animation="FadeIn">
    <div class="row">
        <div class="col-12">
            <div class="card" style="padding:30px">

                <div class="table-responsive">
                    <table class="table table-flush" id="siteTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category Code</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>



<script>
    oTable = $('#siteTable').DataTable({
        stateSave: true,
        "bLengthChange": false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': 'ajaxscripts/paginations/expenseCategory.php'
        },
        'columns': [{
                data: 'categoryName'
            },
            {
                data: 'categoryCode'
            },
            {
                data: 'categoryId'
            }
        ],
        'columnDefs': [{
            targets: [0, 1, 2],
            className: 'text-sm font-weight-normal'
        }]
    });


    $(document).off('click', '.deleteCategory_btn').on('click', '.deleteCategory_btn', function() {
        var theindex = $(this).attr('i_index');
        //alert(theindex);
        $.confirm({
            title: 'Delete Record!',
            content: 'Are you sure to continue?',
            buttons: {
                no: {
                    text: 'No',
                    keys: ['enter', 'shift'],
                    backdrop: 'static',
                    keyboard: false,
                    action: function() {
                        $.alert('Data is safe');
                    }
                },
                yes: {
                    text: 'Yes, Delete it!',
                    btnClass: 'btn-blue',
                    action: function() {

                        var formData = {
                            i_index: theindex
                        };
                        var url = "ajaxscripts/queries/deletesExpCategory.php";
                        var successCallback = function(response) {
                            //alert(response);
                            console.log(response);
                            loadPage("ajaxscripts/tables/expenseCategories.php", function(response) {
                                $('#pageTable').html(response);
                            });

                        };

                        // Call the saveForm function with form data, URL, success callback, and validation function
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });
</script>