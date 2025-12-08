<?php
session_start();
include("../../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = hash('sha256', $_POST["password"]); // matches our DB insert hash

    $sql = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $_SESSION["admin"] = $username;
        $_SESSION["admin_logged_in"] = true;
        header("Location: ../admin_dashboard.php");
        exit();
    } else {
        header("Location: ../admin_login.php?error=1");
        exit();
    }
}

$conn->close();
?>
