<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Credenciales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
<script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>

<body class="bg-light">
    <p class="text-center mt-3 text-muted">Bienvenid@ <?php echo htmlspecialchars($_SESSION["usuario"]); ?>

        <?php
        require '../utiles/conexion.php';
        require '../utiles/depurar.php';
        session_start();

        if (!isset($_SESSION["usuario"])) {
            header("location: ../usuario/iniciar_sesion.php");
            exit;
        }

        $mensaje = "";
        $usuario = $_SESSION["usuario"];

        $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre = ?");
        $sql->bind_param("s", $usuario);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $usuario = $fila["nombre"];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_usuario = depurar($_POST["usuario"]);
            $tmp_contrasena = depurar($_POST["contrasena"]);
            $tmp_tipo_usuario = $_POST["tipo_usuario"];

            if ($tmp_usuario == '') {
                $err_usuario = "El usuario es obligatorio";
            } elseif (strlen($tmp_usuario) < 3 || strlen($tmp_usuario) > 15) {
                $err_usuario = "El usuario no puede contener más de 15 caracteres";
            } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $tmp_usuario)) {
                $err_usuario = "El usuario solo puede contener números y letras";
            } else {
                $usuario = $tmp_usuario;
            }

            if ($tmp_contrasena == '') {
                $err_contrasena = "La contraseña es obligatoria";
            } elseif (strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 255) {
                $err_contrasena = "La contraseña debe tener entre 8 y 255 caracteres";
            } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $tmp_contrasena)) {
                $err_contrasena = "Debe contener mayúsculas, minúsculas, número y carácter especial";
            } else {
                $contrasena = $tmp_contrasena;
            }

            if ($tmp_tipo_usuario == '') {
                $err_tipo_usuario = "El tipo de usuario es obligatorio";
            } elseif ($tmp_tipo_usuario != 1 && $tmp_tipo_usuario != 2) {
                $err_tipo_usuario = "El tipo de usuario no es correcto";
            } else {
                $tipo_usuario = $tmp_tipo_usuario;
            }

            if (isset($usuario) && isset($contrasena) && isset($tipo_usuario)) {
                $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre = ?");
                $sql->bind_param("s", $usuario);
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows == 0) {
                    $err_usuario = "El usuario no existe";
                } else {
                    $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                    $sql = $_conexion->prepare("UPDATE Usuario SET contrasena = ?, tipo_usuario = ? WHERE nombre = ?");
                    $sql->bind_param("sis", $contrasena_cifrada, $tipo_usuario, $usuario);
                    $sql->execute();
                    $_conexion->close();

                    $mensaje = "<div class='alert alert-success mt-3'>Credenciales actualizadas correctamente.</div>";
                }
            }
        }
        ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Cambiar Credenciales</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($mensaje))
                            echo $mensaje; ?>
                        <form action="" method="post">
                            <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">

                            <div class="mb-3">
                                <label class="form-label">Usuario actual</label>
                                <input type="text" class="form-control"
                                    value="<?php echo htmlspecialchars($_SESSION["usuario"]); ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password" name="contrasena" class="form-control"
                                    value="<?php echo $contrasena ?? ''; ?>" required>
                                <?php if (isset($err_contrasena))
                                    echo "<div class='text-danger small'>" . $err_contrasena . "</div>"; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo de usuario</label>
                                <select name="tipo_usuario" class="form-select">
                                    <option value="1">Inquilino</option>
                                    <option value="2">Propietario</option>
                                </select>
                                <?php if (isset($err_tipo_usuario))
                                    echo "<div class='text-danger small'>" . $err_tipo_usuario . "</div>"; ?>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a class="btn btn-secondary" href="../panel_control/mi_perfil.php">Volver</a>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>

                </p>
            </div>
        </div>
    </div>

</body>

</html>