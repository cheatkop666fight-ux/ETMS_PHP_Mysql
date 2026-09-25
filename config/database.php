<?php
$host = "localhost";
$username = "root";
$password = "12345";
$database = "etms";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_errno());
}
