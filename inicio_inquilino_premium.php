<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Inicio Inquilino</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 960px;
      margin: 50px auto;
      padding: 20px;
      text-align: center;
    }

    h1 {
      color: #333;
    }

    .cards {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 30px auto;
      padding: 0 20px;
      max-width: 1200px;
    }

    .card {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 280px;
      transition: transform 0.2s;
      cursor: pointer;
      margin: 10px;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      font-size: 40px;
      color: #FFFFFF;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .card-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 15px;
    }

    .card-button {
      background-color: rgb(115, 196, 156);
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 10px 20px;
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      text-align: center;
    }

    .card-button:hover {
      background-color: rgb(77, 134, 85);
      box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    .card-button:active {
      background-color: #c2185b;
      transform: translateY(0);
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    .bottom-button-container {
      text-align: center;
      margin: 40px 0;
    }
  </style>
  <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
  <link rel="stylesheet" href="./css/general.css">
  <link rel="stylesheet" href="./css/inicio.css">

  <?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  session_start();
  ?>

  <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
  <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
    src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
  <header>
    <div>
      <div>
        <img src="./images/logo_compiso.png" alt="Logo" id="logo">
        <h1 id="titulo">Compiso</h1>
      </div>
      <div>
        <a href="./index.php" id="login">Cerrar Sesión</a>
      </div>
    </div>
  </header>

  <div class="container">
    <?php
    if (isset($_SESSION["usuario"])) {
      echo "<h1>Bienvenid@ " . htmlspecialchars($_SESSION["usuario"]["nombre"]) . "</h1>";
    } else {
      header("Location: usuario/iniciar_sesion.php");
      exit;
    }
    ?>
  </div>

  <div class="cards">
    <div class="card">
      <div class="card-icon">👤</div>
      <div class="card-title">Mi perfil</div>
      <div class="card-buttons">
        <button onclick="location.href='./panel_control/mi_perfil.php'" class="card-button">Mi perfil</button>
        <button onclick="location.href='./panel_control/completa_perfil.php'" class="card-button">Completar
          perfil</button>
      </div>
    </div>
    <div class="card">
      <div class="card-icon">👥</div>
      <div class="card-title">Compañeros</div>
      <div class="card-buttons">
        <button onclick="location.href='./panel_control/buscar_usuarios.php'" class="card-button">Buscar más</button>
        <button onclick="location.href='./matches/matches_usuarios.php'" class="card-button">Mis match</button>
      </div>
    </div>
    <div class="card">
      <div class="card-icon">🏠</div>
      <div class="card-title">Viviendas</div>
      <div class="card-buttons">
        <button onclick="location.href='./panel_control/buscar_vivienda.php'" class="card-button">Buscar más</button>
        <button onclick="location.href='./matches/matches_viviendas.php'" class="card-button">Matches</button>
      </div>
    </div>
  </div>

  <div class="bottom-button-container">
    <button onclick="location.href='./panel_control/comentarios.php'" class="card-button">⭐ Puntúanos y deja tu
      opinión</button>
  </div>

</body>

</html>