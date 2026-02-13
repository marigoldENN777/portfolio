<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/phpmailer/src/Exception.php';
require __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require __DIR__ . '/vendor/phpmailer/src/SMTP.php';
$env = require __DIR__ . '/../safevision_env.php';



$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

function jsonResponse(array $payload, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($payload, JSON_UNESCAPED_UNICODE);
  exit;
}

function clean(string $v): string {
  return trim(str_replace(["\r", "\n"], ' ', $v));
}

$backUrl = '/';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  if ($isAjax) jsonResponse(['ok' => false, 'message' => 'Neispravan zahtev.'], 405);
  header('Location: ' . $backUrl . '?error=1&msg=' . urlencode('Neispravan zahtev.'));
  exit;
}

// Honeypot (spam)
if (!empty($_POST['company'])) {
  if ($isAjax) jsonResponse(['ok' => true, 'message' => 'OK'], 200);
  header('Location: ' . $backUrl);
  exit;
}

$name    = clean($_POST['name'] ?? '');
$subject = clean($_POST['subject'] ?? '');
$contact = clean($_POST['contact'] ?? '');
$message = trim((string)($_POST['message'] ?? ''));

// ✅ Field-level validation
$fieldErrors = [];

if ($name === '')    $fieldErrors['name'] = 'Ime je obavezno.';
if ($subject === '') $fieldErrors['subject'] = 'Naslov je obavezan.';
if ($contact === '') $fieldErrors['contact'] = 'Email / Broj tel. je obavezno.';
if ($message === '') $fieldErrors['message'] = 'Poruka je obavezna.';
elseif (mb_strlen($message) < 10) $fieldErrors['message'] = 'Poruka je prekratka (min 10 karaktera).';

// Optional: validate contact format if provided
if ($contact !== '') {
  if (strpos($contact, '@') !== false) {
    if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
      $fieldErrors['contact'] = 'Unesi validan email.';
    }
  } else {
    $digitsOnly = preg_replace('/\D+/', '', $contact);
    if (strlen($digitsOnly) < 6) {
      $fieldErrors['contact'] = 'Unesi validan broj telefona.';
    }
  }
}

if (!empty($fieldErrors)) {
  if ($isAjax) {
    jsonResponse([
      'ok' => false,
      'message' => 'Molimo ispravite označena polja.',
      'fieldErrors' => $fieldErrors,
    ], 422);
  }

  header('Location: ' . $backUrl . '?error=1&msg=' . urlencode('Molimo ispravite označena polja.'));
  exit;
}

// SMTP (iz cPanel > Connect Devices)
$smtpHost = $env['SMTP_HOST'] ?? 's80.unlimited.rs';
$smtpUser = $env['SMTP_USER'] ?? 'office@safevision.rs';
$smtpPass = $env['SMTP_PASS'] ?? '';
$smtpPort = (int)($env['SMTP_PORT'] ?? 465);

if ($smtpPass === '') {
  if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => false, 'message' => 'Server nije podešen za slanje emaila.']);
    exit;
  }
  header('Location: /?error=1&msg=' . urlencode('Server nije podešen za slanje emaila.'));
  exit;
}


$siteName = 'SafeVision';
$toEmail  = 'office@safevision.rs';
$emailSubject = "[$siteName] " . $subject;

$escapedMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
$now = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

$bodyHtml = <<<HTML
<!doctype html>
<html lang="sr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Nova poruka</title></head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
  <div style="max-width:680px;margin:0 auto;padding:24px;">
    <div style="background:#0f172a;border-radius:16px;padding:18px 20px;">
      <div style="color:#fff;font-size:18px;font-weight:700;">SafeVision</div>
      <div style="color:#cbd5e1;font-size:13px;margin-top:4px;">Kontakt forma — nova poruka</div>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;margin-top:14px;">
      <div style="font-size:14px;color:#334155;line-height:1.5;">
        Stigla je nova poruka sa sajta <b>safevision.rs</b>.
      </div>

      <div style="margin-top:16px;border-top:1px solid #e2e8f0;padding-top:16px;">
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <div style="flex:1;min-width:220px;">
            <div style="font-size:12px;color:#64748b;margin-bottom:6px;">Ime</div>
            <div style="font-size:15px;font-weight:700;color:#0f172a;">{$name}</div>
          </div>
          <div style="flex:1;min-width:220px;">
            <div style="font-size:12px;color:#64748b;margin-bottom:6px;">Kontakt</div>
            <div style="font-size:15px;font-weight:700;color:#0f172a;">{$contact}</div>
          </div>
        </div>

        <div style="margin-top:14px;">
          <div style="font-size:12px;color:#64748b;margin-bottom:6px;">Naslov</div>
          <div style="font-size:15px;font-weight:700;color:#0f172a;">{$subject}</div>
        </div>

        <div style="margin-top:14px;">
          <div style="font-size:12px;color:#64748b;margin-bottom:6px;">Poruka</div>
          <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;font-size:14px;line-height:1.6;color:#0f172a;">
            {$escapedMessage}
          </div>
        </div>
      </div>

      <div style="margin-top:16px;font-size:12px;color:#64748b;">
        Vreme: <b>{$now}</b> &nbsp;
      </div>
    </div>

    <div style="text-align:center;color:#94a3b8;font-size:12px;margin-top:14px;">
      Ova poruka je automatski generisana sa kontakt forme na safevision.rs
    </div>
  </div>
</body>
</html>
HTML;

try {
  $mail = new PHPMailer(true);
  $mail->CharSet = 'UTF-8';

  $mail->isSMTP();
  $mail->Host = $smtpHost;
  $mail->SMTPAuth = true;
  $mail->Username = $smtpUser;
  $mail->Password = $smtpPass;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  $mail->Port = $smtpPort;

  $mail->setFrom($smtpUser, $siteName . ' Website');
  $mail->addAddress($toEmail);

  if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
    $mail->addReplyTo($contact, $name);
  }

  $mail->isHTML(true);
  $mail->Subject = $emailSubject;
  $mail->Body = $bodyHtml;

  $mail->AltBody =
    "Nova poruka sa sajta $siteName\n\n" .
    "Ime: $name\n" .
    "Kontakt: $contact\n" .
    "Naslov: $subject\n\n" .
    "Poruka:\n$message\n\n" .
    "Vreme: $now\nIP: $ip\n";

  $mail->send();

  if ($isAjax) {
    jsonResponse(['ok' => true, 'message' => 'Hvala! Poruka je poslata.'], 200);
  }

  header('Location: ' . $backUrl . '?sent=1');
  exit;

} catch (Exception $e) {
  // SMTP fail = server error (ne fieldErrors)
  if ($isAjax) {
    jsonResponse(['ok' => false, 'message' => 'Greška pri slanju. Pokušajte ponovo.'], 500);
  }

  header('Location: ' . $backUrl . '?error=1&msg=' . urlencode('Greška pri slanju. Pokušajte ponovo.'));
  exit;
}
