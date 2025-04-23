<?php
require 'lib/PHPMailer/PHPMailer.php';
require 'lib/PHPMailer/SMTP.php';
require 'lib/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendConfirmationEmail($toEmail, $orderId) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // 🔁 Zmień!
        $mail->SMTPAuth = true;
        $mail->Username = 'twoj_email@example.com'; // 🔁 Zmień!
        $mail->Password = 'twoje_haslo';             // 🔁 Zmień!
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('twoj_email@example.com', 'GreenStyle');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = "Potwierdzenie zamówienia #$orderId";
        $mail->Body = "<h2>Dziękujemy za zakupy w GreenStyle!</h2>
                       <p>Twoje zamówienie <strong>#$orderId</strong> zostało przyjęte do realizacji.</p>
                       <p>Będziemy Cię informować o dalszym statusie.</p>";

        $mail->send();
        // Możesz dodać logowanie sukcesu, np. do pliku logów
    } catch (Exception $e) {
        // Możesz dodać logowanie błędów
    }
}
