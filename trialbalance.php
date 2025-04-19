<?php include('./includes/sidebar.php'); ?>

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <?php include('./includes/header.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-radius-xl p-4 mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bolder mb-0">Trial Balance</h5>
                        <div>
                            <button id="printTrialbalance" class="btn btn-sm btn-outline-secondary me-2" disabled>
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <button id="downloadExcelBtn" class="btn btn-sm btn-outline-success" disabled>
                                <i class="fas fa-file-excel me-1"></i> Download Excel
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="endDate" class="form-label mb-1">As at Date</label>
                                <input id="endDate" class="form-control border-radius-md" type="text" placeholder="Select date" required>
                            </div>
                            <div class="col-md-2 mt-4 d-flex align-items-end">
                                <button id="searchTrialbalance" class="btn bg-gradient-primary w-100">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                            </div>
                        </div>

                        <div id="trialbalanceTables" class="table-responsive table-container"></div>
                        <div id="trialbalanceSummary" class="mt-4"></div>
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

    .accounting-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-container {
        overflow-x: auto;
    }

    .total-row {
        background-color: #e9ecef;
        font-weight: bold;
    }

    .balance-status-balanced {
        color: #28a745;
        font-weight: bold;
    }

    .balance-status-unbalanced {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    $("#endDate").flatpickr({
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

    let categoriesGlobal = [];

    $("#searchTrialbalance").click(function () {
        let endDate = $("#endDate").val();

        if (!endDate) return showErrorToast("Please select a date.");

        loadTrialbalanceTable(new Date(endDate));
    });

    function loadTrialbalanceTable(endDate) {
        $("#trialbalanceTables").html(`<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i> Loading...</div>`);
        $("#trialbalanceSummary").html('');

        let formattedEnd = formatDate(endDate);

        $.ajax({
            url: "ajaxscripts/tables/trialbalance.php",
            type: "POST",
            data: { endDate: formattedEnd },
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    $("#trialbalanceTables").html(`<div class="alert alert-danger text-center m-3">${response.error}</div>`);
                    showErrorToast(response.error);
                    return;
                }
                categoriesGlobal = response.data || [];
                console.log("Received data:", categoriesGlobal); // Debug: Log the response
                renderTrialbalanceTable(categoriesGlobal, formattedEnd);
                $("#printTrialbalance, #downloadExcelBtn").prop("disabled", false);
            },
            error: function (xhr, status, error) {
                let errorMsg = "Failed to load data. ";
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg += xhr.responseJSON.error;
                } else {
                    errorMsg += "Server error: " + (xhr.statusText || error);
                }
                $("#trialbalanceTables").html(`<div class="alert alert-danger text-center m-3">${errorMsg}</div>`);
                showErrorToast(errorMsg);
                console.error("AJAX Error:", xhr, status, error);
            }
        });
    }

    function renderTrialbalanceTable(categories, endDate) {
        if (!categories || categories.length === 0) {
            $("#trialbalanceTables").html(`<div class="alert alert-info text-center m-3">No data found for the selected date.</div>`);
            $("#trialbalanceSummary").html('');
            return;
        }

        let debitTotal = 0, creditTotal = 0;

        let html = `
        <table class="accounting-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Type</th>
                    <th class="text-end">Debit (GHS)</th>
                    <th class="text-end">Credit (GHS)</th>
                </tr>
            </thead>
            <tbody>`;

        categories.forEach(category => {
            let debit = parseFloat(category.debit) || 0;
            let credit = parseFloat(category.credit) || 0;
            debitTotal += debit;
            creditTotal += credit;

            html += `
            <tr>
                <td>${category.transactionCategory || 'Uncategorized'}</td>
                <td>${category.accountType || 'Unknown'}</td>
                <td class="text-end">${debit > 0 ? debit.toFixed(2) : ''}</td>
                <td class="text-end">${credit > 0 ? credit.toFixed(2) : ''}</td>
            </tr>`;
        });

        html += `
            <tr class="total-row">
                <td colspan="2" class="text-end">Total:</td>
                <td class="text-end">${debitTotal.toFixed(2)}</td>
                <td class="text-end">${creditTotal.toFixed(2)}</td>
            </tr>
            </tbody>
        </table>`;

        $("#trialbalanceTables").html(html);

        // Render summary
        let balanceStatus = Math.abs(debitTotal - creditTotal) < 0.01 ? 'Balanced' : 'Unbalanced';
        let balanceClass = balanceStatus === 'Balanced' ? 'balance-status-balanced' : 'balance-status-unbalanced';
        let summaryHtml = `
            <div class="p-3 bg-light border rounded">
                <h6>Trial Balance Summary</h6>
                <p><strong>As at:</strong> ${$("#endDate").val()}</p>
                <p><strong>Total Debits:</strong> GHS ${debitTotal.toFixed(2)}</p>
                <p><strong>Total Credits:</strong> GHS ${creditTotal.toFixed(2)}</p>
                <p><strong>Balance Status:</strong> <span class="${balanceClass}">${balanceStatus}</span></p>
                ${balanceStatus === 'Unbalanced' ? '<p class="text-danger">Note: Debits and credits do not balance. Please review transactions for errors.</p>' : ''}
            </div>
        `;
        $("#trialbalanceSummary").html(summaryHtml);
    }

    $("#printTrialbalance").click(function () {
        let printContents = document.getElementById("trialbalanceTables").innerHTML;
        let summaryContents = document.getElementById("trialbalanceSummary").innerHTML;
        let newWin = window.open('', '', 'width=900,height=700');
        newWin.document.write('<html><head><title>Trial Balance Report</title>');
        newWin.document.write('<style>body{font-family:Arial;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #000;padding:8px;text-align:left;} th{background:#f1f1f1;} .text-end{text-align:right;} .balance-status-balanced{color:#28a745;} .balance-status-unbalanced{color:#dc3545;} .bg-light{background:#f8f9fa;padding:15px;border-radius:5px;}</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<h3>Trial Balance as at ' + $("#endDate").val() + '</h3>');
        newWin.document.write(printContents);
        newWin.document.write('<br>' + summaryContents);
        newWin.document.write('</body></html>');
        newWin.document.close();
        newWin.print();
    });

    $("#downloadExcelBtn").click(function () {
        const wb = XLSX.utils.book_new();
        const sheetData = categoriesGlobal.map(r => ({
            'Category': r.transactionCategory || 'Uncategorized',
            'Type': r.accountType || 'Unknown',
            'Debit (GHS)': r.debit ? parseFloat(r.debit).toFixed(2) : '',
            'Credit (GHS)': r.credit ? parseFloat(r.credit).toFixed(2) : ''
        }));

        sheetData.push({
            'Category': 'Total',
            'Type': '',
            'Debit (GHS)': categoriesGlobal.reduce((sum, r) => sum + (parseFloat(r.debit) || 0), 0).toFixed(2),
            'Credit (GHS)': categoriesGlobal.reduce((sum, r) => sum + (parseFloat(r.credit) || 0), 0).toFixed(2)
        });

        const ws = XLSX.utils.json_to_sheet(sheetData);
        XLSX.utils.book_append_sheet(wb, ws, "Trial Balance");
        XLSX.writeFile(wb, `Trial_Balance_${$("#endDate").val()}.xlsx`);
    });
</script>