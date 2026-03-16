<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$dbname = "online_games";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: Set charset to utf8 to handle special characters correctly
mysqli_set_charset($conn, "utf8");
?>