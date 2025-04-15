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
        transactionName LIKE '%" . $searchValue . "%' 
        OR transactionDescription LIKE '%" . $searchValue . "%'
        OR transactionCategory LIKE '%" . $searchValue . "%'
        OR transactionAmount LIKE '%" . $searchValue . "%'
        OR transactionDate LIKE '%" . $searchValue . "%'
        OR transactionReceipt LIKE '%" . $searchValue . "%'
        OR (SELECT ecatName FROM inccategory WHERE ecatId = transactions.transactionCategory) LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from `transactions` where `transStatus` = 1 AND `transactionType` = 'Income'");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Income' AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM `transactions` WHERE `transStatus` = 1 AND `transactionType` = 'Income' AND 1 " . $searchQuery . " ORDER BY transactionDate DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "incomeName" => $row['transactionName'],
        "incomeDate" => $row['transactionDate'],
        "incomeCategory" => incCategoryName($row['transactionCategory']),
		"incomeAmount" => number_format(($row['transactionAmount']), 2, '.', ','),	
        "incomeActions" => manageIncome($row['transId'])
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
