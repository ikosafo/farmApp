<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$categoryName = mysqli_real_escape_string($mysqli, $_POST['categoryName']);
$categoryDescription = mysqli_real_escape_string($mysqli, $_POST['categoryDescription']); 
$categoryStatus = mysqli_real_escape_string($mysqli, $_POST['categoryStatus']);
$datetime = date("Y-m-d H:i:s");

// Check if the category already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `categories` WHERE `categoryStatus` = 1 AND `categoryName` = '$categoryName'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
$count = $row['count'];

if ($count > 0) {
    echo "Error: Category name already exists";
} else {
    // Insert new category
    $insertQuery = "INSERT INTO `categories` (`categoryName`, `categoryDescription`, `categoryStatus`) 
                    VALUES ('$categoryName', '$categoryDescription', '$categoryStatus')";

    if ($mysqli->query($insertQuery)) {
        echo "Success";
    } else {
        echo "Error: " . $mysqli->error;
    }
}

$mysqli->close();
?>
