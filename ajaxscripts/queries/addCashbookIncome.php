<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$cbUId = "x324ikjdewdade33424235583"; //mysqli_real_escape_string($mysqli, $_POST['cbUId']);
$cbUserId = "22"; mysqli_real_escape_string($mysqli, $_POST['cbUserId']);
$cashBookEntryType = mysqli_real_escape_string($mysqli, $_POST['cashBookEntryType']);
$cashBookDate = mysqli_real_escape_string($mysqli, $_POST['cashBookDate']);
$cbNominalAccountType = mysqli_real_escape_string($mysqli, $_POST['cbNominalAccountType']);
$cbAmount = mysqli_real_escape_string($mysqli, $_POST['cbAmount']);
$cbReferenceNumber = mysqli_real_escape_string($mysqli, $_POST['cbReferenceNumber']);
$cbPaymentMode = mysqli_real_escape_string($mysqli, $_POST['cbPaymentMode']);
$cbSalesProduceType = mysqli_real_escape_string($mysqli, $_POST['cbSalesProduceType']);
$cbRecipientPayeeName = mysqli_real_escape_string($mysqli, $_POST['cbRecipientPayeeName']);
$cbDescription = mysqli_real_escape_string($mysqli, $_POST['cbDescription']);
$cbField1 = mysqli_real_escape_string($mysqli, $_POST['cbField1']);
$cbField2 = mysqli_real_escape_string($mysqli, $_POST['cbField2']);
$cbField3 = mysqli_real_escape_string($mysqli, $_POST['cbField3']);
$cbField4 = mysqli_real_escape_string($mysqli, $_POST['cbField4']);
$cbActive = 1; //mysqli_real_escape_string($mysqli, $_POST['cbActive']);
$cbLog = mysqli_real_escape_string($mysqli, $_POST['cbLog']);
//$cbUId = $_SESSION['userId']; // Assuming you have session management for user ID

// Insert into general_cashbook table
$insertQuery = "INSERT INTO `general_cashbook` 
                (`cbUId`, `cbUserId`, `cashBookEntryType`, `cashBookDate`,  `cbNominalAccountType`, `cbAmount`, `cbReferenceNumber`, `cbPaymentMode`, `cbSalesProduceType`, `cbRecipientPayeeName`, `cbDescription`, `cbField1`, `cbField2`, `cbField3`, `cbField4`, `cbActive`, `cbLog`)
                VALUES ('$cbUId', '$cbUserId', '$cashBookEntryType', '$cashBookDate', '$cbNominalAccountType', '$cbAmount', '$cbReferenceNumber', '$cbPaymentMode', '$cbSalesProduceType', '$cbRecipientPayeeName', '$cbDescription', '$cbField1', '$cbField2', '$cbField3', '$cbField4', '$cbActive', '$cbLog')";

if ($mysqli->query($insertQuery)) {
    // Query executed successfully
    echo "Success";
} else {
    // Error occurred
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
