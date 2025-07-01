<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../../config.php');
include('../../includes/functions.php'); // Ensure these functions are correctly included

// Check database connection
/* if (mysqli_connect_errno()) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database connection failed: " . mysqli_connect_error()]);
    exit;
} */

// Read DataTables parameters
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

// Sanitize column name
$allowedColumns = ['transactionDate', 'payeePayer', 'produce', 'ghsEquivalent', 'nominalAccount'];
$columnName = in_array($columnName, $allowedColumns) ? $columnName : 'transactionDate';
$columnSortOrder = $columnSortOrder === 'asc' ? 'ASC' : 'DESC';

// Search query
$searchQuery = "";
if ($searchValue != '') {
    $searchValue = mysqli_real_escape_string($mysqli, $searchValue);
    $searchQuery = " AND (
        transactionDate LIKE '%$searchValue%'
        OR payeePayer LIKE '%$searchValue%'
        OR details LIKE '%$searchValue%'
        OR invoiceNo LIKE '%$searchValue%'
        OR currency LIKE '%$searchValue%'
        OR amount LIKE '%$searchValue%'
        OR exchangeRate LIKE '%$searchValue%'
        OR ghsEquivalent LIKE '%$searchValue%'
        OR transactionType LIKE '%$searchValue%'
        OR (SELECT prodName FROM producelist WHERE prodId = cashbook_transactions.produce) LIKE '%$searchValue%' /* Added for produceName */
        OR (SELECT categoryName FROM categories WHERE catId = cashbook_transactions.nominalAccount) LIKE '%$searchValue%'
    ) ";
}

// Total records without filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `cashbook_transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Payment'");
if (!$sel) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Total records query failed: " . mysqli_error($mysqli)]);
    exit;
}
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

// Total records with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `cashbook_transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Payment' AND 1 $searchQuery");
if (!$sel) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Filtered records query failed: " . mysqli_error($mysqli)]);
    exit;
}
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$empQuery = "SELECT * FROM `cashbook_transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Payment' AND 1 $searchQuery ORDER BY `transactionDate` DESC, `updatedAt` DESC  LIMIT $row, $rowperpage";
$empRecords = mysqli_query($mysqli, $empQuery);
if (!$empRecords) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Data query failed: " . mysqli_error($mysqli)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = [
        "date" => !empty($row['transactionDate']) && date_create($row['transactionDate']) ? date_format(date_create($row['transactionDate']), 'd-M-y') : '',
        "payer" => $row['payeePayer'],
        "produce" => produceName($row['produce']) ?? '',
        "amount" => $row['ghsEquivalent'],
        "account" => categoryName($row['nominalAccount']) ?? '',
        "actions" => managePayment($row['transactionId']) ?? ''
    ];
}

// Response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecordwithFilter,
    "data" => $data
];

// Log response for debugging
file_put_contents('debug.json', json_encode($response, JSON_PRETTY_PRINT));

ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;