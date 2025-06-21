<?php

function lock($item)
{
    // Check if $item is null or empty
    if ($item === null || $item === '') {
        return null; // Return null if $item is null or empty
    }
    return base64_encode(base64_encode(base64_encode($item)));
}

function unlock($item)
{
    // Check if $item is null or empty
    if ($item === null || $item === '') {
        return null; // Return null if $item is null or empty
    }
    return base64_decode(base64_decode(base64_decode($item)));
}


function deleteCategory($id)
{
    return '<a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteCategory_btn" 
    data-toggle="tooltip" data-original-title="Delete Category" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageProduct($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs editProduct_btn" 
    data-toggle="tooltip" data-original-title="Edit Product" i_index="' . lock(lock($id)) . '">Edit</a> |
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteProduct_btn" 
    data-toggle="tooltip" data-original-title="Delete Product" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageUser($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewUser_btn" 
    data-toggle="tooltip" data-original-title="View User" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editUser_btn" 
    data-toggle="tooltip" data-original-title="Edit User" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteUser_btn" 
    data-toggle="tooltip" data-original-title="Delete User" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageExpenditure($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewExpenditure_btn" 
    data-toggle="tooltip" data-original-title="View Expenditure" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Edit Expenditure" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Delete Expenditure" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageReceipt($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewReceipt_btn" 
    data-toggle="tooltip" data-original-title="View Income" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editReceipt_btn" 
    data-toggle="tooltip" data-original-title="Edit Income" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteReceipt_btn" 
    data-toggle="tooltip" data-original-title="Delete Income" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function managePayment($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewPayment_btn" 
    data-toggle="tooltip" data-original-title="View Income" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editPayment_btn" 
    data-toggle="tooltip" data-original-title="Edit Income" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deletePayment_btn" 
    data-toggle="tooltip" data-original-title="Delete Income" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageProduction($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewProduction_btn" 
    data-toggle="tooltip" data-original-title="View Produce" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editProduction_btn" 
    data-toggle="tooltip" data-original-title="Edit Produce" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteProduction_btn" 
    data-toggle="tooltip" data-original-title="Delete Produce" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageOrder($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewOrder_btn" 
    data-toggle="tooltip" data-original-title="View Produce" i_index="' . lock(lock($id)) . '">View</a> | 
    <div style="display:none"><a href="javascript:;" class="text-secondary font-weight-bold text-xs editOrder_btn" 
    data-toggle="tooltip" data-original-title="Edit Produce" i_index="' . lock(lock($id)) . '">Edit</a> | </div>
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteOrder_btn" 
    data-toggle="tooltip" data-original-title="Delete Produce" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageExpCategories($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewExpCategory_btn" 
    data-toggle="tooltip" data-original-title="View Expenditure Category" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editExpCategory_btn" 
    data-toggle="tooltip" data-original-title="Edit Expenditure Category" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteExpCategory" 
    data-toggle="tooltip" data-original-title="Delete Expenditure Category" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageIncCategories($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewIncCategory_btn" 
    data-toggle="tooltip" data-original-title="View Expenditure Category" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editIncCategory_btn" 
    data-toggle="tooltip" data-original-title="Edit Expenditure Category" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteIncCategory" 
    data-toggle="tooltip" data-original-title="Delete Expenditure Category" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageProdCategories($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewProdCategory_btn" 
    data-toggle="tooltip" data-original-title="View Product Category" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editProdCategory_btn" 
    data-toggle="tooltip" data-original-title="Edit Product Category" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteProdCategory" 
    data-toggle="tooltip" data-original-title="Delete Product Category" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageCategories($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewCategory_btn" 
    data-toggle="tooltip" data-original-title="View Category" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editCategory_btn" 
    data-toggle="tooltip" data-original-title="Edit Category" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteCategory" 
    data-toggle="tooltip" data-original-title="Delete Category" i_index="' . lock(lock($id)) . '">Delete</a>';
}


function manageCashBook($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewCashBook_btn" 
    data-toggle="tooltip" data-original-title="View Cashbook" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Edit Cashbook" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Delete Cashbook Entry" i_index="' . lock(lock($id)) . '">Delete</a>';
}

function manageCashBookExpenditure($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewCashBookExp_btn" 
    data-toggle="tooltip" data-original-title="View Cashbook" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Edit Cashbook" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Delete Cashbook Entry" i_index="' . lock(lock($id)) . '">Delete</a>';
}

function expCategoryName($id) {
    global $mysqli;

    $getExp = $mysqli->query("select `ecatName` from `expcategory` where `ecatId` = '$id'");
    $resExp = $getExp->fetch_assoc();
    return $resExp['ecatName'];
}


function categoryName($id) {
    global $mysqli;

    $getExp = $mysqli->query("SELECT `categoryName` FROM `categories` WHERE `catId` = '$id'");
    if ($getExp && $getExp->num_rows > 0) {
        $resExp = $getExp->fetch_assoc();
        return $resExp['categoryName'];
    }
    return null;
}



function produceName($id) {
    global $mysqli;

    $getProd = $mysqli->query("SELECT `prodName` FROM `producelist` WHERE `prodId` = '$id'");
    if ($getProd && $getProd->num_rows > 0) {
        $resProd = $getProd->fetch_assoc();
        return $resProd['prodName'];
    }
    return null;
}




function incCategoryName($id) {
    global $mysqli;

    $getInc = $mysqli->query("select `icatName` from `inccategory` where `icatId` = '$id'");
    $resInc = $getInc->fetch_assoc();
    return $resInc['icatName'];
}


function prodCategoryName($id) {
    global $mysqli;

    $getInc = $mysqli->query("select `pcatName` from `prodcategory` where `pcatId` = '$id'");
    $resInc = $getInc->fetch_assoc();
    return $resInc['pcatName'];
}





