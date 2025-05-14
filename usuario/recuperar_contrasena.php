<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../utiles/conexion.php';
require '../utiles/depurar.php';
session_start();

$mensaje = "";

// Lógica para procesar el formulario de recuperación
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

            // Preparar datos para enviar con EmailJS desde el navegador (JS)
            $_SESSION["pendiente_email"] = $email;
            $_SESSION["pendiente_usuario"] = $usuario;
            $_SESSION["pendiente_link"] = "http://compiso.infy.uk/usuario/reestablecer_contrasena.php?token=$token";

            header("Location: ../utiles/enviar_emailjs.html");
            exit;
        } else {
            $mensaje = "<div class='alert alert-danger mt-3'>El correo electrónico no está registrado.</div>";
        }

        $_conexion->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="icon" type="image/jpg" href="../images/logo_compiso.ico" />
    <link rel="stylesheet" href="../css/sesiones.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <header>
        <div>
            <div>
            <a href="../index.php"><img src="../images/logo_compiso.png" alt="Logo" id="logo"></a>
                <h1 id="titulo">Compiso</h1>
            </div>
            <div>
                <a href="./index.php" id="login">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h1>Recuperar Contraseña</h1>

        <!-- Mensaje de error si el email no está registrado -->
        <?php if ($mensaje) echo $mensaje; ?>

        <!-- Formulario de recuperación de contraseña -->
        <form action="" method="post">
            <div>
                <label for="email">Correo Electrónico</label><br>
                <input type="email" name="email" id="email" size="20px" value="<?php echo isset($tmp_email) ? htmlspecialchars($tmp_email) : ''; ?>">
                <?php if (isset($err_email)) echo "<span class='text-danger'>$err_email</span>"; ?>
            </div>

            <button type="submit">Enviar enlace de recuperación</button>
        </form>

        <a href="index.php">Volver a inicio</a>
    </div>

</body>

</html>
