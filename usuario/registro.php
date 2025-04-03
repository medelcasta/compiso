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
        <h1>Registro</h1>

        <?php 
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $tmp_usuario = depurar($_POST["usuario"]);
                $tmp_contrasena = depurar($_POST["contrasena"]);

                //si se inserta usuario con mismo nombre
                //select * from usuarios num row = 0 (= no se ha encontrado usuario igual) para que te puedas registrar 
                
                if($tmp_usuario == ''){
                    $err_usuario = "El usuario es obligorio";
                }else{
                     // 1. Preparacion --> le vamos a quitar todas las variables
                    $sql = $_conexion -> prepare("SELECT * FROM usuarios WHERE usuario =  ?");

                    // 2. Enlazado 
                    $sql -> bind_param("s", $usuario); 

                    // 3. Ejecución
                    $sql -> execute();

                    // 4. Obtener/ Retrieve (para select que tenga algún parametro)
                    $resultado = $sql -> get_result();

                    if($resultado -> num_rows == 1){
                        $err_usuario = "El usuario $tmp_usuario ya existe";
                    }else{       
                        if(strlen($tmp_usuario) < 3 ||  strlen($tmp_usuario) > 15){
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
                }

                if($tmp_contrasena == ''){
                    $err_contrasena = "La contraseña es obligatoria";
                }else{
                    if(strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 15){
                        $err_contrasena = "La contraseña no puede contener mas de 15 caracteres ni menos de 8";
                    }else{
                        $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                        if(!preg_match($patron, $tmp_contrasena)){
                            $err_contrasena = "La contraseña debe contener mayusculas, minusculas, algun numero o caractreres especiales";
                        }else{
                            $contrasena = $tmp_contrasena;
                            $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                        }
                    }
                }

                if(isset($usuario) && isset($contrasena)){
                    /*
                    $sql = "INSERT INTO usuarios VALUES ('$usuario', '$contrasena_cifrada')";

                    $_conexion -> query($sql);
                    */

                    // 1. Preparacion --> le vamos a quitar todas las variables
                    $sql = $_conexion -> prepare("INSERT INTO  usuarios VALUES (?,?)");

                    // 2. Enlazado 
                    $sql -> bind_param("ss", 
                        $usuario, 
                        $contrasena_cifrada
                    ); //se pone s si es string e i si es int (si hubiera decimales se pone d)

                    // 3. Ejecución
                    $sql -> execute();

                    header("location: iniciar_sesion.php");
                    exit;
                }
            }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label>Usuario</label> <br>
            <input type="text" name="usuario" size="20px">
            <?php if(isset($err_usuario)) echo "<span class='error'>$err_usuario</span>" ?>
            <br>
            <label>Contraseña</label> <br>
            <input type="text" name="contrasena" size="20px">
            <?php if(isset($err_contrasena)) echo "<span class='error'>$err_contrasena</span>" ?>
            <br>
            <button> <a>Registrarse</a></button>
            <a href="./inicio_sesion.html">Ya tengo cuenta</a>
        </form>
    </div>
</body>
</html>