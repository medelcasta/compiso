<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compiso</title>
    <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico"/>
    <link rel="stylesheet" href="./css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="header">Compiso</h1>
        <button><a href="./usuario/iniciar_sesion.php">Iniciar Sesion</a></button>
        <button><a href="./usuario/registro.php">Registro</a></button>
        <nav class="menu">
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Sobre nosotros</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </nav>
        <div class="row">
            <?php
     
                require('./utiles/conexion.php');


                $sql = "SELECT nombre FROM Usuario"; 
                $result = $_conexion->query($sql);

    
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card" style="width: 18rem;">';
                        echo '<img src="./images/foto1.jpg" class="card-img-top" alt="...">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row["nombre"] . '</h5>';
                        echo '<a href="./inicio_sesion.html" class="btn btn-primary">Más Info</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No se encontraron usuarios.";
                }

                $_conexion->close();
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>