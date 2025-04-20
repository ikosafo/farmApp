<?php
include('../../config.php');
include('../../includes/functions.php');

if (!isset($_POST['prodId']) || 
    !isset($_POST['productName']) || 
    !isset($_POST['produceCategory']) || 
    !isset($_POST['productPrice']) || 
    !isset($_POST['productExpiration']) || 
    !isset($_POST['productQuantity']) || 
    !isset($_POST['quantityUnit'])) {
    echo "Error: Missing required fields";
    exit;
}

$prodId = $_POST['prodId'];
$productName = $_POST['productName'];
$productDescription = isset($_POST['productDescription']) ? $_POST['productDescription'] : '';
$produceCategory = $_POST['produceCategory'];
$productPrice = $_POST['productPrice'];
$productQuantity = $_POST['productQuantity'];
$productExpiration = $_POST['productExpiration'];
$quantityUnit = $_POST['quantityUnit'];
$datetime = date("Y-m-d H:i:s");

$checkQuery = "SELECT COUNT(*) AS count FROM `producelist` WHERE `prodStatus` = 1 AND `prodName` = ? AND `prodId`  != ?";
$stmt = $mysqli->prepare($checkQuery);
$stmt->bind_param("si", $productName, $prodId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$count = $row['count'];
$stmt->close();

if ($count > 0) {
    echo "Error: Product name already exists";
    exit;
}

// Prepare the UPDATE query
$updateQuery = "UPDATE `producelist` SET 
    `prodName` = ?, 
    `prodDescription` = ?, 
    `prodCategory` = ?, 
    `prodQuantity` = ?, 
    `quantityUnit` = ?, 
    `prodPrice` = ?, 
    `expirationDate` = ?, 
    `updatedAt` = ? 
WHERE `prodId` = ?";

$stmt = $mysqli->prepare($updateQuery);
$stmt->bind_param(
    "sssisdssi",
    $productName,
    $productDescription,
    $produceCategory,
    $productQuantity,
    $quantityUnit,
    $productPrice,
    $productExpiration,
    $datetime,
    $prodId
);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();