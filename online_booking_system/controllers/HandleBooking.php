<?php
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $service = $_POST["service"];
    $date = $_POST["date"];
    $time = $_POST["time"];

    // Check if the slot is already booked for that service
    $check = $conn->prepare("SELECT * FROM bookings WHERE service=? AND date=? AND time=? AND status!='cancelled'");
    $check->bind_param("sss", $service, $date, $time);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('That time slot is already booked! Please choose another.'); window.history.back();</script>";
        exit();
    }

    // If slot is free, insert new booking
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, service, date, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $service, $date, $time);

    if ($stmt->execute()) {
        // Send email confirmation (we’ll add it below)
        include("../includes/send_email.php");

        header("Location: ../public/booking_success.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
