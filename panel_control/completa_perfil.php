<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Completa tu perfil</title>

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
  <?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  session_start();
  if (!isset($_SESSION["usuario"])) {
    die("❌ Error: No has iniciado sesión.");
  }
  require("../utiles/conexion.php");
  require("../utiles/volver.php");
  if (!isset($_conexion)) {
    die("❌ Error: No hay conexión con la base de datos.");
  }
  $id_usuario_sesion = $_SESSION["usuario"]["id_usuario"] ?? null;

  if (!$id_usuario_sesion) {
    die("❌ Error: Usuario en sesión inválido.");
  }
  $sql = $_conexion->prepare("SELECT id_usuario, descripcion FROM Usuario WHERE id_usuario = ?");
  $sql->bind_param("i", $id_usuario_sesion);
  $sql->execute();
  $result = $sql->get_result();
  if ($result->num_rows === 0) {
    die("❌ Error: No se encontró al usuario.");
  }
  $usuario = $result->fetch_assoc();
  $id_usuario = $usuario["id_usuario"];
  $descripcion = $usuario["descripcion"];
  ?>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background-image: url('https://images.unsplash.com/photo-1586105251261-72a756497a12?auto=format&fit=crop&w=1470&q=80');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
</head>

<body class="min-h-screen flex flex-col bg-opacity-90">
  <header class="w-full bg-mint shadow-md py-4 px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="/">
          <img src="../images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12">
        </a>
        <h1 class="text-white text-2xl font-bold">Compiso</h1>
      </div>
      <div class="flex items-center gap-2">
        <a href="<?php echo obtenerEnlaceVolver(); ?>"
          class="border border-white text-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">Volver</a>
      </div>
    </div>
  </header>
  <div id="popupConocenos"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div id="popupContenido"
      class="bg-white p-6 rounded-2xl max-w-4xl w-full relative overflow-y-auto max-h-[90vh] transform scale-95 opacity-0 transition-all duration-300">
      <button onclick="cerrarPopup()"
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl font-bold">&times;</button>
      <h2 class="text-3xl font-bold mt-4 mb-4 text-mint text-center">Conócenos</h2>
      <div class="flex flex-wrap justify-center gap-6 items-center">
        <div class="border-4 border-mint rounded-2xl">
          <img src="../images/aurora.jpg" alt="Aurora" class="rounded-xl w-40 h-40 object-cover">
        </div>
        <div class="border-4 border-mint rounded-2xl">
          <img src="../images/paula.jpeg" alt="Paula" class="rounded-xl w-40 h-40 object-cover">
        </div>
        <div class="border-4 border-mint rounded-2xl">
          <img src="../images/carlos.jpeg" alt="Carlos" class="rounded-xl w-40 h-40 object-cover">
        </div>
        <div class="border-4 border-mint rounded-2xl">
          <img src="../images/luis.jpeg" alt="Luis" class="rounded-xl w-40 h-40 object-cover">
        </div>
      </div>
      <p class="mt-6 text-center max-w-3xl mx-auto text-gray-700 leading-relaxed tracking-wide ">
        <span class="font-semibold text-mint">Compiso</span> lo formamos un grupo de jóvenes dedicados <span
          class="font-semibold text-black">al diseño y desarrollo web.</span>
        <br><br>
        Motivados por solucionar los problemas relevantes a nuestra generación, nos decidimos por adentrarnos en el
        mundo del alquiler en Málaga capital y presentar una opción innovadora para la gente joven que intenta encontrar
        un hueco en esta ciudad llena de oportunidades.
        <br><br>
        En <span class="font-semibold text-mint">Compiso</span> ofrecemos la oportunidad de encontrar gente compatible
        de forma sencilla y directa, además de un piso que se adecue a tus necesidades.
        <br><br>
        <span class="italic text-gray-800">A través de nuestra plataforma, el piso ideal te elige a ti.</span>
      </p>
    </div>
  </div>
  <main class="flex-grow flex flex-col justify-center items-center px-4 py-10">
    <div
      class="w-full max-w-7xl grid grid-cols-1 md:grid-cols-2 gap-8 bg-white bg-opacity-80 backdrop-blur-md rounded-xl shadow-lg p-8">
      <div class="bg-tealCustom bg-opacity-90 text-white rounded-2xl shadow-xl p-6">
        <h2 class="text-3xl font-extrabold text-center mb-6">Completa tu perfil</h2>
        <form id="formPerfil" action="completa_perfil.php" method="POST">
          <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4" id="perfil-steps"></div>
          <div class="text-center mt-4">
            <button type="button" id="siguientePerfil"
              class="bg-white text-tealCustom font-semibold px-6 py-2 rounded-full hover:bg-green-100 transition">
              Siguiente
            </button>
            <button id="enviarPerfil"
              class="bg-orange text-dark-500 font-semibold px-6 py-2 rounded-full hover:bg-blue-100 transition">
              Enviar información de perfil
            </button>
          </div>
        </form>
      </div>
      <div class="bg-tealCustom bg-opacity-90 text-white rounded-2xl shadow-xl p-6">
        <h2 class="text-3xl font-extrabold text-center mb-6">Preferencias de piso</h2>
        <form id="formPiso" action="completa_perfil.php" method="POST">
          <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4" id="piso-steps"></div>
          <div class="text-center mt-4">
            <button type="button" id="siguientePiso"
              class="bg-white text-tealCustom font-semibold px-6 py-2 rounded-full hover:bg-green-100 transition">
              Siguiente
            </button>
            <button id="enviarPiso"
              class="bg-orange text-dark-500 font-semibold px-6 py-2 rounded-full hover:bg-green-100 transition">
              Enviar información de piso
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
  <?php
  $id_usuario_sesion = $_SESSION["usuario"]["id_usuario"] ?? null;

  if (!$id_usuario_sesion) {
    die("❌ Error: ID de usuario no proporcionado.");
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["tipo"])) {
      die('<footer class="bg-black text-white py-6 px-4 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
      <p class="text-sm">&copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.</p>
      <a href="https://www.instagram.com/compiso_web" target="_blank" rel="noopener noreferrer"
        class="flex items-center space-x-2 hover:text-mint transition-colors duration-300">
        <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path
            d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.25-.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </svg>
        <span class="text-sm">Instagram</span>
      </a>
    </div>
  </footer>');
    }

    if (empty($_POST)) {
      die("❌ No se han recibido datos válidos.");
    }

    $tipoFormulario = $_POST["tipo"];

    if ($tipoFormulario === "perfil") {
        // Obtener la descripción actual del usuario
        $sql_select = $_conexion->prepare("SELECT descripcion FROM Usuario WHERE id_usuario = ?");
        $sql_select->bind_param("s", $id_usuario_sesion);
        $sql_select->execute();
        $resultado = $sql_select->get_result();
        $fila = $resultado->fetch_assoc();
        $descripcion_actual = $fila ? $fila["descripcion"] : "";
        $sql_select->close();

        // Construcción de la nueva descripción sin incluir campos irrelevantes
        $nuevaDescripcion = "";
        foreach ($_POST as $campo => $valor) {
            if (!in_array($campo, ["tipo", "id_usuario", "id"])) { 
                $nuevaDescripcion .= ucfirst($campo) . ": " . htmlspecialchars($valor) . ". ";
            }
        }

        // Concatenar la nueva descripción a la existente
        $descripcion_actualizada = trim($descripcion_actual . " " . $nuevaDescripcion);

        // Actualizar la descripción en la base de datos
        $sql_update = $_conexion->prepare("UPDATE Usuario SET descripcion = ? WHERE id_usuario = ?");
        $sql_update->bind_param("ss", $descripcion_actualizada, $id_usuario_sesion);

        // Mostrar la descripción antes de actualizar (para depuración)
        echo "<br> Descripción anterior: " . htmlspecialchars($descripcion_actual);
        echo "<br> Nueva descripción (perfil): " . htmlspecialchars($descripcion_actualizada);

        if ($sql_update->execute()) {
            echo "✅ Descripción del perfil actualizada correctamente.";
        } else {
            echo "❌ Error al actualizar la descripción: " . $_conexion->error;
        }

        $sql_update->close();

    } elseif ($tipoFormulario === "piso") {
        // Gestión de preferencias de búsqueda de piso
        echo "ID Usuario Sesión: " . htmlspecialchars($id_usuario_sesion);

        $preferencias_piso = "";
        foreach ($_POST as $campo => $valor) {
            if ($campo !== "tipo") {
                $preferencias_piso .= ucfirst($campo) . ": " . htmlspecialchars($valor) . ". ";
            }
        }

        $preferencias_piso_actualizadas = trim($preferencias_piso);
        $sql_update = $_conexion->prepare("UPDATE Inquilino SET preferencias_piso = ? WHERE id_usuario = ?");
        $sql_update->bind_param("ss", $preferencias_piso_actualizadas, $id_usuario_sesion);

        // Mostrar preferencias antes de actualizar (para depuración)
        echo "<br> Nueva Preferencia de Piso: " . htmlspecialchars($preferencias_piso_actualizadas);

        if ($sql_update->execute()) {
            echo "✅ Preferencias de piso actualizadas correctamente.";
        } else {
            echo "❌ Error al actualizar las preferencias de piso: " . $_conexion->error;
        }

        $sql_update->close();
    } else {
        die("❌ Error: Tipo de formulario no reconocido.");
    }
}
?>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const perfilPreguntas = [
        { label: "¿Cuál es tu hobby favorito?", name: "hobby" },
        { label: "¿Qué tipo de música te gusta?", name: "musica" },
        { label: "¿Cómo describirías tu personalidad en pocas palabras?", name: "personalidad" },
        { label: "¿Cuál es tu destino de viaje soñado?", name: "viaje" },
        { label: "¿Cuál es tu comida favorita?", name: "comida" },
        { label: "¿Qué deporte practicas o te gustaría practicar?", name: "deporte" },
        { label: "¿Cuál es tu película o serie favorita?", name: "pelicula" },
        { label: "¿Cuál es tu mayor meta en la vida?", name: "meta" },
        { label: "¿Qué idioma te gustaría aprender?", name: "idioma" }
      ];
      const pisoPreguntas = [
        { label: "¿En qué ciudad estás buscando el piso?", name: "ciudad" },
        { label: "¿En qué zona o calle?", name: "direccion" },
        { label: "¿De cuántas habitaciones?", name: "habitaciones" },
        { label: "¿De cuántos baños?", name: "banos" },
        { label: "¿Cuál es tu presupuesto para el piso?", name: "precio" },
        { label: "¿Prefieres amueblado o sin amueblar?", name: "amueblado" },
        { label: "¿Cuánto estás dispuesto a pagar de fianza?", name: "fianza" },
        { label: "¿Necesitas que tenga garaje?", name: "garaje" },
        { label: "¿Te gustaría que tenga terraza o balcón?", name: "terraza" }
      ];
      let perfilIndex = 0;
      let pisoIndex = 0;
      let perfilDatos = {};
      let pisoDatos = {};
      function mostrarPregunta(lista, index, contenedorId, siguienteBtnId) {
        const contenedor = document.getElementById(contenedorId);
        contenedor.innerHTML = "";

        if (index >= lista.length) {
          contenedor.innerHTML = `<div class="col-span-2 text-center text-xl font-bold">✅ Completado`;
          document.getElementById(siguienteBtnId).style.display = "none";
          return;
        }
        const pregunta = lista[index];
        contenedor.innerHTML = `
            <div class="col-span-1 sm:col-span-2 text-center">
                <label class="block text-black font-semibold mb-2">${pregunta.label}</label>
                <input type="text" id="${pregunta.name}" name="${pregunta.name}" class="w-full px-4 py-2 rounded text-black" />
            </div>
        `;
      }
      mostrarPregunta(perfilPreguntas, perfilIndex, "perfil-steps", "siguientePerfil");
      mostrarPregunta(pisoPreguntas, pisoIndex, "piso-steps", "siguientePiso");
      function obtenerDatos(pregunta, tipo) {
        let valor = document.getElementById(pregunta.name).value.trim();
        if (valor === "") {
          alert(`Por favor, completa el campo: ${pregunta.label}`);
          return false;
        }
        if (tipo === "perfil") {
          perfilDatos[pregunta.name] = valor;
        } else {
          pisoDatos[pregunta.name] = valor;
        }
        return true;
      }
      document.getElementById("siguientePerfil").addEventListener("click", () => {
        if (obtenerDatos(perfilPreguntas[perfilIndex], "perfil")) {
          perfilIndex++;
          mostrarPregunta(perfilPreguntas, perfilIndex, "perfil-steps", "siguientePerfil");
        }
      });
      document.getElementById("siguientePiso").addEventListener("click", () => {
        if (obtenerDatos(pisoPreguntas[pisoIndex], "piso")) {
          pisoIndex++;
          mostrarPregunta(pisoPreguntas, pisoIndex, "piso-steps", "siguientePiso");
        }
      });
      function enviarDatos(datos, tipo) {
        let formData = new FormData();
        formData.append("tipo", tipo);
        for (let key in datos) {
          formData.append(key, datos[key]);
        }
        console.log(`📤 Enviando datos (${tipo}):`, Object.fromEntries(formData.entries()));
        fetch("completa_perfil.php", {
          method: "POST",
          body: formData
        })
          .then(response => response.text())
          .then(data => {
            console.log(`📩 Respuesta del servidor (${tipo}):`, data);
            alert(`✅ Datos de ${tipo} enviados correctamente`);
          })
          .catch(error => console.error(`❌ Error al enviar datos (${tipo}):`, error));
      }
      let botonEnviarPerfil = document.getElementById("enviarPerfil");
      if (botonEnviarPerfil) {
        botonEnviarPerfil.addEventListener("click", () => {
          if (Object.keys(perfilDatos).length === 0) {
            alert("No has completado ninguna pregunta de perfil.");
            return;
          }
          enviarDatos(perfilDatos, "perfil");
        });
      }
      let botonEnviarPiso = document.getElementById("enviarPiso");
      if (botonEnviarPiso) {
        botonEnviarPiso.addEventListener("click", () => {
          if (Object.keys(pisoDatos).length === 0) {
            alert("No has completado ninguna pregunta de búsqueda de piso.");
            return;
          }
          enviarDatos(pisoDatos, "piso");
        });
      }
    });
  </script>
  </head>
  <footer class="bg-black text-white py-6 px-4 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
      <p class="text-sm">&copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.</p>
      <a href="https://www.instagram.com/compiso_web" target="_blank" rel="noopener noreferrer"
        class="flex items-center space-x-2 hover:text-mint transition-colors duration-300">
        <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path
            d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.25-.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </svg>
        <span class="text-sm">Instagram</span>
      </a>
    </div>
  </footer>
</body>

</html>