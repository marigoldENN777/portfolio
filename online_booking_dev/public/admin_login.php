<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!--  <link rel="stylesheet" href="/../../assets/css/style.css">-->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="l-form-wrapper">
    <h1>Admin Panel</h1>
    <form action="../controllers/HandleLogin.php" method="POST">
        <div class="l-form-items">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required style="margin-bottom: 10px">

            <button type="submit">LOGIN</button>
        </div>
    </form>

    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>Invalid credentials. Please try again.</p>";
    }
    ?>
</div>
</body>
</html>
