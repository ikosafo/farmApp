<?php
include('./includes/sidebar.php');
include('config.php');

// Fetch key metrics
$totalIncomeQuery = "SELECT SUM(ghsEquivalent) as totalIncome FROM cashbook_transactions WHERE transactionType = 'Receipt' AND transStatus = 1";
$totalIncomeResult = mysqli_query($mysqli, $totalIncomeQuery);
$totalIncome = mysqli_fetch_assoc($totalIncomeResult)['totalIncome'] ?? 0.0;

$totalExpenseQuery = "SELECT SUM(ghsEquivalent) as totalExpense FROM cashbook_transactions WHERE transactionType = 'Payment' AND transStatus = 1";
$totalExpenseResult = mysqli_query($mysqli, $totalExpenseQuery);
$totalExpense = mysqli_fetch_assoc($totalExpenseResult)['totalExpense'] ?? 0.0;

$totalOrdersQuery = "SELECT COUNT(*) as totalOrders FROM orders WHERE orderStatus = 1";
$totalOrdersResult = mysqli_query($mysqli, $totalOrdersQuery);
$totalOrders = mysqli_fetch_assoc($totalOrdersResult)['totalOrders'] ?? 0;

$totalProductsQuery = "SELECT COUNT(prodQuantity) as totalProducts FROM `producelist` WHERE prodStatus = 1";
$totalProductsResult = mysqli_query($mysqli, $totalProductsQuery);
$totalProducts = mysqli_fetch_assoc($totalProductsResult)['totalProducts'] ?? 0;

$totalCategoriesQuery = "SELECT COUNT(*) as totalCategories FROM `categories` WHERE categoryStatus = 1";
$totalCategoriesResult = mysqli_query($mysqli, $totalCategoriesQuery);
$totalCategories = mysqli_fetch_assoc($totalCategoriesResult)['totalCategories'] ?? 0;

$recentOrdersQuery = "SELECT `paymentStatus`, `customerName`, `totalAmount`, `orderStatus`, `deliveryDate` FROM `orders` WHERE `orderStatus` = 1 ORDER BY `deliveryDate` DESC LIMIT 5";
$recentOrdersResult = mysqli_query($mysqli, $recentOrdersQuery);

$recentTransactionsQuery = "SELECT payeePayer AS transactionName, ghsEquivalent AS transactionAmount, transactionDate, transactionType FROM cashbook_transactions WHERE transStatus = 1 ORDER BY transactionDate DESC LIMIT 5";
$recentTransactionsResult = mysqli_query($mysqli, $recentTransactionsQuery);

$categoryQuery = "SELECT transactionType as category, SUM(ghsEquivalent) as totalAmount
                  FROM cashbook_transactions
                  WHERE transStatus = 1
                  GROUP BY transactionType
                  ORDER BY totalAmount DESC
                  LIMIT 6";
$categoryResult = mysqli_query($mysqli, $categoryQuery);
$categoryNames = [];
$categoryAmounts = [];
while ($row = mysqli_fetch_assoc($categoryResult)) {
    $categoryNames[] = $row['category'];
    $categoryAmounts[] = $row['totalAmount'];
}

$monthsQuery = "SELECT DATE_FORMAT(transactionDate, '%b %Y') as month,
                SUM(CASE WHEN transactionType = 'Receipt' THEN ghsEquivalent ELSE 0 END) as income,
                SUM(CASE WHEN transactionType = 'Payment' THEN ghsEquivalent ELSE 0 END) as expenditure
                FROM cashbook_transactions
                WHERE transStatus = 1
                AND transactionDate >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(transactionDate, '%b %Y'), DATE_FORMAT(transactionDate, '%Y-%m')
                ORDER BY DATE_FORMAT(transactionDate, '%Y-%m') ASC";
