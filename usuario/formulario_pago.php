<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-container input, .form-container select, .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            background-color: #0070ba;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #005ea6;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Formulario de Pago</h2>
        <form action="procesar_pago.php" method="POST">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" required placeholder="Introduce tu correo electrónico">

            <label for="metodo_pago">Método de Pago:</label>
            <select name="metodo_pago" id="metodo_pago" required>
                <option value="paypal">PayPal</option>
            </select>

            <input type="hidden" name="precio" value="10.00"> <!-- Precio fijo o dinámico -->

            <button type="submit"><a href="./procesar_pago.php">Pagar</a></button>
        </form>
    </div>
</body>
</html>
