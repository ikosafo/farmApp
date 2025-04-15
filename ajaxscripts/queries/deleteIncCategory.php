<?php
include('../../config.php');
include('../../includes/functions.php');

$i_id = unlock(unlock($_POST['i_index']));
$updateQuery = "UPDATE `inccategory` SET `icatStatus` = 0 WHERE `icatId` = ?";


if ($stmt = $mysqli->prepare($updateQuery)) {
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
