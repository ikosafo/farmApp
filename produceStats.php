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
                        <h5 class="font-weight-bolder mb-0">Produce Statistics</h5>
                        <div>
                            <button id="printStatsButton" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <button id="downloadExcelBtn" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i> Download Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="filterForm">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="produceFilter" class="form-label mb-1">Select Produce</label>
                                    <select id="produceFilter" class="form-control border-radius-md">
                                        <option value="">Select Produce</option>
                                        <?php
                                        $getProduce = $mysqli->query("SELECT * FROM `producelist` WHERE `prodStatus` = 1");
                                        $produceOptions = '';
                                        if ($getProduce && $getProduce->num_rows > 0) {
                                            while ($resProduce = $getProduce->fetch_assoc()) {
                                                $produceOptions .= '<option value="' . $resProduce['prodId'] . '">' . $resProduce['prodName'] . '</option>';
                                            }
                                            echo $produceOptions;
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="seasonFilter" class="form-label mb-1">Select Season</label>
                                    <select id="seasonFilter" class="form-control border-radius-md" disabled>
                                        <option value="">Select Season</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="totalsContainer" class="row mb-4"></div>
                        <div id="statsContainer" class="row mb-4"></div>
                        <div id="chartContainer" class="row mb-4"></div>
                        <div id="produceStatsTables" class="table-responsive table-container"></div>
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

    .toast, .toast-body, .deftxt {
        font-family: 'Poppins', sans-serif !important;
    }

    h3, h5 {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const produceFilter = document.getElementById('produceFilter');
    const seasonFilter = document.getElementById('seasonFilter');
    let transactionsGlobal = [];
    let categoryStats = [];
    let categoriesMap = {};
    let selectedProduceId = null;
    let selectedSeasonId = null;

    function showErrorToast(message) {
        const toastEl = document.getElementById('errorToast');
        toastEl.querySelector('.toast-body').textContent = message;
        new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    }

    function fetchCategories() {
        fetch('ajaxscripts/tables/produceStats.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `fetchCategories=true`
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
            if (Array.isArray(data.categories)) {
                data.categories.forEach(cat => {
                    categoriesMap[cat.catId] = cat.categoryName;
                });
            } else {
                showErrorToast('No categories found.');
            }
        })
        .catch(error => {
            showErrorToast(error.message);
        });
    }

    function fetchSeasons(produceId) {
        seasonFilter.disabled = true;
        seasonFilter.innerHTML = '<option value="">Select Season</option>';
        fetch('ajaxscripts/tables/produceStats.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `fetchSeasons=true&produceId=${encodeURIComponent(produceId)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error(data.error || 'Failed to load seasons');
            }
            seasonFilter.disabled = false;
            if (Array.isArray(data.seasons) && data.seasons.length > 0) {
                data.seasons.forEach(season => {
                    const option = document.createElement('option');
                    option.value = season.seasonId;
                    option.textContent = `${season.seasonName} (${season.startMonth} - ${season.endMonth})`;
                    seasonFilter.appendChild(option);
                });
            } else {
                seasonFilter.innerHTML = '<option value="">No Seasons Available</option>';
                showErrorToast('No seasons found for the selected produce.');
            }
        })
        .catch(error => {
            seasonFilter.innerHTML = '<option value="">No Seasons Available</option>';
            showErrorToast(error.message);
        });
    }

    produceFilter.addEventListener('change', function() {
        selectedProduceId = this.value;
        selectedSeasonId = null;
        seasonFilter.value = '';
        if (selectedProduceId) {
            fetchSeasons(selectedProduceId);
            loadProduceStats(selectedProduceId, null);
        } else {
            seasonFilter.disabled = true;
            seasonFilter.innerHTML = '<option value="">Select Season</option>';
            document.getElementById('produceStatsTables').innerHTML = '';
            document.getElementById('totalsContainer').innerHTML = '';
            document.getElementById('statsContainer').innerHTML = '';
            document.getElementById('chartContainer').innerHTML = '';
        }
    });

    seasonFilter.addEventListener('change', function() {
        selectedSeasonId = this.value;
        if (selectedProduceId) {
            loadProduceStats(selectedProduceId, selectedSeasonId);
        }
    });

    function loadProduceStats(produceId, seasonId) {
        const produceStatsTables = document.getElementById('produceStatsTables');
        produceStatsTables.innerHTML = `<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-lg"></i> Loading...</div>`;

        const body = seasonId 
            ? `produceId=${encodeURIComponent(produceId)}&seasonId=${encodeURIComponent(seasonId)}`
            : `produceId=${encodeURIComponent(produceId)}`;

        fetch('ajaxscripts/tables/produceStats.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: body
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

            transactionsGlobal = Array.isArray(data.data) ? data.data.map(row => ({
                ...row,
                transactionDate: row.transactionDate ? row.transactionDate.split(' ')[0] : '',
                details: `${row.details || ''} (${row.prodName || 'Unknown Produce'})`
            })) : [];
            categoryStats = Array.isArray(data.categoryStats) ? data.categoryStats : [];
            
            if (transactionsGlobal.length === 0) {
                showErrorToast('No transactions found for the selected criteria.');
            }
            if (categoryStats.length === 0) {
                showErrorToast('No category statistics available.');
            }

            renderTotals();
            renderStats();
            renderChart();
            renderTable(transactionsGlobal);
        })
        .catch(error => {
            produceStatsTables.innerHTML = `<div class="alert alert-danger text-center m-2">Failed to load data: ${error.message}. Please try again.</div>`;
            showErrorToast(error.message);
        });
    }

    function renderTotals() {
        const totalsContainer = document.getElementById('totalsContainer');
        let totalIncome = 0;
        let totalExpenditure = 0;

        if (Array.isArray(transactionsGlobal)) {
            transactionsGlobal.forEach(txn => {
                const ghsAmount = parseFloat(txn.ghsEquivalent) || 0;
                if (txn.transactionType === 'Receipt') {
                    totalIncome += ghsAmount;
                } else {
                    totalExpenditure += ghsAmount;
                }
            });
        }

        const netBalance = totalIncome - totalExpenditure;
        const netBalanceColor = netBalance >= 0 ? '#2ecc71' : '#e74c3c';

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

        if (Array.isArray(categoryStats)) {
            categoryStats.forEach(stats => {
                html += `
                    <div class="col-md-4 mb-3">
                        <div class="stats-card">
                            <h6>${stats.categoryName || 'Unknown'}</h6>
                            <p class="income" style="color: #2596be;">Income: ${(parseFloat(stats.totalIncome) || 0).toFixed(2)}</p>
                            <p class="expenditure" style="color: #e28743;">Expenditure: ${(parseFloat(stats.totalExpenditure) || 0).toFixed(2)}</p>
                        </div>
                    </div>`;
            });
        } else {
            html = '<div class="col-12 text-center deftxt">No category statistics available.</div>';
        }

        statsContainer.innerHTML = html;
    }

    let chartInstance = null;

    function renderChart() {
        const chartContainer = document.getElementById('chartContainer');
        if (!Array.isArray(categoryStats) || categoryStats.length === 0) {
            chartContainer.innerHTML = '<div class="text-center deftxt">No data available for the chart.</div>';
            return;
        }

        chartContainer.innerHTML = '<canvas id="categoryChart" height="100"></canvas>';

        const labels = categoryStats.map(s => s.categoryName || 'Unknown');
        const incomeData = categoryStats.map(s => parseFloat(s.totalIncome) || 0);
        const expenditureData = categoryStats.map(s => parseFloat(s.totalExpenditure) || 0);

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
                        text: 'Income vs Expenditure by Category for Selected Produce',
                        font: {
                            family: 'Poppins'
                        }
                    }
                }
            }
        });
    }

    function renderTable(data) {
        const groupedByDate = Array.isArray(data) ? data.reduce((acc, row) => {
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
        }, {}) : {};

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

        if (Object.keys(groupedByDate).length > 0) {
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
        } else {
            html += `<tr><td colspan="7" class="text-center">No transactions available.</td></tr>`;
        }

        html += `
                </tbody>
            </table>`;

        document.getElementById('produceStatsTables').innerHTML = html;
    }

    document.getElementById('printStatsButton').addEventListener('click', function() {
        let netBalance = 0;
        if (Array.isArray(transactionsGlobal)) {
            let totalIncome = 0, totalExpenditure = 0;
            transactionsGlobal.forEach(txn => {
                const ghsAmount = parseFloat(txn.ghsEquivalent) || 0;
                if (txn.transactionType === 'Receipt') totalIncome += ghsAmount;
                else totalExpenditure += ghsAmount;
            });
            netBalance = totalIncome - totalExpenditure;
        }

        const totalsContent = document.getElementById('totalsContainer').innerHTML;
        const statsContent = document.getElementById('statsContainer').innerHTML;
        const chartContent = document.getElementById('chartContainer').innerHTML;
        const tableContent = document.getElementById('produceStatsTables').innerHTML;
        const selectedProduce = document.getElementById('produceFilter').options[document.getElementById('produceFilter').selectedIndex].text;
        const selectedSeason = selectedSeasonId ? document.getElementById('seasonFilter').options[document.getElementById('seasonFilter').selectedIndex].text : '';
        const title = selectedSeason ? `Produce Statistics - ${selectedProduce} (${selectedSeason})` : `Produce Statistics - ${selectedProduce}`;
        const newWin = window.open('', '', 'width=900,height=700');
        newWin.document.write(`
            <html>
            <head>
                <title>${title}</title>
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
                <h3>${title}</h3>
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
        const selectedProduce = document.getElementById('produceFilter').options[document.getElementById('produceFilter').selectedIndex].text;
        const selectedSeason = selectedSeasonId ? document.getElementById('seasonFilter').options[document.getElementById('seasonFilter').selectedIndex].text : '';
        const fileName = selectedSeason ? `Produce_Stats_${selectedProduce}_${selectedSeason}.xlsx` : `Produce_Stats_${selectedProduce}.xlsx`;

        const groupedByDate = Array.isArray(transactionsGlobal) ? transactionsGlobal.reduce((acc, row) => {
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
        }, {}) : {};

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

        const ws = XLSX.utils.json_to_sheet(wsData.length > 0 ? wsData : [{}]);
        XLSX.utils.book_append_sheet(wb, ws, "Transactions");

        const statsData = Array.isArray(categoryStats) ? categoryStats.map(stats => ({
            Category: stats.categoryName || 'Unknown',
            'Total Income (GHS)': (parseFloat(stats.totalIncome) || 0).toFixed(2),
            'Total Expenditure (GHS)': (parseFloat(stats.totalExpenditure) || 0).toFixed(2)
        })) : [];

        const statsWs = XLSX.utils.json_to_sheet(statsData.length > 0 ? statsData : [{}]);
        XLSX.utils.book_append_sheet(wb, statsWs, "Category Stats");

        let totalIncome = 0;
        let totalExpenditure = 0;
        if (Array.isArray(transactionsGlobal)) {
            transactionsGlobal.forEach(txn => {
                const ghsAmount = parseFloat(txn.ghsEquivalent) || 0;
                if (txn.transactionType === 'Receipt') {
                    totalIncome += ghsAmount;
                } else {
                    totalExpenditure += ghsAmount;
                }
            });
        }
        const netBalance = totalIncome - totalExpenditure;
        const totalsData = [{
            'Total Income (GHS)': totalIncome.toFixed(2),
            'Total Expenditure (GHS)': totalExpenditure.toFixed(2),
            'Net Balance (GHS)': netBalance.toFixed(2)
        }];

        const totalsWs = XLSX.utils.json_to_sheet(totalsData);
        XLSX.utils.book_append_sheet(wb, totalsWs, "Totals");

        XLSX.writeFile(wb, fileName);
    });

    // Initialize categories on page load
    fetchCategories();
</script>