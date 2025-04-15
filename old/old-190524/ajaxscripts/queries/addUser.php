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
$permissions = mysqli_real_escape_string($mysqli, $_POST['permissions']);
$datetime = date("Y-m-d H:i:s");

// Check if the username or email already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `users` WHERE `username` = '$username' OR `emailAddress` = '$email'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    // Username or email already exists
    echo "Error: Username or email already exists";
} else {
    // Hash the password
    $hashedPassword = md5($password);

    // Prepare the SQL query
    $insertQuery = "INSERT INTO `users`
    (`fullName`,
     `emailAddress`,
     `userName`,
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
