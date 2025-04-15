<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$fullName = mysqli_real_escape_string($mysqli, $_POST['fullName']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$username = mysqli_real_escape_string($mysqli, $_POST['username']);
$role = mysqli_real_escape_string($mysqli, $_POST['role']);
$dateOfBirth = mysqli_real_escape_string($mysqli, $_POST['dateOfBirth']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$phoneNumber = mysqli_real_escape_string($mysqli, $_POST['phoneNumber']);
$permissions = mysqli_real_escape_string($mysqli, $_POST['permissions']);
$userId = mysqli_real_escape_string($mysqli, $_POST['userId']);

$datetime = date("Y-m-d H:i:s");

// Check if the username or email already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `users` WHERE (`userName` = '$username' OR `emailAddress` = '$email') AND `uId` != '$userId'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    // Username or email already exists
    echo "Error: Username or email already exists";
} else {
    // Check if a new password is provided

    // Prepare the SQL query
    $updateQuery = "UPDATE `users` SET
        `fullName` = '$fullName',
        `emailAddress` = '$email',
        `userName` = '$username',
        `role` = '$role',
        `dob` = '$dateOfBirth',
        `address` = '$address',
        `phoneNumber` = '$phoneNumber',
        `permission` = '$permissions'
    
    WHERE `uId` = '$userId'";

    // Execute the SQL query
    if ($mysqli->query($updateQuery)) {
        // Query executed successfully
        echo "Success";
    } else {
        // Error occurred
        echo "Error: " . $mysqli->error;
    }
}

// Close the database connection
$mysqli->close();
