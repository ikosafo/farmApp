<?php
include('./includes/sidebar.php');
include('config.php');
?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <?php include('./includes/header.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-radius-xl p-4 mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bolder mb-0">Cashbook Report</h5>
                        <div>
                            <button id="printCashButton" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <button id="downloadExcelBtn" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i> Download Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="rangeSelector" class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="dateRangeType" id="customRange" value="custom">
                                <label class="form-check-label" for="customRange">Custom Range</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="dateRangeType" id="predefinedRange" value="predefined">
                                <label class="form-check-label" for="predefinedRange">Predefined Range</label>
                            </div>
                        </div>
                        <div id="filterForm" style="display: none;">
                            <div class="row mb-4">
                                <div class="col-md-3" id="startDateContainer" style="display: none;">
                                    <label for="startDate" class="form-label mb-1">Start Date</label>
                                    <input id="startDate" class="form-control border-radius-md" type="text" placeholder="Select start date" required>
                                </div>
                                <div class="col-md-3" id="endDateContainer" style="display: none;">
                                    <label for="endDate" class="form-label mb-1">End Date</label>
                                    <input id="endDate" class="form-control border-radius-md" type="text" placeholder="Select end date" required>
                                </div>
                                <div class="col-md-3" id="predefinedDateRangeContainer" style="display: none;">
                                    <label for="predefinedDateRange" class="form-label mb-1">Predefined Range</label>
                                    <select id="predefinedDateRange" class="form-control border-radius-md" disabled>
                                        <option value="">Select Range</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="last3days">Last 3 Days</option>
                                        <option value="thisweek">This Week</option>
                                        <option value="lastmonth">Last Month</option>
                                        <option value="last3months">Last 3 Months</option>
                                        <option value="thisyear">This Year</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="nominalAccountContainer" style="display: none;">
                                    <label for="nominalAccountFilter" class="form-label mb-1">Nominal Account</label>
                                    <select id="nominalAccountFilter" class="form-control border-radius-md">
                                        <option value="">Select All</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-4 d-flex align-items-end" id="searchButtonContainer" style="display: none;">
                                    <button id="searchCashbook" class="btn bg-gradient-primary w-100">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="totalsContainer" class="row mb-4"></div>
                        <div id="statsContainer" class="row mb-4"></div>
                        <div id="chartContainer" class="row mb-4"></div>
                        <div id="cashbookTables" class="table-responsive table-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast for errors -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</main>

<?php include('./includes/footer.php'); ?>

<!-- STYLES -->
<style>
    body {
        font-family: 'Poppins', sans-serif !important;
        font-size: 0.9rem;
    }
    .accounting-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
        margin-top: 10px;
        background: #fff;
        border: 1px solid #d1d5db;
        font-family: 'Poppins', sans-serif !important;
    }

    .accounting-table th,
    .accounting-table td {
        padding: 6px 10px;
        text-align: left;
        vertical-align: middle;
        border: 1px solid #d1d5db;
        font-family: 'Poppins', sans-serif !important;
    }

    .accounting-table th {
        background: #e5e7eb;
        color: #1f2937;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-family: 'Poppins', sans-serif !important;
    }

    .accounting-table tbody tr {
        background: #fff;
    }

    .accounting-table tbody tr:hover {
        background: #f3f4f6;
    }

    .accounting-table tbody td {
        color: #1f2937;
        font-weight: 400;
        font-family: 'Poppins', sans-serif !important;
    }

    .table-container {
        overflow-x: auto;
        max-height: 400px;
    }

    .stats-card, .totals-card {
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 15px;
        text-align: center;
        font-size: 0.9rem;
        cursor: pointer;
        font-family: 'Poppins', sans-serif !important;
    }

    .stats-card:hover {
        background: #f3f4f6;
    }

    .stats-card h6, .totals-card h6 {
        font-size: 0.85rem;
        margin-bottom: 10px;
        color: #1f2937;
        font-family: 'Poppins', sans-serif !important;
    }

    .stats-card p, .totals-card p {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        font-family: 'Poppins', sans-serif !important;
    }

    .income {
        color: #2ecc71;
    }

    .expenditure {
        color: #e74c3c;
    }

    .net-balance {
        color: #3498db;
    }

    .form-check-label, .form-label {
        font-family: 'Poppins', sans-serif !important;
    }

    .form-control, .btn {
        font-family: 'Poppins', sans-serif !important;
    }

    .toast, .toast-body {
        font-family: 'Poppins', sans-serif !important;
    }

    h3, h5 {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const startDatePicker = flatpickr("#startDate", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        maxDate: "today"
    });

    const endDatePicker = flatpickr("#endDate", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        maxDate: "today"
    });

    const predefinedDateRange = document.getElementById('predefinedDateRange');
    const startDateContainer = document.getElementById('startDateContainer');
    const endDateContainer = document.getElementById('endDateContainer');
    const predefinedDateRangeContainer = document.getElementById('predefinedDateRangeContainer');
    const nominalAccountContainer = document.getElementById('nominalAccountContainer');
    const searchButtonContainer = document.getElementById('searchButtonContainer');
    let transactionsGlobal = [];
    let selectedCategoryId = null;
    let categoryStats = {};
    let categoriesMap = {};

    function showErrorToast(message) {
        const toastEl = document.getElementById('errorToast');
        toastEl.querySelector('.toast-body').textContent = message;
        new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function getPredefinedRange(range) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        let startDate, endDate = new Date(today);

        switch (range) {
            case 'today':
                startDate = new Date(today);
                break;
            case 'yesterday':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - 1);
                endDate.setDate(today.getDate() - 1);
                break;
            case 'last3days':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - 3);
                break;
            case 'thisweek':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - today.getDay());
                break;
            case 'lastmonth':
                startDate = new Date(today);
                startDate.setMonth(today.getMonth() - 1);
                startDate.setDate(1);
                endDate.setMonth(today.getMonth() - 1);
                endDate.setDate(new Date(today.getFullYear(), today.getMonth(), 0).getDate());
                break;
            case 'last3months':
                startDate = new Date(today);
                startDate.setMonth(today.getMonth() - 3);
                break;
            case 'thisyear':
                startDate = new Date(today.getFullYear(), 0, 1);
                break;
            default:
                return null;
        }

        return { startDate, endDate };
    }

    document.querySelectorAll('input[name="dateRangeType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const filterForm = document.getElementById('filterForm');
            filterForm.style.display = 'block';
            startDateContainer.style.display = this.value === 'custom' ? 'block' : 'none';
            endDateContainer.style.display = this.value === 'custom' ? 'block' : 'none';
            predefinedDateRangeContainer.style.display = this.value === 'predefined' ? 'block' : 'none';
            nominalAccountContainer.style.display = 'block';
            searchButtonContainer.style.display = 'block';
            predefinedDateRange.disabled = this.value !== 'predefined';
            startDatePicker.setDate(null);
            endDatePicker.setDate(null);
            predefinedDateRange.value = '';
            fetchCategories();
        });
    });

    predefinedDateRange.addEventListener('change', function() {
        const range = this.value;
        if (range) {
            const dates = getPredefinedRange(range);
            if (dates) {
                startDatePicker.setDate(dates.startDate);
                endDatePicker.setDate(dates.endDate);
            }
        }
    });

    function fetchCategories() {
        fetch('ajaxscripts/tables/cashbook.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `startDate=2000-01-01&endDate=${formatDate(new Date())}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.error || 'Failed to load categories');
            }
            categoriesMap = {};
            data.categories.forEach(cat => {
                categoriesMap[cat.catId] = cat.categoryName;
            });
            populateNominalAccountFilter(data.categories);
        })
        .catch(error => {
            showErrorToast(error.message);
        });
    }

    document.getElementById('searchCashbook').addEventListener('click', function() {
        const dateRangeType = document.querySelector('input[name="dateRangeType"]:checked').value;
        let startDate, endDate;

        if (dateRangeType === 'custom') {
            startDate = startDatePicker.selectedDates[0];
            endDate = endDatePicker.selectedDates[0];
            if (!startDate || !endDate) {
                return showErrorToast("Please select both start and end dates.");
            }
            if (endDate < startDate) {
                return showErrorToast("End date cannot be before start date.");
            }
        } else if (dateRangeType === 'predefined') {
            const range = predefinedDateRange.value;
            if (!range) {
                return showErrorToast("Please select a predefined range.");
            }
            const dates = getPredefinedRange(range);
            if (!dates) {
                return showErrorToast("Invalid predefined range.");
            }
            startDate = dates.startDate;
            endDate = dates.endDate;
        }

        loadCashbookTables(startDate, endDate);
    });

    function loadCashbookTables(startDate, endDate) {
        const cashbookTables = document.getElementById('cashbookTables');
        cashbookTables.innerHTML = `<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-lg"></i> Loading...</div>`;

        const formattedStart = formatDate(startDate);
        const formattedEnd = formatDate(endDate);

        fetch('ajaxscripts/tables/cashbook.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `startDate=${formattedStart}&endDate=${formattedEnd}&catId=${selectedCategoryId || ''}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.error || 'Failed to load data');
            }

            transactionsGlobal = data.data.map(row => ({
                ...row,
                transactionDate: row.transactionDate ? row.transactionDate.split(' ')[0] : ''
            }));
            categoryStats = data.categoryStats;
            categoriesMap = {};
            data.categories.forEach(cat => {
                categoriesMap[cat.catId] = cat.categoryName;
            });
            populateNominalAccountFilter(data.categories);
            renderTotals();
            renderStats();
            renderChart();
            renderTable(transactionsGlobal);
        })
        .catch(error => {
            cashbookTables.innerHTML = `<div class="alert alert-danger text-center m-2">Failed to load data: ${error.message}. Please try again.</div>`;
            showErrorToast(error.message);
        });
    }

    function populateNominalAccountFilter(categories) {
        const nominalSelect = document.getElementById('nominalAccountFilter');
        const currentValue = selectedCategoryId || '';
        nominalSelect.innerHTML = '<option value="">Select All</option>' +
            categories.map(cat => `<option value="${cat.catId}" ${cat.catId === currentValue ? 'selected' : ''}>${cat.categoryName}</option>`).join('');

        nominalSelect.addEventListener('change', function() {
            selectedCategoryId = this.value;
            if (startDatePicker.selectedDates[0] && endDatePicker.selectedDates[0]) {
                loadCashbookTables(startDatePicker.selectedDates[0], endDatePicker.selectedDates[0]);
            }
        });
    }

    function renderTotals() {
        const totalsContainer = document.getElementById('totalsContainer');
        let totalIncome = 0;
        let totalExpenditure = 0;

        transactionsGlobal.forEach(txn => {
            const ghsAmount = parseFloat(txn.ghsEquivalent) || 0;
            if (txn.transactionType === 'Receipt') {
                totalIncome += ghsAmount;
            } else {
                totalExpenditure += ghsAmount;
            }
        });

        const netBalance = totalIncome - totalExpenditure;
        const netBalanceColor = netBalance >= 0 ? '#2ecc71' : '#e74c3c'; // Green for positive, red for negative

        const html = `
            <div class="col-md-12 mb-3">
                <div class="totals-card">
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Total Income (GHS)</h6>
                            <p class="income" style="color: #2596be;">${totalIncome.toFixed(2)}</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Total Expenditure (GHS)</h6>
                            <p class="expenditure" style="color: #e28743;">${totalExpenditure.toFixed(2)}</p>
                        </div>
                        <div class="col-md-4">
                            <h6>Net Balance (GHS)</h6>
                            <p class="net-balance" style="color: ${netBalanceColor};">${netBalance.toFixed(2)}</p>
                        </div>
                    </div>
                </div>
            </div>`;

        totalsContainer.innerHTML = html;
    }

    function renderStats() {
        const statsContainer = document.getElementById('statsContainer');
        let html = '';

        if (!selectedCategoryId) {
            categoryStats.forEach(stats => {
                html += `
                    <div class="col-md-4 mb-3">
                        <div class="stats-card" data-category-id="${stats.catId}">
                            <h6>${stats.categoryName}</h6>
                            <p class="income" style="color: #2596be;">Income: ${stats.totalIncome.toFixed(2)}</p>
                            <p class="expenditure" style="color: #e28743;">Expenditure: ${stats.totalExpenditure.toFixed(2)}</p>
                        </div>
                    </div>`;
            });
        }

        statsContainer.innerHTML = html;

        document.querySelectorAll('.stats-card[data-category-id]').forEach(card => {
            card.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');
                document.getElementById('nominalAccountFilter').value = categoryId;
                selectedCategoryId = categoryId;
                loadCashbookTables(startDatePicker.selectedDates[0] || new Date(), endDatePicker.selectedDates[0] || new Date());
            });
        });
    }

    let chartInstance = null;

    function renderChart() {
        const chartContainer = document.getElementById('chartContainer');
        let filteredStats = selectedCategoryId
            ? categoryStats.filter(s => s.catId === selectedCategoryId)
            : categoryStats;

        if (filteredStats.length === 0) {
            chartContainer.innerHTML = '<div class="text-center">No data available for the chart.</div>';
            return;
        }

        chartContainer.innerHTML = '<canvas id="categoryChart" height="100"></canvas>';

        const labels = filteredStats.map(s => s.categoryName);
        const incomeData = filteredStats.map(s => parseFloat(s.totalIncome) || 0);
        const expenditureData = filteredStats.map(s => parseFloat(s.totalExpenditure) || 0);

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income (GHS)',
                        data: incomeData,
                        backgroundColor: '#2ecc71',
                        borderColor: '#27ae60',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenditure (GHS)',
                        data: expenditureData,
                        backgroundColor: '#e74c3c',
                        borderColor: '#c0392b',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (GHS)',
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    x: {
                        title: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: selectedCategoryId ? `Income vs Expenditure for ${categoriesMap[selectedCategoryId]}` : 'Income vs Expenditure by Category',
                        font: {
                            family: 'Poppins'
                        }
                    }
                }
            }
        });
    }

    function renderTable(data) {
        const groupedByDate = data.reduce((acc, row) => {
            const date = row.transactionDate;
            if (!acc[date]) {
                acc[date] = { income: null, expenditure: null };
            }
            if (row.transactionType === 'Receipt') {
                acc[date].income = row;
            } else {
                acc[date].expenditure = row;
            }
            return acc;
        }, {});

        let html = `
            <table class="accounting-table">
                <thead>
                    <tr>
                        <th style="width: 90px;" rowspan="2">Date</th>
                        <th style="width: 200px;" colspan="3">Income (Receipts)</th>
                        <th style="width: 200px;" colspan="3">Expenditure (Payments)</th>
                    </tr>
                    <tr>
                        <th style="width: 150px;">Payee & Details</th>
                        <th style="width: 100px;">Currency & Amount</th>
                        <th style="width: 100px;">GHS Equivalent</th>
                        <th style="width: 150px;">Payee & Details</th>
                        <th style="width: 100px;">Currency & Amount</th>
                        <th style="width: 100px;">GHS Equivalent</th>
                    </tr>
                </thead>
                <tbody>`;

        Object.keys(groupedByDate).sort().forEach(date => {
            const { income, expenditure } = groupedByDate[date];
            const incomePayeeDetails = income ? `${income.payeePayer || ''}${income.payeePayer && income.details ? ' - ' : ''}${income.details || ''}` : '';
            const incomeCurrencyAmount = income ? `${income.currency || ''}${income.currency && income.amount ? ': ' : ''}${income.amount ? parseFloat(income.amount).toFixed(2) : ''}` : '';
            const expenditurePayeeDetails = expenditure ? `${expenditure.payeePayer || ''}${expenditure.payeePayer && expenditure.details ? ' - ' : ''}${expenditure.details || ''}` : '';
            const expenditureCurrencyAmount = expenditure ? `${expenditure.currency || ''}${expenditure.currency && expenditure.amount ? ': ' : ''}${expenditure.amount ? parseFloat(expenditure.amount).toFixed(2) : ''}` : '';

            html += `
                <tr>
                    <td>${date || ''}</td>
                    <td>${incomePayeeDetails}</td>
                    <td>${incomeCurrencyAmount}</td>
                    <td>${income && income.ghsEquivalent ? parseFloat(income.ghsEquivalent).toFixed(2) : ''}</td>
                    <td>${expenditurePayeeDetails}</td>
                    <td>${expenditureCurrencyAmount}</td>
                    <td>${expenditure && expenditure.ghsEquivalent ? parseFloat(expenditure.ghsEquivalent).toFixed(2) : ''}</td>
                </tr>`;
        });

        html += `
                </tbody>
            </table>`;

        document.getElementById('cashbookTables').innerHTML = html;
    }

    document.getElementById('printCashButton').addEventListener('click', function() {
        const totalsContent = document.getElementById('totalsContainer').innerHTML;
        const statsContent = document.getElementById('statsContainer').innerHTML;
        const chartContent = document.getElementById('chartContainer').innerHTML;
        const tableContent = document.getElementById('cashbookTables').innerHTML;
        const newWin = window.open('', '', 'width=900,height=700');
        newWin.document.write(`
            <html>
            <head>
                <title>Cashbook Report</title>
                <style>
                    body { font-family: 'Poppins', sans-serif; font-size: 10pt; }
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
                    th { background: #e5e7eb; color: #1f2937; font-weight: 600; font-size: 9pt; }
                    tr:hover { background: #f3f4f6; }
                    .stats-card, .totals-card { border: 1px solid #d1d5db; padding: 10px; margin-bottom: 10px; text-align: center; }
                    .income { color: #2ecc71; }
                    .expenditure { color: #e74c3c; }
                    .net-balance { color: ${netBalance >= 0 ? '#2ecc71' : '#e74c3c'}; }
                </style>
            </head>
            <body>
                <h3>Cashbook Report</h3>
                ${totalsContent}
                ${statsContent}
                ${chartContent}
                ${tableContent}
            </body>
            </html>
        `);
        newWin.document.close();
        newWin.print();
    });

    document.getElementById('downloadExcelBtn').addEventListener('click', function() {
        const wb = XLSX.utils.book_new();

        const groupedByDate = transactionsGlobal.reduce((acc, row) => {
            const date = row.transactionDate;
            if (!acc[date]) {
                acc[date] = { income: null, expenditure: null };
            }
            if (row.transactionType === 'Receipt') {
                acc[date].income = row;
            } else {
                acc[date].expenditure = row;
            }
            return acc;
        }, {});

        const wsData = Object.keys(groupedByDate).sort().map(date => {
            const { income, expenditure } = groupedByDate[date];
            return {
                Date: date,
                'Income Payee & Details': income ? `${income.payeePayer || ''}${income.payeePayer && income.details ? ' - ' : ''}${income.details || ''}` : '',
                'Income Currency & Amount': income ? `${income.currency || ''}${income.currency && income.amount ? ': ' : ''}${income.amount ? parseFloat(income.amount).toFixed(2) : ''}` : '',
                'Income GHS Equivalent': income && income.ghsEquivalent ? parseFloat(income.ghsEquivalent).toFixed(2) : '',
                'Expenditure Payee & Details': expenditure ? `${expenditure.payeePayer || ''}${expenditure.payeePayer && expenditure.details ? ' - ' : ''}${expenditure.details || ''}` : '',
                'Expenditure Currency & Amount': expenditure ? `${expenditure.currency || ''}${expenditure.currency && expenditure.amount ? ': ' : ''}${expenditure.amount ? parseFloat(expenditure.amount).toFixed(2) : ''}` : '',
                'Expenditure GHS Equivalent': expenditure && expenditure.ghsEquivalent ? parseFloat(expenditure.ghsEquivalent).toFixed(2) : ''
            };
        });

        const ws = XLSX.utils.json_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, "Transactions");

        const statsData = categoryStats.map(stats => ({
            Category: stats.categoryName,
            'Total Income (GHS)': stats.totalIncome.toFixed(2),
            'Total Expenditure (GHS)': stats.totalExpenditure.toFixed(2)
        }));

        const statsWs = XLSX.utils.json_to_sheet(statsData);
        XLSX.utils.book_append_sheet(wb, statsWs, "Category Stats");

        let totalIncome = 0;
        let totalExpenditure = 0;
        transactionsGlobal.forEach(txn => {
            const ghsAmount = parseFloat(txn.ghsEquivalent) || 0;
            if (txn.transactionType === 'Receipt') {
                totalIncome += ghsAmount;
            } else {
                totalExpenditure += ghsAmount;
            }
        });
        const netBalance = totalIncome - totalExpenditure;
        const totalsData = [{
            'Total Income (GHS)': totalIncome.toFixed(2),
            'Total Expenditure (GHS)': totalExpenditure.toFixed(2),
            'Net Balance (GHS)': netBalance.toFixed(2)
        }];

        const totalsWs = XLSX.utils.json_to_sheet(totalsData);
        XLSX.utils.book_append_sheet(wb, totalsWs, "Totals");

        XLSX.writeFile(wb, "Cashbook_Report.xlsx");
    });
</script>