SELECT
  `produceid`,
  `seasonName`,
  `duration`,
  `createdAt`,
  `updatedAt`
FROM `seasons`




<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$produceCategory = mysqli_real_escape_string($mysqli, $_POST['produceCategory']);
$seasonName = mysqli_real_escape_string($mysqli, $_POST['seasonName']); 
$duration = mysqli_real_escape_string($mysqli, $_POST['duration']);
$datetime = date("Y-m-d H:i:s");

// Check if the category already exists
$checkQuery = "SELECT COUNT(*) AS count FROM `seasons` WHERE `seasonStatus` = 1 AND `categoryName` = '$categoryName'";
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
