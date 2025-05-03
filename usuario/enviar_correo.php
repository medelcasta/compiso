<?php use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tu_correo@gmail.com'; // TU GMAIL
    $mail->Password = 'tu_contraseña_o_clave_app'; // OJO: usa clave de aplicación si tienes 2FA
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Destinatario
    $mail->setFrom('tu_correo@gmail.com', 'Tu Nombre');
    $mail->addAddress('destinatario@ejemplo.com', 'Nombre del destinatario');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Asunto del correo';
    $mail->Body    = 'Este es el <b>cuerpo</b> del mensaje en HTML';
    $mail->AltBody = 'Este es el cuerpo del mensaje en texto plano';

    $mail->send();
    echo 'Correo enviado con éxito';
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
