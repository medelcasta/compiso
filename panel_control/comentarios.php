<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios y Calificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 20px;
        }
        
        h2, h3 {
            color: #74C69D;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            position: relative;
            font-size: 30px;
        }

        .rating input {
            display: none;
        }

        .rating label {
            color: gray;
            cursor: pointer;
            transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
        }

        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
            transform: scale(1.2);
        }

        .comentarios-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .comentario {
            padding: 10px;
            border-bottom: 1px solid #74C69D;
            text-align: left;
        }

        .comentario:last-child {
            border-bottom: none;
        }

        .comentario strong {
            color: #74C69D;
        }

        button {
            background-color: #74C69D;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #5a9e78;
        }

        textarea {
            width: 80%;
            height: 100px;
            border: 1px solid #74C69D;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    session_start();

    require('../utiles/conexion.php');
    require('../utiles/depurar.php');
    require("../utiles/volver.php");

    if (!isset($_SESSION["usuario"])) {
        header("Location: ../usuario/iniciar_sesion.php");
        exit;
    }

    // PROCESAR ENVÍO DEL FORMULARIO
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usuario = $_SESSION['usuario'];
        $comentario = $_POST['comentario'];
        $num_estrellas = $_POST['num_estrellas'];

        if (!empty($comentario) && !empty($num_estrellas)) {
            $sql_insert = "INSERT INTO Comentarios (id_usuario, comentario, num_estrellas) VALUES (?, ?, ?)";
            $stmt = $_conexion->prepare($sql_insert);
            $stmt->bind_param("ssi", $id_usuario, $comentario, $num_estrellas);

            if ($stmt->execute()) {
                echo "✅ Comentario guardado correctamente.";
            } else {
                echo "❌ Error al guardar el comentario: " . $_conexion->error;
            }

            $stmt->close();
        } else {
            echo "❗ Debes ingresar una calificación y un comentario.";
        }
    }
?>

<a class="btn btn-secondary mx-2" href="<?php echo obtenerEnlaceVolver(); ?>">Volver</a>

<h2>Califica y deja un comentario</h2>

<form action="comentarios.php" method="POST">
    <label>Puntuación:</label>
    <div class="rating">
        <input type="radio" id="estrella5" name="num_estrellas" value="5"><label for="estrella5">★</label>
        <input type="radio" id="estrella4" name="num_estrellas" value="4"><label for="estrella4">★</label>
        <input type="radio" id="estrella3" name="num_estrellas" value="3"><label for="estrella3">★</label>
        <input type="radio" id="estrella2" name="num_estrellas" value="2"><label for="estrella2">★</label>
        <input type="radio" id="estrella1" name="num_estrellas" value="1"><label for="estrella1">★</label>
    </div>
    
    <label>Comentario (opcional):</label>
    <textarea name="comentario" placeholder="Escribe tu comentario..."></textarea>

    <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['usuario']; ?>">
    
    <button type="submit">Enviar</button>
</form>

<h2 style="color: #74C69D; margin-top: 30px;">Otras valoraciones</h2>

<div class="comentarios-container">
<?php
$sql_select = "SELECT comentario, num_estrellas, fecha 
               FROM Comentarios 
               ORDER BY fecha DESC";

$result = $_conexion->query($sql_select);

if (!$result) {
    die("❌ Error en la consulta SQL: " . $_conexion->error);
}

echo "<p>Número de comentarios encontrados: " . $result->num_rows . "</p>"; // Mensaje de depuración

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='comentario'>";
        echo "<span>⭐ " . $row["num_estrellas"] . "/5</span>";
        echo "<p>" . htmlspecialchars($row["comentario"]) . "</p>";
        echo "<small>" . $row["fecha"] . "</small>";
        echo "</div><hr>";
    }
} else {
    echo "<p>No hay comentarios aún.</p>";
}
?>
</div>

</body>
</html>



