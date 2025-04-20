<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$fullName = mysqli_real_escape_string($mysqli, $_POST['fullName']);
$email = mysqli_real_escape_string($mysqli, $_POST['email']);
$username = mysqli_real_escape_string($mysqli, $_POST['username']);
$password = mysqli_real_escape_string($mysqli, $_POST['password']);
$role = mysqli_real_escape_string($mysqli, $_POST['role']);
$dateOfBirth = mysqli_real_escape_string($mysqli, $_POST['dateOfBirth']);
$address = mysqli_real_escape_string($mysqli, $_POST['address']);
$phoneNumber = mysqli_real_escape_string($mysqli, $_POST['phoneNumber']);
$permissions = mysqli_real_escape_string($mysqli, implode(',', $_POST['permissions'])); 
$datetime = date("Y-m-d H:i:s");

// Check if the username, email, or phone number already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `users` WHERE `username` = '$username' OR `emailAddress` = '$email' OR `phoneNumber` = '$phoneNumber'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    echo "Error: Username, email, or phone number already exists";
} else {
    $hashedPassword = md5($password);

    $insertQuery = "INSERT INTO `users`
        (`fullName`,
         `emailAddress`,
         `username`,
         `password`,
         `role`,
         `dob`,
         `address`,
         `phoneNumber`,
         `permission`)
    VALUES ('$fullName',
            '$email',
            '$username',
            '$hashedPassword',
            '$role',
            '$dateOfBirth',
            '$address',
            '$phoneNumber',
            '$permissions')";

    if ($mysqli->query($insertQuery)) {
        echo "Success";
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->close();