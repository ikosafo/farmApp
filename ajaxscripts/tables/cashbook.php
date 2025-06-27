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

// Set resource limits
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 30);

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
    ]);
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

    // Handle categories-only request
    if (isset($_POST['fetchCategoriesOnly'])) {
        $categories_query = "SELECT catId, categoryName FROM categories ORDER BY categoryName";
        $categories_result = $mysqli->query($categories_query);
        if (!$categories_result) {
            throw new Exception('Categories query failed: ' . $mysqli->error);
        }
        $categories = $categories_result->fetch_all(MYSQLI_ASSOC);
        $categories_result->free();
        error_log("Categories fetched: " . count($categories));

        // Sanitize category names
        foreach ($categories as &$category) {
            $category['categoryName'] = mb_convert_encoding($category['categoryName'] ?? '', 'UTF-8', 'UTF-8');
        }
        unset($category);

        ob_end_clean();
        error_log("Buffer cleaned");
        $response = ['success' => true, 'categories' => $categories];
        $json = json_encode($response);
        if ($json === false) {
            throw new Exception('JSON encoding failed: ' . json_last_error_msg());
        }
        error_log("JSON Response: $json");
        echo $json;
        exit;
    }

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

    // Sanitize data
    foreach ($transactions as &$transaction) {
        $transaction['payeePayer'] = mb_convert_encoding($transaction['payeePayer'] ?? '', 'UTF-8', 'UTF-8');
        $transaction['details'] = mb_convert_encoding($transaction['details'] ?? '', 'UTF-8', 'UTF-8');
        $transaction['produce'] = mb_convert_encoding($transaction['produce'] ?? '', 'UTF-8', 'UTF-8');
        $transaction['nominalAccount'] = mb_convert_encoding($transaction['nominalAccount'] ?? '', 'UTF-8', 'UTF-8');
    }
    unset($transaction);

    foreach ($categories as &$category) {
        $category['categoryName'] = mb_convert_encoding($category['categoryName'] ?? '', 'UTF-8', 'UTF-8');
    }
    unset($category);

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
    $json = json_encode($response);
    if ($json === false) {
        throw new Exception('JSON encoding failed: ' . json_last_error_msg());
    }
    error_log("JSON Response: $json");
    echo $json;
    exit;

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    ob_end_clean();
    http_response_code(500);
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    $json = json_encode($response);
    if ($json === false) {
        $response = [
            'success' => false,
            'error' => 'JSON encoding failed: ' . json_last_error_msg()
        ];
        $json = json_encode($response);
    }
    error_log("Error JSON Response: $json");
    echo $json;
    exit;
}
?>