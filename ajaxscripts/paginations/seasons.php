<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../../config.php');
include('../../includes/functions.php'); 

// Read DataTables parameters
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

// Sanitize column name
$columnSortOrder = $columnSortOrder === 'asc' ? 'ASC' : 'DESC';

// Search query
$searchQuery = "";
if ($searchValue != '') {
    $searchValue = mysqli_real_escape_string($mysqli, $searchValue);
    $searchQuery = " AND (
        startMonth LIKE '%$searchValue%'
        OR endMonth LIKE '%$searchValue%'
        OR seasonName LIKE '%$searchValue%'
        OR (SELECT prodName FROM producelist WHERE prodId = cashbook_transactions.produce) LIKE '%$searchValue%' 
    ) ";
}

// Total records without filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `seasons` WHERE `seasonStatus`= 1");
if (!$sel) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Total records query failed: " . mysqli_error($mysqli)]);
    exit;
}
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

// Total records with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `seasons` WHERE `seasonStatus`= 1 AND 1 $searchQuery");
if (!$sel) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(["error" => "Filtered records query failed: " . mysqli_error($mysqli)]);
    exit;
}
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$empQuery = "SELECT * FROM `seasons` WHERE `seasonStatus`= 1 AND 1 $searchQuery ORDER BY `updatedAt` DESC, `createdAt` DESC  LIMIT $row, $rowperpage";
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
        "produceName" => produceName($row['produceid']),
        "seasonName" => $row['seasonName'],
        "startMonth" => $row['startMonth'],
        "endMonth" => $row['endMonth'],
        "action" => manageSeason($row['seasonid']) ?? ''
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