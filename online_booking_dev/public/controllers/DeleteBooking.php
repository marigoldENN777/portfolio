<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");

    $stmt->bind_param("i", $id); // "i" = integer

    $stmt->execute();

    $stmt->close();

    header('Location: ../admin_dashboard.php');
    exit;
}
