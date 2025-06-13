<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compiso</title>
  <link rel="icon" type="image/jpg" href="/images/logo_compiso.ico" />
  <link rel="stylesheet" href="./css/general.css">

  <?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  session_start();
  ?>
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
      color: white;
      background-image: url("../images/bannerbien.mov");
      background-size: cover;
      background-position: center;
      padding: 60px 20px;
      font-size: 3rem;
      font-weight: bold;
      text-align: center;
      margin: 0;
      width: 100%;
      box-sizing: border-box;
      border-radius: 12px;
    }


    .cards {
      display: flex;
      flex-wrap: nowrap;
      justify-content: center;
      gap: 20px;
      margin: 30px auto;
      padding: 0 20px;
      overflow-x: auto;
    }

    .card {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 250px;
      transition: transform 0.2s;
      cursor: pointer;
      flex-shrink: 0;
      margin: 10px 0;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      font-size: 40px;
      color: rgba(54, 97, 61, 0.77);
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
      background-color: rgba(29, 122, 24, 0.61);
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
  </style>

  <script> window.chtlConfig = { chatbotId: "2783453492" } </script>
  <script async data-id="2783453492" id="chatling-embed-script" type="text/javascript"
    src="https://chatling.ai/js/embed.js"></script>
</head>

<body>
  <header>
    <div>
      <div>
        <a href="./index.php"><img src="./images/logo_compiso.png" alt="Logo" id="logo"></a>
        <h2 id="titulo">Compiso</h2>
      </div>
      <div>
        <a href="./usuario/cerrar_session.php" id="login">Cerrar Sesión</a>
      </div>
    </div>
  </header>

  <div class="dashboard">
    <div class="container">
      <?php
      if (isset($_SESSION["usuario"])) {
        echo "<h1>Bienvenid@ " . $_SESSION["usuario"]["nombre"] . "</h1>";
      } else {
        header("location: usuario/iniciar_sesion.php");
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
        <div class="card-title">Inquilinos</div>
        <div class="card-buttons">
          <button onclick="location.href='./panel_control/buscar_usuarios.php'" class="card-button">Buscar más</button>
          <button onclick="location.href='./matches/matches_usuarios.php'" class="card-button">Mis matches</button>
        </div>
      </div>
      <div class="card">
        <div class="card-icon">🏠</div>
        <div class="card-title">Viviendas</div>
        <div class="card-buttons">
          <button onclick="location.href='./panel_control/pisos.php'" class="card-button">Mis anuncios</button>
          <button onclick="location.href='./panel_control/subir_vivienda.php'" class="card-button">Nuevo
            anuncio</button>
        </div>
      </div>
    </div>

    <div class="container mt-4" style="text-align: center;">
      <button onclick="location.href='./panel_control/comentarios.php'" class="card-button"
        style="padding: 12px 30px; font-size: 16px; margin-top: 20px;">
        ⭐ Puntúanos y deja tu opinión
      </button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>