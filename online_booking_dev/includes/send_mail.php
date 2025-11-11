<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // path for Composer install

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nevenjosipovic5@gmail.com'; // replace
    $mail->Password   = 'uyjurrmsgljyyvul';    // use Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('nevenjosipovic5@gmail.com', 'Booking System');
    $mail->addAddress($email, $name);
    $mail->addReplyTo('nevenjosipovic5@gmail.com', 'Info');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Booking Confirmation';
    $mail->Body    = "
        <h3>Hello $name,</h3>
        <p>Your booking for <strong>$service</strong> has been received!</p>
        <p><b>Date:</b> $date<br><b>Time:</b> $time</p>
        <p>We’ll contact you soon to confirm.</p>
        <p>Thank you,<br>Popravke Obuće Sava</p>
    ";

    $mail->send();
} catch (Exception $e) {
    error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
}
?>
