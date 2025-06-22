<?php
include('../../config.php');
include('../../includes/functions.php');

// Validate POST data
$reportCategory = isset($_POST['reportCategory']) ? mysqli_real_escape_string($mysqli, $_POST['reportCategory']) : null;
$reportStartDate = isset($_POST['reportStartDate']) ? mysqli_real_escape_string($mysqli, $_POST['reportStartDate']) : null;
$reportEndDate = isset($_POST['reportEndDate']) ? mysqli_real_escape_string($mysqli, $_POST['reportEndDate']) : null;

// Check if required fields are provided
if (empty($reportCategory) || empty($reportStartDate) || empty($reportEndDate)) {
    echo '<div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom: 30px;">';
    echo '<span style="text-align:center; color:red;">Error: Please select a category and date range.</span>';
    echo '</div>';
    exit;
}

// Determine query based on report category
if ($reportCategory == 'Trial Balance') {
    $getResults = $mysqli->query("
        SELECT 
            transactionType,
            nominalAccount,
            DATE_FORMAT(transactionDate, '%Y-%m') AS monthYear, 
            SUM(ghsEquivalent) AS totalAmount 
        FROM 
            cashbook_transactions 
        WHERE 
            transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
            AND transactionType IN ('Receipt', 'Payment')
        GROUP BY 
            transactionType, nominalAccount, monthYear
        ORDER BY 
            transactionType, monthYear ASC
    ");

    $getResultsDetails = $mysqli->query("
        SELECT 
            transactionType,
            payeePayer AS transactionName,
            nominalAccount,
            transactionDate,
            details AS transactionDescription,
            ghsEquivalent AS transactionAmount 
        FROM 
            cashbook_transactions 
        WHERE 
            transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
            AND transactionType IN ('Receipt', 'Payment')
        ORDER BY 
            transactionType, transactionDate ASC
    ");
} elseif ($reportCategory == 'Orders') {
    $getResults = $mysqli->query("
        SELECT 
            DATE_FORMAT(createdAt, '%Y-%m') AS monthYear, 
            SUM(totalAmount) AS totalAmount 
        FROM 
            orders 
        WHERE 
            createdAt BETWEEN '$reportStartDate' AND '$reportEndDate'
            AND orderStatus != '0'
        GROUP BY 
            monthYear
        ORDER BY 
            monthYear ASC
    ");

    $getResultsDetails = $mysqli->query("
        SELECT 
            customerName,
            customerEmail,
            customerPhone,
            orderDetails,
            deliveryMethod,
            deliveryDate,
            paymentStatus,
            totalAmount,
            createdAt,
            orderStatus,
            customerAddress 
        FROM 
            orders 
        WHERE 
            createdAt BETWEEN '$reportStartDate' AND '$reportEndDate'
            AND orderStatus != '0'
        ORDER BY 
            createdAt ASC
    ");
} else {
    $transactionType = ($reportCategory == 'Receipt') ? 'Receipt' : 'Payment';
    
    $getResults = $mysqli->query("
        SELECT 
            nominalAccount,
            DATE_FORMAT(transactionDate, '%Y-%m') AS monthYear, 
            SUM(ghsEquivalent) AS totalAmount 
        FROM 
            cashbook_transactions 
        WHERE 
            transactionType = '$transactionType'
            AND transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
        GROUP BY 
            nominalAccount, monthYear
        ORDER BY 
            monthYear ASC
    ");

    $getResultsDetails = $mysqli->query("
        SELECT 
            payeePayer AS transactionName,
            nominalAccount,
            transactionDate,
            details AS transactionDescription,
            ghsEquivalent AS transactionAmount 
        FROM 
            cashbook_transactions 
        WHERE 
            transactionType = '$transactionType'
            AND transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
        ORDER BY 
            transactionDate ASC
    ");
}

// Preprocess data with category names
$data = [];
while ($row = $getResults->fetch_assoc()) {
    if ($reportCategory == 'Trial Balance') {
        $row['categoryName'] = categoryName($row['nominalAccount'] ?? 'Unknown') ?? 'Unknown';
    } elseif ($reportCategory == 'Orders') {
        $row['categoryName'] = $row['monthYear'];
    } else {
        $row['categoryName'] = categoryName($row['nominalAccount'] ?? 'Unknown') ?? 'Unknown';
    }
    $data[] = $row;
}

$output = '<div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn" style="margin-bottom: 30px;">';
$output .= '<div id="printArea">';
$output .= '<h5 class="font-weight-bolder mb-0">' . htmlspecialchars($reportCategory) . ' Report</h5>';
$output .= '<p class="text-sm text-secondary">From ' . htmlspecialchars($reportStartDate) . ' to ' . htmlspecialchars($reportEndDate) . '</p>';

$output .= '<div class="table-responsive">';
$output .= '<table class="table table-flush" id="reportTable" style="width: 100%;">';
$output .= '<thead><tr>';

if ($reportCategory == 'Trial Balance') {
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payee/Payer</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount (GHS)</th>';
} elseif ($reportCategory == 'Orders') {
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer Name</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery Details</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery Method</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Delivery Date</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Status</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order Date</th>';
} else {
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payee/Payer</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>';
    $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount (GHS)</th>';
}
$output .= '</tr></thead>';
$output .= '<tbody>';

$totalIncome = 0;
$totalExpenditure = 0;
$totalOrders = 0;

while ($resResults = $getResultsDetails->fetch_assoc()) { 
    if ($reportCategory == 'Orders') {
        $totalOrders += $resResults['totalAmount'];
        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($resResults['customerName'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['customerEmail'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['customerPhone'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['orderDetails'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['deliveryMethod'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['deliveryDate'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['paymentStatus'] ?? '') . '</td>';
        $output .= '<td>' . number_format($resResults['totalAmount'] ?? 0, 2) . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['createdAt'] ?? '') . '</td>';
        $output .= '</tr>';
    } elseif ($reportCategory == 'Trial Balance') {
        $amount = $resResults['transactionAmount'];
        if ($resResults['transactionType'] == 'Receipt') {
            $totalIncome += $amount;
        } else {
            $totalExpenditure += $amount;
        }
        $categoryName = categoryName($resResults['nominalAccount'] ?? 'Unknown') ?? 'Unknown';
        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionType'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionName'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($categoryName) . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionDate'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionDescription'] ?? '') . '</td>';
        $output .= '<td>' . number_format($resResults['transactionAmount'] ?? 0, 2) . '</td>';
        $output .= '</tr>';
    } else {
        $amount = $resResults['transactionAmount'];
        if ($reportCategory == 'Income') {
            $totalIncome += $amount;
        } else {
            $totalExpenditure += $amount;
        }
        $categoryName = categoryName($resResults['nominalAccount'] ?? 'Unknown') ?? 'Unknown';
        $output .= '<tr>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionName'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($categoryName) . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionDate'] ?? '') . '</td>';
        $output .= '<td>' . htmlspecialchars($resResults['transactionDescription'] ?? '') . '</td>';
        $output .= '<td>' . number_format($resResults['transactionAmount'] ?? 0, 2) . '</td>';
        $output .= '</tr>';
    }
}

if ($reportCategory == 'Trial Balance') {
    $output .= '<tr class="font-weight-bold"><td colspan="5">Total Revenue</td><td>' . number_format($totalIncome, 2) . '</td></tr>';
    $output .= '<tr class="font-weight-bold"><td colspan="5">Total Expenditure</td><td>' . number_format($totalExpenditure, 2) . '</td></tr>';
    $output .= '<tr class="font-weight-bold"><td colspan="5">Net Balance</td><td>' . number_format($totalIncome - $totalExpenditure, 2) . '</td></tr>';
} elseif ($reportCategory == 'Receipt') {
    $output .= '<tr class="font-weight-bold"><td colspan="4">Total Revenue</td><td>' . number_format($totalIncome, 2) . '</td></tr>';
} elseif ($reportCategory == 'Payment') {
    $output .= '<tr class="font-weight-bold"><td colspan="4">Total Expenditure</td><td>' . number_format($totalExpenditure, 2) . '</td></tr>';
} elseif ($reportCategory == 'Orders') {
    $output .= '<tr class="font-weight-bold"><td colspan="8">Total Orders</td><td>' . number_format($totalOrders, 2) . '</td></tr>';
}

$output .= '</tbody>';
$output .= '</table>';
$output .= '</div>';

if (!empty($data)) {
    $output .= '<canvas id="myChart" style="max-height: 300px; margin-top: 20px;"></canvas>';
    $output .= '<script>';
    $output .= '(function() {';
    $output .= '    const ctx = document.getElementById("myChart")?.getContext("2d");';
    $output .= '    const data = ' . json_encode($data) . ';';
    $output .= '    let chartInstance = Chart.getChart("myChart");';
    $output .= '    if (chartInstance) chartInstance.destroy();';
    
    if ($reportCategory == 'Trial Balance') {
        $output .= '    const types = [...new Set(data.map(item => item.transactionType))];';
        $output .= '    const categories = [...new Set(data.map(item => item.categoryName))];';
        $output .= '    const months = [...new Set(data.map(item => item.monthYear))];';
        $output .= '    const generateColors = (num) => {';
        $output .= '        const colors = [];';
        $output .= '        for (let i = 0; i < num; i++) {';
        $output .= '            colors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.2)`);';
        $output .= '        }';
        $output .= '        return colors;';
        $output .= '    };';
        $output .= '    const categoryColors = generateColors(categories.length);';
        $output .= '    const datasets = categories.map((category, index) => {';
        $output .= '        return {';
        $output .= '            label: category,';
        $output .= '            data: months.map(month => {';
        $output .= '                const item = data.find(d => d.monthYear === month && d.categoryName === category);';
        $output .= '                return item ? item.totalAmount : 0;';
        $output .= '            }),';
        $output .= '            backgroundColor: categoryColors[index],';
        $output .= '            borderColor: categoryColors[index].replace("0.2", "1"),';
        $output .= '            borderWidth: 1';
        $output .= '        };';
        $output .= '    });';
    } else {
        $output .= '    const categories = [...new Set(data.map(item => item.categoryName))];';
        $output .= '    const months = [...new Set(data.map(item => item.monthYear))];';
        $output .= '    const generateColors = (num) => {';
        $output .= '        const colors = [];';
        $output .= '        for (let i = 0; i < num; i++) {';
        $output .= '            colors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.2)`);';
        $output .= '        }';
        $output .= '        return colors;';
        $output .= '    };';
        $output .= '    const categoryColors = generateColors(categories.length);';
        $output .= '    const datasets = categories.map((category, index) => {';
        $output .= '        return {';
        $output .= '            label: category,';
        $output .= '            data: months.map(month => {';
        $output .= '                const item = data.find(d => d.monthYear === month && d.categoryName === category);';
        $output .= '                return item ? item.totalAmount : 0;';
        $output .= '            }),';
        $output .= '            backgroundColor: categoryColors[index],';
        $output .= '            borderColor: categoryColors[index].replace("0.2", "1"),';
        $output .= '            borderWidth: 1';
        $output .= '        };';
        $output .= '    });';
    }

    $output .= '    if (ctx) {';
    $output .= '        new Chart(ctx, {';
    $output .= '            type: "bar",';
    $output .= '            data: {';
    $output .= '                labels: months,';
    $output .= '                datasets: datasets';
    $output .= '            },';
    $output .= '            options: {';
    $output .= '                responsive: true,';
    $output .= '                scales: {';
    $output .= '                    x: { stacked: true, ticks: { autoSkip: false, maxRotation: 90, minRotation: 45 } },';
    $output .= '                    y: { beginAtZero: true, stacked: true }';
    $output .= '                },';
    $output .= '                plugins: {';
    $output .= '                    tooltip: {';
    $output .= '                        callbacks: {';
    $output .= '                            label: function(context) {';
    $output .= '                                const category = context.dataset.label;';
    $output .= '                                const amount = context.raw;';
    $output .= '                                return `${category}: ${amount.toLocaleString("en-US", { style: "currency", currency: "GHS" })}`;';
    $output .= '                            }';
    $output .= '                        }';
    $output .= '                    }';
    $output .= '                }';
    $output .= '            }';
    $output .= '        });';
    $output .= '    }';

    $output .= '    const printButton = document.getElementById("printButton");';
    $output .= '    if (printButton) {';
    $output .= '        printButton.addEventListener("click", () => {';
    $output .= '            const printArea = document.getElementById("printArea").innerHTML;';
    $output .= '            const originalContent = document.body.innerHTML;';
    $output .= '            document.body.innerHTML = printArea;';
    $output .= '            window.print();';
    $output .= '            document.body.innerHTML = originalContent;';
    $output .= '            window.location.reload();';
    $output .= '        });';
    $output .= '    }';

    $output .= '    const downloadExcel = document.getElementById("downloadExcel");';
    $output .= '    if (downloadExcel) {';
    $output .= '        downloadExcel.addEventListener("click", () => {';
    $output .= '            const table = document.getElementById("reportTable");';
    $output .= '            let csv = [];';
    $output .= '            const rows = table.querySelectorAll("tr");';
    $output .= '            for (let row of rows) {';
    $output .= '                const cols = row.querySelectorAll("td, th");';
    $output .= '                const rowData = Array.from(cols).map(col => `"${col.innerText.replace(/"/g, \'""\')}"`).join(",");';
    $output .= '                csv.push(rowData);';
    $output .= '            }';
    $output .= '            const csvContent = csv.join("\n");';
    $output .= '            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });';
    $output .= '            const link = document.createElement("a");';
    $output .= '            const url = URL.createObjectURL(blob);';
    $output .= '            link.setAttribute("href", url);';
    $output .= '            link.setAttribute("download", "' . htmlspecialchars($reportCategory) . '_Report.csv");';
    $output .= '            document.body.appendChild(link);';
    $output .= '            link.click();';
    $output .= '            document.body.removeChild(link);';
    $output .= '        });';
    $output .= '    }';
    $output .= '})();';
    $output .= '</script>';
} else {
    $output .= '<p>No data available for the chart.</p>';
}

$output .= '</div>';

if (mysqli_num_rows($getResultsDetails) > 0) {
    $output .= '<div class="mt-5">';
    $output .= '<button id="printButton" class="btn btn-sm btn-outline-secondary me-2">';
    $output .= '<i class="fas fa-print me-1"></i> Print';
    $output .= '</button>';
    $output .= '<button id="downloadExcel" class="btn btn-sm btn-outline-success">';
    $output .= '<i class="fas fa-file-excel me-1"></i> Download Excel';
    $output .= '</button>';
    $output .= '</div>';
} else {
    $output .= '<span style="text-align-center-align: center;">No record found</span>';
}

$output .= '</div>';

echo $output;
?>