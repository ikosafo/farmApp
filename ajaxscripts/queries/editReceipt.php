<?php
include('../../config.php');
include('../../includes/functions.php');

// Retrieve and sanitize form data
$transactionId = isset($_POST['transactionId']) ? $_POST['transactionId'] : '';
$transactionDate = isset($_POST['transactionDate']) ? $_POST['transactionDate'] : '';
$payeePayer = isset($_POST['payeePayer']) ? $_POST['payeePayer'] : '';
$details = isset($_POST['details']) ? $_POST['details'] : '';
$produce = isset($_POST['produce']) ? $_POST['produce'] : '';
$invoiceNo = isset($_POST['invoiceNo']) ? $_POST['invoiceNo'] : '';
$currency = isset($_POST['currency']) ? $_POST['currency'] : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$exchangeRate = isset($_POST['exchangeRate']) ? floatval($_POST['exchangeRate']) : 1.0;
$ghsEquivalent = isset($_POST['ghsEquivalent']) ? floatval($_POST['ghsEquivalent']) : 0;
$transactionType = "Receipt"; 
$nominalAccount = isset($_POST['nominalAccount']) ? $_POST['nominalAccount'] : '';

// Server-side validation
if (empty($transactionDate) || empty($payeePayer) || empty($currency) || empty($amount) || empty($nominalAccount) || empty($transactionId)) {
    echo "Error: All required fields must be filled.";
    $mysqli->close();
    exit;
}

// Update the cashbook_transactions table using a prepared statement
$stmt = $mysqli->prepare("UPDATE `cashbook_transactions` SET 
    `transactionDate` = ?, 
    `payeePayer` = ?, 
    `details` = ?, 
    `produce` = ?, 
    `invoiceNo` = ?, 
    `currency` = ?, 
    `amount` = ?, 
    `exchangeRate` = ?, 
    `ghsEquivalent` = ?, 
    `transactionType` = ?, 
    `nominalAccount` = ?, 
    `updatedAt` = NOW() 
    WHERE `transactionId` = ?");

if (!$stmt) {
    echo "Error: " . $mysqli->error;
    $mysqli->close();
    exit;
}

// Bind parameters (ssssssssssss: 11 strings, 1 float, 1 string for transactionId)
$stmt->bind_param('ssssssssdsss', 
    $transactionDate, 
    $payeePayer, 
    $details, 
    $produce, 
    $invoiceNo, 
    $currency, 
    $amount, 
    $exchangeRate, 
    $ghsEquivalent, 
    $transactionType, 
    $nominalAccount, 
    $transactionId
);

// Execute the query
if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>