<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../../config.php');
include('../../includes/functions.php');

// Check database connection
/* if (mysqli_connect_errno()) {
    ob_clean();
    header('Content-Type: text/plain');
    echo "Error: Database connection failed: " . mysqli_connect_error();
    exit;
} */

// Sanitize and retrieve form data
$transactionDate = isset($_POST['transactionDate']) ? mysqli_real_escape_string($mysqli, $_POST['transactionDate']) : '';
$payeePayer = isset($_POST['payeePayer']) ? mysqli_real_escape_string($mysqli, $_POST['payeePayer']) : '';
$details = isset($_POST['details']) ? mysqli_real_escape_string($mysqli, $_POST['details']) : '';
$produce = isset($_POST['produce']) ? mysqli_real_escape_string($mysqli, $_POST['produce']) : '';
$invoiceNo = isset($_POST['invoiceNo']) ? mysqli_real_escape_string($mysqli, $_POST['invoiceNo']) : '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$transactionType = "Payment";
$nominalAccount = isset($_POST['nominalAccount']) ? mysqli_real_escape_string($mysqli, $_POST['nominalAccount']) : '';

// Server-side validation
if (empty($transactionDate) || empty($amount) || empty($nominalAccount)) {
    ob_clean();
    header('Content-Type: text/plain');
    echo "Error: All required fields (transactionDate, payeePayer, amount, nominalAccount) must be filled.";
    $mysqli->close();
    exit;
}

// Insert into the cashbook_transactions table
$insertQuery = "INSERT INTO `cashbook_transactions` (
    `transactionDate`, `payeePayer`, `details`, `produce`, `invoiceNo`, 
    `amount`, `ghsEquivalent`, `transactionType`, `nominalAccount`, `createdAt`, `updatedAt`
) VALUES (
    '$transactionDate', '$payeePayer', '$details', '$produce', '$invoiceNo',
    '$amount', '$amount','$transactionType', '$nominalAccount', NOW(), NOW()
)";

if ($mysqli->query($insertQuery)) {
    ob_clean();
    header('Content-Type: text/plain');
    echo "Success";
} else {
    ob_clean();
    header('Content-Type: text/plain');
    echo "Error: " . $mysqli->error;
}

$mysqli->close();