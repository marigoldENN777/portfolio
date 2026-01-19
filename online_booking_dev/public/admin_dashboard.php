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

  <!-- Tailwind -->
  <link rel="stylesheet" href="/assets/css/tailwind.css?v=1">
  <link rel="stylesheet" href="/online_booking/assets/css/tailwind.css?v=1">
</head>

<body class="min-h-screen bg-white text-gray-900">
  <div class="mx-auto max-w-6xl p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-semibold tracking-tight">Admin Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Manage incoming bookings.</p>
      </div>

      <a
        href="logout.php"
        class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2
               text-sm font-medium text-gray-900 shadow-sm hover:bg-gray-50"
      >
        Logout
      </a>
    </div>

    <?php if (count($bookings) > 0): ?>
      <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 p-4">
          <p class="text-sm text-gray-600">
            Total bookings: <span class="font-medium text-gray-900"><?= count($bookings) ?></span>
          </p>
        </div>

        <!-- Responsive table wrapper -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Phone</th>
                <th class="px-4 py-3">Service</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Time</th>
                <th class="px-4 py-3">Actions</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              <?php foreach ($bookings as $b): ?>
                <tr class="hover:bg-gray-50">
                  <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                    <?= htmlspecialchars($b['id']) ?>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                    <?= htmlspecialchars($b['name']) ?>
                  </td>

                  <td class="px-4 py-3 text-gray-700">
                    <span class="break-all"><?= htmlspecialchars($b['email']) ?></span>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                    <?= htmlspecialchars($b['phone']) ?>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                    <?= htmlspecialchars($b['service']) ?>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                    <?= htmlspecialchars($b['date']) ?>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3 text-gray-700">
                    <?= htmlspecialchars($b['time']) ?>
                  </td>

                  <td class="whitespace-nowrap px-4 py-3">
                    <form action="controllers/DeleteBooking.php" method="POST" class="inline">
                      <input type="hidden" name="id" value="<?= htmlspecialchars($b['id']) ?>">
                      <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2
                               text-xs font-semibold text-red-700 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-200"
                        onclick="return confirm('Delete this booking?');"
                      >
                        Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php else: ?>
      <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-sm">
        <h2 class="text-lg font-semibold">No bookings yet</h2>
        <p class="mt-2 text-sm text-gray-600">
          When someone submits the booking form, appointments will appear here.
        </p>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
