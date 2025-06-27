<?php
// Start output buffering
ob_start();
error_log("Script started");

// Set content type to JSON
header('Content-Type: application/json');
error_log("Header set");

// Enable error logging
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);
error_log("Error reporting configured");

// Include database configuration
try {
    include('../../config.php');
    error_log("Config included");
} catch (Exception $e) {
    error_log("Config include failed: " . $e->getMessage());
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
    error_log("MySQLi connection verified");

    // Check connection
    if ($mysqli->connect_errno) {
        throw new Exception("MySQLi connection failed: {$mysqli->connect_error}");
    }
    error_log("Database connected");

    // Get POST parameters
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $catId = $_POST['catId'] ?? null;
    error_log("POST params: startDate=$startDate, endDate=$endDate, catId=$catId");

    // Validate input
    if (!$startDate || !$endDate) {
        throw new Exception('Start date and end date are required.');
    }
    error_log("Input validated");

    // Base query
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
        c.catId
    FROM cashbook_transactions t
    LEFT JOIN producelist p ON t.produce = p.prodId
    LEFT JOIN categories c ON t.nominalAccount = c.catId
    WHERE t.transStatus = 1 AND t.transactionDate BETWEEN ? AND ?";
    error_log("Query prepared: $query");

    // Add category filter
    $params = [$startDate, $endDate];
    $types = 'ss';
    if ($catId) {
        $query .= " AND t.nominalAccount = ?";
        $params[] = $catId;
        $types .= 's';
    }
    error_log("Params: " . json_encode($params));

    // Prepare and bind
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception('Query preparation failed: ' . $mysqli->error);
    }
    error_log("Statement prepared");

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    error_log("Query executed");

    $result = $stmt->get_result();
    $transactions = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    error_log("Transactions fetched: " . count($transactions));

    // Get categories
    $categories_query = "SELECT catId, categoryName FROM categories ORDER BY categoryName";
    $categories_result = $mysqli->query($categories_query);
    if (!$categories_result) {
        throw new Exception('Categories query failed: ' . $mysqli->error);
    }
    $categories = $categories_result->fetch_all(MYSQLI_ASSOC);
    $categories_result->free();
    error_log("Categories fetched: " . count($categories));

    // Calculate category statistics
    $categoryStats = [];
    foreach ($categories as $cat) {
        $catId = $cat['catId'];
        $categoryName = $cat['categoryName'];
        $income = 0;
        $expenditure = 0;

        foreach ($transactions as $t) {
            if ($t['catId'] == $catId || (!$catId && !$t['catId'])) {
                $ghsAmount = floatval($t['ghsEquivalent']) ?? 0;
                if ($t['transactionType'] === 'Receipt') {
                    $income += $ghsAmount;
                } else {
                    $expenditure += $ghsAmount;
                }
            }
        }

        $categoryStats[] = [
            'catId' => $catId,
            'categoryName' => $categoryName,
            'totalIncome' => $income,
            'totalExpenditure' => $expenditure
        ];
    }
    error_log("Category stats calculated");

    // Clean buffer and output JSON
    ob_end_clean();
    error_log("Buffer cleaned");
    $response = [
        'success' => true,
        'data' => $transactions,
        'categories' => $categories,
        'categoryStats' => $categoryStats
    ];
    error_log("JSON Response: " . json_encode($response, JSON_THROW_ON_ERROR));
    echo json_encode($response, JSON_THROW_ON_ERROR);
    error_log("JSON output sent");
    exit;

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    ob_end_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_THROW_ON_ERROR);
    exit;
}
?>