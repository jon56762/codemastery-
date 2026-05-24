<?php

$host = "127.0.0.1";
$user = "root";
$password = "Succ00$$";
$databaseName = "codemastery";
$port = "3306";

$connect = new mysqli($host, $user, $password, $databaseName, $port);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
} else {
    echo "Connected Succesfully";
}

$connect->set_charset("utf8");