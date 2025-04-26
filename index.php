<?php
include('./includes/sidebar.php');
include('config.php');

// Fetch key metrics
// Total revenue
$totalIncomeQuery = "SELECT SUM(transactionAmount) as totalIncome FROM transactions WHERE transactionType = 'Income' AND transStatus = 1";
$totalIncomeResult = mysqli_query($mysqli, $totalIncomeQuery);
$totalIncome = mysqli_fetch_assoc($totalIncomeResult)['totalIncome'] ?? 0.0;

// Total Expenses
$totalExpenseQuery = "SELECT SUM(transactionAmount) as totalExpense FROM transactions WHERE transactionType = 'Expenditure' AND transStatus = 1";
$totalExpenseResult = mysqli_query($mysqli, $totalExpenseQuery);
$totalExpense = mysqli_fetch_assoc($totalExpenseResult)['totalExpense'] ?? 0.0;

// Total Orders
$totalOrdersQuery = "SELECT COUNT(*) as totalOrders FROM orders WHERE orderStatus = 1";
$totalOrdersResult = mysqli_query($mysqli, $totalOrdersQuery);
$totalOrders = mysqli_fetch_assoc($totalOrdersResult)['totalOrders'] ?? 0;

// Total Products
$totalProductsQuery = "SELECT COUNT(prodQuantity) as totalProducts FROM `producelist` WHERE prodStatus = 1";
$totalProductsResult = mysqli_query($mysqli, $totalProductsQuery);
$totalProducts = mysqli_fetch_assoc($totalProductsResult)['totalProducts'] ?? 0;

// Total Categories
$totalCategoriesQuery = "SELECT COUNT(*) as totalCategories FROM `categories` WHERE categoryStatus = 1";
$totalCategoriesResult = mysqli_query($mysqli, $totalCategoriesQuery);
$totalCategories = mysqli_fetch_assoc($totalCategoriesResult)['totalCategories'] ?? 0;

// Recent Orders
$recentOrdersQuery = "SELECT `paymentStatus`,`customerName`, `totalAmount`, `orderStatus`,`deliveryDate` FROM `orders` ORDER BY `deliveryDate` DESC LIMIT 5";
$recentOrdersResult = mysqli_query($mysqli, $recentOrdersQuery);

// Recent Transactions
$recentTransactionsQuery = "SELECT transactionName, transactionAmount, transactionDate, transactionType FROM transactions ORDER BY transactionDate DESC LIMIT 5";
$recentTransactionsResult = mysqli_query($mysqli, $recentTransactionsQuery);

// Sales Distribution for Pie Chart (from orders.orderDetails)
$salesQuery = "SELECT orderDetails FROM orders WHERE orderStatus = 1";
$salesResult = mysqli_query($mysqli, $salesQuery);
$productQuantities = [];
while ($row = mysqli_fetch_assoc($salesResult)) {
    $orderDetails = json_decode($row['orderDetails'], true);
    if (is_array($orderDetails)) {
        foreach ($orderDetails as $product => $quantity) {
            if (isset($productQuantities[$product])) {
                $productQuantities[$product] += $quantity;
            } else {
                $productQuantities[$product] = $quantity;
            }
        }
    }
}
// Sort by quantity descending and limit to top 6 for chart readability
arsort($productQuantities);
$topProducts = array_slice($productQuantities, 0, 6, true);
$productNames = array_keys($topProducts);
$quantities = array_values($topProducts);

// Revenue vs Expenditure for Line Chart (last 6 months)
$monthsQuery = "SELECT DATE_FORMAT(transactionDate, '%b %Y') as month, 
                SUM(CASE WHEN transactionType = 'Income' THEN transactionAmount ELSE 0 END) as income,
                SUM(CASE WHEN transactionType = 'Expenditure' THEN transactionAmount ELSE 0 END) as expenditure
                FROM transactions 
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
        canvas {
            height: 350px !important;
        }
    </style>
    <div class="container-fluid py-4">
        <!-- Key Metrics -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize" style="font-size:11px !important;">Total Revenue</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalIncome, 2, '.', ','); ?>
                                        <span style="font-size:10px;"> GHC </span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-capitalize" style="font-size:11px !important;">Total Expenditure</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalExpense, 2, '.', ','); ?>
                                        <span style="font-size:10px;"> GHC </span>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-capitalize" style="font-size:11px !important;">Completed Supplies</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalOrders, 0); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-capitalize" style="font-size:11px !important;">Categories</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php echo number_format($totalCategories, 0); ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-shop text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="row mt-4">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="card z-index-2">
                    <div class="card-header pb-0">
                        <h6>Revenue vs Expenditure</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-income-expense" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">


                    <div class="card-header pb-0">
                        <h6>Recent Supplies / Deliveries</h6>
                        <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
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

        <!-- Transactions and Sales Distribution -->
        <div class="row mt-4">
            <div class="col-lg-4 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Recent Transactions</h6>
                        <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold">Latest transactions</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            <?php while ($transaction = mysqli_fetch_assoc($recentTransactionsResult)) { ?>
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="ni <?php echo $transaction['transactionType'] == 'Expenditure' ? 'ni-money-coins text-danger' : 'ni-money-coins text-success'; ?> text-gradient"></i>
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
                        <h6>Sales Distribution</h6>
                        <p class="text-sm">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">Quantity distribution of sold products</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-sales-distribution" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include('includes/footer.php') ?>

<!-- Chart.js for visualizations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue vs Expenditure Chart
const ctxIncomeExpense = document.getElementById('chart-income-expense').getContext('2d');
new Chart(ctxIncomeExpense, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($monthLabels); ?>,
        datasets: [{
            label: 'Revenue',
            data: <?php echo json_encode($incomeData); ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        }, {
            label: 'Expenditure',
            data: <?php echo json_encode($expenditureData); ?>,
            borderColor: 'rgba(255, 99, 132, 1)',
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Sales Distribution Chart
const ctxSalesDistribution = document.getElementById('chart-sales-distribution').getContext('2d');
new Chart(ctxSalesDistribution, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($productNames); ?>,
        datasets: [{
            data: <?php echo json_encode($quantities); ?>,
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true
    }
});
</script>