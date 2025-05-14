<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/formularios.css">
    <link rel="stylesheet" href="./css/quienes.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <div>
            <div>
                <a href="./index.php"><img src="./images/logo_compiso.png" alt="Logo" id="logo"></a>
                <h1 id="titulo">Compiso</h1>
            </div>
            <div>
                <a href="./usuario/iniciar_sesion.php" id="login">Iniciar Sesión</a>
                <a href="./usuario/registro.php" id="registro">Registro</a>
            </div>
        </div>
    </header>

    <main class="container mt-5">
        <div class="row">
            <?php
            require('./utiles/conexion.php');

            $sql = "SELECT nombre, imagen FROM Usuario";
            $result = $_conexion->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $foto = trim(str_replace("uploads/", "", $row["imagen"]));
                    $ruta_relativa = "usuario/uploads/" . htmlspecialchars($foto);

                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card shadow-sm">';
                    echo '      <img src="' . $ruta_relativa . '" class="card-img-top" alt="Usuario" style="width: 100%; height: 200px; object-fit: cover;">';
                    echo '      <div class="card-body">';
                    echo '          <h5 class="card-title">' . htmlspecialchars($row["nombre"]) . '</h5>';
                    echo '          <a href="./usuario/iniciar_sesion.php" class="btn btn-primary">Más Info</a>';
                    echo '      </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo "<div class='alert alert-warning'>No se encontraron usuarios.</div>";
            }
            ?>
        </div>

        <br>
        <h2 style="text-align: center;">Nuestro Equipo</h2>

        <div class="team-container">
            <div class="team">
                <img src="./images/aurora.jpg" alt="Persona 1" onclick="mostrar(0)">
                <img src="./images/paula.jpeg" alt="Persona 2" onclick="mostrar(1)">
                <img src="./images/carlos.jpeg" alt="Persona 3" onclick="mostrar(2)">
                <img src="./images/luis.jpeg" alt="Persona 4" onclick="mostrar(3)">
            </div>
            <div class="team-description">
                <p>
                    Somos un equipo apasionado y dedicado, comprometido con la excelencia y la innovación. 
                    Nuestra historia comenzó con una visión compartida: crear soluciones que marquen la diferencia. 
                    A lo largo de los años, hemos trabajado juntos para superar desafíos y alcanzar metas, 
                    siempre guiados por nuestros valores fundamentales de colaboración, creatividad y perseverancia.
                </p>
            </div>
        </div>

        <br><br>
        <div class="form-container">
            <h4>Contacta con Nosotros</h4>
            <form action="" method="post">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" required>
                <label>Mensaje</label><br>
                <textarea name="mensaje" placeholder="Mensaje" required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>

        <h2 style="color: #74C69D; text-align: center; margin-top: 30px;">Valoraciones</h2>

        <div class="comentarios-container">
            <?php
            require('./utiles/conexion.php');

            $sql = "SELECT comentario, num_estrellas, fecha FROM Comentarios ORDER BY fecha DESC";
            $result = $_conexion->query($sql);

            if (!$result) {
                die("❌ Error en la consulta SQL: " . $_conexion->error);
            }

            echo "<p>Número de comentarios encontrados: " . $result->num_rows . "</p>"; // Mensaje de depuración

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='comentario'>";
                    echo "<span>⭐ " . $row["num_estrellas"] . "/5</span>";
                    echo "<p>" . htmlspecialchars($row["comentario"]) . "</p>";
                    echo "<small>" . $row["fecha"] . "</small>";
                    echo "</div><hr>";
                }
            } else {
                echo "<p>No hay valoraciones aún.</p>";
            }
            ?>
        </div>

    </main>

    <footer>
        <p>&copy; 2025 Compiso. Todos los derechos reservados.</p>
    </footer>
</body>
</html>



