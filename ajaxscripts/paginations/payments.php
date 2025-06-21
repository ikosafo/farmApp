<?php
include('../../config.php');
include('../../includes/functions.php');

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Search
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (
        transactionDate LIKE '%" . $searchValue . "%' 
        OR payeePayer LIKE '%" . $searchValue . "%'
        OR details LIKE '%" . $searchValue . "%'
        OR produce LIKE '%" . $searchValue . "%'
        OR invoiceNo LIKE '%" . $searchValue . "%'
        OR currency LIKE '%" . $searchValue . "%'
        OR amount LIKE '%" . $searchValue . "%'
        OR exchangeRate LIKE '%" . $searchValue . "%'
        OR ghsEquivalent LIKE '%" . $searchValue . "%'
        OR transactionType LIKE '%" . $searchValue . "%'
        OR (SELECT categoryName FROM categories WHERE catId = cashbook_transactions.nominalAccount) LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from `cashbook_transactions` where `transStatus` = 1 AND `transactionType` = 'Payment'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `cashbook_transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Payment' AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM `cashbook_transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Payment' AND 1 " . $searchQuery . " ORDER BY updatedAt DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "date" => $row['transactionDate'],
        "payer" => $row['payeePayer'],
        "produce" => produceName($row['produce']),
        "amount" => $row['ghsEquivalent'],
        "account" => categoryName($row['nominalAccount']),
        "actions" => managePayment($row['transactionId'])
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
