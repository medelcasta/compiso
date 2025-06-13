<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require '../utiles/conexion.php';
require '../utiles/depurar.php';
session_start();

$mensaje = "";

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

            $_SESSION["pendiente_email"] = $email;
            $_SESSION["pendiente_usuario"] = $usuario;
            $_SESSION["pendiente_link"] = "http://compiso.infy.uk/usuario/reestablecer_contrasena.php?token=$token";

            header("Location: ../utiles/enviar_emailjs.html");
            exit;
        } else {
            $mensaje = "<div class='text-red-600 text-center font-semibold mt-4'>El correo electrónico no está registrado.</div>";
        }

        $_conexion->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recuperar Contraseña - Compiso</title>
  <link rel="icon" type="image/jpg" href="../images/logo_compiso.ico" />
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
</head>

<body class="bg-gray-50 text-gray-800">
  <header class="bg-mint p-4 shadow-md">
    <div class="container mx-auto px-6 flex justify-between items-center">
      <div class="flex items-center gap-4">
        <a href="../index.php"><img src="../images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12"></a>
        <h1 class="text-white text-2xl font-bold">Compiso</h1>
      </div>
      <a href="./index.php"
        class="bg-white text-tealCustom font-semibold py-2 px-4 rounded-lg shadow hover:bg-tealCustom hover:text-white transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2">
        Cerrar sesión
      </a>
    </div>
  </header>

  <main class="container mx-auto px-6 py-16 flex justify-center">
    <section class="bg-white rounded-3xl max-w-md w-full p-10 sm:p-12 shadow-[0_0_15px_0_rgba(0,108,103,0.6)]">
      <h2 class="text-3xl font-extrabold mb-8 text-center text-tealCustom tracking-tight">Recuperar Contraseña</h2>

      <?php if ($mensaje) echo $mensaje; ?>

      <form action="" method="post" class="space-y-7" novalidate>
        <div>
          <label for="email" class="block text-gray-700 font-semibold mb-2">Correo Electrónico</label>
          <input type="email" name="email" id="email"
            value="<?php echo isset($tmp_email) ? htmlspecialchars($tmp_email) : ''; ?>"
            placeholder="correo@ejemplo.com"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom" />
          <?php if (isset($err_email)) echo "<p class='mt-1 text-sm text-red-600 font-medium'>$err_email</p>"; ?>
        </div>

        <button type="submit"
          class="w-full bg-tealCustom text-white font-bold py-3 rounded-xl shadow-md hover:bg-tealCustom/90 focus:outline-none focus:ring-4 focus:ring-tealCustom/50 transition">
          Enviar enlace de recuperación
        </button>
      </form>

      <nav class="mt-8 flex flex-col space-y-3 text-center text-tealCustom font-semibold text-sm tracking-wide select-none">
        <a href="index.php" class="hover:underline focus:outline-none focus:ring-2 focus:ring-tealCustom rounded">Volver al inicio</a>
      </nav>
    </section>
  </main>

</body>

</html>
