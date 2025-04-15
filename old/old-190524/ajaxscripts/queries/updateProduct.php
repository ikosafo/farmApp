<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$productName = mysqli_real_escape_string($mysqli, $_POST['productName']);
$productDescription = mysqli_real_escape_string($mysqli, $_POST['productDescription']);
$produceCategory = mysqli_real_escape_string($mysqli, $_POST['produceCategory']);
$productPrice = mysqli_real_escape_string($mysqli, $_POST['productPrice']);
$productQuantity = mysqli_real_escape_string($mysqli, $_POST['productQuantity']);
$productExpiration = mysqli_real_escape_string($mysqli, $_POST['productExpiration']);
$prodId = mysqli_real_escape_string($mysqli, $_POST['prodId']);
$datetime = date("Y-m-d H:i:s");

// Check if the updated product name already exists in the table for other records
$checkQuery = "SELECT COUNT(*) AS count FROM `producelist` WHERE `prodActive` = 1 AND `prodName` = '$productName' AND `prodId` != '$prodId'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    // Product name already exists for other records
    echo "Error: Product name already exists";
} else {
    // Prepare the SQL query
    $updateQuery = "UPDATE `producelist`
    SET
        `prodName` = '$productName',
        `prodDescription` = '$productDescription',
        `prodCategory` = '$produceCategory',
        `prodQuantity` = '$productQuantity',
        `prodPrice` = '$productPrice',
        `expirationDate` = '$productExpiration'
    WHERE
        `prodId` = '$prodId'";

    // Execute the SQL query
    if ($mysqli->query($updateQuery)) {
        // Query executed successfully
        echo "Success";
    } else {
        // Error occurred
        echo "Error: " . $mysqli->error;
    }
}

// Close the database connection
$mysqli->close();
