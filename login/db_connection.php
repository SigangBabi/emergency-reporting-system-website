<?php
$servername = "localhost";
$username = "root";        // default for XAMPP
$password = "";            // no password by default
$dbname = "test";          // your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
