<?php
// Start output buffering to prevent stray output
ob_start();

// Include database configuration
include('../../config.php');

// Disable display of errors to prevent JSON corruption
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Handle server-side processing for DataTables
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['draw'])) {
    // DataTables parameters
    $draw = intval($_POST['draw']);
    $start = intval($_POST['start']);
    $length = intval($_POST['length']);
    $searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($mysqli, $_POST['search']['value']) : '';
    $orderColumnIndex = isset($_POST['order'][0]['column']) ? intval($_POST['order'][0]['column']) : 0;
    $orderDir = isset($_POST['order'][0]['dir']) ? mysqli_real_escape_string($mysqli, $_POST['order'][0]['dir']) : 'asc';
    $columns = ['transactionDate', 'payeePayer', 'details', 'amount', 'transactionType', 'balance'];

    // Map DataTables column index to database column
    $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'transactionDate';

    // Base query
    $query = "SELECT * FROM cashbook_transactions WHERE 1=1";

    // Search filter
    if (!empty($searchValue)) {
        $query .= " AND (transactionDate LIKE '%$searchValue%' 
                        OR payeePayer LIKE '%$searchValue%' 
                        OR details LIKE '%$searchValue%' 
                        OR amount LIKE '%$searchValue%' 
                        OR transactionType LIKE '%$searchValue%')";
    }

    // Order and limit
    $query .= " ORDER BY $orderColumn $orderDir LIMIT $start, $length";

    // Get total records
    $totalRecordsQuery = "SELECT COUNT(*) as total FROM cashbook_transactions";
    $totalRecordsResult = $mysqli->query($totalRecordsQuery);
    if (!$totalRecordsResult) {
        error_log("Total records query failed: " . $mysqli->error);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
        ob_end_clean();
        exit;
    }
    $totalRecords = $totalRecordsResult->fetch_assoc()['total'];

    // Get filtered records count
    $filteredRecordsQuery = "SELECT COUNT(*) as total FROM cashbook_transactions WHERE 1=1";
    if (!empty($searchValue)) {
        $filteredRecordsQuery .= " AND (transactionDate LIKE '%$searchValue%' 
                                       OR payeePayer LIKE '%$searchValue%' 
                                       OR details LIKE '%$searchValue%' 
                                       OR amount LIKE '%$searchValue%' 
                                       OR transactionType LIKE '%$searchValue%')";
    }
    $filteredRecordsResult = $mysqli->query($filteredRecordsQuery);
    if (!$filteredRecordsResult) {
        error_log("Filtered records query failed: " . $mysqli->error);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
        ob_end_clean();
        exit;
    }
    $totalFiltered = $filteredRecordsResult->fetch_assoc()['total'];

    // Fetch data
    $result = $mysqli->query($query);
    if (!$result) {
        error_log("Data query failed: " . $mysqli->error);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error']);
        ob_end_clean();
        exit;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Format action buttons
        $actions = '
            <button class="btn btn-sm btn-primary viewTransaction_btn" i_index="' . $row['id'] . '"><i class="fas fa-eye"></i></button>
            <button class="btn btn-sm btn-warning editTransaction_btn" i_index="' . $row['id'] . '"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger deleteTransaction_btn" i_index="' . $row['id'] . '"><i class="fas fa-trash"></i></button>
        ';

        $data[] = [
            'transactionDate' => $row['transactionDate'],
            'payeePayer' => htmlspecialchars($row['payeePayer']),
            'details' => htmlspecialchars($row['details']),
            'amount' => number_format($row['amount'], 2),
            'transactionType' => $row['transactionType'],
            'balance' => number_format($row['balance'], 2),
            'actions' => $actions
        ];
    }

    // JSON response for DataTables
    $response = [
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    ];

    // Clean output buffer and send JSON
    header('Content-Type: application/json');
    ob_end_clean();
    echo json_encode($response);
    exit;
}
?>

 <style>
        .table-responsive {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
    <div class="table-responsive">
        <table class="table table-hover align-items-center mb-0" id="cashbookTable">
            <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Date</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Payee/Payer</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Details</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Transaction Type</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Balance</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

   
    <script>
        // Basic saveForm function (replace with your actual implementation if different)
        function saveForm(formData, url, successCallback) {
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: successCallback,
                error: function(xhr, status, error) {
                    $.notify('An error occurred: ' + error, {
                        className: 'error',
                        position: 'top right'
                    });
                }
            });
        }

        var oTable = $('#cashbookTable').DataTable({
            stateSave: true,
            lengthChange: false,
            processing: true,
            serverSide: true,
            serverMethod: 'post',
            ajax: {
                url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                error: function(xhr, error, thrown) {
                    console.log('AJAX error:', xhr.responseText);
                    $.notify('Failed to load table data: ' + error, {
                        className: 'error',
                        position: 'top right'
                    });
                }
            },
            columns: [
                { data: 'transactionDate', className: 'text-sm' },
                { data: 'payeePayer', className: 'text-sm' },
                { data: 'details', className: 'text-sm' },
                { data: 'amount', className: 'text-sm' },
                { data: 'transactionType', className: 'text-sm' },
                { data: 'balance', className: 'text-sm' },
                { data: 'actions', className: 'text-sm' },
            ],
            language: {
                emptyTable: "No transactions found",
                processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
            }
        });

        $(document).off('click', '.deleteTransaction_btn').on('click', '.deleteTransaction_btn', function() {
            var theindex = $(this).attr('i_index');
            $.confirm({
                title: 'Delete Transaction',
                content: 'Are you sure you want to delete this transaction?',
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
                            var url = "ajaxscripts/queries/deleteTransaction.php";
                            var successCallback = function(response) {
                                $.notify("Transaction deleted successfully!", {
                                    className: "success",
                                    position: "top right"
                                });
                                oTable.ajax.reload();
                            };
                            saveForm(formData, url, successCallback);
                        }
                    }
                }
            });
        });
    </script>

<?php
// Clean up output buffer
ob_end_flush();
?>