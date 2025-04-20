<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$userId = mysqli_real_escape_string($mysqli, $_POST['userId']);
$fullName = mysqli_real_escape_string($mysqli, $_POST['fullName']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$username = mysqli_real_escape_string($mysqli, $_POST['username']);
$role = mysqli_real_escape_string($mysqli, $_POST['role']);
$dateOfBirth = mysqli_real_escape_string($mysqli, $_POST['dateOfBirth']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$phoneNumber = mysqli_real_escape_string($mysqli, $_POST['phoneNumber']);
$permissions = mysqli_real_escape_string($mysqli, implode(',', $_POST['permissions'])); 

$checkQuery = "SELECT COUNT(*) AS count FROM `users` WHERE (`username` = '$username' OR `emailAddress` = '$email' OR `phoneNumber` = '$phoneNumber') AND `uId` != '$userId'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    echo "Error: Username, email, or phone number already exists";
} else {
    $updateQuery = "UPDATE `users` SET
        `fullName` = '$fullName',
        `emailAddress` = '$email',
        `username` = '$username',
        `role` = '$role',
        `dob` = '$dateOfBirth',
        `address` = '$address',
        `phoneNumber` = '$phoneNumber',
        `permission` = '$permissions'
    WHERE `uId` = '$userId'";

    if ($mysqli->query($updateQuery)) {
        echo "Success";
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->close();
