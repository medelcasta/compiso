<?php
session_start();
require('../utiles/conexion.php');

if (!isset($_SESSION["usuario"])) {
    header("Location: iniciar_sesion.php");
    exit;
}

$usuario = $_SESSION["usuario"];
$id_usuario = $usuario["id_usuario"];
$mensaje = "";

// Variables por defecto
$contrasena = "";
$tipo_usuario = "";
$descripcion = "";

// Obtener los datos actuales del usuario
$sql = $_conexion->prepare("SELECT contrasena, tipo_usuario, descripcion FROM Usuario WHERE id_usuario = ?");
$sql->bind_param("s", $id_usuario);
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
    $nueva_contrasena = $_POST["contrasena"] ?? "";
    $nueva_descripcion = $_POST["descripcion"] ?? "";
    $nuevo_tipo_usuario = $tipo_usuario; // No se cambia desde el formulario

    // Validaciones
    $errores = false;

    if (strlen($nueva_contrasena) < 6) {
        $err_contrasena = "La contraseña debe tener al menos 6 caracteres.";
        $errores = true;
    }

    if (empty(trim($nueva_descripcion))) {
        $err_descripcion = "La descripción no puede estar vacía.";
        $errores = true;
    }

    if (!$errores) {
        $sql_update = $_conexion->prepare("UPDATE Usuario SET contrasena = ?, descripcion = ? WHERE id_usuario = ?");
        $sql_update->bind_param("sss", $nueva_contrasena, $nueva_descripcion, $id_usuario);

        if ($sql_update->execute()) {
            $mensaje = "<div class='alert alert-success'>Credenciales actualizadas correctamente.</div>";
            // Refrescar valores mostrados en el formulario
            $contrasena = $nueva_contrasena;
            $descripcion = $nueva_descripcion;
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
            padding: 30px;
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
        <h2>Cambiar Credenciales</h2>

        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form action="" method="post" novalidate>
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">

            <div class="mb-3">
                <label class="form-label">ID de usuario</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($id_usuario); ?>" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" name="contrasena" value="<?php echo htmlspecialchars($contrasena); ?>" required>
                <?php if (isset($err_contrasena)) echo "<div class='text-danger'>$err_contrasena</div>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($descripcion); ?></textarea>
                <?php if (isset($err_descripcion)) echo "<div class='text-danger'>$err_descripcion</div>"; ?>
            </div>

            <div class="text-end">
                <a href="../panel_control/mi_perfil.php" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </div>
        </form>
    </div>
</body>

</html>
