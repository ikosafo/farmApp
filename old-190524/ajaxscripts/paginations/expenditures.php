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
    expenditureName LIKE '%" . $searchValue . "%' 
    OR expenditureDescription LIKE '%" . $searchValue . "%'
    OR expenditureCategory LIKE '%" . $searchValue . "%'
    OR expenditureAmount LIKE '%" . $searchValue . "%'
    OR expenditureDate LIKE '%" . $searchValue . "%'
    OR expenditureReceipt LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from expenditures where expActive = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM expenditures WHERE expActive = 1 AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM expenditures WHERE expActive = 1 AND 1 " . $searchQuery . " ORDER BY expenditureDate DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "expenditureName" => $row['expenditureName'],
        "expenditureCategory" => $row['expenditureCategory'],
        "expenditureAmount" => $row['expenditureAmount'],
        "action" => manageExpenditure($row['expId'])
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
