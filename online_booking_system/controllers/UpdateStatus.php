<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION["admin"])) {
    header("Location: ../public/AdminLogin.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    $stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ../public/AdminDashboard.php");
    exit();
}
?>