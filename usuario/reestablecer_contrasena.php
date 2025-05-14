<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../utiles/conexion.php';
require '../utiles/depurar.php';
session_start();

$token = $_GET['token'] ?? '';
$mensaje = '';
$mostrarFormulario = false;

if ($token !== '') {
    $sql = $_conexion->prepare("SELECT email, expiracion FROM ContrasenaOlvidada WHERE token = ?");
    $sql->bind_param("s", $token);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $email = $fila["email"];
        $expiracion = strtotime($fila["expiracion"]);
        $ahora = time();

        if ($ahora < $expiracion) {
            $mostrarFormulario = true;
        } else {
            $mensaje = "<div class='alert alert-danger'>El enlace ha expirado. Solicita uno nuevo.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Token inválido o no encontrado.</div>";
    }
    $sql->close();
}

// Procesar nueva contraseña
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nueva"], $_POST["confirmar"])) {
    $nueva = depurar($_POST["nueva"]);
    $confirmar = depurar($_POST["confirmar"]);

    if ($nueva === '' || $confirmar === '') {
        $mensaje = "<div class='alert alert-danger'>Ambos campos son obligatorios.</div>";
        $mostrarFormulario = true;
    } elseif ($nueva !== $confirmar) {
        $mensaje = "<div class='alert alert-danger'>Las contraseñas no coinciden.</div>";
        $mostrarFormulario = true;
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $sql = $_conexion->prepare("UPDATE Usuario SET contrasena = ? WHERE email = ?");
        $sql->bind_param("ss", $hash, $email);
        if ($sql->execute()) {
            $_conexion->query("DELETE FROM ContrasenaOlvidada WHERE token = '$token'");
            $mensaje = "<div class='alert alert-success'>Contraseña actualizada correctamente. <a href='./iniciar_sesion.php'>Iniciar sesión</a></div>";
            $mostrarFormulario = false;
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al actualizar la contraseña.</div>";
            $mostrarFormulario = true;
        }
        $sql->close();
    }
}

$_conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecer Nueva Contraseña</title>
    <link rel="icon" type="image/jpg" href="../images/logo_compiso.ico" />
    <link rel="stylesheet" href="../css/sesiones.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="form-container">
        <h1>Establecer Nueva Contraseña</h1>

        <?php echo $mensaje; ?>

        <?php if ($mostrarFormulario): ?>
        <form method="post">
            <div>
                <label for="nueva">Nueva contraseña</label><br>
                <input type="password" id="nueva" name="nueva" required>
            </div>
            <div>
                <label for="confirmar">Confirmar contraseña</label><br>
                <input type="password" id="confirmar" name="confirmar" required>
            </div>
            <button type="submit">Guardar nueva contraseña</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
