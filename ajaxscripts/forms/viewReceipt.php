<?php
include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$getInc = $mysqli->query("SELECT * FROM `cashbook_transactions` WHERE transactionId = '$i_id'");
$resInc = $getInc->fetch_assoc();

if (!$resInc) {
    echo "<div class='alert alert-danger'>No transaction found with ID: $i_id</div>";
    exit;
}
?>

<style>
/* Custom Error Alert Styling */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Styling for detail display */
.detail-label {
    font-size: 0.875rem;
    color: #495057;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1rem;
    color: #212529;
    word-break: break-word;
    font-weight: 800;
}

.detail-value.multiline {
    white-space: pre-wrap; /* Preserve newlines for details field */
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="font-weight-bolder mb-0">View Receipt Details</h5>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="detail-label">Date</div>
        <span class="detail-value"><?= $resInc['transactionDate'] != '' ? htmlspecialchars($resInc['transactionDate']) : '-' ?></span>
    </div>
    <div class="col-12 col-md-6">
        <div class="detail-label">Payee</div>
        <span class="detail-value"><?= $resInc['payeePayer'] != '' ? htmlspecialchars($resInc['payeePayer']) : '-' ?></span>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="detail-label">Details</div>
        <p class="detail-value multiline"><?= $resInc['details'] != '' ? htmlspecialchars($resInc['details']) : '-' ?></p>
    </div>
    <div class="col-12 col-md-6">
        <div class="detail-label">Produce</div>
        <span class="detail-value"><?= ($produce = produceName($resInc['produce'])) != '' ? htmlspecialchars($produce) : '-' ?></span>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="detail-label">Invoice No.</div>
        <span class="detail-value"><?= $resInc['invoiceNo'] != '' ? htmlspecialchars($resInc['invoiceNo']) : '-' ?></span>
    </div>
    <div class="col-12 col-md-6">
        <div class="detail-label">Nominal Account</div>
        <span class="detail-value"><?= ($category = categoryName($resInc['nominalAccount'])) != '' ? htmlspecialchars($category) : '-' ?></span>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="detail-label">Currency</div>
        <span class="detail-value"><?= $resInc['currency'] != '' ? htmlspecialchars($resInc['currency']) : '-' ?></span>
    </div>
    <div class="col-12 col-md-6">
        <div class="detail-label">Amount</div>
        <span class="detail-value"><?= $resInc['amount'] != '' ? htmlspecialchars(number_format($resInc['amount'], 2)) : '-' ?></span>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="detail-label">Exchange Rate</div>
        <span class="detail-value"><?= $resInc['exchangeRate'] != '' ? htmlspecialchars(number_format($resInc['exchangeRate'], 2)) : '-' ?></span>
    </div>
    <div class="col-12 col-md-6">
        <div class="detail-label">GHS Equivalent</div>
        <span class="detail-value"><?= $resInc['ghsEquivalent'] != '' ? htmlspecialchars(number_format($resInc['ghsEquivalent'], 2)) : '-' ?></span>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="detail-label">Transaction Type</div>
        <span class="detail-value"><?= $resInc['transactionType'] != '' ? htmlspecialchars($resInc['transactionType']) : '-' ?></span>
    </div>
    <div class="col-12 col-md-6">
        <div class="detail-label">Updated At</div>
        <span class="detail-value"><?= $resInc['updatedAt'] != '' ? htmlspecialchars($resInc['updatedAt']) : '-' ?></span>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
</div>
