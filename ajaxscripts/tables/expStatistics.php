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
$keyMetricsQuery = $mysqli->query("SELECT COUNT(*) as count, SUM(COALESCE(ghsEquivalent, 0)) as total, AVG(COALESCE(ghsEquivalent, 0)) as avg FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transStatus = 1");
if (!$keyMetricsQuery) {
    error_log("Key metrics query failed: " . $mysqli->error);
    $keyMetrics = ['count' => 0, 'total' => 0, 'avg' => 0];
} else {
    $keyMetrics = $keyMetricsQuery->fetch_assoc() ?: ['count' => 0, 'total' => 0, 'avg' => 0];
}
$totalCount = $keyMetrics['count'];
$totalAmount = number_format($keyMetrics['total'] ?: 0, 2);
$avgAmount = number_format($keyMetrics['avg'] ?: 0, 2);
$totalPayments = $keyMetrics['total'] ?: 1;

// Top Nominal Account
$topAccountQuery = $mysqli->query("SELECT COALESCE(nominalAccount, 'N/A') as categoryName, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transStatus = 1 GROUP BY nominalAccount ORDER BY total DESC LIMIT 1");
$topAccount = $topAccountQuery ? $topAccountQuery->fetch_assoc() : ['categoryName' => 'N/A'];
$topAccountName = $topAccount['categoryName'];
if (!$topAccountQuery) error_log("Top account query failed: " . $mysqli->error);

// Top Payee
$topPayeeQuery = $mysqli->query("SELECT COALESCE(payeePayer, 'N/A') as payee, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transStatus = 1 GROUP BY payeePayer ORDER BY total DESC LIMIT 1");
$topPayee = $topPayeeQuery ? $topPayeeQuery->fetch_assoc() : ['payee' => 'N/A'];
$topPayeeName = $topPayee['payee'];
if (!$topPayeeQuery) error_log("Top payee query failed: " . $mysqli->error);

// Top Produce
$topProduceQuery = $mysqli->query("SELECT COALESCE(produce, 'N/A') as produceName, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transStatus = 1 AND produce IS NOT NULL AND produce != '' GROUP BY produce ORDER BY total DESC LIMIT 1");
$topProduce = $topProduceQuery ? $topProduceQuery->fetch_assoc() : ['produceName' => 'N/A'];
$topProduceName = $topProduce['produceName'];
if (!$topProduceQuery) error_log("Top produce query failed: " . $mysqli->error);

// Payments Summary Table
$tableQuery = $mysqli->query("SELECT COALESCE(nominalAccount, 'N/A') as categoryName, COUNT(*) as count, SUM(COALESCE(ghsEquivalent, 0)) as total FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transStatus = 1 GROUP BY nominalAccount ORDER BY total DESC");
$tableData = [];
if ($tableQuery) {
    while ($row = $tableQuery->fetch_assoc()) {
        $row['rawPercentage'] = ($row['total'] / $totalPayments) * 100;
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

// Payments Over Time Chart Data
$lineChartQuery = $mysqli->query("SELECT DATE_FORMAT(transactionDate, '%b-%Y') as month_label, DATE_FORMAT(transactionDate, '%Y-%m') as month_sort_key, SUM(ghsEquivalent) as total FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transStatus = 1 AND transactionDate >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month_label, month_sort_key ORDER BY month_sort_key DESC");
$lineChartLabels = [];
$lineChartData = [];
while ($row = $lineChartQuery->fetch_assoc()) {
    $lineChartLabels[] = $row['month_label'];
    $lineChartData[] = (float)$row['total'];
}

// Data for pie chart (by nominal account)
$pieChartQuery = $mysqli->query("SELECT c.categoryName, SUM(t.ghsEquivalent) as total FROM `cashbook_transactions` t JOIN categories c ON t.nominalAccount = c.catId WHERE t.transactionType = 'Payment' AND t.transStatus = 1 GROUP BY c.categoryName");
$pieChartLabels = [];
$pieChartData = [];
$pieChartColors = [];
$colors = ['#5E81AC', '#88C0D0', '#A3BE8C', '#D08770', '#BF616A', '#EBCB8B', '#B48EAD', '#81A1C1'];
$colorIndex = 0;
while ($row = $pieChartQuery->fetch_assoc()) {
    $pieChartLabels[] = $row['categoryName'];
    $pieChartData[] = (float)$row['total'];
    $pieChartColors[] = $colors[$colorIndex % count($colors)];
    $colorIndex++;
}
?>

<style>
    .statistics-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.6;
        --primary-color: #5E81AC;
        --secondary-color: #4C566A;
        --accent-color: #88C0D0;
        --background-color: #ECEFF4;
        --card-bg: #FFFFFF;
        --text-color: #2E3440;
        --muted-text: #4C566A;
    }

    .statistics-container .card {
        background: var(--card-bg);
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: visible;
        position: relative;
    }

    .statistics-container .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .statistics-container .card-header {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        color: #FFFFFF;
        padding: 0.75rem;
        border-radius: 0.75rem 0.75rem 0 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .statistics-container .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: visible;
    }

    .statistics-container .card-body i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary-color);
        transition: transform 0.3s ease;
    }

    .statistics-container .card:hover .card-body i {
        transform: scale(1.1);
    }

    .statistics-container .card h6 {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--muted-text);
        margin-bottom: 0.4rem;
    }

    .statistics-container .card h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-color);
        margin: 0;
        word-wrap: break-word;
    }

    .statistics-container .table-responsive {
        border-radius: 0.75rem;
        overflow: hidden;
        width: 100%;
    }

    .statistics-container .table {
        margin-bottom: 0;
        background: var(--card-bg);
        width: 100%;
        table-layout: auto;
    }

    .statistics-container .table thead th {
        background: var(--primary-color);
        color: #FFFFFF;
        font-weight: 500;
        border: none;
        padding: 0.6rem;
        font-size: 0.85rem;
    }

    .statistics-container .table tbody tr {
        transition: background 0.2s ease;
    }

    .statistics-container .table tbody tr:hover {
        background: rgba(94, 129, 172, 0.05);
    }

    .statistics-container .table td,
    .statistics-container .table th {
        padding: 0.6rem;
        vertical-align: middle;
        color: var(--text-color);
        border-color: rgba(0, 0, 0, 0.05);
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .statistics-container .table td:first-child,
    .statistics-container .table th:first-child {
        white-space: normal;
        min-width: 150px;
    }

    .statistics-container .chart-wrapper {
        position: relative;
        width: 100%;
        max-width: 100%;
        overflow: visible;
    }

    .statistics-container .canvas {
        min-height: 250px;
        max-width: 100%;
        width: 100%;
        position: relative;
        z-index: 10;
    }

    .statistics-container .search-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        box-sizing: border-box;
    }

    @media (max-width: 767px) {
        .statistics-container .card {
            padding: 0.75rem;
        }
        .statistics-container .card h4 {
            font-size: 1rem;
        }
        .statistics-container .card h6 {
            font-size: 0.7rem;
        }
        .statistics-container .card-body {
            padding: 1rem;
        }
        .statistics-container .table td,
        .statistics-container .table th {
            font-size: 0.75rem;
            padding: 0.5rem;
        }
        .statistics-container .table td:first-child,
        .statistics-container .table th:first-child {
            min-width: 100px;
        }
    }
