<div class="card mt-4" data-animation="FadeIn">
    <div class="row">
        <div class="col-12">
            <div class="card" style="padding:30px">

                <div class="table-responsive">
                    <table class="table table-flush" id="siteTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expiry</th>
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
            'url': 'ajaxscripts/paginations/products.php'
        },
        'columns': [{
                data: 'productName'
            },
            {
                data: 'productDescription'
            },
            {
                data: 'productCategory'
            },
            {
                data: 'productPrice'
            },
            {
                data: 'productQuantity'
            },
            {
                data: 'productExpiry'
            },
            {
                data: 'productId'
            }
        ],
        'columnDefs': [{
            targets: [0, 1, 2, 3, 4, 5, 6],
            className: 'text-sm font-weight-normal'
        }]
    });


    $(document).off('click', '.deleteProduct_btn').on('click', '.deleteProduct_btn', function() {
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
                        var url = "ajaxscripts/queries/deleteProduct.php";
                        var successCallback = function(response) {
                            //alert(response);
                            console.log(response);
                            loadPage("ajaxscripts/tables/products.php", function(response) {
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

    $(document).off('click', '.editProduct_btn').on('click', '.editProduct_btn', function() {
        var theindex = $(this).attr('i_index');
        //alert(theindex);

        var formData = {
            i_index: theindex
        };
        var url = "ajaxscripts/forms/editProduct.php";
        var successCallback = function(response) {
            $('#pageForm').html(response);

            // Scroll to the top of the loaded content with animation
            $('html, body').animate({
                scrollTop: $('#pageForm').offset().top
            }, 'fast');
        };

        // Call the saveForm function with form data, URL, success callback, and validation function
        saveForm(formData, url, successCallback);

    });
</script>