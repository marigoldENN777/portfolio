<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch all bookings
$result = $conn->query("SELECT * FROM bookings ORDER BY date DESC, time DESC");

$bookings = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<style>
    body {
        background: #101820;
        color: white;
        font-family: Arial, sans-serif;
        padding: 30px;
    }
    h1 {
        color: #00d4ff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #1c1c2b;
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #333;
    }
    th {
        background: #00d4ff;
        color: #000;
        text-align: left;
    }
    tr:hover {
        background: #2a2a40;
    }
    .logout {
        color: #00d4ff;
        text-decoration: none;
        float: right;
    }
    .logout:hover {
        text-decoration: underline;
    }
    .delete-btn {
        background: crimson;
        color: white;
        border: none;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 6px;
    }
</style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="logout.php" class="logout">Logout</a>

    <?php if (count($bookings) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['id']) ?></td>
                <td><?= htmlspecialchars($b['name']) ?></td>
                <td><?= htmlspecialchars($b['email']) ?></td>
                <td><?= htmlspecialchars($b['phone']) ?></td>
                <td><?= htmlspecialchars($b['service']) ?></td>
                <td><?= htmlspecialchars($b['date']) ?></td>
                <td><?= htmlspecialchars($b['time']) ?></td>
                <td>
                    <form action="controllers/DeleteBooking.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>No bookings yet.</p>
    <?php endif; ?>
</body>
</html>
