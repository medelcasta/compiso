<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../utiles/conexion.php';
require '../utiles/depurar.php';
require '../utiles/phpmailer_load.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tmp_email = depurar($_POST["email"]);

    if ($tmp_email == '') {
        $err_email = "El correo electrónico es obligatorio";
    } elseif (!filter_var($tmp_email, FILTER_VALIDATE_EMAIL)) {
        $err_email = "El correo electrónico no es válido";
    } else {
        $email = $tmp_email;
    }

    if (isset($email)) {
        $sql = $_conexion->prepare("SELECT nombre FROM Usuario WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $usuario = $fila["nombre"];

            $token = bin2hex(random_bytes(32));
            $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $sql_token = $_conexion->prepare("INSERT INTO ContrasenaOlvidada (email, token, expiracion) VALUES (?, ?, ?)");
            $sql_token->bind_param("sss", $email, $token, $expiracion);
            $sql_token->execute();

            // Configuración y envío del correo con PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'compiso2425@gmail.com'; // Tu correo Gmail
                $mail->Password = 'eirg orqm olwd kzhx'; // Contraseña de aplicación
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('compiso2425@gmail.com', 'Compiso');
                $mail->addAddress($email, $usuario);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de contraseña';
                $enlace = "http://compiso.infy.uk/usuario/recuperar_contrasena.php?token=$token";
                $mail->Body = "
                    <p>Hola <strong>$usuario</strong>,</p>
                    <p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
                    <p><a href='$enlace'>$enlace</a></p>
                    <p>Este enlace expirará en 1 hora.</p>
                    <p>Si no solicitaste este cambio, ignora este mensaje.</p>
                ";
                $mail->AltBody = "Hola $usuario, visita este enlace para recuperar tu contraseña: $enlace";

                $mail->send();
                $mensaje = "<div class='alert alert-success mt-3'>Se ha enviado un enlace de recuperación a tu correo electrónico.</div>";
            } catch (Exception $e) {
                $mensaje = "<div class='alert alert-danger mt-3'>Error al enviar correo: {$mail->ErrorInfo}</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>El correo electrónico no está registrado.</div>";
        }

        $_conexion->close();
    }
}
?>
