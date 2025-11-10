<?php
$host = 'localhost';
$db = 'booking_system';
$user = 'root';
$pass = '12345'; // your MySQL password if you have one

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>