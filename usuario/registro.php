<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="icon" type="image/jpg" href="../images/logo_compiso.ico" />
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/formularios.css">

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

    <header>
        <div >
            <div >
                <img src="../images/logo_compiso.png" alt="Logo" id="logo">
                <h1 id="titulo">Compiso</h1>
            </div>
            <div>
                <a href="./index.php" id="login">Cerrar sesión</a>
            </div>
        </div>
    </header>
    <div class="form-container">
        
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
            $imagen = $_FILES['imagenes'];

            // Validaciones de campos
            if ($tmp_nombre == '') {
                $err_nombre = "El nombre es obligatorio";
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

             // Validación de imagen
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($imagen["type"], $allowed_types)) {
            echo "<div class='alert alert-danger text-center mt-3'>Formato de imagen no válido.</div>";
            exit;
        }

        // Procesar imagen
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = uniqid() . "-" . basename($imagen["name"]);
        $target_file = $target_dir . $file_name;

        // Verificar si la imagen se mueve correctamente al servidor
        if (!move_uploaded_file($imagen["tmp_name"], $target_file)) {
            echo "<div class='alert alert-danger text-center mt-3'>Error al mover la imagen al servidor.</div>";
            echo "<pre>";
            print_r(error_get_last());
            echo "</pre>";
            exit;
        } else {
            echo "<div class='alert alert-success text-center mt-3'>Imagen subida correctamente: " . htmlspecialchars($file_name) . "</div>";
            
        }
        echo "<div class='alert alert-info'>Ruta completa destino: $target_file</div>";

            // Si los campos son correctos, inserta en la base de datos
            if (
                isset($nombre) && isset($contrasena_cifrada) && isset($email) && isset($apellidos) &&
                isset($telefono) && isset($tipo_usuario) && isset($fecha_nacimiento) && isset($sexo)
            ) {
                $id_usuario = uniqid(); // Generar un ID único para el usuario

                // Registrar al usuario en la tabla Usuario
                $sql = $_conexion->prepare("INSERT INTO Usuario (
                        id_usuario, nombre, contrasena, apellidos, email, telefono, tipo_usuario, fecha_nacimiento, sexo, descripcion, imagen
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");

                $sql->bind_param(
                    "ssssssissss",
                    $id_usuario,
                    $nombre,
                    $contrasena_cifrada,
                    $apellidos,
                    $email,
                    $telefono,
                    $tipo_usuario,
                    $fecha_nacimiento,
                    $sexo,
                    $descripcion,
                    $target_file
                );

                if ($sql->execute()) {
                    // Si el registro del usuario fue exitoso, registramos en Inquilino o Propietario
                    if ($tipo_usuario == 1) {
                        // Tipo de usuario es Inquilino
                        $preferencias = !empty($_POST["preferencias"]) ? depurar($_POST["preferencias"]) : NULL; // Si no se proporciona, asignar NULL
                        $datos_bancarios_inquilino = !empty($_POST["datos_bancarios"]) ? depurar($_POST["datos_bancarios"]) : NULL; // Si no se proporciona, asignar NULL
                        $id_inquilino = uniqid(); // Generar un ID único para el inquilino

                        // Insertar en la tabla Inquilino
                        $sql_inquilino = $_conexion->prepare("INSERT INTO Inquilino (id_inquilino, preferencias, datos_bancarios, id_usuario) VALUES (?, ?, ?, ?)");
                        $sql_inquilino->bind_param("ssss", $id_inquilino, $preferencias, $datos_bancarios_inquilino, $id_usuario);
                        if (!$sql_inquilino->execute()) {
                            echo "Error al registrar al inquilino: " . $sql_inquilino->error;
                        }
                    } elseif ($tipo_usuario == 2) {
                        // Tipo de usuario es Propietario
                        $datos_bancarios_propietario = !empty($_POST["datos_bancarios"]) ? depurar($_POST["datos_bancarios"]) : NULL; // Si no se proporciona, asignar NULL
                        $id_propietario = uniqid(); // Generar un ID único para el propietario

                        // Insertar en la tabla Propietario
                        $sql_propietario = $_conexion->prepare("INSERT INTO Propietario (id_propietario, datos_bancarios, id_usuario) VALUES (?, ?, ?)");
                        $sql_propietario->bind_param("sss", $id_propietario, $datos_bancarios_propietario, $id_usuario);
                        if (!$sql_propietario->execute()) {
                            echo "Error al registrar al propietario: " . $sql_propietario->error;
                        }
                    }

                    // Redirigir al inicio de sesión si todo fue exitoso
                    header("location: iniciar_sesion.php");
                    exit;
                } else {
                    echo "Error al registrar el usuario: " . $sql->error;
                }
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data" >
            <div >
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" 
                    value="<?php echo htmlspecialchars($_POST['nombre'] ?? '') ?>">
                <?php if (isset($err_nombre)) echo "<span class='text-danger'>$err_nombre</span>"; ?>
            </div>

            <div >
                <label class="form-label">Apellidos</label>
                <input type="text" name="apellidos" 
                    value="<?php echo htmlspecialchars($_POST['apellidos'] ?? '') ?>">
                <?php if (isset($err_apellidos)) echo "<span class='text-danger'>$err_apellidos</span>"; ?>
            </div>

            <div >
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" >
                <?php if (isset($err_contrasena)) echo "<span class='text-danger'>$err_contrasena</span>"; ?>
            </div>

            <div >
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirmar_contrasena" >
                <?php if (isset($err_confirmar)) echo "<span class='text-danger'>$err_confirmar</span>"; ?>
            </div>

            <div >
                <label class="form-label">Email</label>
                <input type="email" name="email" 
                    value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
                <?php if (isset($err_email)) echo "<span class='text-danger'>$err_email</span>"; ?>
            </div>

            <div >
                <label class="form-label">Teléfono</label>
                <input type="tel" name="telefono" 
                    value="<?php echo htmlspecialchars($_POST['telefono'] ?? '') ?>">
                <?php if (isset($err_telefono)) echo "<span class='text-danger'>$err_telefono</span>"; ?>
            </div>

            <div >
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" 
                    value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>">
                <?php if (isset($err_fecha_nacimiento)) echo "<span class='text-danger'>$err_fecha_nacimiento</span>"; ?>
            </div>

            <div >
                <label class="form-label">Sexo</label>
                <select name="sexo" >
                   
                <option value="">Seleccione...</option>
                    <option value="Hombre" <?php if (isset($_POST['sexo']) && $_POST['sexo'] == 'Hombre') echo 'selected'; ?>>Hombre</option>
                    <option value="Mujer" <?php if (isset($_POST['sexo']) && $_POST['sexo'] == 'Mujer') echo 'selected'; ?>>Mujer</option>
                    <option value="Otro" <?php if (isset($_POST['sexo']) && $_POST['sexo'] == 'Otro') echo 'selected'; ?>>Otro</option>
                </select>
                <?php if (isset($err_sexo)) echo "<span class='text-danger'>$err_sexo</span>"; ?>
            </div>

            <div>
                <label class="form-label">Tipo de usuario</label>
                <div>
                    <input type="radio" id="inquilino" name="tipo_usuario" value="1" <?php if (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == '1') echo 'checked'; ?>>
                    <label for="inquilino">Inquilino</label>
                </div>
                <div>
                    <input type="radio" id="propietario" name="tipo_usuario" value="2" <?php if (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == '2') echo 'checked'; ?>>
                    <label for="propietario">Propietario</label>
                </div>
                <?php if (isset($err_tipo_usuario)) echo "<span class='text-danger'>$err_tipo_usuario</span>"; ?>
            </div>


            <div >
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" ><?php echo htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div >
                <label class="form-label">Datos Bancarios</label>
                <input type="text" name="datos_bancarios" 
                    value="<?php echo htmlspecialchars($_POST['datos_bancarios'] ?? '') ?>">
            </div>

            <div  id="preferenciasContainer" style="display: none;">
                <label class="form-label">Preferencias (solo inquilinos)</label>
                <textarea name="preferencias" ><?php echo htmlspecialchars($_POST['preferencias'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="imagenes" class="form-label">Foto del usuario </label>
                <input type="file" class="form-control" name="imagenes" accept="image/*" required>
            </div>

            <button type="submit" >Registrarse</button>
        </form>
    </div>

    <script>
        // Mostrar u ocultar las preferencias dependiendo del tipo de usuario
        document.querySelector('select[name="tipo_usuario"]').addEventListener('change', function() {
            const preferenciasContainer = document.getElementById('preferenciasContainer');
            if (this.value == '1') {
                preferenciasContainer.style.display = 'block';
            } else {
                preferenciasContainer.style.display = 'none';
            }
        });

        // Mostrar preferencias al recargar si el valor es Inquilino
        window.addEventListener('load', function() {
            const tipoUsuario = document.querySelector('select[name="tipo_usuario"]').value;
            const preferenciasContainer = document.getElementById('preferenciasContainer');
            if (tipoUsuario == '1') {
                preferenciasContainer.style.display = 'block';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+OJ5n1hbQpC4eYfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
</body>

</html>
