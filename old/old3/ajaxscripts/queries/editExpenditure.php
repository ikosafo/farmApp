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
$expId = mysqli_real_escape_string($mysqli, $_POST['i_id']); // Assuming you have an expId to identify the expenditure

// Update the expenditures table based on expId
$updateQuery = "UPDATE `expenditures` SET 
                `expenditureName` = '$expenditureName',
                `expenditureDescription` = '$expenditureDescription',
                `expenditureCategory` = '$expenditureCategory',
                `expenditureAmount` = '$expenditureAmount',
                `expenditureDate` = '$expenditureDate',
                `expenditureReceipt` = '$expenditureReceipt'
                WHERE `expId` = '$expId'";

if ($mysqli->query($updateQuery)) {
    // Query executed successfully
    echo "Success";
} else {
    // Error occurred
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
