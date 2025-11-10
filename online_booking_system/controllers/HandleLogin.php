<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]); // matches our DB insert hash

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION["admin"] = $username;
        header("Location: ../public/AdminDashboard.php");
        exit();
    } else {
        header("Location: ../public/AdminLogin.php?error=1");
        exit();
    }
}

$conn->close();
?>
