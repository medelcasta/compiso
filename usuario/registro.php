<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro - Compiso</title>
    <link rel="icon" type="image/jpg" href="../images/logo_compiso.ico" />
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
            background-image: url('../images/fondo.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require('../utiles/conexion.php');
    require('../utiles/depurar.php');
    require("./funcionalidad_registro.php");
    ?>

    <script>
        // Validación en vivo para la edad >= 18 años
        function validarEdad() {
            const inputFecha = document.getElementById('fecha_nacimiento');
            const errorEdad = document.getElementById('error_edad');
            const fechaIngresada = new Date(inputFecha.value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaIngresada.getFullYear();
            const m = hoy.getMonth() - fechaIngresada.getMonth();
            if (
                edad < 18 ||
                (edad === 18 && m < 0) ||
                (edad === 18 && m === 0 && hoy.getDate() < fechaIngresada.getDate())
            ) {
                errorEdad.textContent = "Debes ser mayor de 18 años";
                inputFecha.setCustomValidity("Debes ser mayor de 18 años");
            } else {
                errorEdad.textContent = "";
                inputFecha.setCustomValidity("");
            }
        }

        window.addEventListener('load', () => {
            const inputFecha = document.getElementById('fecha_nacimiento');
            inputFecha.addEventListener('input', validarEdad);

            const form = document.querySelector('form');
            form.addEventListener('submit', (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    form.querySelectorAll(':invalid').forEach((el) => {
                        el.reportValidity();
                    });
                }
            });
        });
    </script>
</head>

<body class="min-h-screen flex flex-col bg-white text-gray-800 font-sans">

    <!-- HEADER -->
    <header class="w-full bg-mint shadow-md py-4 px-6">
  <div class="max-w-7xl mx-auto flex items-center justify-between">
    <div class="flex items-center gap-4">
      <a href="/">
        <img src="../images/logo_compiso.png" alt="Logo" class="rounded-lg w-12 h-12">
      </a>
      <h1 class="text-white text-2xl font-bold">Compiso</h1>
    </div>
    <div class="flex items-center gap-2">
        <a href="/" class="border border-white text-white px-4 py-2 rounded-lg hover:bg-white hover:text-mint transition">Volver</a>
    </div>
  </div>
</header>

    <!-- MAIN FORM CONTAINER -->
    <main class="flex-grow container mx-auto px-6 py-16 flex justify-center">
        <section class="bg-white rounded-3xl max-w-4xl w-full p-10 sm:p-12 shadow-[0_0_15px_0_rgba(0,108,103,0.6)]"
            aria-labelledby="titulo-registro">

            <h2 id="titulo-registro" class="text-4xl font-extrabold mb-8 text-center text-tealCustom tracking-tight">
                Registro
            </h2>

            <form action="" method="post" enctype="multipart/form-data" novalidate
                class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6" aria-describedby="form-errors">

                <div>
                    <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required
                        value="<?php echo htmlspecialchars($nombre ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error-nombre" />
                    <?php if (!empty($err_nombre))
                        echo "<p id='error-nombre' class='mt-1 text-sm text-red-600 font-medium'>$err_nombre</p>"; ?>
                </div>

                <div>
                    <label for="apellidos" class="block text-gray-700 font-semibold mb-2">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" required
                        value="<?php echo htmlspecialchars($apellidos ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error-apellidos" />
                    <?php if (!empty($err_apellidos))
                        echo "<p id='error-apellidos' class='mt-1 text-sm text-red-600 font-medium'>$err_apellidos</p>"; ?>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" id="email" required
                        value="<?php echo htmlspecialchars($email ?? ''); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error-email" />
                    <?php if (!empty($err_email))
                        echo "<p id='error-email' class='mt-1 text-sm text-red-600 font-medium'>$err_email</p>"; ?>
                </div>

                <div>
                    <label for="contrasena" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="contrasena" id="contrasena" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                            aria-required="true" aria-describedby="error-contrasena" />
                        <button type="button" onclick="mostrarOcultar()"
                            class="absolute right-3 top-3 text-tealCustom font-semibold text-sm hover:text-tealCustom/80 focus:outline-none focus:ring-2 focus:ring-tealCustom rounded select-none">
                            <i id="icono" class="fas fa-eye"></i>
                        </button>
                        <?php if (!empty($err_contrasena))
                            echo "<p id='error-contrasena' class='mt-1 text-sm text-red-600 font-medium'>$err_contrasena</p>"; ?>
                    </div>
                </div>


                <div>
                    <label for="telefono" class="block text-gray-700 font-semibold mb-2">Teléfono</label>
                    <input type="tel" name="telefono" id="telefono" required
                        value="<?php echo htmlspecialchars($telefono ?? ''); ?>" maxlength="9" pattern="\d{9}"
                        placeholder="Ej: 612345678"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error-telefono" />
                    <?php if (!empty($err_telefono))
                        echo "<p id='error-telefono' class='mt-1 text-sm text-red-600 font-medium'>$err_telefono</p>"; ?>
                </div>

                <div>
                    <label for="confirmar_contrasena" class="block text-gray-700 font-semibold mb-2">Confirmar
                        contraseña</label>
                    <div class="relative">
                        <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                            aria-required="true" aria-describedby="error-confirmar_contrasena" />
                        <button type="button" onclick="mostrarOcultar2()"
                            class="absolute right-3 top-3 text-tealCustom font-semibold text-sm hover:text-tealCustom/80 focus:outline-none focus:ring-2 focus:ring-tealCustom rounded select-none">
                            <i id="icono" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <?php if (!empty($err_confirmar_contrasena))
                        echo "<p id='error-confirmar_contrasena' class='mt-1 text-sm text-red-600 font-medium'>$err_confirmar_contrasena</p>"; ?>
                </div>

                <div>
                    <label for="tipo_usuario" class="block text-gray-700 font-semibold mb-2">Tipo de usuario</label>
                    <select name="tipo_usuario" id="tipo_usuario" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error-tipo_usuario">
                        <option value="" disabled <?php if (empty($tipo_usuario))
                            echo 'selected'; ?>>Seleccione
                        </option>
                        <option value="propietario" <?php if (($tipo_usuario ?? '') === 'propietario')
                            echo 'selected'; ?>>Propietario</option>
                        <option value="inquilino" <?php if (($tipo_usuario ?? '') === 'inquilino')
                            echo 'selected'; ?>>Inquilino</option>
                    </select>
                    <?php if (!empty($err_tipo_usuario))
                        echo "<p id='error-tipo_usuario' class='mt-1 text-sm text-red-600 font-medium'>$err_tipo_usuario</p>"; ?>
                </div>

                <div>
                    <label for="fecha_nacimiento" class="block text-gray-700 font-semibold mb-2">Fecha de
                        nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                        value="<?php echo htmlspecialchars($fecha_nacimiento ?? ''); ?>"
                        max="<?php echo date('Y-m-d'); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error_edad" />
                    <p id="error_edad" class="mt-1 text-sm text-red-600 font-medium" aria-live="polite"></p>
                    <?php if (!empty($err_fecha_nacimiento))
                        echo "<p id='error_fecha_nacimiento' class='mt-1 text-sm text-red-600 font-medium'>$err_fecha_nacimiento</p>"; ?>
                </div>


                <div>
                    <label for="sexo" class="block text-gray-700 font-semibold mb-2">Sexo</label>
                    <select name="sexo" id="sexo" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-required="true" aria-describedby="error-sexo">
                        <option value="" disabled <?php if (empty($sexo))
                            echo 'selected'; ?>>Seleccione</option>
                        <option value="Hombre" <?php if (($sexo ?? '') === 'Hombre')
                            echo 'selected'; ?>>Hombre</option>
                        <option value="Mujer" <?php if (($sexo ?? '') === 'Mujer')
                            echo 'selected'; ?>>Mujer</option>
                        <option value="Otro" <?php if (($sexo ?? '') === 'Otro')
                            echo 'selected'; ?>>Otro</option>
                    </select>
                    <?php if (!empty($err_sexo))
                        echo "<p id='error-sexo' class='mt-1 text-sm text-red-600 font-medium'>$err_sexo</p>"; ?>

                </div>


                <?php if (!empty($err_sexo))
                    echo "<p class='text-red-500'>$err_sexo</p>"; ?>


                <div>

                    <label for="imagen" class="block text-gray-700 font-semibold mb-2">Foto de perfil</label>
                    <input type="file" name="imagen" id="imagen" accept="image/png, image/jpeg"
                        class="w-full text-gray-700" aria-describedby="error-imagen" required />
                    <!--<button type="submit" class="mt-2 bg-tealCustom text-white px-4 py-2 rounded-lg">Subir
                            Imagen</button>-->

                </div>

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
                        $archivo = $_FILES["imagen"];
                        $nombre = basename($archivo["name"]);
                        $ruta_destino = "uploads/" . $nombre;

                        // Verifica que sea PNG o JPG
                        $tipo = $archivo["type"];
                        if ($tipo == "image/png" || $tipo == "image/jpeg" || $tipo == "image/gif" || $tipo == "image/webp") {
                            // Mueve el archivo a la carpeta de destino
                            if (move_uploaded_file($archivo["tmp_name"], $ruta_destino)) {
                                echo "Imagen subida con éxito: $nombre";
                            } else {
                                echo "Error al subir la imagen.";
                            }
                        } else {
                            echo "Formato no permitido.";
                        }
                    } else {
                        echo "No se seleccionó ninguna imagen o hubo un error.";
                    }
                }
                ?>

                <br>

                <div class="sm:col-span-2">
                    <label for="descripcion" class="block text-gray-700 font-semibold mb-2">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tealCustom focus:border-tealCustom"
                        aria-describedby="error-descripcion"><?php echo htmlspecialchars($descripcion ?? ''); ?></textarea>
                    <?php if (!empty($err_descripcion))
                        echo "<p id='error-descripcion' class='mt-1 text-sm text-red-600 font-medium'>$err_descripcion</p>"; ?>
                </div>



                <div class="sm:col-span-2 flex justify-center">
                    <button type="submit"
                        class="bg-tealCustom hover:bg-mint text-white font-bold py-3 px-8 rounded-lg shadow-md focus:outline-none focus:ring-4 focus:ring-tealCustom focus:ring-opacity-50 transition"
                        aria-label="Enviar formulario de registro">
                        Registrar
                    </button>
                </div>

                <?php if (!empty($err_general)): ?>
                    <p id="form-errors" class="sm:col-span-2 text-center text-red-600 font-semibold">
                        <?php echo $err_general; ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($registro_exitoso)): ?>
                    <p class="sm:col-span-2 text-center text-green-600 font-semibold">
                        <?php echo $registro_exitoso; ?>
                    </p>
                <?php endif; ?>
            </form>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white py-6 px-4 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
        <p class="text-sm">&copy; <?php echo date("Y"); ?> Compiso. Todos los derechos reservados.</p>
        <a href="https://www.instagram.com/compiso_web" target="_blank" rel="noopener noreferrer"
           class="flex items-center space-x-2 hover:text-mint transition-colors duration-300">
            <svg class="w-5 h-5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.25-.25a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            <span class="text-sm">Instagram</span>
        </a>
    </div>
</footer>
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
        function mostrarOcultar2() {
            var input = document.getElementById("confirmar_contrasena");
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