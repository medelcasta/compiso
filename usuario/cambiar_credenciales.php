<?php
session_start();
require('../utiles/conexion.php');

if (!isset($_SESSION["usuario"])) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];
$mensaje = "";

// Obtener los datos actuales del usuario
$sql = $_conexion->prepare("SELECT contrasena, tipo_usuario, descripcion FROM Usuario WHERE nombre = ?");
$sql->bind_param("s", $usuario);
$sql->execute();
$resultado = $sql->get_result();

if ($fila = $resultado->fetch_assoc()) {
    $contrasena = $fila["contrasena"];
    $tipo_usuario = $fila["tipo_usuario"];
    $descripcion = $fila["descripcion"];
} else {
    echo "<div class='alert alert-danger'>No se encontró información del usuario.</div>";
    exit;
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nueva_contrasena = $_POST["contrasena"];
    $nuevo_tipo_usuario = $_POST["tipo_usuario"];
    $nueva_descripcion = $_POST["descripcion"];

    // Validar los datos
    $errores = false;
    if (strlen($nueva_contrasena) < 6) {
        $err_contrasena = "La contraseña debe tener al menos 6 caracteres.";
        $errores = true;
    }

    if (!in_array($nuevo_tipo_usuario, [1, 2])) {
        $err_tipo_usuario = "El tipo de usuario no es válido.";
        $errores = true;
    }

    if (empty($nueva_descripcion)) {
        $err_descripcion = "La descripción no puede estar vacía.";
        $errores = true;
    }

    // Si no hay errores, actualizar los datos
    if (!$errores) {
        $sql_update = $_conexion->prepare("UPDATE Usuario SET contrasena = ?, tipo_usuario = ?, descripcion = ? WHERE nombre = ?");
        $sql_update->bind_param("siss", $nueva_contrasena, $nuevo_tipo_usuario, $nueva_descripcion, $usuario);

        if ($sql_update->execute()) {
            $mensaje = "<div class='alert alert-success'>Credenciales actualizadas correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al actualizar las credenciales.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Credenciales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn {
            margin-top: 10px;
        }

        .text-danger {
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div>
            <h2>Cambiar Credenciales</h2>
        </div>
        <div>
            <?php if (isset($mensaje)) echo $mensaje; ?>
            <form action="" method="post">
                <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">

                <div>
                    <label class="form-label">Usuario actual</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION["usuario"]); ?>" disabled>
                </div>

                <div>
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password" class="form-control" name="contrasena" value="<?php echo $contrasena ?? ''; ?>" required>
                    <?php if (isset($err_contrasena)) echo "<div class='text-danger'>" . $err_contrasena . "</div>"; ?>
                </div>

                <div>
                    <label class="form-label">Tipo de usuario</label>
                    <select name="tipo_usuario" class="form-select">
                        <option value="1" <?php echo (isset($tipo_usuario) && $tipo_usuario == 1) ? 'selected' : ''; ?>>Inquilino</option>
                        <option value="2" <?php echo (isset($tipo_usuario) && $tipo_usuario == 2) ? 'selected' : ''; ?>>Propietario</option>
                    </select>
                    <?php if (isset($err_tipo_usuario)) echo "<div class='text-danger'>" . $err_tipo_usuario . "</div>"; ?>
                </div>

                <div>
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea>
                    <?php if (isset($err_descripcion)) echo "<div class='text-danger'>" . $err_descripcion . "</div>"; ?>
                </div>

                <div class="text-end">
                    <a href="../panel_control/mi_perfil.php" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>