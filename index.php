<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
        src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
    <header class="cabecera">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="./images/logo_compiso.png" alt="Logo" style="width: 50px;" class="rounded">
                <h1 class="ms-2 fw-bold text-white">Compiso</h1>
            </div>
            <div>
                <a href="./usuario/iniciar_sesion.php" class="btn btn-light me-2" id="login">Iniciar Sesión</a>
                <a href="./usuario/registro.php" class="btn btn-outline-light me-2" id="registro">Registro</a>
            </div>
        </div>
    </header>

    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Compiso</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Sobre nosotros</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                </ul>
            </div>
        </div>
    </nav> -->

    <main class="container mt-5">
        <div class="row">
            <?php
            require('./utiles/conexion.php');

            $sql = "SELECT nombre FROM Usuario";
            $result = $_conexion->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card shadow-sm">';
                    echo '      <img src="./images/foto1.jpg" class="card-img-top" alt="Usuario">';
                    echo '      <div class="card-body">';
                    echo '          <h5 class="card-title">' . htmlspecialchars($row["nombre"]) . '</h5>';
                    echo '          <a href="./usuario/iniciar_sesion.php" class="btn custom-btn" id="masinfo">Más Info</a>';
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

        <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./images/foto1.jpg" class="d-block w-100 h-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>First slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./images/foto2.jpg" class="d-block w-100 h-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Some representative placeholder content for the second slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./images/foto3.jpg" class="d-block w-100 h-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the third slide.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

        </div>
        <h4>Contacta con Nosotros</h4>
        <form action="" method="post">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $tmp_email = depurar($_POST["email"]);
                $tmp_nombre = depurar($_POST["nombre"]);
                $tmp_mensaje = depurar($_POST["mensaje"]);

                if ($tmp_email == '') {
                    $err_email = "El email es obligatorio";
                } else {
                    if (!filter_var($tmp_email, FILTER_VALIDATE_EMAIL)) {
                        $err_email = "Formato de email no válido";
                    } else {
                        $email = $tmp_email;
                    }
                }
                if ($tmp_nombre == '') {
                    $err_nombre = "El nombre es obligatorio";
                } else {
                    if (strlen($tmp_nombre) < 3 || strlen($tmp_nombre) > 50) {
                        $err_nombre = "El nombre no puede contener más de 50 caracteres";
                    } else {
                        $nombre = $tmp_nombre;
                    }
                }
                if ($tmp_mensaje == '') {
                    $err_mensaje = "El mensaje es obligatorio";
                } else {
                    if (strlen($tmp_mensaje) < 10 || strlen($tmp_mensaje) > 500) {
                        $err_mensaje = "El mensaje no puede contener más de 500 caracteres";
                    } else {
                        $mensaje = $tmp_mensaje;
                    }
                }
                if (isset($email) && isset($nombre) && isset($mensaje)) {
                    echo "<div class='alert alert-success'>Mensaje enviado correctamente.</div>";
                }
            }
            ?>

            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            <?php if (isset($err_nombre))
                echo "<span class='text-danger'>$err_nombre</span>"; ?> <br>
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <?php if (isset($err_email))
                echo "<span class='text-danger'>$err_email</span>"; ?> <br>
            <label>Mensaje</label>
            <textarea name="mensaje" class="form-control" placeholder="Mensaje" required></textarea>
            <?php if (isset($err_mensaje))
                echo "<span class='text-danger'>$err_mensaje</span>"; ?> <br>
            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
        </form>
    </main>

    <footer class="bg-light py-4 mt-5 border-top">
        <div class="container">
            <p class="text-black">&copy; 2025 Compiso. Todos los derechos reservados.</p>
            <p class="text-black"><a class="nav-link" href="#">Sobre nosotros</a></p>
            <p class="text-black"><a class="nav-link" href="#">Contacto</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>