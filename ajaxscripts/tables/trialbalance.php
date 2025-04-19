<?php
include('../../config.php');
include('../../includes/functions.php');

// Enable error logging for debugging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../../logs/php_errors.log'); // Ensure this path is writable
error_reporting(E_ALL);

// Get POST data
$endDate = isset($_POST['endDate']) ? mysqli_real_escape_string($mysqli, $_POST['endDate']) : '';

// Validate input
if (empty($endDate)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Date is required']);
    exit;
}

// Verify database connection
if ($mysqli->connect_error) {
    error_log("Database connection failed: " . $mysqli->connect_error);
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Ensure incCategoryName and expCategoryName functions exist
if (!function_exists('incCategoryName') || !function_exists('expCategoryName')) {
    error_log("Category name functions missing: incCategoryName or expCategoryName not defined");
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Category name functions not defined']);
    exit;
}

// Step 1: Get all unique categories and assign account types
$categories_query = "
    SELECT DISTINCT transactionCategory, transactionType
    FROM transactions
    WHERE transStatus = 1
    AND transactionDate <= '$endDate'";
$categories_result = $mysqli->query($categories_query);
if (!$categories_result) {
    error_log("Categories SQL Error: " . $mysqli->error);
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Database error: ' . $mysqli->error]);
    exit;
}

$category_types = ['Cash' => 'Asset']; // Cash as a fixed Asset account
$category_names = ['Cash' => 'Cash'];
while ($row = $categories_result->fetch_assoc()) {
    $category_id = $row['transactionCategory'];
    $type = $row['transactionType'];
    // Assign account type and name based on transactionType
    if ($type === 'Income') {
        $category_types[$category_id] = 'Revenue';
        $category_names[$category_id] = incCategoryName($category_id);
    } elseif ($type === 'Expenditure') {
        $category_types[$category_id] = 'Expense';
        $category_names[$category_id] = expCategoryName($category_id);
    }
}
$categories_result->free();

// Step 2: Aggregate debits and credits for each category
$data = [];
foreach ($category_types as $category_id => $account_type) {
    $debit = 0;
    $credit = 0;

    if ($category_id === 'Cash') {
        // Cash: Debits from Income, Credits from Expenditure
        $cash_query = "
            SELECT 
                SUM(CASE WHEN transactionType = 'Income' THEN transactionAmount ELSE 0 END) as debit,
                SUM(CASE WHEN transactionType = 'Expenditure' THEN transactionAmount ELSE 0 END) as credit
            FROM transactions
            WHERE transStatus = 1
            AND transactionDate <= '$endDate'";
        $cash_result = $mysqli->query($cash_query);
        if ($cash_result) {
            $row = $cash_result->fetch_assoc();
            $debit = floatval($row['debit'] ?? 0);
            $credit = floatval($row['credit'] ?? 0);
            $cash_result->free();
        } else {
            error_log("Cash SQL Error: " . $mysqli->error);
        }
    } else {
        // Other categories: Debits for Expenditure, Credits for Income
        $query = "
            SELECT 
                SUM(CASE WHEN transactionType = 'Expenditure' THEN transactionAmount ELSE 0 END) as debit,
                SUM(CASE WHEN transactionType = 'Income' THEN transactionAmount ELSE 0 END) as credit
            FROM transactions
            WHERE transStatus = 1
            AND transactionDate <= '$endDate'
            AND transactionCategory = '" . $mysqli->real_escape_string($category_id) . "'";
        $result = $mysqli->query($query);
        if ($result) {
            $row = $result->fetch_assoc();
            $debit = floatval($row['debit'] ?? 0);
            $credit = floatval($row['credit'] ?? 0);
            $result->free();
        } else {
            error_log("Category SQL Error for $category_id: " . $mysqli->error);
        }
    }

    if ($debit > 0 || $credit > 0) {
        $data[] = [
            'transactionCategory' => htmlspecialchars($category_names[$category_id] ?? 'Uncategorized'),
            'accountType' => $account_type,
            'debit' => $debit,
            'credit' => $credit
        ];
    }
}

// Debug: Log the data
error_log("Trial Balance Data: " . json_encode($data));

// Close connection
$mysqli->close();

// Prepare response
$response = [
    'data' => $data
];

header('Content-Type: application/json');
echo json_encode($response);
?>