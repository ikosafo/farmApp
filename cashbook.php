<?php include('./includes/sidebar.php');

function produceName($id) {
    global $mysqli;
    $getProd = $mysqli->query("SELECT `prodName` FROM `producelist` WHERE `prodId` = '$id'");
    if ($getProd && $getProd->num_rows > 0) {
        $resProd = $getProd->fetch_assoc();
        return $resProd['prodName'];
    }
    return null;
}
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
                            <button id="printCashbook" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <button id="downloadExcelBtn" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i> Download Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="startDate" class="form-label mb-1">Start Date</label>
                                <input id="startDate" class="form-control border-radius-md" type="text" placeholder="Select start date" required>
                            </div>
                            <div class="col-md-3">
                                <label for="endDate" class="form-label mb-1">End Date</label>
                                <input id="endDate" class="form-control border-radius-md" type="text" placeholder="Select end date" required>
                            </div>
                            <div class="col-md-3">
                                <label for="cashBalance" class="form-label mb-1">Cash Balance</label>
                                <input id="cashBalance" class="form-control border-radius-md" type="number" placeholder="Enter cash balance" step="0.01" required>
                            </div>
                            <div class="col-md-3 mt-4 d-flex align-items-end">
                                <button id="searchCashbook" class="btn bg-gradient-primary w-100">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                            </div>
                        </div>

                        <div id="filterContainer" class="row mb-4" style="display: none;">
                            <div class="col-md-4">
                                <label for="nominalAccountFilter" class="form-label mb-1">Nominal Account</label>
                                <select id="nominalAccountFilter" class="form-control border-radius-md">
                                    <option value="">Select All</option>
                                </select>
                            </div>
                        </div>

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
    .accounting-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        margin-top: 20px;
    }

    .accounting-table th,
    .accounting-table td {
        border: 1px solid #dee2e6;
        padding: 10px;
        text-align: left;
        vertical-align: top;
    }

    .accounting-table thead th {
        background-color: #f1f3f5;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.8rem;
        text-align: center;
    }

    .table-container {
        overflow-x: auto;
    }
</style>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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

let transactionsGlobal = [];

function showErrorToast(message) {
    const toastEl = document.getElementById('errorToast');
    toastEl.querySelector('.toast-body').textContent = message;
    new bootstrap.Toast(toastEl, { delay: 5000 }).show();
}

function formatDate(date) {
    return date.toISOString().split('T')[0];
}

document.getElementById('searchCashbook').addEventListener('click', function() {
    const startDate = startDatePicker.selectedDates[0];
    const endDate = endDatePicker.selectedDates[0];
    const cashBalance = document.getElementById('cashBalance').value;

    if (!startDate || !endDate) {
        return showErrorToast("Please select both start and end dates.");
    }

    if (!cashBalance) {
        return showErrorToast("Please enter a cash balance.");
    }

    if (endDate < startDate) {
        return showErrorToast("End date cannot be before start date.");
    }

    loadCashbookTables(startDate, endDate, cashBalance);
});

function loadCashbookTables(startDate, endDate, cashBalance) {
    const cashbookTables = document.getElementById('cashbookTables');
    cashbookTables.innerHTML = `<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>`;

    const formattedStart = formatDate(startDate);
    const formattedEnd = formatDate(endDate);

    fetch('ajaxscripts/tables/cashbook.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `startDate=${formattedStart}&endDate=${formattedEnd}`
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

        transactionsGlobal = data.data;
        renderTable(transactionsGlobal, cashBalance);
        populateNominalAccountFilter(data.categories);
        document.getElementById('filterContainer').style.display = 'block';
    })
    .catch(error => {
        cashbookTables.innerHTML = `<div class="alert alert-danger text-center m-3">Failed to load data: ${error.message}. Please try again.</div>`;
        showErrorToast(error.message);
    });
}

