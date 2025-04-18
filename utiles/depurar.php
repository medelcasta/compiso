<?php
    function depurar($entrada) : string{   
        $salida = htmlspecialchars($entrada);//esto nos pone en modo texto cualquier cosa por si nos mete scripts y demas
        $salida = trim($salida); // esto lo que hace es quitar los espacios de los laterales
        $salida = stripslashes($salida); // esto te quita muchos \ que te puedan hacer bugs dentro de la aplicacion.
        $salida = preg_replace('!\s!', ' ', $salida); //esto nos quita todos los espacios sobrantes dentro de la cadena
        return $salida;
    }
?>