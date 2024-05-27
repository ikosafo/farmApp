<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$expenditureName = mysqli_real_escape_string($mysqli, $_POST['expenditureName']);
$expenditureDescription = mysqli_real_escape_string($mysqli, $_POST['expenditureDescription']);
$expenditureCategory = mysqli_real_escape_string($mysqli, $_POST['expenditureCategory']);
$expenditureAmount = mysqli_real_escape_string($mysqli, $_POST['expenditureAmount']);
$expenditureDate = mysqli_real_escape_string($mysqli, $_POST['expenditureDate']);
$expenditureReceipt = mysqli_real_escape_string($mysqli, $_POST['expenditureReceipt']);
//$expUId = $_SESSION['userId']; // Assuming you have session management for user ID

// Insert into the expenditures table
$insertQuery = "INSERT INTO `expenditures` 
                (`expenditureName`, `expenditureDescription`, `expenditureCategory`, `expenditureAmount`, `expenditureDate`, `expenditureReceipt`)
                VALUES ('$expenditureName', '$expenditureDescription', '$expenditureCategory', '$expenditureAmount', '$expenditureDate', '$expenditureReceipt')";

if ($mysqli->query($insertQuery)) {
    // Query executed successfully
    echo "Success";
} else {
    // Error occurred
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
