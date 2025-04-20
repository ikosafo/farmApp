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
        prodName LIKE '%" . $searchValue . "%' 
        OR prodDescription LIKE '%" . $searchValue . "%'
        OR prodPrice LIKE '%" . $searchValue . "%'
        OR expirationDate LIKE '%" . $searchValue . "%'
        OR prodQuantity LIKE '%" . $searchValue . "%'
        OR (SELECT pcatName FROM prodcategory WHERE pcatId = producelist.prodCategory) LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from `producelist` where `prodStatus` = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM `producelist` WHERE `prodStatus` = 1  AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM `producelist` WHERE `prodStatus` = 1 AND 1 " . $searchQuery . " ORDER BY prodId DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "produceName" => $row['prodName'],
        "produceCategory" => prodCategoryName($row['prodCategory']),
        "producePrice" => number_format(($row['prodPrice']), 2, '.', ','),
		"produceQuantity" => $row['prodQuantity'].' ('.$row['quantityUnit'].')',	
        "produceActions" => manageProduction($row['prodId'])
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
