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
$paymentStatus = mysqli_real_escape_string($mysqli, $_POST['paymentStatus']);
$totalAmount = floatval($_POST['totalAmount']);
$createdAt = date("Y-m-d H:i:s");
$updatedAt = $createdAt;

// Process Delivery details (products and quantities)
$products = $_POST['products'] ?? [];
$quantities = $_POST['quantities'] ?? [];
$orderDetails = [];

if (count($products) !== count($quantities) || empty($products)) {
    echo "Error: Invalid Delivery details provided";
    exit;
}

// Validate quantities against available stock
for ($i = 0; $i < count($products); $i++) {
    $productName = mysqli_real_escape_string($mysqli, $products[$i]);
    $quantity = intval($quantities[$i]);
    
    if ($productName && $quantity > 0) {
        // Check available quantity
        $query = "SELECT `prodQuantity` FROM `producelist` WHERE `prodName` = '$productName' AND `prodStatus` = 1";
        $result = $mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            if ($quantity > $product['prodQuantity']) {
                echo "Error: Only {$product['prodQuantity']} units available for $productName";
                exit;
            }
            $orderDetails[$productName] = $quantity;
        } else {
            echo "Error: Product $productName not found";
            exit;
        }
    }
}

// Convert Delivery details to JSON
$orderDetailsJson = json_encode($orderDetails);
if ($orderDetailsJson === false) {
    echo "Error: Failed to encode Delivery details";
    exit;
}

// Start transaction
$mysqli->begin_transaction();

try {
    // Insert order
    $insertQuery = "INSERT INTO `orders` (
        `customerName`,
        `customerEmail`,
        `customerPhone`,
        `customerAddress`,
        `orderDetails`,
        `deliveryMethod`,
        `deliveryDate`,
        `paymentStatus`,
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
        '$paymentStatus',
        '$totalAmount',
        '$createdAt',
        '$updatedAt'
    )";

    if (!$mysqli->query($insertQuery)) {
        throw new Exception("Error inserting order: " . $mysqli->error);
    }

    // Update product quantities
    foreach ($orderDetails as $productName => $quantity) {
        $productNameEscaped = mysqli_real_escape_string($mysqli, $productName);
        $updateQuery = "UPDATE `producelist` SET 
            `prodQuantity` = `prodQuantity` - $quantity,
            `updatedAt` = '$updatedAt'
            WHERE `prodName` = '$productNameEscaped'";
        
        if (!$mysqli->query($updateQuery)) {
            throw new Exception("Error updating quantity for $productName: " . $mysqli->error);
        }
    }

    // Commit transaction
    $mysqli->commit();
    echo "Success";
} catch (Exception $e) {
    // Rollback transaction on error
    $mysqli->rollback();
    echo $e->getMessage();
}

// Close the database connection
$mysqli->close();