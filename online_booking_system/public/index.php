<?php include("../config/db.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Booking System</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h1>Book an Appointment</h1>

  <form action="../controllers/handle_booking.php" method="POST">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Service:</label>
    <select name="service" required>
      <option value="Singing Lesson">Singing Lesson</option>
      <option value="Shoe Repair">Shoe Repair</option>
    </select>

    <label>Date:</label>
    <input type="date" name="date" required>

    <label>Time:</label>
    <input type="time" name="time" required>

    <button type="submit">Book Now</button>
  </form>
</body>
</html>
