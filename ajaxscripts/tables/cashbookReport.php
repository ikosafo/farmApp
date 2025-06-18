<div class="table-responsive">
    <table class="table table-hover align-items-center mb-0" id="cashbookReportTable">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Date</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Payee/Payer</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Details</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Currency</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Amount</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">GHS Equivalent</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Transaction Type</th>
                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Balance</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    var rTable = $('#cashbookReportTable').DataTable({
        stateSave: true,
        lengthChange: false,
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'ajaxscripts/paginations/cashbookReport.php'
        },
        columns: [
            { data: 'transactionDate', className: 'text-sm' },
            { data: 'payeePayer', className: 'text-sm' },
            { data: 'details', className: 'text-sm' },
            { data: 'currency', className: 'text-sm' },
            { data: 'amount', className: 'text-sm' },
            { data: 'ghsEquivalent', className: 'text-sm' },
            { data: 'transactionType', className: 'text-sm' },
            { data: 'balance', className: 'text-sm' },
        ],
        language: {
            emptyTable: "No transactions found",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
        }
    });
</script>