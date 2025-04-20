<?php
include('../../config.php');
include('../../includes/functions.php');

// Retrieve and sanitize POST data
$i_id = unlock(unlock($_POST['i_index']));

// Prepare the SQL query using a prepared statement
$updateQuery = "UPDATE `producelist` SET prodStatus = 0 WHERE prodId = ?";

// Prepare the statement
if ($stmt = $mysqli->prepare($updateQuery)) {
    // Bind the parameter
    $stmt->bind_param("s", $i_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Query executed successfully
        echo "Success";
    } else {
        // Error occurred
        echo "Error: " . $mysqli->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Error in preparing the statement
    echo "Error: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
