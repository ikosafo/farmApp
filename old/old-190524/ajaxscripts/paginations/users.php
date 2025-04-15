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
    userName LIKE '%" . $searchValue . "%' 
    OR fullName LIKE '%" . $searchValue . "%'
    OR phoneNumber LIKE '%" . $searchValue . "%'
    OR address LIKE '%" . $searchValue . "%'
    OR dob LIKE '%" . $searchValue . "%'
    OR permission LIKE '%" . $searchValue . "%'
    OR emailAddress LIKE '%" . $searchValue . "%'
    OR role LIKE '%" . $searchValue . "%'
    ) ";
}

## Total number of records without filtering
$sel = mysqli_query($mysqli, "select count(*) as allcount from users where uActive = 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($mysqli, "SELECT COUNT(*) AS allcount FROM users WHERE uActive = 1 AND 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT * FROM users WHERE uActive = 1 AND 1 " . $searchQuery . " ORDER BY fullName LIMIT " . $row . "," . $rowperpage;
$empRecords = mysqli_query($mysqli, $empQuery);
$data = array();


while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
        "fullName" => $row['fullName'],
        "phoneNumber" => $row['phoneNumber'],
        "username" => $row['userName'],
        "emailAddress" => $row['emailAddress'],
        "userId" => manageUser($row['uId'])
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
