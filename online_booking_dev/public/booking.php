<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
</head>
<body>
    <h2>Book a Time Slot</h2>
<form action="/controllers/HandleBooking.php" method="POST">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Phone:</label>
    <input type="text" name="phone">

    <label>Service:</label>
    <select name="service">
        <option value="singing">Singing Lesson</option>
        <option value="repair">Repair Service</option>
        <option value="consultation">Consultation</option>
    </select>

    <label>Date:</label>
    <input type="date" name="date" required>

    <label>Time:</label>
    <input type="time" name="time" required>

    <button type="submit">Book Now</button>
</form>

</body>
</html>