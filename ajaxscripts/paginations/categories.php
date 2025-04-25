<?php
include('../../config.php');
include('../../includes/functions.php');

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; 
$columnIndex = $_POST['order'][0]['column']; 
$columnName = $_POST['columns'][$columnIndex]['data']; 
$columnSortOrder = $_POST['order'][0]['dir']; 
$searchValue = $_POST['search']['value']; 

## Search
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " and 
    (
    categoryName LIKE '%" . $searchValue . "%'
    OR categoryDescription LIKE '%" . $searchValue . "%'
    OR categoryStatus LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from `categories` where `categoryStatus` = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `categories` WHERE `categoryStatus` = 1 AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM `categories` WHERE `categoryStatus` = 1 AND 1 " . $searchQuery . " ORDER BY `catId` DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "catName" => $row['categoryName'],
        "catDescription" => $row['categoryDescription'],
        "catAction" => manageCategories($row['catId'])
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
