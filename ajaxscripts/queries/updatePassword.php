<?php
include('../../config.php');
session_start();

$userId = $_SESSION['uId']; 
$currentPassword = mysqli_real_escape_string($mysqli, $_POST['currentPassword']);
$newPassword = mysqli_real_escape_string($mysqli, $_POST['newPassword']);
$hashedCurrentPassword = md5($currentPassword);

$getUser = $mysqli->query("SELECT `password` FROM `users` WHERE `uId` = '$userId'") or die(mysqli_error($mysqli));
$user = $getUser->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

if ($user['password'] !== $hashedCurrentPassword) {
    echo "Current password is incorrect.";
    exit();
}


$hashedNewPassword = md5($newPassword);
$update = $mysqli->query("UPDATE `users` SET `password` = '$hashedNewPassword' WHERE `uId` = '$userId'") or die(mysqli_error($mysqli));

echo "Success";
