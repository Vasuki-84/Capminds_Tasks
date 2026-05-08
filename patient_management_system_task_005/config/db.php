<?php

$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "hospital_db";
$port = 3308;

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database,
    $port
);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

?>