<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$transactionName = mysqli_real_escape_string($mysqli, $_POST['transactionName']);
$transactionDescription = mysqli_real_escape_string($mysqli, $_POST['transactionDescription']);
$transactionCategory = mysqli_real_escape_string($mysqli, $_POST['transactionCategory']);
$transactionAmount = mysqli_real_escape_string($mysqli, $_POST['transactionAmount']);
$transactionDate = mysqli_real_escape_string($mysqli, $_POST['transactionDate']);
$transactionReceipt = mysqli_real_escape_string($mysqli, $_POST['transactionReceipt']);
$transIndex = mysqli_real_escape_string($mysqli, $_POST['transIndex']); 

// Update the transactions table based on transId
$updateQuery = "UPDATE `transactions` SET 
                `transactionName` = '$transactionName',
                `transactionDescription` = '$transactionDescription',
                `transactionCategory` = '$transactionCategory',
                `transactionAmount` = '$transactionAmount',
                `transactionDate` = '$transactionDate',
                `transactionReceipt` = '$transactionReceipt'
                WHERE `transId` = '$transIndex'";

if ($mysqli->query($updateQuery)) {
    // Query executed successfully
    echo "Success";
} else {
    // Error occurred
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
