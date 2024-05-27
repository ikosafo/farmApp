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
    prodName LIKE '%" . $searchValue . "%' 
    OR prodDescription LIKE '%" . $searchValue . "%'
    OR prodCategory LIKE '%" . $searchValue . "%'
    OR prodPrice LIKE '%" . $searchValue . "%'
    OR expirationDate LIKE '%" . $searchValue . "%'
    OR prodQuantity LIKE '%" . $searchValue . "%'
    )";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from producelist where prodActive = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM producelist WHERE prodActive = 1 AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM producelist WHERE prodActive = 1 AND 1 " . $searchQuery . " ORDER BY prodId DESC LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "productName" => $row['prodName'],
        "productDescription" => $row['prodDescription'],
        "productCategory" => $row['prodCategory'],
        "productPrice" => $row['prodPrice'],
        "productQuantity" => $row['prodQuantity'],
        "productExpiry" => $row['expirationDate'],
        "productId" => manageProduct($row['prodId'])
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
