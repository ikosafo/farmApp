<?php
include('../../config.php');
include('../../includes/functions.php');

// Database connection (assuming $mysqli is already defined)
global $mysqli;

// Query for key metrics
$totalQuery = $mysqli->query("SELECT COUNT(*) as count, SUM(ghsEquivalent) as total, AVG(ghsEquivalent) as avg FROM `cashbook_transactions` WHERE transactionType = 'Payment'");
$totalData = $totalQuery->fetch_assoc();
$totalCount = $totalData['count'];
$totalAmount = number_format($totalData['total'], 2);
$avgAmount = number_format($totalData['avg'], 2);

// Top nominal account
$topAccountQuery = $mysqli->query("SELECT c.categoryName, SUM(t.ghsEquivalent) as total FROM `cashbook_transactions` t JOIN categories c ON t.nominalAccount = c.catId WHERE t.transactionType = 'Payment' GROUP BY c.categoryName ORDER BY total DESC LIMIT 1");
$topAccount = $topAccountQuery->fetch_assoc();
$topAccountName = $topAccount ? $topAccount['categoryName'] : 'N/A';

// Top produce
$topProduceQuery = $mysqli->query("SELECT p.prodName, SUM(t.ghsEquivalent) as total FROM `cashbook_transactions` t JOIN producelist p ON t.produce = p.prodId WHERE t.transactionType = 'Payment' GROUP BY p.prodName ORDER BY total DESC LIMIT 1");
$topProduce = $topProduceQuery->fetch_assoc();
$topProduceName = $topProduce ? $topProduce['prodName'] : 'N/A';

// Unique produce count
$uniqueProduceQuery = $mysqli->query("SELECT COUNT(DISTINCT t.produce) as unique_count FROM `cashbook_transactions` t WHERE t.transactionType = 'Payment'");
$uniqueProduce = $uniqueProduceQuery->fetch_assoc();
$uniqueProduceCount = $uniqueProduce['unique_count'];

// Data for table (by nominal account and produce)
$tableQuery = $mysqli->query("SELECT c.categoryName, p.prodName, COUNT(*) as count, SUM(t.ghsEquivalent) as total FROM `cashbook_transactions` t JOIN categories c ON t.nominalAccount = c.catId JOIN producelist p ON t.produce = p.prodId WHERE t.transactionType = 'Payment' GROUP BY c.categoryName, p.prodName ORDER BY total DESC");
$tableData = [];
$rawPercentages = [];
$totalReceipts = $totalData['total'] ?: 1; // Avoid division by zero
while ($row = $tableQuery->fetch_assoc()) {
    $row['rawPercentage'] = ($row['total'] / $totalReceipts) * 100;
    $tableData[] = $row;
}

// Adjust percentages to sum to 100%
$roundedPercentages = [];
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
foreach ($tableData as $index => &$row) {
    $row['displayPercentage'] = $roundedPercentages[$index];
}

// Data for line chart (monthly receipts for past 12 months)
$lineChartQuery = $mysqli->query("SELECT DATE_FORMAT(transactionDate, '%Y-%m') as month, SUM(ghsEquivalent) as total FROM `cashbook_transactions` WHERE transactionType = 'Payment' AND transactionDate >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month");
$lineChartLabels = [];
$lineChartData = [];
while ($row = $lineChartQuery->fetch_assoc()) {
    $lineChartLabels[] = $row['month'];
    $lineChartData[] = (float)$row['total'];
}

// Data for pie chart (by nominal account)
$pieChartQuery = $mysqli->query("SELECT c.categoryName, SUM(t.ghsEquivalent) as total FROM `cashbook_transactions` t JOIN categories c ON t.nominalAccount = c.catId WHERE t.transactionType = 'Payment' GROUP BY c.categoryName");
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

<div class="statistics-container">
    <!-- Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave text-primary fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Total Receipts</h6>
                    <h4 class="text-primary">GHS <?php echo $totalAmount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3">
                <div class="card-body text-center">
                    <i class="fas fa-calculator text-success fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Average Receipt</h6>
                    <h4 class="text-success">GHS <?php echo $avgAmount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3">
                <div class="card-body text-center">
                    <i class="fas fa-receipt text-info fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Transactions</h6>
                    <h4 class="text-info"><?php echo $totalCount; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3">
                <div class="card-body text-center">
                    <i class="fas fa-chart-pie text-warning fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Top Account</h6>
                    <h4 class="text-warning"><?php echo $topAccountName; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3">
                <div class="card-body text-center">
                    <i class="fas fa-leaf text-success fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Top Produce</h6>
                    <h4 class="text-success"><?php echo $topProduceName; ?></h4>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="card shadow-sm border-radius-xl p-3">
                <div class="card-body text-center">
                    <i class="fas fa-seedling text-primary fa-2x mb-2"></i>
                    <h6 class="font-weight-bolder">Unique Produce</h6>
                    <h4 class="text-primary"><?php echo $uniqueProduceCount; ?></h4>
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

    <!-- Table -->
    <div class="card shadow-sm border-radius-xl p-4">
        <h5 class="font-weight-bolder mb-3">Receipts Summary</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nominal Account</th>
                        <th>Produce</th>
                        <th>Transactions</th>
                        <th>Total (GHS)</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tableData as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['categoryName']); ?></td>
                            <td><?php echo htmlspecialchars($row['prodName']); ?></td>
                            <td><?php echo $row['count']; ?></td>
                            <td>GHS <?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo number_format($row['displayPercentage'], 1); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .statistics-container .card {
        border: none;
        transition: transform 0.2s ease;
    }
    .statistics-container .card:hover {
        transform: translateY(-2px);
    }
    .statistics-container .table th, .statistics-container .table td {
        padding: 12px;
        vertical-align: middle;
    }
    .statistics-container .table thead th {
        background: #f8f9fa;
        color: #343a40;
        font-weight: 600;
    }
    @media (max-width: 767px) {
        .statistics-container .card {
            padding: 12px !important;
        }
        .statistics-container .table th, .statistics-container .table td {
            font-size: 0.85rem;
            padding: 8px;
        }
    }
</style>

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