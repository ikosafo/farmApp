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
        incomeName LIKE '%" . $searchValue . "%' 
        OR incomeDescription LIKE '%" . $searchValue . "%'
        OR incomeCategory LIKE '%" . $searchValue . "%'
        OR incomeAmount LIKE '%" . $searchValue . "%'
        OR incomeDate LIKE '%" . $searchValue . "%'
        OR incomeReceipt LIKE '%" . $searchValue . "%'
        OR (SELECT ecatName FROM inccategory WHERE ecatId = incomes.incomeCategory) LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from `incomes` where incStatus = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `incomes` WHERE incStatus = 1 AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM `incomes` WHERE `incStatus` = 1 AND 1 " . $searchQuery . " ORDER BY incomeDate DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "incomeName" => $row['incomeName'],
        "incomeDate" => $row['incomeDate'],
        "incomeCategory" => incCategoryName($row['incomeCategory']),
		"incomeAmount" => number_format(($row['incomeAmount']), 2, '.', ','),	
        "incomeActions" => manageIncome($row['incId'])
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
