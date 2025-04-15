<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$categoryName = mysqli_real_escape_string($mysqli, $_POST['categoryName']);
$categoryCode = mysqli_real_escape_string($mysqli, $_POST['categoryCode']);
$datetime = date("Y-m-d H:i:s");

// Check if the category name or category code already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `expense_category` WHERE `ecatActive` = 1 AND (`ecatName` = '$categoryName' OR `ecatCode` = '$categoryCode')";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    // category name or category code already exists
    echo "Error: Category name or category code already exists";
} else {
    // Prepare the SQL query
    $insertQuery = "INSERT INTO `expense_category` (`ecatName`, `ecatCode`) VALUES ('$categoryName', '$categoryCode')";

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
