<?php
require_once "../utiles/conexion.php";
// Solo ejecutar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once "../utiles/depurar.php"; // Asegúrate de tener la función depurar

    // Recoger y limpiar entradas
    $nombre = depurar($_POST['nombre'] ?? '');
    $apellidos = depurar($_POST['apellidos'] ?? '');
    $email = depurar($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';
    $telefono = depurar($_POST['telefono'] ?? '');
    $tipo_usuario = depurar($_POST['tipo_usuario'] ?? '');
    $fecha_nacimiento = depurar($_POST['fecha_nacimiento'] ?? '');
    $sexo = depurar($_POST['sexo'] ?? '');
    $descripcion = depurar($_POST['descripcion'] ?? '');

    // Inicializar errores
    $err_nombre = $err_apellidos = $err_email = $err_contrasena = $err_confirmar_contrasena = "";
    $err_telefono = $err_tipo_usuario = $err_fecha_nacimiento = $err_sexo = $err_imagen = "";
    $err_general = "";
    $registro_exitoso = "";

    // Validaciones
    if (empty($nombre) || strlen($nombre) < 2) {
        $err_nombre = "Por favor, introduce un nombre válido.";
    }

    if (empty($apellidos) || strlen($apellidos) < 2) {
        $err_apellidos = "Por favor, introduce los apellidos.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err_email = "Introduce un email válido.";
    }

    if (empty($contrasena) || strlen($contrasena) < 6) {
        $err_contrasena = "La contraseña debe tener al menos 6 caracteres.";
    }

    if ($contrasena !== $confirmar_contrasena) {
        $err_confirmar_contrasena = "Las contraseñas no coinciden.";
    }

    if (empty($telefono) || !preg_match('/^\d{9}$/', $telefono)) {
        $err_telefono = "Introduce un número de teléfono válido (9 dígitos).";
    }

    if (empty($tipo_usuario)) {
        $err_tipo_usuario = "Selecciona el tipo de usuario.";
    }

    if (empty($fecha_nacimiento)) {
        $err_fecha_nacimiento = "Introduce tu fecha de nacimiento.";
    } else {
        $fecha = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha)->y;
        if ($edad < 18) {
            $err_fecha_nacimiento = "Debes ser mayor de 18 años.";
        }
    }

    if (empty($sexo)) {
        $err_sexo = "Selecciona tu sexo.";
    }

    // Validación y subida de imagen
    $imagen_ruta = null;
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen'];
        $nombre_archivo = basename($imagen['name']);
        $tamano = $imagen['size'];
        $tipo = mime_content_type($imagen['tmp_name']);
        $permitidos = ['image/jpeg', 'image/png'];

        if (!in_array($tipo, $permitidos)) {
            $err_imagen = "La imagen debe ser JPG o PNG.";
        } elseif ($tamano > 2 * 1024 * 1024) {
            $err_imagen = "La imagen no debe superar los 2MB.";
        } else {
            // Asegura que la carpeta exista
            $carpeta_subida = __DIR__ . "/uploads/";
            if (!is_dir($carpeta_subida)) {
                mkdir($carpeta_subida, 0755, true);
            }

            $nombre_unico = uniqid() . "_" . $nombre_archivo;
            $destino = $carpeta_subida . $nombre_unico;

            if (!move_uploaded_file($imagen['tmp_name'], $destino)) {
                $err_imagen = "Error al subir la imagen.";
            } else {
                $imagen_ruta = $nombre_unico;
            }
        }
    }

}

// Verificar si hay errores antes de proceder
if (
    empty($err_nombre) && empty($err_apellidos) && empty($err_email) && empty($err_contrasena) &&
    empty($err_confirmar_contrasena) && empty($err_telefono) && empty($err_tipo_usuario) &&
    empty($err_fecha_nacimiento) && empty($err_sexo) && empty($err_imagen)
) {
    try {
        // Comprobar si el email ya está registrado
        $stmt = $_conexion->prepare("SELECT id_usuario FROM Usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $err_email = "Este correo ya está registrado.";
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Generar ID único asegurando que no esté en la BD
            do {
                $id_usuario = uniqid("usr_");
                $stmt_check_id = $_conexion->prepare("SELECT id_usuario FROM Usuario WHERE id_usuario = ?");
                $stmt_check_id->bind_param("s", $id_usuario);
                $stmt_check_id->execute();
                $stmt_check_id->store_result();
            } while ($stmt_check_id->num_rows > 0);

            // Insertar el usuario en la tabla Usuario
            $stmt = $_conexion->prepare("INSERT INTO Usuario (id_usuario, nombre, apellidos, email, contrasena, telefono, tipo_usuario, fecha_nacimiento, sexo, descripcion, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssssssssss",
                $id_usuario,
                $nombre,
                $apellidos,
                $email,
                $hash,
                $telefono,
                $tipo_usuario,
                $fecha_nacimiento,
                $sexo,
                $descripcion,
                $imagen_ruta
            );

            if ($stmt->execute()) {
                $registro_exitoso = "Registro completado correctamente.";
                $_POST = [];

                // Si el usuario es inquilino, agregarlo a la tabla Inquilino
                if ($tipo_usuario === 'inquilino') {
                    $id_inquilino = uniqid("inq_");
                    $preferencias_piso = NULL;
                    $datos_bancarios = NULL;

                    $sqlInq = "INSERT INTO Inquilino (id_inquilino, preferencias_piso, datos_bancarios, id_usuario) VALUES (?, ?, ?, ?)";
                    $stmtInq = $_conexion->prepare($sqlInq);

                    if (!$stmtInq) {
                        die("❌ Error al preparar la consulta para Inquilino: " . $_conexion->error);
                    }

                    $stmtInq->bind_param("ssss", $id_inquilino, $preferencias_piso, $datos_bancarios, $id_usuario);

                    if ($stmtInq->execute()) {
                        echo "✅ Inquilino registrado correctamente.";
                    } else {
                        echo "❌ Error al registrar inquilino: " . $_conexion->error;
                    }

                    $stmtInq->close();
                }

                // Si el usuario es propietario, agregarlo a la tabla Propietario
                if ($tipo_usuario === 'propietario') {
                    $id_propietario = uniqid("prop_");
                    $datos_bancarios = NULL;

                    $sqlProp = "INSERT INTO Propietario (id_propietario, datos_bancarios, id_usuario) VALUES (?, ?, ?)";
                    $stmtProp = $_conexion->prepare($sqlProp);

                    if (!$stmtProp) {
                        die("❌ Error al preparar la consulta para Propietario: " . $_conexion->error);
                    }

                    $stmtProp->bind_param("sss", $id_propietario, $datos_bancarios, $id_usuario);

                    if ($stmtProp->execute()) {
                        echo "✅ Propietario registrado correctamente.";
                    } else {
                        echo "❌ Error al registrar propietario: " . $_conexion->error;
                    }

                    $stmtProp->close();
                }
            } else {
                die("❌ Error al registrar usuario: " . $_conexion->error);
            }
        }
    } catch (Exception $e) {
        die("❌ Error del servidor: " . $e->getMessage());
    }
}
?>