function populateNominalAccountFilter(categories) {
    const nominalSelect = document.getElementById('nominalAccountFilter');
    nominalSelect.innerHTML = '<option value="">Select All</option>' + 
        categories.map(cat => `<option value="${cat.categoryName}">${cat.categoryName}</option>`).join('');

    nominalSelect.addEventListener('change', function() {
        const categoryName = this.value;
        const cashBalance = document.getElementById('cashBalance').value;
        filterTable(categoryName, cashBalance);
    });
}

function filterTable(categoryName, cashBalance) {
    let filteredData = transactionsGlobal;
    if (categoryName) {
        filteredData = transactionsGlobal.filter(t => t.nominalAccount === categoryName);
    }
    renderTable(filteredData, cashBalance);
}

function renderTable(data, cashBalance) {
    let runningBalance = parseFloat(cashBalance) || 0;
    let html = `
        <table class="accounting-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Payee/Payer</th>
                    <th>Details</th>
                    <th>Produce</th>
                    <th>Invoice No</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Exchange Rate</th>
                    <th>GHS Equivalent</th>
                    <th>Transaction Type</th>
                    <th>Nominal Account</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>`;

    data.forEach(row => {
        const ghsAmount = parseFloat(row.ghsEquivalent) || 0;
        runningBalance += row.transactionType === 'Receipt' ? ghsAmount : -ghsAmount;

        html += `
            <tr>
                <td>${row.transactionDate || ''}</td>
                <td>${row.payeePayer || ''}</td>
                <td>${row.details || ''}</td>
                <td>${row.produce || ''}</td>
                <td>${row.invoiceNo || ''}</td>
                <td>${row.currency || ''}</td>
                <td>${row.amount ? parseFloat(row.amount).toFixed(2) : ''}</td>
                <td>${row.exchangeRate ? parseFloat(row.exchangeRate).toFixed(4) : ''}</td>
                <td>${row.ghsEquivalent ? parseFloat(row.ghsEquivalent).toFixed(2) : ''}</td>
                <td>${row.transactionType || ''}</td>
                <td>${row.nominalAccount || ''}</td>
                <td>${runningBalance.toFixed(2)}</td>
            </tr>`;
    });

    html += `
            <tr style="font-weight: bold; background: #f8f9fa;">
                <td colspan="11" class="text-end">Closing Balance:</td>
                <td>${runningBalance.toFixed(2)}</td>
            </tr>
        </tbody>
        </table>`;

    document.getElementById('cashbookTables').innerHTML = html;
}

document.getElementById('printCashbook').addEventListener('click', function() {
    const printContents = document.getElementById('cashbookTables').innerHTML;
    const newWin = window.open('', '', 'width=900,height=700');
    newWin.document.write(`
        <html>
        <head>
            <title>Cashbook Report</title>
            <style>
                body { font-family: Arial; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                th { background: #f1f1f1; }
            </style>
        </head>
        <body>
            <h3>Cashbook Report</h3>
            ${printContents}
        </body>
        </html>
    `);
    newWin.document.close();
    newWin.print();
});

document.getElementById('downloadExcelBtn').addEventListener('click', function() {
    const wb = XLSX.utils.book_new();
    const wsData = transactionsGlobal.map(row => ({
        Date: row.transactionDate,
        'Payee/Payer': row.payeePayer,
        Details: row.details,
        Produce: row.produce,
        'Invoice No': row.invoiceNo,
        Currency: row.currency,
        Amount: parseFloat(row.amount).toFixed(2),
        'Exchange Rate': parseFloat(row.exchangeRate).toFixed(4),
        'GHS Equivalent': parseFloat(row.ghsEquivalent).toFixed(2),
        'Transaction Type': row.transactionType,
        'Nominal Account': row.nominalAccount,
        Balance: parseFloat(row.balance).toFixed(2)
    }));

    const ws = XLSX.utils.json_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, "Cashbook");
    XLSX.writeFile(wb, "Cashbook_Report.xlsx");
});
</script>