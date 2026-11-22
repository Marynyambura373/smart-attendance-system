<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_attendance";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Uncomment the line below only if you want to test the connection
// echo "Database connected successfully!";

?>