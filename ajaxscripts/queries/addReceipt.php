<?php
include('../../config.php');
include('../../includes/functions.php');

// Sanitize and retrieve form data
$transactionDate = isset($_POST['transactionDate']) ? mysqli_real_escape_string($mysqli, $_POST['transactionDate']) : '';
$payeePayer = isset($_POST['payeePayer']) ? mysqli_real_escape_string($mysqli, $_POST['payeePayer']) : '';
$details = isset($_POST['details']) ? mysqli_real_escape_string($mysqli, $_POST['details']) : '';
$produce = isset($_POST['produce']) ? mysqli_real_escape_string($mysqli, $_POST['produce']) : '';
$invoiceNo = isset($_POST['invoiceNo']) ? mysqli_real_escape_string($mysqli, $_POST['invoiceNo']) : '';
$currency = isset($_POST['currency']) ? mysqli_real_escape_string($mysqli, $_POST['currency']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$exchangeRate = isset($_POST['exchangeRate']) ? floatval($_POST['exchangeRate']) : 1.0;
$ghsEquivalent = isset($_POST['ghsEquivalent']) ? floatval($_POST['ghsEquivalent']) : 0;
$transactionType = "Receipt"; // Hardcoded for income
$nominalAccount = isset($_POST['nominalAccount']) ? mysqli_real_escape_string($mysqli, $_POST['nominalAccount']) : '';

// Server-side validation
if (empty($transactionDate) || empty($payeePayer) || empty($currency) || empty($amount) || empty($nominalAccount)) {
    echo "Error: All required fields must be filled.";
    $mysqli->close();
    exit;
}

// Insert into the cashbook_transactions table
$insertQuery = "INSERT INTO `cashbook_transactions` (
    `transactionDate`, `payeePayer`, `details`, `produce`, `invoiceNo`, 
    `currency`, `amount`, `exchangeRate`, `ghsEquivalent`, `transactionType`, 
    `nominalAccount`, `createdAt`, `updatedAt`
) VALUES (
    '$transactionDate', '$payeePayer', '$details', '$produce', '$invoiceNo',
    '$currency', $amount, $exchangeRate, $ghsEquivalent, '$transactionType',
    '$nominalAccount', NOW(), NOW()
)";

if ($mysqli->query($insertQuery)) {
    echo "Success";
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>