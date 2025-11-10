<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION["admin"])) {
  header("Location: AdminLogin.php");
  exit();
}

$result = $conn->query("SELECT * FROM bookings ORDER BY date, time ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h1>Welcome, <?php echo $_SESSION["admin"]; ?></h1>
  <a href="../controllers/Logout.php">Logout</a>

  <h2>All Bookings</h2>
  <table border="1" cellpadding="10">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Service</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['email'] ?></td>
      <td><?= $row['service'] ?></td>
      <td><?= $row['date'] ?></td>
      <td><?= $row['time'] ?></td>
      <td><?= ucfirst($row['status']) ?></td>
      <td>
        <a href="../controllers/UpdateStatus.php?id=<?= $row['id'] ?>&status=confirmed">✅ Confirm</a> |
        <a href="../controllers/UpdateStatus.php?id=<?= $row['id'] ?>&status=cancelled">❌ Cancel</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
