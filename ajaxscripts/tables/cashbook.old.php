<?php
include('../../config.php');
include('../../includes/functions.php');

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Get POST data
$transactionType = isset($_POST['transactionType']) ? mysqli_real_escape_string($mysqli, $_POST['transactionType']) : '';
$startDate = isset($_POST['startDate']) ? mysqli_real_escape_string($mysqli, $_POST['startDate']) : '';
$endDate = isset($_POST['endDate']) ? mysqli_real_escape_string($mysqli, $_POST['endDate']) : '';

// Validate inputs
if (empty($transactionType) || !in_array($transactionType, ['Income', 'Expenditure'])) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Invalid transaction type']);
    exit;
}

if (empty($startDate) || empty($endDate)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Start and end dates are required']);
    exit;
}

// Build the query
$query = "SELECT transactionName, transactionDate, transactionAmount 
          FROM transactions 
          WHERE transStatus = 1 
          AND transactionType = '$transactionType'
          AND transactionDate BETWEEN '$startDate' AND '$endDate'";

$result = $mysqli->query($query);
if (!$result) {
    error_log("SQL Error: " . $mysqli->error . " | Query: $query");
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Database error: ' . $mysqli->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'transactionName' => htmlspecialchars($row['transactionName']),
        'transactionDate' => date('M d, Y', strtotime($row['transactionDate'])),
        'transactionAmount' => floatval($row['transactionAmount'])
    ];
}

// Free result
$result->free();

// Prepare response
$response = [
    'data' => $data
];

header('Content-Type: application/json');
echo json_encode($response);