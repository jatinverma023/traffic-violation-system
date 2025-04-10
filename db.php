<?php
$servername = "localhost"; // Default for MAMP
$username = "root"; // Default MAMP username
$password = "root"; // Default MAMP password
$dbname = "traffic"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>