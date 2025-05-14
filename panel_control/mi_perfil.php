<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - Compiso</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card-header-custom {
            background-color: rgb(64, 145, 108);
            color: white;
            padding: 20px 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 0;
            margin: 0;
            max-width: 100%;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .profile-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .profile-container img.img-thumbnail {
            width: 150px;
            height: auto;
            flex-shrink: 0;
        }

        .profile-details {
            flex-grow: 1;
        }

        .card-footer-custom {
            background-color: #f1f1f1;
            padding: 15px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_start();

    require('../utiles/conexion.php');
    require('../utiles/depurar.php');
    require("../utiles/volver.php");

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../usuario/iniciar_sesion.php");
        exit;
    }

    $nombre_sesion = $_SESSION["usuario"];

    $sql = $_conexion->prepare("SELECT * FROM Usuario WHERE nombre = ?");
    $sql->bind_param("s", $nombre_sesion);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $nombre = $fila["nombre"];
        $apellidos = $fila["apellidos"];
        $email = $fila["email"];
        $telefono = $fila["telefono"];
        $tipo_usuario = $fila["tipo_usuario"];
        $fecha_nacimiento = $fila["fecha_nacimiento"];
        $sexo = $fila["sexo"];
        $descripcion = $fila["descripcion"];
        $foto = trim(str_replace("uploads/", "", $fila["imagen"])); // Eliminamos "uploads/"
    } else {
        echo "<div class='alert alert-danger'>No se encontró información del usuario.</div>";
        exit;
    }

    // Depuración del valor de $foto
    //var_dump($foto);
    ?>
    
    <div class="container py-5">
        <div class="text-center mb-4">
            <h2 class="text-primary">Bienvenid@ <?php echo htmlspecialchars($nombre_sesion); ?></h2>
            <h1 class="display-5">Mi Perfil</h1>
        </div>

        <div class="card mx-auto shadow-lg" style="max-width: 500px;">
            <div class="card-header-custom">
                <h4 class="card-title">
                    <?php echo htmlspecialchars($nombre . ' ' . $apellidos); ?>
                    <?php 
                        if ($tipo_usuario == 1) {
                            echo '<img src="../images/inquilino.png" width="50px">';
                        } elseif ($tipo_usuario == 2) {
                            echo '<img src="../images/propietario.png" width="50px">';
                        } else {
                            echo '<img src="../images/administrador.png" width="50px">';
                        }
                    ?>
                </h4>
            </div>

            <div class="card-body">
                <div class="profile-container">
                    <div>
                        <?php
                        $ruta_relativa = "../usuario/uploads/" . htmlspecialchars($foto);
                        $ruta_absoluta = $_SERVER['DOCUMENT_ROOT'] . "/usuario/uploads/" . htmlspecialchars($foto);

                        if (!empty($foto)) {
                            if (file_exists($ruta_relativa)) {
                                echo '<img src="' . $ruta_relativa . '" class="img-thumbnail mb-3" alt="Foto de perfil">';
                            } elseif (file_exists($ruta_absoluta)) {
                                echo '<img src="' . $ruta_absoluta . '" class="img-thumbnail mb-3" alt="Foto de perfil">';
                            } else {
                                echo "<p class='text-danger'>❌ Imagen no encontrada en 'uploads'. Verifica que el archivo existe.</p>";
                            }
                        } else {
                            echo "<p class='text-danger'>❌ La variable de imagen está vacía.</p>";
                        }
                        ?>
                    </div>
                    <div class="profile-details">
                        <p><img src="../images/email.png" width="30px"> <?php echo htmlspecialchars($email); ?></p>
                        <p><img src="../images/movil.png" width="30px"> <?php echo htmlspecialchars($telefono); ?></p>
                        <p><img src="../images/tarta.png" width="30px"> <?php echo htmlspecialchars($fecha_nacimiento); ?></p>
                        <p>
                            <?php 
                                if($sexo == 'Mujer'){
                                    echo '<img src="../images/mujer.jpg" width="30px">';
                                } else if($sexo == 'Hombre'){
                                    echo '<img src="../images/hombre.jpg" width="30px">';
                                }
                            ?>
                        </p>
                    </div>
                </div>

                <p class="profile-description"><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($descripcion)); ?></p>
            </div>

            <div class="card-footer-custom">
                <a class="btn btn-secondary mx-2" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
                <a href="../usuario/cambiar_credenciales.php" class="btn btn-primary mx-2">Cambiar credenciales</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

