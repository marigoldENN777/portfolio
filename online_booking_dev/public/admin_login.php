<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <!-- Tailwind -->
  <link rel="stylesheet" href="/assets/css/tailwind.css?v=1">
  <link rel="stylesheet" href="/online_booking/assets/css/tailwind.css?v=1">
  
</head>

<body class="min-h-screen bg-white text-gray-900">
  <div class="p-6">
    <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="mb-6">
        <h1 class="text-xl font-semibold tracking-tight">
          Admin Panel
        </h1>
        <p class="mt-1 text-xs text-gray-500">
          Credentials: <span class="font-mono">admin / admin123</span>
        </p>
      </div>

      <?php if (isset($_GET['error'])) : ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
          Invalid credentials. Please try again.
        </div>
      <?php endif; ?>

      <form action="controllers/HandleLogin.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">
            Username
          </label>
          <input
            type="text"
            name="username"
            required
            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                   focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">
            Password
          </label>
          <input
            type="password"
            name="password"
            required
            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900
                   focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/10"
          >
        </div>

        <button
          type="submit"
          class="mt-2 inline-flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5
                 text-sm font-semibold text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900/20"
        >
          LOGIN
        </button>
      </form>
    </div>
  </div>
</body>
</html>
