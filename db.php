<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_attendance_db"; // Double-check if your DB in phpMyAdmin is named smart_attendance_db or smart_attendance

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>