<?php include("../config/db.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Booking System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!--  <link rel="stylesheet" href="/../../assets/css/style.css">-->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php session_start(); ?>
<?php if(isset($_SESSION["admin_logged_in"])) : ?>
    <a href="admin_dashboard" style="display: inline-block; padding: 20px; text-decoration: none; font-size: 18px">Admin Dashboard</a>
<?php endif; ?>
<div class="form-wrapper" style="margin-bottom: 100px">
  <h1>Book an Appointment</h1>
    <h5 style="color: #fff">Visit <a style="font-size: 16px; color: #fff; font-style: italic;" href="admin_login.php">admin login</a> to view appointments</h5>

  <form action="controllers/HandleBooking.php" method="POST">
      <div class="form-items">
        <label>Name:</label>
        <input type="text" name="name" required">

        <label>Email:</label>
        <input type="email" name="email" required>



        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Time:</label>
        <input type="time" name="time" required >
          <label>Phone:</label>
          <input type="text" name="phone" required >
          <label>Service:</label>
          <select name="service" required style="margin-bottom: 20px">
              <option value="Singing Lesson">Singing Lesson</option>
              <option value="Shoe Repair">Shoe Repair</option>
          </select>

        <button type="submit">BOOK NOW</button>
          <?php
          // Show success if the URL contains ?success=1
              if (isset($_GET['success'])) {
                  echo "<p id='successMsg' style='color: #fff; font-size: 20px'>Form submitted successfully!</p>";
              }
          ?>
      </div>
  </form>

</div>
<script>
    setInterval(() => {
        const msg = document.getElementById('successMsg');
        if(msg) {
            msg.style.display = 'none';
        }
    }, 3000)

</script>
</body>
</html>
