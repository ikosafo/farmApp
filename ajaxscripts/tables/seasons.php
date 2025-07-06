<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="seasonTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Produce Name</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Season</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Start Month</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">End Month</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>


<script>
    var oTable = $('#seasonTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/seasons.php'
        },
        columns: [
            { data: 'produceName', className: 'text-sm' },
            { data: 'seasonName', className: 'text-sm' },
            { data: 'startMonth', className: 'text-sm' },
            { data: 'endMonth', className: 'text-sm' },
            { data: 'action', className: 'text-sm' },
           
        ],
        language: {
            emptyTable: "No season found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });


    $(document).off('click', '.deleteSeasonBtn').on('click', '.deleteSeasonBtn', function() {
        var theindex = $(this).attr('i_index');
        $.confirm({
            title: 'Delete Season',
            content: 'Are you sure you want to delete this season?',
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
                        var url = "ajaxscripts/queries/deleteSeason.php";
                        var successCallback = function(response) {
                            $.notify("Produce deleted successfully!", {
                                className: "success",
                                position: "top center"
                            });
                            loadPage("ajaxscripts/tables/seasons.php", function(response) {
                                $('#seasonsTable').html(response);
                            });
                        };
                        saveForm(formData, url, successCallback);
                    }
                }
            }
        });
    });

</script>