<?php
include('../../config.php');
include('../../includes/functions.php');

// Escape and sanitize user inputs
$produceid = mysqli_real_escape_string($mysqli, $_POST['produceid']);
$seasonName = mysqli_real_escape_string($mysqli, $_POST['seasonName']);
$startMonth = mysqli_real_escape_string($mysqli, $_POST['startMonth']);
$endMonth = mysqli_real_escape_string($mysqli, $_POST['endMonth']);
$datetime = date("Y-m-d H:i:s");

// Validate inputs
if (empty($produceid) || empty($seasonName) || empty($startMonth) || empty($endMonth)) {
    echo "Error: All fields are required.";
    $mysqli->close();
    exit;
}

// Define months array for comparison
$months = ["January", "February", "March", "April", "May", "June", 
           "July", "August", "September", "October", "November", "December"];

// Check for overlapping months
$startIndex = array_search($startMonth, $months);
$endIndex = array_search($endMonth, $months);
$newSeasonMonths = [];

if ($startIndex <= $endIndex) {
    // Same year: e.g., February (1) to August (7)
    for ($i = $startIndex; $i <= $endIndex; $i++) {
        $newSeasonMonths[] = $months[$i];
    }
} else {
    // Cross-year: e.g., July (6) to February (1)
    for ($i = $startIndex; $i < count($months); $i++) {
        $newSeasonMonths[] = $months[$i];
    }
    for ($i = 0; $i <= $endIndex; $i++) {
        $newSeasonMonths[] = $months[$i];
    }
}

// Check existing seasons for the same produce
$checkQuery = "SELECT `startMonth`, `endMonth` FROM `seasons` WHERE `produceid` = '$produceid' AND `seasonStatus` = 1";
$result = $mysqli->query($checkQuery);

while ($row = $result->fetch_assoc()) {
    $existingStartIndex = array_search($row['startMonth'], $months);
    $existingEndIndex = array_search($row['endMonth'], $months);
    $existingSeasonMonths = [];

    if ($existingStartIndex <= $existingEndIndex) {
        // Same year
        for ($i = $existingStartIndex; $i <= $existingEndIndex; $i++) {
            $existingSeasonMonths[] = $months[$i];
        }
    } else {
        // Cross-year
        for ($i = $existingStartIndex; $i < count($months); $i++) {
            $existingSeasonMonths[] = $months[$i];
        }
        for ($i = 0; $i <= $existingEndIndex; $i++) {
            $existingSeasonMonths[] = $months[$i];
        }
    }

    // Check for overlap
    $overlap = array_intersect($newSeasonMonths, $existingSeasonMonths);
    if (!empty($overlap)) {
        echo "Error: The selected months overlap with an existing season for this produce: " . implode(", ", $overlap);
        $mysqli->close();
        exit;
    }
}

// Check for duplicate season name for the same produce
/* $checkQuery = "SELECT COUNT(*) AS count FROM `seasons` WHERE `seasonStatus` = 1 AND `produceid` = '$produceid' AND `seasonName` = '$seasonName'";
$result = $mysqli->query($checkQuery);
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    echo "Error: Season name already exists for this produce.";
    $mysqli->close();
    exit;
} */

// Insert new season
$insertQuery = "INSERT INTO `seasons` (`produceid`, `seasonName`, `startMonth`, `endMonth`, `createdAt`, `updatedAt`) 
                VALUES ('$produceid', '$seasonName', '$startMonth', '$endMonth', '$datetime', '$datetime')";

if ($mysqli->query($insertQuery)) {
    echo "Success";
} else {
    echo "Error: " . $mysqli->error;
}

//$mysqli->close();