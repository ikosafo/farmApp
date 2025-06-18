<?php
include('../../config.php');
session_start();

$userId = $_SESSION['uId']; 

$currencyghs = mysqli_real_escape_string($mysqli, $_POST['currencyghs']);
$currencyusd = mysqli_real_escape_string($mysqli, $_POST['currencyusd']);
$currencyeur = mysqli_real_escape_string($mysqli, $_POST['currencyeur']);

if (!is_numeric($currencyghs) || !is_numeric($currencyusd) || !is_numeric($currencyeur)) {
    echo "Invalid input. Please enter valid numeric values.";
    exit;
}

$update = $mysqli->query("
    UPDATE `currencies` 
    SET 
        `currencyghs` = '$currencyghs',
        `currencyusd` = '$currencyusd',
        `currencyeur` = '$currencyeur',
        `updatedAt` = now()
    LIMIT 1
") or die(mysqli_error($mysqli));

echo "Success";
