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

function manageCashBook($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewExpenditure_btn" 
    data-toggle="tooltip" data-original-title="View Cashbook" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Edit Cashbook" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Delete Cashbook Entry" i_index="' . lock(lock($id)) . '">Delete</a>';
}

function manageCashBookExpenditure($id)
{
    return '<a href="javascript:;" class="text-secondary font-weight-bold text-xs viewExpenditure_btn" 
    data-toggle="tooltip" data-original-title="View Cashbook" i_index="' . lock(lock($id)) . '">View</a> | 
    <a href="javascript:;" class="text-secondary font-weight-bold text-xs editExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Edit Cashbook" i_index="' . lock(lock($id)) . '">Edit</a> | 
    <a href="javascript:;" data-type="confirm" class="text-secondary font-weight-bold text-xs deleteExpenditure_btn" 
    data-toggle="tooltip" data-original-title="Delete Cashbook Entry" i_index="' . lock(lock($id)) . '">Delete</a>';
}





