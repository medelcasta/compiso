<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php 
        require '../util/conexion.php';
        require '../util/depurar.php';
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
    <div>
        <h1>Cambiar Credeciales</h1>
        <?php
        $usuario = $_GET["usuario"];
        /*
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $_conexion -> query($sql);
        */
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
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tmp_usuario = depurar($_POST["usuario"]);
            $tmp_contrasena = depurar($_POST["contrasena"]);

            if($tmp_usuario == ''){
                $err_usuario = "El usuario es obligorio";
            }else{
                if(strlen($tmp_usuario) < 3 || strlen($tmp_usuario) > 15){
                    $err_usuario = "El usuario no puede contener mas de 15 caracteres";
                }else{
                    $patron = "/^[a-zA-Z0-9]+$/";
                    if(!preg_match($patron, $tmp_usuario)){
                        $err_usuario = "El usuario solo puedo contener numeros y letras";
                    }else{
                        $usuario = $tmp_usuario;
                    }
                }
            }
            if($tmp_contrasena == ''){
                $err_contrasena = "La contraseña es obligatoria";
            }else{
                if(strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 255){
                    $err_contrasena = "La contraseña no puede contener mas de 255 caracteres";
                }else{
                    $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                    if(!preg_match($patron, $tmp_contrasena)){
                        $err_contrasena = "La contraseña debe contener mayusculas, minusculas, algun numero y caractreres especiales";
                    }else{
                        $contrasena = $tmp_contrasena;
                    }
                }
            }
            if($tmp_tipo_usuario == ''){
                $err_tipo_usuario = "El tipo de usuario es obligario";
            }else{
                if($tmp_tipo_usuario != 1 && $tmp_tipo_usuario != 2){
                    $err_tipo_usuario = "El tipo de usuario no es correcto";
                }else{
                    $tipo_usuario = $tmp_tipo_usuario;
                }
            }

            if(isset($usuario) && isset($contrasena) && isset($tipo_usuario)){
                
                // 1. Preparacion --> le vamos a quitar todas las variables
                $sql = $_conexion -> prepare("SELECT * FROM usuarios WHERE usuario = ?");

                // 2. Enlazado 
                $sql -> bind_param("s", $usuario); 

                // 3. Ejecución
                $sql -> execute();
                // 4. Obtener/ Retrieve (para select que tenga algún parametro)
                $resultado = $sql -> get_result();

                //var_dump($resultado);

                if($resultado -> num_rows == 0){
                    $err_usuario = "El usuario no existe";
                }else{
                    $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                    /*
                    $sql = "UPDATE usuarios SET
                    contrasena = '$contrasena_cifrada'
                    WHERE usuario = '$usuario'
                    ";

                    $_conexion -> query($sql);
                    */
                    // 1. Preparacion --> le vamos a quitar todas las variables
                    $sql = $_conexion -> prepare("UPDATE usuarios SET
                    constrasena = ?,
                    tipo_usuario = ?
                    WHERE usuario = ? 
                    ");
                    // 2. Enlazado 
                    $sql -> bind_param("sss",
                        $contrasena,
                        $usuario,
                        $tipo_usuario,
                    ); //se pone s si es string e i si es int (si hubiera decimales se pone d)

                    // 3. Ejecución
                    $sql -> execute();
                    $_conexion -> close();

                }
            }
        }         
        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="hidden" class="form-control" type="text" name="usuario" value="<?php echo $usuario ?>">
                <input type="disabled" class="form-control" type="text" name="usuario" value="<?php echo $_SESSION["usuario"] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contrasena" value="<?php echo $contrasena?>">
            </div>
            <select name="tipo_usuario">
                <option value="1">Inquilino</option>
                <option value="2">Propietario</option>
            <select>
            <div class="mb-3">
                <input type="hidden" name="categoria" value="<?php echo $categoria ?>" >
                <input class="btn btn-primary" type="submit" value="Confirmar">
                <a class="btn btn-secondary" href="iniciar_sesion.php">Iniciar Sesion</a>
                <a class="btn btn-secondary" href="../index.php">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>