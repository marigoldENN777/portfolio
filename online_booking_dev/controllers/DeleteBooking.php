<?php
session_start();
require '../config/Database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../public/AdminLogin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ../public/AdminDashboard.php');
    exit;
}
