<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="usersTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Full Name</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Phone Number</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Role</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Username</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>



<script>
    var oTable = $('#usersTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/users.php'
        },
        columns: [
            { data: 'fullName', className: 'text-sm' },
            { data: 'phoneNumber', className: 'text-sm' },
            { data: 'userRole', className: 'text-sm' },
            { data: 'username', className: 'text-sm' },
            { data: 'userActions', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No users found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });


    $(document).off('click', '.deleteUser_btn').on('click', '.deleteUser_btn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete User',
            content: 'Are you sure you want to delete this user?',
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
                        var url = "ajaxscripts/queries/deleteUser.php";
                        var successCallback = function(response) {
                            $.notify("User deleted successfully!", {
                                className: "success",
                                position: "top right"
                            });
                            loadPage("ajaxscripts/tables/users.php", function(response) {
                                $('#userTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });


</script>