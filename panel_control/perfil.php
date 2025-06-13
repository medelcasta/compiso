<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Información del Usuario</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  require('../utiles/conexion.php');
  require("../utiles/volver.php");
  ?>

  <style>
    body {
      background-image: url('https://www.transparenttextures.com/patterns/cartographer.png');
      background-color: #74c69d;
    }

    .enlarge-img {
      transition: transform 0.3s ease;
    }

    .enlarge-img:hover {
      transform: scale(1.05);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
    <?php
    if (isset($_GET["id_usuario"])) {
      $id_usuario = $_GET["id_usuario"];

      $sql = $_conexion->prepare("SELECT nombre, apellidos, email, telefono, tipo_usuario, descripcion, imagen, sexo, fecha_nacimiento FROM Usuario WHERE id_usuario = ?");
      $sql->bind_param("s", $id_usuario);
      $sql->execute();
      $resultado = $sql->get_result();

      if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        $tipo_usuario_texto = match ($usuario["tipo_usuario"]) {
          1 => "Inquilino",
          2 => "Propietario",
          default => "Administrador"
        };

        $tipo_icono = match ($usuario["tipo_usuario"]) {
          1 => "../images/inquilino.png",
          2 => "../images/propietario.png",
          default => "../images/administrador.png"
        };

        $genero_icono = ($usuario["sexo"] ?? '') === "Mujer" ? "../images/mujer.jpg" : "../images/hombre.jpg";

        // Imagen de perfil: personalizada o por defecto
        $imagen_path = !empty($usuario["imagen"])
          ? "../usuario/" . trim($usuario["imagen"])
          : "https://i.pravatar.cc/300"; // Imagen predeterminada moderna
    ?>
        <!-- Imagen de perfil -->
        <div class="flex flex-col items-center bg-gray-100 p-6">
          <img src="<?php echo htmlspecialchars($imagen_path); ?>" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover enlarge-img">
          <div class="flex items-center gap-2 mt-4">
            <img src="<?php echo $tipo_icono; ?>" alt="Tipo de usuario" class="w-6 h-6">
            <h1 class="text-xl font-semibold text-gray-800">
              <?php echo htmlspecialchars($usuario["nombre"] . ' ' . $usuario["apellidos"]); ?>
            </h1>
          </div>
        </div>

        <!-- Info del usuario -->
        <div class="px-6 py-4 space-y-3 text-gray-700">
          <p class="flex items-center gap-2">
            <img src="../images/email.png" class="w-5 h-5"> <?php echo htmlspecialchars($usuario["email"]); ?>
          </p>
          <p class="flex items-center gap-2">
            <img src="../images/movil.png" class="w-5 h-5"> <?php echo htmlspecialchars($usuario["telefono"]); ?>
          </p>
          <p class="flex items-center gap-2">
            <img src="<?php echo $genero_icono; ?>" class="w-5 h-5"> <?php echo htmlspecialchars($usuario["sexo"]); ?>
          </p>
          <p class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-6 4h4m-5 4h6m-3 4h3m-6 4h3m-6 4h6"></path>
            </svg>
            <?php echo htmlspecialchars($usuario["fecha_nacimiento"]); ?>
          </p>

          <!-- Descripción -->
          <div>
            <h2 class="text-base font-semibold text-gray-800 mb-1">Descripción:</h2>
            <p class="text-sm text-gray-600 text-justify">
              <?php echo !empty($usuario["descripcion"]) ? htmlspecialchars($usuario["descripcion"]) : "Sin descripción."; ?>
            </p>
          </div>
        </div>

        <!-- Botones -->
        <div class="bg-gray-100 border-t border-gray-200 px-6 py-4 flex justify-center gap-4">
          <form action="../conversaciones/dialogo.php" method="post">
            <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($id_usuario); ?>">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
              Enviar mensaje
            </button>
          </form>
          <a href="/matches/matches_usuarios.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
            Volver
          </a>
        </div>
    <?php
      } else {
        echo "<p class='p-6 text-center'>No se encontró información para el usuario seleccionado.</p>";
      }
    } else {
      echo "<p class='p-6 text-center'>Usuario no encontrado.</p>";
    }

    $_conexion->close();
    ?>
  </div>
</body>

</html>