$monthsResult = mysqli_query($mysqli, $monthsQuery);
$monthLabels = [];
$incomeData = [];
$expenditureData = [];
while ($row = mysqli_fetch_assoc($monthsResult)) {
    $monthLabels[] = $row['month'];
    $incomeData[] = $row['income'] ?? 0.0;
    $expenditureData[] = $row['expenditure'] ?? 0.0;
}
?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <?php include('./includes/header.php') ?>

    <style>
        /* Ensure Poppins font */
        body, .card, .numbers, .table, .timeline-content, h6, p, span {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Card Styling */
        .card {
            background: #ffffff;
            border: 1px solid #d4e4c3;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-body {
            padding: 1.5rem;
        }

        .numbers p {
            color: #4a7043;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        /* Adjusted font size for numbers to prevent overlapping */
        .numbers h5 {
            color: #2d6a4f;
            font-size: 1.2rem; /* Adjusted for smaller size */
            font-weight: 700;
            white-space: nowrap; /* Prevent text wrapping */
            overflow: hidden; /* Hide overflow */
            text-overflow: ellipsis; /* Add ellipsis for overflow */
        }

        .numbers h5 span {
            font-size: 0.7rem; /* Adjusted for smaller size */
            font-weight: 400;
            color: #40916c;
        }

        /* Icon Styling */
        .statistics-div .icon-shape {
            width: 40px;
            height: 40px;
            background: linear-gradient(90deg, #2d6a4f, #40916c);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .feather-icon {
            width: 20px !important;
            height: 20px !important;
            color: #ffffff !important;
        }

        /* Table Styling */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            color: #4a7043;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            background: #f0f4f3;
            padding: 0.75rem;
        }

        .table td {
            color: #1f2937;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.75rem;
        }

        .table tbody tr:hover {
            background: #e1e8d8;
        }

        /* Timeline Styling */
        .timeline-block .timeline-step {
            background: linear-gradient(90deg, #2d6a4f, #40916c);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-content h6 {
            color: #2d6a4f;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .timeline-content p {
            color: #4a7043;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Chart Styling - Adjusted for responsiveness */
        .chart {
            position: relative; /* Added for correct sizing */
            height: 300px; /* Default height */
        }

        .chart-canvas {
            width: 100% !important; /* Ensure canvas takes full width */
            height: 100% !important; /* Ensure canvas takes full height */
        }


        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .main-content {
                margin-top: 4rem; /* Increased to ensure content is below header */
            }

            .card {
                margin-bottom: 1.5rem;
            }

            .numbers h5 {
                font-size: 1rem; /* Further adjustment for small screens */
            }

            .numbers p {
                font-size: 0.75rem;
            }

            .icon-shape {
                width: 32px;
                height: 32px;
            }

            .feather-icon {
                width: 16px !important;
                height: 16px !important;
            }

            .table th, .table td {
                font-size: 0.75rem;
                padding: 0.5rem;
            }

            .timeline-block .timeline-step {
                width: 24px;
                height: 24px;
            }

            .timeline-content h6 {
                font-size: 0.85rem;
            }

            .timeline-content p {
                font-size: 0.65rem;
            }
            .chart {
                height: 250px; /* Smaller height on mobile */
            }
        }

        @media (max-width: 576px) {
            .main-content {
                margin-top: 3.5rem;
            }

            .numbers h5 {
                font-size: 0.9rem; 
            }
            .numbers h5 span {
                font-size: 0.6rem;
            }

            .icon-shape {
                width: 28px;
                height: 28px;
            }
            .chart {
                height: 200px; 
            }
        }
    </style>

    <div class="container-fluid statistics-div py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize">Total Receipt</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalIncome, 2, '.', ','); ?>
                                        <span>GHC</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i data-feather="dollar-sign" class="feather-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize">Total Payments</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalExpense, 2, '.', ','); ?>
                                        <span>GHC</span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                    <i data-feather="credit-card" class="feather-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize">Deliveries/Supp</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalOrders, 0); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i data-feather="truck" class="feather-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize">Categories</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalCategories, 0); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i data-feather="grid" class="feather-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2">
                    <div class="card-header pb-0">
                        <h6>Receipt vs Payment</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-income-expense" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>Recent Supplies / Deliveries</h6>
                        <p class="text-sm">
                            <i data-feather="arrow-up" class="feather-icon text-success"></i>
                            <span class="font-weight-bold">Latest supplies</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = mysqli_fetch_assoc($recentOrdersResult)) { ?>
                                        <tr>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold"><?php echo date('d M Y', strtotime($order['deliveryDate'])); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($order['customerName']); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold"><?php echo number_format($order['totalAmount'], 2); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold"><?php echo htmlspecialchars($order['paymentStatus']); ?></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-4 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Recent Transactions</h6>
                        <p class="text-sm">
                            <i data-feather="arrow-up" class="feather-icon text-success"></i>
                            <span class="font-weight-bold">Latest transactions</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            <?php while ($transaction = mysqli_fetch_assoc($recentTransactionsResult)) { ?>
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i data-feather="<?php echo $transaction['transactionType'] == 'Payment' ? 'credit-card' : 'dollar-sign'; ?>" class="feather-icon"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($transaction['transactionName']); ?></h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            <?php echo number_format($transaction['transactionAmount'], 2); ?> GHC - <?php echo date('d M Y', strtotime($transaction['transactionDate'])); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card z-index-2">
                    <div class="card-header pb-0">
                        <h6>Transactional Distribution</h6>
                        <p class="text-sm">
                            <i data-feather="arrow-up" class="feather-icon text-success"></i>
                            <span class="font-weight-bold">Amount distribution by transaction category</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-category-distribution" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Receipt vs Payment Chart (Bar Graph)
const ctxIncomeExpense = document.getElementById('chart-income-expense').getContext('2d');
new Chart(ctxIncomeExpense, {
    type: 'bar', // Changed to bar chart
    data: {
        labels: <?php echo json_encode($monthLabels); ?>,
        datasets: [{
            label: 'Receipt',
            data: <?php echo json_encode($incomeData); ?>,
            backgroundColor: '#2d6a4f', // Green color
            borderColor: '#2d6a4f',
            borderWidth: 1
        }, {
            label: 'Payment',
            data: <?php echo json_encode($expenditureData); ?>,
            backgroundColor: '#e74c3c', // Red color
            borderColor: '#e74c3c',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Allow charts to adapt to container size
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#d4e4c3' },
                title: { display: true, text: 'Amount (GHC)', font: { family: 'Poppins', size: 12 } }
            },
            x: {
                grid: { color: '#d4e4c3' },
                title: { display: true, text: 'Month', font: { family: 'Poppins', size: 12 } }
            }
        },
        plugins: {
            legend: { labels: { font: { family: 'Poppins', size: 12 } } }
        }
    }
});

// Category Distribution Chart (Pie Graph with Green/Red Colors)
const ctxCategoryDistribution = document.getElementById('chart-category-distribution').getContext('2d');
new Chart(ctxCategoryDistribution, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($categoryNames); ?>,
        datasets: [{
            data: <?php echo json_encode($categoryAmounts); ?>,
            backgroundColor: [
                '#2d6a4f', // Dark Green
                '#e74c3c', // Red
                '#40916c', // Medium Green
                '#c0392b', // Darker Red
                '#6fa87f', // Light Green
                '#f39c12'  // A neutral accent color (orange) if needed for more categories
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Allow charts to adapt to container size
        plugins: {
            legend: {
                position: 'top',
                labels: { font: { family: 'Poppins', size: 12 } }
            },
            tooltip: { // Added to ensure tooltips are readable
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed !== null) {
                            label += new Intl.NumberFormat('en-GH', { style: 'currency', currency: 'GHS' }).format(context.parsed);
                        }
                        return label;
                    }
                }
            }
        }
    }
});
</script>