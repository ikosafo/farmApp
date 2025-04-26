<?php
include('../../config.php');
include('../../includes/functions.php');

$paymentStatus = isset($_POST['paymentStatus']) ? mysqli_real_escape_string($mysqli, $_POST['paymentStatus']) : '';

$whereClause = "WHERE `orderStatus` = 1";
if ($paymentStatus != '') {
    $whereClause .= " AND paymentStatus = '" . $paymentStatus . "'";
}

$query = "SELECT paymentStatus, SUM(totalAmount) as totalAmount 
          FROM `orders` 
          $whereClause 
          GROUP BY paymentStatus";

$result = mysqli_query($mysqli, $query);
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
        'paymentStatus' => $row['paymentStatus'],
        'totalAmount' => $row['totalAmount']
    );
}

echo json_encode($data);