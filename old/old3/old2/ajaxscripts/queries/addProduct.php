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
$datetime = date("Y-m-d H:i:s");

// Check if the product name or product code already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `producelist` WHERE `prodActive` = 1 AND `prodName` = '$productName'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    // Product name or product code already exists
    echo "Error: Product already exists";
} else {
    // Prepare the SQL query
    $insertQuery = "INSERT INTO `producelist`
    (`prodName`,
     `prodDescription`,
     `prodCategory`,
     `prodQuantity`,
     `prodPrice`,
     `expirationDate`)
VALUES ('$productName',
'$productDescription',
'$produceCategory',
'$productQuantity',
'$productPrice',
'$productExpiration')";

    // Execute the SQL query
    if ($mysqli->query($insertQuery)) {
        // Query executed successfully
        echo "Success";
    } else {
        // Error occurred
        echo "Error: " . $mysqli->error;
    }
}

// Close the database connection
$mysqli->close();
