<?php
declare(strict_types=1);

require __DIR__ . '/db.php'; // db.php is inside /admin

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /public/index.html?sent=0');
  exit;
}

$name    = trim((string)($_POST['name'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$contact = trim((string)($_POST['contact'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $subject === '' || $contact === '' || $message === '') {
  header('Location: /public/index.html?sent=0');
  exit;
}

$stmt = db()->prepare("
  INSERT INTO contact_messages (name, title, contact, message)
  VALUES (:name, :title, :contact, :message)
");

$stmt->execute([
  ':name' => $name,
  ':title' => $subject,
  ':contact' => $contact,
  ':message' => $message,
]);

header('Location: /public/index.html?sent=1');
exit;
