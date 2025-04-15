<?php
date_default_timezone_set('UTC');
$mysqli = new mysqli('localhost:3308', 'root', 'root', 'farmApp');

if ($mysqli->connect_errno) {
    echo "cannot connect MYSQLI error no{$mysqli->connect_errno}:{$mysqli->connect_errno}";
    exit();
}

session_start();
