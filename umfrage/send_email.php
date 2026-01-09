<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient_email = filter_var($_POST['recipient_email'], FILTER_SANITIZE_EMAIL);
    $recipient_name = filter_var($_POST['recipient_name']);
    $subject = filter_var($_POST['subject']);
    $message = filter_var($_POST['message']);
    $notenpunkte = filter_var($_POST['notenpunkte'], FILTER_VALIDATE_INT);
    $own_name = filter_var($_POST['own_name']);
    $own_email = filter_var($_POST['own_email'], FILTER_SANITIZE_EMAIL);
    $feedbackInput = filter_var($_POST['feedbackInput']);

    $mail = new PHPMailer(true);

    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, " \t\n\r\0\x0B\"");
                $_ENV[$key] = $value;
            }
        }
    }

    try {
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASSWORD"];

        $mail->setFrom($_ENV["EMAIL_FROM_ADDRESS"], $_ENV["EMAIL_FROM_NAME"]);
        $mail->addAddress($recipient_email, $recipient_name);
        if (!empty($own_email)) {
            $mail->addAddress($own_email, $own_name);
        }

        $mail->Subject = "Umfragen Feedback: " . $subject;
        $mail->Body = "Erhaltenes Feedback: " . nl2br($message) . "<br>Notenpunkte: " . $notenpunkte;
        if (!empty($own_name)) {
            $mail->Body .= "<br>Feedback erhalten von: " . $own_name;
        }
        if (!empty($own_email && $own_name)) {
            $mail->Body .= ", " . $own_email;
        }
        if (!empty($own_email) && empty($own_name)) {
            $mail->Body .= "<br>Feedback erhalten von: " . $own_email;
        }
        $mail->Body .= "<br>Tipp: " . $feedbackInput;
        $mail->AltBody = "Erhaltenes Feedback: " . $message . "\nNotenpunkte: " . $notenpunkte;
        if (!empty($own_name)) {
            $mail->AltBody .= "\nFeedback erhalten von: " . $own_name;
        }
        if (!empty($own_email && $own_name)) {
            $mail->AltBody .= ", " . $own_email;
        }
        if (!empty($own_email) && empty($own_name)) {
            $mail->AltBody .= "\nFeedback erhalten von: " . $own_email;
        }
        $mail->AltBody .= "\nSpezifisches Feedback: " . $feedbackInput;

        $mail->send();
        echo "E-Mail erfolgreich an " . $recipient_name . " gesendet.";
    } catch (Exception $e) {
        echo "Nachricht konnte nicht gesendet werden. Mailer-Fehler: {$mail->ErrorInfo}";
    }
} else {
    echo "Ungültige Anfragemethode";
}