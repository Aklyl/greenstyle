<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendConfirmationEmail($toEmail, $orderId) {
    $mail = new PHPMailer(true);
    
    try {
        // Ustawienia serwera SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';      // ← Zmień na swój serwer SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'twoj_email@example.com'; // ← Twój email
        $mail->Password = 'twoje_haslo';            // ← Hasło do maila
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Dane nadawcy i odbiorcy
        $mail->setFrom('twoj_email@example.com', 'GreenStyle');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = "Potwierdzenie zamówienia #$orderId";
        $mail->Body = "<h2>Dziękujemy za zakupy w GreenStyle!</h2>
                       <p>Twoje zamówienie <strong>#$orderId</strong> zostało przyjęte do realizacji.</p>
                       <p>Będziemy Cię informować o dalszym statusie.</p>";

        $mail->send();
        // echo 'E-mail został wysłany.';
    } catch (Exception $e) {
        // echo "Błąd wysyłki: {$mail->ErrorInfo}";
    }
}
