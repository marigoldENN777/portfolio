<?php
declare(strict_types=1);
session_start();

if (empty($_SESSION['is_admin'])) {
  header('Location: /admin/login.php');
  exit;
}

require __DIR__ . '/db.php';


$stmt = db()->query("
  SELECT id, name, title, contact, message, created_at
  FROM contact_messages
  ORDER BY created_at DESC
  LIMIT 500
");
$messages = $stmt->fetchAll();
$total = count($messages);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin - Messages</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
  <header class="border-b bg-white">
    <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-2xl bg-slate-900"></div>
        <div>
          <div class="font-semibold leading-5">Safe Vision</div>
          <div class="text-xs text-slate-500">Admin Panel</div>
        </div>
      </div>
      <a href="/logout.php"
         class="px-3 py-2 rounded-xl text-sm bg-slate-900 text-white hover:bg-slate-800">
        Logout
      </a>
    </div>
  </header>

  <main class="mx-auto max-w-6xl px-4 py-6">
    <div class="flex items-end justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold">Contact Messages</h1>
        <p class="text-sm text-slate-500 mt-1">
          Total: <span class="font-medium text-slate-900"><?= $total ?></span>
        </p>
      </div>
    </div>

    <div class="mt-5 bg-white border rounded-2xl shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 border-b">
            <tr class="text-left text-slate-600">
              <th class="px-4 py-3">Name</th>
              <th class="px-4 py-3">Title</th>
              <th class="px-4 py-3">Email/Phone</th>
              <th class="px-4 py-3">Message</th>
              <th class="px-4 py-3">Received</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php if (!$messages): ?>
              <tr>
                <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                  No messages yet.
                </td>
              </tr>
            <?php endif; ?>

            <?php foreach ($messages as $m): ?>
              <tr class="hover:bg-slate-50 align-top">
                <td class="px-4 py-3 font-medium whitespace-nowrap">
                  <?= htmlspecialchars((string)$m['name']) ?>
                </td>
                <td class="px-4 py-3">
                  <?= htmlspecialchars((string)$m['title']) ?>
                </td>
                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                  <?= htmlspecialchars((string)$m['contact']) ?>
                </td>
                <td class="px-4 py-3 text-slate-700 max-w-2xl">
                  <div class="leading-6">
                    <?= htmlspecialchars((string)$m['message']) ?>
                  </div>
                </td>
                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                  <?= htmlspecialchars((string)$m['created_at']) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>
</html>
