<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" type="image/jpg" href="../images/logo_compiso.ico" />
    <link rel="stylesheet" href="../css/sesiones.css">
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
            <a href="../index.php"><img src="../images/logo_compiso.png" alt="Logo" id="logo"></a>
                <h1 id="titulo">Compiso</h1>
            </div>
            <div>
                <a href="../index.php" id="login">Volver</a>
            </div>
        </div>
    </header>
    
    <div class="form-container">
        <h1>Inicio Sesión</h1>

        <?php
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Consultar la suscripción del usuario
            $sql = $_conexion->prepare("SELECT suscripcion FROM Usuario WHERE email = ?");
            $sql->bind_param("s", $email);
            $sql->execute();
            $resultado = $sql->get_result();
            $datos_usuario = $resultado->fetch_assoc();

            if ($datos_usuario["suscripcion"] == "gratuito" && $tipo_usuario == "1") {
                header("Location: pago.php"); // Redirigir a la página de pago
                exit;
            }

            $tmp_email = depurar($_POST["email"]);
            $tmp_contrasena = depurar($_POST["contrasena"]);

            if ($tmp_email == '') {
                $err_email = "El email es obligatorio";
            } else {
                if (!filter_var($tmp_email, FILTER_VALIDATE_EMAIL)) {
                    $err_email = "Formato de email no válido";
                } else {
                    $email = $tmp_email;
                }
            }

            if ($tmp_contrasena == '') {
                $err_contrasena = "La contraseña es obligatoria";
            } else {
                if (strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 15) {
                    $err_contrasena = "La contraseña debe tener entre 8 y 15 caracteres";
                } else {
                    $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                    if (!preg_match($patron, $tmp_contrasena)) {
                        $err_contrasena = "Debe contener mayúsculas, minúsculas, números o caracteres especiales";
                    } else {
                        $contrasena = $tmp_contrasena;
                    }
                }
            }

            if (isset($email) && isset($contrasena)) {
                $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE email = ?");
                $sql->bind_param("s", $email);
                $sql->execute();
                $resultado = $sql->get_result();

                if ($resultado->num_rows == 0) {
                    $err_email = "El email $email no está registrado";
                } else {
                    $datos_usuario = $resultado->fetch_assoc();
                    $acceso_concedido = password_verify($contrasena, $datos_usuario["contrasena"]);

                    if ($acceso_concedido) {
                        session_start();
                        $_SESSION["usuario"] = $datos_usuario["nombre"];
                        $_SESSION["tipo_usuario"] = $datos_usuario["tipo_usuario"];
                        $_SESSION['id_propietario'] = $datos_usuario['id_propietario']; 

                        // Redireccionar según el tipo de usuario
                        if ($datos_usuario["tipo_usuario"] == "1") {
                            header("location: ../inicio_inquilino.php");
                        } elseif ($datos_usuario["tipo_usuario"] == "2") {
                            header("location: ../inicio_propietario.php");
                        /*} elseif ($datos_usuario["tipo_usuario"] == "3") {
                            header("location: ../inicio.php");*/
                        } else {
                            // Si el tipo de usuario no es reconocido, redirigir a inicio genérico
                            header("location: ../index.php");
                        }
                        exit;
                    } else {
                        $err_contrasena = "La contraseña es incorrecta";
                    }
                }
            }
        }
        ?>

        <form action="" method="post">
            <div >
                <label for="email">Email</label><br>
                <input type="text"  name="email" id="email" size="20px"
                    value="<?php echo isset($tmp_email) ? htmlspecialchars($tmp_email) : ''; ?>">
                <?php if (isset($err_email)) echo "<span class='text-danger'>$err_email</span>"; ?>
            </div>

            <div >
                <label for="contrasena">Contraseña</label><br>
                <input type="password"  name="contrasena" id="contrasena" size="20px">
                <button type="button"  onclick="mostrarOcultar()">Mostrar / Ocultar</button>
                <?php if (isset($err_contrasena)) echo "<span class='text-danger'>$err_contrasena</span>"; ?>
            </div>

            <button type="submit">Iniciar sesión</button>
            <a  href="../index.php">Volver a Inicio</a>
            <a  href="formulario_pago.php">Premium</a>
            <a href="./recuperar_contrasena.php" >¿Olvidaste la contraseña?</a>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        function mostrarOcultar() {
            let input = document.getElementById("contrasena");
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
</body>
</html>