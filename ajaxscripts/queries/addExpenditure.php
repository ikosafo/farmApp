<?php
include('../../config.php');
include('../../includes/functions.php');

$expenditureName = mysqli_real_escape_string($mysqli, $_POST['expenditureName']);
$expenditureDescription = mysqli_real_escape_string($mysqli, $_POST['expenditureDescription']);
$expenditureCategory = mysqli_real_escape_string($mysqli, $_POST['expenditureCategory']);
$expenditureAmount = mysqli_real_escape_string($mysqli, $_POST['expenditureAmount']);
$expenditureDate = mysqli_real_escape_string($mysqli, $_POST['expenditureDate']);
$expenditureReceipt = mysqli_real_escape_string($mysqli, $_POST['expenditureReceipt']);

// Insert into the expenditures table
$insertQuery = "INSERT INTO `expenditures` 
                (`expenditureName`, `expenditureDescription`, `expenditureCategory`, `expenditureAmount`, `expenditureDate`, `expenditureReceipt`)
                VALUES ('$expenditureName', '$expenditureDescription', '$expenditureCategory', '$expenditureAmount', '$expenditureDate', '$expenditureReceipt')";

if ($mysqli->query($insertQuery)) {
    echo "Success";
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
