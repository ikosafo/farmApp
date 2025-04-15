<?php
include('../../config.php');
include('../../includes/functions.php');

// Get and sanitize inputs
$categoryName = trim($_POST['categoryName'] ?? '');
$categoryDescription = trim($_POST['categoryDescription'] ?? '');
$categoryStatus = isset($_POST['categoryStatus']) ? (int)$_POST['categoryStatus'] : 1; 
$catIndex = isset($_POST['catIndex']) ? (int)$_POST['catIndex'] : 0; 
$datetime = date("Y-m-d H:i:s");

if (empty($categoryName)) {
    echo "Error: Category name is required";
    exit;
}

if ($catIndex <= 0) {
    echo "Error: Invalid category ID";
    exit;
}

// Initialize response
$response = "";

// Create database connection
if (!$mysqli) {
    echo "Error: Database connection failed";
    exit;
}

try {
    // Check if category name already exists (exclude current category)
    $checkQuery = "SELECT COUNT(*) AS count FROM `incCategory` WHERE `icatName` = ? AND `icatStatus` = 1 AND `icatId` != ?";
    $stmt = $mysqli->prepare($checkQuery);
    if (!$stmt) {
        throw new Exception("Error preparing check query: " . $mysqli->error);
    }
    $stmt->bind_param("si", $categoryName, $catIndex);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $stmt->close();

    if ($count > 0) {
        echo "Error: Category name already exists";
        exit;
    }

    // Update existing category
    $updateQuery = "UPDATE `incCategory` SET `icatName` = ?, `icatDesc` = ?, `icatStatus` = ?, `updatedAt` = ? WHERE `icatId` = ?";
    $stmt = $mysqli->prepare($updateQuery);
    if (!$stmt) {
        throw new Exception("Error preparing update query: " . $mysqli->error);
    }
    $stmt->bind_param("ssisi", $categoryName, $categoryDescription, $categoryStatus, $datetime, $catIndex);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Success";
        } else {
            echo "Error: No category found with the provided ID";
        }
    } else {
        throw new Exception("Error updating category: " . $stmt->error);
    }
    $stmt->close();
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $mysqli->close();
}
?>