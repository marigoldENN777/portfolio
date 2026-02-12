<?php
include("../config/db.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Booking System</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <!-- Tailwind (compiled) -->
  <link rel="stylesheet" href="/assets/css/tailwind.css?v=1">
  <link rel="stylesheet" href="/online_booking_dev/assets/css/tailwind.css?v=1">
  <link rel="stylesheet" href="/online_booking_dev/public/assets/css/tailwind.css?v=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>

<body class="min-h-screen bg-white text-gray-900">
  <div class="mx-auto flex min-h-screen max-w-3xl items-center justify-center p-6">
    <div class="w-full">
      <!-- Top bar -->
      <div class="mb-6 flex items-center justify-between">
        <div class="text-sm text-gray-600">
          <?php if(isset($_SESSION["admin_logged_in"])) : ?>
            <a
              href="admin_dashboard"
              class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 shadow-sm hover:bg-gray-50"
            >
              Admin Dashboard
            </a>
          <?php endif; ?>
        </div>

        <a
          href="admin_login.php"
          class="text-sm font-medium text-gray-700 underline underline-offset-4 hover:text-gray-900"
        >
          Admin login
        </a>
      </div>

      <!-- Card -->
      <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
        <div class="mb-8">
          <h1 class="text-2xl font-semibold tracking-tight" style="font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;">
            Book an Appointment
          </h1>
          <p class="mt-2 text-sm text-gray-600" style="font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;">
            Fill out the form below to book your appointment.
          </p>
        </div>

        <?php if (isset($_GET['success'])) : ?>
          <div
            id="successMsg"
            class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
          >
            Form submitted successfully!
          </div>
        <?php endif; ?>

        <form action="controllers/HandleBooking.php" method="POST" class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input
              type="text"
              name="name"
              required
              placeholder="Your name"
              class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                     focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input
              type="email"
              name="email"
              required
              placeholder="you@example.com"
              class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                     focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
            >
          </div>

          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
              <label class="block text-sm font-medium text-gray-700">Date</label>
              <input
                type="date"
                name="date"
                required
                class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                       focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Time</label>
              <input
                type="time"
                name="time"
                required
                class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                       focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
              >
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input
              type="text"
              name="phone"
              required
              placeholder="+381..."
              class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                     focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Service</label>
            <select
              name="service"
              required
              class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                     focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
            >
              <option value="Singing Lesson">Singing Lesson</option>
              <option value="Shoe Repair">Shoe Repair</option>
            </select>
          </div>

          <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5
                   text-sm font-semibold text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900/20"
          >
            BOOK NOW
          </button>
        </form>
      </div>

      <div class="mt-10 text-center text-xs text-gray-500">
        © <?php echo date("Y"); ?> Online Booking System
      </div>
    </div>
  </div>

  <script>
    // Hide success message after 3 seconds
    window.addEventListener("DOMContentLoaded", () => {
      const msg = document.getElementById("successMsg");
      if (!msg) return;
      setTimeout(() => { msg.style.display = "none"; }, 3000);
    });
  </script>
</body>
</html>
