<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /public/index.html?sent=0');
  exit;
}

$name    = trim((string)($_POST['name'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$contact = trim((string)($_POST['contact'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $subject === '' || $contact === '' || $message === '') {
  header('Location: /index.html?sent=0');
  exit;
}

$to = "nevenjosipovic5@gmail.com"; // <-- email klijenta
$emailSubject = "Nova poruka sa sajta: " . $subject;

$emailBody = "
Ime: $name
Kontakt: $contact

Poruka:
$message
";

$headers = "From: nevenjosipovic5@gmail.com\r\n";
$headers .= "Reply-To: $contact\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($to, $emailSubject, $emailBody, $headers)) {
    header('Location: /index.html?sent=1');
} else {
    header('Location: /index.html?sent=0');
}
exit;
