<?php include('./includes/sidebar.php'); ?>

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
                            <div class="col-md-2 mt-4 d-flex align-items-end">
                                <button id="searchCashbook" class="btn bg-gradient-primary w-100">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
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
    $("#startDate, #endDate").flatpickr({
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        maxDate: "today"
    });

    function showErrorToast(message) {
        const toastEl = document.getElementById('errorToast');
        toastEl.querySelector('.toast-body').textContent = message;
        new bootstrap.Toast(toastEl, { delay: 5000 }).show();
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    let incomeGlobal = [];
    let expenseGlobal = [];

    $("#searchCashbook").click(function () {
        let startDate = $("#startDate").val();
        let endDate = $("#endDate").val();

        if (!startDate || !endDate) return showErrorToast("Please select both start and end dates.");

        let start = new Date(startDate);
        let end = new Date(endDate);
        if (end < start) return showErrorToast("End date cannot be before start date.");

        loadCashbookTables(start, end);
    });

    function loadCashbookTables(startDate, endDate) {
        $("#cashbookTables").html(`<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>`);

        let formattedStart = formatDate(startDate);
        let formattedEnd = formatDate(endDate);

        let incomeReq = $.post("ajaxscripts/tables/cashbook.php", {
            transactionType: "Income",
            startDate: formattedStart,
            endDate: formattedEnd
        });

        let expenseReq = $.post("ajaxscripts/tables/cashbook.php", {
            transactionType: "Expenditure",
            startDate: formattedStart,
            endDate: formattedEnd
        });

        $.when(incomeReq, expenseReq).done(function (incomeRes, expenseRes) {
            let income = incomeRes[0].data || [];
            let expense = expenseRes[0].data || [];
            incomeGlobal = income;
            expenseGlobal = expense;
            renderCombinedTable(income, expense);
        }).fail(function () {
            $("#cashbookTables").html(`<div class="alert alert-danger text-center m-3">Failed to load data. Please try again.</div>`);
            showErrorToast("Failed to load data.");
        });
    }

    function renderCombinedTable(income, expense) {
        let maxRows = Math.max(income.length, expense.length);
        let incomeTotal = 0, expenseTotal = 0;

        let html = `
        <table class="accounting-table">
            <thead>
                <tr>
                    <th colspan="3">Income</th>
                    <th colspan="3">Expenditure</th>
                </tr>
                <tr>
                    <th>Entry</th><th>Date</th><th>Amount</th>
                    <th>Entry</th><th>Date</th><th>Amount</th>
                </tr>
            </thead>
            <tbody>`;

        for (let i = 0; i < maxRows; i++) {
            const incomeRow = income[i] || {};
            const expenseRow = expense[i] || {};

            let incomeAmt = parseFloat(incomeRow.transactionAmount || 0);
            let expenseAmt = parseFloat(expenseRow.transactionAmount || 0);
            incomeTotal += incomeAmt;
            expenseTotal += expenseAmt;

            html += `
            <tr>
                <td>${incomeRow.transactionName || ''}</td>
                <td>${incomeRow.transactionDate || ''}</td>
                <td class="text-end">${incomeRow.transactionAmount ? incomeAmt.toFixed(2) : ''}</td>
                <td>${expenseRow.transactionName || ''}</td>
                <td>${expenseRow.transactionDate || ''}</td>
                <td class="text-end">${expenseRow.transactionAmount ? expenseAmt.toFixed(2) : ''}</td>
            </tr>`;
        }

        html += `
            <tr style="font-weight: bold; background: #f8f9fa;">
                <td colspan="2" class="text-end">Total Income:</td>
                <td class="text-end">GHS ${incomeTotal.toFixed(2)}</td>
                <td colspan="2" class="text-end">Total Expenditure:</td>
                <td class="text-end">GHS ${expenseTotal.toFixed(2)}</td>
            </tr>
            </tbody>
        </table>`;

        $("#cashbookTables").html(html);
    }

    // Print Functionality
    $("#printCashbook").click(function () {
        let printContents = document.getElementById("cashbookTables").innerHTML;
        let newWin = window.open('', '', 'width=900,height=700');
        newWin.document.write('<html><head><title>Cashbook Report</title>');
        newWin.document.write('<style>body{font-family:Arial;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #000;padding:8px;text-align:left;} th{background:#f1f1f1;}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<h3>Cashbook Report</h3>');
        newWin.document.write(printContents);
        newWin.document.write('</body></html>');
        newWin.document.close();
        newWin.print();
    });

    // Excel Download Functionality
    $("#downloadExcelBtn").click(function () {
        const wb = XLSX.utils.book_new();

        const incomeSheet = XLSX.utils.json_to_sheet(incomeGlobal.map(r => ({
            Entry: r.transactionName,
            Date: r.transactionDate,
            Amount: parseFloat(r.transactionAmount).toFixed(2)
        })));

        const expenseSheet = XLSX.utils.json_to_sheet(expenseGlobal.map(r => ({
            Entry: r.transactionName,
            Date: r.transactionDate,
            Amount: parseFloat(r.transactionAmount).toFixed(2)
        })));

        XLSX.utils.book_append_sheet(wb, incomeSheet, "Income");
        XLSX.utils.book_append_sheet(wb, expenseSheet, "Expenditure");

        XLSX.writeFile(wb, "Cashbook_Report.xlsx");
    });
</script>
