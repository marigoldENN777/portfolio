<?php
$host = 'localhost';
$db = 'online_booking';
$user = 'root';
$pass = ''; // your MySQL password if you have one

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>