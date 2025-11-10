<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h1>Admin Panel</h1>
  <form action="../controllers/HandleLogin.php" method="POST">
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
  </form>

  <?php
  if (isset($_GET['error'])) {
      echo "<p style='color:red;'>Invalid credentials. Please try again.</p>";
  }
  ?>
</body>
</html>
