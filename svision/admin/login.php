<?php
declare(strict_types=1);
session_start();

// CHANGE THESE
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'admin123';

if (!empty($_SESSION['is_admin'])) {
  header('Location: /admin/admin.php');
  exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = (string)($_POST['username'] ?? '');
  $p = (string)($_POST['password'] ?? '');

  if ($u === ADMIN_USER && $p === ADMIN_PASS) {
    $_SESSION['is_admin'] = true;
    header('Location: /admin.php');
    exit;
  }
  $error = 'Invalid username or password.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white border rounded-2xl shadow-sm p-6">
    <div class="flex items-center gap-3">
      <div class="h-10 w-10 rounded-2xl bg-slate-900"></div>
      <div>
        <div class="font-semibold leading-5">Safe Vision</div>
        <div class="text-xs text-slate-500">Admin Login</div>
      </div>
    </div>

    <?php if ($error): ?>
      <div class="mt-4 rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-700">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" class="mt-5 space-y-4">
      <div>
        <label class="block text-sm font-medium">Username</label>
        <input name="username" required
          class="mt-1 w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-900"
          placeholder="admin" />
      </div>

      <div>
        <label class="block text-sm font-medium">Password</label>
        <input name="password" type="password" required
          class="mt-1 w-full rounded-xl border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-900"
          placeholder="••••••••" />
      </div>

      <button class="w-full rounded-xl bg-slate-900 text-white py-2 hover:bg-slate-800">
        Sign in
      </button>
    </form>

    <p class="text-xs text-slate-500 mt-4">
      Change credentials in <code class="bg-slate-100 px-1 rounded">admin/login.php</code>.
    </p>
  </div>
</body>
</html>
