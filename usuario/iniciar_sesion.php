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
    ?>

    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div>
    <h1>Inicio Sesión</h1>

    <?php 
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $tmp_usuario = depurar($_POST["usuario"]);
                $tmp_contrasena = depurar($_POST["contrasena"]);

                if($tmp_usuario == ''){
                    $err_usuario = "El usuario es obligorio";
                }else{
                    if(strlen($tmp_usuario) < 3 || strlen($tmp_usuario) > 15){
                        $err_usuario = "El usuario no puede contener mas de 15 caracteres ni menos de 3";
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
                    if(strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 15){
                        $err_contrasena = "La contraseña no puede contener mas de 15 caracteres";
                    }else{
                        $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                        if(!preg_match($patron, $tmp_contrasena)){
                            $err_contrasena = "La contraseña debe contener mayusculas, minusculas, algun numero o caractreres especiales";
                        }else{
                            $contrasena = $tmp_contrasena;
                        }
                    }
                }
                if(isset($usuario) && isset($contrasena)){

                        // 1. Preparacion --> le vamos a quitar todas las variables
                        $sql = $_conexion -> prepare("SELECT * FROM usuarios WHERE usuario = ?");

                        // 2. Enlazado 
                        $sql -> bind_param("s", $usuario); 
    
                        // 3. Ejecución
                        $sql -> execute();
    
                        // 4. Obtener/ Retrieve (para select que tenga algún parametro)
                        $resultado = $sql -> get_result();
    
                        if($resultado -> num_rows == 0){
                            $err_usuario = "El usuario $usuario no existe";
                        }else{
                            $datos_usuario = $resultado -> fetch_assoc();
    
                            $acceso_concedido = password_verify($contrasena, $datos_usuario["contrasena"]);
                            if($acceso_concedido){
                                session_start();
                                $_SESSION["usuario"] = $usuario;
                                header("location: ../index.php");
                            }else{
                                $err_contrasena = "La contraseña es incorrecta";
                            }
                        }
                    }
         
                }
            ?>
        <label>Usuario</label> <br>
        <input type="text" size="20px">
        <?php if(isset($err_usuario)) echo "<span class='error'>$err_usuario</span>" ?>
        <br>
        <label>Contraseña</label> <br>
        <input type="text" size="20px">
        <?php if(isset($err_contrasena)) echo "<span class='error'>$err_contrasena</span>" ?>
        <br>
        <input class="btn btn-primary" type="submit" value="Iniciar Sesion">
        <button class="btn btn-secondary"><a href="./index.html">Volver a Inicio</a></button>
    </div>
</body>
</html>