<?php
include('../../config.php');
include('../../includes/functions.php');

// Database connection
global $mysqli;

// Log database connection status
if ($mysqli->connect_error) {
    error_log("Database connection failed: " . $mysqli->connect_error);
    die("Database connection failed. Please check the error logs for details.");
}

// Key Metrics
$keyMetricsQuery = $mysqli->query("SELECT COUNT(*) as count, SUM(COALESCE(ghsEquivalent, 0)) as total, AVG(COALESCE(ghsEquivalent, 0)) as avg FROM `cashbook_transactions` WHERE transactionType = 'Receipt' AND transStatus = 1");
if (!$keyMetricsQuery) {
    error_log("Key metrics query failed: " . $mysqli->error);
    $keyMetrics = ['count' => 0, 'total' => 0, 'avg' => 0];
} else {
    $keyMetrics = $keyMetricsQuery->fetch_assoc() ?: ['count' => 0, 'total' => 0, 'avg' => 0];
}
$totalCount = $keyMetrics['count'];
$totalAmount = number_format($keyMetrics['total'] ?: 0, 2);
$avgAmount = number_format($keyMetrics['avg'] ?: 0, 2);
$totalReceipts = $keyMetrics['total'] ?: 1;

// Top Nominal Account
$topAccountQuery = $mysqli->query("SELECT COALESCE(nominalAccount, 'N/A') as categoryName, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Receipt' AND transStatus = 1 GROUP BY nominalAccount ORDER BY total DESC LIMIT 1");
$topAccount = $topAccountQuery ? $topAccountQuery->fetch_assoc() : ['categoryName' => 'N/A'];
$topAccountName = $topAccount['categoryName'];
if (!$topAccountQuery) error_log("Top account query failed: " . $mysqli->error);

// Top Payee
$topPayeeQuery = $mysqli->query("SELECT COALESCE(payeePayer, 'N/A') as payee, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Receipt' AND transStatus = 1 GROUP BY payeePayer ORDER BY total DESC LIMIT 1");
$topPayee = $topPayeeQuery ? $topPayeeQuery->fetch_assoc() : ['payee' => 'N/A'];
$topPayeeName = $topPayee['payee'];
if (!$topPayeeQuery) error_log("Top payee query failed: " . $mysqli->error);

// Top Produce
$topProduceQuery = $mysqli->query("SELECT COALESCE(produce, 'N/A') as produceName, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Receipt' AND transStatus = 1 AND produce IS NOT NULL AND produce != '' GROUP BY produce ORDER BY total DESC LIMIT 1");
$topProduce = $topProduceQuery ? $topProduceQuery->fetch_assoc() : ['produceName' => 'N/A'];
$topProduceName = $topProduce['produceName'];
if (!$topProduceQuery) error_log("Top produce query failed: " . $mysqli->error);

// Receipts Summary Table
$tableQuery = $mysqli->query("SELECT COALESCE(nominalAccount, 'N/A') as categoryName, COUNT(*) as count, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Receipt' AND transStatus = 1 GROUP BY nominalAccount ORDER BY total DESC");
$tableData = [];
if ($tableQuery) {
    while ($row = $tableQuery->fetch_assoc()) {
        $row['rawPercentage'] = ($row['total'] / $totalReceipts) * 100;
        $tableData[] = $row;
    }
} else {
    error_log("Table query failed: " . $mysqli->error);
}

// Percentage calculation
$roundedPercentages = [];
if (!empty($tableData)) {
    if (count($tableData) === 1) {
        $roundedPercentages[] = 100;
    } else {
        $sumRounded = 0;
        foreach ($tableData as $row) {
            $rounded = round($row['rawPercentage'], 1);
            $roundedPercentages[] = $rounded;
            $sumRounded += $rounded;
        }
        $adjustment = 100 - $sumRounded;
        if ($adjustment != 0 && !empty($roundedPercentages)) {
            $maxIndex = array_search(max($roundedPercentages), $roundedPercentages);
            $roundedPercentages[$maxIndex] += $adjustment;
        }
    }
}
foreach ($tableData as $index => &$row) {
    $row['displayPercentage'] = $roundedPercentages[$index] ?? 0;
}
unset($row);

// Pie Chart Data
$lineChartQuery = $mysqli->query("SELECT DATE_FORMAT(transactionDate, '%Y-%m') as month, SUM(ghsEquivalent) as total FROM `cashbook_transactions` WHERE transactionType = 'Receipt' AND transStatus = 1 AND transactionDate >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month");
$lineChartLabels = [];
$lineChartData = [];
while ($row = $lineChartQuery->fetch_assoc()) {
    $lineChartLabels[] = $row['month'];
    $lineChartData[] = (float)$row['total'];
}