</style>

<div class="statistics-container">
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-money-bill-wave"></i>
                    <h6>Total Payments</h6>
                    <h4>GHS <?php echo $totalAmount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-calculator"></i>
                    <h6>Average Payment</h6>
                    <h4>GHS <?php echo $avgAmount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-receipt"></i>
                    <h6>Transactions</h6>
                    <h4><?php echo $totalCount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-chart-pie"></i>
                    <h6>Top Account</h6>
                    <h4><?php echo htmlspecialchars(categoryName($topAccountName)); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-user-tie"></i>
                    <h6>Top Payee</h6>
                    <h4><?php echo htmlspecialchars($topPayeeName); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-leaf"></i>
                    <h6>Top Produce</h6>
                    <h4><?php echo htmlspecialchars(produceName($topProduceName)); ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Payments Over Time</div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="paymentsLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Payments by Nominal Account</div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="paymentsPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="">
                <!-- <div class="card-header">Payments Summary</div> -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <?php if (empty($tableData)): ?>
                            <p class="text-center text-muted m-3">No payment data available.</p>
                        <?php else: ?>
                            <table class="table table-hover mb-0" id="paymentsSummaryTable">
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
                                            <td><?php echo htmlspecialchars(categoryName($row['categoryName']) ?? '-'); ?></td>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Line Chart (Bar Graph)
const lineChartCtx = document.getElementById('paymentsLineChart').getContext('2d');
new Chart(lineChartCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($lineChartLabels); ?>,
        datasets: [{
            label: 'Payments (GHS)',
            data: <?php echo json_encode($lineChartData); ?>,
            backgroundColor: 'rgba(94, 129, 172, 0.8)',
            borderColor: 'rgba(94, 129, 172, 1)',
            borderWidth: 1,
            barPercentage: 0.8,
            categoryPercentage: 0.9
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Amount (GHS)', color: '#2E3440', font: { size: 12 } },
                grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
                title: { display: true, text: 'Month', color: '#2E3440', font: { size: 12 } },
                grid: { display: false }
            }
        },
        plugins: {
            legend: { labels: { color: '#2E3440', font: { size: 12 } } },
            tooltip: {
                enabled: true,
                backgroundColor: '#FFFFFF',
                titleColor: '#2E3440',
                bodyColor: '#2E3440',
                borderColor: '#5E81AC',
                borderWidth: 1,
                mode: 'index',
                intersect: false,
                position: 'nearest',
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        let value = context.raw || 0;
                        return `${label}: GHS ${value.toLocaleString()}`;
                    }
                }
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        hover: {
            mode: 'index',
            intersect: false
        }
    }
});

// Pie Chart
const pieChartCtx = document.getElementById('paymentsPieChart').getContext('2d');
new Chart(pieChartCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($pieChartLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($pieChartData); ?>,
            backgroundColor: <?php echo json_encode($pieChartColors); ?>,
            borderColor: '#FFFFFF',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: { color: '#2E3440', font: { size: 12 } }
            },
            tooltip: {
                backgroundColor: '#FFFFFF',
                titleColor: '#2E3440',
                bodyColor: '#2E3440',
                borderColor: '#5E81AC',
                borderWidth: 1,
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
        },
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        }
    }
});
</script>