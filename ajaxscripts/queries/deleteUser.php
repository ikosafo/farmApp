<?php
include('../../config.php');
include('../../includes/functions.php');

// Retrieve and sanitize POST data
$i_id = unlock(unlock($_POST['i_index']));

// Prepare the SQL query using a prepared statement
$updateQuery = "UPDATE `users` SET uStatus = 0 WHERE `uId` = ?";

// Prepare the statement
if ($stmt = $mysqli->prepare($updateQuery)) {
    // Bind the parameter
    $stmt->bind_param("s", $i_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $mysqli->error;
    }

    $stmt->close();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
