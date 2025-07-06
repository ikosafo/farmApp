<?php
include('../../config.php');

$fromCategory = $_POST['fromCategory'];
$toCategory = $_POST['toCategory'];

if (empty($fromCategory) || empty($toCategory)) {
    echo "Please select both categories";
    exit;
}

if ($fromCategory == $toCategory) {
    echo "Cannot merge a category into itself";
    exit;
}

// Begin transaction
mysqli_begin_transaction($mysqli);

try {
    // Update cashbook_transactions to set nominalAccount to the new category ID
    $updateQuery = "UPDATE cashbook_transactions SET nominalAccount = ? WHERE nominalAccount = ?";
    $stmt = mysqli_prepare($mysqli, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $toCategory, $fromCategory);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete the old category
    $deleteQuery = "UPDATE `categories` SET `categoryStatus` = 0 WHERE catId = ?";
    $stmt = mysqli_prepare($mysqli, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $fromCategory);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Commit transaction
    mysqli_commit($mysqli);
    echo "Success";
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($mysqli);
    echo "Error: " . $e->getMessage();
}

mysqli_close($mysqli);
?>