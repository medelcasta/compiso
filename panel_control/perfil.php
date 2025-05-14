<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    require('../utiles/conexion.php');
    require("../utiles/volver.php");
    ?>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
    <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
        <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
    
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 800px;
            margin: 50px auto;
        }

            h1 {
                text-align: center; /* Centrar el texto */
                color: #343a40; /* Color del texto */
                font-size: 2.5rem; /* Tamaño más grande para el nombre */
                font-weight: bold; /* Hacer el texto más grueso */
                margin-bottom: 20px; /* Espaciado inferior */
            }

    
        
    

            .profile-container {
                display: flex;
                align-items: center; /* Alinear verticalmente la imagen y los detalles */
                gap: 20px; /* Espaciado entre la imagen y los detalles */
            }
        
            .profile-container img {
                flex-shrink: 0; /* Evita que la imagen se reduzca */
                width: 150px; /* Tamaño fijo para la imagen */
                height: auto;
            }
        
            .profile-details {
                flex-grow: 1; /* Permite que los detalles ocupen el espacio restante */
                display: flex;
                flex-direction: column; /* Asegura que los detalles estén en una columna */
                justify-content: center; /* Centra verticalmente los detalles */
            }
        
            .profile-description {
                margin-top: 20px;
                font-size: 16px;
                color: #495057;
                text-align: justify;
            }

    
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
    
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET["id_usuario"])) {
            $id_usuario = $_GET["id_usuario"];
            
            // Consultar la información del usuario
            $sql = $_conexion->prepare("SELECT nombre, apellidos, email, telefono, tipo_usuario, descripcion FROM Usuario WHERE id_usuario = ?");
            $sql->bind_param("s", $id_usuario);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();

                // Convertir tipo_usuario de número a texto
                $tipo_usuario_texto = "";
                if ($usuario["tipo_usuario"] == 1) {
                    $tipo_usuario_texto = "Inquilino";
                } elseif ($usuario["tipo_usuario"] == 2) {
                    $tipo_usuario_texto = "Propietario";
                } else {
                    $tipo_usuario_texto = "Desconocido";
                } 
                echo "<h1>Usuario ";
                if ($usuario["tipo_usuario"] == 1) { 
                    echo '<img src="../images/inquilino.png" width="50px">';
                } elseif ($usuario["tipo_usuario"] == 2) { 
                    echo '<img src="../images/propietario.png" width="50px">';
                } else { 
                    echo '<img src="../images/administrador.png" width="50px">';
                }
                echo "</h1>";
                ?>
                <h4 class="card-title"><?php echo $usuario["nombre"] . ' ' . $usuario["apellidos"]; ?></h4>
                <div>
                    <br>
                    <div>
                        <br>
                        <?php if (!empty($usuario["imagen"])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($usuario["imagen"]); ?>" class="img-thumbnail mb-3" alt="Foto de perfil">
                        <?php else: ?>
                            <img src="../images/mujer.jpg" class="img-thumbnail mb-3" width="100px" alt="Foto de perfil predeterminada">
                        <?php endif; ?>
                        
                    </div>
                </div>
                <div class="profile-details">
                    <p><img src="../images/email.png" width="30px">  <?php echo $usuario["email"]; ?></p>
                    <p><img src="../images/movil.png" width="30px">  <?php echo $usuario["telefono"]; ?></p>
                </div>
            <?php
                
                echo "<p class='profile-description'><strong>Descripción:</strong> " . (!empty($usuario["descripcion"]) ? $usuario["descripcion"] : "Sin descripción") . "</p>";

                // Botón de Enviar Mensaje (sin funcionalidad por el momento)
                echo '<form action="../mensajes/chat.php" method="post">
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Enviar mensaje</button> 
                        <a class="btn btn-secondary" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>
                    </div>
                      </form>'; ?>
                
            <?php } else {
                echo "<p>No se encontró información para el usuario seleccionado.</p>";
            }
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }

        // Cerrar conexión
        $_conexion->close();
        ?>
        
    </div>
</body>

</html>
