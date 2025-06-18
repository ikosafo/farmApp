<?php
include('../../config.php');
$theindex = isset($_POST['i_index']) ? $_POST['i_index'] : '';
if ($theindex) {
    $query = $mysqli->query("SELECT * FROM cashbook_transactions WHERE id = '$theindex'");
    $transaction = $query->fetch_assoc();
    if ($transaction) {
?>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">Date</label>
                <p class="form-control-static"><?php echo $transaction['transactionDate']; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Payee/Payer</label>
                <p class="form-control-static"><?php echo $transaction['payeePayer']; ?></p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">Details</label>
                <p class="form-control-static"><?php echo $transaction['details']; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Produce</label>
                <p class="form-control-static"><?php echo $transaction['produce'] ?: 'N/A'; ?></p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">Invoice Number</label>
                <p class="form-control-static"><?php echo $transaction['invoiceNo'] ?: 'N/A'; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Currency</label>
                <p class="form-control-static"><?php echo $transaction['currency']; ?></p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">Amount</label>
                <p class="form-control-static"><?php echo $transaction['amount']; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Exchange Rate</label>
                <p class="form-control-static"><?php echo $transaction['exchangeRate'] ?: 'N/A'; ?></p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">GHS Equivalent</label>
                <p class="form-control-static"><?php echo $transaction['ghsEquivalent'] ?: 'N/A'; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Transaction Type</label>
                <p class="form-control-static"><?php echo $transaction['transactionType']; ?></p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">Nominal Account</label>
                <p class="form-control-static"><?php echo $transaction['nominalAccount'] ?: 'N/A'; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Receipt</label>
                <p class="form-control-static"><?php echo $transaction['receipt'] ?: 'N/A'; ?></p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6">
                <label class="form-label">Payment</label>
                <p class="form-control-static"><?php echo $transaction['payment'] ?: 'N/A'; ?></p>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Balance</label>
                <p class="form-control-static"><?php echo $transaction['balance'] ?: 'N/A'; ?></p>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        </div>
<?php
    } else {
        echo '<p class="text-danger">Transaction not found.</p>';
    }
} else {
    echo '<p class="text-danger">Invalid transaction ID.</p>';
}
?>