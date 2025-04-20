<?php
include('../../config.php');
include('../../includes/functions.php');

// Sanitize and retrieve form data
$customerName = mysqli_real_escape_string($mysqli, $_POST['fullName']);
$customerEmail = mysqli_real_escape_string($mysqli, $_POST['email']);
$customerPhone = mysqli_real_escape_string($mysqli, $_POST['phoneNumber']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$fulfillmentMethod = mysqli_real_escape_string($mysqli, $_POST['fulfillmentMethod']);
$preferredDate = mysqli_real_escape_string($mysqli, $_POST['preferredDate']);
$paymentMethod = mysqli_real_escape_string($mysqli, $_POST['paymentMethod']);
$totalAmount = floatval($_POST['totalAmount']);
$createdAt = date("Y-m-d H:i:s");
$updatedAt = $createdAt;

// Process order details (products and quantities)
$products = $_POST['products'] ?? [];
$quantities = $_POST['quantities'] ?? [];
$orderDetails = [];

if (count($products) !== count($quantities) || empty($products)) {
    echo "Error: Invalid order details provided";
    exit;
}

// Create order details as {productName:quantity}
for ($i = 0; $i < count($products); $i++) {
    $productName = mysqli_real_escape_string($mysqli, $products[$i]);
    $quantity = intval($quantities[$i]);
    if ($productName && $quantity > 0) {
        $orderDetails[$productName] = $quantity;
    }
}

// Convert order details to JSON
$orderDetailsJson = json_encode($orderDetails);
if ($orderDetailsJson === false) {
    echo "Error: Failed to encode order details";
    exit;
}

// Prepare the SQL query to insert the order
$insertQuery = "INSERT INTO `orders` (
    `customerName`,
    `customerEmail`,
    `customerPhone`,
    `customerAddress`,
    `orderDetails`,
    `deliveryMethod`,
    `deliveryDate`,
    `paymentMethod`,
    `totalAmount`,
    `createdAt`,
    `updatedAt`
) VALUES (
    '$customerName',
    '$customerEmail',
    '$customerPhone',
    '$address',
    '$orderDetailsJson',
    '$fulfillmentMethod',
    '$preferredDate',
    '$paymentMethod',
    '$totalAmount',
    '$createdAt',
    '$updatedAt'
)";

// Execute the SQL query
if ($mysqli->query($insertQuery)) {
    // Query executed successfully
    echo "Success";
} else {
    // Error occurred
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();