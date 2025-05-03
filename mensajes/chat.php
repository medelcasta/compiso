<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require('../utiles/conexion.php');
require("../utiles/volver.php");

session_start();
if (!isset($_SESSION["usuario"])) {
    echo "No has iniciado sesión.";
    exit;
}
$usuario = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat Simple</title>
    <style>
        #chat-box { border: 1px solid #ccc; height: 300px; overflow-y: scroll; padding: 10px; margin-bottom: 10px; }
    </style>
    <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
</head>
<body>

<h2>Chat entre Usuarios</h2>

<div id="chat-box"></div>

<!-- Usuario actual (oculto para JS) -->
<input type="hidden" id="username" value="<?php echo $usuario ?>">

<!-- Campo de entrada del mensaje -->
<input type="text" id="message" placeholder="Escribe un mensaje">
<button onclick="sendMessage()">Enviar</button>

<script>
function loadMessages() {
    fetch('get_messages.php')
    .then(response => response.json())
    .then(data => {
        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = '';
        data.reverse().forEach(msg => {
            chatBox.innerHTML += `<p><strong>${msg.username}:</strong> ${msg.message} <small>(${msg.created_at})</small></p>`;
        });
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

function sendMessage() {
    const username = document.getElementById('username').value;
    const message = document.getElementById('message').value;

    if (username && message) {
        const formData = new FormData();
        formData.append('username', username);
        formData.append('message', message);

        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(() => {
            document.getElementById('message').value = '';
            loadMessages();
        });
    }
}

setInterval(loadMessages, 2000); // Recarga cada 2 segundos
loadMessages();
</script>

</body>
</html>
