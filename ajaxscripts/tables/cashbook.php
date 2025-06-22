<?php
// Start output buffering to capture any stray output
ob_start();

// Set content type to JSON
header('Content-Type: application/json');

// Enable error logging (disable display for production)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/php_errors.log'); // Replace with actual writable path
error_reporting(E_ALL);

// Include database configuration
try {
    include('../../config.php');
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to include config.php: ' . $e->getMessage()
    ], JSON_THROW_ON_ERROR);
    exit;
}

try {
    // Verify MySQLi connection
    if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
        throw new Exception('Database connection not initialized. Check config.php for $mysqli definition.');
    }

    // Check connection
    if ($mysqli->connect_errno) {
        throw new Exception("MySQLi connection failed: {$mysqli->connect_error}");
    }

    // Get POST parameters
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $categoryName = $_POST['categoryName'] ?? null;

    // Validate input
    if (!$startDate || !$endDate) {
        throw new Exception('Start date and end date are required.');
    }

    // Base query for transactions with LEFT JOINs for produce and category names
    $query = "SELECT 
        t.transactionDate,
        t.payeePayer,
        t.details,
        p.prodName AS produce,
        t.invoiceNo,
        t.currency,
        t.amount,
        t.exchangeRate,
        t.ghsEquivalent,
        t.transactionType,
        c.categoryName AS nominalAccount,
        t.balance
    FROM cashbook_transactions t
    LEFT JOIN producelist p ON t.produce = p.prodId
    LEFT JOIN categories c ON t.nominalAccount = c.catId
    WHERE t.transStatus = 1 AND t.transactionDate BETWEEN ? AND ?";

    // Add category filter if provided
    $params = [$startDate, $endDate];
    $types = 'ss';
    if ($categoryName) {
        $query .= " AND c.categoryName = ?";
        $params[] = $categoryName;
        $types .= 's';
    }

    // Prepare and bind parameters
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception('Query preparation failed: ' . $mysqli->error);
    }
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get categories for nominal account filter
    $categories_query = "SELECT categoryName FROM categories ORDER BY categoryName";
    $categories_result = $mysqli->query($categories_query);
    if (!$categories_result) {
        throw new Exception('Categories query failed: ' . $mysqli->error);
    }
    $categories = $categories_result->fetch_all(MYSQLI_ASSOC);
    $categories_result->free();

    // Clean buffer and output JSON
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'data' => $transactions,
        'categories' => $categories
    ], JSON_THROW_ON_ERROR);

} catch (Exception $e) {
    // Clean buffer and output error JSON
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_THROW_ON_ERROR);
}
?>