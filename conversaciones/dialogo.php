<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../utiles/conexion.php');
session_start();

if (!isset($_SESSION["usuario"])) {
    echo "No has iniciado sesión.";
    exit;
}

// Verificamos si el valor de la sesión es un array o directamente un ID
$emisor_id = is_array($_SESSION["usuario"]) ? $_SESSION["usuario"]["id_usuario"] : $_SESSION["usuario"];

$receptor_id = isset($_GET["usuario_id"]) ? trim($_GET["usuario_id"]) : '';

if (!$receptor_id || $receptor_id === $emisor_id) {
    echo "ID de receptor inválido.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f9; font-family: Arial, sans-serif; }
        .chat-container {
            max-width: 700px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .mensaje { padding: 10px 15px; margin: 10px 0; border-radius: 20px; max-width: 70%; word-wrap: break-word; }
        .emisor { background-color: #d1e7dd; margin-left: auto; text-align: right; }
        .receptor { background-color: #f8d7da; margin-right: auto; text-align: left; }
        .mensajes { height: 400px; overflow-y: auto; display: flex; flex-direction: column; border: 1px solid #ccc; padding: 15px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="chat-container">
    <h2 class="text-center">Chat con el usuario</h2>
    <div class="mensajes mb-3" id="mensajes"></div>
    <form id="form-mensaje">
        <div class="input-group">
            <input type="text" id="mensaje" name="mensaje" class="form-control" placeholder="Escribe tu mensaje...">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>

<script>
    const receptorId = "<?php echo $receptor_id; ?>";

    // Función para cargar los mensajes del chat
    function cargarMensajes() {
        fetch(`obtener_dialogo.php?usuario_id=${receptorId}`)
            .then(response => response.text())
            .then(data => {
                const contenedor = document.getElementById("mensajes");
                contenedor.innerHTML = data;
                contenedor.scrollTop = contenedor.scrollHeight;
            });
    }

    // Enviar el mensaje al hacer submit en el formulario
    document.getElementById("form-mensaje").addEventListener("submit", function (e) {
        e.preventDefault();
        const input = document.getElementById("mensaje");
        const mensaje = input.value.trim();
        if (mensaje === "") return;

        const formData = new FormData();
        formData.append("mensaje", mensaje);
        formData.append("usuario_id", receptorId);

        // Realizamos la llamada para enviar el mensaje a "enviar_dialogo.php"
        fetch("enviar_dialogo.php", {
            method: "POST",
            body: formData
        }).then(() => {
            input.value = ""; // Limpiamos el campo de entrada de mensaje
            cargarMensajes();  // Recargamos los mensajes para mostrar el nuevo
        });
    });

    // Recargar los mensajes cada 5 segundos
    setInterval(cargarMensajes, 5000);
    cargarMensajes(); // Cargar los mensajes al inicio
</script>
</body>
</html>
