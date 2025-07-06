<?php
ob_start();
error_log("Script started");

header('Content-Type: application/json');
error_log("Header set");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
error_reporting(E_ALL);
error_log("Error reporting configured");

ini_set('memory_limit', '256M');
ini_set('max_execution_time', 30);

try {
    include('../../config.php');
    error_log("Config included");

    if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
        throw new Exception('Database connection not initialized. Check config.php for $mysqli definition.');
    }
    error_log("MySQLi connection verified");

    if ($mysqli->connect_errno) {
        throw new Exception("MySQLi connection failed: {$mysqli->connect_error}");
    }
    error_log("Database connected");

    if (isset($_POST['fetchCategoriesOnly'])) {
        $categories_query = "SELECT catId, categoryName FROM categories ORDER BY categoryName";
        $categories_result = $mysqli->query($categories_query);
        if (!$categories_result) {
            throw new Exception('Categories query failed: ' . $mysqli->error);
        }
        $categories = $categories_result->fetch_all(MYSQLI_ASSOC);
        $categories_result->free();
        error_log("Categories fetched: " . count($categories));

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

    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $catId = $_POST['catId'] ?? null;
    $produceId = $_POST['produceId'] ?? null;
    $seasonId = $_POST['seasonId'] ?? null;
    error_log("POST params: startDate=$startDate, endDate=$endDate, catId=$catId, produceId=$produceId, seasonId=$seasonId");

    if (!$startDate || !$endDate) {
        throw new Exception('Start date and end date are required.');
    }
    error_log("Input validated");

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
    WHERE t.transStatus = 1";
    $params = [];
    $types = '';

    if ($seasonId) {
        // Fetch season details
        $season_query = "SELECT startMonth, endMonth FROM seasons WHERE seasonid = ? AND seasonStatus = 1";
        $season_stmt = $mysqli->prepare($season_query);
        if (!$season_stmt) {
            throw new Exception('Season query preparation failed: ' . $mysqli->error);
        }
        $season_stmt->bind_param('s', $seasonId);
        $season_stmt->execute();
        $season_result = $season_stmt->get_result();
        if ($season_result->num_rows === 0) {
            throw new Exception('No active season found for the provided season ID.');
        }
        $season = $season_result->fetch_assoc();
        $season_stmt->close();

        $startMonth = (int)$season['startMonth'];
        $endMonth = (int)$season['endMonth'];
        $currentYear = date('Y');

        if ($startMonth <= $endMonth) {
            // Same year
            $startDate = "$currentYear-$startMonth-01";
            $endDate = "$currentYear-$endMonth-" . date('t', mktime(0, 0, 0, $endMonth, 1, $currentYear));
            $query .= " AND t.transactionDate BETWEEN ? AND ?";
            $params = [$startDate, $endDate];
            $types = 'ss';
        } else {
            // Spans different years
            $query .= " AND (
                (t.transactionDate BETWEEN ? AND ?) OR
                (t.transactionDate BETWEEN ? AND ?)
            )";
            $startDate1 = "$currentYear-$startMonth-01";
            $endDate1 = "$currentYear-12-31";
            $startDate2 = ($currentYear + 1) . "-01-01";
            $endDate2 = ($currentYear + 1) . "-$endMonth-" . date('t', mktime(0, 0, 0, $endMonth, 1, $currentYear + 1));
            $params = [$startDate1, $endDate1, $startDate2, $endDate2];
            $types = 'ssss';
        }
    } else {
        $query .= " AND t.transactionDate BETWEEN ? AND ?";
        $params = [$startDate, $endDate];
        $types = 'ss';
        if ($catId) {
            $query .= " AND t.nominalAccount = ?";
            $params[] = $catId;
            $types .= 's';
        }
    }

    if ($produceId) {
        $query .= " AND t.produce = ?";
        $params[] = $produceId;
        $types .= 's';
    }

    error_log("Query prepared: $query");
    error_log("Params: " . json_encode($params));

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

    $categories_query = "SELECT catId, categoryName FROM categories ORDER BY categoryName";
    $categories_result = $mysqli->query($categories_query);
    if (!$categories_result) {
        throw new Exception('Categories query failed: ' . $mysqli->error);
    }
    $categories = $categories_result->fetch_all(MYSQLI_ASSOC);
    $categories_result->free();
    error_log("Categories fetched: " . count($categories));

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