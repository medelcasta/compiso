<?php
// Función para obtener el enlace correcto de "Volver"
    function obtenerEnlaceVolver() {
        if (isset($_SESSION['tipo_usuario'])) {
            if ($_SESSION['tipo_usuario'] == 1) {
                return '../inicio_inquilino.php';
            } elseif ($_SESSION['tipo_usuario'] == 2) {
                return '../inicio_propietario.php';
            }
        }
        return '../inicio.php'; // Por defecto
    }
    ?>