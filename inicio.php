<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);


    session_start();


    ?>
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
        </div>
    </header>

    <div class="container text-center mt-5">
        <?php
        if (isset($_SESSION["usuario"])) {
            echo "<h2>Bienvenid@ " . $_SESSION["usuario"] . "</h2>";
        } else {
            header("location: usuario/iniciar_sesion.php");
            exit;
        }
        ?>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <!-- <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3 sidebar">
                <h4 class="text-white text-center py-3">Mi Panel</h4>
                <ul class="nav flex-column px-3">
                    <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="bi bi-house-door"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-people"></i> Usuarios</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-graph-up"></i> Reportes</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#"><i class="bi bi-gear"></i> Configuración</a>
                    </li>
                </ul>
                </div>
            </nav> -->

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Panel de control</h1>
                    </div>

                    <!-- Cards -->
                    <div class="row g-4">
                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-success">
                                <div class="card-body">
                                    <a href="./panel_control/mi_perfil.php" class="btn btn-success mb-2">Mi perfil</a>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-success">
                                <div class="card-body">
                                    <a href="./panel_control/completa_perfil.php" class="btn btn-success mb-2">Completa
                                        tu perfil</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-primary">
                                <div class="card-body">
                                    <a href="./panel_control/compis.php" class="btn btn-primary mb-2">Compañeros</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-primary">
                                <div class="card-body">
                                    <a href="./matches/mostrar_matches.php" class="btn btn-primary mb-2">Mostrar
                                        matches</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-primary">
                                <div class="card-body">
                                    <a href="./panel_control/buscar_usuarios.php" class="btn btn-primary mb-2">Buscar
                                        Usuarios</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-warning">
                                <div class="card-body">
                                    <a href="./panel_control/pisos.php" class="btn btn-warning mb-2">Pisos</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-warning">
                                <div class="card-body">
                                    <a href="./panel_control/buscar_vivienda.php" class="btn btn-warning mb-2">Buscar
                                        viviendas</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <div class="card text-bg-warning">
                                <div class="card-body">
                                    <a href="./panel_control/subir_vivienda.php" class="btn btn-warning mb-2">Subir
                                        viviendas</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <div class="mt-4">
            <a href="./usuario/cerrar_session.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>