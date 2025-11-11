<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $service  = trim($_POST['service']);
    $date     = $_POST['date'];
    $time     = $_POST['time'];

    // Prepare and bind statement (MySQLi)
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, service, date, time) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $name, $email, $phone, $service, $date, $time);

    if ($stmt->execute()) {
        echo " <a style='text-decoration: none; display: block' href='/public/booking.php'>Back to booking</a>
<p>✅ Booking successful! We saved your appointment.</p>";
    } else {
        echo "<p>❌ Booking failed: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p>No data submitted.</p>";
}
