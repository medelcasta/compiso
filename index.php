<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico"/>
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
<script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
</head>
<body>
    <header class="bg-primary text-white py-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Compiso</h1>
            <div>
                <a href="./usuario/iniciar_sesion.php" class="btn btn-light me-2">Iniciar Sesión</a>
                <a href="./usuario/registro.php" class="btn btn-outline-light">Registro</a>
            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Compiso</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sobre nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <div class="row">
            <?php
                require('./utiles/conexion.php');

                $sql = "SELECT nombre FROM Usuario"; 
                $result = $_conexion->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '  <div class="card shadow-sm">';
                        echo '      <img src="./images/foto1.jpg" class="card-img-top" alt="Usuario">';
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

                $_conexion->close();
            ?>
        </div>
    </main>

    <footer class="bg-light text-center py-4 mt-5 border-top">
        <div class="container">
            <p class="mb-0">&copy; 2025 Compiso. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
