<?php
include('../../config.php');
include('../../includes/functions.php');

if (!isset($_POST['productName']) || 
    !isset($_POST['produceCategory']) || 
    !isset($_POST['productPrice']) || 
    !isset($_POST['productExpiration']) || 
    !isset($_POST['productQuantity']) || 
    !isset($_POST['quantityUnit'])) {
    echo "Error: Missing required fields";
    exit;
}

$productName = $_POST['productName'];
$productDescription = isset($_POST['productDescription']) ? $_POST['productDescription'] : '';
$produceCategory = $_POST['produceCategory'];
$productPrice = $_POST['productPrice'];
$productQuantity = $_POST['productQuantity'];
$productExpiration = $_POST['productExpiration'];
$quantityUnit = $_POST['quantityUnit'];
$datetime = date("Y-m-d H:i:s");

// Check if the product name already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `producelist` WHERE `prodStatus` = 1 AND `prodName` = ?";
$stmt = $mysqli->prepare($checkQuery);
$stmt->bind_param("s", $productName);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $row['count'];
$stmt->close();

if ($count > 0) {
    echo "Error: Product already exists";
    exit;
}

$insertQuery = "INSERT INTO `producelist` (
    `prodName`, 
    `prodDescription`, 
    `prodCategory`, 
    `prodQuantity`, 
    `quantityUnit`, 
    `prodPrice`, 
    `expirationDate`, 
    `prodStatus`, 
    `createdAt`, 
    `updatedAt`
) VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?)";

$stmt = $mysqli->prepare($insertQuery);
$stmt->bind_param(
    "sssisdsss",
    $productName,
    $productDescription,
    $produceCategory,
    $productQuantity,
    $quantityUnit,
    $productPrice,
    $productExpiration,
    $datetime,
    $datetime
);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();