// Data for pie chart (by nominal account)
$pieChartQuery = $mysqli->query("SELECT c.categoryName, SUM(t.ghsEquivalent) as total FROM `cashbook_transactions` t JOIN categories c ON t.nominalAccount = c.catId WHERE t.transactionType = 'Receipt' AND t.transStatus = 1 GROUP BY c.categoryName");
$pieChartLabels = [];
$pieChartData = [];
$pieChartColors = [];
$colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEEAD', '#D4A5A5', '#9B59B6', '#3498DB'];
$colorIndex = 0;
while ($row = $pieChartQuery->fetch_assoc()) {
    $pieChartLabels[] = $row['categoryName'];
    $pieChartData[] = (float)$row['total'];
    $pieChartColors[] = $colors[$colorIndex % count($colors)];
    $colorIndex++;
}
?>


    <style>
        .statistics-container .card {
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .statistics-container .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
        .statistics-container .table th, .statistics-container .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .statistics-container .table thead th {
            background: #f8f9fa;
            color: #343a40;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .border-radius-xl {
            border-radius: 0.75rem;
        }
        .card h4 {
            word-wrap: break-word;
        }
        #receiptsLineChart, #receiptsPieChart {
            min-height: 300px;
            width: 100%;
        }
        @media (max-width: 767px) {
            .statistics-container .card {
                padding: 12px !important;
            }
            .statistics-container .table th, .statistics-container .table td {
                font-size: 0.85rem;
                padding: 8px;
            }
            h4 {
                font-size: 1.1rem;
            }
        }
    </style>

<div class="statistics-container">
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-money-bill-wave text-primary fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Total Receipts</h6>
                    <h4 class="text-primary mb-0">GHS <?php echo $totalAmount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-calculator text-success fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Average Receipt</h6>
                    <h4 class="text-success mb-0">GHS <?php echo $avgAmount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-receipt text-info fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Transactions</h6>
                    <h4 class="text-info mb-0"><?php echo $totalCount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-chart-pie text-warning fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Top Account</h6>
                    <h4 class="text-warning mb-0"><?php echo htmlspecialchars(categoryName($topAccountName)); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-user-tie text-primary fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Top Payee</h6>
                    <h4 class="text-primary mb-0"><?php echo htmlspecialchars($topPayeeName); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="fas fa-leaf text-success fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Top Produce</h6>
                    <h4 class="text-success mb-0"><?php echo htmlspecialchars(produceName($topProduceName)); ?></h4>
                </div>
            </div>
        </div>
    </div>

     <!-- Graphs -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-radius-xl p-4">
                <h5 class="font-weight-bolder mb-3">Receipts Over Time</h5>
                <canvas id="receiptsLineChart"></canvas>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-radius-xl p-4">
                <h5 class="font-weight-bolder mb-3">Receipts by Nominal Account</h5>
                <canvas id="receiptsPieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-radius-xl p-4">
                <h5 class="font-weight-bolder mb-3">Receipts Summary</h5>
                <div class="table-responsive">
                    <?php if (empty($tableData)): ?>
                        <p class="text-center text-muted">No receipt data available.</p>
                    <?php else: ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nominal Account</th>
                                    <th>Transactions</th>
                                    <th>Total (GHS)</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableData as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(categoryName($row['categoryName'])); ?></td>
                                        <td><?php echo (int)$row['count']; ?></td>
                                        <td>GHS <?php echo number_format($row['total'], 2); ?></td>
                                        <td><?php echo number_format($row['displayPercentage'] ?? 0, 1); ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Line Chart
const lineChartCtx = document.getElementById('receiptsLineChart').getContext('2d');
new Chart(lineChartCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($lineChartLabels); ?>,
        datasets: [{
            label: 'Receipts (GHS)',
            data: <?php echo json_encode($lineChartData); ?>,
            borderColor: '#1a2a44',
            backgroundColor: 'rgba(26, 42, 68, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Amount (GHS)' } },
            x: { title: { display: true, text: 'Month' } }
        },
        plugins: { legend: { display: true } }
    }
});

// Pie Chart
const pieChartCtx = document.getElementById('receiptsPieChart').getContext('2d');
new Chart(pieChartCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($pieChartLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($pieChartData); ?>,
            backgroundColor: <?php echo json_encode($pieChartColors); ?>
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        let value = context.raw || 0;
                        let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: GHS ${value.toLocaleString()} (${percentage}%)`;
                    }
                }
            }
        }
    }
});
</script>