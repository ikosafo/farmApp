<?php
include('../../config.php'); 

header('Content-Type: application/json');

$produceId = isset($_POST['produceId']) ? $mysqli->real_escape_string($_POST['produceId']) : '';
$seasonId = isset($_POST['seasonId']) ? $mysqli->real_escape_string($_POST['seasonId']) : '';
$fetchSeasons = isset($_POST['fetchSeasons']) && $_POST['fetchSeasons'] === 'true';

if ($fetchSeasons) {
    // Handle fetchSeasons request
    if (!$produceId) {
        echo json_encode(['success' => false, 'error' => 'No produce selected']);
        exit;
    }

    $response = ['success' => true, 'seasons' => []];

    $seasonsQuery = "
        SELECT seasonId, seasonName, startMonth, endMonth
        FROM seasons
        WHERE seasonStatus = 1 AND produceid = ?
    ";
    $stmt = $mysqli->prepare($seasonsQuery);
    $stmt->bind_param('i', $produceId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response['seasons'][] = [
            'seasonId' => $row['seasonId'],
            'seasonName' => $row['seasonName'],
            'startMonth' => $row['startMonth'],
            'endMonth' => $row['endMonth']
        ];
    }

    $stmt->close();
    echo json_encode($response);
    $mysqli->close();
    exit;
}

if (!$produceId) {
    echo json_encode(['success' => false, 'error' => 'No produce selected']);
    exit;
}

$response = ['success' => true, 'data' => [], 'categoryStats' => [], 'categories' => []];

// Build the transaction query with season filtering if seasonId is provided
$transactionQuery = "
    SELECT ct.*, pl.prodName, c.catId, c.categoryName
    FROM cashbook_transactions ct
    LEFT JOIN producelist pl ON ct.produce = pl.prodId
    LEFT JOIN categories c ON ct.nominalAccount = c.catId
    WHERE ct.produce = ?
";

if ($seasonId) {
    // Fetch season details to get startMonth and endMonth
    $seasonQuery = "
        SELECT startMonth, endMonth
        FROM seasons
        WHERE seasonId = ? AND seasonStatus = 1
    ";
    $stmt = $mysqli->prepare($seasonQuery);
    $stmt->bind_param('i', $seasonId);
    $stmt->execute();
    $seasonResult = $stmt->get_result();
    $season = $seasonResult->fetch_assoc();
    $stmt->close();

    if ($season) {
        $startMonth = $mysqli->real_escape_string($season['startMonth']);
        $endMonth = $mysqli->real_escape_string($season['endMonth']);

        // Convert month names to numbers for SQL MONTH function
        $monthMap = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];
        $startMonthNum = $monthMap[$startMonth];
        $endMonthNum = $monthMap[$endMonth];

        // Handle seasons that cross year boundaries (e.g., September to March)
        if ($startMonthNum <= $endMonthNum) {
            $transactionQuery .= " AND MONTH(ct.transactionDate) BETWEEN $startMonthNum AND $endMonthNum";
        } else {
            $transactionQuery .= " AND (MONTH(ct.transactionDate) >= $startMonthNum OR MONTH(ct.transactionDate) <= $endMonthNum)";
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid season selected']);
        $mysqli->close();
        exit;
    }
}

$transactionQuery .= " ORDER BY ct.transactionDate";

$stmt = $mysqli->prepare($transactionQuery);
$stmt->bind_param('i', $produceId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response['data'][] = $row;
}
$stmt->close();

// Fetch category statistics with season filtering
$statsQuery = "
    SELECT c.catId, c.categoryName,
           SUM(CASE WHEN ct.transactionType = 'Receipt' THEN ct.ghsEquivalent ELSE 0 END) as totalIncome,
           SUM(CASE WHEN ct.transactionType = 'Payment' THEN ct.ghsEquivalent ELSE 0 END) as totalExpenditure
    FROM cashbook_transactions ct
    LEFT JOIN categories c ON ct.nominalAccount = c.catId
    WHERE ct.produce = ?
";

if ($seasonId && $season) {
    if ($startMonthNum <= $endMonthNum) {
        $statsQuery .= " AND MONTH(ct.transactionDate) BETWEEN $startMonthNum AND $endMonthNum";
    } else {
        $statsQuery .= " AND (MONTH(ct.transactionDate) >= $startMonthNum OR MONTH(ct.transactionDate) <= $endMonthNum)";
    }
}

$statsQuery .= " GROUP BY c.catId, c.categoryName";

$stmt = $mysqli->prepare($statsQuery);
$stmt->bind_param('i', $produceId);
$stmt->execute();
$statsResult = $stmt->get_result();

while ($row = $statsResult->fetch_assoc()) {
    $response['categoryStats'][] = [
        'catId' => $row['catId'],
        'categoryName' => $row['categoryName'],
        'totalIncome' => floatval($row['totalIncome']),
        'totalExpenditure' => floatval($row['totalExpenditure'])
    ];
}
$stmt->close();

// Fetch all categories for consistency
$categoriesQuery = "SELECT catId, categoryName FROM categories";
$categoriesResult = $mysqli->query($categoriesQuery);

if ($categoriesResult) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $response['categories'][] = $row;
    }
}

echo json_encode($response);
$mysqli->close();