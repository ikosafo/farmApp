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
    $searchQuery = " and 
    (
    cashBookEntryType LIKE '%" . $searchValue . "%' 
    OR cashBookDate LIKE '%" . $searchValue . "%'
    OR cbNominalAccountType LIKE '%" . $searchValue . "%'
    OR cbReferenceNumber LIKE '%" . $searchValue . "%'
    OR cbSalesProduceType LIKE '%" . $searchValue . "%'
    OR cbRecipientPayeeName LIKE '%" . $searchValue . "%'
    OR cbDescription LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from general_cashbook WHERE cashBookEntryType='Expenditure' AND cbActive = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM general_cashbook WHERE cashBookEntryType='Expenditure' AND cbActive = 1 AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$cashbkQuery = "SELECT * FROM general_cashbook WHERE cashBookEntryType='Expenditure' AND cbActive = 1 AND 1 " . $searchQuery . " ORDER BY cashbkId DESC LIMIT " . $row . "," . $rowperpage;
$cashbkRecords = mysqli_query($mysqli, $cashbkQuery);
$data = array();


while ($row = mysqli_fetch_assoc($cashbkRecords)) {
    $data[] = array(
        "cashBookEntryType" => $row['cashBookEntryType'],
        "cashBookDate" => $row['cashBookDate'],
        "cbNominalAccountType" => $row['cbNominalAccountType'],
		"cbAmount" => number_format(($row['cbAmount']), 2, '.', ','),	
		"cbSalesProduceType" => $row['cbSalesProduceType'],	
		"cbRecipientPayeeName" => mb_strimwidth($row['cbRecipientPayeeName'],0,15, '...'),		
        "action" => manageCashBook($row['cashbkId'])
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
