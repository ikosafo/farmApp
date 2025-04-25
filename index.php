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

// Recent Orders
$recentOrdersQuery = "SELECT customerName, customerPhone, totalAmount, createdAt, orderStatus FROM orders ORDER BY createdAt DESC LIMIT 5";
$recentOrdersResult = mysqli_query($mysqli, $recentOrdersQuery);

// Recent Transactions
$recentTransactionsQuery = "SELECT transactionName, transactionAmount, transactionDate, transactionType FROM transactions ORDER BY transactionDate DESC LIMIT 5";
$recentTransactionsResult = mysqli_query($mysqli, $recentTransactionsQuery);

// Product Names and Quantities for Pie Chart
$productsQuery = "SELECT prodName, prodQuantity FROM producelist WHERE prodStatus = 1 LIMIT 6"; // Limit to 6 for better chart readability
$productsResult = mysqli_query($mysqli, $productsQuery);
$productNames = [];
$quantities = [];
while ($row = mysqli_fetch_assoc($productsResult)) {
    $productNames[] = $row['prodName'];
    $quantities[] = $row['prodQuantity'];
}
?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <!-- Header -->
    <?php include('./includes/header.php') ?>
    <!-- End Header -->

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
                                        <?php echo number_format($totalProducts, 0); ?>
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
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($order['customerName']); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold"><?php echo htmlspecialchars($order['customerPhone']); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold"><?php echo number_format($order['totalAmount'], 2); ?> GHC</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold"><?php echo date('d M Y', strtotime($order['createdAt'])); ?></span>
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

        <!-- Transactions and Product Categories -->
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
                        <h6>Product Distribution</h6>
                        <p class="text-sm">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">Quantity distribution of products</span>
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-product-categories" class="chart-canvas" height="300"></canvas>
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
// Income vs Expenses Chart
const ctxIncomeExpense = document.getElementById('chart-income-expense').getContext('2d');
new Chart(ctxIncomeExpense, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Revenue',
            data: [12000, 19000, 15000, 22000, 18000, 25000],
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false
        }, {
            label: 'Expenditure',
            data: [8000, 10000, 12000, 9000, 11000, 13000],
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

// Product Distribution Chart
const ctxProductCategories = document.getElementById('chart-product-categories').getContext('2d');
new Chart(ctxProductCategories, {
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