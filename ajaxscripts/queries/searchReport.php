<?php
include('../../config.php');
include('../../includes/functions.php');

$reportCategory = mysqli_real_escape_string($mysqli, $_POST['reportCategory']);
$reportStartDate = mysqli_real_escape_string($mysqli, $_POST['reportStartDate']);
$reportEndDate = mysqli_real_escape_string($mysqli, $_POST['reportEndDate']);

// Determine query based on report category
if ($reportCategory == 'Trial Balance') {
    // For Trial Balance, fetch both Income and Expenditure for a summary
    $getResults = $mysqli->query("
        SELECT 
            transactionType,
            transactionCategory,
            DATE_FORMAT(transactionDate, '%Y-%m') AS monthYear, 
            SUM(transactionAmount) AS totalAmount 
        FROM 
            transactions 
        WHERE 
            transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
            AND transactionType IN ('Income', 'Expenditure')
        GROUP BY 
            transactionType, transactionCategory, monthYear
        ORDER BY 
            transactionType, monthYear ASC
    ");

    $getResultsDetails = $mysqli->query("
        SELECT 
            transactionType,
            transactionName,
            transactionCategory,
            transactionDate,
            transactionDescription,
            transactionAmount 
        FROM 
            transactions 
        WHERE 
            transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
            AND transactionType IN ('Income', 'Expenditure')
        ORDER BY 
            transactionType, transactionDate ASC
    ");
} else {
    // For Income or Expenditure
    $transactionType = ($reportCategory == 'Income') ? 'Income' : 'Expenditure';
    
    $getResults = $mysqli->query("
        SELECT 
            transactionCategory,
            DATE_FORMAT(transactionDate, '%Y-%m') AS monthYear, 
            SUM(transactionAmount) AS totalAmount 
        FROM 
            transactions 
        WHERE 
            transactionType = '$transactionType'
            AND transactionDate BETWEEN '$reportStartDate' AND '$reportEndDate'
        GROUP BY 
            transactionCategory, monthYear
        ORDER BY 
            monthYear ASC
    ");

    $getResultsDetails = $mysqli->query("
        SELECT 
            transactionName,
            transactionCategory,
            transactionDate,
            transactionDescription,
            transactionAmount 
        FROM 
            transactions 
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
        $row['categoryName'] = $row['transactionType'] == 'Income' 
            ? incCategoryName($row['transactionCategory']) 
            : expCategoryName($row['transactionCategory']);
    } else {
        $row['categoryName'] = $reportCategory == 'Income' 
            ? incCategoryName($row['transactionCategory']) 
            : expCategoryName($row['transactionCategory']);
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
}
$output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Transaction Name</th>';
$output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>';
$output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>';
$output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>';
$output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>';
$output .= '</tr></thead>';
$output .= '<tbody>';

$totalIncome = 0;
$totalExpenditure = 0;
while ($resResults = $getResultsDetails->fetch_assoc()) { 
    $amount = $resResults['transactionAmount'];
    if ($reportCategory == 'Trial Balance' && $resResults['transactionType'] == 'Income') {
        $totalIncome += $amount;
    } elseif ($reportCategory == 'Trial Balance' && $resResults['transactionType'] == 'Expenditure') {
        $totalExpenditure += $amount;
    } elseif ($reportCategory == 'Income') {
        $totalIncome += $amount;
    } else {
        $totalExpenditure += $amount;
    }
    // Map category ID to name
    $categoryName = $reportCategory == 'Income' ? incCategoryName($resResults['transactionCategory']) : 
                    ($reportCategory == 'Expenditure' ? expCategoryName($resResults['transactionCategory']) : 
                    ($resResults['transactionType'] == 'Income' ? incCategoryName($resResults['transactionCategory']) : 
                    expCategoryName($resResults['transactionCategory'])));
    $output .= '<tr>';
    if ($reportCategory == 'Trial Balance') {
        $output .= '<td>' . htmlspecialchars($resResults['transactionType']) . '</td>';
    }
    $output .= '<td>' . htmlspecialchars($resResults['transactionName']) . '</td>';
    $output .= '<td>' . htmlspecialchars($categoryName) . '</td>';
    $output .= '<td>' . htmlspecialchars($resResults['transactionDate']) . '</td>';
    $output .= '<td>' . htmlspecialchars($resResults['transactionDescription']) . '</td>';
    $output .= '<td>' . number_format($resResults['transactionAmount'], 2) . '</td>';
    $output .= '</tr>';
}
if ($reportCategory == 'Trial Balance') {
    $output .= '<tr class="font-weight-bold"><td colspan="' . ($reportCategory == 'Trial Balance' ? 5 : 4) . '">Total Income</td><td>' . number_format($totalIncome, 2) . '</td></tr>';
    $output .= '<tr class="font-weight-bold"><td colspan="' . ($reportCategory == 'Trial Balance' ? 5 : 4) . '">Total Expenditure</td><td>' . number_format($totalExpenditure, 2) . '</td></tr>';
    $output .= '<tr class="font-weight-bold"><td colspan="' . ($reportCategory == 'Trial Balance' ? 5 : 4) . '">Net Balance</td><td>' . number_format($totalIncome - $totalExpenditure, 2) . '</td></tr>';
} elseif ($reportCategory == 'Income') {
    $output .= '<tr class="font-weight-bold"><td colspan="4">Total Income</td><td>' . number_format($totalIncome, 2) . '</td></tr>';
} else {
    $output .= '<tr class="font-weight-bold"><td colspan="4">Total Expenditure</td><td>' . number_format($totalExpenditure, 2) . '</td></tr>';
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

    // Add Print and Excel Download functionality
    $output .= '    const printButton = document.getElementById("printButton");';
    $output .= '    if (printButton) {';
    $output .= '        printButton.addEventListener("click", () => {';
    $output .= '            const printArea = document.getElementById("printArea").innerHTML;';
    $output .= '            const originalContent = document.body.innerHTML;';
    $output .= '            document.body.innerHTML = printArea;';
    $output .= '            window.print();';
    $output .= '            document.body.innerHTML = originalContent;';
    $output .= '            window.location.reload();'; // Reload to restore event listeners
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
    $output .= '<button id="printButton" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-print me-1"></i> Print
                            </button>';
    $output .= '<button id="downloadExcel" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i> Download Excel
                            </button>';
    $output .= '</div>';
} else {
    $output .= '<span style="text-align:center">No record found</span>';
}

$output .= '</div>';

echo $output;
?>