<?php
require __DIR__ . '/../../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $service  = trim($_POST['service']);
    $phone  = trim($_POST['phone']);
    $date     = $_POST['date'];
    $time     = $_POST['time'];

    // Prepare and bind statement (MySQLi)
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone , service, date, time) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $name, $email, $phone, $service, $date, $time);
    if ($stmt->execute()) {
        header("Location: ../index.php?success=1");
        exit;
    } else {
        echo "<p>❌ Booking failed: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p>No data submitted.</p>";
}
