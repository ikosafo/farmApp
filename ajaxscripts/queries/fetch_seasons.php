<?php
include('../../config.php');

if (isset($_POST['produceId'])) {
    $produceId = $mysqli->real_escape_string($_POST['produceId']);
    $response = ['success' => false, 'seasons' => [], 'error' => ''];

    $query = "SELECT `seasonid`, `seasonName`, `startMonth`, `endMonth` FROM `seasons` WHERE `produceid` = '$produceId' AND `seasonStatus` = 1";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response['seasons'][] = [
                'seasonId' => $row['seasonid'],
                'seasonName' => $row['seasonName'],
                'startMonth' => $row['startMonth'],
                'endMonth' => $row['endMonth']
            ];
        }
        $response['success'] = true;
    } else {
        $response['error'] = 'No active seasons found for this produce.';
    }

    echo json_encode($response);
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'No produce ID provided.']);
    exit;
}
?>