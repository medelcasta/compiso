<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php 
        error_reporting( E_ALL );
        ini_set("display_errors", 1 ); 
        require ('../util/conexion.php'); 
        require ('../util/depurar.php');
        session_start();
        if(isset($_SESSION["usuario"])){
            echo "<h2>Bienvenid@ ". $_SESSION["usuario"] ."</h2>";
        }else{
            header("location: usuario/iniciar_sesion.php");
            exit;
        }
    ?>
</head>
<body>
    <h1>Mi perfil</h1>
    <div>
        <?php
        $usuario = $_GET["usuario"];
        $foto = $_GET["foto"];
        $direccion = $_GET["direccion"];
        $telefono = $_GET["telefono"];
        $correo = $_GET["correo"];
        
        // 1. Preparacion --> le vamos a quitar todas las variables
        $sql = $_conexion -> prepare("SELECT * FROM usuarios WHERE usuario = ?");

        // 2. Enlazado 
        $sql -> bind_param("s", $usuario); 

        // 3. Ejecución
        $sql -> execute();

        // 4. Obtener/ Retrieve (para select que tenga algún parametro)
        $resultado = $sql -> get_result();

        while($fila = $resultado -> fetch_assoc()) {
            $usuario = $fila["usuario"];
            $foto = $fila["foto"];
            $direccion = $fila["direccion"];
            $telefono = $fila["telefono"];
            $correo = $fila["correo"];
        }
        ?>
        <h1><?php echo $_SESSION["usuario"]?></h1>
        <h2><?php echo $usuario ?></h2>
        <img src="<?php echo $foto ?>" alt="Foto de perfil" width="100" height="100">
        <p>Dirección: <?php echo $direccion ?></p>
        <p>Teléfono: <?php echo $telefono ?></p>
        <p>Correo: <?php echo $correo ?></p>


        <button><a href="./usuario/cambiar_credenciales.php">Cambiar credenciales</a></button>
    </div>
</body>
</html>