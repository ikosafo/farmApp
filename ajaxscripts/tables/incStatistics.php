<?php
include('../../config.php');
include('../../includes/functions.php');

// Get current and previous quarter
$currentYear = date('Y'); // 2025
$currentQuarter = ceil(date('n') / 3); // Q2
$currentQuarterStr = $currentYear . '-Q' . $currentQuarter; // 2025-Q2
$prevQuarterStr = $currentQuarter == 1 ? ($currentYear - 1) . '-Q4' : $currentYear . '-Q' . ($currentQuarter - 1); // 2025-Q1

// Query for current quarter detailed income transactions
$getCurrentDetails = $mysqli->query("
    SELECT 
        transactionName,
        transactionCategory,
        transactionDate,
        transactionDescription,
        transactionAmount
    FROM 
        transactions 
    WHERE 
        transactionType = 'Income' AND transStatus = 1
        AND CONCAT(YEAR(transactionDate), '-Q', QUARTER(transactionDate)) = '$currentQuarterStr'
    ORDER BY 
        transactionDate ASC
");

// Query for current quarter total income
$getCurrentTotal = $mysqli->query("
    SELECT 
        SUM(transactionAmount) AS totalAmount
    FROM 
        transactions 
    WHERE 
        transactionType = 'Income' AND transStatus = 1
        AND CONCAT(YEAR(transactionDate), '-Q', QUARTER(transactionDate)) = '$currentQuarterStr'
");
$currentTotal = $getCurrentTotal->fetch_assoc()['totalAmount'] ?? 0;

// Query for previous quarter total income
$getPrevTotal = $mysqli->query("
    SELECT 
        SUM(transactionAmount) AS totalAmount
    FROM 
        transactions 
    WHERE 
        transactionType = 'Income' AND transStatus = 1
        AND CONCAT(YEAR(transactionDate), '-Q', QUARTER(transactionDate)) = '$prevQuarterStr'
");
$prevTotal = $getPrevTotal->fetch_assoc()['totalAmount'] ?? 0;

// Query for chart data (both quarters, grouped by category)
$getChartData = $mysqli->query("
    SELECT 
        transactionCategory,
        CONCAT(YEAR(transactionDate), '-Q', QUARTER(transactionDate)) AS quarter,
        SUM(transactionAmount) AS totalAmount 
    FROM 
        transactions 
    WHERE 
        transactionType = 'Income' AND transStatus = 1
        AND CONCAT(YEAR(transactionDate), '-Q', QUARTER(transactionDate)) IN ('$currentQuarterStr', '$prevQuarterStr')
    GROUP BY 
        transactionCategory, quarter
");

// Preprocess details with category names
$details = [];
while ($row = $getCurrentDetails->fetch_assoc()) {
    $row['categoryName'] = categoryName($row['transactionCategory']);
    $details[] = $row;
}

// Preprocess chart data
$chartData = [];
while ($row = $getChartData->fetch_assoc()) {
    $row['categoryName'] = categoryName($row['transactionCategory']);
    $chartData[$row['quarter']][] = $row;
}

$output = '<div class="bg-gradient-to-br">';
$output .= '<div class="container mx-auto px-2 py-4 pt-0">'; 
$output .= '<div class="bg-white rounded-xl p-4">'; 

// Include dependencies
$output .= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">';
$output .= '<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>';

// Header
$output .= '<h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Revenue Report - ' . $currentQuarterStr . '</h1>';

// Debug data
$output .= '<!-- Debug: ' . $currentQuarterStr . ' has ' . count($details) . ' transactions -->';
$output .= '<!-- Debug: Current Total from DB: ' . number_format($currentTotal, 2) . ', Previous Total from DB: ' . number_format($prevTotal, 2) . ' -->';

// Table
$output .= '<div class="overflow-x-auto">';
$output .= '<table class="w-full text-left border-collapse">';
$output .= '<thead class="bg-gray-200">';
$output .= '<tr>';
$output .= '<th class="py-2 px-3 text-sm font-semibold text-gray-700">Transaction Name</th>';
$output .= '<th class="py-2 px-3 text-sm font-semibold text-gray-700">Category</th>';
$output .= '<th class="py-2 px-3 text-sm font-semibold text-gray-700">Date</th>';
$output .= '<th class="py-2 px-3 text-sm font-semibold text-gray-700">Description</th>';
$output .= '<th class="py-2 px-3 text-sm font-semibold text-gray-700">Amount</th>';
$output .= '</tr>';
$output .= '</thead>';
$output .= '<tbody>';

$totalIncome = 0;
foreach ($details as $resResults) {
    $totalIncome += $resResults['transactionAmount'];
    $output .= '<tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">';
    $output .= '<td class="py-2 px-3 text-sm text-gray-600">' . htmlspecialchars($resResults['transactionName']) . '</td>';
    $output .= '<td class="py-2 px-3 text-sm text-gray-600">' . htmlspecialchars($resResults['categoryName']) . '</td>';
    $output .= '<td class="py-2 px-3 text-sm text-gray-600">' . htmlspecialchars($resResults['transactionDate']) . '</td>';
    $output .= '<td class="py-2 px-3 text-sm text-gray-600">' . htmlspecialchars($resResults['transactionDescription']) . '</td>';
    $output .= '<td class="py-2 px-3 text-sm text-gray-600">' . number_format($resResults['transactionAmount'], 2) . '</td>';
    $output .= '</tr>';
}

$output .= '<tr class="bg-gray-100 font-semibold">';
$output .= '<td colspan="4" class="py-2 px-3 text-sm text-gray-700">Total Income</td>';
$output .= '<td class="py-2 px-3 text-sm text-gray-700">' . number_format($totalIncome, 2) . '</td>';
$output .= '</tr>';
$output .= '</tbody>';
$output .= '</table>';
$output .= '</div>';

// Comparison note
$output .= '<div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg p-3 mt-4 shadow-md">';
$output .= '<p class="text-base font-semibold">Current Quarter (' . $currentQuarterStr . ') Total: ' . number_format($currentTotal, 2) . '</p>';
$output .= '<p class="text-base font-semibold">Previous Quarter (' . $prevQuarterStr . ') Total: ' . number_format($prevTotal, 2) . '</p>';
$output .= '<p class="text-base font-semibold">Change: ' . ($prevTotal > 0 ? number_format((($currentTotal - $prevTotal) / $prevTotal) * 100, 2) : '-') . '%</p>';
$output .= '</div>';

// Chart (moved to bottom with caption)
$output .= '<div class="bg-gray-50 rounded-lg p-3 mt-4 shadow-sm">'; 
$output .= '<h2 class="text-lg font-semibold text-gray-700 mb-2">Quarterly Revenue Comparison</h2>';
$output .= '<canvas id="comparisonChart" class="max-h-80"></canvas>';
$output .= '</div>';

$output .= '</div>'; // End card
$output .= '</div>'; // End container
$output .= '</div>'; // End background

// JavaScript for chart
$output .= '<script>';
$output .= '(function() {';
$output .= '    console.log("Script initialized for Revenue Report");';
$output .= '    const chartData = ' . json_encode($chartData) . ';';

// Chart
$output .= '    try {';
$output .= '        const ctx = document.getElementById("comparisonChart")?.getContext("2d");';
$output .= '        if (ctx) {';
$output .= '            console.log("Creating comparison chart");';
$output .= '            const allCategories = [...new Set([].concat(...Object.values(chartData).map(q => q.map(item => item.categoryName))))];';
$output .= '            const currentData = allCategories.map(cat => {';
$output .= '                const item = chartData["' . $currentQuarterStr . '"]?.find(d => d.categoryName === cat);';
$output .= '                return item ? item.totalAmount : 0;';
$output .= '            });';
$output .= '            const prevData = allCategories.map(cat => {';
$output .= '                const item = chartData["' . $prevQuarterStr . '"]?.find(d => d.categoryName === cat);';
$output .= '                return item ? item.totalAmount : 0;';
$output .= '            });';
$output .= '            new Chart(ctx, {';
$output .= '                type: "bar",';
$output .= '                data: {';
$output .= '                    labels: allCategories,';
$output .= '                    datasets: [';
$output .= '                        {';
$output .= '                            label: "' . $currentQuarterStr . '",';
$output .= '                            data: currentData,';
$output .= '                            backgroundColor: "rgba(59, 130, 246, 0.5)",';
$output .= '                            borderColor: "rgba(59, 130, 246, 1)",';
$output .= '                            borderWidth: 1';
$output .= '                        },';
$output .= '                        {';
$output .= '                            label: "' . $prevQuarterStr . '",';
$output .= '                            data: prevData,';
$output .= '                            backgroundColor: "rgba(239, 68, 68, 0.5)",';
$output .= '                            borderColor: "rgba(239, 68, 68, 1)",';
$output .= '                            borderWidth: 1';
$output .= '                        }';
$output .= '                    ]';
$output .= '                },';
$output .= '                options: {';
$output .= '                    responsive: true,';
$output .= '                    scales: {';
$output .= '                        y: {';
$output .= '                            beginAtZero: true,';
$output .= '                            title: { display: true, text: "Income (GHS)" }';
$output .= '                        },';
$output .= '                        x: {';
$output .= '                            title: { display: true, text: "Category" }';
$output .= '                        }';
$output .= '                    },';
$output .= '                    plugins: {';
$output .= '                        legend: { display: true, position: "top" },';
$output .= '                        tooltip: {';
$output .= '                            callbacks: {';
$output .= '                                label: function(context) {';
$output .= '                                    const quarter = context.dataset.label;';
$output .= '                                    const amount = context.raw;';
$output .= '                                    return `${quarter} - ${context.label}: ${amount.toLocaleString("en-US", { style: "currency", currency: "GHS" })}`;';
$output .= '                                }';
$output .= '                            }';
$output .= '                        }';
$output .= '                    }';
$output .= '                }';
$output .= '            });';
$output .= '        } else {';
$output .= '            console.error("Canvas not found");';
$output .= '        }';
$output .= '    } catch (e) {';
$output .= '        console.error("Error creating chart: ", e);';
$output .= '    }';
$output .= '})();';
$output .= '</script>';

if (empty($details)) {
    $output .= '<div class="container mx-auto px-2 py-4 pt-0">';
    $output .= '<div class="bg-white rounded-xl shadow-lg p-4 text-center">';
    $output .= '<p class="text-base text-gray-600">No record found for ' . $currentQuarterStr . '</p>';
    $output .= '</div>';
    $output .= '</div>';
}

$output .= '</div>'; 

echo $output;