<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../utiles/conexion.php');
    require('../utiles/depurar.php');
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Registro</h1>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_nombre = depurar($_POST["nombre"]);
            $tmp_contrasena = depurar($_POST["contrasena"]);
            $tmp_confirmar = depurar($_POST["confirmar_contrasena"]);
            $tmp_email = depurar($_POST["email"]);
            $tmp_apellidos = depurar($_POST["apellidos"]);
            $tmp_telefono = depurar($_POST["telefono"]);
            $tmp_tipo_usuario = $_POST["tipo_usuario"];
            $tmp_fecha_nacimiento = $_POST["fecha_nacimiento"];
            $tmp_sexo = depurar($_POST["sexo"]);
            $tmp_descripcion = depurar($_POST["descripcion"]);

            if ($tmp_nombre == '') {
                $err_nombre = "El nombre es obligatorio";
            } else {
                $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre = ?");
                $sql->bind_param("s", $tmp_nombre);
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows == 1) {
                    $err_nombre = "El nombre $tmp_nombre ya existe";
                } else {
                    if (strlen($tmp_nombre) < 3 || strlen($tmp_nombre) > 15) {
                        $err_nombre = "El nombre debe tener entre 3 y 15 caracteres";
                    } else {
                        $patron = "/^[a-zA-Z0-9]+$/";
                        if (!preg_match($patron, $tmp_nombre)) {
                            $err_nombre = "El nombre solo puede contener letras y números";
                        } else {
                            $nombre = $tmp_nombre;
                        }
                    }
                }
            }

            if ($tmp_contrasena == '') {
                $err_contrasena = "La contraseña es obligatoria";
            } else {
                if (strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 15) {
                    $err_contrasena = "Debe tener entre 8 y 15 caracteres";
                } else {
                    $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                    if (!preg_match($patron, $tmp_contrasena)) {
                        $err_contrasena = "Debe contener mayúsculas, minúsculas, un número o carácter especial";
                    } else {
                        if ($tmp_confirmar != $tmp_contrasena) {
                            $err_confirmar = "Las contraseñas no coinciden";
                        } else {
                            $contrasena = $tmp_contrasena;
                            $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                        }
                    }
                }
            }

            if ($tmp_email == '') {
                $err_email = "El email es obligatorio";
            } else {
                if (!filter_var($tmp_email, FILTER_VALIDATE_EMAIL)) {
                    $err_email = "El formato de email no es válido";
                } else {
                    $email = $tmp_email;
                }
            }

            if ($tmp_apellidos == '') {
                $err_apellidos = "Los apellidos son obligatorios";
            } else {
                $apellidos = $tmp_apellidos;
            }

            if ($tmp_telefono == '') {
                $err_telefono = "El teléfono es obligatorio";
            } else {
                if (!is_numeric($tmp_telefono)) {
                    $err_telefono = "Debe contener solo números";
                } else {
                    $telefono = $tmp_telefono;
                }
            }

            if ($tmp_tipo_usuario == '') {
                $err_tipo_usuario = "El tipo de usuario es obligatorio";
            } else {
                if ($tmp_tipo_usuario != 1 && $tmp_tipo_usuario != 2) {
                    $err_tipo_usuario = "Tipo de usuario no válido";
                } else {
                    $tipo_usuario = $tmp_tipo_usuario;
                }
            }

            if ($tmp_fecha_nacimiento == '') {
                $err_fecha_nacimiento = "La fecha de nacimiento es obligatoria";
            } else {
                $fecha_nacimiento = $tmp_fecha_nacimiento;
            }

            if ($tmp_sexo == '') {
                $err_sexo = "El sexo es obligatorio";
            } else {
                $sexo = $tmp_sexo;
            }

            $descripcion = $tmp_descripcion;

            if (
                isset($nombre) && isset($contrasena_cifrada) && isset($email) && isset($apellidos) &&
                isset($telefono) && isset($tipo_usuario) && isset($fecha_nacimiento) && isset($sexo)
            ) {
                $id_usuario = uniqid();

                $sql = $_conexion->prepare("INSERT INTO Usuario (
                        id_usuario, nombre, contrasena, apellidos, email, telefono, tipo_usuario, fecha_nacimiento, sexo, descripcion
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $sql->bind_param(
                    "ssssssisss",
                    $id_usuario,
                    $nombre,
                    $contrasena_cifrada,
                    $apellidos,
                    $email,
                    $telefono,
                    $tipo_usuario,
                    $fecha_nacimiento,
                    $sexo,
                    $descripcion
                );

                if ($sql->execute()) {
                    header("location: iniciar_sesion.php");
                    exit;
                } else {
                    echo "Error al registrar el usuario: " . $sql->error;
                }
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? '') ?>">
                <?php if (isset($err_nombre))
                    echo "<span class='text-danger'>$err_nombre</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['apellidos'] ?? '') ?>">
                <?php if (isset($err_apellidos))
                    echo "<span class='text-danger'>$err_apellidos</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-control">
                <?php if (isset($err_contrasena))
                    echo "<span class='text-danger'>$err_contrasena</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirmar_contrasena" class="form-control">
                <?php if (isset($err_confirmar))
                    echo "<span class='text-danger'>$err_confirmar</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if (isset($err_email))
                    echo "<span class='text-danger'>$err_email</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="tel" name="telefono" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['telefono'] ?? '') ?>">
                <?php if (isset($err_telefono))
                    echo "<span class='text-danger'>$err_telefono</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control"
                    value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>">
                <?php if (isset($err_fecha_nacimiento))
                    echo "<span class='text-danger'>$err_fecha_nacimiento</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Sexo</label>
                <select name="sexo" class="form-control">
                    <option value="">Selecciona</option>
                    <option value="Hombre" <?php if (($_POST['sexo'] ?? '') == 'Hombre')
                        echo 'selected'; ?>>Hombre
                    </option>
                    <option value="Mujer" <?php if (($_POST['sexo'] ?? '') == 'Mujer')
                        echo 'selected'; ?>>Mujer</option>
                    <option value="Otro" <?php if (($_POST['sexo'] ?? '') == 'Otro')
                        echo 'selected'; ?>>Otro</option>
                </select>
                <?php if (isset($err_sexo))
                    echo "<span class='text-danger'>$err_sexo</span>"; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción (opcional)</label>
                <textarea name="descripcion" class="form-control"
                    rows="3"><?php echo htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de usuario</label>
                <select name="tipo_usuario" class="form-control">
                    <option value="">Selecciona</option>
                    <option value="1" <?php if (($_POST['tipo_usuario'] ?? '') == '1')
                        echo 'selected'; ?>>Inquilino
                    </option>
                    <option value="2" <?php if (($_POST['tipo_usuario'] ?? '') == '2')
                        echo 'selected'; ?>>Propietario
                    </option>
                </select>
                <?php if (isset($err_tipo_usuario))
                    echo "<span class='text-danger'>$err_tipo_usuario</span>"; ?>
            </div>

            <button type="submit" class="btn btn-success">Registrar</button>
            <a href="./iniciar_sesion.php" class="btn btn-link">Ya tengo cuenta</a>
            <a href="../index.php" class="btn btn-secondary">Volver a Inicio</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>