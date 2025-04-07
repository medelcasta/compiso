<?php

    $_servidor = "sql302.infinityfree.com"; // O "localhost"
    $_usuario = "if0_38638851";
    $_contrasena = "4IbtiTJdujdk";
    $_base_de_datos = "if0_38638851_compiso_db";

    
    $_conexion = new Mysqli($_servidor, $_usuario, $_contrasena, $_base_de_datos)
        or die("Error de conexión");

?>