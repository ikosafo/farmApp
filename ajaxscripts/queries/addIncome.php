<?php
include('../../config.php');
include('../../includes/functions.php');

$transactionName = mysqli_real_escape_string($mysqli, $_POST['transactionName']);
$transactionDescription = mysqli_real_escape_string($mysqli, $_POST['transactionDescription']);
$transactionCategory = mysqli_real_escape_string($mysqli, $_POST['transactionCategory']);
$transactionAmount = mysqli_real_escape_string($mysqli, $_POST['transactionAmount']);
$transactionDate = mysqli_real_escape_string($mysqli, $_POST['transactionDate']);
$transactionReceipt = mysqli_real_escape_string($mysqli, $_POST['transactionReceipt']);

// Insert into the transactions table
$insertQuery = "INSERT INTO `transactions` 
                (`transactionName`, `transactionDescription`, `transactionCategory`, `transactionAmount`, `transactionDate`, `transactionReceipt`, `transactionType`)
                VALUES ('$transactionName', '$transactionDescription', '$transactionCategory', '$transactionAmount', '$transactionDate', '$transactionReceipt','Income')";

if ($mysqli->query($insertQuery)) {
    echo "Success";
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
