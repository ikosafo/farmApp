<?php
include('../../config.php');
include('../../includes/functions.php');

## Read DataTables parameters
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($mysqli, $_POST['search']['value']); // Search value

## Search query
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (
        customerName LIKE '%" . $searchValue . "%' 
        OR customerEmail LIKE '%" . $searchValue . "%' 
        OR customerPhone LIKE '%" . $searchValue . "%' 
        OR orderDetails LIKE '%" . $searchValue . "%' 
        OR deliveryMethod LIKE '%" . $searchValue . "%' 
        OR deliveryDate LIKE '%" . $searchValue . "%' 
        OR paymentStatus LIKE '%" . $searchValue . "%' 
        OR totalAmount LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `orders` WHERE `orderStatus` = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `orders` WHERE `orderStatus` = 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM `orders` WHERE `orderStatus` = 1 " . $searchQuery . " ORDER BY createdAt DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
    // Parse orderDetails JSON
    $orderDetails = json_decode($row['orderDetails'], true);
    $orderDetailsText = '';
    if (is_array($orderDetails)) {
        foreach ($orderDetails as $product => $quantity) {
            $orderDetailsText .= "$product: $quantity<br>";
        }
    } else {
        $orderDetailsText = 'No items';
    }

    $data[] = array(
        "customerDetails" => "<strong>" . htmlspecialchars($row['customerName']) . "</strong><br>" .
                            "<small>Email: " . htmlspecialchars($row['customerEmail']) . "<br>" .
                            "Phone: " . htmlspecialchars($row['customerPhone']) . "</small>",
        "orderDetails" => "<strong>Delivery Made: " . date('M j, Y', strtotime($row['createdAt'])) . "</strong><br>" .
                         "<small>" . $orderDetailsText . "</small>",
        "deliveryDetails" => "<strong>" . htmlspecialchars($row['deliveryMethod']) . "</strong><br>" .
                            "<small>Date: " . date('M j, Y', strtotime($row['deliveryDate'])) . "</small>",
        "paymentDetails" => "<strong>GHC " . number_format($row['totalAmount'], 2, '.', ',') . "</strong><br>" .
                           "<small>Status: " . htmlspecialchars($row['paymentStatus']) . "</small>",
        "orderActions" => manageOrder($row['orderid'])
    );
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecordwithFilter,
    "iTotalDisplayRecords" => $totalRecords,
    "aaData" => $data
);

echo json_encode($response);