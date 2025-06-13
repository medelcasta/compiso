<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Iniciar Sesión</title>
    <link rel="icon" type="image/x-icon" href="../images/logo_compiso.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mint: '#74C69D',
                        tealCustom: '#40916c'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-image: url('../images/atras.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ?>
</head>

<?php
session_start();

require('../utiles/conexion.php');
require('../utiles/depurar.php');
require('../utiles/volver.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tmp_email = depurar($_POST["email"]);
    $tmp_contrasena = depurar($_POST["contrasena"]);

    // Validar email
    if ($tmp_email == '') {
        $err_email = "El email es obligatorio";
    } elseif (!filter_var($tmp_email, FILTER_VALIDATE_EMAIL)) {
        $err_email = "Formato de email no válido";
    } else {
        $email = $tmp_email;
    }

    // Validar contraseña
    if ($tmp_contrasena == '') {
        $err_contrasena = "La contraseña es obligatoria";
    } elseif (strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 15) {
        $err_contrasena = "La contraseña debe tener entre 8 y 15 caracteres";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $tmp_contrasena)) {
        $err_contrasena = "Debe contener mayúsculas, minúsculas, números o caracteres especiales";
    } else {
        $contrasena = $tmp_contrasena;
    }

    // Si no hay errores, intentar login
    if (!isset($err_email) && !isset($err_contrasena)) {
        // Buscar usuario por email
        $sql = $_conexion->prepare("SELECT id_usuario, nombre, contrasena, tipo_usuario FROM Usuario WHERE email = ?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $resultado = $sql->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verificar la contraseña con password_verify
            if (password_verify($contrasena, $usuario['contrasena'])) {
                // Guardar datos del usuario en la sesión (como array)
                $_SESSION["usuario"] = [
                    "id_usuario" => $usuario['id_usuario'],
                    "nombre" => $usuario['nombre'],
                    "email" => $email,
                    "tipo_usuario" => $usuario['tipo_usuario']
                ];
                $_SESSION["tipo_usuario"] = $usuario['tipo_usuario'];
                $_SESSION['idUsuario'] = $usuario['id_usuario'];

                // Redirigir según tipo de usuario, por ejemplo:
                if ($usuario['tipo_usuario'] == '1') {
                    header("Location: ../inicio_inquilino.php");
                } else {
                    // Ajusta esta ruta para otros tipos de usuario
                    header("Location: ../inicio_propietario.php");
                }
                exit;
            } else {
                $err_general = "Contraseña incorrecta.";
            }
        } else {
            $err_general = "No existe ningún usuario con ese email.";
        }
    }
}
?>


<body class="min-h-screen flex flex-col bg-gray-50 text-gray-800">

    <!-- HEADER -->
    <header class="bg-mint p-4 shadow-md">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="../index.php"><img src="../images/logo_compiso.png" alt="Logo"
                        class="rounded-lg w-12 h-12"></a>
                <h1 class="text-white text-2xl font-bold">Compiso</h1>
            </div>
            <a href="../index.php"
                class="bg-white text-tealCustom font-semibold py-2 px-4 rounded-lg shadow hover:bg-tealCustom hover:text-white transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2">
                Volver
            </a>
        </div>
    </header>

    <!-- MAIN con formulario -->
    <main class="container mx-auto px-6 py-16 flex justify-center">
        <section class="bg-white rounded-3xl max-w-md w-full p-10 sm:p-12 shadow-[0_0_15px_0_rgba(0,108,103,0.6)]">
            <h2 class="text-4xl font-extrabold mb-8 text-center text-tealCustom tracking-tight">Iniciar Sesión</h2>

            <?php if (isset($err_general)) {
                echo "<p class='text-center mb-4 text-red-600 font-semibold'>$err_general</p>";
            } ?>

            <form action="" method="post" class="space-y-7" novalidate>

                <div>
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="text" name="email" id="email"
                        value="<?php echo isset($tmp_email) ? htmlspecialchars($tmp_email) : ''; ?>"
                        placeholder="correo@ejemplo.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-describedby="email-error" />
                    <?php if (isset($err_email))
                        echo "<p id='email-error' class='mt-1 text-sm text-red-600 font-medium'>$err_email</p>"; ?>
                </div>

                <div>
                    <label for="contrasena" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="contrasena" id="contrasena" placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                            aria-describedby="password-error" />
                        <button type="button" onclick="mostrarOcultar()"
                            class="absolute right-3 top-3 text-tealCustom font-semibold text-sm hover:text-tealCustom/80 focus:outline-none focus:ring-2 focus:ring-tealCustom rounded select-none">
                            <i id="icono" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (isset($err_contrasena))
                        echo "<p id='password-error' class='mt-1 text-sm text-red-600 font-medium'>$err_contrasena</p>"; ?>
                </div>


                <button type="submit"
                    class="w-full bg-tealCustom text-white font-bold py-3 rounded-xl shadow-md hover:bg-tealCustom/90 focus:outline-none focus:ring-4 focus:ring-tealCustom/50 transition">
                    Iniciar Sesión
                </button>
            </form>

            <nav
                class="mt-8 flex flex-col space-y-3 text-center text-tealCustom font-semibold text-sm tracking-wide select-none">
                <a href="./registro.php"
                    class="hover:underline focus:outline-none focus:ring-2 focus:ring-tealCustom rounded">Aún no me he
                    registrado</a>
                <a href="../paypal/crear_plan.php"
                    class="hover:underline focus:outline-none focus:ring-2 focus:ring-tealCustom rounded">Premium</a>
                <a href="./recuperar_contrasena.php"
                    class="hover:underline focus:outline-none focus:ring-2 focus:ring-tealCustom rounded">¿Olvidaste la
                    contraseña?</a>
            </nav>
        </section>
    </main>

    <script>
        function mostrarOcultar() {
    var input = document.getElementById("contrasena");
    var icono = document.getElementById("icono");

    if (input.type === "password") {
        input.type = "text";
        icono.classList.remove("fa-eye");
        icono.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icono.classList.remove("fa-eye-slash");
        icono.classList.add("fa-eye");
    }
}

    </script>
</body>

</html>