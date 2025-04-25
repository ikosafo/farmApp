<?php
include('../../config.php');
include('../../includes/functions.php');

$orderid = mysqli_real_escape_string($mysqli, $_POST['orderid']);
$customerName = mysqli_real_escape_string($mysqli, $_POST['customerName']);
$customerEmail = mysqli_real_escape_string($mysqli, $_POST['customerEmail']);
$customerPhone = mysqli_real_escape_string($mysqli, $_POST['customerPhone']);
$customerAddress = mysqli_real_escape_string($mysqli, $_POST['customerAddress']);
$deliveryMethod = mysqli_real_escape_string($mysqli, $_POST['deliveryMethod']);
$deliveryDate = mysqli_real_escape_string($mysqli, $_POST['deliveryDate']);
$paymentStatus = mysqli_real_escape_string($mysqli, $_POST['paymentStatus']);
$totalAmount = mysqli_real_escape_string($mysqli, $_POST['totalAmount']);
$products = $_POST['products'];
$quantities = $_POST['quantities'];

// Validate inputs
if (empty($orderid) || empty($customerName) || empty($customerPhone) || empty($customerAddress) || empty($deliveryMethod) || empty($deliveryDate) || empty($paymentStatus) || empty($products)) {
    echo "Missing required fields";
    exit;
}

// Prepare Delivery details JSON
$orderDetails = [];
foreach ($products as $index => $productName) {
    $productName = mysqli_real_escape_string($mysqli, $productName);
    $quantity = intval($quantities[$index]);
    
    // Fetch product price
    $getPrice = $mysqli->query("SELECT `prodPrice` FROM `producelist` WHERE `prodName` = '$productName' AND `prodStatus` = 1");
    if ($getPrice->num_rows == 0) {
        echo "Invalid product selected";
        exit;
    }
    $price = floatval($getPrice->fetch_assoc()['prodPrice']);
    
    $orderDetails[] = [
        'product' => $productName,
        'quantity' => $quantity,
        'price' => $price
    ];
}

$orderDetailsJson = json_encode($orderDetails);
if ($orderDetailsJson === false) {
    echo "Error encoding Delivery details";
    exit;
}

// Update Delivery in database
$updateQuery = "UPDATE `orders` SET 
    `customerName` = '$customerName',
    `customerEmail` = '$customerEmail',
    `customerPhone` = '$customerPhone',
    `customerAddress` = '$customerAddress',
    `deliveryMethod` = '$deliveryMethod',
    `deliveryDate` = '$deliveryDate',
    `paymentStatus` = '$paymentStatus',
    `totalAmount` = '$totalAmount',
    `orderDetails` = '$orderDetailsJson',
    `updatedAt` = NOW()
    WHERE `orderid` = '$orderid'";

if ($mysqli->query($updateQuery)) {
    echo "Success";
} else {
    echo "Error updating order: " . $mysqli->error;
}
?>